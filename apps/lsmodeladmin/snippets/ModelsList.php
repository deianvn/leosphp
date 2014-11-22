<div class="ModelsPanel">

<?php

if (whas('Error')) {

?>

    <div class="ErrorPanel">
        <span class="ErrorLabel"><?php echo wget('Error'); ?>!</span>
    </div>

<?php

}

$modules = wget('Modules');

if (count($modules) > 0) {

?>

    <table class="ModelsTable">
        <tr>
            <td colspan="2" align="right">
                <form name="ModelsForm" method="post" action="lsmodeladmin">
                    <input type="submit" name="@Models_Generate" value="<?php $this->ll('Generate'); ?>" />
                    <input type="submit" name="@Models_CreateTables" value="<?php $this->ll('CreateTables'); ?>" />
                    <input type="submit" name="@Models_AddConstraints" value="<?php $this->ll('AddConstraints'); ?>" />
                    <input type="submit" name="@Models_CleanTables" value="<?php $this->ll('CleanTables'); ?>" />
                    <input type="submit" name="@Models_CleanFiles" value="<?php $this->ll('CleanFiles'); ?>" />
                </form>
            </td>
        </tr>
    </table>
    
<?php
    foreach ($modules as $module => $models) {

?>
    <form name="ModelsForm" method="post" action="lsmodeladmin">
        <input type="hidden" name="Param_Module" value="<?php echo $module; ?>" />
        <div class="TitlePanel">
            <span class="TitleLabel"><?php echo $module; ?></span><br />
            <input type="submit" name="@Models_Generate" value="<?php $this->ll('Generate'); ?>" />
            <input type="submit" name="@Models_CreateTables" value="<?php $this->ll('CreateTables'); ?>" />
            <input type="submit" name="@Models_AddConstraints" value="<?php $this->ll('AddConstraints'); ?>" />
            <input type="submit" name="@Models_CleanTables" value="<?php $this->ll('CleanTables'); ?>" />
            <input type="submit" name="@Models_CleanFiles" value="<?php $this->ll('CleanFiles'); ?>" />
        </div>
    </form>
<?php
        if (count($models) === 0) {
?>

    <div class="ErrorPanel">
        <span class="ErrorLabel"><?php $this->ll('NoModelsAvailable'); ?>!</span>
    </div>

<?php
        } else {
?>

    <table class="ModelsTable">

<?php
            foreach ($models as $model) {
?>

        <tr>
            <form name="ModelsForm" method="post" action="lsmodeladmin">
                <input type="hidden" name="Param_Module" value="<?php echo $module; ?>" />
                <input type="hidden" name="Param_Model" value="<?php echo $model; ?>" />
                <td class="ModelCell"><span class="InfoLabel"><?php echo $model; ?></span></td>
                <td class="ActionCell" align="right">
                    <input type="submit" name="@Models_Generate" value="<?php $this->ll('Generate'); ?>" />
                    <input type="submit" name="@Models_CreateTables" value="<?php $this->ll('CreateTables'); ?>" />
                    <input type="submit" name="@Models_AddConstraints" value="<?php $this->ll('AddConstraints'); ?>" />
                    <input type="submit" name="@Models_CleanTables" value="<?php $this->ll('CleanTables'); ?>" />
                    <input type="submit" name="@Models_CleanFiles" value="<?php $this->ll('CleanFiles'); ?>" />
                </td>
            </form>
        </tr>

<?php

            }
?>
    
    </table>
    
<?php
        }
    }
} else {

?>

    <div class="ErrorPanel">
        <span class="ErrorLabel"><?php $this->ll('NoModulesAvailable'); ?>!</span>
    </div>

<?php

}

?>

</div>