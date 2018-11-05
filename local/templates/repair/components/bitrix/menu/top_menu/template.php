<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <?
    foreach ($arResult as $arItem):
        if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
            continue;
        ?>
        <li class="nav-item <? if ($arItem["SELECTED"]): ?>active<? endif ?>">
            <a class="nav-link" href="<?= $arItem["LINK"] ?>">
                <?= $arItem["TEXT"] ?>
                <? if ($arItem["SELECTED"]): ?>
                    <span class="sr-only">(current)</span>
                <? endif; ?>
            </a>
        </li>

    <? endforeach ?>
<? endif ?>