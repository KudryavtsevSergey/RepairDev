<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
$oAsset = Bitrix\Main\Page\Asset::getInstance();
CJSCore::Init(); ?>

<!DOCTYPE html>
<html class="no-js" lang="ru">
<head>
    <title><?= $APPLICATION->ShowTitle() ?></title>
    <? $APPLICATION->ShowHead() ?>
    <link rel="icon" href="<?= SITE_TEMPLATE_PATH ?>/favicon.ico"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

    <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/assets/css/bootstrap.min.css") ?>
    <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/assets/css/custom.css") ?>
    <? $oAsset->addCss(SITE_TEMPLATE_PATH . "/assets/css/flag-icon.min.css") ?>

    <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/assets/js/jquery-3.3.1.js") ?>
    <? $oAsset->addJs(SITE_TEMPLATE_PATH . "/assets/js/bootstrap.bundle.min.js") ?>

</head>
<body>
<? $APPLICATION->ShowPanel() ?>
<nav class="navbar navbar-expand-md navbar-dark bg-dark zero-top-bottom-padding">
    <a class="navbar-brand" href="<?= SITE_DIR ?>">
        <? //= GetMessage("REPAIR_HEADER") ?>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto">
            <? $APPLICATION->IncludeComponent("bitrix:menu", "top_menu", Array(
                "ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
                "CHILD_MENU_TYPE" => "left",    // Тип меню для остальных уровней
                "DELAY" => "N",    // Откладывать выполнение шаблона меню
                "MAX_LEVEL" => "1",    // Уровень вложенности меню
                "MENU_CACHE_GET_VARS" => array(    // Значимые переменные запроса
                    0 => "",
                ),
                "MENU_CACHE_TIME" => "3600",    // Время кеширования (сек.)
                "MENU_CACHE_TYPE" => "N",    // Тип кеширования
                "MENU_CACHE_USE_GROUPS" => "Y",    // Учитывать права доступа
                "ROOT_MENU_TYPE" => "top",    // Тип меню для первого уровня
                "USE_EXT" => "N",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
            ),
                false
            ); ?>
        </ul>
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">

            <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR . "include/locale.php"
            )); ?>

            <? global $USER;
            if ($USER->IsAuthorized()) {
                echo "<li class=\"nav-item active\"><a class=\"nav-link\">{$USER->GetLogin()}</a></li>";
                echo "<li class=\"nav-item\"><a class=\"nav-link\" href='?logout=yes'>" . GetMessage('LOG_OUT') . "</a></li>";
            }
            ?>
            <!--
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown01" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">Dropdown</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
            -->
        </ul>
    </div>
</nav>
<main role="main" class="container">
