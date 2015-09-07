    <div class="form_row">
	<label for="name">Category Name*</label>
	<input type="text" name="name" value="<?php echo $strName; ?>" />
	<?php echo form_error('name'); ?>
    </div>
    
    
    <div class="form_row">
	<label for="name">Depreciation Rate (Annual %)</label>
	<input type="text" name="depreciation_rate" value="<?php echo $strDepreciationRate; ?>" />
	<?php echo form_error('depreciation_rate'); ?>
    </div>

    <div class="form_row">
        <label>Quantity Category
            <span class="form_help">If set to yes, allows items to be set in quantities. For example, 1 Chair item actually contains 10 individual chairs.</span>
        </label>
        <select name="quantity_enabled">
            <option value="0" <?=($intQuantityEnabled == 0 ? 'selected' : '')?>>No</option>
            <option value="1" <?=($intQuantityEnabled == 1 ? 'selected' : '')?>>Yes</option>
        </select>
    </div>

    <?php if($arrSessionData['objSystemUser']->levelid > 2) { ?>
        <div class="form_row">
            <label for="name">Category Support Contact
                <span class="form_help">If multiple email addresses are needed, please separate these by a comma.</span>
            </label>
            <input type="text" name="support_emails" value="<?php echo $strSupportEmails; ?>" style="width: 500px;"/>
            <?php echo form_error('support_emails'); ?>
        </div>
    <?php } ?>

    <p>Please select custom fields you wish to use for this category</p>
    <a id="select_all" href="#" style="padding: 0 0 5px 16px">Select All / Remove All</a>
    <?php if(count($arrCustomFields) > 0 && $arrSessionData['objSystemUser']->levelid > 2) { ?>
        <?php foreach($arrCustomFields as $customfield) { ?>

            <div class="form_row">

                <label for="customfields"><?=$customfield->field_name?></label>
                <? if($arrCategoryCustomFields) { ?>
                    <input type="checkbox" name="customfields[]" value="<?=$customfield->id?>" <?=(in_array($customfield->id, $arrCategoryCustomFields, true) ? 'checked' : '')?>>
                <? } else { ?>
                    <input type="checkbox" name="customfields[]" value="<?=$customfield->id?>">
                <?php } ?>

            </div>
        <?php } ?>
    <?php } ?>

    </form>
</div>
</div>
</div>