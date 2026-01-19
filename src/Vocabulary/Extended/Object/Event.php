<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use Rikudou\ActivityPub\Enum\EventJoinMode;
use Rikudou\ActivityPub\Enum\EventParticipationMode;
use Rikudou\ActivityPub\Enum\EventStatus;
use Rikudou\ActivityPub\Enum\RepliesModerationOption;
use Rikudou\ActivityPub\Validator\Condition\NotNull;
use Rikudou\ActivityPub\Validator\ConditionalValidator;
use Rikudou\ActivityPub\Validator\IsBoolValidator;
use Rikudou\ActivityPub\Validator\IsEnumValidator;
use Rikudou\ActivityPub\Validator\NonNegativeNumberValidator;
use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

/**
 * Represents any kind of event.
 * 
 * This implementation follows FEP-8a8e (Event Federation) which extends the base ActivityStreams Event type
 * with additional properties for event management, participation, and moderation.
 */
class Event extends BaseObject
{
    public string $type {
        get => 'Event';
    }

    /**
     * Specifies how people can join the event.
     * 
     * - "free": Anyone can join without approval
     * - "restricted": Join requests require approval
     * - "invite": Only invited users can join
     * - "external": Registration happens on an external platform
     * 
     * Can be set using an EventJoinMode enum value, a Link object, or a string URL
     * (which will be automatically converted to a Link for extensibility).
     */
    public EventJoinMode|Link|null $joinMode = null {
        get => $this->joinMode;
        set (EventJoinMode|Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->joinMode = $value;
            } else {
                $this->set('joinMode', $value);
            }
        }
    }

    /**
     * Specifies the format of participation.
     * 
     * - "online": Virtual/online event
     * - "offline": Physical/in-person event
     * - "mixed": Hybrid event (both online and offline)
     * 
     * Can be set using an EventParticipationMode enum value, a Link object, or a string URL
     * (which will be automatically converted to a Link for extensibility).
     */
    public EventParticipationMode|Link|null $participationMode = null {
        get => $this->participationMode;
        set (EventParticipationMode|Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->participationMode = $value;
            } else {
                $this->set('participationMode', $value);
            }
        }
    }

    /**
     * Specifies the current status of the event.
     * 
     * - "tentative": Event might be cancelled
     * - "confirmed": Event is confirmed to happen
     * - "cancelled": Event has been cancelled
     * 
     * Can be set using an EventStatus enum value, a Link object, or a string URL
     * (which will be automatically converted to a Link for extensibility).
     */
    public EventStatus|Link|null $eventStatus = null {
        get => $this->eventStatus;
        set (EventStatus|Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->eventStatus = $value;
            } else {
                $this->set('eventStatus', $value);
            }
        }
    }

    /**
     * The maximum number of attendees/participants allowed for this event.
     */
    public ?int $maximumAttendeeCapacity = null {
        get => $this->maximumAttendeeCapacity;
        set {
            if ($this->__directSet) {
                $this->maximumAttendeeCapacity = $value;
            } else {
                $this->set('maximumAttendeeCapacity', $value);
            }
        }
    }

    /**
     * The current number of participants who have joined or are attending the event.
     */
    public ?int $participantCount = null {
        get => $this->participantCount;
        set {
            if ($this->__directSet) {
                $this->participantCount = $value;
            } else {
                $this->set('participantCount', $value);
            }
        }
    }

    /**
     * The number of available spots remaining for the event.
     * This is typically calculated as maximumAttendeeCapacity - participantCount.
     */
    public ?int $remainingAttendeeCapacity = null {
        get => $this->remainingAttendeeCapacity;
        set {
            if ($this->__directSet) {
                $this->remainingAttendeeCapacity = $value;
            } else {
                $this->set('remainingAttendeeCapacity', $value);
            }
        }
    }

    /**
     * Specifies how replies/comments on the event are moderated.
     * 
     * - "allow": All comments are allowed
     * - "restricted": Comments are moderated before appearing
     * - "closed": No comments are allowed
     * 
     * Can be set using a RepliesModerationOption enum value, a Link object, or a string URL
     * (which will be automatically converted to a Link for extensibility).
     */
    public RepliesModerationOption|Link|null $repliesModerationOption = null {
        get => $this->repliesModerationOption;
        set (RepliesModerationOption|Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->repliesModerationOption = $value;
            } else {
                $this->set('repliesModerationOption', $value);
            }
        }
    }

    /**
     * Whether anonymous participation is enabled for this event.
     * When true, users can participate without revealing their identity.
     */
    public ?bool $anonymousParticipationEnabled = null {
        get => $this->anonymousParticipationEnabled;
        set {
            if ($this->__directSet) {
                $this->anonymousParticipationEnabled = $value;
            } else {
                $this->set('anonymousParticipationEnabled', $value);
            }
        }
    }

    protected function getValidators(): iterable
    {
        yield parent::getValidators();
        yield from [
            'joinMode' => new ConditionalValidator(
                new NotNull(),
                new IsEnumValidator(EventJoinMode::class),
            ),
            'participationMode' => new ConditionalValidator(
                new NotNull(),
                new IsEnumValidator(EventParticipationMode::class),
            ),
            'eventStatus' => new ConditionalValidator(
                new NotNull(),
                new IsEnumValidator(EventStatus::class),
            ),
            'maximumAttendeeCapacity' => new ConditionalValidator(
                new NotNull(),
                new NonNegativeNumberValidator(),
            ),
            'participantCount' => new ConditionalValidator(
                new NotNull(),
                new NonNegativeNumberValidator(),
            ),
            'remainingAttendeeCapacity' => new ConditionalValidator(
                new NotNull(),
                new NonNegativeNumberValidator(),
            ),
            'repliesModerationOption' => new ConditionalValidator(
                new NotNull(),
                new IsEnumValidator(RepliesModerationOption::class),
            ),
            'anonymousParticipationEnabled' => new ConditionalValidator(
                new NotNull(),
                new IsBoolValidator(),
            ),
        ];
    }
}
