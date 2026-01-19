<?php

namespace Rikudou\ActivityPub\Enum;

enum EventJoinMode: string
{
    case Free = "free";
    case Restricted = "restricted";
    case Invite = "invite";
    case External = "external";
}
