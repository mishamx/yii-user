

    <?php if(Yii::app()->user->isGuest){
        echo CHtml::beginForm(array('/user/login'),'post',array('id'=>'b2g-login-box'));
        $link = '//' .
        Yii::app()->controller->uniqueid .
        '/' . Yii::app()->controller->action->id;
        echo CHtml::hiddenField('quicklogin', $link);
        echo CHtml::errorSummary($model); ?>

        <?php echo CHtml::activeTextField($model,'username', array('size' => 30, 'placeholder'=>$model->getAttributeLabel('username'))) ?>
        <?php echo CHtml::activePasswordField($model,'password', array('size' => 30, 'placeholder'=>$model->getAttributeLabel('password'))); ?>
        <?php echo CHtml::htmlButton('Sign in', array('id'=>'b2g-login-submit', 'title'=>Yii::t('app', 'Sign in'), 'type'=>'submit')); ?>
        <?php echo CHtml::link(Yii::t('app', 'Forgot password?'), array("/user/recovery"),array('id'=>'b2g-login-forgot'));?>
        <?php echo CHtml::link(Yii::t('app', 'Sign up'), array("/user/registration"),array('class'=>'button grad-blue'));?>

        <?php echo CHtml::endForm(); ?>
    <?php }else{ ?>
        <div id="b2g-profile-box">
            <div class="user-login-data">
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/users/YourAvatar.png" width='34' style="border-radius: 100%; float:left; padding-right: 5px;" />
                <div class="welcome"><?php echo Yii::t('app', 'Welcome, ').CHtml::link($model->username, array("/user/profile/profile"));?></div>
                <div class="balance"><span><?php echo Yii::t('app', 'Your balance: '); ?></span><i class="fa fa-dollar" title="<?php echo Yii::t('app', 'Your balance');?>"></i><?php echo '0.00';?></div>
            </div>

            <?php echo CHtml::link('Log out', array("/user/logout"),array('class'=>'logout', 'title'=>Yii::t('app', 'Log out')));?>
            <div class="my-messages">
                <i class="fa fa-envelope"></i>
                <span class="active-count">2</span>
            </div>
        </div>
    <?php };?>

