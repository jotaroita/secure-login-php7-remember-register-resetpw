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

to do:
- Change Password Form (need confirm old password before continue)
- if multiple access for the same user at the same time, disconnect others, delete all cookies

require DB Mysql
if run in PHP > 7 the script use random_bytes,
otherwise the script will use openssl_random_pseudo_bytes


