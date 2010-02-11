<?php
$this->breadcrumbs=array(
	Yii::t("user", 'Profile Fields'),
);
?>

<h1><?php echo Yii::t("user", 'List Profile Field'); ?></h1>

<ul class="actions">
	<li><?php echo CHtml::link(Yii::t("user", 'Create Profile Field'),array('create')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Manage Profile Field'),array('admin')); ?></li>
</ul><!-- actions -->

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
