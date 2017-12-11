<!DOCTYPE html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<?php
//////////////////////////////////////////////////////////
// echopay.php                                          //
// v1.62 - last edited 11/26/2003                       //
//                                                      //
// Released under the GNU General Public License        //
//////////////////////////////////////////////////////////
?>

<HTML><HEAD>
<TITLE>ECHOpayform - Download Your Copy at http://www.echopay.com/</TITLE>
<link rel="stylesheet" type="text/css" href="style.css">
<script language="JavaScript" src="checkform.js" type="text/javascript"></script>
</HEAD>
<BODY>

<?php 
require("info.php");
if ($_REQUEST['ep_submit']) {
	include("includes/echophp.class");
	
	$echoPHP = new EchoPHP;
	$echoPHP->set_EchoServer("https://wwws.echo-inc.com/scripts/INR200.EXE");
	$echoPHP->set_order_type("S");
	$echoPHP->set_merchant_echo_id(MERC_ID); 
      	$echoPHP->set_merchant_pin(MERC_PIN);        
    	$echoPHP->set_billing_ip_address($REMOTE_ADDR);
    	$echoPHP->set_billing_phone($_REQUEST['phone_num']);
    	$echoPHP->set_debug(DEBUG);
    	$echoPHP->set_billing_first_name($_REQUEST['first_name']); 
    	$echoPHP->set_billing_last_name($_REQUEST['last_name']); 
    	$echoPHP->set_billing_address1($_REQUEST['address1']);
	$echoPHP->set_billing_address2($_REQUEST['address2']);
    	$echoPHP->set_billing_city($_REQUEST['city']);
    	$echoPHP->set_billing_state($_REQUEST['state']);
    	$echoPHP->set_billing_zip($_REQUEST['zipcode']);
    	$echoPHP->set_billing_country($_REQUEST['billing_country']);
    	$echoPHP->set_billing_email($_REQUEST['email_addr']);
    	$echoPHP->set_counter($_REQUEST['scounter']);
	$echoPHP->set_grand_total($_REQUEST['amount']);
	
	if ($_REQUEST['payment_type'] == "cc") {
	        $echoPHP->set_transaction_type("EV");
          	$echoPHP->set_cc_number($_REQUEST['cc_num']);
	    	$echoPHP->set_ccexp_month($_REQUEST['month']);
	    	$echoPHP->set_ccexp_year($_REQUEST['year']);
		$echoPHP->set_cnp_security($_REQUEST['cnp_security']);
   		$ECHO_ERROR = (!($echoPHP->Submit()));
   		
	    	if ($ECHO_ERROR) 
	    	{
	    		if ($echoPHP->decline_code == "1013")
		   	{
		   		echo "<b>ERROR:</b> Your ECHO-ID or PIN is missing from this form, or is not setup correctly. To ensure your ECHO-ID and PIN are correct, you should be able to login to the <a href=\"https://wwws.echo-inc.com/login.asp\">ECHO Merchant Center</a>. If you can't, please contact <i>ECHO</i> Customer Service at 800-233-0406 Ext. 1 and they will help you resolve the problem.<br><br>Otherwise, you can email <a href=\"mailto:developer-support@echo-inc.com\"><i>ECHO</i> Developer Support</a> for assistance.";
   			}
   			else
   			{
	        		print("<b>ERROR:</b> Verification of your account FAILED.  Please go back and check the form.<br><br>");
		        	echo $echoPHP->get_echoresponse();
			}
			echo "</body></html>";
		        die("");
	    	} 
	    	else 
	    	{ 
	    		echo "Thank you for your order!<br><br>";
	    		echo $echoPHP->get_echoresponse(); 
	    	}
	} 
	if ($_REQUEST['payment_type'] == "echeck") {
		$echoPHP->set_transaction_type("DD");
		$echoPHP->set_ec_account_type($_REQUEST['ec_account_type']);
		$echoPHP->set_ec_bank_name($_REQUEST['ec_bank_name']);
		$echoPHP->set_ec_first_name($_REQUEST['first_name']);
		$echoPHP->set_ec_last_name($_REQUEST['last_name']);
		$echoPHP->set_ec_address1($_REQUEST['address1']);
		$echoPHP->set_ec_address2($_REQUEST['address2']);	
		$echoPHP->set_ec_city($_REQUEST['city']);
		$echoPHP->set_ec_state($_REQUEST['state']);
		$echoPHP->set_ec_zip($_REQUEST['zipcode']);
		$echoPHP->set_ec_rt($_REQUEST['ec_rt']);
		$echoPHP->set_ec_account($_REQUEST['ec_account']);
		$echoPHP->set_ec_serial_number($_REQUEST['ec_serial_number']);
		$echoPHP->set_ec_payee(CHECK_PAYEE); 
		$echoPHP->set_ec_id_state($_REQUEST['ec_id_state']);
		$echoPHP->set_ec_id_number($_REQUEST['ec_id_number']);
		$echoPHP->set_ec_id_type($_REQUEST['ec_id_type']);
		if ($_REQUEST['ec_account_type'] == "P") { 
			$ec_payment_type = "WEB"; 
		} else { 
			$ec_payment_type = "CTX"; 
		}
		$echoPHP->set_ec_payment_type($_REQUEST['ec_payment_type']);
	    
		$ECHO_ERROR = (!($echoPHP->Submit()));
	    	if ($ECHO_ERROR) 
	    	{
	    		if ($echoPHP->decline_code == "1013")
		   	{
		   		echo "<b>ERROR:</b> Your ECHO-ID or PIN is missing from this form, or is not setup correctly. To ensure your ECHO-ID and PIN are correct, you should be able to login to the <a href=\"https://wwws.echo-inc.com/login.asp\">ECHO Merchant Center</a>. If you can't, please contact <i>ECHO</i> Customer Service at 800-233-0406 Ext. 1 and they will help you resolve the problem.<br><br>Otherwise, you can email <a href=\"mailto:developer-support@echo-inc.com\"><i>ECHO</i> Developer Support</a> for assistance.";
   			}
   			else
   			{
	        		print("<b>ERROR:</b> Verification of your account FAILED.  Please go back and check the form.<br><br>");
		        	echo $echoPHP->get_echoresponse();
			}
			echo "</body></html>";
		        die("");
		}
		else 
	    	{ 
			echo "Thank you for your order!<br><br>";
			echo $echoPHP->get_echoresponse(); 
	    	}
	}
	include("includes/class.phpmailer.php");
	if ($_REQUEST['email_addr'] != "") {
		$mail = new phpmailer();
		$mail->IsSMTP();                                      // set mailer to use SMTP
		$mail->From = YOUR_EMAIL_ADDRESS;
		$mail->FromName = YOUR_NAME;
		$mail->Host = MAILSERVER;        // specify main and backup server
		$mail->AddAddress($_REQUEST['email_addr']);
		$mail->WordWrap = 80;                                 // set word wrap to 50 characters
		$mail->IsHTML(true);                                  // set email format to HTML
		$mail->Subject = EMAIL_SUBJECT;
		$mail->Body = "\r\nDear ". $_REQUEST['first_name'] . " " . $_REQUEST['last_name'] . ",\r\n<br>";
		$fd = fopen (THANK_YOU_EMAIL_TEXT, "r");
		$mail->Body .= fread ($fd, filesize (THANK_YOU_EMAIL_TEXT));
		fclose ($fd);
		$mail->Body .= "\r\n<br><br>\r\n" .  "Your reciept for $" . $_REQUEST['amount'] .".\r\n<br><br>\r\n";
		$mail->Body .= "Transaction Record:\r\n<br>" . $echoPHP->get_echotype2() ."\r\n<br>";
		if (!($mail->send())) {
			echo "mail error";
		}
	}
	$merchantmail = new phpmailer();
	$merchantmail->IsSMTP();                                      // set mailer to use SMTP
	$merchantmail->From = YOUR_EMAIL_ADDRESS;
	$merchantmail->FromName = YOUR_NAME;
	$merchantmail->Host = MAILSERVER;        // specify main and backup server
	$merchantmail->AddAddress(YOUR_EMAIL_ADDRESS);
	$merchantmail->WordWrap = 80;                                 // set word wrap to 50 characters
	$merchantmail->IsHTML(true);                                  // set email format to HTML
	$merchantmail->Subject = "ECHOPay Information";
	$merchantmail->Body = "\r\nPayment information:\r\n<br>";
	$merchantmail->Body .= "Name: " . $_REQUEST['first_name'] . " " . $_REQUEST['last_name'] ."\r\n<br>";
	$merchantmail->Body .= "Address: \r\n<br><dd>" . $_REQUEST['address1'] ."\r\n<br>";
	if ($_REQUEST['address2']) 
		$merchantmail->Body .= "<dd>" . $_REQUEST['address2'] ."\r\n<br>";
	$merchantmail->Body .= "<dd>" . $_REQUEST['city'] . "," . $_REQUEST['state'] . "  " . $_REQUEST['zipcode']."\r\n<br>";
	if ($_REQUEST['billing_province'])
		$merchantmail->Body .= "Province: " . $_REQUEST['billing_province'] . "\r\n<br>";
	if ($_REQUEST['billing_country'])
		$merchantmail->Body .= "Country: " . $_REQUEST['billing_country'] . "\r\n<br>";
	$merchantmail->Body .= "Phone: " . $_REQUEST['phone_num'] . "\r\n<br>";
	$merchantmail->Body .= "Email: " . $_REQUEST['email_addr'] . "\r\n<br><br>";
	$merchantmail->Body .= "Amount: $" . $_REQUEST['amount'] . "\r\n<br><br>";
	$merchantmail->Body .= "Payment Type: " . $_REQUEST['payment_type'] . "\r\n<br>";
	$merchantmail->Body .= "Comments: " . $_REQUEST['comments'] ."\r\n<br>";
	$merchantmail->Body .= "Transaction Record:\r\n<br>" . $echoPHP->get_echotype2() ."\r\n<br>";
	if (!($merchantmail->send())) {
		echo "mail error";
	}
} 
else 
{
?>
<div align="center"><img src="<?php echo BANNER; ?>"></div><br>
<?php include(TITLE_TEXT); ?>
<FORM name="epform" method="post" action="<?php echo $PHP_SELF; ?>" onsubmit="return check_form(this);">
<input type="hidden" name="ep_submit" value="yes">
<P>
	<TABLE cellspacing="0" cellpadding="1" width="500" align="center" border="0">
		<TR>
			<TD class="topLeftTop">
<P align="right" class="required">First Name:</P>
			</TD>
			<TD class="topTop">
				<INPUT style="WIDTH: 153px; HEIGHT: 22px" size="16" name="first_name" value="<? echo $_POST['billing_first_name'] ?>">
			</TD>
			<TD class="topTop">
<P align="right" class="required">Last Name:</P>
			</TD>
			<TD class="topRightTop">
				<INPUT style="WIDTH: 129px; HEIGHT: 22px" size="14" name="last_name" value="<? echo $_POST['billing_last_name'] ?>">
			</TD>
		</TR>
		<TR>
			<TD class="topLeft">
<P align="right" class="required">Address:</P>
			</TD>
			<TD colspan="3" class="topRight">
				<INPUT style="WIDTH: 345px; HEIGHT: 22px" maxlength="225" size="44" name="address1" value="<? echo $_POST['billing_address'] ?>">
			</TD>
		</TR>
		<TR>
			<TD class="topLeft">
<P align="right">Address:</P>
			</TD>
			<TD colspan="3" class="topRight">
				<INPUT style="WIDTH: 344px; HEIGHT: 22px" maxlength="225" size="44" name="address2" value="<? echo $_POST['billing_address2'] ?>">
			</TD>
		</TR>
		<TR>
			<TD class="topLeft">
<P align="right" class="required">City:</P>
			</TD>
			<TD>
				<INPUT name="city" value="<? echo $_POST['billing_city'] ?>">
			</TD>
			<TD>
<P align="right">State:</P>
			</TD>
			<TD class="topRight">
				<SELECT name="state">
					<OPTION selected></OPTION>
					<OPTION value="AL">AL</OPTION>
					<OPTION value="AK">AK</OPTION>
					<OPTION value="AZ">AZ</OPTION>
					<OPTION value="AR">AR</OPTION>
					<OPTION value="CA">CA</OPTION>
					<OPTION value="CO">CO</OPTION>
					<OPTION value="CT">CT</OPTION>
					<OPTION value="DE">DE</OPTION>
					<OPTION value="DC">DC</OPTION>
					<OPTION value="FL">FL</OPTION>
					<OPTION value="GA">GA</OPTION>
					<OPTION value="HI">HI</OPTION>
					<OPTION value="ID">ID</OPTION>
					<OPTION value="IL">IL</OPTION>
					<OPTION value="IN">IN</OPTION>
					<OPTION value="IA">IA</OPTION>
					<OPTION value="KS">KS</OPTION>
					<OPTION value="KY">KY</OPTION>
					<OPTION value="LA">LA</OPTION>
					<OPTION value="ME">ME</OPTION>
					<OPTION value="MD">MD</OPTION>
					<OPTION value="MA">MA</OPTION>
					<OPTION value="MI">MI</OPTION>
					<OPTION value="MN">MN</OPTION>
					<OPTION value="MS">MS</OPTION>
					<OPTION value="MO">MO</OPTION>
					<OPTION value="MT">MT</OPTION>
					<OPTION value="NE">NE</OPTION>
					<OPTION value="NV">NV</OPTION>
					<OPTION value="NH">NH</OPTION>
					<OPTION value="NJ">NJ</OPTION>
					<OPTION value="NM">NM</OPTION>
					<OPTION value="NY">NY</OPTION>
					<OPTION value="NC">NC</OPTION>
					<OPTION value="ND">ND</OPTION>
					<OPTION value="OH">OH</OPTION>
					<OPTION value="OK">OK</OPTION>
					<OPTION value="OR">OR</OPTION>
					<OPTION value="PA">PA</OPTION>
					<OPTION value="RI">RI</OPTION>
					<OPTION value="SC">SC</OPTION>
					<OPTION value="SD">SD</OPTION>
					<OPTION value="TN">TN</OPTION>
					<OPTION value="TX">TX</OPTION>
					<OPTION value="UT">UT</OPTION>
					<OPTION value="VT">VT</OPTION>
					<OPTION value="VA">VA</OPTION>
					<OPTION value="WA">WA</OPTION>
					<OPTION value="WV">WV</OPTION>
					<OPTION value="WI">WI</OPTION>
					<OPTION value="WY">WY</OPTION>
				</SELECT>
			</TD>
		</TR>
		<TR>
		        <TD class="topLeft">
			        <p align="right">Province:</p>
		        </TD>
		        <TD class="topRight" colspan="3"><input type="text" name="billing_province" maxlength="32" value="<? echo $_POST['billing_province'] ?>">
		        </TD>
 		</TR>


		<TR>
			<TD class="topLeft">
				<P align="right" class="required">Postal Code:</P>
			</TD>
			<TD>
				<INPUT maxlength="10" size="10" name="zipcode" value="<? echo $_POST['billing_zip'] ?>">
			</TD>
			<TD colspan="2" class="topRight">&nbsp;
			</TD>
		</TR>
		<TR>
					<TD class="topLeft">
						<P align="right" class="required">Country:</P>
		                        </TD>
		                        <TD colspan="3" class="topRight">
		                                <select style="WIDTH: 398px; HEIGHT: 22px" name="billing_country">
					<option value="AF">Afghanistan</option>
		<option value="AL">Albania</option>
		<option value="DZ">Algeria</option>
		<option value="AS">American Samoa</option>
		<option value="AD">Andorra</option>
		<option value="AO">Angola</option>
		<option value="AI">Anguilla</option>
		<option value="AQ">Antarctica</option>
		<option value="AG">Antigua and Barbuda</option>
		<option value="AR">Argentina</option>
		<option value="AM">Armenia</option>
		<option value="AW">Aruba</option>
		<option value="AU">Australia</option>
		<option value="AT">Austria</option>
		<option value="AZ">Azerbaijan</option>
		<option value="BS">Bahamas</option>
		<option value="BH">Bahrain</option>
		<option value="BD">Bangladesh</option>
		<option value="BB">Barbados</option>
		<option value="BY">Belarus</option>
		<option value="BE">Belgium</option>
		<option value="BZ">Belize</option>
		<option value="BJ">Benin</option>
		<option value="BM">Bermuda</option>
		<option value="BT">Bhutan</option>
		<option value="BO">Bolivia</option>
		<option value="BA">Bosnia and Herzegowina</option>
		<option value="BW">Botswana</option>
		<option value="BV">Bouvet Island</option>
		<option value="BR">Brazil</option>
		<option value="IO">British Indian Ocean Territory</option>
		<option value="BN">Brunei Darussalam</option>
		<option value="BG">Bulgaria</option>
		<option value="BF">Burkina Faso</option>
		<option value="BI">Burundi</option>
		<option value="KH">Cambodia</option>
		<option value="CM">Cameroon</option>
		<option value="CA">Canada</option>
		<option value="CV">Cape Verde</option>
		<option value="KY">Cayman Islands</option>
		<option value="CF">Central African Republic</option>
		<option value="TD">Chad</option>
		<option value="CL">Chile</option>
		<option value="CN">China</option>
		<option value="CX">Christmas Island</option>
		<option value="CC">Cocos (Keeling) Islands</option>
		<option value="CO">Colombia</option>
		<option value="KM">Comoros</option>
		<option value="CG">Congo</option>
		<option value="CK">Cook Islands</option>
		<option value="CR">Costa Rica</option>
		<option value="CI">Cote D'Ivoire</option>
		<option value="HR">Croatia</option>
		<option value="CU">Cuba</option>
		<option value="CY">Cyprus</option>
		<option value="CZ">Czech Republic</option>
		<option value="DK">Denmark</option>
		<option value="DJ">Djibouti</option>
		<option value="DM">Dominica</option>
		<option value="DO">Dominican Republic</option>
		<option value="TP">East Timor</option>
		<option value="EC">Ecuador</option>
		<option value="EG">Egypt</option>
		<option value="SV">El Salvador</option>
		<option value="GQ">Equatorial Guinea</option>
		<option value="ER">Eritrea</option>
		<option value="EE">Estonia</option>
		<option value="ET">Ethiopia</option>
		<option value="FK">Falkland Islands (Malvinas)</option>
		<option value="FO">Faroe Islands</option>
		<option value="FJ">Fiji</option>
		<option value="FI">Finland</option>
		<option value="FR">France</option>
		<option value="FX">France, MEtropolitan</option>
		<option value="GF">French Guiana</option>
		<option value="PF">French Polynesia</option>
		<option value="TF">French Southern Territories</option>
		<option value="GA">Gabon</option>
		<option value="GM">Gambia</option>
		<option value="GE">Georgia</option>
		<option value="DE">Germany</option>
		<option value="GH">Ghana</option>
		<option value="GI">Gibraltar</option>
		<option value="GR">Greece</option>
		<option value="GL">Greenland</option>
		<option value="GD">Grenada</option>
		<option value="GP">Guadeloupe</option>
		<option value="GU">Guam</option>
		<option value="GT">Guatemala</option>
		<option value="GN">Guinea</option>
		<option value="GW">Guinea-bissau</option>
		<option value="GY">Guyana</option>
		<option value="HT">Haiti</option>
		<option value="HM">Heard and Mc Donald Islands</option>
		<option value="HN">Honduras</option>
		<option value="HK">Hong Kong</option>
		<option value="HU">Hungary</option>
		<option value="IS">Iceland</option>
		<option value="IN">India</option>
		<option value="ID">Indonesia</option>
		<option value="IR">Iran (Islamic Republic of)</option>
		<option value="IQ">Iraq</option>
		<option value="IE">Ireland</option>
		<option value="IL">Israel</option>
		<option value="IT">Italy</option>
		<option value="JM">Jamaica</option>
		<option value="JP">Japan</option>
		<option value="JO">Jordan</option>
		<option value="KZ">Kazakhstan</option>
		<option value="KE">Kenya</option>
		<option value="KI">Kiribati</option>
		<option value="KP">Korea, Democratic People's Republic of</option>
		<option value="KR">Korea, Republic of</option>
		<option value="KW">Kuwait</option>
		<option value="KG">Kyrgyzstan</option>
		<option value="LA">Lao People's Democratic Republic</option>
		<option value="LV">Latvia</option>
		<option value="LB">Lebanon</option>
		<option value="LS">Lesotho</option>
		<option value="LR">Liberia</option>
		<option value="LY">Libyan Arab Jamahiriya</option>
		<option value="LI">Liechtenstein</option>
		<option value="LT">Lithuania</option>
		<option value="LU">Luxembourg</option>
		<option value="MO">Macau</option>
		<option value="MK">Macedonia, The Former Yugoslav Republic of</option>
		<option value="MG">Madagascar</option>
		<option value="MW">Malawi</option>
		<option value="MY">Malaysia</option>
		<option value="MV">Maldives</option>
		<option value="ML">Mali</option>
		<option value="MT">Malta</option>
		<option value="MH">Marshall Islands</option>
		<option value="MQ">Martinique</option>
		<option value="MR">Mauritania</option>
		<option value="MU">Mauritius</option>
		<option value="YT">Mayotte</option>
		<option value="MX">Mexico</option>
		<option value="FM">Micronesia, Federated States of</option>
		<option value="MD">Moldova, Republic of</option>
		<option value="MC">Monaco</option>
		<option value="MN">Mongolia</option>
		<option value="MS">Montserrat</option>
		<option value="MA">Morocco</option>
		<option value="MZ">Mozambique</option>
		<option value="MM">Myanmar</option>
		<option value="NA">Namibia</option>
		<option value="NR">Nauru</option>
		<option value="NP">Nepal</option>
		<option value="NL">Netherlands</option>
		<option value="AN">Netherlands Antilles</option>
		<option value="NC">New Caledonia</option>
		<option value="NZ">New Zealand</option>
		<option value="NI">Nicaragua</option>
		<option value="NE">Niger</option>
		<option value="NG">Nigeria</option>
		<option value="NU">Niue</option>
		<option value="NF">Norfolk Island</option>
		<option value="MP">Northern Mariana Islands</option>
		<option value="NO">Norway</option>
		<option value="OM">Oman</option>
		<option value="PK">Pakistan</option>
		<option value="PW">Palau</option>
		<option value="PA">Panama</option>
		<option value="PG">Papua New Guinea</option>
		<option value="PY">Paraguay</option>
		<option value="PE">Peru</option>
		<option value="PH">Philippines</option>
		<option value="PN">Pitcairn</option>
		<option value="PL">Poland</option>
		<option value="PT">Portugal</option>
		<option value="PR">Puerto Rico</option>
		<option value="QA">Qatar</option>
		<option value="RE">Reunion</option>
		<option value="RO">Romania</option>
		<option value="RU">Russian Federation</option>
		<option value="RW">Rwanda</option>
		<option value="KN">Saint Kitts and Nevis</option>
		<option value="LC">Saint Lucia</option>
		<option value="VC">Saint Vincent and the Grenadines</option>
		<option value="WS">Samoa</option>
		<option value="SM">San Marino</option>
		<option value="ST">Sao Tome and Principe</option>
		<option value="SA">Saudi Arabia</option>
		<option value="SN">Senegal</option>
		<option value="SC">Seychelles</option>
		<option value="SL">Sierra Leone</option>
		<option value="SG">Singapore</option>
		<option value="SK">Slovakia (Slovak Republic)</option>
		<option value="SI">Slovenia</option>
		<option value="SB">Solomon Islands</option>
		<option value="SO">Somalia</option>
		<option value="ZA">South Africa</option>
		<option value="GS">South Georgia and the South Sandwich Islands</option>
		<option value="ES">Spain</option>
		<option value="LK">Sri Lanka</option>
		<option value="SH">St. Helena</option>
		<option value="PM">St. Pierre and Miquelon</option>
		<option value="SD">Sudan</option>
		<option value="SR">Suriname</option>
		<option value="SJ">Svalbard and Jan Mayen Islands</option>
		<option value="SZ">Swaziland</option>
		<option value="SE">Sweden</option>
		<option value="CH">Switzerland</option>
		<option value="SY">Syrian Arab Republic</option>
		<option value="TW">Taiwan, Province of China</option>
		<option value="TJ">Tajikistan</option>
		<option value="TZ">Tanzania, United Republic of</option>
		<option value="TH">Thailand</option>
		<option value="TG">Togo</option>
		<option value="TK">Tokelau</option>
		<option value="TO">Tonga</option>
		<option value="TT">Trinidad and Tobago</option>
		<option value="TN">Tunisia</option>
		<option value="TR">Turkey</option>
		<option value="TM">Turkmenistan</option>
		<option value="TC">Turks and Caicos Islands</option>
		<option value="TV">Tuvalu</option>
		<option value="UG">Uganda</option>
		<option value="UA">Ukraine</option>
		<option value="AE">United Arab Emirates</option>
		<option value="GB">United Kingdom</option>
		<option value="US" selected>United States</option>
		<option value="UM">United States Minor Outlying Islands</option>
		<option value="UY">Uruguay</option>
		<option value="UZ">Uzbekistan</option>
		<option value="VU">Vanuatu</option>
		<option value="VA">Vatican City State (Holy See)</option>
		<option value="VE">Venezuela</option>
		<option value="VN">Viet Nam</option>
		<option value="VG">Virgin Islands (British)</option>
		<option value="VI">Virgin Islands (U.S.)</option>
		<option value="WF">Wallis and Futuna Islands</option>
		<option value="EH">Western Sahara</option>
		<option value="YE">Yemen</option>
		<option value="YU">Yugoslavia</option>
		<option value="ZR">Zaire</option>
		<option value="ZM">Zambia</option>
		<option value="ZW">Zimbabwe</option>
						</select>
		                        </TD>
		</TR>
		<TR>
			<TD class="topLeft">
<P align="right">E-mail:</P>
			</TD>
			<TD>
				<INPUT name="email_addr" value="<? echo $_POST['billing_email'] ?>">
			</TD>
			<TD>
<P align="right" class="required">Phone Number:</P>
			</TD>
			<TD class="topRight">
				<INPUT style="WIDTH: 104px; HEIGHT: 22px" maxlength="20" size="13" name="phone_num" value="<? echo $_POST['billing_phone'] ?>">
			</TD>
		</TR>
		<TR>
			<TD colspan="4" class="topBothSides"><P align=right class="required"><FONT size=1>required</FONT></P>
			</TD>
		</TR>
		<TR>
			<TD colspan="4" class="topBothSides">&nbsp; Please select your method of 
				Payment:
			</TD>
		</TR>
		<TR>
			<TD colspan="4" class="ccTitle">
				<input type="radio" name="payment_type" value="cc" checked>&nbsp;Credit Card
			</TD>
		</TR>
		<TR class="cc">
			<TD colspan="2" class="ccLeft">
				<P align="right">Credit Card Type:</P>
				</TD>
				<TD colspan="2" class="ccRight">
				<select name="cc_type">
				<option></option>
				<?php if($ep_payment['allow_visa'] == "Y") { ?>
				<option value="visa">Visa</option>
				<?php }
				if($ep_payment['allow_mastercard'] == "Y") { ?>
				<option value="master">Master Card</option>
				<?php }
				if($ep_payment['allow_discover'] == "Y") { ?>
				<option value="discover">Discover</option>
				<?php }
				if($ep_payment['allow_amex'] == "Y") { ?>
				<option value="amex">American Express</option>
				<?php } ?>
				</select>
			</TD>
		</TR>
		<TR class="cc">
			<TD colspan="2" class="ccLeft">
<P align="right">Credit Card Number:</P>
			</TD>
			<TD colspan="2" class="ccRight">
				<INPUT name="cc_num">
			</TD>
		</TR>
		<TR class="cc">
			<td class="ccLeft" colspan="2" align="right">Security Code:</td>
			<td class="ccRight" colspan="2"><INPUT name="cnp_security" maxlength="4" size="10"></td>
		</TR>
		<TR class="cc">
			<TD class="ccLeft" colspan="2">&nbsp;</TD>
			<TD class="ccRight" colspan="2"><INPUT type="checkbox" name="no_cnp_security" value="Y"> Check here if no security code</TD>
		</TR>
		<TR class="cc">
			<TD colspan="2" class="ccLeft">
<P align="right">Expiration Date:</P>
			</TD>
			<TD colspan="2" class="ccRight">
				<SELECT size="1" name="month">
					<OPTION value="01" selected>1</OPTION>
					<OPTION value="02">2</OPTION>
					<OPTION value="03">3</OPTION>
					<OPTION value="04">4</OPTION>
					<OPTION value="05">5</OPTION>
					<OPTION value="06">6</OPTION>
					<OPTION value="07">7</OPTION>
					<OPTION value="08">8</OPTION>
					<OPTION value="09">9</OPTION>
					<OPTION value="10">10</OPTION>
					<OPTION value="11">11</OPTION>
					<OPTION value="12">12</OPTION>
				</SELECT>
				<SELECT size="1" name="year">
				<?php
				$final_year = date("Y") + 9;
				for ($this_year = date("Y"); $this_year <= $final_year; $this_year++) {
					echo "<OPTION value=$this_year";
					if ($this_year == date("Y")) {
						echo " selected";
					}
					echo ">$this_year</OPTION>";
				}
				?>
				</SELECT>
			</TD>
		</TR>
		<TR>
			<TD colspan="4" class="checkTitle">
				<input type="radio" name="payment_type" value="echeck">&nbsp;Electronic Check
			</TD>
		</TR>
		<TR class="check">
			<TD nowrap class="checkLeft">
<P align="right">Bank Name:<FONT size="2">  </FONT></P>
			</TD>
			<TD class="checkNone">
				<INPUT name="ec_bank_name">
			</TD>
			<TD nowrap class="checkNone">
<P align="right">Check No.</P>
			</TD>
			<TD class="checkRight">
				<INPUT size="11" name="ec_serial_number">
			</TD>
		</TR>
		<TR class="check">
			<TD nowrap class="checkLeft">
<P align="right">Routing Number:</P>
			</TD>
			<TD class="checkNone">
				<INPUT name="ec_rt" size="9" maxlength="9">
			</TD>
			<TD nowrap class="checkNone">
<P align="right">Account Number:</P>
			</TD>
			<TD class="checkRight">
				<INPUT name="ec_account">
			</TD>
  <TR>
	<td nowrap class="checkLeft">
      <P align=right>Account Type:</P> </td>
	<td colspan="3" class="checkRight"><select name="ec_account_type">
	      <option selected></option>
		  <option value="P">Personal</option>
		  <option value="B">Business</option>
	</select>
	</td>
	</TR>
			<TR>
				<TD colspan="4" class="checkBoth">
      <P align=center>Identification for Checking information:</P>
				</TD>
			</TR>
			<TR>
				<TD nowrap valign="top" colspan="4" class="checkBottom">
				<CENTER><FONT name="Verdana">ID Type:
					<SELECT name="ec_id_type">
						<OPTION selected></OPTION>
						<OPTION value="DL">Drivers License</OPTION>
						<OPTION value="SS">Goverment ID</OPTION>
						<OPTION value="MI">Military ID</OPTION>
						<OPTION value="GN">Generic ID</OPTION>
					</SELECT>ID Num.
					<INPUT size="10" name="ec_id_number">State
					<SELECT name="ec_id_state">
						<OPTION selected></OPTION>
						<OPTION value="AL">AL</OPTION>
						<OPTION value="AK">AK</OPTION>
						<OPTION value="AZ">AZ</OPTION>
						<OPTION value="AR">AR</OPTION>
						<OPTION value="CA">CA</OPTION>
						<OPTION value="CO">CO</OPTION>
						<OPTION value="CT">CT</OPTION>
						<OPTION value="DE">DE</OPTION>
						<OPTION value="DC">DC</OPTION>
						<OPTION value="FL">FL</OPTION>
						<OPTION value="GA">GA</OPTION>
						<OPTION value="HI">HI</OPTION>
						<OPTION value="ID">ID</OPTION>
						<OPTION value="IL">IL</OPTION>
						<OPTION value="IN">IN</OPTION>
						<OPTION value="IA">IA</OPTION>
						<OPTION value="KS">KS</OPTION>
						<OPTION value="KY">KY</OPTION>
						<OPTION value="LA">LA</OPTION>
						<OPTION value="ME">ME</OPTION>
						<OPTION value="MD">MD</OPTION>
						<OPTION value="MA">MA</OPTION>
						<OPTION value="MI">MI</OPTION>
						<OPTION value="MN">MN</OPTION>
						<OPTION value="MS">MS</OPTION>
						<OPTION value="MO">MO</OPTION>
						<OPTION value="MT">MT</OPTION>
						<OPTION value="NE">NE</OPTION>
						<OPTION value="NV">NV</OPTION>
						<OPTION value="NH">NH</OPTION>
						<OPTION value="NJ">NJ</OPTION>
						<OPTION value="NM">NM</OPTION>
						<OPTION value="NY">NY</OPTION>
						<OPTION value="NC">NC</OPTION>
						<OPTION value="ND">ND</OPTION>
						<OPTION value="OH">OH</OPTION>
						<OPTION value="OK">OK</OPTION>
						<OPTION value="OR">OR</OPTION>
						<OPTION value="PA">PA</OPTION>
						<OPTION value="RI">RI</OPTION>
						<OPTION value="SC">SC</OPTION>
						<OPTION value="SD">SD</OPTION>
						<OPTION value="TN">TN</OPTION>
						<OPTION value="TX">TX</OPTION>
						<OPTION value="UT">UT</OPTION>
						<OPTION value="VT">VT</OPTION>
						<OPTION value="VA">VA</OPTION>
						<OPTION value="WA">WA</OPTION>
						<OPTION value="WV">WV</OPTION>
						<OPTION value="WI">WI</OPTION>
						<OPTION value="WY">WY</OPTION>
					</SELECT></FONT></CENTER></FONT>
				</TD>
			</TR>
			<TR>
				<TD colspan="4">&nbsp;
				</TD>
			</TR>
			<TR>
				<TD colspan="4" class="ccTitle">
				<CENTER>Please enter the amount: $
					<INPUT size="8" name="amount" value="<? echo $_POST['grand_total'] ?>">
					<br>&nbsp;<FONT size=1>You must use dd.cc format, e.g. 49.99 format</FONT></CENTER>
				</TD>
			</TR>
			<TR>
    			<TD align=right colSpan=4>&nbsp;</TD></TR>
  			<TR>
			    <TD align="right" valign="top">Comments:</TD>
			    <TD align=right colSpan=3>
			      <P align=left><TEXTAREA name=comments rows=4 cols=40 wrap="soft"></TEXTAREA></P></TD></TR>
			<TR>
				<TD colspan="4" align="right">
					<P align="right">
					<INPUT type="hidden" name="scounter" value="<?php mt_srand((double) microtime() * 1000000); echo mt_rand(); ?>">
					<INPUT type="submit" value="Submit" name="Submit">
					<INPUT type="reset" value="Reset"></P>
				</TD>
			</TR>
	</TABLE></p>
	<p align="center"><a href="http://www.echo-inc.com/" target="_blank"><img border="0" alt="ECHO Inc." src="echo_certified.gif"></a></p>
</FORM><?php 
}
?>
</BODY>
</HTML>
