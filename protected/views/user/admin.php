<?php
$this->breadcrumbs=array(
	Yii::t("user", 'Users')=>array('index'),
	Yii::t("user", 'Manage'),
);
?>
<h1>Manage Users</h1>

<ul class="actions">
	<li><?php echo CHtml::link(Yii::t("user", 'List User'),array('index')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Create User'),array('create')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Manage Profile Field'),array('profileField/admin')); ?></li>
</ul><!-- actions -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider,
	'columns'=>array(
		array(
			'name' => 'id',
			'type'=>'raw',
			'value' => 'CHtml::link(CHtml::encode($data->id),array("user/update","id"=>$data->id))',
		),
		array(
			'name' => 'username',
			'type'=>'raw',
			'value' => 'CHtml::link(CHtml::encode($data->username),array("user/view","id"=>$data->id))',
		),
		array(
			'name'=>'email',
			'type'=>'raw',
			'value'=>'CHtml::link(CHtml::encode($data->email), "mailto:".$data->email)',
		),
		array(
			'name' => 'createtime',
			'value' => 'date("d.m.Y H:i:s",$data->createtime)',
		),
		array(
			'name' => 'lastvisit',
			'value' => 'date("d.m.Y H:i:s",$data->lastvisit)',
		),
		array(
			'name'=>'status',
			'value'=>'User::itemAlias("UserStatus",$data->status)',
		),
		array(
			'name'=>'superuser',
			'value'=>'User::itemAlias("AdminStatus",$data->superuser)',
		),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
