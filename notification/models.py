import time
import datetime
from django.utils import timezone
from django.db import models
from django.contrib.auth.models import User
from tagging.models import Tag
from django.db.models.signals import post_save
from django.forms import ModelForm

from job.models import Job

class Notification(models.Model):
    message = models.CharField(max_length=255)
    action = models.CharField(max_length=100)
    creator = models.ForeignKey(User, related_name="creator")
    assignee = models.ForeignKey(User, related_name="assignee")
    job = models.ForeignKey(Job)

    created = models.DateTimeField(default=datetime.datetime.now)
    read_on = models.DateTimeField(blank=True, null=True)

    def read(self):
        self.read_on = timezone.now()
        self.save()
