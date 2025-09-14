<?php

namespace app\services;

use yii\base\Event;

/**
 * Event triggered when a book is created
 */
class BookCreatedEvent extends Event
{
    /**
     * @var int Book ID
     */
    public $bookId;

    /**
     * @var array Array of author IDs
     */
    public $authorIds;
}

