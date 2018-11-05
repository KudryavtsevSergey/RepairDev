<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Список заказов");
?>
    <p style="float: right;">
        If you make a mistake when filling out the parsing card or you have questions, call us at <a href="tel:+1555288233"> +1 555 288233 </a>
    </p>
    <div style="clear: both;"></div>

    <a href="<?= SITE_DIR ?>create.php" class="btn btn-primary btn-sm">
        Create a new card
    </a>
<?
global $USER;
if($USER->IsAdmin() || in_array(OPERATOR_GROUP, $USER->GetUserGroupArray())){
    $arrFilterOrders = [];
}
else {
    $arrFilterOrders = ['UF_USER_ID' => $USER->GetId()];
}
$GLOBALS['arrFilterOrders'] = $arrFilterOrders;
?>
<? $APPLICATION->IncludeComponent("bitrix:highloadblock.list", "table", Array(
    "BLOCK_ID" => "2",    // ID инфоблока
    "CHECK_PERMISSIONS" => "N",    // Проверять права доступа
    "DETAIL_URL" => "/detail.php?ID=#ID#&BLOCK_ID=#BLOCK_ID#",    // Путь к странице просмотра записи
    "FILTER_NAME" => "arrFilterOrders",    // Идентификатор фильтра
    "PAGEN_ID" => "page",    // Идентификатор страницы
    "ROWS_PER_PAGE" => "15",    // Разбить по страницам количеством
    "SORT_FIELD" => "ID",    // Поле сортировки
    "SORT_ORDER" => "DESC",    // Направление сортировки
    "COMPONENT_TEMPLATE" => ".default"
),
    false
); ?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>