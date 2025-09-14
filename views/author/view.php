<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\ActiveForm;
use app\models\AuthorSubscription;

/* @var $this yii\web\View */
/* @var $model app\models\Author */
/* @var $books app\models\Book[] */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Информация об авторе</h5>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'full_name',
                            [
                                'attribute' => 'books_count',
                                'label' => 'Количество книг',
                                'value' => function ($model) {
                                    return $model->getBooksCount();
                                },
                            ],
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Книги автора</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($books)): ?>
                        <div class="row">
                            <?php foreach ($books as $book): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <?= Html::a($book->title, ['/book/view', 'id' => $book->id]) ?>
                                            </h6>
                                            <p class="card-text">
                                                <small class="text-muted">Год: <?= $book->year ?></small>
                                                <?php if ($book->isbn): ?>
                                                    <br><small class="text-muted">ISBN: <?= $book->isbn ?></small>
                                                <?php endif; ?>
                                            </p>
                                            <?php if ($book->cover_path): ?>
                                                <div class="text-center">
                                                    <?= Html::img($book->getCoverUrl(), [
                                                        'class' => 'book-cover-small',
                                                        'alt' => $book->title
                                                    ]) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">У этого автора пока нет книг в каталоге.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="subscription-form">
                <h5>Подписаться на уведомления</h5>
                <p class="text-muted">Получайте SMS-уведомления о новых книгах этого автора</p>

                <?php $subscriptionModel = new AuthorSubscription(); ?>
                <?php $form = ActiveForm::begin([
                    'action' => ['/subscription/create'],
                    'method' => 'post',
                ]); ?>

                <?= $form->field($subscriptionModel, 'author_id')->hiddenInput(['value' => $model->id])->label(false) ?>

                <?= $form->field($subscriptionModel, 'phone')->textInput([
                    'placeholder' => '+7 (999) 123-45-67',
                    'maxlength' => true
                ]) ?>

                <!-- Honeypot field -->
                <div style="display: none;">
                    <?= $form->field($subscriptionModel, 'website')->textInput() ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Подписаться', ['class' => 'btn btn-primary btn-block']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить этого автора?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
        <?= Html::a('Назад к списку', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

</div>

