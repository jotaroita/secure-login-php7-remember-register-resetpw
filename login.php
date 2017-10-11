<?php
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
session_start();

//check the cookie and set the session user 
include("check.php"); 

//if session is set means that the user is already logged in, so doesnt show the login page to an user already logged


if (isset($_SESSION['user'])) {
//When user logged try to access to login page...
//header("location:content.php"); //decomment this line for redirect to content page 
$message="You are already Logged in. Enjoy contents <a href=content.php>here</a> ";//or stay in this page and show a message
}

// *********************************
// * CHECK IF USER/PW MATCH        *
// *********************************

//if login form is submitted 
if (isset($_POST["login"])) {

$_POST["email"]=trim($_POST["email"]);

do {
//if not valid email "end cicle" and show again the login form
if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)===false or !preg_match('/@.+\./', $_POST["email"])) {$message="Invalid Email";break;}

//******************** ADD A DELAY FOR AVOID BRUTAL FORCE ATTACKS 
//otherwise read from database how many login attemps in the last 10 minutes from the same IP address
$sql = $db->prepare("SELECT data FROM log_accessi WHERE ip='".$_SERVER['REMOTE_ADDR']."' and accesso=0 and data>date_sub(now(), interval 10 minute) ORDER BY data DESC");
$sql->execute();
$attempts=$sql->rowCount();
$last=$sql->fetchColumn();
	
$last=strtotime($last);
$delay=min(max(($attempts-3),0)*2,30); //after 3rd wrong try, add a delay of (# attempts * 2) as seconds (maximum 30 seconds each try)
	
//if there are many tries in few second, show a messagge and "end cicle" so doesnt check the email/pw this time
if (time()<($last+$delay)) {$message="Too many attempts, wait $delay seconds before retry";break;}
//***************************************************************


$sql = $db->prepare("SELECT * FROM utenti WHERE email=?");
$sql->bindParam(1, $_POST["email"]);
$sql->execute();
$rows = $sql->fetch(PDO::FETCH_ASSOC);	

//check if password type is match with password in the database
//using php function password_hash in the register.php and password_verify here
//I add the constant PEPPER has salt (see check.php) the system already set a secure salt with the function password_hash
//(if u remove PEPPER or change it remember to do that in the register.php too)

$checked = password_verify($_POST['password'].PEPPER, $rows["password"]);
if ($checked) { //if email/pw are right:
    $message='password correct<br>enjoy content <a href=index.php>here</a>';
	$_SESSION['user'] = $rows["id"];
	
	//...and if remember me checked send the cookie
	if ($_POST["remember"]=="true") {
	
	//create a random selector and auth code in the token database
    //function aZ is in the check.php file
	 $selector = aZ();
	 $authenticator = bin2hex(random_ver(33));
	   $res=$db->prepare("INSERT INTO auth_tokens (selector,hashedvalidator,userid,expires,ip) VALUES (?,?,?,FROM_UNIXTIME(".(time() + 864000*7)."),?)");
	   $res->execute(array($selector,password_hash($authenticator, PASSWORD_DEFAULT, ['cost' => 12]),$rows['id'],$_SERVER['REMOTE_ADDR']));			
//set the cookie
setcookie(
        'remember',
         $selector.':'.base64_encode($authenticator),
         (time() + 864000*7), //the cookie will be valid for 7 days, or till log-out
         '/',
         WEBSITE,
         false, // TLS-only
         false  // http-only
    );
}

//redirect to page with content only for members
header("location:content.php");

//if email/pw are wrong	
} else {
    $message=($attempts>1)?"Wrong credentials ($attempts attempts)":"Wrong credentials retry";
}


//save the access log
$sql = $db->prepare("INSERT INTO log_accessi (ip,mail_immessa,accesso) VALUES (? ,? ,?)");
$sql->bindParam(1, $_SERVER['REMOTE_ADDR']);
$sql->bindParam(2, $_POST["email"]);
$sql->bindParam(3, $checked);
$sql->execute();

}while(0);

}

// *********************************
// * HTML FOR LOGIN FORM           *
// *********************************

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login Below</title>
	<link href='http://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>
	<link href=style.css  rel='stylesheet' type='text/css'>
	</head>
<body>

	<div class="header">
		<a href="/">Secure Login 3R<br><small>Register, Remember (cookie), Reset - Script in PHP with DB Mysql and query PDO</small></a>
	</div>

	<? if (empty($_SESSION["user"])) {
	?>
	<h1>Login</h1>
	<span>or <a href="register.php">register here</a></span>
	<br><p style=color:#C00><b><?= $message ?></b></p>
	<form action="login.php" method="POST">
		
		<input type="email" required placeholder="Enter your email" name="email">
		<input type="password" required placeholder="and password" name="password">
		<input type=checkbox name=remember value=true> Remember me
		<br><br><input type="submit" name=login>

	</form>
	<br><span>Forgot your Password? <a href="reset.php">reset here</a></span>
	<?
	}
	
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
</body>
</html>