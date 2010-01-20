<?php $this->pageTitle=Yii::app()->name . ' - '.Yii::t("user", "Login"); ?>

<h1><?php echo Yii::t("user", "Restore"); ?></h1>

<?php if(Yii::app()->user->hasFlash('recoveryMessage')): ?>
<div class="confirmation">
<?php echo Yii::app()->user->getFlash('recoveryMessage'); ?>
</div>
<?php else: ?>

<div class="yiiForm">
<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($form); ?>

<div class="simple">
<?php echo CHtml::activeLabel($form,'login_or_email'); ?>
<?php echo CHtml::activeTextField($form,'login_or_email') ?>
<p class="hint"><?php echo Yii::t("user","Please enter your login or email addres."); ?></p>
</div>

<div class="action">
<br/>
<?php echo CHtml::submitButton(Yii::t("user", "Restore")); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->
<?php endif; ?>