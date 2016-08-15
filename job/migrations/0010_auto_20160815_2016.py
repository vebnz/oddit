# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.db import models, migrations


class Migration(migrations.Migration):

    dependencies = [
        ('job', '0009_auto_20160815_1947'),
    ]

    operations = [
        migrations.AlterField(
            model_name='job',
            name='remote',
            field=models.IntegerField(default=1, choices=[(0, b'No'), (1, b'Yes')]),
        ),
    ]
