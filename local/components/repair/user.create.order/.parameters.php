<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

/** @var array $arCurrentValues */

$arTemplates = \classes\stores\Template::get();
$templates = [];
foreach ($arTemplates as $template){
    $templates[$template['ID']] = $template['NAME'];
}


$arComponentParameters['PARAMETERS']['TEMPLATE'] = array(
    "PARENT" => "BASE",
    "NAME" => "Шаблон деталей",
    "TYPE" => "LIST",
    "VALUES" => $templates,
    "REFRESH" => "Y",
);
