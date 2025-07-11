<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class DailySummary extends ActiveRecord
{
    public static function tableName()
    {
        return 'daily_summary';
    }

    public function rules()
    {
        return [
            [['user_id', 'service', 'date'], 'required'],
            [['total_received', 'total_sent'], 'number'],
            [['date'], 'safe'],
        ];
    }
}