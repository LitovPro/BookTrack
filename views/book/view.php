<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Информация о книге</h5>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'title',
                            'year',
                            'isbn',
                            [
                                'attribute' => 'description',
                                'format' => 'ntext',
                            ],
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
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Обложка</h5>
                </div>
                <div class="card-body text-center">
                    <?php if ($model->cover_path): ?>
                        <?= Html::img($model->getCoverUrl(), [
                            'class' => 'book-cover',
                            'alt' => $model->title
                        ]) ?>
                    <?php else: ?>
                        <div class="text-muted">
                            <i class="fas fa-image fa-3x"></i>
                            <p>Обложка не загружена</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить эту книгу?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
        <?= Html::a('Назад к списку', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

</div>

