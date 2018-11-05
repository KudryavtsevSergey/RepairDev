<?php
/**
 * Created by PhpStorm.
 * User: Sun
 * Date: 01.11.2018
 * Time: 20:06
 */

namespace classes\service;

class CarfaxService
{
    private $url = "https://www.carfax.eu/free-check-result";

    public function getCurData($vinNumber)
    {
        $parameters = [
            'vin' => $vinNumber
        ];

        $url = $this->url . '?' . http_build_query($parameters);

        return $this->treatmentHtml($url);
    }

    private function treatmentHtml($url)
    {
        $html = \file_get_html($url);

        $icOrTd = $html->find('.ic-or-td', 0);

        $data = [];
        foreach ($icOrTd->find('.paragraph') as $paragraph){

            $name = trim($paragraph->find('.field--name-field-headline', 0)->innertext);
            $value = trim($paragraph->find('.field--name-field-value', 0)->innertext);

            $data[$name] = $value;
        }

        if($data['Make'] == 'Not available' || $data['Model'] == 'Not available'){
            $result = [
                'error' => true
            ];
        }
        else {
            $result = [
                'make_and_model' => "{$data['Make']} {$data['Model']}",
                'year_of_issue' => $data['Model Year'],
            ];
        }

        return $result;
    }
}