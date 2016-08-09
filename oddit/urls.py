#from django.conf.urls import patterns, url, include
from django.contrib import admin
from django.conf.urls import *
admin.autodiscover()

urlpatterns = patterns('',
    (r'^admin/doc/', include('django.contrib.admindocs.urls')),
    (r'^admin/', include(admin.site.urls)),
    (r'jobs/', include('job.urls')),
    (r'^', include('job.urls')),
    (r'^search/', include('haystack.urls')),
    url(r'^accounts/', include('allauth.urls')),
)
