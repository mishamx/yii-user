<h2>Update User <?php echo $model->id; ?></h2>

<div class="actionBar">
[<?php echo CHtml::link('User List',array('list')); ?>]
[<?php echo CHtml::link('New User',array('create')); ?>]
[<?php echo CHtml::link('Manage User',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
	'update'=>true,
)); ?>