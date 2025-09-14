<?php

namespace app\models;

use yii\base\Model;

/**
 * Form model for report filters
 */
class ReportForm extends Model
{
    /**
     * @var int
     */
    public $year;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if ($this->year === null) {
            $this->year = date('Y');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['year'], 'integer'],
            [['year'], 'required'],
            [['year'], 'default', 'value' => function() {
                return date('Y');
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'year' => 'Год издания',
        ];
    }
}

