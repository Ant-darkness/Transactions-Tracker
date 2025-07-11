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



    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            return true;
        }
        return false;
    }

    
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $date = date('Y-m-d', strtotime($this->created_at));
        $userId = $this->user_id;
        $service = $this->service;

        $summary = DailySummary::findOne([
            'user_id' => $userId,
            'service' => $service,
            'date' => $date,
        ]);

        
        if (!$summary) {
            $summary = new DailySummary([
                'user_id' => $userId,
                'service' => $service,
                'date' => $date,
                'total_received' => 0,
                'total_sent' => 0,
            ]);
        }

        
        if ($this->type === 'Received') {
            $summary->total_received += $this->amount;
        } elseif ($this->type === 'Sent') {
            $summary->total_sent += $this->amount;
        }

        $summary->save(false); 
    }

    

}