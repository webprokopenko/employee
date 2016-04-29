<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%employee}}".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $address
 * @property string $email
 * @property integer $status
 *
 * @property Assignment[] $assignments
 * @property Dismiss[] $dismisses
 * @property Bonus[] $bonuses
 * @property Recruit[] $recruits
 * @property Vacation[] $vacations
 */
class Employee extends ActiveRecord
{
    const STATUS_PROBATION = 1;
    const STATUS_WORK = 2;
    const STATUS_VACATION = 3;
    const STATUS_DISMISS = 4;

    public static function getStatusList()
    {
        return [
            self::STATUS_PROBATION => 'Probation',
            self::STATUS_WORK => 'Work',
            self::STATUS_VACATION => 'Vacation',
            self::STATUS_DISMISS => 'Dismiss',
        ];
    }

    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusList(), $this->status);
    }

    public function getFullName()
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (in_array('status', array_keys($changedAttributes)) && $this->status != $changedAttributes['status']) {
            if ($this->status == self::STATUS_PROBATION) {
                if ($this->email) {
                    Yii::$app->mailer->compose('employee/probation', ['model' => $this])
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($this->email)
                        ->setSubject('You are recruit on probation!')
                        ->send();
                    $log = new Log();
                    $log->message = $this->last_name . ' ' . $this->first_name . ' is joined to interview';
                    $log->save();
                }
            } elseif ($this->status == self::STATUS_WORK) {
                if ($this->email) {
                    Yii::$app->mailer->compose('employee/work', ['model' => $this])
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($this->email)
                        ->setSubject('You are recruit!')
                        ->send();
                    $log = new Log();
                    $log->message = $this->last_name . ' ' . $this->first_name . ' is passed an interview';
                    $log->save();
                }
            } elseif ($this->status == self::STATUS_DISMISS) {
                if ($this->email) {
                    Yii::$app->mailer->compose('employee/dismiss', ['model' => $this])
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setTo($this->email)
                        ->setSubject('You are failed an interview')
                        ->send();
                    $log = new Log();
                    $log->message = $this->last_name . ' ' . $this->first_name . ' is failed an interview';
                    $log->save();
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
        return '{{%employee}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'address', 'status'], 'required'],
            [['status'], 'integer'],
            [['first_name', 'last_name', 'address', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'address' => 'Address',
            'email' => 'Email',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAssignments()
    {
        return $this->hasMany(Assignment::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDismisses()
    {
        return $this->hasMany(Dismiss::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuses()
    {
        return $this->hasMany(Bonus::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecruits()
    {
        return $this->hasMany(Recruit::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVacations()
    {
        return $this->hasMany(Vacation::className(), ['employee_id' => 'id']);
    }
}
