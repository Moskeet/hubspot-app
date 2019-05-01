<?php

namespace App\Hubspot;

class HubspotHelper
{
    /**
     * @param string|int $value
     *
     * @return \DateTime|null
     */
    public function convertTimestampToDateTime($value): ?\DateTime
    {
        if (empty($value)) {
            return null;
        }

        return \DateTime::createFromFormat(
            'U',
            substr($value, 0, -3),
            new \DateTimeZone('UTC')
        );
    }
}
