<?php
/**
 * Created by PhpStorm.
 * User: Sun
 * Date: 25.10.2018
 * Time: 0:45
 */

namespace classes\stores;


use classes\adapters\Iblock;

class SparePart extends Iblock {

    /**
     * @var int
     */
    protected static $_store_id = \SPARE_PART_IBLOCK_ID;
}
