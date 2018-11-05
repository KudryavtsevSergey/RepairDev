<?php

namespace travelsoft\booking\adapters;

/**
 * Адаптер для механизма кеширования
 *
 * @author dimabresky
 */
class Cache {

    /**
     * @var \Bitrix\Main\Data\Cache
     */
    protected $_cache = null;

    /**
     * @var string
     */
    protected $_id = null;

    /**
     * @var string
     */
    protected $_dir = null;

    /**
     * @var int
     */
    protected $_time = null;

    /**
     * @param string  $id
     * @param string  $dir
     * @param int $time в секундах
     */
    public function __construct(string $id, string $dir = "/carsharing", int $time = 3600) {
        $this->_cache = \Bitrix\Main\Data\Cache::createInstance();
        $this->_id = $id . serialize(User::groups());
        $this->_dir = $dir;
        $this->_time = $time;
    }

    /**
     * Получение из кеша
     * @return array
     */
    public function get() {
        $result = array();
        if ($this->_cache->initCache($this->_time, $this->_id, $this->_dir)) {
            $result = $this->_cache->getVars();
        }
        return $result;
    }

    /**
     * Кеширование информации
     * @param callable $callback
     * @return array
     */
    public function caching(callable $callback) {
        $result = array();
        if ($this->_cache->startDataCache()) {

            $result = (array) $callback();
            if (!empty($result)) {
                $this->_cache->endDataCache($result);
            } else {
                $this->_cache->abortDataCache();
            }
        }
        return $result;
    }

    /**
     * Устанавливает тегированный кеш
     * @global type $CACHE_MANAGER
     * @param string $tagName
     */
    public function setTagCache(string $tagName) {

        global $CACHE_MANAGER;
        $CACHE_MANAGER->StartTagCache($this->getCacheDir());
        $CACHE_MANAGER->RegisterTag($tagName);
        $CACHE_MANAGER->EndTagCache();
    }

    /**
     * Очистка тегированного кеша по названию тега
     * @global \travelsoft\booking\adapters\type $CACHE_MANAGER
     * @param string $tagName
     */
    public static function clearByTag(string $tagName) {
        global $CACHE_MANAGER;
        $CACHE_MANAGER->ClearByTag($tagName);
    }

    /**
     * @return string
     */
    public function getCacheDir() {
        return $this->_dir;
    }

}
