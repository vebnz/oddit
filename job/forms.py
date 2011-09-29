from django import forms
from job.models import Job, JobApply
from django.forms import CharField
from django.contrib.auth.models import User

class JobForm(forms.ModelForm):
    def __init__(self, *args, **kwargs):
        self.user = kwargs.pop('user', None)
        super(JobForm, self).__init__(*args, **kwargs)

    def save(self, commit=True):
        instance = super(JobForm, self).save(commit=False)
        if self.user:
            instance.user = self.user
            return instance.save()

    # over-ride anything here
    company = CharField(label='Company Name')
    title = CharField(label='Job Title')

    class Meta:
        model = Job
        widgets = {
            'user'	: forms.Select(attrs={'class' : 'styled'}),
            'category' : forms.Select(attrs={'class' : 'styled'}),
            'region' : forms.Select(attrs={'class' : 'styled'}),
            'city' : forms.Select(attrs={'class' : 'styled'}),
            'remote' : forms.Select(attrs={'class' : 'styled'}),
            'type' : forms.Select(attrs={'class' : 'styled'}),
            'description' : forms.Textarea(attrs={'class' : 'validation'}),
        }
        exclude = ('bold', 'featured', 'ip', 'user')

class ApplyForm(forms.ModelForm):
    def __init__(self, *args, **kwargs):
        self.user = kwargs.pop('user', None)
        self.job = kwargs.pop('job', None)
        super(ApplyForm, self).__init__(*args, **kwargs)

    def save(self, commit=True):
        instance = super(ApplyForm, self).save(commit=False)
        if self.user:
            instance.user = self.user
            return instance.save()
        if self.job:
            instance.job = self.job
            return instance.save()

    class Meta:
        model = JobApply

        exclude = 'user, job_id'
