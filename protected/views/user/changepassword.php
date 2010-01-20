<?php $this->pageTitle=Yii::app()->name . ' - '.Yii::t("user", "Change Password"); ?>

<h1><?php echo Yii::t("user", "Change Password"); ?></h1>


<div class="yiiForm">
<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($form); ?>

<div class="simple">
<?php echo CHtml::activeLabel($form,'password'); ?>
<?php echo CHtml::activePasswordField($form,'password'); ?>
<p class="hint">
<?php echo Yii::t("user", "Minimal password length 4 symbols."); ?>
</p>
</div>

<div class="simple">
<?php echo CHtml::activeLabel($form,'verifyPassword'); ?>
<?php echo CHtml::activePasswordField($form,'verifyPassword'); ?>
</div>


<div class="action">
<br/>
<?php echo CHtml::submitButton(Yii::t("user", "Save")); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->