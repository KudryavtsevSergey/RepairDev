<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetTitle("Создание заявки");
?><?$APPLICATION->IncludeComponent(
    "repair:user.create.order",
    "",
    Array(
        "TEMPLATE" => "6",
        "LOCAL_REDIRECT" => "/"
    )
);?><?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php');
?>