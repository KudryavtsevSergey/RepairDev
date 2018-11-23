<?

AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserRegisterHandler");
AddEventHandler("main", "OnBeforeUserUpdate", "OnBeforeUserUpdateHandler");
AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");

// описываем саму функцию
function OnBeforeUserRegisterHandler(&$arFields)
{
    $arFields['LOGIN'] = $arFields['EMAIL'];
    $arFields['CONFIRM_PASSWORD'] = $arFields['PASSWORD'];
    $arFields['ACTIVE'] = 'N';

    //$GLOBALS['APPLICATION']->ThrowException("<pre>" . print_r($arFields, true) . "</pre>");

    return true;
}

function OnAfterUserRegisterHandler($arFields)
{
    $emailFields = [
        'USER_EMAIL' => $arFields['EMAIL'],
        'USER_LINK' => "{$_SERVER['HTTP_HOST']}/bitrix/admin/user_edit.php?lang=" . LANGUAGE_ID . "&ID={$arFields['USER_ID']}",
    ];

    createEmailEvent('USER_VERIFICATION', $emailFields);

    $arUserEmailFields = [
        'USER_EMAIL' => $arFields['EMAIL']
    ];

    createEmailEvent('USER_NOTIFY', $arUserEmailFields);

    $_SESSION['USER_REGISTERED'] = 'Y';
}

function OnBeforeUserUpdateHandler($arFields)
{
    $user = CUser::GetByID($arFields['ID'])->Fetch();

    if ($user['ACTIVE'] == 'N' && $arFields['ACTIVE'] == 'Y') {

        $emailFields = [
            'USER_EMAIL' => $arFields['EMAIL']
        ];

        createEmailEvent('USER_CONFIRMATION', $emailFields, $user['LID']);
    }

    return true;
}

function getMessageId($event, $siteId)
{
    $messageId = false;

    switch ($siteId) {
        case 's1':
            switch ($event){
                case 'USER_VERIFICATION':
                    $messageId = 30;
                    break;
                case 'USER_NOTIFY':
                    $messageId = 32;
                    break;
                case 'USER_CONFIRMATION':
                    $messageId = 31;
                    break;
            }
            break;
        case 's2':
            switch ($event){
                case 'USER_VERIFICATION':
                    $messageId = 33;
                    break;
                case 'USER_NOTIFY':
                    $messageId = 35;
                    break;
                case 'USER_CONFIRMATION':
                    $messageId = 34;
                    break;
            }
            break;
    }
    return $messageId;
}

function createEmailEvent($event, $arFields, $siteId = false)
{
    $siteId = $siteId ?: SITE_ID;
    $messageId = getMessageId($event, $siteId);
    if (!empty($messageId)) {
        CEvent::Send($event, $siteId, $arFields, "Y", $messageId);
    }
}