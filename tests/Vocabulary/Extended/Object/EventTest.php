<?php

namespace Rikudou\Tests\ActivityPub\Vocabulary\Extended\Object;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Rikudou\ActivityPub\Enum\EventJoinMode;
use Rikudou\ActivityPub\Enum\EventParticipationMode;
use Rikudou\ActivityPub\Enum\EventStatus;
use Rikudou\ActivityPub\Enum\RepliesModerationOption;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Exception\InvalidPropertyValueException;
use Rikudou\ActivityPub\Vocabulary\Core\Link;
use Rikudou\ActivityPub\Vocabulary\Extended\Object\Event;
use Rikudou\ActivityPub\Vocabulary\Extended\Object\Place;
use Rikudou\Tests\ActivityPub\AbstractVocabularyTest;

#[CoversClass(Event::class)]
final class EventTest extends AbstractVocabularyTest
{
    public function testBasicEventCreation(): void
    {
        $event = new Event();
        $event->validatorMode = ValidatorMode::None;
        $event->id = 'https://example.com/events/1';
        $event->name = 'Community Meetup';
        $event->startTime = new DateTimeImmutable('2026-02-15T18:00:00Z');
        $event->endTime = new DateTimeImmutable('2026-02-15T20:00:00Z');

        $json = $event->jsonSerialize();

        self::assertSame('Event', $json['type']);
        self::assertSame('https://example.com/events/1', $json['id']);
        self::assertSame('Community Meetup', $json['name']);
    }

    public function testFep8a8eProperties(): void
    {
        $event = new Event();
        $event->validatorMode = ValidatorMode::None;
        $event->id = 'https://example.com/events/1';
        $event->joinMode = EventJoinMode::Free;
        $event->participationMode = EventParticipationMode::Mixed;
        $event->eventStatus = EventStatus::Confirmed;
        $event->maximumAttendeeCapacity = 100;
        $event->participantCount = 45;
        $event->remainingAttendeeCapacity = 55;
        $event->repliesModerationOption = RepliesModerationOption::Allow;
        $event->anonymousParticipationEnabled = false;

        $json = $event->jsonSerialize();

        self::assertSame('free', $json['joinMode']);
        self::assertSame('mixed', $json['participationMode']);
        self::assertSame('confirmed', $json['eventStatus']);
        self::assertSame(100, $json['maximumAttendeeCapacity']);
        self::assertSame(45, $json['participantCount']);
        self::assertSame(55, $json['remainingAttendeeCapacity']);
        self::assertSame('allow', $json['repliesModerationOption']);
        self::assertFalse($json['anonymousParticipationEnabled']);
    }

    public function testEventWithLocation(): void
    {
        $place = new Place();
        $place->validatorMode = ValidatorMode::None;
        $place->id = 'https://example.com/places/1';
        $place->name = 'Conference Center';
        $place->latitude = 40.7128;
        $place->longitude = -74.0060;

        $event = new Event();
        $event->validatorMode = ValidatorMode::None;
        $event->id = 'https://example.com/events/1';
        $event->location = $place;

        $json = $event->jsonSerialize();

        self::assertIsArray($json['location']);
        self::assertSame('Place', $json['location']['type']);
        self::assertSame('Conference Center', $json['location']['name']);
    }

    public function testEventWithLinkAsProperty(): void
    {
        $event = new Event();
        $event->validatorMode = ValidatorMode::None;
        $event->id = 'https://example.com/events/1';
        $event->joinMode = Link::fromString('https://example.com/custom-join-mode');

        $json = $event->jsonSerialize();

        self::assertIsArray($json['joinMode']);
        self::assertSame('https://example.com/custom-join-mode', $json['joinMode']['href']);
    }

    #[DataProvider('validationData')]
    public function testJoinModeValidation(ValidatorMode $validatorMode, mixed $value, bool $expectException): void
    {
        $event = $this->createMinimalValidObject(Event::class, $validatorMode);
        $event->validatorMode = $validatorMode;
        
        if ($expectException) {
            $this->expectException(InvalidPropertyValueException::class);
        }
        
        $event->joinMode = $value;

        self::assertIsArray($event->jsonSerialize());
    }

