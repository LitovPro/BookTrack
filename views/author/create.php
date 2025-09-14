<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Author */

$this->title = 'Добавить автора';
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="author-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Информация об авторе</h5>
            </div>
            <div class="card-body">
                <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="form-group mt-3">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>

