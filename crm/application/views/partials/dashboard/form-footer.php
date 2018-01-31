<div class="box-footer">
    <div class="pull-right"><span class="asterisk"><strong>*</strong></span> required fields</div>
    <input type="submit" class="btn btn-success" id="submitButton" value="<?= isset($submit_text) ? $submit_text : 'Submit' ?>">&nbsp;
    <input type="reset" name="reset" value="Reset" class="btn btn-default">
    &nbsp; or <?= anchor($url, 'cancel') ?>
</div>