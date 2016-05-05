<?php

namespace app\services;

use Yii;

class Notifier implements NotifierInterface
{
    private $fromEmail;

    public function __construct($fromEmail)
    {
        $this->fromEmail = $fromEmail;
    }

    /**
     * @param $view
     * @param $data
     * @param $email
     * @param $subject
     */
    public function notify($view, $data, $email, $subject)
    {
        Yii::$app->mailer->compose($view, $data)
            ->setFrom($this->fromEmail)
            ->setTo($email)
            ->setSubject($subject)
            ->send();
    }
} 