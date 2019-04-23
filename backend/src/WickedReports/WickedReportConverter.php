<?php

namespace App\WickedReports;

class WickedReportConverter
{
    /**
     * @param WickedReportContactData $data
     *
     * @return array
     */
    public function contactToArray(WickedReportContactData $data): array
    {
        $fields = [
            'SourceSystem' => $data->getSystem(),
            'SourceID' => $data->getSourceId(),
            'CreateDate' => $data->getCreateDate()->format('Y-m-d H:i:s'),
            'Email' => $data->getEmail(),
            'FirstName' => $data->getFirstName(),
            'LastName' => $data->getLastName(),
        ];

        if ($data->getCity()) {
            $fields['City'] = $data->getCity();
        }

        if ($data->getState()) {
            $fields['State'] = $data->getState();
        }

        if ($data->getCountry()) {
            $fields['Country'] = $data->getCountry();
        }

        if ($data->getIpAddress()) {
            $fields['IP_Address'] = $data->getIpAddress();
        }

        return $fields;
    }
}
