# Create your views here.
import operator, datetime, sys

from django.template import Context, loader, RequestContext
from django.template.loader import render_to_string
from job.models import Job, Category, JobType, Tag, City, JobApply, User
from django.db.models import Count
from django.http import HttpResponse, HttpResponseRedirect, HttpResponseForbidden
from django.shortcuts import render_to_response, get_object_or_404
from django.core.paginator import Paginator, EmptyPage, PageNotAnInteger
from django.core.exceptions import ObjectDoesNotExist
from django.core.mail import send_mail
from job.forms import JobForm, ApplyForm, UserProfileForm
from django.template.context_processors import csrf
from django.views.decorators.csrf import csrf_protect
from django.contrib.auth.decorators import login_required
from django.http import Http404

from  django.utils.datastructures  import  MultiValueDictKeyError

def index(request, category_name='all', type_name='all'):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id")).distinct()
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]

    category_name = category_name.replace("-", " ") # url is slugified
    type_name = type_name.replace("-", " ")
    if category_name and category_name != 'all' and type_name and type_name == 'all':
        latest_job_list = Job.objects.filter(status=1,category__name__iexact=category_name).order_by('created')
    elif category_name and category_name != 'all' and type_name and type_name != 'all':
        latest_job_list = Job.objects.filter(status=1,category__name__iexact=category_name,type__name__iexact=type_name).order_by('created')
    elif  category_name and category_name == 'all' and type_name and type_name != 'all':
        latest_job_list = Job.objects.filter(status=1,type__name__iexact=type_name).order_by('created')
    else:
        latest_job_list = Job.objects.filter(status=1).order_by('created')

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
                                                  'type_name': type_name,
                                                 }, context_instance=RequestContext(request))

def detail(request, job_id, job_name):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id")).distinct()
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]

    job = get_object_or_404(Job, pk=job_id)

    job_tags = job.tag_list.split(',');

    try:
        checkApply = JobApply.objects.get(user=request.user.id, job=job)
    except JobApply.DoesNotExist:
        checkApply = None

    return render_to_response('jobs/job.html', {
        'job': job,
        'job_tags': job_tags,
        'applied': checkApply,
        'categories': category_list,
        'popular_categories': popular_categories_list,
        'popular_tags': popular_tags,},
        context_instance=RequestContext(request))


def results_search(request):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id")).distinct()
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]
    job_types = JobType.objects.all()


    try:
        query = request.GET['query']
    except (InvalidQuery, MultiValueDictKeyError), e:
        return render_to_response('jobs/search_results.html', {
                                                           'categories': category_list,
                                                           'popular_categories': popular_categories_list,
                                                           'job_types': job_types,
                                                           'popular_tags':
                                                           popular_tags}, context_instance=RequestContext(request))

    total_jobs = Job.objects.count()
    job_types = JobType.objects.all()

    jobs = Job.objects.filter(title__icontains=query) | \
            Job.objects.filter(tag_list__icontains=query) | \
            Job.objects.filter(description__icontains=query)

    total_jobs = Job.objects.count()

    return render_to_response('jobs/search_results.html', {'query': query,
                                                           'job_list': jobs,
                                                           'categories': category_list,
                                                           'popular_categories': popular_categories_list,
                                                           'total_jobs': total_jobs,
                                                           'job_types': job_types,
                                                           'popular_tags':
                                                           popular_tags}, context_instance=RequestContext(request))


@login_required
def apply_job(request, job_id):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id"))
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]

    j = get_object_or_404(Job, pk=job_id)
    try:
       checkApply = JobApply.objects.get(user=request.user, job=j)
       errors = 'You have already applied for this job'
       return render_to_response('jobs/apply_job.html',  {
           'errors' : errors,
           'popular_categories': popular_categories_list,
           'popular_tags': popular_tags,
           'categories': category_list,
           'job': j,},
            context_instance=RequestContext(request))


    except JobApply.DoesNotExist:
       app = JobApply(user=request.user, job=j)

       if request.method == 'POST':
          errors = 'aaaa'
          form = ApplyForm(request.POST, request.FILES, instance=app, user=request.user, job=j)

          msg = 'Dear ' + j.user.first_name + ',\n\n' + app.user.first_name + ' ' + app.user.last_name + ' has applied for your job "' + j.title + '".\n\nDownload their CV now and get in touch' 

          if form.is_valid():
             form.save()

             msg_plain = render_to_string('jobapplicationemail.txt', {'j': j, 'app': app})
             msg_html  = render_to_string('jobapplicationemail.html', {'j': j, 'app': app})

             send_mail('Someone applied for your Job ', msg_plain, 'mike@oddit.co.nz', [j.user.email], html_message=msg_html, fail_silently=False)
             return HttpResponseRedirect('/jobs/applied-for')
          else:
            print "JobApply fucked\n"
       else:
          form = ApplyForm(instance=app, user=request.user, job=j)

       return render_to_response('jobs/apply_job.html',  {
           'form' : form,
           'popular_categories': popular_categories_list,
           'popular_tags': popular_tags,
           'categories': category_list,
           'job': j,},
            context_instance=RequestContext(request))

