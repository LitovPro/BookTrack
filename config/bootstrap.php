<?php

use app\services\BookCreatedEvent;
use app\services\NotifySubscribersHandler;
use app\services\SmsSenderInterface;
use app\services\SmsPilotSender;
use app\services\SubscriptionService;

// Register event handler for book creation
Yii::$app->on('book.created', function (BookCreatedEvent $event) {
    $smsSender = new SmsPilotSender();
    $subscriptionService = new SubscriptionService();
    $handler = new NotifySubscribersHandler($smsSender, $subscriptionService);
    $handler->handle($event);
});

