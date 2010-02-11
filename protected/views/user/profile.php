<?php $this->pageTitle=Yii::app()->name . ' - '.Yii::t("user", "Profile");
$this->breadcrumbs=array(
	Yii::t("user", "Profile"),
);
?><h2><?php echo Yii::t("user", 'Your profile'); ?></h2>

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
<table class="dataGrid">
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('username')); ?>
</th>
    <td><?php echo CHtml::encode($model->username); ?>
</td>
</tr>
<?php 
		$profileFields=ProfileField::model()->forOwner()->sort()->findAll();
		if ($profileFields) {
			foreach($profileFields as $field) {
				//echo "<pre>"; print_r($profile); die();
			?>
<tr>
	<th class="label"><?php echo CHtml::encode(Yii::t("user", $field->title)); ?>
</th>
    <td><?php echo CHtml::encode($profile->getAttribute($field->varname)); ?>
</td>
</tr>
			<?php
			}
		}
?>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('password')); ?>
</th>
    <td><?php echo CHtml::link(Yii::t("user", "Change password"),array("changepassword")); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('email')); ?>
</th>
    <td><?php echo CHtml::encode($model->email); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('createtime')); ?>
</th>
    <td><?php echo date("d.m.Y H:i:s",$model->createtime); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('lastvisit')); ?>
</th>
    <td><?php echo date("d.m.Y H:i:s",$model->lastvisit); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('status')); ?>
</th>
    <td><?php echo CHtml::encode(User::itemAlias("UserStatus",$model->status));
    ?>
</td>
</tr>
</table>
