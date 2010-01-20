<div class="yiiForm">

<p>
Fields with <span class="required">*</span> are required.
</p>

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($model); ?>

<div class="simple">
<?php echo CHtml::activeLabelEx($model,'username'); ?>
<?php echo CHtml::activeTextField($model,'username',array('size'=>60,'maxlength'=>128)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'password'); ?>
<?php echo CHtml::activePasswordField($model,'password',array('size'=>60,'maxlength'=>128)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'email'); ?>
<?php echo CHtml::activeTextField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'activkey'); ?>
<?php echo CHtml::activeTextField($model,'activkey',array('size'=>60,'maxlength'=>128)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'createtime'); ?>
<?php echo CHtml::activeTextField($model,'createtime'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'lastvisit'); ?>
<?php echo CHtml::activeTextField($model,'lastvisit'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'superuser'); ?>
<?php echo CHtml::activeTextField($model,'superuser'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($model,'status'); ?>
<?php echo CHtml::activeTextField($model,'status'); ?>
</div>

<div class="action">
<?php echo CHtml::submitButton($update ? 'Save' : 'Create'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->