import hashlib
import urllib
from django import template
from django.utils.safestring import mark_safe

register = template.Library()

# TEMPLATE USE:  {{ email|avatar_url }}
@register.filter
def avatar_url(email):
    return "https://api.adorable.io/avatars/285/%s" % (hashlib.md5(email.lower()).hexdigest())

# TEMPLATE USE:  {{ email|avatar }}
@register.filter
def avatar(email):
    url = avatar_url(email)
    return mark_safe('<img src="%s" class="img-circle img-thumbnail img-responsive">' % (url))
