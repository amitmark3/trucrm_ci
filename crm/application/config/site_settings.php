<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* MAIN SETTINGS */
$config['website_title'] = 'Trucrm by Mark3';
$config['website_email'] = 'info@mark3.in';
$config['website_phone_number'] = '011 20471563';
$config['website_phone_number_link'] = '9899568569856';
$config['website_url'] = '192.168.1.100:8181/trucrm_ci';
$config['website_url_link'] = 'http://192.168.1.100:8181/trucrm_ci';

/* SOCIAL MEDIA LINKS */
$config['website_facebook_link'] = 'https://www.facebook.com/trucrm';
$config['website_twitter_link'] = 'https://twitter.com/trucrm';

/* SUPPORT SETTINGS */
$config['website_support_title'] = 'Trucrm Support Team';
$config['website_support_email'] = 'trucrm@mark3.in';

/* WEB DEVELOPER SETTINGS */
$config['dev_name'] = 'Amit Kumar';
$config['dev_email'] = 'amit@mark3.in';

/* SEND EMAILS? */
$config['send_emails'] = TRUE;

/* MAINTENANCE MODE */
$config['maintenance_mode'] = FALSE;
$config['maintenance_ips'] = ['::1', '109.255.56.242']; // localhost, mine, martin

/* DOCUMENT UPLOADING */
$config['allowed_file_types'] = "'image'";
$config['allowed_file_extentions'] = "'jpg', 'jpeg', 'bmp', 'gif', 'png'";
$config['max_file_size'] = 1024 * 10; // 10MB