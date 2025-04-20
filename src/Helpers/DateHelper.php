<?php

namespace Azuriom\Plugin\Tailwindify\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function humanReadable($timestamp)
    {
        if (empty($timestamp)) {
            return 'Jamais';
        }

        $date = Carbon::createFromTimestamp($timestamp, 'Europe/Paris');
        $now = Carbon::now('Europe/Paris');

        if ($date->isToday()) {
            return 'Aujourd\'hui à ' . $date->format('H:i');
        }

        if ($date->isYesterday()) {
            return 'Hier à ' . $date->format('H:i');
        }

        if ($date->diffInDays($now) < 7) {
            return 'Il y a ' . $date->diffInDays($now) . ' jours';
        }

        return $date->format('d/m/Y à H:i');
    }
}

