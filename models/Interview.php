<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%interview}}".
 *
 * @property integer $id
 * @property string $date
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property integer $status
 * @property string $reject_reason
 * @property integer $employee_id
 */
class Interview extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    const STATUS_NEW = 1;
    const STATUS_PASS = 2;
    const STATUS_REJECT = 3;

    public static function getStatusList()
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_PASS => 'Passed',
            self::STATUS_REJECT => 'Rejected',
        ];
    }

    public function getNextStatusList()
    {
        if ($this->status == self::STATUS_PASS) {
            return [
                self::STATUS_PASS => 'Passed',
            ];
        } elseif ($this->status == self::STATUS_REJECT) {
            return [
                self::STATUS_PASS => 'Passed',
                self::STATUS_REJECT => 'Rejected',
            ];
        } else {
            return [
                self::STATUS_NEW => 'New',
                self::STATUS_PASS => 'Passed',
                self::STATUS_REJECT => 'Rejected',
            ];
        }
    }

    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusList(), $this->status);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (in_array('status', array_keys($changedAttributes)) && $this->status != $changedAttributes['status']) {
            if ($this->status == self::STATUS_NEW) {
                if ($this->email) {
                    Yii::$app->mailer->compose('interview/join', ['model' => $this])
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($this->email)
                        ->setSubject('You are joined to interview!')
                        ->send();
                }
            } elseif ($this->status == self::STATUS_PASS) {
                if ($this->email) {
                    Yii::$app->mailer->compose('interview/pass', ['model' => $this])
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($this->email)
                        ->setSubject('You are passed an interview!')
                        ->send();
                }
            } elseif ($this->status == self::STATUS_REJECT) {
                if ($this->email) {
                    Yii::$app->mailer->compose('interview/reject', ['model' => $this])
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($this->email)
                        ->setSubject('You are failed an interview')
                        ->send();
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%interview}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'first_name', 'last_name'], 'required'],
            [['status'], 'required', 'except' => self::SCENARIO_CREATE],
            [['status'], 'default', 'value' => self::STATUS_NEW],
            [['date'], 'safe'],
            [['reject_reason'], 'required', 'when' => function (self $model) {
                    return $model->status == self::STATUS_REJECT;
                }, 'whenClient' => "function (attribute, value) {
                    return $('#interview-status').val() == '" . self::STATUS_REJECT. "';
                }"
            ],
            [['status', 'employee_id'], 'integer', 'except' => self::SCENARIO_CREATE],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 255],
            [['reject_reason'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'status' => 'Status',
            'reject_reason' => 'Reject Reason',
            'employee_id' => 'Employee',
        ];
    }
}
