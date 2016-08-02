# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations
import job.validators
from django.conf import settings


class Migration(migrations.Migration):

    dependencies = [
        migrations.swappable_dependency(settings.AUTH_USER_MODEL),
    ]

    operations = [
        migrations.CreateModel(
            name='Category',
            fields=[
                ('id', models.AutoField(verbose_name='ID', serialize=False, auto_created=True, primary_key=True)),
                ('name', models.CharField(max_length=30)),
            ],
            options={
                'verbose_name_plural': 'Categories',
            },
        ),
        migrations.CreateModel(
            name='City',
            fields=[
                ('id', models.AutoField(verbose_name='ID', serialize=False, auto_created=True, primary_key=True)),
                ('name', models.CharField(max_length=30)),
            ],
            options={
                'verbose_name_plural': 'Cities',
            },
        ),
        migrations.CreateModel(
            name='Company',
            fields=[
                ('id', models.AutoField(verbose_name='ID', serialize=False, auto_created=True, primary_key=True)),
                ('name', models.CharField(max_length=30)),
                ('created', models.DateField(editable=False)),
                ('updated', models.DateTimeField(editable=False)),
            ],
            options={
                'verbose_name_plural': 'Companies',
            },
        ),
        migrations.CreateModel(
            name='Job',
            fields=[
                ('id', models.AutoField(verbose_name='ID', serialize=False, auto_created=True, primary_key=True)),
                ('title', models.CharField(max_length=30)),
                ('description', models.TextField()),
                ('company', models.CharField(max_length=30)),
                ('position', models.IntegerField(default=0, choices=[(0, b'Junior'), (1, b'Intermediate'), (2, b'Senior'), (3, b'Intern')])),
                ('remote', models.IntegerField(default=0, choices=[(0, b'No'), (1, b'Yes')])),
                ('created', models.DateField(editable=False)),
                ('updated', models.DateTimeField(editable=False)),
                ('budget', models.IntegerField()),
                ('budget_type', models.IntegerField(default=0, choices=[(0, b'Per Hour'), (1, b'Total'), (2, b'Negotiable')])),
                ('ip', models.GenericIPAddressField(default=b'225.225.225.225')),
                ('expires', models.DateField()),
                ('featured', models.NullBooleanField()),
                ('bold', models.NullBooleanField()),
                ('tag_list', models.CharField(max_length=255)),
                ('category', models.ForeignKey(to='job.Category')),
                ('city', models.ForeignKey(to='job.City')),
            ],
            options={
                'verbose_name_plural': 'Jobs',
            },
        ),
        migrations.CreateModel(
            name='JobApply',
            fields=[
                ('id', models.AutoField(verbose_name='ID', serialize=False, auto_created=True, primary_key=True)),
                ('notes', models.TextField()),
                ('created', models.DateField(editable=False)),
                ('updated', models.DateTimeField(editable=False)),
                ('resume', models.FileField(upload_to=b'resumes/', validators=[job.validators.validate_pdf])),
                ('job', models.ForeignKey(to='job.Job')),
                ('user', models.ForeignKey(to=settings.AUTH_USER_MODEL)),
            ],
            options={
                'verbose_name': 'Job Application',
                'verbose_name_plural': 'Job Applications',
            },
        ),
        migrations.CreateModel(
            name='JobType',
            fields=[
                ('id', models.AutoField(verbose_name='ID', serialize=False, auto_created=True, primary_key=True)),
                ('name', models.CharField(max_length=30)),
            ],
            options={
                'verbose_name_plural': 'Job Types',
            },
        ),
        migrations.CreateModel(
            name='Region',
            fields=[
                ('id', models.AutoField(verbose_name='ID', serialize=False, auto_created=True, primary_key=True)),
                ('name', models.CharField(max_length=30)),
            ],
            options={
                'verbose_name_plural': 'Regions',
            },
        ),
        migrations.CreateModel(
            name='UserProfile',
            fields=[
                ('id', models.AutoField(verbose_name='ID', serialize=False, auto_created=True, primary_key=True)),
                ('nickname', models.CharField(max_length=30)),
                ('user', models.OneToOneField(to=settings.AUTH_USER_MODEL)),
            ],
        ),
        migrations.AddField(
            model_name='job',
            name='region',
            field=models.ForeignKey(to='job.Region'),
        ),
        migrations.AddField(
            model_name='job',
            name='type',
            field=models.ForeignKey(to='job.JobType'),
        ),
        migrations.AddField(
            model_name='job',
            name='user',
            field=models.ForeignKey(to=settings.AUTH_USER_MODEL),
        ),
        migrations.AddField(
            model_name='city',
            name='region',
            field=models.ForeignKey(to='job.Region'),
        ),
    ]
