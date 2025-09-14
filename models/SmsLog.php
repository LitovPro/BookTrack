<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "sms_log".
 *
 * @property int $id
 * @property string $phone
 * @property string $message
 * @property string $status
 * @property string|null $response_json
 * @property int $created_at
 */
class SmsLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sms_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false, // Отключаем updated_at
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'message', 'status'], 'required'],
            [['phone'], 'string', 'max' => 32],
            [['message'], 'string'],
            [['status'], 'string', 'max' => 32],
            [['response_json'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Телефон',
            'message' => 'Сообщение',
            'status' => 'Статус',
            'response_json' => 'Ответ API',
            'created_at' => 'Дата отправки',
        ];
    }
}

