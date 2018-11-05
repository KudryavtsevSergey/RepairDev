<?php
define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);
define("PUBLIC_AJAX_MODE", true);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

try {

    function attemptSendErrors(array $errors)
    {
        if (!empty($errors)) {
            throw new \Exception(implode('<br>', $errors));
        }
    }

    $errors = [];

    $userId = $_POST['user_id'] ?? '';
    $langId  = $_POST['lang_id '] ?? '';

    if (empty($userId)) {
        $errors[] = 'Не указан id пользователя.';
    }
    if (empty($langId)) {
        $errors[] = 'Не указан id языка.';
    }

    $user = CUser::GetByID($userId)->Fetch();

    $personalCountry = '';

    if(!empty($user['PERSONAL_COUNTRY'])){
        $personalCountry = GetCountryByID($user['PERSONAL_COUNTRY'], $langId);
    }

    $response = [
        'PERSONAL_COUNTRY' => $personalCountry,
        'ID' => $user['ID'],
        'EMAIL' => $user['EMAIL'],
        'NAME' => $user['NAME'],
    ];

    echo json_encode($response);

} catch (Exception $ex) {

    echo json_encode($ex->getMessage());
}