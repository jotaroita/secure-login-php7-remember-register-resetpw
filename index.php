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

//each page in your website has to begin with
session_start();
include("check.php"); 

//the log-out are request simply by adding ?logout=1 in your url check.php will do the job


//below an example of index, you can change html, but keep somewhere a link "log-in" to show if the user is NOT logged in yet
?>
<!DOCTYPE html>
<html>
<head>
	<title>Secure Login 3R - Home Page</title>
	<link href='http://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>
	<link href=style.css  rel='stylesheet' type='text/css'>
	</head>
<body>

	<div class="header">
		<a href="/">Secure Login 3R<br><small>Register, Remember (cookie), Reset - Script in PHP with DB Mysql and query PDO</small></a>
	</div>
	<center>
	<h1>Home Page</h1>
	
	<p style=max-width:500px><small>
	Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
	Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
	</small></p>
	<hr><p>
<?
//check if user logged, session set means user logged
 if (isset($_SESSION['user'])) {
   echo '<span style=color:#009900><b>You are Logged in</b></span>,
   you can visit <a href=content.php>reserved content here</a><br><br>or if you want <a href="index.php?logout=1">LOG-OUT</a>';
 //if no session set, show link for login
 } else {
   echo '<span style=color:#AA0000><b>You are a guest</b></span>, please <a href="login.php">login here</a><br><br>or <a href="register.php">register here</a>';
 }

 //this line is for debug keep it if u want understand how script works, delete it if u dont need
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
</p>
</center>
</body>
</html>

 