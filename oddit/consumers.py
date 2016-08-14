from django.http import HttpResponse
from channels import Group

def ws_add(message):
    Group("lobby").add(message.reply_channel)

def ws_disconnect(message):
    Group("lobby").discard(messafe.reply_channel)


def ws_message(message):
    Group("lobby").send({
        "text": "[user]: %s " % message.content['text']
    })
