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

    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusList(), $this->status);
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
            [['date', 'first_name', 'last_name', 'status'], 'required'],
            [['date'], 'safe'],
            [['status', 'employee_id'], 'integer'],
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