@login_required
def applied(request):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id"))
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]

    applied = JobApply.objects.filter(user=request.user).values_list('job_id', flat=True)
    jobs = Job.objects.filter(id__in=applied)

    return render_to_response('jobs/applied.html',  {
        'popular_categories': popular_categories_list,
        'popular_tags': popular_tags,
        'categories': category_list,
        'job_list': jobs,},
        context_instance=RequestContext(request))

@login_required
def my_jobs(request):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id"))
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]

    myjobs = Job.objects.filter(user=request.user)

    return render_to_response('jobs/my_jobs.html', {
        'popular_categories': popular_categories_list,
        'popular_tags': popular_tags,
        'categories': category_list,
        'job_list': myjobs,},
        context_instance=RequestContext(request))

@login_required
def applications(request, job_id, job_name):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id")).distinct()
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]

    job = get_object_or_404(Job, pk=job_id)

    if job.user != request.user:
        return HttpResponseForbidden()

    apps = None
    try:
        apps = JobApply.objects.filter(job=job)
    except Job.DoesNotExist:
        apps = None

    return render_to_response('jobs/applications.html', {
        'job': job,
        'applications': apps,
        'categories': category_list,
        'popular_categories': popular_categories_list,
        'popular_tags': popular_tags,},
        context_instance=RequestContext(request))

@login_required
def edit_job(request, job_id):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id"))
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]

    job = get_object_or_404(Job, pk=job_id)

    if job.user != request.user:
        return HttpResponseForbidden()

    if request.POST:
        form = JobForm(request.POST, instance=job, user=request.user)
        if form.is_valid():
            form.save()
            return HttpResponseRedirect('/jobs/my-jobs')
    else:
        form = JobForm(instance=job, user=request.user)

    return render_to_response('jobs/new_job.html',  {
        'form' : form,
        'popular_categories': popular_categories_list,
        'popular_tags': popular_tags,
        'categories': category_list,},
        context_instance=RequestContext(request))

@login_required
def expire_job(request, job_id):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id"))
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]

    job = get_object_or_404(Job, pk=job_id)

    if job.user != request.user:
        return HttpResponseForbidden()

    if request.method == 'POST':
        form = JobForm(data=request.POST, instance=job, user=request.user)
        if 'no' in request.POST:
            return HttpResponseRedirect('/jobs/my-jobs')
        elif 'yes' in request.POST:
            print 'expiring job'
            job.status = 2
            job.save()
            return HttpResponseRedirect('/jobs/my-jobs')
    else:
        form = JobForm(instance=job, user=request.user)

    return render_to_response('jobs/expire_job.html',  {
        'form' : form,
        'popular_categories': popular_categories_list,
        'popular_tags': popular_tags,
        'categories': category_list},
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
            return HttpResponseRedirect('/jobs/')
            print "something fucked!"
    else:
        form = JobForm(instance=job, user=request.user)

    return render_to_response('jobs/new_job.html',  {
        'form' : form,
        'popular_categories': popular_categories_list,
        'popular_tags': popular_tags,
        'categories': category_list},
        context_instance=RequestContext(request))

def view_profile(request, user_id):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id")).distinct()
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]

    user = get_object_or_404(User, pk=user_id)

    return render_to_response('jobs/profile.html',  {
        'view_user' : user,
        'popular_categories': popular_categories_list,
        'popular_tags': popular_tags,
        'categories': category_list},
        context_instance=RequestContext(request))

@login_required
def profile(request):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id")).distinct()
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]

    user = get_object_or_404(User, pk=request.user.id)

    return render_to_response('jobs/profile.html',  {
        'view_user' : user,
        'popular_categories': popular_categories_list,
        'popular_tags': popular_tags,
        'categories': category_list},
        context_instance=RequestContext(request))

@login_required
def settings(request):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id")).distinct()
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]

    if request.method == 'POST':
        form = UserProfileForm(request.POST, instance=request.user.profile)
        form.user = request.user
        if form.is_valid():
            form.save()
            return HttpResponseRedirect('/my-profile')
    else:
        form = UserProfileForm(instance=request.user.profile)

    return render_to_response('jobs/settings.html',  {
        'form' : form,
        'popular_categories': popular_categories_list,
        'popular_tags': popular_tags,
        'categories': category_list},
        context_instance=RequestContext(request))

def about_us(request):
    popular_categories_list = Job.objects.values('category', 'category__name').annotate(num_jobs=Count("id")).distinct()
    popular_tags = Tag.objects.usage_for_model(Job, counts=True)[:5]
    popular_tags.sort(key=operator.attrgetter('count'), reverse=True)
    category_list = Category.objects.all()[:10]

    return render_to_response('jobs/about_us.html',  {
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
