<?php

/**
  * SquirrelMail CAPTCHA Plugin reCAPTCHA Backend
  * Copyright (c) 2007-2011 Paul Lesniewski <paul@squirrelmail.org>
  * Licensed under the GNU GPL. For full terms see the file COPYING.
  *
  * @package plugins
  * @subpackage captcha
  *
  */



function recaptcha_check_configuration()
{
        global $recaptcha_public_key, $recaptcha_private_key;

        if (empty($recaptcha_public_key) || empty($recaptcha_private_key))
        {
                do_err('CAPTCHA plugin reCAPTCHA backend is missing a reCAPTCHA key.  Please see https://www.google.com/recaptcha/admin/create', FALSE);
                return TRUE;
        }
}



function recaptcha_show_input_widgets()
{
        global $recaptcha_public_key;

        $recaptcha_url = 'https://www.google.com/recaptcha/api.js';

        $output .="<form action=\"\" method=\"post\">\n"
                . "<tr><td colspan=\"2\"><br /><center>\n"
                . "<table width=\"350\">\n"
                . "<tr><td><center>\n"
                . "<div class=\"g-recaptcha\" data-sitekey=$recaptcha_public_key></div>\n"
                . "<script src=$recaptcha_url></script>\n"
                . "</form>\n";

        return $output;
}



function recaptcha_validate_captcha()
{
        global $recaptcha_private_key;
        $response = $_POST['g-recaptcha-response'];
        $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$recaptcha_private_key.'&response='.$response);

        $verifyResponse = json_decode($verifyResponse);
        if ($verifyResponse->success)
                return TRUE;
        else
                return FALSE;
}
