<?php
$lang['setup_heading'] = 'Welcome to Trucrm';
$lang['setup_access_denied'] = 'You do not have permission to access that page.';


/*********************************************************************/
/* PRICE PLAN
/*********************************************************************/

/* ERROR MESSAGES */
$lang['setup_insert_price_plan_failed'] = 'There was a problem saving the price plan. Please try again.';

/* SUCCESS MESSAGES */
$lang['setup_insert_price_plan_successful'] = 'The price plan has been saved successfully.';


/*********************************************************************/
/* STRIPE CHARGE
/*********************************************************************/

/* ERROR MESSAGES */
$lang['setup_stripe_token_missing'] = "There was a problem sending the request to Stripe. Please try again.";
$lang['setup_payment_failed_card_error'] = 'There was a problem with your card details.';
$lang['setup_payment_token_failed'] = 'There was a problem processing the payment. You have NOT been charged. Please try again.';
$lang['setup_payment_network_error'] = 'A connection cannot be made to Stripe\'s server at this time. Please try again in a few minutes.';
$lang['setup_payment_api_error'] = 'Looks like there is a problem with the Stripe server at this time. Please try again in a few minutes.';
$lang['setup_payment_we_screwed_up'] = 'Looks like we made a boo-boo! The details of the error have been logged and the web developer has been sent an email (and will get a serious talking to!).';

/* SUCCESS MESSAGES */
$lang['setup_payment_successful'] = 'Payment has been successfully charged.';


/*********************************************************************/
/* CONFIRMATION
/*********************************************************************/
$lang['setup_confirmation_heading'] = 'Thank you!';
$lang['setup_billing_complete'] = 'The billing process is now complete and your card has been charged.';


/*********************************************************************/
/* IMPORT USERS & DEPARTMENTS
/*********************************************************************/
$lang['setup_import_heading'] = 'Import Users & Departments';
$lang['setup_import_intro'] = 'Select the CSV file using the form below.';

/* ERROR MESSAGES */
$lang['setup_import_failed_email'] = 'The email address <strong>%s</strong> is already in the system.';
$lang['setup_import_failed_empty_fields'] = 'Some of the fields were not included in the CSV file. Please double check the file and correct any issues.';
$lang['setup_import_failed_file_is_empty'] = 'The CSV file does not have any information in it.';
$lang['setup_import_failed_role'] = 'There was a problem with the role chosen.';
$lang['setup_import_failed_invalid_file'] = 'An incorrect file type was used. Only .csv files are supported at this time.';
$lang['setup_import_failed'] = 'There was a problem importing the file.';

/* SUCCESS MESSAGES */
$lang['setup_import_successful'] = 'The file was successfully imported.';

/* EMAILS */
$lang['setup_welcome_email_subject'] = 'Welcome to Trucrm by Mark3!';


/*********************************************************************/
/* FINISH
/*********************************************************************/
$lang['setup_finish_heading'] = 'Setup Complete!';

