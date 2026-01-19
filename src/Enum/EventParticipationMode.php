<?php

namespace Rikudou\ActivityPub\Enum;

enum EventParticipationMode: string
{
    case Online = "online";
    case Offline = "offline";
    case Mixed = "mixed";
}
