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
include("check.php");

if (isset($_SESSION['user'])) {
//When user logged try to access to register page...
//header("location:content.php"); //decomment this line for automatically redirect to content page 
die("You are already Logged in. Enjoy contents <a href=content.php>here</a>"); //or stay in this page and show a message
}

//if form sent
if (isset($_POST["register"])) {
$_POST["email"]=trim($_POST["email"]);

do {
//check if email is valid, if the 2 password match and if PW is atleast 8 char long, usefull if js is disable on user browser
if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)===false or !preg_match('/@.+\./', $_POST["email"])) {$error="Invalid Email";break;}
if ($_POST["password"]!=$_POST["confirm_password"]) {$error="Password mismatch";break;}
if (strlen($_POST["password"])<8) {$error="Password too short (minimum 8 character)";break;}

//check if email already registerd in DB
$sql = $db->prepare("SELECT * FROM utenti WHERE email=?");
$sql->bindParam(1, $_POST["email"]);
$sql->execute();
$exists=$sql->rowCount();

if ($exists) {$error="Email Already Registered";break;}

// save new user in the DB, here i used the PEPPER constant defined in the check.php as additional salt
// Hash a new password for storing in the database.
// The function automatically generates a cryptographically safe salt.
//(if u remove PEPPER or change it remember to do that in the login.php too)
$hash = password_hash($_POST['password'].PEPPER, PASSWORD_DEFAULT, ['cost' => 12]);

try {
$sql = $db->prepare("INSERT INTO utenti (email,password) VALUES (? ,?)");
$sql->bindParam(1, $_POST["email"]);
$sql->bindParam(2, $hash);
$sql->execute();
} catch (PDOException $e) {
$error="Error during ";break;
}

$registered=1;

} while(0);

}
//disattivare submit dopo il primo click poi riattivarlo oppure valutare ajax
?>

<!DOCTYPE html>
<html>
<head>
	<title>Register Below</title>
	<link href='http://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>
    <link href=style.css  rel='stylesheet' type='text/css'>
	</head>
<body>

	<div class="header">
		<a href="/">Secure Login 3R<br><small>Register, Remember (cookie), Reset - Script in PHP with DB Mysql and query PDO</small></a>
	</div>

	
		<br>


	<h1>Register</h1>	
	<span>or <a href="login.php">login here</a></span>
    <br><p style=color:#C00><b><?= $error ?></b></p>
	<?php
	//show form when user registration fails and when page loaded without post data
	if(!$registered) { ?>
	<form action="register.php" method="POST">
		<input type="email" required placeholder="Enter your email" name="email" value=<?=$_POST["email"];?>>
		<input type="password" required placeholder="and password" name="password" pattern=".{8,}" minlength="8">
		<input type="password" required placeholder="confirm password" name="confirm_password">
		<input type="submit" name=register>
	</form>
	<?
	} else {
	?>
	<br><br><p>Sign-up completed! Please <a href=login.php>log-in</a>.
	<?
	}
	?>
</body>
</html>
<?
//debug
echo "<br><br><br><span style=font-family:courier;color:#AAA>## $debug</span>";

//the js check password match before send form


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
<script>
var password = document.getElementsByName("password")[0]
  , confirm_password = document.getElementsByName("confirm_password")[0];

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
</script>
