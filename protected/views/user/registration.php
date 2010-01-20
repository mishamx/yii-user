<?php $this->pageTitle=Yii::app()->name . ' - '.Yii::t("user", "Registration"); ?>

<h1><?php echo Yii::t("user", "Registration"); ?></h1>

<?php if(Yii::app()->user->hasFlash('registration')): ?>
<div class="confirmation">
<?php echo Yii::app()->user->getFlash('registration'); ?>
</div>
<?php else: ?>

<div class="yiiForm">
<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($form); ?>

<div class="simple">
<?php echo CHtml::activeLabel($form,'username'); ?>
<?php echo CHtml::activeTextField($form,'username'); ?>
</div>

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

<div class="simple">
<?php echo CHtml::activeLabel($form,'email'); ?>
<?php echo CHtml::activeTextField($form,'email'); ?>
</div>

<?php if(extension_loaded('gd')): ?>
<div class="simple">
	<?php echo CHtml::activeLabel($form,'verifyCode'); ?>
	<div>
	<?php $this->widget('CCaptcha'); ?>
	<?php echo CHtml::activeTextField($form,'verifyCode'); ?>
	</div>
	<p class="hint"><?php echo Yii::t("user","Please enter the letters as they are shown in the image above."); ?>
	<br/><?php echo Yii::t("user","Letters are not case-sensitive."); ?></p>
</div>
<?php endif; ?>

<div class="action">
<?php #echo CHtml::activeCheckBox($form,'rememberMe'); ?>
<?php #echo CHtml::activeLabel($form,'rememberMe'); ?>
<br/>
<?php echo CHtml::submitButton(Yii::t("user", "Registration")); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->
<?php endif; ?>