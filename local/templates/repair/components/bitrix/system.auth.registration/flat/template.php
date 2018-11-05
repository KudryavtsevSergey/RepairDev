<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");

if ($arParams["~AUTH_RESULT"]["TYPE"] == "OK") {
    $_SESSION['USER_REGISTERED'] = 'Y';
}
?>
<div class="d-flex justify-content-center">
    <? if ($_SESSION['USER_REGISTERED'] == "Y") {
        $text = 'Регистрация прошла успешно.<br> Ваш аккаунт будет активирован в течении 48-ми часов.<br> После активации вам на почту придет уведомление.';
        $text = str_replace(array("<br>", "<br />"), "\n", $text);
        ?>

        <div class="alert alert-success">
            <?= nl2br(htmlspecialcharsbx($text)) ?>
        </div>

        <?
    } else {
        ?>
        <div style="width: 50%;">
            <h3 class="bx-title"><?= GetMessage("AUTH_REGISTER") ?></h3>

            <p><?= GetMessage('TOP_MESSAGE') ?></p>

            <? if (!empty($arParams["~AUTH_RESULT"])):
                $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
                ?>
                <div class="alert <?= ($arParams["~AUTH_RESULT"]["TYPE"] == "OK" ? "alert-success" : "alert-danger") ?>">
                    <?= nl2br(htmlspecialcharsbx($text)) ?>
                </div>
            <? endif ?>

            <? if ($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) && $arParams["AUTH_RESULT"]["TYPE"] === "OK"): ?>
                <div class="alert alert-success"><? echo GetMessage("AUTH_EMAIL_SENT") ?></div>
            <? else: ?>

            <? if ($arResult["USE_EMAIL_CONFIRMATION"] === "Y"): ?>
                <div class="alert alert-warning"><? echo GetMessage("AUTH_EMAIL_WILL_BE_SENT") ?></div>
            <? endif ?>

                <noindex>

                    <form method="post" action="<?= $arResult["AUTH_URL"] ?>" name="bform"
                          enctype="multipart/form-data" class="row">

                        <? if ($arResult["BACKURL"] <> ''): ?>
                            <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                        <? endif ?>

                        <input type="hidden" name="AUTH_FORM" value="Y"/>
                        <input type="hidden" name="TYPE" value="REGISTRATION"/>

                        <div class="form-group col-12">
                            <label for="email">
                                <span class="bx-authform-starrequired">*</span>
                                <?= GetMessage("AUTH_EMAIL") ?>
                            </label>
                            <input id="email" type="email" class="form-control" name="USER_EMAIL" maxlength="255"
                                   value="<?= $arResult["USER_EMAIL"] ?>">
                        </div>

                        <div class="form-group col-12">
                            <label for="pwd">
                                <span class="bx-authform-starrequired">*</span>
                                <?= GetMessage("AUTH_PASSWORD_REQ") ?>
                            </label>
                            <? if ($arResult["SECURE_AUTH"]): ?>
                                <div class="bx-authform-psw-protected" id="bx_auth_secure" style="display:none">
                                    <div class="bx-authform-psw-protected-desc">
                                        <span></span><? echo GetMessage("AUTH_SECURE_NOTE") ?></div>
                                </div>

                                <script type="text/javascript">
                                    document.getElementById('bx_auth_secure').style.display = '';
                                </script>
                            <? endif ?>

                            <input type="password" class="form-control" id="pwd" name="USER_PASSWORD" maxlength="255"
                                   value="<?= $arResult["USER_PASSWORD"] ?>" autocomplete="off">
                        </div>

                        <!--<div class="form-group">
                        <label for="pwd-confirm">
                            <span class="bx-authform-starrequired">*</span>
                            <?= GetMessage("AUTH_CONFIRM") ?>
                        </label>
                        <? if ($arResult["SECURE_AUTH"]): ?>
                            <div class="bx-authform-psw-protected" id="bx_auth_secure_conf" style="display:none">
                                <div class="bx-authform-psw-protected-desc">
                                    <span></span><? echo GetMessage("AUTH_SECURE_NOTE") ?></div>
                            </div>

                            <script type="text/javascript">
                                document.getElementById('bx_auth_secure_conf').style.display = '';
                            </script>
                        <? endif ?>

                        <input type="password" class="form-control" id="pwd-confirm" name="USER_CONFIRM_PASSWORD"
                               maxlength="255" value="<?= $arResult["USER_CONFIRM_PASSWORD"] ?>" autocomplete="off">
                    </div>-->

                        <div class="form-group col-12">
                            <label for="auth_name">
                                <?= GetMessage("NAME_AND_LAST_NAME") ?>
                            </label>
                            <input type="text" name="USER_NAME" maxlength="255" value="<?= $arResult["USER_NAME"] ?>"
                                   class="form-control" id="auth_name">
                        </div>

                        <!--<div class="form-group">
                            <label for="user_last_name">
                                <?= GetMessage("AUTH_LAST_NAME") ?>
                            </label>
                            <input type="text" name="USER_LAST_NAME" maxlength="255" value="<?= $arResult["USER_LAST_NAME"] ?>"
                                   class="form-control" id="user_last_name">
                        </div>-->

                        <div class="form-group col-8">
                            <label for="uf_user_citizenship">
                                <?= GetMessage('COUNTRY') ?>
                            </label>
                            <select class="form-control" id="uf_user_citizenship" name="UF_USER_CITIZENSHIP">
                                <option><?= GetMessage('UNKNOWN') ?></option>
                                <? foreach ($arResult["COUNTRIES"] as $country): ?>
                                    <option value="<?= $country['id']; ?>" <?= $country['selected']; ?>>
                                        <?= $country['name']; ?>
                                    </option>
                                <? endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-4">
                            <label>&nbsp;</label>
                            <button type="submit" class="form-control btn btn-primary" name="Register"
                                    value="<?= GetMessage("AUTH_REGISTER") ?>">
                                <?= GetMessage("AUTH_REGISTER") ?>
                            </button>
                        </div>
                        <hr class="bxe-light">

                        <div class="bx-authform-description-container col-12">
                            <? echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]; ?>
                        </div>

                        <div class="bx-authform-description-container col-12">
                            <span class="bx-authform-starrequired">*</span><?= GetMessage("AUTH_REQ") ?>
                        </div>

                        <div class="bx-authform-link-container col-12">
                            <a href="<?= $arResult["AUTH_AUTH_URL"] ?>"
                               rel="nofollow"><b><?= GetMessage("AUTH_AUTH") ?></b></a>
                        </div>
                    </form>
                </noindex>

                <script type="text/javascript">
                    document.bform.USER_NAME.focus();
                </script>

            <? endif ?>
        </div>
        <?
    }
    ?>
</div>
