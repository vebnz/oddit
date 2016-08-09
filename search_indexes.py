import datetime
from haystack import indexes
from oddit.models import Job


class NoteIndex(indexes.SearchIndex, indexes.Indexable):
    title = indexes.CharField(document=True, use_template=True)
    user = indexes.CharField(model_attr='user')

    def get_model(self):
        return Note

    def index_queryset(self, using=None):
        """Used when the entire index for model is updated."""
        return self.get_model().objects.filter(pub_date__lte=datetime.datetime.now())
