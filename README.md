# Note

Note taking website allow the user to write notes.

3 pages:
  - login page https://syiner.com/Note/login.php
  - register page https://syiner.com/Note/register.php
  - note page https://syiner.com/Note/Note.php (but you have to login first)

# Usage!

 - create account then, the page will automatically direct you to note page and create new note automatically then, you have two button first one add new note second one delete selected note the notes will saved automatically when you finish writing finally, there is logout link in top right corners to log out


### Tech

Register page create user by email, name and password the website encrypted the password by salt using Base64 encoding.
The Front-End of website developed using Bootstrap 4 framework and the Back-End developed by PHP language 
Most of the Back-End functions are located in include/DB_Functions.php

The database is MySQL, creating two table: 
1.	users which attributes are id, name, email, encrypted_password and salt 
2.	notes which attributes are note_id, user_id, note
The user_id attribute are foreign key and id, note_id are prime keys


Note uses a number of open source projects to work properly:

* [Bootstrap 4] - Bootstrap is an open source toolkit for developing with HTML, CSS, and JS
* [jQuery] - jQuery is a fast, small, and feature-rich JavaScript library
* [PHP] - Hypertext Preprocessor is a server-side scripting language designed for Web development
* [MySQL] - MySQL is an open source relational database management system
* [AndroidHive] - Login and Registration with PHP, MySQL

### Installation

just put database credentials in include/Config.php file

   [Bootstrap 4]: <https://getbootstrap.com/>
   [jQuery]: <http://jquery.com>
   [php]: <http://twitter.com/tjholowaychuk>
   [mysql]: <https://www.mysql.com/>
   [androidhive]: <https://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/>
   

