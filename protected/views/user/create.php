<h2>New User</h2>

<div class="actionBar">
[<?php echo CHtml::link('User List',array('list')); ?>]
[<?php echo CHtml::link('Manage User',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>false,
)); ?>