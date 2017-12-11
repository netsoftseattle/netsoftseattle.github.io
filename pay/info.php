<?php
//////////////////////////////////////////////////////////
// info.php	                                        //
// v1.3 - last edited 08/27/03                          //
//                                                      //
//  Released under the GNU General Public License       //
//////////////////////////////////////////////////////////
// All of these items require values.  Please fill them all in.

// the banner image name
define('BANNER', 'banner.gif');

// the filename where the top text will be located (can be html)
define('TITLE_TEXT', 'title.txt');

// your echo merchant id
define('MERC_ID', '4024350588'); 

// your echo pin
define('MERC_PIN', '75118311'); 

// Payee for checks
define('CHECK_PAYEE', 'Shansoft');

// a mailserver that you can send mail with, for most unix boxes this can be localhost or 127.0.0.1
define('MAILSERVER', '127.0.0.1'); 

// your email address (used to get a copy of the thank you email and the address that the email is sent from)
define('YOUR_EMAIL_ADDRESS', 'ychen@shansoft.com'); 

// your name
define('YOUR_NAME', 'Yi Chen'); 

// the subject of the thank you email
define('EMAIL_SUBJECT', 'Thank You!'); 

// the filename where the thank you email text will be located
// the contents of this file is precede by Dear <the person's name> and  
//  followed by "Your reciept for $<amount>"
define('THANK_YOU_EMAIL_TEXT', 'thankyou.txt'); 

// here is the allowed credit card types
// change the values to "Y" if you want to accept the card
// change the values to "N" if you don't want to accept the card
$ep_payment["allow_visa"] = "Y";
$ep_payment["allow_mastercard"] = "Y";
$ep_payment["allow_discover"] = "N";
$ep_payment["allow_amex"] = "N";

// set to T to return back fields from ECHO, otherwise leave F
define('DEBUG', 'F');
?>
