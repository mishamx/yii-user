<?php $this->pageTitle=Yii::app()->name . ' - '.Yii::t("user", "Login"); ?>

<h1><?php echo Yii::t("user", "Login"); ?></h1>

<?php if(Yii::app()->user->hasFlash('loginMessage')): ?>
<div class="confirmation">
<?php echo Yii::app()->user->getFlash('loginMessage'); ?>
</div>
<?php endif; ?>
<div class="yiiForm">

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($form); ?>

<div class="simple">
<?php echo CHtml::activeLabel($form,'username'); ?>
<?php echo CHtml::activeTextField($form,'username') ?>
</div>

<div class="simple">
<?php echo CHtml::activeLabel($form,'password'); ?>
<?php echo CHtml::activePasswordField($form,'password') ?>
</div>

<div class="simple">

<p class="hint">
<?php echo CHtml::link(Yii::t("user", "Registration"),Yii::app()->User->registrationUrl); ?> | <?php echo CHtml::link(Yii::t("user", "Lost Password?"),Yii::app()->User->recoveryUrl); ?>
</p>
</div>

<div class="action">
<?php echo CHtml::activeCheckBox($form,'rememberMe'); ?>
<?php echo CHtml::activeLabel($form,'rememberMe'); ?>
<br/>
<?php echo CHtml::submitButton(Yii::t("user", "Login")); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->