<?php
/**
 * Created by PhpStorm.
 * User: Sun
 * Date: 25.10.2018
 * Time: 0:49
 */

namespace classes\stores;


use classes\adapters\Highloadblock;

class CarParsingCard extends Highloadblock
{
    /**
     * @var int
     */
    protected static $_store_id = \CAR_PARSING_CARD_HL_ID;

    /**
     * @return array
     */
    public static function getByUserId()
    {
        global $USER;

        $orders = self::get(['filter' => ['UF_USER_ID' => $USER->GetId()]]);

        return $orders;
    }
}