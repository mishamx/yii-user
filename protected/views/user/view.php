<?php
$this->breadcrumbs=array(
	Yii::t("user", 'Users')=>array('index'),
	$model->username,
);
?>
<h1><?php echo Yii::t("user", 'View User').' "'.$model->username.'"'; ?></h1>

<ul class="actions">
	<li><?php echo CHtml::link(Yii::t("user", 'List User'),array('index')); ?></li>
<?php if(Yii::app()->User->isAdmin()) { ?>
	<li><?php echo CHtml::link(Yii::t("user", 'Create User'),array('create')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Update User'),array('update','id'=>$model->id)); ?></li>
	<li><?php echo CHtml::linkButton(Yii::t("user", 'Delete User'),array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t("user", 'Are you sure to delete this item?'))); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Manage User'),array('admin')); ?></li>
	<li><?php echo CHtml::link(Yii::t("user", 'Manage Profile Field'),array('profileField/admin')); ?></li>
<?php } ?>
</ul><!-- actions -->

<?php 
if(Yii::app()->User->isAdmin()) {
	$attributes = array(
		'id',
		'username',
	);
	
	$profileFields=ProfileField::model()->forOwner()->sort()->findAll();
	if ($profileFields) {
		foreach($profileFields as $field) {
			array_push($attributes,array(
					'label' => Yii::t("user", $field->title),
					'name' => $field->varname,
					'value' => $model->profile->getAttribute($field->varname),
				));
		}
	}
	
	array_push($attributes,
		'password',
		'email',
		'activkey',
		array(
			'name' => 'createtime',
			'value' => date("d.m.Y H:i:s",$model->createtime),
		),
		array(
			'name' => 'lastvisit',
			'value' => date("d.m.Y H:i:s",$model->lastvisit),
		),
		array(
			'name' => 'superuser',
			'value' => User::itemAlias("AdminStatus",$model->superuser),
		),
		array(
			'name' => 'status',
			'value' => User::itemAlias("UserStatus",$model->status),
		)
	);
	
	$this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>$attributes,
	));
	
} else {
// For all users
	$attributes = array(
			'username',
	);
	
	$profileFields=ProfileField::model()->forAll()->sort()->findAll();
	if ($profileFields) {
		foreach($profileFields as $field) {
			array_push($attributes,array(
					'label' => Yii::t("user", $field->title),
					'name' => $field->varname,
					'value' => $model->profile->getAttribute($field->varname),
				));
		}
	}
	array_push($attributes,
		array(
			'name' => 'createtime',
			'value' => date("d.m.Y H:i:s",$model->createtime),
		),
		array(
			'name' => 'lastvisit',
			'value' => date("d.m.Y H:i:s",$model->lastvisit),
		)
	);
			
	$this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'attributes'=>$attributes,
	));
}
?>
