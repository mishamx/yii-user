<?php

class UserModule extends CWebModule
{
	public $user_pase_size = 10;
	public $fields_pase_size = 10;
	
	public $hash='md5';
	public $sendActivationMail=true;
	public $loginNotActiv=false;
	public $autoLogin=true;
	public $registrationUrl = array("/user/registration");
	public $recoveryUrl = array("/user/recovery");
	public $loginUrl = array("/user/login");
	public $logoutUrl = array("/user/logout");
	public $profileUrl = array("/user/profile");
	public $returnUrl = array("/user/profile");
	public $returnLogoutUrl = array("/user/login");
	public $relations = array();
	
	private $_user;
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'user.models.*',
			'user.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
	
	public function t($str='',$params=array()) {
		return Yii::t("user", $str, $params);
	}
	
	/**
	 * @return hash string.
	 */
	public function encrypting($string="") {
		$hash = Yii::app()->controller->module->hash;
		if ($hash=="md5")
			return md5($string);
		if ($hash=="sha1")
			return sha1($string);
		else
			return hash($hash,$string);
	}
	
	/**
	 * Return admin status.
	 * @return boolean
	 */
	public function isAdmin() {
		if(Yii::app()->user->isGuest)
			return false;
		else {
			if(User::model()->active()->superuser()->findbyPk(Yii::app()->user->id))
				return true;
			else
				return false;
		}
	}

	/**
	 * Return admins.
	 * @return array syperusers names
	 */	
	public function getAdmins() {
		$admins = User::model()->active()->superuser()->findAll();
		$return_name = array();
		foreach ($admins as $admin)
			array_push($return_name,$admin->username);
		return $return_name;
	}
	
	/**
	 * Send mail method
	 */
	public function sendMail($email,$subject,$message) {
    	$headers="From: ".Yii::app()->params['adminEmail']."\r\nReply-To: ".Yii::app()->params['adminEmail'];
		return mail($email,$subject,$message,$headers);
	}
	
	/**
	 * Return safe user data.
	 * @param user id not required
	 * @return user object or false
	 */
	public function user($id=0) {
		if ($id) 
			return User::model()->active()->findbyPk($id);
		else {
			if(Yii::app()->user->isGuest)
				return false;
			else 
				return User::model()->active()->findbyPk(Yii::app()->user->id);
		}
	}
	
	/**
	 * Return safe user data.
	 * @param user id not required
	 * @return user object or false
	 */
	public function users() {
		return User;
	}
}
