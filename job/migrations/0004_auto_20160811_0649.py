# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('job', '0003_auto_20160808_1849'),
    ]

    operations = [
        migrations.RemoveField(
            model_name='userprofile',
            name='nickname',
        ),
        migrations.AddField(
            model_name='userprofile',
            name='location',
            field=models.CharField(default='N/A', max_length=75),
            preserve_default=False,
        ),
    ]
