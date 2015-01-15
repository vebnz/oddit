import time
import datetime
from django.db import models
from django.contrib.auth.models import User
from tagging.models import Tag
from django.db.models.signals import post_save
from indextank.client import ApiClient
from django.forms import ModelForm
from job.validators import validate_pdf

api = ApiClient('http://:twdfy1QVjimypE@61rg.api.searchify.com')
search = api.get_index('jobs')

# Create your models here.
class Region(models.Model):
    name = models.CharField(max_length=30)

    def __unicode__(self):
        return self.name

    class Meta:
        verbose_name_plural = "Regions"

class City(models.Model):
    name = models.CharField(max_length=30)
    region = models.ForeignKey(Region)

    def __unicode__(self):
        return self.name

    class Meta:
        verbose_name_plural = "Cities"

class Category(models.Model):
    name = models.CharField(max_length=30)

    def __unicode__(self):
        return self.name

    class Meta:
        verbose_name_plural = "Categories"

class Company(models.Model):
    name =  models.CharField(max_length=30)
    created = models.DateField(editable=False)
    updated = models.DateTimeField(editable=False)

    def save(self):
        if not self.id:
            self.created = datetime.date.today()
        self.updated = datetime.datetime.today()
        super(Company, self).save()

    def __unicode__(self):
        return self.name

    class Meta:
        verbose_name_plural = "Companies"

class JobType(models.Model):
    name = models.CharField(max_length=30)

    def __unicode__(self):
        return self.name

    class Meta:
        verbose_name_plural = "Job Types"

class Job(models.Model):
    REMOTE_CHOICES = (
            (0, 'No'),
            (1, 'Yes')
    )
    POSITION_CHOICES = (
        (0, 'Junior'),
        (1, 'Intermediate'),
        (2, 'Senior'),
	(3, 'Intern')
    )
    BUDGET_CHOICES = (
            (0, 'Per Hour'),
            (1, 'Total'),
	    (2, 'Negotiable')
    )
    user = models.ForeignKey(User)
    title = models.CharField(max_length=30)
    category = models.ForeignKey(Category)
    description = models.TextField()
    company = models.CharField(max_length=30)
    position = models.IntegerField(default=0, choices=POSITION_CHOICES)
    region = models.ForeignKey(Region)
    city = models.ForeignKey(City)
    remote = models.IntegerField(default=0, choices=REMOTE_CHOICES)
    created = models.DateField(editable=False)
    updated = models.DateTimeField(editable=False)
    type = models.ForeignKey(JobType)
    budget = models.IntegerField()
    budget_type = models.IntegerField(default=0, choices=BUDGET_CHOICES)
    ip = models.IPAddressField(default="225.225.225.225")
    expires = models.DateField()
    featured = models.NullBooleanField()
    bold = models.NullBooleanField()
    tag_list = models.CharField(max_length=255)

    def save(self, *args, **kwargs):
        if not self.id:
            self.created = datetime.date.today()
        self.updated = datetime.datetime.today()

        super(Job, self).save()

        self.tags = self.tag_list

        search.add_document(self.id, { 'title': self.title, 'match': 'all',
                                      'tags': self.tag_list, 'company':
                                      self.company, 'region': self.region.name,
                                      'city': self.city.name})

    def _get_tags(self):
        return Tag.objects.get_for_object(self)

    def _set_tags(self, tag_list):
        Tag.objects.update_tags(self, tag_list)

    tags = property(_get_tags, _set_tags)

    def __unicode__(self):
        return self.title

    class Meta:
        verbose_name_plural = "Jobs"

class JobApply(models.Model):
    user = models.ForeignKey(User)
    job = models.ForeignKey(Job)
    notes = models.TextField()
    created = models.DateField(editable=False)
    updated = models.DateTimeField(editable=False)
    resume = models.FileField(upload_to='resumes/', validators=[validate_pdf])

    def save(self, *args, **kwargs):
        if not self.id:
            self.created = datetime.date.today()
        self.updated = datetime.datetime.today()
        super(JobApply, self).save()

    def __unicode__(self):
        return u"%s's application" % self.user

    class Meta:
        verbose_name_plural = 'Job Applications'
        verbose_name = 'Job Application'

class UserProfile(models.Model):
    user = models.OneToOneField(User)
    nickname = models.CharField(max_length=30)

    def __str__(self):
        return u"%s's profile" % self.user

def create_user_profile(sender, instance, created, **kwargs):
    if created:
        profile, created = UserProfile.objects.get_or_create(user=instance)

post_save.connect(create_user_profile, sender=User)
