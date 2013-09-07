Jl4 Newsletter Application
==============

I give you a nifty little [Laravel 4](http://laravel.com) newsletter application. 
Give it a shot!

Things you can do with it
--------------

- Add and manage subscribers (you can import a CSV list of subscribers: first name, last name, email - trial)
- Assign subscribers to lists - which make it easy to manage subscribers
- Send emails to individual subscribers or entire lists
- Track emails 
- View email readers' statistics - reads, browsers, platforms etc. 
- It also automatically adds an 'unsubscribe' link to all emails
- *Please note that the dashboard  (stats page) is cached every 30 minutes. So you won't see a change immediately.*

Things you should do [Edited]
--------------
- Download a fresh copy of [Laravel 4.0](https://github.com/laravel/laravel/archive/master.zip) into your dev environment
- Use ```composer install``` to install its dependencies (see [Laravel website](http://laravel.com/docs/installation) for full guide)
- Download and extract the contents of Jl4 Newsletter Application into a separate directory
- Copy the ```composer.json``` file from step 3 and overwrite that in step 1 with it. 
- Run ```composer update```
- Copy the entire ```app/``` and ```public/``` directories from step 3 and overwrite those in step 1 with these
- Add ```,"app/libraries/csvImporter.php", "app/libraries/Browser.php"``` to ```autoload``` in ```composer.json``` file after ```"app/tests/TestCase.php"```
- Run ```composer dump-autoload```
- Set up your mysql database with the following credentials (You can change them if you want, just ensure you configure ```app/config/database.php``` to match):
```'database' => 'newsletterl4','username' => 'newsletterl4','password' => 'RncqKAJGLzw9z87n'```
- It will be sending a lot of emails, so ensure you configure your ```app/config/mail.php``` accordingly
- Run the migrations included in ```app/database/migrations``` or import the ```jl4-mysql-dump.sql``` included
- NB: The application needs two users - the first one (added when you run the migrations - by default, myself) is the super admin who receives emails when the other one (added when you setup the site in frontend) fills the 'help' form.
- Once the above is done, go to your new home page from where you will follow instructions and voila!

Many thanks to
--------------

- [Laravel 4](http://laravel.com)
- [Bootstrap 3.0](http://twbs.github.io/bootstrap)
- [Sentry 2 from Cartalyst](http://docs.cartalyst.com/sentry-2)
- [CkEditor 4.2 + CkFinder 2.3.1](http://ckeditor.com)
- and the few other assets used

Candidates for further work
--------------

- Tracking bounces since Swiftmailer only delivers to the SMTP server 
- Importing CSV - not very consistent at the moment
- Scalability/performance - the statistics page is currently cached every 30 minutes 
- Bugs/minor fixes as they arise


Feel free to use/contribute to it as you wish. In case you need to, [@kJamesy](https://twitter.com/kJamesy) me

License
--------------

[The MIT License](http://opensource.org/licenses/MIT)
