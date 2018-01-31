<?php
$lang['company_access_denied'] = 'You do not have permission to access that page.';

/* PAGE HEADINGS */
$lang['company_heading_index'] = 'Company Overview';
$lang['company_heading_edit'] = 'Company Details';
$lang['company_heading_confirmation'] = 'Payment Confirmation';

/* FORM FIELD LABELS */
$lang['company_name_label'] = 'Name <span class="asterisk">*</span>';
$lang['company_email_address_label'] = 'Email Address <span class="asterisk">*</span>';
$lang['company_description_label'] = 'Company Information';
$lang['company_address_label'] = 'Address <span class="asterisk">*</span>';
$lang['company_phone_number_label'] = 'Phone Number';
$lang['company_website_address_label'] = 'Website Address';

/* FORM FIELDS PLACEHOLDERS */
$lang['company_name_placeholder'] = 'Name';
$lang['company_email_address_placeholder'] = 'Email address';
$lang['company_description_placeholder'] = 'Here you can addd information about the company like the year the company was founded, the number of employees, etc.';
$lang['company_address_placeholder'] = 'Enter the postal address of the company.';
$lang['company_phone_number'] = 'phone number';
$lang['company_website_url_placeholder'] = 'http://www.address.com';

/* ERROR MESSAGES */
$lang['company_edit_details_failed'] = 'There was a problem updating the company details.';
$lang['company_price_plan_update_failed'] = 'There was a problem updating the price plan.';
$lang['company_payment_failed_card_error'] = 'There was a problem with your card details.';
$lang['company_payment_token_failed'] = 'There was a problem processing the payment. You have NOT been charged. Please try again.';
$lang['company_payment_network_error'] = 'A connection cannot be made to Stripe\'s server at this time. Please try again in a few minutes.';
$lang['company_payment_api_error'] = 'Looks like there is a problem with the Stripe server at this time. Please try again in a few minutes.';
$lang['company_payment_we_screwed_up'] = 'Looks like we made a proper boo-boo! The details of the error have been logged and the web developer has been sent an email (and will get a serious talking to!).';

/* SUCCESS MESSAGES */
$lang['company_edit_details_successful'] = 'The company details have been updated successfully.';
$lang['company_price_plan_update_successful'] = 'The price plan has been changed successfully.';
$lang['company_payment_successful'] = 'The payment has been successfully charged.';



/*********************************************************************/
/* IMPORT USERS & DEPARTMENTS
/*********************************************************************/
$lang['company_import_heading'] = 'Import Users & Departments';
$lang['company_import_intro'] = 'Select the CSV file using the form below.';

/* ERROR MESSAGES */
$lang['company_import_invalid_file_type'] = 'Invalid file type uploaded. Please make sure it\'s a csv file.';
$lang['company_import_failed_email'] = 'The email address <strong>%s</strong> is already in the system.';
$lang['company_import_failed_empty_fields'] = 'Some of the fields were not included on the CSV file. Please double check the file and correct any issues.';
$lang['company_import_failed_file_is_empty'] = 'The CSV file does not have any users in it.';
$lang['company_import_failed_role'] = 'There was a problem with the role chosen.';
$lang['company_import_failed'] = 'There was a problem importing the file.';

/* SUCCESS MESSAGES */
$lang['company_import_successful'] = 'The file was successfully imported.';

/* EMAILS */
$lang['company_welcome_email_subject'] = 'Welcome to Trucrm by Mark3!';