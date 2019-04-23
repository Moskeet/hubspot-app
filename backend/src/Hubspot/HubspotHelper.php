<?php

namespace App\Hubspot;

class HubspotHelper
{
    /**
     * @param string|int $value
     *
     * @return \DateTime
     */
    public function convertTimestampToDateTime($value): \DateTime
    {
        return \DateTime::createFromFormat(
            'U',
            substr($value, 0, -3),
            new \DateTimeZone('UTC')
        );
    }
}
