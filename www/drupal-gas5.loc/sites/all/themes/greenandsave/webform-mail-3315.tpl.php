<?php

$to = $form_values['submitted_tree']['email'];
$name = $form_values['submitted_tree']['name'];
$city = $form_values['submitted_tree']['city']; 
$phone = $form_values['submitted_tree']['phone']; 


$params['body'] = "Dear $name \n\n
Thank you for your interest in the Eco Academy from $city. We have received your information
and a representative will follow-up with you regarding additional information. \n\n

Regards,\n
Home Efficiency Consulting\n
\n
";

?>