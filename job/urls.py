from django.conf.urls.defaults import *
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
)

if settings.DEBUG:
    urlpatterns += patterns('',
        url(r'^static/(?P<path>.*)$', 'django.views.static.serve',
            {'document_root': settings.MEDIA_ROOT,}),
    )
