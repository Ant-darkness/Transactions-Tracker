<?php
namespace app\models;

use yii\db\ActiveRecord;

class Transaction extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%transaction}}';
    }

    public function rules()
{
    return [
        [['user_id', 'service', 'type', 'amount', 'transaction_date'], 'required'],
        [['user_id'], 'integer'],
        [['service', 'type'], 'string', 'max' => 255],
        [['amount'], 'number', 'min' => 0.01, 'message' => 'Amount must be greater than zero.'],
        [['transaction_date', 'created_at'], 'safe'],
        [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
    ];
}

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}