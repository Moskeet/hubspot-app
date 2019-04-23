<?php

namespace App\WickedReports;

class WickerReportManager
{
    /**
     * @var WickedReportProvider
     */
    private $wickedReportProvider;

    /**
     * @param WickedReportProvider $wickedReportProvider
     */
    public function __construct(
        WickedReportProvider $wickedReportProvider
    ) {
        $this->wickedReportProvider = $wickedReportProvider;
    }

    /**
     * @param WickedReportContacts $wickedReportContacts
     *
     * @return bool
     */
    public function storeContacts(WickedReportContacts $wickedReportContacts): bool
    {
        return $this->wickedReportProvider->storeContacts($wickedReportContacts->getContacts());
    }
}
