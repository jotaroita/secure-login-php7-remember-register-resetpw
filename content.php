<?

/*
 *
 * LOGIN 3R: A secure PHP Login Script with Register, Remember, and Reset function
 *
 * Uses PHP SESSIONS, modern password-hashing and salting and gives the basic functions a proper login system needs.
 *
 * @author jotaroita
 * @link https://github.com/jotaroita/secure-login-php7-remember-register-resetpw
 * @license http://opensource.org/licenses/MIT MIT License
 *
 * INSTALL:
 * 1. upload all file in a folder in your server
 * 2. edit check.php and config variables
 * 3. execute query for set tables in your database see setup.sql file
 * 4. run the script www.yoursite.com/login3r/
 *
*/


//another example of page
//each page that need to be different for logged user and for not logged user has to begin with these 2 lines
session_start();
include("check.php"); 
 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Secure Login 3R - Reserved Page</title>
	<link href='http://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>
	<link href=style.css  rel='stylesheet' type='text/css'>
	</head>
<body>

	<div class="header">
		<a href="/">Secure Login 3R<br><small>Register, Remember (cookie), Reset - Script in PHP with DB Mysql and query PDO</small></a>
	</div>
	<center>
	
	
<?
//contents for logged user
 if (isset($_SESSION['user'])) {
 ?>
<h1>Welcome</h1>
<p style=max-width:500px><small>
 Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.
 Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. 
 <br><br>Consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.
 Donec quam felis, lorem ipsum dolor sit amet, ultricies nec, pellentesque eu, pretium quis, sem. </small></p>
 <hr><p>
 <span style=color:#009900><b>You are Logged in</b></span>, you can visit the <a href=index.php>home page</a><br><br>or if you want <a href="index.php?logout=1">LOG-OUT</a>
 <?
 //contents to display for guest (not logged)
 } else {
 ?>
 <h1>Page only for the Members</h1>
<p style=max-width:500px><small>
 access denied
 </small></p>
 <hr><p>
 <span style=color:#AA0000><b>You are a guest</b></span> this page are reserved to registered user only,<br><br>go to <a href=index.php>home page</a> 
   or<br><br>please <a href="login.php">login here</a> or <a href="register.php">register here</a>
   <?
 }
 
 //debug u can delete if u dont need
  echo "<br><br><br><span style=font-family:courier;color:#AAA>## $debug</span>";
  
  /*
 
  With my script did you have saved time? How many hours of works?
  How much value has one hour of your time?
 
  do a script like that from your own require weeks of research and work,
  if this script it's usefull for you consider a donation
  
  https://www.paypal.me/mengarelli
 
  or bitcoin:
  188ikyTQTBmQ4bWrY2hGdmKcMwEbewSpyJ
 
*/
?>

</center>
</body>
</html>
 
 