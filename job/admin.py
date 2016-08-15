from job.models import Job, Category, Region, City, JobType
from job.models import UserProfile, JobApply
from django.contrib import admin
from django import forms

class JobAdmin(admin.ModelAdmin):
    list_display = ('title', 'category', 'created')
    search_fields = ['title',]

admin.site.register(Job, JobAdmin)
admin.site.register(Category)
admin.site.register(Region)
admin.site.register(City)
admin.site.register(JobType)
admin.site.register(UserProfile)
admin.site.register(JobApply)
