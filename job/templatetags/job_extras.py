import hashlib
import urllib
from django import template
from django.utils.safestring import mark_safe

register = template.Library()

# return only the URL of the gravatar
# TEMPLATE USE:  {{ email|gravatar_url:150 }}
@register.filter
def gravatar_url(email, size=40):
    default = "http://placehold.it/300x300"
    return "https://www.gravatar.com/avatar/%s?d=retro" % (hashlib.md5(email.lower()).hexdigest())

# return an image tag with the gravatar
# TEMPLATE USE:  {{ email|gravatar:150 }}
@register.filter(name='gravatar')
def gravatar(email, size=40):
    url = gravatar_url(email, size)
    return mark_safe('<img src="%s" height="%d" width="%d" class="img-circle img-thumbnail img-responsive">' % (url, size, size))
