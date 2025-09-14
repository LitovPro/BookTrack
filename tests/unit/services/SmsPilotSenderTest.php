<?php

namespace app\tests\unit\services;

use Codeception\Test\Unit;
use app\services\SmsPilotSender;
use app\models\SmsLog;

/**
 * Unit tests for SmsPilotSender
 */
class SmsPilotSenderTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * Test SMS sending in emulation mode
     */
    public function testSendSmsInEmulationMode()
    {
        $sender = new SmsPilotSender('эмулятор');

        $result = $sender->sendSms('+7-999-123-45-67', 'Test message');

        $this->assertTrue($result['success']);
        $this->assertEquals('EMULATED', $result['status']);
        $this->assertStringContainsString('SMS sent in emulation mode', $result['message']);
        $this->assertArrayHasKey('response', $result);
        $this->assertArrayHasKey('id', $result['response']);
    }

    /**
     * Test SMS logging
     */
    public function testSmsLogging()
    {
        $this->markTestSkipped('Requires database setup');
    }

    /**
     * Test SMS sending with different phone numbers
     */
    public function testSendSmsWithDifferentPhones()
    {
        $sender = new SmsPilotSender('эмулятор');

        $phones = [
            '+7-999-123-45-67',
            '+7-999-123-45-68',
            '+7-999-123-45-69',
        ];

        foreach ($phones as $phone) {
            $result = $sender->sendSms($phone, 'Test message');
            $this->assertTrue($result['success']);
            $this->assertEquals('EMULATED', $result['status']);
        }
    }

    /**
     * Test SMS sending with long message
     */
    public function testSendSmsWithLongMessage()
    {
        $sender = new SmsPilotSender('эмулятор');

        $longMessage = str_repeat('Test message ', 100);

        $result = $sender->sendSms('+7-999-123-45-67', $longMessage);

        $this->assertTrue($result['success']);
        $this->assertEquals('EMULATED', $result['status']);
    }
}
