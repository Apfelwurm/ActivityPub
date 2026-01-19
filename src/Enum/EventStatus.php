<?php

namespace Rikudou\ActivityPub\Enum;

enum EventStatus: string
{
    case Tentative = "tentative";
    case Confirmed = "confirmed";
    case Cancelled = "cancelled";
}
