<h2>User List</h2>

<div class="actionBar">
[<?php echo CHtml::link('New User',array('create')); ?>]
[<?php echo CHtml::link('Manage User',array('admin')); ?>]
</div>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<?php foreach($models as $n=>$model): ?>
<div class="item">
<?php echo CHtml::encode($model->getAttributeLabel('id')); ?>:
<?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('username')); ?>:
<?php echo CHtml::encode($model->username); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('password')); ?>:
<?php echo CHtml::encode($model->password); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('email')); ?>:
<?php echo CHtml::encode($model->email); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('activkey')); ?>:
<?php echo CHtml::encode($model->activkey); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('createtime')); ?>:
<?php echo CHtml::encode($model->createtime); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('lastvisit')); ?>:
<?php echo CHtml::encode($model->lastvisit); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('superuser')); ?>:
<?php echo CHtml::encode($model->superuser); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('status')); ?>:
<?php echo CHtml::encode($model->status); ?>
<br/>

</div>
<?php endforeach; ?>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>