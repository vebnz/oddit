from django.http import HttpResponse
from channels import Group
from channels.sessions import channel_session
from channels.auth import http_session_user, channel_session_user, channel_session_user_from_http


@channel_session_user_from_http
def ws_connect(message):
    room = message.content['path'].strip('/')
    message.channel_session["room"] = room
    Group("chat-%s" % room).add(message.reply_channel)


@channel_session_user
def ws_message(message):
    Group("chat-%s" % message.channel_session['room']).send({
        "text": message.content['text']
    })


@channel_session_user
def ws_disconnect(message):
    Group("chat-%s" % message.channel_session['room']).discard(message.reply_channel)
