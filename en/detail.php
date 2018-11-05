<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Заказ");
?><? $APPLICATION->IncludeComponent(
    "bitrix:highloadblock.view",
    "",
    Array(
        "BLOCK_ID" => $_REQUEST['BLOCK_ID'],
        "CHECK_PERMISSIONS" => "N",
        "LIST_URL" => "/",
        "ROW_ID" => $_REQUEST['ID'],
        "ROW_KEY" => "ID"
    )
); ?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>