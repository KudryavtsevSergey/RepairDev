<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!empty($arResult['ERROR'])) {
    echo $arResult['ERROR'];
    return false;
}
global $USER;
?>

    <h3 class="mar-30"><?= GetMessage("TITLE") ?></h3>

    <table class="table table-borderless">
        <? /*table-hover
        <thead>
        <tr>
            <th><?= GetMessage("NAME") ?></th>
            <?if($USER->isAdmin()):?>
                <th><?= GetMessage("USER_DATA") ?></th>
            <?endif;?>
            <th><?= GetMessage("ID") ?></th>
            <th><?= GetMessage("DOWNLOAD") ?></th>
        </tr>
        </thead>
        */ ?>
        <tbody>
        <? foreach ($arResult['rows'] as $row): ?>
            <? $url = str_replace(['#ID#', '#BLOCK_ID#'], [$row['ID'], intval($arParams['BLOCK_ID'])], $arParams['DETAIL_URL']); ?>

            <tr>
                <td>
                    <? /*
                        <a href="<?= htmlspecialcharsbx($url);?>">
                            <?= "{$row['UF_MAKE_AND_MODEL']} {$row['UF_YEAR_OF_ISSUE']} {$row['UF_VIN_NUMBER']}" ?>
                        </a>*/ ?>

                    <p>
                        <?= "{$row['UF_MAKE_AND_MODEL']} {$row['UF_YEAR_OF_ISSUE']} {$row['UF_VIN_NUMBER']}" ?>
                    </p>
                </td>
                <td id="td-data-<?= $row['ID']; ?>">
                    <? /*if($USER->isAdmin()):?>
                            <a class="btn btn-primary btn-sm js-click-user" href="<?= $templateFolder;?>/ajax/get_user_data.php" data-user_id="<?= $row['UF_USER_ID'];?>">
                                <?= GetMessage('GET_USER_DATA')?>
                            </a>
                    <?endif;*/ ?>
                </td>
                <td>
                    <? if ($USER->isAdmin()): ?>
                        <a class="btn btn-primary btn-sm btn-block js-click-user"
                           href="<?= $templateFolder; ?>/ajax/get_user_data.php"
                           data-user_id="<?= $row['UF_USER_ID']; ?>"
                           data-container-id="td-data-<?= $row['ID']; ?>">
                            <?= $row['ID'] ?>
                        </a>
                    <? else: ?>
                        <p class="btn btn-primary btn-sm btn-block">
                            <?= $row['ID'] ?>
                        </p>
                    <? endif; ?>
                </td>
                <td>
                    <a class="btn btn-success btn-sm btn-block"
                       href="/doc_generate/create_xls.php?ORDER_ID=<?= $row['ID']; ?>&site_id=<?= SITE_ID; ?>" target="__blank">
                        <?= GetMessage("DOWNLOAD_XLS") ?>
                    </a>
                </td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>

    <script>
        $('.js-click-user').click(function (e) {
            e.preventDefault();

            let _this = this;
            let lang_id = '<?= LANGUAGE_ID; ?>';

            $.ajax({
                method: 'post',
                url: _this.href,
                data: {user_id: _this.dataset.user_id, lang_id: lang_id },
                success: function (data) {
                    if (data !== '') {
                        let user = JSON.parse(data);
                        $('#' + _this.dataset.containerId).html(getUserData(user));
                    }
                }
            });
        });

        function getUserData(user) {
            return `<div class="bordered-user-data">E-mail: ${user.EMAIL}<br/>
            <?= GetMessage("USER_NAME") ?>: ${user.NAME}<br/>
            <?= GetMessage('PERSONAL_COUNTRY');?>: ${user.PERSONAL_COUNTRY}</div>`;
        }
    </script>

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