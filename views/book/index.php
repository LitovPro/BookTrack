<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest): ?>
        <p>
            <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <?php Pjax::begin(); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Поиск и фильтрация</h5>
        </div>
        <div class="card-body">
            <?= $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'title',
                    'year',
                    'isbn',
                    [
                        'attribute' => 'authors',
                        'value' => function ($model) {
                            $authors = [];
                            foreach ($model->authors as $author) {
                                $authors[] = Html::a($author->full_name, ['/author/view', 'id' => $author->id]);
                            }
                            return implode(', ', $authors);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'cover_path',
                        'label' => 'Обложка',
                        'value' => function ($model) {
                            if ($model->cover_path) {
                                return Html::img($model->getCoverUrl(), [
                                    'class' => 'book-cover-small',
                                    'alt' => $model->title
                                ]);
                            }
                            return '<span class="text-muted">Нет обложки</span>';
                        },
                        'format' => 'raw',
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'visibleButtons' => [
                            'update' => !Yii::$app->user->isGuest,
                            'delete' => !Yii::$app->user->isGuest,
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>

    <?php Pjax::end(); ?>

</div>

