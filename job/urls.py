#from django.conf.urls import patterns, url, include
from django.conf.urls import *
from django.conf import settings

from django.views import static
import job.views as jobs
import notification.views as notifications

urlpatterns = [
    url(r'^$', jobs.index),
    url(r'^type/(?P<type_name>[\w|\W]+)/$', jobs.index),
    url(r'^tags/(?P<tag_name>[\w|\W]+)/$', jobs.index),
    url(r'^category/(?P<category_name>[\w|\W]+)/$', jobs.index),
    url(r'^category/(?P<category_name>\w+)/type/(?P<type_name>\w+)/$', jobs.index),
    url(r'^show/(?P<job_name>[\w|\W]+)/(?P<job_id>\d+)/$', jobs.detail),
    url(r'^apply/(?P<job_id>\d+)/$', jobs.apply_job),
    url(r'^search$', jobs.results_search),
    url(r'^new/region$', jobs.update_region),
    url(r'^new$', jobs.new_job),
    url(r'^about-us$', jobs.about_us),
    url(r'^applied-for$', jobs.applied),
    url(r'^my-jobs$', jobs.my_jobs),
	url(r'^edit/(?P<job_id>\d+)/$', jobs.edit_job),
	url(r'^expire/(?P<job_id>\d+)/$', jobs.expire_job),
	url(r'^applications/(?P<job_name>[\w|\W]+)/(?P<job_id>\d+)/$', jobs.applications),
    url(r'^dump/', notifications.view_message_dump),
]

if settings.DEBUG:
    urlpatterns += [
        url(r'^static/(?P<path>.*)$', static.serve,
            {'document_root': settings.MEDIA_ROOT,})
    ]
