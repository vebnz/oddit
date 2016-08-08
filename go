# for laziness!
bower install
python manage.py makemigrations
python manage.py compress
python manage.py runserver 0.0.0.0:9001
