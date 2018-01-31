<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>[SUBJECT]</title>
        <style type="text/css">
            body {
                padding-top: 0 !important;
                padding-bottom: 0 !important;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                -webkit-text-size-adjust: 100% !important;
                -ms-text-size-adjust: 100% !important;
                -webkit-font-smoothing: antialiased !important;
            }
            .tableContent img {
                border: 0 !important;
                display: block !important;
                outline: none !important;
            }
            a {
                color: #A52A2A;
                /*font-weight: 500;*/
                text-decoration: none;
            }
            a:hover {
                color: black;
            }
            p, h1 {
                color: #382F2E;
            }
            p {
                text-align: left;
                font-size: 14px;
                font-weight: normal;
                line-height: 19px;
            }
            h2 {
                text-align: left;
                font-size: 19px;
                font-weight: normal;
            }
        </style>
    </head>
    <body paddingwidth="0" paddingheight="0" style="padding-top: 0; padding-bottom: 0; padding-top: 0; padding-bottom: 0; background-repeat: repeat; width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; -webkit-font-smoothing: antialiased;" offset="0" toppadding="0" leftpadding="0">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tableContent" align="center" style="font-family:Helvetica,Arial,serif;">
            <tr>
                <td>
                    <table width="520" border="0" cellspacing="0" cellpadding="0" align="center">
                        <tr>
                            <td>
                                <table border="0" cellspacing="0" cellpadding="0" align="center">
                                    <tr><td height="75" colspan="2"></td></tr>
                                    <tr>
                                        <td valign="top" align="center" colspan="2">
                                            <!-- Welcome logo -->
                                            <img src="<?php echo site_url('/assets/img/emails/welcome.gif');?>" data-default="placeholder" data-max-width="520">
                                            <br />
                                            <br />
                                            <br />
                                            <!-- Intro text -->
                                            <h2><?php echo sprintf(lang('email_activate_heading'), $email); ?></h2>
                                            <p><?php echo lang('email_activate_subheading'); ?></p>
                                            <p>Have questions? Get in touch with us via Facebook or Twitter, or email our support team.</p>
                                            <br />
                                            <br />
                                            <?php echo anchor('auth/activate/'.$id.'/'.$activation, lang('email_activate_link'), ['style' => 'color:#ffffff;background:brown;border-radius:5px;padding:15px;']); ?>
                                            <p>Kind Regards,</p>
                                            <p><strong><?php echo $this->config->item('website_support_title'); ?></strong></p>
                                            <br />
                                            <br />
                                            <br />
                                            <br />
                                            <hr />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top" width="40%">
                                            <p>
                                               Team Trucrm,<br />
                                                Delhi.
                                            </p>
                                        </td>
                                        <td valign="top" width="60%">
                                            <p>
                                                Phone: <a href="tel:<?php echo $this->config->item('website_phone_number_link'); ?>"><?php echo $this->config->item('website_phone_number'); ?></a><br />
                                                Email: <a href="mailto:<?php echo $this->config->item('website_support_email'); ?>"><?php echo $this->config->item('website_support_email'); ?></a><br />
                                                Website: <a href="<?php echo $this->config->item('website_url_link'); ?>"><?php echo $this->config->item('website_url'); ?></a><br />
                                                Facebook: <a href="<?php echo $this->config->item('website_facebook_link'); ?>"><?php echo $this->config->item('website_facebook_link'); ?></a><br />
                                                Twitter: <a href="<?php echo $this->config->item('website_twitter_link'); ?>"><?php echo $this->config->item('website_twitter_link'); ?></a><br />
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
