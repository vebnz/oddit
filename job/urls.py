#from django.conf.urls import patterns, url, include
from django.conf.urls import *
from django.conf import settings

urlpatterns = patterns('',
    (r'^$', 'job.views.index'),
    (r'^type/(?P<type_name>[\w|\W]+)/$', 'job.views.index'),
    (r'^tags/(?P<tag_name>[\w|\W]+)/$', 'job.views.index'),
    (r'^category/(?P<category_name>[\w|\W]+)/$', 'job.views.index'),
    (r'^category/(?P<category_name>\w+)/type/(?P<type_name>\w+)/$', 'job.views.index'),
    (r'^show/(?P<job_name>[\w|\W]+)/(?P<job_id>\d+)/$', 'job.views.detail'),
    (r'^apply/(?P<job_id>\d+)/$', 'job.views.apply_job'),
    (r'^search$', 'job.views.results_search'),
    (r'^new/region$', 'job.views.update_region'),
    (r'^new$', 'job.views.new_job'),
    (r'^about-us$', 'job.views.about_us'),
    (r'^applied-for$', 'job.views.applied'),
    (r'^my-jobs$', 'job.views.my_jobs'),
	(r'^edit/(?P<job_id>\d+)/$', 'job.views.edit_job'),
	(r'^expire/(?P<job_id>\d+)/$', 'job.views.expire_job'),
	(r'^applications/(?P<job_name>[\w|\W]+)/(?P<job_id>\d+)/$', 'job.views.applications'),
    (r'^my-profile$', 'job.views.profile'),
    (r'^settings$', 'job.views.settings'),
)

if settings.DEBUG:
    urlpatterns += patterns('',
        url(r'^static/(?P<path>.*)$', 'django.views.static.serve',
            {'document_root': settings.MEDIA_ROOT,}),
    )
