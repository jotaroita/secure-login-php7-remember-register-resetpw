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
//When user logged try to access to reset page...
//header("location:content.php"); //decomment this line for automatically redirect to content page 
die("You are already Logged in. Enjoy contents <a href=content.php>here</a>"); //or stay in this page and show a message
}

//remove expired reset_code (expire time set to 10 minutes)
$sql = $db->prepare("UPDATE utenti SET reset_code='', reset_selector='' WHERE reset_code!='' and last_update<date_sub(now(), interval 10 minute)");
$sql->execute();


// **********************************
// * SEND RESET LINK TO INPUT EMAIL *
// **********************************

//if form sent
if (isset($_POST["reset"])) {
$_POST["email"]=trim($_POST["email"]);

do {
//check if email is valid
if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)===false or !preg_match('/@.+\./', $_POST["email"])) {$message="Invalid Email";break;}

//reset_code already sent
$sql = $db->prepare("SELECT * FROM utenti WHERE email=? and reset_code!=''");
$sql->bindParam(1, $_POST["email"]);
$sql->execute();
if ($sql->rowCount() > 0) {$message="Reset already request for this user, check your email";break;}

//generate a reset_selector and a reset_code
$reset_selector = aZ();
$reset_code = bin2hex(random_ver(10));


//add reset_code in the database
$sql = $db->prepare("UPDATE utenti SET reset_selector='".$reset_selector."', reset_code='".password_hash($reset_code, PASSWORD_DEFAULT, ['cost' => 6])."' WHERE email=? and reset_code='' LIMIT 1");
$sql->bindParam(1, $_POST["email"]);
$sql->execute();
$debug.="reset code saved in database<br>";
//doesn't show other specific error (like user is not in our database) for security reasons

$pw_url="http://".WEBSITE.SCRIPTFOLDER."/reset.php?s=".$reset_selector."&p=".base64_encode($reset_code);

 $mail_body = "Dear user,\n\nIf this e-mail does not apply to you please ignore it.
 It appears that you have requested a password reset at our website ".WEBSITE."\n\n
 To reset your password, please click the link below.
 If you cannot click it, please paste it into your web browser's address bar.\n\n" . $pw_url . "\n\nThanks,\nThe Administration\n\n\n
 PS For security reasons this link will be expire in 10 minutes";
 
	
	//if send email doesnt work
	//use https://github.com/PHPMailer/PHPMailer
	//instead of standard php mail function
	
$debug=(mail($_POST["email"], WEBSITE." - Password Reset", $mail_body))?"Mail with Reset code sent":"Mail NOT sent";
		
        $message="If email registered in our system, the password recovery key has been sent.";




} while(0);

}

// **********************************
// * VERIFY LINK CLICKED            *
// **********************************

if (!empty($_GET["p"])) {
$p=base64_decode($_GET["p"]);
$s=$_GET["s"];
do {
//reset_code and reset_selector match with the value in database?
$sql = $db->prepare("SELECT reset_code FROM utenti WHERE reset_selector=? and reset_code!='' limit 1");
$sql->bindParam(1, $s);
$sql->execute();
if (!$sql->rowCount()) {$message="Isn't possible reset your password, retry.";$debug="Selector not found in DB: Reset code probably expired<br>";break;}
$reset_db=$sql->fetchColumn();
$ok=password_verify($p, $reset_db);
$debug.="Reset Code ".($ok)?"Match <br>":"don't match!<br>";

} while(0);
}

// **********************************
// * NEW PW SUBMITTED               *
// **********************************

if (isset($_POST["newpw"]) && !empty($_POST["p"]) && !empty($_POST["s"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
$ok=1; //means, if an error occured let the user retry to set the pw
//check if email is valid, lenght new password
$e=trim($_POST["email"]);
if (filter_var($e, FILTER_VALIDATE_EMAIL)===false or !preg_match('/@.+\./', $e)) {$error="Invalid Email";break;}
if ($_POST["password"]!=$_POST["confirm_password"]) {$error="Password mismatch";break;}
if (strlen($_POST["password"])<8) {$error="Password too short (minimum 8 character)";break;}
$p=base64_decode($_POST["p"]);
$s=$_POST["s"];

$npw=$_POST["password"];

do {

//get reset_code from db
$sql = $db->prepare("SELECT * FROM utenti WHERE reset_selector=? and email=? and reset_code!='' limit 1");
$sql->bindParam(1, $s);
$sql->bindParam(2, $e);
$sql->execute();

//if email and selector code doesn't match probably is a typo error in the email field (let the user retry)
if (!$sql->rowCount()) {$message="Isn't possible reset your password, check typo in your email, retry.";$debug="Selector Code and Email doesn't match<br>";break;}
$row = $sql->fetch(PDO::FETCH_ASSOC);	
$verified=password_verify($p, $row["reset_code"]);

$ok=0; //error after this point will invalidate the selector and reset code, so the user have to restart process from the beginnin with a new email link request


//right email and selector, but wrong code, could be an attack: reset code and selector deleted, url in the email become invalid
if (!$verified) {
$message="Security warning, reset password failed";$debug="Reset Code don't match<br>";
$sql = $db->prepare("UPDATE utenti SET reset_code='', reset_selector='' WHERE id=".$row["id"]." limit 1");
$sql->execute();
break;
}

//update new password only if reset_selector and email match with values in database and reset_code is verified
$hash = password_hash($_POST['password'].PEPPER, PASSWORD_DEFAULT, ['cost' => 12]);
$sql = $db->prepare("UPDATE utenti SET password=?,reset_selector='', reset_code='' WHERE id=".$row["id"]." limit 1");
$sql->bindParam(1, $hash);
$sql->execute();
if (!$sql->rowCount()) {$message="Error during update password.";$debug="Error saving new password<br>";break;}
$pwupdated=1;
} while(0);
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Reset Password</title>
	<link href='http://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>
    <link href=style.css  rel='stylesheet' type='text/css'>
	</head>
<body>

	<div class="header">
		<a href="/">Secure Login 3R<br><small>Register, Remember (cookie), Reset - Script in PHP with DB Mysql and query PDO</small></a>
	</div>

	
		<br>


	
<? if ($ok) { ?>
<h1>Set New Password</h1>	
    <br><p style=color:#00C><b><?= $message ?></b></p>
<form action="reset.php" method="POST">
<input type=hidden name=p value="<?=base64_encode($p);?>">
<input type=hidden name=s value="<?=$s;?>">
		<input type="email" required placeholder="Enter your email" name="email" value=<?=$_POST["email"];?>>
		<input type="password" required placeholder="New password" name="password" pattern=".{8,}" minlength="8">
		<input type="password" required placeholder="Confirm New password" name="confirm_password">
		<input type="submit" name=newpw>
	</form>
	<script>
var password = document.getElementsByName("password")[0]
  , confirm_password = document.getElementsByName("confirm_password")[0];

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
</script>
<?
}elseif ($verified && $pwupdated) {
?>
<h1>New Password Set</h1>	
	<span>Try it: <a href="login.php">login now</a></span>
<?
}else{
?>
	<h1>Reset Password</h1>	
	<span>or <a href="login.php">login here</a></span>
    <br><p style=color:#00C><b><?= $message ?></b></p>
	<form action="reset.php" method="POST">
		<input type="email" required placeholder="Enter your email" name="email" value=<?=$_POST["email"];?>>
		<input type="submit" name=reset>
	</form>
<?
}
?>
</body>
</html>
<?
//debug
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
