<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "interview".
 *
 * @property integer $id
 * @property string $date
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property integer $status
 * @property string $reject_reason
 * @property integer $employee_id
 *
 * @property Employee $employee
 */
class Interview extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE   =   'create';

    const STAT_NEW  =   1;
    const STAT_PASSED  =   2;
    const STAT_REJECT   =   3;

    public static function getStatusList(){
        return[
            self::STAT_NEW  =>  'New',
            self::STAT_PASSED  =>  'Passed',
            self::STAT_REJECT  =>  'Rejected',
        ];
    }

    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusList(),$this->status);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'interview';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'first_name', 'last_name'], 'required'],
            [['status'], 'required', 'except' => self::SCENARIO_CREATE],

            [['date'],'safe'],
            [['reject_reason'], 'required','when'=>function (self $model){
                    return $model->status == self::STAT_REJECT;
                }, 'whenClient'=>"function(attribute, value){
                    return $('#interview-status').val() == '". self::STAT_NEW. "';
                }"
            ],
            [['status', 'employee_id'], 'integer', 'except'=>self::SCENARIO_CREATE],
            [['first_name', 'last_name', 'email'], 'string', 'max' => 255],
            [['reject_reason'], 'string']
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
            'employee_id' => 'Employee ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }
}
