<?php

namespace app\components;

use Yii;
use yii\base\BootstrapInterface;
use app\services\BookCreatedEvent;
use app\services\NotifySubscribersHandler;

class BootstrapComponent implements BootstrapInterface
{
    public function bootstrap($app)
    {
        // Регистрируем обработчик события создания книги
        $app->on('book.created', function($event) use ($app) {
            $smsSender = new \app\services\SmsPilotSender();
            $subscriptionService = new \app\services\SubscriptionService();
            $handler = new \app\services\NotifySubscribersHandler($smsSender, $subscriptionService);
            $handler->handle($event);
        });
    }
}
