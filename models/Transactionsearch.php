<?php
namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class TransactionSearch extends Transaction
{
    public $date_from;
    public $date_to;

    public function rules()
    {
        return [
            [['type', 'service', 'date_from', 'date_to'], 'safe'],
        ];
    }

    public function search($params, $service)
    {
        $query = Transaction::find()->where(['user_id' => \Yii::$app->user->identity->id, 'service' => $service]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->type) {
            $query->andWhere(['type' => $this->type]);
        }

        if ($this->date_from) {
            $query->andWhere(['>=', 'transaction_date', $this->date_from]);
        }

        if ($this->date_to) {
            $query->andWhere(['<=', 'transaction_date', $this->date_to]);
        }

        return $dataProvider;
    }
}