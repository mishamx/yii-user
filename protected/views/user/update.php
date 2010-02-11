<?php
$this->breadcrumbs=array(
	(Yii::t("user", 'Users'))=>array('index'),
	$model->username=>array('view','id'=>$model->id),
	(Yii::t("user", 'Update')),
);
?>

<h1><?php echo  Yii::t("user", 'Update User')." ".$model->id; ?></h1>

<ul class="actions">
	<li><?php echo CHtml::link(Yii::t("user", 'List User'),array('index')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Create User'),array('create')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'View User'),array('view','id'=>$model->id)); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Manage User'),array('admin')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Manage Profile Field'),array('profileField/admin')); ?></li>
</ul><!-- actions -->

<?php echo $this->renderPartial('_form', array('model'=>$model,'profile'=>$profile)); ?>