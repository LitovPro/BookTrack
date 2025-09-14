<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'BookTrack - Управление книгами и авторами';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-light p-5 rounded">
        <h1 class="display-4">Добро пожаловать в BookTrack!</h1>
        <p class="lead">Система управления книгами и авторами с уведомлениями о новых изданиях.</p>
        <hr class="my-4">
        <p>Просматривайте каталог книг, изучайте информацию об авторах и подписывайтесь на уведомления о новых книгах.</p>
        <div class="row mt-4">
            <div class="col-md-4">
                <?= Html::a('Просмотреть книги', ['/book/index'], ['class' => 'btn btn-primary btn-lg']) ?>
            </div>
            <div class="col-md-4">
                <?= Html::a('Просмотреть авторов', ['/author/index'], ['class' => 'btn btn-success btn-lg']) ?>
            </div>
            <div class="col-md-4">
                <?= Html::a('Отчёт ТОП-10', ['/report/top-authors'], ['class' => 'btn btn-info btn-lg']) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Возможности системы</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-book text-primary"></i> Каталог книг с поиском и фильтрацией</li>
                        <li><i class="fas fa-user text-success"></i> Информация об авторах и их произведениях</li>
                        <li><i class="fas fa-bell text-warning"></i> Подписка на уведомления о новых книгах</li>
                        <li><i class="fas fa-chart-bar text-info"></i> Отчёты по популярности авторов</li>
                        <li><i class="fas fa-edit text-secondary"></i> Управление контентом (для авторизованных пользователей)</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Как это работает</h5>
                </div>
                <div class="card-body">
                    <ol>
                        <li><strong>Просмотр:</strong> Изучайте каталог книг и авторов</li>
                        <li><strong>Подписка:</strong> Оставляйте номер телефона для получения уведомлений</li>
                        <li><strong>Уведомления:</strong> Получайте SMS при выходе новых книг любимых авторов</li>
                        <li><strong>Отчёты:</strong> Анализируйте популярность авторов по годам</li>
                    </ol>
                    <?php if (Yii::$app->user->isGuest): ?>
                        <p class="text-muted mt-3">
                            <small>Для управления контентом необходимо <a href="<?= Url::to(['/site/login']) ?>">войти в систему</a> или <a href="<?= Url::to(['/site/signup']) ?>">зарегистрироваться</a></small>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

