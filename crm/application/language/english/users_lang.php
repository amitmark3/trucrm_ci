<?php

/* HEADINGS */
$lang['users_heading_index']		= 'Users';
$lang['users_heading_department']	= 'Users for %s';
$lang['users_heading_add']			= 'Add User';
$lang['users_heading_edit']			= 'Edit User';
$lang['users_heading_import']		= 'Import Users';
$lang['users_heading_view']			= 'User Profile';
$lang['users_heading_change_password'] = 'Change Password';

/* FORM FIELD LABELS */
$lang['users_name_label'] = 'Name <span class="asterisk">*</span>';
$lang['users_company_label'] = 'Company <span class="asterisk">*</span>';
$lang['users_first_name_label'] = 'First Name <span class="asterisk">*</span>';
$lang['users_last_name_label'] = 'Last Name <span class="asterisk">*</span>';
$lang['users_email_address_label'] = 'Email Address <span class="asterisk">*</span>';
$lang['users_phone_number_label'] = 'Phone Number';
$lang['users_job_title_label'] = 'Job Title <small>(optional)</small>';
$lang['users_employee_number_label'] = 'Exployee Number <small>(optional)</small>';
$lang['users_department_label'] = 'Department <span class="asterisk">*</span>';
$lang['users_group_label'] = 'Role <span class="asterisk">*</span>';

$lang['users_old_password_label'] = 'Old Password <span class="asterisk">*</span>';
$lang['users_new_password_label'] = 'New Password <span class="asterisk">*</span>';
$lang['users_new_password_confirm_label'] = 'Confirm New Password <span class="asterisk">*</span>';

/* FORM FIELD PLACEHOLDERS */
$lang['users_company_placeholder'] = 'Company';
$lang['users_role_placeholder'] = 'Role';
$lang['users_name_placeholder'] = 'Name';
$lang['users_first_name_placeholder'] = 'First Name';
$lang['users_last_name_placeholder'] = 'Last Name';
$lang['users_email_address_placeholder'] = 'Email Address';
$lang['users_phone_number_placeholder'] = 'Phone Number';
$lang['users_job_title_placeholder'] = 'Job Title';
$lang['users_employee_number_placeholder'] = 'Exployee Number';
$lang['users_department_placeholder'] = 'Department';
$lang['users_group_placeholder'] = 'Role';

/* SUCCESS MESSAGES */
$lang['users_insert_successful'] = 'The user has been added successfully.';
$lang['users_insert_successful_activation'] = 'The user has been added and account activation email sent.';
$lang['users_update_successful'] = 'The user has been updated successfully.';
$lang['users_delete_successful'] = 'The user has been deleted successfully.';
$lang['users_activated_successful'] = 'The user has been activated successfully.';
$lang['users_deactivated_successful'] = 'The user has been deactivated successfully.';
$lang['users_change_password_successful'] = 'The users password has been changed successfully.';
$lang['users_reset_password_successful'] = 'The users password has been changed successfully.';

/* ERROR MESSAGES */
$lang['users_insert_failed'] = 'There was a problem saving the user.';
$lang['users_insert_failed_email_exists'] = 'The email address is already in use.';
$lang['users_insert_failed_no_departments'] = 'No departments have been created yet. Please create a department using the form below before adding a new user.';
$lang['users_insert_failed_name_exists'] = 'A user with the name <strong>%s</strong> already exists. Please provide more information (such as a middle initial as part of the first name) to differentiate between the two.';
$lang['users_insert_failed_department_manager_exists'] = 'The department chosen already has a manager.';
$lang['users_update_failed'] = 'There was a problem updating the user.';
$lang['users_delete_failed'] = 'There was a problem deleting the user.';
$lang['users_activate_failed'] = 'There was a problem activating the user.';
$lang['users_deactivate_failed'] = 'There was a problem deactivating the user.';
$lang['users_change_password_failed'] = 'There was a problem changing the users password.';
$lang['users_reset_password_failed'] = 'There was a problem changing the users password.';

$lang['users_invalid_company_to_edit'] = 'You are not allowed to edit that user.';
$lang['users_invalid_company_to_delete'] = 'You are not allowed to delete that user.';

/* INFO / WARNING MESSAGES */
$lang['users_invalid_id'] = 'No user ID number was provided.';
$lang['users_access_denied']  = 'You do not have permission to access that page.';

/******************************* EMAILS ******************************/

/* WELCOME EMAIL */
$lang['users_email_welcome_subject'] = 'Welcome to Trucrm by Mark3';

/* NEW PASSWORD EMAIL */
$lang['users_change_password_by_admin_email_subject'] = 'Password Changed on Trucrm';

/* RESET PASSWORD EMAIL */
$lang['users_reset_password_email_subject'] = 'Password Reset on Trucrm';

