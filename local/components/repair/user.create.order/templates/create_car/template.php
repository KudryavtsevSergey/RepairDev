<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? global $USER; ?>
<form method="post">
    <?= bitrix_sessid_post() ?>

    <div class="d-flex justify-content-center">
        <div style="width: 50%; text-align: center;">
            <h3><?= GetMessage('CAR_PARSING_CARD') ?></h3>

            <p><?= GetMessage('CAR_PARSING_TEXT') ?></p>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6 col-sm-6">
            <label for="vin_number"><?= GetMessage('VIN_NUMBER') ?></label>
            <input id="vin_number" class="form-control" type="text" name="CAR_PARSING_CARD[UF_VIN_NUMBER]"
                   placeholder="<?= GetMessage('VIN_NUMBER') ?>">
        </div>

        <div class="form-group col-md-6 col-sm-6">
            <label for="lot_number"><?= GetMessage('LOT_NUMBER') ?></label>
            <input id="lot_number" class="form-control" type="text" name="CAR_PARSING_CARD[UF_LOT_NUMBER]"
                   placeholder="<?= GetMessage('LOT_NUMBER') ?>">
        </div>
        <div class="form-group col-md-6 col-sm-6">
            <label for="year_of_issue"><?= GetMessage('YEAR_OF_ISSUE') ?></label>
            <input id="year_of_issue" class="form-control" type="text" name="CAR_PARSING_CARD[UF_YEAR_OF_ISSUE]"
                   placeholder="<?= GetMessage('YEAR_OF_ISSUE') ?>">
        </div>

        <div class="form-group col-md-6 col-sm-6">
            <label for="make_and_model"><?= GetMessage('MAKE_AND_MODEL') ?></label>
            <input id="make_and_model" class="form-control" type="text" name="CAR_PARSING_CARD[UF_MAKE_AND_MODEL]"
                   placeholder="<?= GetMessage('MAKE_AND_MODEL') ?>">
        </div>
        <div class="form-group col-md-6 col-sm-6">
            <label for="colour"><?= GetMessage('COLOUR') ?></label>
            <input id="colour" class="form-control" type="text" name="CAR_PARSING_CARD[UF_COLOUR]"
                   placeholder="<?= GetMessage('COLOUR') ?>">
        </div>

    </div>

    <button type="submit" class="btn btn-primary">
        <?= GetMessage('SAVE') ?>
    </button>

</form>

<script>
    $('#vin_number').focusout(function(){

        $.ajax({
            method: 'post',
            url: "<?= $componentPath; ?>/ajax/get_auto_info.php",
            data: {vin_number:  this.value},
            success: function (data) {
                if (data !== '') {
                    let autoInfo = JSON.parse(data);

                    if (autoInfo.error !== true) {

                        $('#make_and_model').val(autoInfo.make_and_model);
                        $('#year_of_issue').val(autoInfo.year_of_issue);
                    }
                }
            }
        });
    });
</script>