<?php

namespace App\Adapters;

use App\WickedReports\WickedReportContactData;

class HubspotToWickedReportAdapter
{
    /**
     * @param array $hubspotContacts
     *
     * @return array
     */
    public function adapt(array $hubspotContacts): array
    {
        return array_map(function($element) {
            $wickedReportContactData = new WickedReportContactData();

            var_dump($element);
            return $wickedReportContactData;
        }, $hubspotContacts);
    }
}
