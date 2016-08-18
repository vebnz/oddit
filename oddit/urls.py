#from django.conf.urls import patterns, url, include
from django.contrib import admin, admindocs
from django.conf.urls import *
import job.views as jobs
admin.autodiscover()

urlpatterns = [
    url(r'^jet/dashboard/', include('jet.dashboard.urls', 'jet-dashboard')),  # Django JET dashboard URLS
    url(r'^jet/', include('jet.urls', 'jet')),
    url(r'^admin/doc/', include('django.contrib.admindocs.urls')),
    url(r'^admin/', include(admin.site.urls)),
    url(r'^tinymce/', include('tinymce.urls')),
    url(r'jobs/', include('job.urls')),
    url(r'^', include('job.urls')),
    url(r'^search/', include('haystack.urls')),
    url(r'^accounts/', include('allauth.urls')),
    url(r'^my-profile$', jobs.profile),
    url(r'^my-profile/edit$', jobs.settings),
    url(r'^profile/(?P<user_id>\d+)/$$', jobs.view_profile),
]
