from .models import Notification
def notify_count(request):
    notification_count = Notification.objects.filter(assignee__id=request.user.id).count()
    return {
        'notify_count' : notification_count
    }
