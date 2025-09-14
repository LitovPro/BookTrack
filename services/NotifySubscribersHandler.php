<?php

namespace app\services;

use Yii;
use app\models\Book;
use app\models\Author;

/**
 * Handler for book created event - sends SMS to subscribers
 */
class NotifySubscribersHandler
{
    /**
     * @var SmsSenderInterface
     */
    private $smsSender;

    /**
     * @var SubscriptionService
     */
    private $subscriptionService;

    /**
     * Constructor
     *
     * @param SmsSenderInterface $smsSender
     * @param SubscriptionService $subscriptionService
     */
    public function __construct(SmsSenderInterface $smsSender, SubscriptionService $subscriptionService)
    {
        $this->smsSender = $smsSender;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Handle book created event
     *
     * @param BookCreatedEvent $event
     */
    public function handle(BookCreatedEvent $event): void
    {
        try {
            // Get book information
            $book = Book::findOne($event->bookId);
            if (!$book) {
                Yii::error("Book not found: {$event->bookId}", __METHOD__);
                return;
            }

            // Get author names
            $authors = Author::find()
                ->where(['id' => $event->authorIds])
                ->all();

            $authorNames = array_map(function($author) {
                return $author->full_name;
            }, $authors);

            // Get all subscribers for these authors
            $subscribers = $this->subscriptionService->getSubscribersForAuthors($event->authorIds);

            if (empty($subscribers)) {
                Yii::info("No subscribers found for authors: " . implode(', ', $event->authorIds), __METHOD__);
                return;
            }

            // Prepare message
            $message = $this->prepareMessage($book->title, $authorNames);

            // Send SMS to all subscribers
            foreach ($subscribers as $phone) {
                try {
                    $this->smsSender->sendSms($phone, $message);
                    Yii::info("SMS sent to {$phone} for book: {$book->title}", __METHOD__);
                } catch (\Exception $e) {
                    Yii::error("Failed to send SMS to {$phone}: " . $e->getMessage(), __METHOD__);
                }
            }

        } catch (\Exception $e) {
            Yii::error("Error in NotifySubscribersHandler: " . $e->getMessage(), __METHOD__);
        }
    }

    /**
     * Prepare SMS message
     *
     * @param string $bookTitle Book title
     * @param array $authorNames Array of author names
     * @return string
     */
    private function prepareMessage(string $bookTitle, array $authorNames): string
    {
        $authorsText = implode(', ', $authorNames);
        return "Новая книга от вашего любимого автора!\n\n" .
               "Название: {$bookTitle}\n" .
               "Автор(ы): {$authorsText}\n\n" .
               "С уважением, команда BookTrack";
    }
}

