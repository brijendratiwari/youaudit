<?php
function htmlemail($emailfrom, $email, $name, $subject, $messagebody, $template_path){
	$template=(file_get_contents(path_absolute.$template_path));		
	$html=(str_replace("<body />",$messagebody,$template));
							
	// Generate a boundary string
	$semi_rand = (md5(time()));
	$mime_boundary = ("==b1_{$semi_rand}x");
	  
	$headers =
	"Reply-To: $emailfrom\n".
	"MIME-Version: 1.0\n".
	"From: \"$name\" <$emailfrom>\n".
	"Content-Type: multipart/alternative; boundary=\"{$mime_boundary}\"\n\n";
	$message.="This is a multi-part message in MIME format.\n";

	$message.="--{$mime_boundary}\n".
			 "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
			 "Content-Transfer-Encoding: 8bit\n\n";

	$message.=(trim(strip_tags($messagebody))."\n\n");
	
	$message.="--{$mime_boundary}\n" .
			 "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
			 "Content-Transfer-Encoding: 8bit\n\n";
			 
	$message.=("$html\n");
	
	$message.="--{$mime_boundary}--\n\n";
	
	//print($message);
	@mail($email, $subject, $message, $headers);
}


function build_owner_email($values){	
	$message = "";
	
	// choose a package
	$message .= '<div class="form_block">';
	$message .= '<h3>New Registration</h3>';
	$message .= '<p>Someone has registered for an account on the '.website_title.' website. Their details are below.</p>';
	$message .= '<h3 style="margin: 10px 0 0 0;">Account Summary</h3>';
	$message .= '<table>';
    $message .= '<tbody>';
		
$message .= '<tr>';		
		$message .= '<td width="200"><strong>Chosen Package</strong></td>';
		$message .= '<td class="border_left">'.stripslashes($values['register_package']).'</td>';
		$message .= '</tr>';
		
		$message .= '<tr>';
		$message .= '<td width="200"><strong>Business Details</strong></td>';
    $message .= '<td>'.stripslashes($values['register_business_name']).'<br />';
		$message .= stripslashes($values['register_business_address']).'<br />';
		$message .= stripslashes($values['register_business_city']).'<br />';
		$message .= stripslashes($values['register_business_county']).'<br />';
		$message .= stripslashes($values['register_business_postcode']);
		if (!empty($values['register_business_country'])) {
			$message .= '<br />'.stripslashes($values['register_business_country']);
		}
		$message .= '</td>';
		$message .= '</tr>';
		
		$message .= '<tr>';	
		$message .= '<td width="200"><strong>Account Holder Details</strong></td>';
		$message .= '<td class="border_left">'.stripslashes($values['register_contact_firstname']).' '.stripslashes($values['register_contact_surname']).'<br />';
		$message .= stripslashes($values['register_contact_telephone']).'<br />';
		$message .= stripslashes($values['register_contact_email']).'</td>';
    $message .= '</tr>';
		
		$message .= '<tr>';		
		$message .= '<td class="border_bottom" width="200"><strong>Password</strong></td>';
		$message .= '<td class="border_bottom border_left">'.stripslashes($values['register_password']).'</td>';
		$message .= '</tr>';
		
		$message .= '<tr>';		
		$message .= '<td class="border_bottom" width="200"><strong>Security Question</strong></td>';
		$message .= '<td class="border_bottom border_left">'.stripslashes($values['register_security_question']).'</td>';
		$message .= '</tr>';
		
		$message .= '<tr>';		
		$message .= '<td width="200"><strong>Security Answer</strong></td>';
		$message .= '<td class="border_left">'.stripslashes($values['register_security_answer']).'</td>';
		$message .= '</tr>';
		
    $message .= '</tbody>';
    $message .= '</table>';
	$message.= '</div>';
	
	return $message;
}

function build_customer_email($values){	
	$message = "";
	
	// thankyou message
	$message .= '<div class="form_block">';
	$message .= '<h3>Thanks for registering with '.website_title.'</h3>';
	$message .= '<p>Hi '.stripslashes($values['register_contact_name']).',</p>';
	$message .= '<p>Thank you for choosing '.website_title.'.</p>';
	$message .= '<p>We have received your registration details and these will be verified by our team.</p>';
	$message .= '<p>As soon as this process is complete, we will contact you to confirm your account.</p>';
	$message .= '<p>In the meantime, please find a summary of your details below.</p>';
	
	$message .= '<h3 style="margin: 10px 0 0 0;">Account Summary</h3>';
	$message .= '<table>';
    $message .= '<tbody>';
		
		$message .= '<tr>';		
		$message .= '<td width="200"><strong>Chosen Package</strong></td>';
		$message .= '<td class="border_left">'.stripslashes($values['register_package']).'</td>';
		$message .= '</tr>';
		
		$message .= '<tr>';
		$message .= '<td width="200"><strong>Business Details</strong></td>';
    $message .= '<td>'.stripslashes($values['register_business_name']).'<br />';
		$message .= stripslashes($values['register_business_address']).'<br />';
		$message .= stripslashes($values['register_business_city']).'<br />';
		$message .= stripslashes($values['register_business_county']).'<br />';
		$message .= stripslashes($values['register_business_postcode']);
		if (!empty($values['register_business_country'])) {
			$message .= '<br />'.stripslashes($values['register_business_country']);
		}
		$message .= '</td>';
		$message .= '</tr>';
		
		$message .= '<tr>';	
		$message .= '<td width="200"><strong>Account Holder Details</strong></td>';
		$message .= '<td class="border_left">'.stripslashes($values['register_contact_firstname']).' '.stripslashes($values['register_contact_surname']).'<br />';
		$message .= stripslashes($values['register_contact_telephone']).'<br />';
		$message .= stripslashes($values['register_contact_email']).'</td>';
    $message .= '</tr>';
		
		$message .= '<tr>';		
		$message .= '<td class="border_bottom" width="200"><strong>Password</strong></td>';
		$message .= '<td class="border_bottom border_left">'.stripslashes($values['register_password']).'</td>';
		$message .= '</tr>';
		
		$message .= '<tr>';		
		$message .= '<td class="border_bottom" width="200"><strong>Security Question</strong></td>';
		$message .= '<td class="border_bottom border_left">'.stripslashes($values['register_security_question']).'</td>';
		$message .= '</tr>';
		
		$message .= '<tr>';		
		$message .= '<td width="200"><strong>Security Answer</strong></td>';
		$message .= '<td class="border_left">'.stripslashes($values['register_security_answer']).'</td>';
		$message .= '</tr>';
		
    $message .= '</tbody>';
    $message .= '</table>';
	$message.= '</div>';
	
	return $message;
}
?>