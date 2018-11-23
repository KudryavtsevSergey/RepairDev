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
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="d-flex justify-content-center">

    <div style="width: 50%;">

        <? if ($_SESSION['USER_REGISTERED'] == "Y"):
            $text = 'Регистрация прошла успешно.<br> Ваш аккаунт будет активирован в течении 48-ми часов.<br> После активации вам на почту придет уведомление.';
            $text = str_replace(array("<br>", "<br />"), "\n", $text);
            ?>

            <div class="alert alert-success">
                <?= nl2br(htmlspecialcharsbx($text)) ?>
            </div>

        <? else: ?>

        <h3 class="bx-title"><?= GetMessage("AUTH_REGISTER") ?></h3>

        <p><?= GetMessage('TOP_MESSAGE') ?></p>

        <? if (count($arResult["ERRORS"]) > 0) {
            foreach ($arResult["ERRORS"] as $key => $error)
                if (intval($key) == 0 && $key !== 0)
                    $arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;" . GetMessage("REGISTER_FIELD_" . $key) . "&quot;", $error);

            ShowError(implode("<br />", $arResult["ERRORS"]));
        } elseif ($arResult["USE_EMAIL_CONFIRMATION"] === "Y") {
            echo "<p>" . GetMessage("REGISTER_EMAIL_WILL_BE_SENT") . "</p>";
        } ?>

        <form id="regform" method="post" action="<?= POST_FORM_ACTION_URI ?>" name="regform"
              enctype="multipart/form-data" class="row">

            <input type="hidden" name="REGISTER[CONFIRM_PASSWORD]" value="not_empty_data"/>
            <input type="hidden" name="REGISTER[LOGIN]" value="not_empty_data"/>

            <div class="form-group col-12">
                <label for="email">
                    <span class="bx-authform-starrequired">*</span>
                    <?= GetMessage("AUTH_EMAIL") ?>
                </label>
                <input id="email" type="email" class="form-control" name="REGISTER[EMAIL]" value="<?= $arResult["VALUES"]["EMAIL"] ?>">
            </div>

            <div class="form-group col-12">
                <label for="pwd">
                    <span class="bx-authform-starrequired">*</span>
                    <?= GetMessage("AUTH_PASSWORD_REQ") ?>
                </label>
                <input type="password" class="form-control" id="pwd"  name="REGISTER[PASSWORD]" value="<?= $arResult["VALUES"]["PASSWORD"] ?>" autocomplete="off">
            </div>

            <div class="form-group col-12">
                <label for="auth_name">
                    <?= GetMessage("NAME_AND_LAST_NAME") ?>
                </label>
                <input type="text" name="REGISTER[NAME]" value="<?= $arResult["VALUES"]["NAME"] ?>"
                       class="form-control" id="auth_name">
            </div>

            <div class="form-group col-8">
                <label for="uf_user_citizenship">
                    <?= GetMessage('COUNTRY') ?>
                </label>
                <select class="form-control" id="uf_user_citizenship" name="REGISTER[PERSONAL_COUNTRY]">
                    <? foreach ($arResult["COUNTRIES"]["reference_id"] as $key => $value): ?>
                        <option value="<?= $value ?>"
                            <? if ($value == $arResult["VALUES"]["PERSONAL_COUNTRY"]): ?> selected="selected"<? endif ?>>
                            <?= $arResult["COUNTRIES"]["reference"][$key] ?>
                        </option>
                    <? endforeach; ?>
                </select>
            </div>

            <div class="form-group col-4">
                <label>&nbsp;</label>
                <input type="submit" class="form-control btn btn-primary" name="register_submit_button" value="<?= GetMessage("AUTH_REGISTER") ?>"/>
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

        <?endif;?>
    </div>
</div>