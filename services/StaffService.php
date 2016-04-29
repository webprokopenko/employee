<?php

namespace app\services;

use app\models\Interview;
use app\models\Log;
use Yii;

class StaffService
{
    public function joinToInterview($lastName, $firstName, $email, $date)
    {
        $interview = new Interview();
        $interview->date = $date;
        $interview->last_name = $lastName;
        $interview->first_name = $firstName;
        $interview->email = $email;
        $interview->status = Interview::STATUS_NEW;
        $interview->save(false);

        if ($interview->email) {
            Yii::$app->mailer->compose('interview/join', ['model' => $this])
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($interview->email)
                ->setSubject('You are joined to interview!')
                ->send();
        }

        $log = new Log();
        $log->message = $interview->last_name . ' ' . $interview->first_name . ' is joined to interview';
        $log->save();

        return $interview;
    }
} 