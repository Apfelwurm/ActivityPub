<?php

namespace Rikudou\ActivityPub\Enum;

enum RepliesModerationOption: string
{
    case Allow = "allow";
    case Restricted = "restricted";
    case Closed = "closed";
}
