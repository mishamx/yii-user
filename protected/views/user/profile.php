<h2><?php echo Yii::t("user", 'Your profile'); ?></h2>

<div class="actionBar">
[<?php echo CHtml::link(Yii::t("user", 'Profile'),array('profile')); ?>]
[<?php echo CHtml::link(Yii::t("user", 'Change password'),array('changepassword')); ?>]
[<?php echo CHtml::link(Yii::t("user", 'Logout'),array('logout')); ?>]
</div>
<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
<div class="confirmation">
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
    <td><?php echo date("d.m.Y",$model->createtime); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('lastvisit')); ?>
</th>
    <td><?php echo date("d.m.Y",$model->lastvisit); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($model->getAttributeLabel('status')); ?>
</th>
    <td><?php switch ($model->status) {
    			case 0:
    				echo Yii::t("user", "You account is not activated.");
    			break;
    			case 1:
    				echo Yii::t("user", "Ok");
    			break;
    			case -1:
    				echo Yii::t("user", "You account is blocked.");
    			break;
    	}
    ?>
</td>
</tr>
</table>
