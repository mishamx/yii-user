<?php

class RegistrationController extends Controller
{
	public $defaultAction = 'registration';
	


	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}
	/**
	 * Registration user
	 */
	public function actionRegistration() {
            $model = new RegistrationForm;
            $profile=new Profile;
		    if (Yii::app()->user->id) {
		    	$this->redirect(Yii::app()->controller->module->profileUrl);
		    } else {
		    	if(isset($_POST['RegistrationForm'])) {
					$model->attributes=$_POST['RegistrationForm'];
					$profile->attributes=$_POST['Profile'];
					if($model->validate()&&$profile->validate())
					{
						$soucePassword = $model->password;
						$model->password=UserModule::encrypting($model->password);
						$model->verifyPassword=UserModule::encrypting($model->verifyPassword);
						$model->activkey=UserModule::encrypting(microtime().$model->password);
						$model->createtime=time();
						$model->lastvisit=((Yii::app()->controller->module->autoLogin&&Yii::app()->controller->module->loginNotActiv)?time():0);
						$model->superuser=0;
						$model->status=0;
						
						if ($model->save()) {
							//$model->save();
							$profile->user_id=$model->id;
							$profile->save();
							$activation_url = 'http://' . $_SERVER['HTTP_HOST'].$this->createUrl('/user/activation',array("activkey" => $model->activkey, "email" => $model->email));
							UserModule::sendMail($model->email,"You registered from ".Yii::app()->name,"Please activate you account go to $activation_url.");
							if (Yii::app()->controller->module->loginNotActiv) {
								if (Yii::app()->controller->module->autoLogin) {
									$identity=new UserIdentity($model->username,$soucePassword);
									$identity->authenticate();
									Yii::app()->user->login($identity,0);
									$this->redirect(Yii::app()->controller->module->returnUrl);
								} else {
									Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email or login."));
									$this->refresh();
								}
							} else {
								Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email."));
								$this->refresh();
							}
						}
					}
				}
			    $this->render('/user/registration',array('form'=>$model,'profile'=>$profile));
		    }
	}

}