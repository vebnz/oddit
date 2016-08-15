# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('job', '0008_userprofile_phone'),
    ]

    operations = [
        migrations.DeleteModel(
            name='Company',
        ),
        migrations.RemoveField(
            model_name='job',
            name='budget_type',
        ),
        migrations.RemoveField(
            model_name='job',
            name='company',
        ),
    ]
