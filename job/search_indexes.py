import datetime
from haystack import indexes
from job.models import Job

class JobIndex(indexes.SearchIndex, indexes.Indexable):
    text = indexes.CharField(document=True, use_template=True)
    title = indexes.CharField(model_attr='title')
    category = indexes.CharField()

    def get_model(self):
        return Job

    def index_queryset(self, using=None):
        """Used when the entire index for model is updated."""
        #return self.get_model().objects.filter(created__lte=datetime.datetime.now())
        return self.get_model().objects.all()
