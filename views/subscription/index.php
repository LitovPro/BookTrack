<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Author;

/* @var $this yii\web\View */
/* @var $model app\models\AuthorSubscription */

$this->title = 'Подписка на уведомления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Подпишитесь на уведомления о новых книгах ваших любимых авторов. Мы будем отправлять SMS-уведомления на указанный номер телефона.</p>

    <div class="row">
        <div class="col-md-6">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'author_id')->dropDownList(
                ArrayHelper::map(Author::find()->all(), 'id', 'full_name'),
                ['prompt' => 'Выберите автора']
            )->label('Автор') ?>

            <?= $form->field($model, 'phone')->textInput(['maxlength' => true])->label('Номер телефона') ?>

            <!-- Honeypot field -->
            <div style="display: none;">
                <?= $form->field($model, 'website')->textInput() ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Подписаться', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

        <div class="col-md-6">
            <div class="alert alert-info">
                <h4>Как это работает:</h4>
                <ul>
                    <li>Выберите автора из списка</li>
                    <li>Укажите свой номер телефона</li>
                    <li>Нажмите "Подписаться"</li>
                    <li>Получайте SMS-уведомления о новых книгах</li>
                </ul>
            </div>
        </div>
    </div>

</div>
