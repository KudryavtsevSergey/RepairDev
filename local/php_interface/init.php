<?php

require_once 'constants.php';
require_once 'functions.php';
require_once 'plugins/vendor/autoload.php';
require_once 'include/events/onBeforeUserRegister.php';

//CJSCore::Init();

$classes = [
    "\\travelsoft\\booking\\adapters\\Cache" => "include/classes/adapters/Cache.php",

    "\\classes\\adapters\\Highloadblock" => "include/classes/adapters/Highloadblock.php",
    "\\classes\\adapters\\Iblock" => "include/classes/adapters/Iblock.php",
    "\\classes\\stores\\CarParsingCard" => "include/classes/stores/CarParsingCard.php",
    "\\classes\\stores\\CarParsingCardSparePart" => "include/classes/stores/CarParsingCardSparePart.php",
    "\\classes\\stores\\SparePart" => "include/classes/stores/SparePart.php",
    "\\classes\\stores\\Template" => "include/classes/stores/Template.php",

    "\\classes\\service\\CarfaxService" => "include/classes/service/CarfaxService.php",
    "\\classes\\service\\CurlService" => "include/classes/service/CurlService.php",
];

foreach ($classes as $className => $classPath) {
    $autoLoadClasses[$className] = '/local/php_interface/' . $classPath;
}
if(!empty($autoLoadClasses)) {
    \Bitrix\Main\Loader::registerAutoLoadClasses(null, $autoLoadClasses);
}


switch (SITE_ID){
    case 's1':
        define('LANG_POSTFIX', '_RU');
        break;
    case 's2':
        define('LANG_POSTFIX', '_EN');
        break;
}













function getRandNum() {
    return rand(1000,9999). " MA-7" ;
}

function getMarkaModel() {
    $arr = [
        ["Mercedes-Benz", "C-Class"],
        ["Audi", "A-4"],
        ["Audi", "A-5"],
        ["Kia", "Rio"],
        ["Volkswagen", "Golf 5"],
        ["Volkswagen", "Golf 4"],
        ["Mercedes-Benz", "GLA"],
        ["Toyota", "Avensis"],
    ];
    return $arr[rand(0, count($arr) - 1)];
}

function getRandStatus() {
    $arr = [
        '<a href="#" class="btn btn-xs btn-primary">Свободна</a>',
        '<a href="#" class="btn btn-xs btn-danger">В прокате</a>',
        '<a href="#" class="btn btn-xs btn-white">Снята с линии</a>',
        '<a href="#" class="btn btn-xs btn-warning">Забронирована</a>',
    ];
    return $arr[rand(0, count($arr) - 1)];
}

function getRandFIO() {
    $arr = [
        'Корневкий Антон Олегович',
        'Храповицкий Сергей Викторович',
        'Иванов Виталий Игоревич',
        'Корнеев Илья Игоревич',
        'Лисовский Александр Петрович',
        'Петров Сергей Викторович',
        'Маркевич Павел Леонидович',
    ];
    return $arr[rand(0, count($arr) - 1)];
}


