<?php

class RegistrationController extends Controller
{
	public $defaultAction = 'registration';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return CMap::mergeArray(parent::filters(),array(
			'accessControl', // perform access control for CRUD operations
		));
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('registration'),
				'expression'=>'Yii::app()->getModule(\'user\')->allowGuestRegister',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			'captcha'=>Yii::app()->getModule('user')->captchaParams,
		);
	}
	/**
	 * Registration user
	 */
	public function actionRegistration() {
        Profile::$regMode = true;
        $model = new RegistrationForm;
        $profile=new Profile;

        // ajax validator
        if(isset($_POST['ajax']) && $_POST['ajax']==='registration-form')
        {
            echo UActiveForm::validate(array($model,$profile));
            Yii::app()->end();
        }

        if (Yii::app()->user->id) {
            $this->redirect(Yii::app()->controller->module->profileUrl);
        } else {
            if(isset($_POST['RegistrationForm'])) {
                $model->attributes=$_POST['RegistrationForm'];
                $profile->attributes=((isset($_POST['Profile'])?$_POST['Profile']:array()));
                if($model->validate()&&$profile->validate())
                {
                    $soucePassword = $model->password;
                    $model->activkey=UserModule::encrypting(microtime().$model->password);
                    $model->password=UserModule::encrypting($model->password);
                    $model->verifyPassword=UserModule::encrypting($model->verifyPassword);
                    $model->superuser=0;
                    $model->status=((Yii::app()->controller->module->activeAfterRegister)?User::STATUS_ACTIVE:User::STATUS_NOACTIVE);

                    if ($model->save()) {
                        $profile->user_id=$model->id;
                        $profile->save();
                        if (Yii::app()->controller->module->sendActivationMail) {
                            $activation_url = $this->createAbsoluteUrl('/user/activation/activation',array("activkey" => $model->activkey, "email" => $model->email));
                            UserModule::sendMail($model->email,UserModule::t("You registered from {site_name}",array('{site_name}'=>Yii::app()->name)),UserModule::t("Please activate you account go to {activation_url}",array('{activation_url}'=>$activation_url)));
                        }

                        if ((Yii::app()->controller->module->loginNotActiv||(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false))&&Yii::app()->controller->module->autoLogin) {
                                $identity=new UserIdentity($model->username,$soucePassword);
                                $identity->authenticate();
                                Yii::app()->user->login($identity,0);
                                $this->redirect(Yii::app()->controller->module->returnUrl);
                        } else {
                            if (!Yii::app()->controller->module->activeAfterRegister&&!Yii::app()->controller->module->sendActivationMail) {
                                Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Contact Admin to activate your account."));
                            } elseif(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false) {
                                Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please {{login}}.",array('{{login}}'=>CHtml::link(UserModule::t('Login'),Yii::app()->controller->module->loginUrl))));
                            } elseif(Yii::app()->controller->module->loginNotActiv) {
                                Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email or login."));
                            } else {
                                Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email."));
                            }
                            $this->refresh();
                        }
                    }
                } else $profile->validate();
            }
            $this->render('/user/registration',array('model'=>$model,'profile'=>$profile));
        }
	}
}