# Oddit

Oddit stands for "Odd IT Jobs".

Here's a screenshot of our first alpha version:
![oddit alpha screenshot](http://i.imgur.com/LPqr8.png)

Here's the admin panel:
![oddit alpha admin screen](http://i.imgur.com/UZgs3hO.png)

### Tell me more...
We started this project out of a need for small jobs we could do.  Take an Electrician for example, they can come around and put in a new light switch for you, you pay them $80 and they go away. Job well done. But why is such a thing uncommon in the Information Technology industry?

There's always someone who:

 - Needs the banner on their Wordpress website replaced
 - Needs their email newsletter system updated
 - Requires maintenance on their PHP website
 - Wants an email system for their business domain
 - Wants a mobile app for their business prototyped
 - Fixing their Shopify/Joomla/Drupal site
 - ... and so on

**However** where do the people/organisations get people for these jobs? They're either gambling on a student, or wasting money on a consultant. **Oddit** is the solution. A place to find people with the skills you need to do one-offs, casual and fixed-term jobs.

### The source code

Oddit is built on the Python Django framework (1.8).

Packages are required, please see requirements.txt:

 - cd to the directory where requirements.txt is located.
 - activate your virtualenv.
 - run: pip install -r requirements.txt in your shell.


There's not really much to making this work, it's all very simple-like. If you do end up fiddling with this code
remember parts of it were created 5 years ago.

### Initial Data load

 - python manage.py makemigrations
 - python manage.py migrate
 - python loaddata job/sql/fixtures.json

We'll be accepting pull requests and fixing issues/bugs posted. Help Oddit thrive!

### Developed by [veb ltd](http://veb.nz)
- [Mike Mackenzie](https://github.com/veb)
- [Ray Arvin Rimorin](https://github.com/avwave)
- [Jamie Gracie](https://github.com/Kingy)

### Contributors
- [Rob Attfield](https://github.com/rattfieldnz)
