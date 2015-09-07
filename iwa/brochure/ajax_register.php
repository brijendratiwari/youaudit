<?php
include("config.php"); // config has functions included
	
$json = array();
$json['error_array'] = array();
$json['success'] = FALSE;		// needs to be false
$json['post'] = $_POST;

// process the registration form
if (!empty($_POST)) {
	
	// Package Radio Buttons
	if(strlen($_POST['name']) > 0) { $json['error_array']['name'] = "You're probably a robot";}
	
	// Package Radio Buttons
	if(isset($_POST['register_package']) && strlen($_POST['register_package']) < 1) { $json['error_array']['register_package'] = "Please choose a package";}
	
	// Business Name
	if(strlen($_POST['register_business_name']) < 3){ $json['error_array']['register_business_name'] = "Business Name must be at least 3 characters";}
	
	// Business Address
	if(strlen($_POST['register_business_address']) < 3){ $json['error_array']['register_business_address'] = "Business Address must be at least 3 characters";}

	// Business City
	if(strlen($_POST['register_business_city']) < 3){ $json['error_array']['register_business_city'] = "Business City must be at least 3 characters";}
	
	// Business County
	if(strlen($_POST['register_business_county']) < 3){ $json['error_array']['register_business_county'] = "Business County must be at least 3 characters";}
	
	// Business Postcode
	if(strlen($_POST['register_business_postcode']) < 3){ $json['error_array']['register_business_postcode'] = "Business Postcode must be at least 3 characters";}
	
	// Contact Name
	if(strlen($_POST['register_contact_firstname']) < 3){ $json['error_array']['register_contact_firstname'] = "Contact First Name must be at least 3 characters";}
        
        // Contact Name
	if(strlen($_POST['register_contact_surname']) < 3){ $json['error_array']['register_contact_surname'] = "Contact Surname must be at least 3 characters";}
	
	// Contact Telephone
	if(strlen($_POST['register_contact_telephone']) < 3){ $json['error_array']['register_contact_telephone'] = "Contact Telephone must be at least 3 characters";}
	
	// Contact Email Address
	if ((strlen($_POST['register_contact_email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $_POST['register_contact_email'])) {
		$json['error_array']['register_contact_email'] = "Please enter a valid email address";
	}
	
	// Contact Email Confirm		
	if($_POST['register_contact_email_confirm'] != $_POST['register_contact_email']){ $json['error_array']['register_contact_email_confirm'] = "Both email addresses must match";}
	
	// Password
	if(strlen($_POST['register_password']) < 6) { $json['error_array']['register_password'] = "Password must be at least 6 characters";}
	
	// Confirm Password
	if($_POST['register_password_confirm'] != $_POST['register_password']){ $json['error_array']['register_password_confirm'] = "Both passwords must match";}
	
	// Security Question
	if(strlen($_POST['register_security_question']) < 3){ $json['error_array']['register_security_question'] = "Security Question must be at least 3 characters";}
	
	// Security Answer
	if(strlen($_POST['register_security_answer']) < 3){ 
			$json['error_array']['register_security_answer'] = "Security Answer must be at least 3 characters";
	}
	
	
	// Package Radio Buttons
	//if(isset($_POST['register_package']) && strlen($_POST['register_package']) < 1) { $json['error_array']['register_package'] = "Please choose a package";}
	
	// Terms & Conditions
	//if(isset($_POST['register_terms']) && strlen($_POST['register_terms']) < 1){ $json['error_array']['register_terms'] = "Please confirm you have read and agree to the terms and conditions"; }

	
	if(empty($json['error_array'])){
		$json['success'] = true;
		
		// build up owner email message		
		//$message_owner = build_owner_email($_POST);
		// send email to owner
		//htmlemail(contact_email, contact_email, website_title, "Online Registration Notification", $message_owner, "brochure/email_owner.htm");
		
		/*$to      = contact_email;
		$subject = 'New iWork Registration';
		$message = 'Someone has registered for iWork';
		$headers = 'From: ' . contact_email . "\r\n" .
    'Reply-To: ' . contact_email . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);*/
		
		// build up customer email message
		$message_customer = build_customer_email($_POST);
		// send email to customer
		htmlemail(contact_email, $_POST['register_contact_email'], website_title, "iWork Audit Registration Confirmation", $message_customer, "brochure/email_customer.htm");
	}
	
}

echo json_encode($json);
?>