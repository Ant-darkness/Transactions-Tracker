<?php

namespace app\models;

use yii\db\ActiveRecord;

class TransactionTrend extends ActiveRecord
{
    public static function tableName()

    {
        return '{{%transaction_trend}}';
    }

    public function rules()
    {
        return [
            [['service', 'period', 'data', 'create_at'], 'required'],
            [['data'], 'string'],
            [['ceated_at'], 'safe'],
            [['service', 'period'], 'string', 'max' => 255],
        ];
    }
}