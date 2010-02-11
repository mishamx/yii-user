<?php $this->pageTitle=Yii::app()->name . ' - '.Yii::t("user", "Registration");
$this->breadcrumbs=array(
	Yii::t("user", "Registration"),
);
?>

<h1><?php echo Yii::t("user", "Registration"); ?></h1>

<?php if(Yii::app()->user->hasFlash('registration')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('registration'); ?>
</div>
<?php else: ?>

<div class="form">
<?php echo CHtml::beginForm(); ?>

	<p class="note"><?php echo Yii::t("user", 'Fields with <span class="required">*</span> are required.'); ?></p>
	
	<?php echo CHtml::errorSummary($form); ?>
	<?php echo CHtml::errorSummary($profile); ?>
	
	<div class="row">
	<?php echo CHtml::activeLabelEx($form,'username'); ?>
	<?php echo CHtml::activeTextField($form,'username'); ?>
	</div>
	
	<div class="row">
	<?php echo CHtml::activeLabelEx($form,'password'); ?>
	<?php echo CHtml::activePasswordField($form,'password'); ?>
	<p class="hint">
	<?php echo Yii::t("user", "Minimal password length 4 symbols."); ?>
	</p>
	</div>
	
	<div class="row">
	<?php echo CHtml::activeLabelEx($form,'verifyPassword'); ?>
	<?php echo CHtml::activePasswordField($form,'verifyPassword'); ?>
	</div>
	
	<div class="row">
	<?php echo CHtml::activeLabelEx($form,'email'); ?>
	<?php echo CHtml::activeTextField($form,'email'); ?>
	</div>
	
<?php 
		$profileFields=ProfileField::model()->forRegistration()->sort()->findAll();
		if ($profileFields) {
			foreach($profileFields as $field) {
			?>
	<div class="row">
		<?php echo CHtml::activeLabelEx($profile,$field->varname); ?>
		<?php 
		if ($field->field_type=="TEXT") {
			echo CHtml::activeTextArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
		} else {
			echo CHtml::activeTextField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
		}
		 ?>
		<?php echo CHtml::error($profile,$field->varname); ?>
	</div>	
			<?php
			}
		}
?>
	<?php if(extension_loaded('gd')): ?>
	<div class="row">
		<?php echo CHtml::activeLabelEx($form,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo CHtml::activeTextField($form,'verifyCode'); ?>
		</div>
		<p class="hint"><?php echo Yii::t("user","Please enter the letters as they are shown in the image above."); ?>
		<br/><?php echo Yii::t("user","Letters are not case-sensitive."); ?></p>
	</div>
	<?php endif; ?>
	
	<div class="row submit">
		<?php echo CHtml::submitButton(Yii::t("user", "Registration")); ?>
	</div>

<?php echo CHtml::endForm(); ?>
</div><!-- form -->
<?php endif; ?>