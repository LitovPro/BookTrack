<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BookSearch */
/* @var $form yii\bootstrap5\ActiveForm */
?>

<div class="book-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'search')->textInput(['placeholder' => 'Поиск по названию, ISBN, автору...']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'year')->textInput(['placeholder' => 'Год издания']) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'isbn')->textInput(['placeholder' => 'ISBN']) ?>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label class="form-label">&nbsp;</label>
                <div>
                    <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Сбросить', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

