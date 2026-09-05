<?php

namespace src\Domain\Mail\Actions;

use Carbon\Carbon;

class NextScheduledMailSendAction
{
    public const INTERVAL_MINUTES = 5;

    public static function scheduledAtFormatted(?Carbon $now = null): string
    {
        $now = $now ?? now('Europe/Riga');
        $remainder = $now->minute % self::INTERVAL_MINUTES;

        if ($remainder === 0 && $now->second === 0) {
            return $now->format('H:i');
        }

        $minutesToAdd = $remainder === 0
            ? self::INTERVAL_MINUTES
            : self::INTERVAL_MINUTES - $remainder;

        return $now->copy()
            ->startOfMinute()
            ->addMinutes($minutesToAdd)
            ->format('H:i');
    }
}
