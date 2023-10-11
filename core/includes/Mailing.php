<?php

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Project: edusogno-task
 * File: Mailing.php
 * Author: wanpeninsula
 * Organization: Edusogno
 * Author URI: https://www.pennycodes.dev
 * Created: 09/10/2023 at 7:43 pm
 *
 * Copyright (c) 2023 Edusogno. All rights reserved.
 */
class Mailing
{

    private string $name;
    private string $email;
    private string $subject;
    private string $message;



    public function __construct()
    {

    }

    public function setPayload(string $name, string $email, string $subject, string $message): void
    {
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function send(): bool
    {
        $mailConfig = EdusognoApp::config('smtp_or_mail');
       if ($mailConfig === 'mail') {
           $headers = "MIME-Version: 1.0" . "\r\n";
           $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

           $headers .= "From: ".EdusognoApp::config('smtp_username')." ".EdusognoApp::config('site_name') . "\r\n";
           return mail(
               $this->email,
               $this->subject,
               $this->message,
               $headers
           );
       }
       else {



           try {
               $appConfig = EdusognoApp::configs();
               $mailer = new PHPMailer();
               $mailer->isSMTP();
               $mailer->SMTPDebug = 0;
               $mailer->Host = $appConfig['smtp_host'];
               $mailer->SMTPAuth = true;
               $mailer->Username = $appConfig['smtp_username'];
               $mailer->Password = $appConfig['smtp_password'];
//               $mailer->SMTPSecure = $appConfig['smtp_encryption'];
               $mailer->SMTPSecure = false;
               $mailer->SMTPAutoTLS = false;
               $mailer->Port = $appConfig['smtp_port'];
               $mailer->isHTML();
               $mailer->setFrom($appConfig['smtp_username'], $appConfig['site_name']);
               $mailer->addReplyTo($appConfig['smtp_username'], $appConfig['site_name']);

                $mailer->addAddress($this->email, $this->name);
                $mailer->Subject = $this->subject;
                $mailer->Body = $this->message;

                if ($mailer->send()) {
                    $mailer->clearAddresses();
                    $mailer->clearAllRecipients();
                    $mailer->clearReplyTos();
                    $mailer->smtpClose();
                    return true;
                } else {
                    return false;
                }
           }
           catch (Exception $e) {
               Utils::logError('Email Sending Error', $e);
           }
       }
         return false;
    }



}
