<h2>Managing User</h2>

<div class="actionBar">
[<?php echo CHtml::link('User List',array('list')); ?>]
[<?php echo CHtml::link('New User',array('create')); ?>]
</div>

<table class="dataGrid">
  <thead>
  <tr>
    <th><?php echo $sort->link('id'); ?></th>
    <th><?php echo $sort->link('username'); ?></th>
    <th><?php echo $sort->link('password'); ?></th>
    <th><?php echo $sort->link('email'); ?></th>
    <th><?php echo $sort->link('activkey'); ?></th>
    <th><?php echo $sort->link('createtime'); ?></th>
    <th><?php echo $sort->link('lastvisit'); ?></th>
    <th><?php echo $sort->link('superuser'); ?></th>
    <th><?php echo $sort->link('status'); ?></th>
	<th>Actions</th>
  </tr>
  </thead>
  <tbody>
<?php foreach($models as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?></td>
    <td><?php echo CHtml::encode($model->username); ?></td>
    <td><?php echo CHtml::encode($model->password); ?></td>
    <td><?php echo CHtml::encode($model->email); ?></td>
    <td><?php echo CHtml::encode($model->activkey); ?></td>
    <td><?php echo CHtml::encode($model->createtime); ?></td>
    <td><?php echo CHtml::encode($model->lastvisit); ?></td>
    <td><?php echo CHtml::encode($model->superuser); ?></td>
    <td><?php echo CHtml::encode($model->status); ?></td>
    <td>
      <?php echo CHtml::link('Update',array('update','id'=>$model->id)); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>'',
      	  'params'=>array('command'=>'delete','id'=>$model->id),
      	  'confirm'=>"Are you sure to delete #{$model->id}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>