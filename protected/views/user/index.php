<?php
$this->breadcrumbs=array(
	Yii::t("user", "Users"),
);
?>

<h1>List User</h1>
<?php if(Yii::app()->User->isAdmin()) {
	?><ul class="actions">
	<li><?php echo CHtml::link(Yii::t("user", 'Create User'),array('create')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Manage User'),array('admin')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Manage Profile Field'),array('profileField/admin')); ?></li>
</ul><!-- actions --><?php 
} ?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		array(
			'name' => 'username',
			'type'=>'raw',
			'value' => 'CHtml::link(CHtml::encode($data->username),array("user/view","id"=>$data->id))',
		),
		array(
			'name' => 'createtime',
			'value' => 'date("d.m.Y H:i:s",$data->createtime)',
		),
		array(
			'name' => 'lastvisit',
			'value' => 'date("d.m.Y H:i:s",$data->lastvisit)',
		),
	),
)); ?>
