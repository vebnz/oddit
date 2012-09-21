# Create your views here.
import operator, datetime, sys

from django.template import Context, loader, RequestContext
from job.models import Job, Company, Category, JobType, Tag, City, JobApply
from django.db.models import Count
from django.http import HttpResponse, HttpResponseRedirect
from django.shortcuts import render_to_response, get_object_or_404
from django.core.paginator import Paginator, EmptyPage, PageNotAnInteger
from indextank.client import ApiClient
from django.core.exceptions import ObjectDoesNotExist
from job.forms import JobForm, ApplyForm
from django.core.context_processors import csrf
from django.views.decorators.csrf import csrf_protect
from django.contrib.auth.decorators import login_required

def index(request, category_name='all', type_name='all'):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id")).distinct()
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]

    category_name = category_name.replace("-", " ") # url is slugified
    type_name = type_name.replace("-", " ")
    if category_name and category_name != 'all' and type_name and type_name == 'all':
        latest_job_list = Job.objects.filter(category__name__iexact=category_name).order_by('created')
    elif category_name and category_name != 'all' and type_name and type_name != 'all':
        latest_job_list = Job.objects.filter(category__name__iexact=category_name,type__name__iexact=type_name).order_by('created')
    elif  category_name and category_name == 'all' and type_name and type_name != 'all':
        latest_job_list = Job.objects.filter(type__name__iexact=type_name).order_by('created')
    else:
        latest_job_list = Job.objects.all().order_by('created')

    total_jobs = Job.objects.count()
    job_types = JobType.objects.all()

    paginator = Paginator(latest_job_list, 10)

    page = request.GET.get('page')
    try:
        latest_job_list = paginator.page(page)
    except PageNotAnInteger:
        latest_job_list = paginator.page(1)
    except EmptyPage:
        latest_job_list = paginator.page(paginator.num_pages)
    except:
        latest_job_list = paginator.page(1)

    return render_to_response('jobs/index.html', {'latest_job_list': latest_job_list,
                                                  'categories': category_list,
                                                  'popular_categories': popular_categories_list,
                                                  'total_jobs': total_jobs,
                                                  'job_types': job_types,
                                                  'popular_tags': popular_tags,
						  'category_name': category_name,
                                                 }, context_instance=RequestContext(request))

def detail(request, job_id, job_name):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id")).distinct()
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]
    p = get_object_or_404(Job, pk=job_id)
    return render_to_response('jobs/job.html', {'job': p,
                                                'company': p.title,
                                                'categories': category_list,
                                                'popular_categories': popular_categories_list,
                                                'popular_tags': popular_tags,
                                               })


API_URL = 'http://:twdfy1QVjimypE@61rg.api.searchify.com'
INDEX_NAME = 'jobs'

def results_search(request):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id")).distinct()
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]
    query = request.GET['query']
    api = ApiClient(API_URL)
    index = api.get_index(INDEX_NAME)
    search_result = index.search(query, fetch_fields=['name'])

    total_jobs = Job.objects.count()
    job_types = JobType.objects.all()

    entry_list = []
    for result in search_result['results']:
        try:
            entry_object = Job.objects.get(pk=result['docid'])
            entry_list.append(entry_object)
        except ObjectDoesNotExist, e:
            pass

    total_jobs = Job.objects.count()
    job_types = JobType.objects.all()

    return render_to_response('jobs/search_results.html', {'query': query,
                                                           'job_list': entry_list,
                                                           'categories': category_list,
                                                           'popular_categories': popular_categories_list,
                                                           'total_jobs': total_jobs,
                                                           'job_types': job_types,
                                                           'popular_tags': popular_tags})


@login_required
def apply_job(request, job_id):
    popular_categories_list = Job.objects.values('category',
                                                 'category__name').annotate(num_jobs=Count("id"))
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]
    j = get_object_or_404(Job, pk=job_id)
    try:
       checkApply = JobApply.objects.get(user=request.user, job=j)
       error = 'You have already applied for this job'
       return render_to_response('jobs/apply_job.html',  {'error' : error,
                                                       'popular_categories': popular_categories_list,
                                                       'popular_tags': popular_tags,
                                                       'categories': category_list,
                                                        'job': j,},
                               context_instance=RequestContext(request))


    except JobApply.DoesNotExist:

       app = JobApply(user=request.user, job=j)
       if request.method == 'POST':
          print request.FILES['resume']
          form = ApplyForm(request.POST, request.FILES, instance=app, user=request.user, job=j)
          if form.is_valid():
             form.save()
             return HttpResponseRedirect('/jobs/')
          else:
            print 'something fucked'
       else:
          form = ApplyForm(instance=app, user=request.user, job=j)

       return render_to_response('jobs/apply_job.html',  {'form' : form,
                                                       'popular_categories': popular_categories_list,
                                                      'popular_tags': popular_tags,
                                                       'categories': category_list,
                                                        'job': j,},
                               context_instance=RequestContext(request))

@login_required
def new_job(request):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id")).distinct()
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]

    job = Job()
    if request.method == 'POST':
        form = JobForm(data=request.POST, instance=job, user=request.user)
        if form.is_valid():
            form.save()
            return HttpResponseRedirect('/jobs/')
        else:
            print 'something fucked'
    else:
        form = JobForm(instance=job, user=request.user)

    return render_to_response('jobs/new_job.html',  {'form' : form,
                                                     'popular_categories': popular_categories_list,
                                                     'popular_tags': popular_tags,
                                                     'categories': category_list},
                              context_instance=RequestContext(request))

def update_region(request):
    ci = City.objects.filter(region=request.POST['id'])
    city_list = []

    for city in ci:
        city_list.append(city.id)
        city_list.append(":")
        city_list.append(city)
        city_list.append("|")

    return HttpResponse(city_list)
