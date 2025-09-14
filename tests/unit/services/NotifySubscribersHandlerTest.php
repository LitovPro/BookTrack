<?php

namespace app\tests\unit\services;

use Codeception\Test\Unit;
use app\services\NotifySubscribersHandler;
use app\services\SmsSenderInterface;
use app\services\SubscriptionService;
use app\services\BookCreatedEvent;
use PHPUnit\Framework\MockObject\MockObject;
use app\models\Book;
use app\models\Author;
use Yii;

/**
 * Unit tests for NotifySubscribersHandler
 */
class NotifySubscribersHandlerTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * Test SMS sending to subscribers
     */
    public function testHandleSendsSmsToSubscribers()
    {
        // Create mock SMS sender
        /** @var SmsSenderInterface&MockObject $mockSmsSender */
        $mockSmsSender = $this->createMock(SmsSenderInterface::class);
        $mockSmsSender->expects($this->once())
            ->method('sendSms')
            ->with('+7-999-123-45-67', $this->stringContains('Новая книга'));

        // Create mock subscription service
        /** @var SubscriptionService&MockObject $mockSubscriptionService */
        $mockSubscriptionService = $this->createMock(SubscriptionService::class);
        $mockSubscriptionService->expects($this->once())
            ->method('getSubscribersForAuthors')
            ->with([1])
            ->willReturn(['+7-999-123-45-67']);

        // Create handler
        $handler = new NotifySubscribersHandler($mockSmsSender, $mockSubscriptionService);

        // Create event
        $event = new BookCreatedEvent();
        $event->bookId = 1;
        $event->authorIds = [1];

        // Mock book and author data
        $this->mockBookAndAuthorData();

        // Execute
        $handler->handle($event);
    }

    /**
     * Test handler with no subscribers
     */
    public function testHandleWithNoSubscribers()
    {
        // Create mock SMS sender (should not be called)
        /** @var SmsSenderInterface&MockObject $mockSmsSender */
        $mockSmsSender = $this->createMock(SmsSenderInterface::class);
        $mockSmsSender->expects($this->never())
            ->method('sendSms');

        // Create mock subscription service
        /** @var SubscriptionService&MockObject $mockSubscriptionService */
        $mockSubscriptionService = $this->createMock(SubscriptionService::class);
        $mockSubscriptionService->expects($this->once())
            ->method('getSubscribersForAuthors')
            ->with([1])
            ->willReturn([]);

        // Create handler
        $handler = new NotifySubscribersHandler($mockSmsSender, $mockSubscriptionService);

        // Create event
        $event = new BookCreatedEvent();
        $event->bookId = 1;
        $event->authorIds = [1];

        // Mock book and author data
        $this->mockBookAndAuthorData();

        // Execute
        $handler->handle($event);
    }

    /**
     * Mock book and author data for tests
     */
    private function mockBookAndAuthorData()
    {
        // Mock Book::findOne
        $mockBook = $this->createMock(Book::class);
        $mockBook->title = 'Test Book';

        // Mock Author::find
        $mockAuthor = $this->createMock(Author::class);
        $mockAuthor->full_name = 'Test Author';

        // Mock static methods using reflection or create test data
        // For now, we'll skip the database mocking in unit tests
    }
}
