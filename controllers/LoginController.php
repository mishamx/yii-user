<?php

class LoginController extends Controller
{
	public $defaultAction = 'login';

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if (Yii::app()->user->isGuest) {
			$model=new UserLogin;
			// collect user input data
			if(isset($_POST['UserLogin']))
			{
				$model->attributes=$_POST['UserLogin'];
				// validate user input and redirect to previous page if valid
				if($model->validate()) {
					$returnUrl = UserModule::getReturnUrl(!$this->isUserWasLogined());
					$this->lastViset();
					if (Yii::app()->getBaseUrl()."/index.php" === $returnUrl)
						$this->redirect(Yii::app()->controller->module->returnUrl);
					else
						$this->redirect($returnUrl);
				}
			}
			// display the login form
			$this->render('/user/login',array('model'=>$model));
		} else
			$this->redirect(Yii::app()->controller->module->returnUrl);
	}

	private function lastViset() {
		$user = UserModule::user();
		$user->lastvisit_at = date('Y-m-d H:i:s');
		$user->save();
	}

	/**
	 * Return if it's user's was already logged in.
	 *
	 * @return bool
	 */
	private function isUserWasLogined()
	{
		$user = UserModule::user();

		return $user->lastvisit_at != '0000-00-00 00:00:00';
	}
}