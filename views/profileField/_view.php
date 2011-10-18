<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('varname')); ?>:</b>
	<?php echo CHtml::encode($data->varname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('field_type')); ?>:</b>
	<?php echo CHtml::encode($data->field_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('field_size')); ?>:</b>
	<?php echo CHtml::encode($data->field_size); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('field_size_min')); ?>:</b>
	<?php echo CHtml::encode($data->field_size_min); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('required')); ?>:</b>
	<?php echo CHtml::encode($data->required); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('match')); ?>:</b>
	<?php echo CHtml::encode($data->match); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('range')); ?>:</b>
	<?php echo CHtml::encode($data->range); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('error_message')); ?>:</b>
	<?php echo CHtml::encode($data->error_message); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('other_validator')); ?>:</b>
	<?php echo CHtml::encode($data->other_validator); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('default')); ?>:</b>
	<?php echo CHtml::encode($data->default); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('position')); ?>:</b>
	<?php echo CHtml::encode($data->position); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('visible')); ?>:</b>
	<?php echo CHtml::encode($data->visible); ?>
	<br />

	*/ ?>

</div>