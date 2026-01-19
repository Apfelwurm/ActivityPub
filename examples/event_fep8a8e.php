<?php

/**
 * Example usage of FEP-8a8e Event implementation
 * 
 * This demonstrates how to create and use Event objects with the new FEP-8a8e properties.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Rikudou\ActivityPub\Enum\EventJoinMode;
use Rikudou\ActivityPub\Enum\EventParticipationMode;
use Rikudou\ActivityPub\Enum\EventStatus;
use Rikudou\ActivityPub\Enum\RepliesModerationOption;
use Rikudou\ActivityPub\Vocabulary\Extended\Object\Event;
use Rikudou\ActivityPub\Vocabulary\Extended\Object\Place;

// Example 1: Basic Event
echo "=== Example 1: Basic Event ===\n\n";

$event = new Event();
$event->id = 'https://example.com/events/meetup-2026';
$event->name = 'Local Developer Meetup';
$event->summary = 'Monthly gathering for developers in our community';
$event->startTime = new DateTimeImmutable('2026-03-15T18:00:00Z');
$event->endTime = new DateTimeImmutable('2026-03-15T21:00:00Z');

echo json_encode($event, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), "\n\n";

// Example 2: Event with FEP-8a8e properties
echo "=== Example 2: Event with FEP-8a8e Properties ===\n\n";

$event = new Event();
$event->id = 'https://example.com/events/conference-2026';
$event->name = 'ActivityPub Conference 2026';
$event->summary = 'Annual conference for ActivityPub developers and enthusiasts';
$event->content = '<p>Join us for three days of talks, workshops, and networking!</p>';
$event->startTime = new DateTimeImmutable('2026-06-01T09:00:00Z');
$event->endTime = new DateTimeImmutable('2026-06-03T17:00:00Z');

// FEP-8a8e Event-specific properties
$event->joinMode = EventJoinMode::Restricted;
$event->participationMode = EventParticipationMode::Mixed;
$event->eventStatus = EventStatus::Confirmed;
$event->maximumAttendeeCapacity = 500;
$event->participantCount = 234;
$event->remainingAttendeeCapacity = 266;
$event->repliesModerationOption = RepliesModerationOption::Restricted;
$event->anonymousParticipationEnabled = false;

echo json_encode($event, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), "\n\n";

// Example 3: Event with location
echo "=== Example 3: Event with Location ===\n\n";

$place = new Place();
$place->id = 'https://example.com/places/conference-center';
$place->name = 'Downtown Conference Center';
$place->latitude = 40.7128;
$place->longitude = -74.0060;

$event = new Event();
$event->id = 'https://example.com/events/workshop-2026';
$event->name = 'ActivityPub Workshop';
$event->startTime = new DateTimeImmutable('2026-04-20T14:00:00Z');
$event->endTime = new DateTimeImmutable('2026-04-20T17:00:00Z');
$event->location = $place;
$event->joinMode = EventJoinMode::Free;
$event->participationMode = EventParticipationMode::Offline;
$event->eventStatus = EventStatus::Confirmed;
$event->maximumAttendeeCapacity = 30;

echo json_encode($event, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), "\n\n";

// Example 4: Online Event
echo "=== Example 4: Online Event ===\n\n";

$event = new Event();
$event->id = 'https://example.com/events/webinar-2026';
$event->name = 'Introduction to FEP-8a8e';
$event->summary = 'Learn about the Event Federation proposal';
$event->startTime = new DateTimeImmutable('2026-05-10T15:00:00Z');
$event->endTime = new DateTimeImmutable('2026-05-10T16:00:00Z');
$event->url = 'https://example.com/webinar-room';
$event->joinMode = EventJoinMode::Free;
$event->participationMode = EventParticipationMode::Online;
$event->eventStatus = EventStatus::Confirmed;
$event->repliesModerationOption = RepliesModerationOption::Allow;
$event->anonymousParticipationEnabled = true;

echo json_encode($event, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), "\n\n";

// Example 5: Invite-only Event
echo "=== Example 5: Invite-only Event ===\n\n";

$event = new Event();
$event->id = 'https://example.com/events/private-dinner-2026';
$event->name = 'Core Team Dinner';
$event->startTime = new DateTimeImmutable('2026-06-03T19:00:00Z');
$event->endTime = new DateTimeImmutable('2026-06-03T22:00:00Z');
$event->joinMode = EventJoinMode::Invite;
$event->participationMode = EventParticipationMode::Offline;
$event->eventStatus = EventStatus::Tentative;
$event->maximumAttendeeCapacity = 20;
$event->repliesModerationOption = RepliesModerationOption::Closed;
$event->anonymousParticipationEnabled = false;

echo json_encode($event, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), "\n";
