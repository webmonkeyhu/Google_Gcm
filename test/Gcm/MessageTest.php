<?php

declare(strict_types=1);

namespace WebmonkeyTest\Google\Gcm;

use PHPUnit\Framework\TestCase;
use Webmonkey\Google\Gcm\Message;

class MessageTest extends TestCase
{
    protected $validRegistrationIds = [
        '1234567890',
        '0987654321',
    ];

    protected $validData = [
        'key'  => 'value',
        'key2' => [
            'value',
        ],
    ];

    /**
     * @var Message
     */
    private $m;

    protected function setUp() : void
    {
        $this->m = new Message();
    }

    public function testExpectedRegistrationIdBehavior()
    {
        self::assertEquals($this->m->getRegistrationIds(), []);
        self::assertStringNotContainsString('registration_ids', $this->m->toJson());
        $this->m->setRegistrationIds($this->validRegistrationIds);
        self::assertEquals($this->m->getRegistrationIds(), $this->validRegistrationIds);
        foreach ($this->validRegistrationIds as $id) {
            $this->m->addRegistrationId($id);
        }
        self::assertEquals($this->m->getRegistrationIds(), $this->validRegistrationIds);
        self::assertStringContainsString('registration_ids', $this->m->toJson());
        $this->m->clearRegistrationIds();
        self::assertEquals($this->m->getRegistrationIds(), []);
        self::assertStringNotContainsString('registration_ids', $this->m->toJson());
        $this->m->addRegistrationId('1029384756');
        self::assertEquals($this->m->getRegistrationIds(), ['1029384756']);
        self::assertStringContainsString('registration_ids', $this->m->toJson());
    }

    public function testInvalidRegistrationIdThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->m->addRegistrationId(['1234']);
    }

    public function testExpectedCollapseKeyBehavior()
    {
        self::assertEquals($this->m->getCollapseKey(), null);
        self::assertStringNotContainsString('collapse_key', $this->m->toJson());
        $this->m->setCollapseKey('my collapse key');
        self::assertEquals($this->m->getCollapseKey(), 'my collapse key');
        self::assertStringContainsString('collapse_key', $this->m->toJson());
        $this->m->setCollapseKey(null);
        self::assertEquals($this->m->getCollapseKey(), null);
        self::assertStringNotContainsString('collapse_key', $this->m->toJson());
    }

    public function testInvalidCollapseKeyThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->m->setCollapseKey(['1234']);
    }

    public function testExpectedDataBehavior()
    {
        self::assertEquals($this->m->getData(), []);
        self::assertStringNotContainsString('data', $this->m->toJson());
        $this->m->setData($this->validData);
        self::assertEquals($this->m->getData(), $this->validData);
        self::assertStringContainsString('data', $this->m->toJson());
        $this->m->clearData();
        self::assertEquals($this->m->getData(), []);
        self::assertStringNotContainsString('data', $this->m->toJson());
        $this->m->addData('mykey', 'myvalue');
        self::assertEquals($this->m->getData(), ['mykey' => 'myvalue']);
        self::assertStringContainsString('data', $this->m->toJson());
    }

    public function testExpectedNotificationBehavior()
    {
        $this->assertEquals($this->m->getNotification(), []);
        $this->assertStringNotContainsString('notification', $this->m->toJson());
        $this->m->setNotification($this->validData);
        $this->assertEquals($this->m->getNotification(), $this->validData);
        $this->assertStringContainsString('notification', $this->m->toJson());
        $this->m->clearNotification();
        $this->assertEquals($this->m->getNotification(), []);
        $this->assertStringNotContainsString('notification', $this->m->toJson());
        $this->m->addNotification('mykey', 'myvalue');
        $this->assertEquals($this->m->getNotification(), ['mykey' => 'myvalue']);
        $this->assertStringContainsString('notification', $this->m->toJson());
    }

    public function testInvalidDataThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->m->addData(['1234'], 'value');
    }

    public function testDuplicateDataKeyThrowsException()
    {
        $this->expectException(\RuntimeException::class);
        $this->m->setData($this->validData);
        $this->m->addData('key', 'value');
    }

    public function testExpectedDelayWhileIdleBehavior()
    {
        self::assertEquals($this->m->getDelayWhileIdle(), false);
        self::assertStringNotContainsString('delay_while_idle', $this->m->toJson());
        $this->m->setDelayWhileIdle(true);
        self::assertEquals($this->m->getDelayWhileIdle(), true);
        self::assertStringContainsString('delay_while_idle', $this->m->toJson());
        $this->m->setDelayWhileIdle(false);
        self::assertEquals($this->m->getDelayWhileIdle(), false);
        self::assertStringNotContainsString('delay_while_idle', $this->m->toJson());
    }

    public function testExpectedTimeToLiveBehavior()
    {
        self::assertEquals($this->m->getTimeToLive(), 2419200);
        self::assertStringNotContainsString('time_to_live', $this->m->toJson());
        $this->m->setTimeToLive(12345);
        self::assertEquals($this->m->getTimeToLive(), 12345);
        self::assertStringContainsString('time_to_live', $this->m->toJson());
        $this->m->setTimeToLive(2419200);
        self::assertEquals($this->m->getTimeToLive(), 2419200);
        self::assertStringNotContainsString('time_to_live', $this->m->toJson());
    }

    public function testExpectedRestrictedPackageBehavior()
    {
        self::assertEquals($this->m->getRestrictedPackageName(), null);
        self::assertStringNotContainsString('restricted_package_name', $this->m->toJson());
        $this->m->setRestrictedPackageName('my.package.name');
        self::assertEquals($this->m->getRestrictedPackageName(), 'my.package.name');
        self::assertStringContainsString('restricted_package_name', $this->m->toJson());
        $this->m->setRestrictedPackageName(null);
        self::assertEquals($this->m->getRestrictedPackageName(), null);
        self::assertStringNotContainsString('restricted_package_name', $this->m->toJson());
    }

    public function testInvalidRestrictedPackageThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->m->setRestrictedPackageName(['1234']);
    }

    public function testExpectedDryRunBehavior()
    {
        self::assertEquals($this->m->getDryRun(), false);
        self::assertStringNotContainsString('dry_run', $this->m->toJson());
        $this->m->setDryRun(true);
        self::assertEquals($this->m->getDryRun(), true);
        self::assertStringContainsString('dry_run', $this->m->toJson());
        $this->m->setDryRun(false);
        self::assertEquals($this->m->getDryRun(), false);
        self::assertStringNotContainsString('dry_run', $this->m->toJson());
    }
}
