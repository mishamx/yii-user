<?php
/**
 * RegistrationForm class.
 * RegistrationForm is the data structure for keeping
 * user registration form data. It is used by the 'registration' action of 'UserController'.
 */
class RegistrationForm extends User {
	public $verifyPassword;
	public $verifyCode;
	
	public function rules() {
		return array(
			array('username, password, verifyPassword, email', 'required'),
			array('username', 'length', 'max'=>20, 'min' => 3,'message' => UserModule::t("Incorrect username (length between 3 and 20 characters).")),
			array('password', 'length', 'max'=>128, 'min' => 4,'message' => UserModule::t("Incorrect password (minimal length 4 symbols).")),
			array('email', 'email'),
			array('username', 'unique', 'message' => UserModule::t("This user's name already exists.")),
			array('email', 'unique', 'message' => UserModule::t("This users's email adress already exists.")),
			array('password', 'compare', 'compareAttribute'=>'verifyPassword', 'message' => UserModule::t("Retype Password is incorrect.")),
			array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9\s,]+$/u','message' => UserModule::t("Incorrect symbol's. (A-z0-9)")),
		);
	}
	
}