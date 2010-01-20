<?php

class User extends CActiveRecord
{
	public $username;
	public $password;
	public $email;
	public $hash='md5';
	public $sendActivationMail=true;
	public $loginNotActiv=false;
	public $autoLogin=true;
	public $registrationUrl = array("user/registration");
	public $recoveryUrl = array("user/recovery");
	public $loginUrl = array("user/login");
	public $returnUrl = array("user/profile");
	public $returnLogoutUrl = array("user/login");
	
	/**
	 * The followings are the available columns in table 'User':
	 * @var integer $id
	 * @var string $username
	 * @var string $password
	 * @var string $email
	 * @var string $activkey
	 * @var integer $createtime
	 * @var integer $lastvisit
	 * @var integer $superuser
	 * @var integer $status
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'User';
	}
	
	
	public function rules() {
		return array(
			#array('username, password, verifyPassword, email', 'required'),
			array('username', 'length', 'max'=>20, 'min' => 3,'message' => Yii::t("user", "Incorrect username (length between 3 and 20 characters).")),
			array('password', 'length', 'max'=>128, 'min' => 4,'message' => Yii::t("user", "Incorrect password (minimal length 4 symbols).")),
			array('email', 'email'),
			array('username', 'unique', 'message' => Yii::t("user", "This user\'s name already exists.")),
			array('email', 'unique', 'message' => Yii::t("user", "This user\'s email adress already exists.")),
			#array('password', 'compare', 'compareAttribute'=>'verifyPassword', 'message' => Yii::t("user", "Retype Password is incorrect.")),
			#array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd')),
			#array('username', 'match', 'pattern' => '/^[A-Za-z0-9\s,]+$/u','message' => Yii::t("user", "Incorrect symbol's. (A-z0-9)")),
			array('username, password, email, activkey, createtime, lastvisit, superuser, status', 'required'),
			array('createtime, lastvisit, superuser, status', 'numerical', 'integerOnly'=>true),
			#array('username, password, email, activkey', 'length', 'max'=>128),
		);
	}
	
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>Yii::t("user", "username"),
			'password'=>Yii::t("user", "password"),
			'verifyPassword'=>Yii::t("user", "Retype Password"),
			'email'=>Yii::t("user", "E-mail"),
			'verifyCode'=>Yii::t("user", "Verification Code"),
			'id' => 'Id',
			'activkey' => Yii::t("user", "activation key"),
			'createtime' => Yii::t("user", "Registration date"),
			'lastvisit' => Yii::t("user", "Last visit"),
			'superuser' => Yii::t("user", "Superuser"),
			'status' => Yii::t("user", "Status"),
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}
	
	/**
	 * @return hash string.
	 */
	public function encrypting($string="") {
		$hash = Yii::app()->User->hash;
		if ($hash=="md5")
			return md5($string);
		if ($hash=="sha1")
			return sha1($string);
		else
			return hash($hash,$string);
	}
}