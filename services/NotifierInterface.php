<?php
namespace app\services;

interface NotifierInterface
{
    /**
     * @param $view
     * @param $data
     * @param $email
     * @param $subject
     */
    public function notify($view, $data, $email, $subject);
}