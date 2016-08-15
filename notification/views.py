from django.shortcuts import render_to_response, get_object_or_404
from django.contrib.auth.decorators import login_required
from django.core.exceptions import ObjectDoesNotExist
from job.models import Job, User

@login_required
def view_message_dump(request):
    return render_to_response('channels/dump.html', {
        "user": request.user
    })
