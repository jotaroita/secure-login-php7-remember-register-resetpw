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
try{
    $db = new pdo( 'mysql:host='.$hosting.';dbname='.$database,$database_user,$database_password,
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    //echo(json_encode(array('outcome' => true)));
}
catch(PDOException $ex){
    //echo(json_encode(array('outcome' => false, 'message' => 'Unable to connect')));
	die("Unable to Connect");
}
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