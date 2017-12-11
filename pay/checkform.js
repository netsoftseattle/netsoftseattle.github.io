// not valid email
emailReg1 = /(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/
// valid email
emailReg2 = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,6}|[0-9]{1,3})(\]?)$/
// valid phone
phoneReg = /^\(?(\d{3})\)?\-?[\.\-\/ ]?(\d{3})[\.\-\/ ]?(\d{4})$/
intlphoneReg = /^\d(\d|\-){6,20}/
// valid amount
amountReg = /^\d{1,10}\.\d{2}$/

function check_form(epform) {
  epform.Submit.disabled = true;
  var error = 0;
  var error_message = "Errors have occured during the process of your form!\nPlease make the following corrections:\n\n";
  var payment_type = epform.payment_type.value;
  var payment_value = null;
  if (epform.payment_type.length) {
    for (var i = 0; i < epform.payment_type.length; i++)
      if (epform.payment_type[i].checked)
        payment_value = epform.payment_type[i].value;
  } else if (epform.payment_type.checked) {
    payment_value = epform.payment_type.value;
  } else if (epform.payment_type.value) {
    payment_value = epform.payment_type.value;
  }
  var first_name = epform.first_name.value;
  var last_name = epform.last_name.value;
  var address1 = epform.address1.value;
  var city = epform.city.value;
  var state = epform.state.value;
  var province = epform.billing_province.value;
  var zip = epform.zipcode.value;
  var country = epform.billing_country.value;
  var email = epform.email_addr.value;
  var phone = epform.phone_num.value;
   
  // amount field
  var amount = epform.amount.value;

  if (!first_name)
  {
      error_message += "* First name is missing.\n";
      error = 1;
  } 

  if (!last_name)
  {
      error_message += "* Last name is missing.\n";
      error = 1;
  } 

  if (!address1)
  {
      error_message += "* Address is missing.\n";
      error = 1;
  } 

  if (!city)
  {
      error_message += "* City is missing.\n";
      error = 1;
  } 

  if (!zip)
  {
      error_message += "* Zip code/Postal code is missing.\n";
      error = 1;
  } 

  if (!phone)
  {
      error_message += "* Phone number is missing.\n";
      error = 1;
  } 

  //if (!email)
  //{
  //    error_message += "* E-Mail address is missing.\n";
  //    error = 1;
  //} 

  if (state && province) 
  {
      error_message += "* Cannot Enter Both Billing State and Province.\n";
      error = 1;
  }

  if (country == 'US') {
    if (!state) {
          error_message += "* For United States, billing state is required.\n";
	  error = 1;
    }
    if (!(phoneReg.exec(phone))) {
    	error_message = error_message + "* Invalid Phone Number.\n";
    	error = 1;
    }
  } else {
    if (!province) {
    	 error_message += "* Billing province is required for non-US.\n";
	 error = 1;
    }
    if (!(intlphoneReg.exec(phone))) {
         error_message += "* Invalid international phone number (Digits & '-' only, no spaces).\n";
         error = 1;
    }    
  }
    
  //if (!(!emailReg1.exec(email) && emailReg2.test(email))) {
//	error_message = error_message + "* Invalid Email Address.\n";
//	error = 1;
  //}

  if (payment_value == "cc") {
	  // cycle through the form elements and trim all fields
	  var cc = '';
	  var cclen = 0;
	  var ccid = 0;
	  var myDate = new Date();
	  var thisMonth = myDate.getMonth()+1;
	  var thisYear = myDate.getFullYear();
	  var year = epform.year.options[epform.year.selectedIndex].value;
	  var month = epform.month.options[epform.month.selectedIndex].value;
	  var cclen;
	  var ccid;
	  month = month.substr(0,2);
	  var str = epform.cc_type.options[epform.cc_type.selectedIndex].value;
	  str=str.slice(0,3);
	  str=str.toLowerCase();
	  if (epform.cc_num.value) {
	    var cc = epform.cc_num.value;
	    cc=cc.replace(/[^0-9]/g, "")
	    cclen=cc.length;
	    ccid=cc.charAt(0);
	  }
	  else {
	    error_message += "* Credit Card Number Is Missing.\n";
	    error = 1;
	  }
	  switch (str) {
	    case "vis":
	      if (ccid != 4) {
		  error_message += "* Credit Card Number " + cc + " Is Not A Valid Visa.\n";
		  error = 1;
	      }
	      if (cclen != 16) {
		  error_message += "* You Have " + cclen + " Digits In Your Credit Card.  16 Digits Are Required.\n";
		  error = 1;
	      }
	      var cnp_len = 3;
	      break;
	    case "mas":
	      if (ccid != 5) {
	        error_message += "* Credit Card Number " + cc + " Is Not A Valid MasterCard.\n";
		error = 1;
	      }
	      if (cclen != 16) {
	        error_message += "* You Have " + cclen + " Digits In Your Credit Card.  16 Digits Are Required.\n";
		error = 1;
	      }
	      var cnp_len = 3;
	      break;
	    case "dis":
	        if (ccid != 6) {
	          error_message += "* Credit Card Number " + cc + " Is Not A Valid Discover.\n";
		  error = 1;
		}
	  	if (cclen != 16) {
	    	  error_message += "* You Have " + cclen + " Digits In Your Credit Card.  16 Digits Are Required.\n";
	    	  error = 1;
	  	}
	        var cnp_len = 4;
 	  	break;
	    case "ame":
	      if (ccid != 3) {
	      error_message += "* Credit Card Number " + cc + " Is Not A Valid American Express.\n";
	      error = 1;
	      }
	      if (cclen != 15) {
	        error_message += "* You Have " + cclen + " Digits In Your Credit Card.  15 Digit Are Required.\n";
		error = 1;
	      }
	      var cnp_len = 4;
	      break;
	    default:
		error_message += "* Please Select a Credit Card Type.\n";
		error = 1;
	      break;
	  }
	  
	  if (year == thisYear) {
	    if (month < thisMonth) {
	      error_message += "* Expiration Date Is Invalid.\n";
	      error = 1;
	    }
	  }
	  
	  if (!epform.no_cnp_security.checked) {
	    if (!epform.cnp_security.value) {
	      error_message += "* Card Security Code Is Missing.\n";
	      error = 1;
	    }
	    else {
	      var cnp_val = epform.cnp_security.value;
	      if (cnp_val.length != cnp_len) {
	        error_message += "* Card Security Code Is Invalid.\n";
		error = 1;
	        epform.cnp_security.focus();
	        epform.cnp_security.select();
	      }
	    }
	  }
  }
  if (payment_value == "echeck") 
  {
    var ec_rt = epform.ec_rt.value;
    var ec_account = epform.ec_account.value;
    var ec_account_type = epform.ec_account_type.value;
    var ec_id_type = epform.ec_id_type.value;
    var ec_id_number = epform.ec_id_number.value;
    var ec_id_state = epform.ec_id_state.value;
    var ec_bank_name = epform.ec_bank_name.value;
    var ec_serial_number = epform.ec_serial_number.value;

    if (ec_bank_name == "" || ec_serial_number == "" || ec_id_number == "" || ec_id_state == "" || ec_rt == "" || ec_account == "" || ec_account_type == "" || ec_id_type == "") 
    {
       error_message = error_message + "* Missing Required Fields For Check Processing.\n";
       error = 1;
    }
  }
  if (amount == "") {
        error_message = error_message + "* Amount Is Missing.\n";
        error = 1;
  } else {
  	if (!(amountReg.exec(amount))) {
	        error_message = error_message + "* Invalid Amount, Enter A Valid Amount e.g. 49.99.\n";
	        error = 1;
	}
  }
  if (error == 1) {
        alert(error_message);
	epform.Submit.disabled = false;
        return false;
  } else {
    return true;
  }
}
