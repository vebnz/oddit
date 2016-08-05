# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('job', '0001_initial'),
    ]

    operations = [
        migrations.AddField(
            model_name='job',
            name='status',
            field=models.IntegerField(default=0, choices=[(0, b'Draft'), (1, b'Published'), (2, b'Expired')]),
        ),
    ]
