<?php $this->pageTitle=Yii::app()->name . ' - '.Yii::t("user", "Profile");
$this->breadcrumbs=array(
	Yii::t("user", "Profile")=>array('profile'),
	Yii::t("user", "Edit"),
);
?><h2><?php echo Yii::t("user", 'Edit profile'); ?></h2>

<ul class="actions">
<?php 
if(Yii::app()->User->isAdmin()) {
?>
<li><?php echo CHtml::link(Yii::t("user", 'Manage User'),array('admin')); ?></li>
<?php 
} else {
?>
<li><?php echo CHtml::link(Yii::t("user", 'List User'),array('index')); ?></li>
<?php
}
?>
<li><?php echo CHtml::link(Yii::t("user", 'Profile'),array('profile')); ?></li>
<li><?php echo CHtml::link(Yii::t("user", 'Edit'),array('edit')); ?></li>
<li><?php echo CHtml::link(Yii::t("user", 'Change password'),array('changepassword')); ?></li>
<li><?php echo CHtml::link(Yii::t("user", 'Logout'),array('logout')); ?></li>
</ul>
<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('profileMessage'); ?>
</div>
<?php endif; ?>
<div class="form">

<?php echo CHtml::beginForm(); ?>

	<p class="note"><?php echo Yii::t("user", 'Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo CHtml::errorSummary($model);
		  echo CHtml::errorSummary($profile); ?>

<?php 
		$profileFields=ProfileField::model()->forOwner()->sort()->findAll();
		if ($profileFields) {
			foreach($profileFields as $field) {
			?>
	<div class="row">
		<?php echo CHtml::activeLabelEx($profile,$field->varname);
		if ($field->field_type=="TEXT") {
			echo CHtml::activeTextArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
		} else {
			echo CHtml::activeTextField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
		}
		echo CHtml::error($profile,$field->varname); ?>
	</div>	
			<?php
			}
		}
?>
	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'username'); ?>
		<?php echo CHtml::activeTextField($model,'username',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo CHtml::error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'email'); ?>
		<?php echo CHtml::activeTextField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo CHtml::error($model,'email'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t("user", 'Create') : Yii::t("user", 'Save')); ?>
	</div>

<?php echo CHtml::endForm(); ?>

</div><!-- form -->
