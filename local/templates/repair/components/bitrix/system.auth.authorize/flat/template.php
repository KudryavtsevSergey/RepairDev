<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 */

//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
?>

<div class="d-flex justify-content-center">

    <div style="width: 50%;">
    <? if (!empty($arParams["~AUTH_RESULT"])):
        $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]); ?>

        <div class="alert alert-danger"><?= nl2br(htmlspecialcharsbx($text)) ?></div>
    <? endif ?>

    <? if ($arResult['ERROR_MESSAGE'] <> ''):
        $text = str_replace(array("<br>", "<br />"), "\n", $arResult['ERROR_MESSAGE']); ?>

        <div class="alert alert-danger"><?= nl2br(htmlspecialcharsbx($text)) ?></div>
    <? endif ?>

    <h3 class="bx-title"><?= GetMessage("AUTH_PLEASE_AUTH") ?></h3>

    <p><?= GetMessage('TOP_MESSAGE')?></p>

    <form name="form_auth" method="post" target="_top" action="<?= $arResult["AUTH_URL"] ?>">

        <input type="hidden" name="AUTH_FORM" value="Y"/>
        <input type="hidden" name="TYPE" value="AUTH"/>
        <? if (strlen($arResult["BACKURL"]) > 0): ?>
            <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
        <? endif ?>

        <? foreach ($arResult["POST"] as $key => $value): ?>
            <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
        <? endforeach ?>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="USER_LOGIN" maxlength="255"
                   value="<?= $arResult["LAST_LOGIN"] ?>">
        </div>

        <div class="form-group">
            <label for="pwd"><?= GetMessage("AUTH_PASSWORD") ?></label>
            <? if ($arResult["SECURE_AUTH"]): ?>
                <div class="bx-authform-psw-protected" id="bx_auth_secure" style="display:none">
                    <div class="bx-authform-psw-protected-desc">
                        <span></span>
                        <? echo GetMessage("AUTH_SECURE_NOTE") ?>
                    </div>
                </div>

                <script type="text/javascript">
                    document.getElementById('bx_auth_secure').style.display = '';
                </script>
            <? endif ?>

            <input type="password" class="form-control" id="pwd" name="USER_PASSWORD" maxlength="255"
                   autocomplete="off">
        </div>

        <? if ($arResult["STORE_PASSWORD"] == "Y"): ?>
            <div class="form-group form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y">
                    <?= GetMessage("AUTH_REMEMBER_ME") ?>
                </label>
            </div>
        <? endif ?>

        <a href="<?= $arResult["AUTH_REGISTER_URL"] ?>" class="btn btn-success" rel="nofollow">
            <b><?= GetMessage("AUTH_REGISTER") ?></b>
        </a>

        <button style="float: right;" type="submit" class="btn btn-primary" name="Login" value="<?= GetMessage("AUTH_AUTHORIZE") ?>">
            <?= GetMessage("AUTH_AUTHORIZE") ?>
        </button>

    </form>

    <? if ($arParams["NOT_SHOW_LINKS"] != "Y"): ?>
        <hr class="bxe-light">

        <noindex>
            <div class="bx-authform-link-container">
                <a href="<?= $arResult["AUTH_FORGOT_PASSWORD_URL"] ?>" rel="nofollow">
                    <b><?= GetMessage("AUTH_FORGOT_PASSWORD_2") ?></b>
                </a>
            </div>
        </noindex>
    <? endif ?>

    <?/* if ($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"): ?>
        <noindex>
            <div class="bx-authform-link-container">
                <?= GetMessage("AUTH_FIRST_ONE") ?><br/><br/>

            </div>
        </noindex>
    <? endif */?>
    </div>
</div>

<script type="text/javascript">
    <?if (strlen($arResult["LAST_LOGIN"]) > 0):?>
    try {
        document.form_auth.USER_PASSWORD.focus();
    } catch (e) {
    }
    <?else:?>
    try {
        document.form_auth.USER_LOGIN.focus();
    } catch (e) {
    }
    <?endif?>
</script>

