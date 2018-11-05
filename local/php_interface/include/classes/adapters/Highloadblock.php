<?php

namespace classes\adapters;

use Bitrix\Highloadblock\HighloadBlockTable as HL;
use travelsoft\booking\adapters\Cache;

\Bitrix\Main\Loader::includeModule("highloadblock");

/**
 * Класс адаптер для bitrix highloadblock
 *
 * @author dimabresky
 */
abstract class Highloadblock{

    /**
     * @var int
     */
    protected static $_store_id = null;

    /**
     * Возвращает полученные данные из хранилища в виде массива
     * @param array $query
     * @param bool $likeArray
     * @param callable $callback
     * @return array
     */
    public static function get(array $query = array(), bool $likeArray = true, callable $callback = null) {

        $store = self::getStore();
        $dbList = $store::getList((array) $query);
        
        if (!$likeArray) {
            
            return $dbList;
        }
        
        $result = array();
        if ($callback) {
            while ($res = $dbList->fetch()) {
                $callback($res);
                $result[$res["ID"]] = $res;
            }
        } else {
            while ($res = $dbList->fetch()) {
                $result[$res["ID"]] = $res;
            }
        }

        return (array) $result;
    }

    /**
     * Обновление записи по id
     * @param int $id
     * @param array $arUpdate
     * @return boolean
     */
    public static function update(int $id, array $arUpdate): bool {

        $store = self::getStore();
        $result = boolval($store::update($id, $arUpdate));
        if ($result) {
            self::clearCache();
        }
        return $result;
    }

    /**
     * Добавляет запись в хранилище
     * @param array $arSave
     * @return int
     */
    public static function add(array $arSave): int {

        $store = self::getStore();
        
        $result = (int) $store::add($arSave)->getId();
        if ($result > 0) {
            self::clearCache();
        }
        return $result;
    }

    /**
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool {

        $store = self::getStore();
        $result = boolval($store::delete($id));
        if ($result) {
            self::clearCache();
        }
        return $result;
    }

    /**
     * Возвращает поля записи таблицы по id
     * @param int $id
     * @param array $select
     * @return array
     */
    public static function getById(int $id, array $select = array()): array {

        $class = \get_called_class();
        $query = array("filter" => array("ID" => $id));
        if (!empty($select)) {
            $query["select"] = $select;
        }
        $result = current($class::get($query));
        if (is_array($result) && !empty($result)) {

            return $result;
        } else {

            return array();
        }
    }

    /**
     * @return string
     */
    protected static function getStore(): string {

        return HL::compileEntity(HL::getById(self::getStoreId())->fetch())->getDataClass();
    }
    
    /**
     * @return int
     */
    protected static function getStoreId () : int {
        $class = \get_called_class();
        return $class::$_store_id;
    }
    
    protected static function clearCache () {
        Cache::clearByTag("highloadblock_" . self::getStoreId());
    }
    
}