    public static function validationData(): iterable
    {
        yield 'none mode accepts enum' => [ValidatorMode::None, EventJoinMode::Free, false];
        yield 'none mode accepts string' => [ValidatorMode::None, 'free', false];
        yield 'none mode accepts link' => [ValidatorMode::None, Link::fromString('https://example.com/join'), false];
        
        yield 'lax mode accepts enum' => [ValidatorMode::Lax, EventJoinMode::Restricted, false];
        yield 'lax mode accepts link' => [ValidatorMode::Lax, Link::fromString('https://example.com/join'), false];
    }

    #[DataProvider('capacityValidationData')]
    public function testCapacityValidation(ValidatorMode $validatorMode, mixed $value, bool $expectException): void
    {
        $event = $this->createMinimalValidObject(Event::class, $validatorMode);
        $event->validatorMode = $validatorMode;
        
        if ($expectException) {
            $this->expectException(InvalidPropertyValueException::class);
        }
        
        $event->maximumAttendeeCapacity = $value;

        self::assertIsArray($event->jsonSerialize());
    }

    public static function capacityValidationData(): iterable
    {
        yield 'none mode accepts positive integer' => [ValidatorMode::None, 100, false];
        yield 'none mode accepts zero' => [ValidatorMode::None, 0, false];
        yield 'none mode accepts string (invalid but no validation)' => [ValidatorMode::None, '100', false];
        
        yield 'lax mode accepts positive integer' => [ValidatorMode::Lax, 50, false];
        yield 'lax mode rejects negative' => [ValidatorMode::Lax, -1, true];
        yield 'lax mode rejects string' => [ValidatorMode::Lax, '50', true];
    }

    #[DataProvider('booleanValidationData')]
    public function testAnonymousParticipationValidation(ValidatorMode $validatorMode, mixed $value, bool $expectException): void
    {
        $event = $this->createMinimalValidObject(Event::class, $validatorMode);
        $event->validatorMode = $validatorMode;
        
        if ($expectException) {
            $this->expectException(InvalidPropertyValueException::class);
        }
        
        $event->anonymousParticipationEnabled = $value;

        self::assertIsArray($event->jsonSerialize());
    }

    public static function booleanValidationData(): iterable
    {
        yield 'none mode accepts true' => [ValidatorMode::None, true, false];
        yield 'none mode accepts false' => [ValidatorMode::None, false, false];
        yield 'none mode accepts string (invalid but no validation)' => [ValidatorMode::None, 'true', false];
        
        yield 'lax mode accepts true' => [ValidatorMode::Lax, true, false];
        yield 'lax mode accepts false' => [ValidatorMode::Lax, false, false];
        yield 'lax mode rejects string' => [ValidatorMode::Lax, 'true', true];
        yield 'lax mode rejects integer' => [ValidatorMode::Lax, 1, true];
    }

    public function testCompleteEventJsonSerialization(): void
    {
        $event = new Event();
        $event->validatorMode = ValidatorMode::None;
        $event->id = 'https://example.com/events/conference-2026';
        $event->name = 'ActivityPub Conference 2026';
        $event->summary = 'Annual conference for ActivityPub developers';
        $event->startTime = new DateTimeImmutable('2026-06-01T09:00:00Z');
        $event->endTime = new DateTimeImmutable('2026-06-03T17:00:00Z');
        $event->joinMode = EventJoinMode::Restricted;
        $event->participationMode = EventParticipationMode::Mixed;
        $event->eventStatus = EventStatus::Confirmed;
        $event->maximumAttendeeCapacity = 500;
        $event->participantCount = 234;
        $event->remainingAttendeeCapacity = 266;
        $event->repliesModerationOption = RepliesModerationOption::Restricted;
        $event->anonymousParticipationEnabled = false;

        $json = $event->jsonSerialize();

        self::assertSame('Event', $json['type']);
        self::assertArrayHasKey('joinMode', $json);
        self::assertArrayHasKey('participationMode', $json);
        self::assertArrayHasKey('eventStatus', $json);
        self::assertArrayHasKey('maximumAttendeeCapacity', $json);
        self::assertArrayHasKey('participantCount', $json);
        self::assertArrayHasKey('remainingAttendeeCapacity', $json);
        self::assertArrayHasKey('repliesModerationOption', $json);
        self::assertArrayHasKey('anonymousParticipationEnabled', $json);

        // Verify enum serialization
        self::assertSame('restricted', $json['joinMode']);
        self::assertSame('mixed', $json['participationMode']);
        self::assertSame('confirmed', $json['eventStatus']);
        self::assertSame('restricted', $json['repliesModerationOption']);
    }
}
