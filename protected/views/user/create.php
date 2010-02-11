<?php
$this->breadcrumbs=array(
	Yii::t("user", 'Users')=>array('index'),
	Yii::t("user", 'Create'),
);
?>
<h1><?php echo Yii::t("user", "Create User"); ?></h1>

<ul class="actions">
	<li><?php echo CHtml::link(Yii::t("user", 'List User'),array('index')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Manage User'),array('admin')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Manage Profile Field'),array('profileField/admin')); ?></li>
</ul><!-- actions -->

<?php echo $this->renderPartial('_form', array('model'=>$model,'profile'=>$profile)); ?>