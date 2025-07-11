<?php

namespace app\models;

use yii\base\model;
use yii\data\ActiveDataProvider;

class TransactionTrendSearch extends TransactionTrend

{
    public function rules()
    {
        return [
            [['service', 'period'], 'safe'],
        ];
    }
}

