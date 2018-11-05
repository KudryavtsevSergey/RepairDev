<?php
define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);
define("PUBLIC_AJAX_MODE", true);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
include_once($_SERVER["DOCUMENT_ROOT"] . '/local/php_interface/plugins/vendor/simple-html-dom/simple-html-dom/simple_html_dom.php');

try {

    function attemptSendErrors(array $errors)
    {
        if (!empty($errors)) {
            throw new \Exception(implode('<br>', $errors));
        }
    }

    $errors = [];

    $vinNumber = $_POST['vin_number'] ?? '';

    if (empty($vinNumber)) {
        $errors[] = 'Не указан vin номер.';
    }

    $carfaxService = new \classes\service\CarfaxService();

    $response = $carfaxService->getCurData($vinNumber);

    echo json_encode($response);

} catch (Exception $ex) {

    echo json_encode($ex->getMessage());
}


