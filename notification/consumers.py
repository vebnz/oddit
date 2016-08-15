from django.http import HttpResponse
from channels import Group, Channel
from channels.sessions import channel_session, enforce_ordering
from channels.auth import http_session_user, channel_session_user, channel_session_user_from_http

from .models import Notification
from job.models import Job
from django.contrib.auth.models import User

from pprint import pprint

import json

def msg_consumer(message):
    Notification.objects.create(
        creator=User.objects.get(pk=message.content['sender']),
        assignee=User.objects.get(pk=message.content['assignee']),
        job=Job.objects.get(pk=message.content['job']),
        action=message.content['action'],
        message=message.content['message']
    )

    Group("lobby").send({
        "text": message.content['message']
    })

@enforce_ordering
@channel_session_user_from_http
def ws_connect(message):
    Group("lobby").add(message.reply_channel)


@enforce_ordering
@channel_session_user
def ws_message(message):
    message_json = json.loads(message.content['text'])
    Channel("lobby-messages").send({
        "sender": message.user.id,
        "assignee": message_json['assignee'],
        "job": message_json['job'],
        "action": message_json['action'],
        "message": message_json['msg'],
    })

@enforce_ordering(slight=True)
@channel_session_user
def ws_disconnect(message):
    Group("lobby").discard(message.reply_channel)
