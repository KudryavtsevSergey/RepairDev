<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult['ERROR'])) {
    echo $arResult['ERROR'];
    return false;
}
?>

    <h3><?= GetMessage("TITLE") ?></h3>

    <div class="row">
        <? foreach ($arResult['rows'] as $row): ?>
            <? $url = str_replace(['#ID#', '#BLOCK_ID#'], [$row['ID'], intval($arParams['BLOCK_ID'])], $arParams['DETAIL_URL']); ?>

            <div class="col-md-8">
                <p>
                    <a href="<?= htmlspecialcharsbx($url) ?>">
                        <?= "{$row['UF_MAKE_AND_MODEL']} {$row['UF_YEAR_OF_ISSUE']} {$row['UF_VIN_NUMBER']}" ?>
                    </a>
                </p>
            </div>
            <div class="col-md-2">
                <p class="btn btn-primary btn-sm btn-block">
                    <?= $row['ID'] ?>
                </p>
            </div>
            <div class="col-md-2">
                <p>
                    <a class="btn btn-success btn-sm btn-block"
                       href="/doc_generate/create_xls.php?ORDER_ID=<?= $row['ID']; ?>" target="__blank">
                        <?= GetMessage("DOWNLOAD_XLS") ?>
                    </a>
                </p>
            </div>

        <? endforeach; ?>

    </div>

<? if ($arParams['ROWS_PER_PAGE'] > 0):
    $APPLICATION->IncludeComponent(
        'bitrix:main.pagenavigation',
        '',
        array(
            'NAV_OBJECT' => $arResult['nav_object'],
            'SEF_MODE' => 'N',
        ),
        false
    );
endif; ?>