<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */
/* @var $selectedYear int */
/* @var $availableYears array */
/* @var $model yii\base\Model */

$this->title = 'ТОП-10 авторов по количеству книг';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-top-authors">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Выберите год</h5>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'method' => 'get',
                'action' => ['/report/top-authors'],
            ]); ?>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'year')->dropDownList($availableYears, [
                        'onchange' => 'this.form.submit()'
                    ])->label('Год издания') ?>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <?= Html::submitButton('Показать отчёт', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">ТОП-10 авторов за <?= $selectedYear ?> год</h5>
        </div>
        <div class="card-body">
            <?php if ($dataProvider->getCount() > 0): ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'attribute' => 'rank',
                            'label' => 'Место',
                            'contentOptions' => ['class' => 'rank'],
                        ],
                        [
                            'attribute' => 'full_name',
                            'label' => 'Автор',
                            'value' => function ($model) {
                                return Html::a($model['full_name'], ['/author/view', 'id' => $model['id']]);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'books_count',
                            'label' => 'Количество книг',
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                    ],
                    'tableOptions' => ['class' => 'table table-striped table-bordered'],
                    'summary' => 'Показано {count} из {totalCount} авторов',
                ]); ?>
            <?php else: ?>
                <div class="alert alert-info">
                    <h5>Нет данных</h5>
                    <p>За выбранный год (<?= $selectedYear ?>) в каталоге нет книг.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-3">
        <?= Html::a('Назад к списку авторов', ['/author/index'], ['class' => 'btn btn-secondary']) ?>
        <?= Html::a('Назад к списку книг', ['/book/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

</div>
