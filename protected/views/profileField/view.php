<?php
$this->breadcrumbs=array(
	Yii::t("user", 'Profile Fields')=>array('admin'),
	Yii::t("user", $model->title),
);
?>
<h1><?php echo Yii::t("user", 'View Profile Field #').$model->varname; ?></h1>

<ul class="actions">
	<li><?php echo CHtml::link(Yii::t("user", 'Manage User'),array('user/admin')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Create Profile Field'),array('create')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Update Profile Field'),array('update','id'=>$model->id)); ?></li>
	<li><?php echo CHtml::linkButton(Yii::t("user", 'Delete Profile Field'),array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure to delete this item?')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Manage Profile Field'),array('admin')); ?></li>
</ul><!-- actions -->

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'varname',
		'title',
		'field_type',
		'field_size',
		'field_size_min',
		'required',
		'match',
		'range',
		'error_message',
		'other_validator',
		'default',
		'position',
		'visible',
	),
)); ?>
