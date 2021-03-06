<?php

namespace App\WickedReports;

class WickerReportManager
{
    /**
     * @var WickedReportProvider
     */
    private $wickedReportProvider;

    /**
     * @var WickedReportConverter
     */
    private $wickedReportConverter;

    /**
     * @param WickedReportProvider $wickedReportProvider
     * @param WickedReportConverter $wickedReportConverter
     */
    public function __construct(
        WickedReportProvider $wickedReportProvider,
        WickedReportConverter $wickedReportConverter
    ) {
        $this->wickedReportProvider = $wickedReportProvider;
        $this->wickedReportConverter = $wickedReportConverter;
    }

    /**
     * @param WickedReportContacts $wickedReportContacts
     *
     * @return bool
     */
    public function storeContacts(WickedReportContacts $wickedReportContacts): bool
    {
        $list = [];

        foreach ($wickedReportContacts->getContacts() as $contact) {
            $list[] = $this->wickedReportConverter->contactToArray($contact);
        }

        return $this->wickedReportProvider->storeContacts($list);
    }
}
