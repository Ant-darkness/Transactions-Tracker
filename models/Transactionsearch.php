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
        $query = Transaction::find()->where(['user_id' => \Yii::$app->user->identity->id,
        'service' => $service,])->orderBy(['transaction_date' => SORT_DESC]);

        $defaultLimit = 5;

        $this->load($params);

        $dateFilterApplied = !empty($this->date_from) || !empty($this->date_to);

        if (!$dateFilterApplied) {
            $query->limit($defaultLimit);
        }

        

        if (!$this->validate()) {
           return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
           ]);
        }

        if (!empty($this->type)) {
            $query->andWhere(['type' => $this->type]);
        }

        if (!empty($this->date_from)) {

            $query->andWhere(['>=', 'DATE(transaction_date)', $this->date_from]);
        }

        if (!empty($this->date_to)) {
            $query->andWhere(['<=', 'DATE(transaction_date)', $this->date_to]);
        }
        
        if (!$dateFilterApplied) {
            $today = date('Y-m-d');
            $query->andWhere(['=', 'DATE(transaction_date)', $today]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
           ]);
    }

    
}