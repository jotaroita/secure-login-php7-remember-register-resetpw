# secure-login-php7-remember-register-resetpw

Secure Login Functions:
- Easy Register Form with just email and password (no need to confirm email)
- Login (using DB and sessions)
- Remember checkbox (cookie with secure token and selector) more secure if run in PHP 7 (but works in 5.6 too)
- Reset password (send email to the user for recovery)
- Logout (...and unset cookie)
- avoid SQL injection with request in PDO
- avoid brute force attacks (there is an attempt table) after X attemps u need to wait before retry to login
- Session Expire after X seconds of inactivity
- 2 example pages "home page" and "restricted access page"
- full and easy implementation in your website and script

require DB Mysql
if run in PHP > 7 the script use random_bytes,
otherwise the script will use openssl_random_pseudo_bytes

HOW TO INSTALL:
 1. upload all file in a folder in your server for example login3r
 2. edit check.php and config variables
 3. execute query for set tables in your database see setup.sql file
 4. run the script www.yoursite.com/login3r/index.php

