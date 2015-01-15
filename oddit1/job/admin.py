from job.models import Job, Category, Company, Region, City, JobType
from job.models import UserProfile, JobApply
from django.contrib import admin
from django import forms

class JobAdmin(admin.ModelAdmin):
    list_display = ('title', 'company', 'category', 'created')
    search_fields = ['title', 'company']

admin.site.register(Job, JobAdmin)
admin.site.register(Category)
admin.site.register(Company)
admin.site.register(Region)
admin.site.register(City)
admin.site.register(JobType)
admin.site.register(UserProfile)
admin.site.register(JobApply)
