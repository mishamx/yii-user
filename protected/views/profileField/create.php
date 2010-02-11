<?php
$this->breadcrumbs=array(
	Yii::t("user", 'Profile Fields')=>array('admin'),
	Yii::t("user", 'Create'),
);
?>
<h1><?php echo Yii::t("user", 'Create Profile Field'); ?></h1>

<ul class="actions">
	<li><?php echo CHtml::link(Yii::t("user", 'Manage User'),array('user/admin')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Manage Profile Field'),array('admin')); ?></li>
</ul><!-- actions -->

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>