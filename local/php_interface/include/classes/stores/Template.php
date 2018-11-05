<?php
/**
 * Created by PhpStorm.
 * User: Sun
 * Date: 25.10.2018
 * Time: 0:48
 */

namespace classes\stores;


use classes\adapters\Iblock;

class Template extends Iblock {

    /**
     * @var int
     */
    protected static $_store_id = \TEMPLATE_IBLOCK_ID;
}