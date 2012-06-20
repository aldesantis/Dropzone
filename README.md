Dropzone
========

What is this?
-------------

This is a simple drag'n'drop upload script I made in a boring eveening: it
allows your users to leave you a file. The link to download it will be sent to
you by email.

What technologies does it use?
------------------------------

It uses the following libraries/technologies:

   - [Twitter Bootstrap](http://twitter.github.com/bootstrap/), the CSS toolkit
     from Twitter.
   - [Silex](http://www.silex-project.org), a PHP micro-framework made on top
     of [Symfony2](http://www.symfony.com) components.
   - [Monolog](https://github.com/Seldaek/monolog), a PHP5 logging library.
   - [Twig](http://twig.sensiolabs.org), an amazing PHP template engine.
   - [jQuery Filedrop](https://github.com/weixiyen/jquery-filedrop), guess for
     what?
   - [Swiftmailer](http://www.swiftmailer.org), again, an amazing PHP mailing
     library.

Installation
------------

### Clone the Git repository

Open a terminal and type:

    $ git clone git://github.com/alessandro1997/Dropzone.git

This will copy Dropzone into the **Dropzone** folder.

### Download the submodules

Move into Dropzone's directory and type:

    $ git submodule init
    $ git submodule update

This will download Git submodules into their directories.

### Download Composer dependencies

Then you have to type:

    $ wget http://getcomposer.org/composer.phar
    $ php composer.phar install

This will download a copy of [Composer](http://getcomposer.org) and install the
project's dependencies.

### Configure Dropzone

Copy the **app/config.php.dist** file into **app/config.php** and customize the
values.

### Create the writable directories

Create the **app/logs** and **app/cache** directories:

    $ mkdir app/logs app/cache
    $ chmod 777 app/logs app/cache

Do the same for your uploads directory.

### Upload!

Upload everything to your FTP server (make sure **web** is the document root)
and check that everything works as expected.

That's it!
