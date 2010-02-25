<?php
$this->breadcrumbs=array(
	UserModule::t('Profile Fields'),
);
?>

<h1><?php echo UserModule::t('List Profile Field'); ?></h1>

<?php echo $this->renderPartial('_menu'); ?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
