from django.conf.urls import patterns, url, include
from django.contrib import admin

admin.autodiscover()

urlpatterns = patterns('',
    (r'^admin/doc/', include('django.contrib.admindocs.urls')),
    (r'^admin/', include(admin.site.urls)),
    (r'^jobs/', include('job.urls')),
    (r'^accounts/', include('registration.urls')),
    url(r'', include('social_auth.urls')),
)
