<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!--[if !mso]><!--><meta http-equiv="X-UA-Compatible" content="IE=edge" /><!--<![endif]-->
        <meta name="viewport" content="width=device-width" />
        <meta name="robots" content="noindex,nofollow" />
        <title> </title>
        <style type="text/css">
            .heading { color: #dd4b39; }
        </style>
    </head>
    <body>
        <h1 class="heading"><?php echo sprintf(lang('email_welcome_heading'), $first_name);?></h1>
        <p><?php echo sprintf(lang('email_welcome_subheading'), anchor('login', lang('email_welcome_login_link')));?></p>
        <p>Your login details are:</p>
        <p>
            Email Address: <?php echo $email; ?><br>
            Password: <?php echo $password; ?>
        </p>
        <p>Kind Regards,</p>
        <p><?php echo $this->config->item('website_support_title'); ?></p>
        <!--p>Follow us on <a href="https://twitter.com/mark3">Twitter</a> or <a href="https://www.facebook.com/mark3">Facebook</a></p-->
    </body>
</html>