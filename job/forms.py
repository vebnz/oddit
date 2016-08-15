from django import forms
from job.models import Job, JobApply, UserProfile
from django.forms import CharField
from django.forms.widgets import TextInput
from django.contrib.auth.models import User

class NumberInput(TextInput):
        input_type = 'number'

class JobForm(forms.ModelForm):
    def __init__(self, *args, **kwargs):
        self.user = kwargs.pop('user', None)
        super(JobForm, self).__init__(*args, **kwargs)

    #def clean(self):
        #    cleaned_data = super(JobForm, self).clean()
        #budget = cleaned)data.get("budget")
        #if budget[0] == "$": budget = budget[1:] # cut off the dollar sign
        #budget = budget.replace(',', '')


    def save(self, commit=True):
        instance = super(JobForm, self).save(commit=False)
        if self.user:
            instance.user = self.user
            return instance.save()

    # override anything here
    title = CharField(label='Job Title',max_length=30)
    budget = CharField(label='Budget ($)')
    class Meta:
        model = Job
        widgets = {
            'user'      : forms.Select(attrs={'class' : 'styled'}),
            'category'  : forms.Select(attrs={'class' : 'styled'}),
            'position'  : forms.Select(attrs={'class' : 'styled'}),
            'region'    : forms.Select(attrs={'class' : 'styled'}),
            'city'      : forms.Select(attrs={'class' : 'styled'}),
            'remote'    : forms.Select(attrs={'class' : 'styled'}),
            'type'      : forms.Select(attrs={'class' : 'styled'}),
	        'budget'    : forms.NumberInput(attrs={'min' : '0', 'max' : '1000000', 'type' : 'number'}),
	        'budget_type' : forms.Select(attrs={'class' : 'styled'}),
            'description' : forms.Textarea(attrs={'class' : 'validation'}),
            'expires'   : forms.DateInput(attrs={'class': 'datepicker', 'type' : 'date'}),
        }
        exclude = ('bold', 'featured', 'ip', 'user', 'status')

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

        exclude = ('user', 'job')

class UserForm(forms.ModelForm):
    class Meta:
        model = User
        exclude = ('username','password','is_staff','is_active','last_login','is_superuser', 'groups', 'user_permissions', 'date_joined')

class UserProfileForm(forms.ModelForm):
    def __init__(self, *args, **kwargs):
        # magic 
        self.user = kwargs['instance'].user
        user_kwargs = kwargs.copy()
        user_kwargs['instance'] = self.user
        self.user_form = UserForm(*args, **user_kwargs)
        # magic end 

        super(UserProfileForm, self).__init__(*args, **kwargs)

        self.fields.update(self.user_form.fields)
        self.initial.update(self.user_form.initial)

    def save(self, *args, **kwargs):
        self.user_form.save(*args, **kwargs)
        return super(UserProfileForm, self).save(*args, **kwargs)

    class Meta:
        model = UserProfile
        exclude = ('user',)
        
class SignupForm(forms.Form):
    first_name = forms.CharField(max_length=30, label='First Name')
    last_name = forms.CharField(max_length=30, label='Last Name')

    def signup(self, request, user):
        user.first_name = self.cleaned_data['first_name']
        user.last_name = self.cleaned_data['last_name']
        user.save()