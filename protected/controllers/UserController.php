<?php

class UserController extends CController
{
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'profile'.
	 */
	public $defaultAction='profile';
	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('list','show','registration','captcha','login', 'recovery', 'activation'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('profile', 'logout', 'changepassword'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','create','update'),
				'users'=>array('admin'),
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
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xEBF4FB,
			),
		);
	}
	/**
	 * Activation user account
	 */
	public function actionActivation () {
		$email = $_GET['email'];
		$activkey = $_GET['activkey'];
		if ($email&&$activkey) {
			$find = User::model()->findByAttributes(array('email'=>$email));
			if ($find->status) {
			    $this->render('message',array('title'=>Yii::t("user", "User activation"),'content'=>Yii::t("user", "You account is active.")));
			} elseif($find->activkey==$activkey) {
				$find->activkey = Yii::app()->User->encrypting(microtime());
				$find->status = 1;
				$find->save();
			    $this->render('message',array('title'=>Yii::t("user", "User activation"),'content'=>Yii::t("user", "You account is activated.")));
			} else {
			    $this->render('message',array('title'=>Yii::t("user", "User activation"),'content'=>Yii::t("user", "Incorrect activation URL.")));
			}
		} else {
			$this->render('message',array('title'=>Yii::t("user", "User activation"),'content'=>Yii::t("user", "Incorrect activation URL.")));
		}
	}
	
	/**
	 * Change password
	 */
	public function actionChangepassword() {
		$form = new UserChangePassword;
		if ($uid = Yii::app()->user->id) {
			if(isset($_POST['UserChangePassword'])) {
					$form->attributes=$_POST['UserChangePassword'];
					if($form->validate()) {
						$new_password = User::model()->findByPk(Yii::app()->user->id);
						$new_password->password = Yii::app()->User->encrypting($form->password);
						$new_password->save();
						Yii::app()->user->setFlash('profileMessage',Yii::t("user", "New password is saved."));
						$this->redirect(array("user/profile"));
					}
			} 
			$this->render('changepassword',array('form'=>$form));
	    }
	}
	
	
	/**
	 * Recovery password
	 */
	public function actionRecovery () {
		$form = new UserRecoveryForm;
		if ($uid = Yii::app()->user->id) {
		    	$this->redirect(Yii::app()->User->returnUrl);
		    } else {
				$email = $_GET['email'];
				$activkey = $_GET['activkey'];
				if ($email&&$activkey) {
					$form2 = new UserChangePassword;
		    		$find = User::model()->findByAttributes(array('email'=>$email));
		    		if($find->activkey==$activkey) {
			    		if(isset($_POST['UserChangePassword'])) {
							$form2->attributes=$_POST['UserChangePassword'];
							if($form2->validate()) {
								$find->password = Yii::app()->User->encrypting($form2->password);
								$find->activkey=Yii::app()->User->encrypting(microtime().$form2->password);
								$find->save();
								Yii::app()->user->setFlash('loginMessage',Yii::t("user", "New password is saved."));
								$this->redirect(array("user/login"));
							}
						} 
						$this->render('changepassword',array('form'=>$form2));
		    		} else {
		    			Yii::app()->user->setFlash('recoveryMessage',Yii::t("user", "Incorrect recovery link."));
						$this->redirect('http://' . $_SERVER['HTTP_HOST'].$this->createUrl('user/recovery'));
		    		}
		    	} else {
			    	if(isset($_POST['UserRecoveryForm'])) {
			    		$form->attributes=$_POST['UserRecoveryForm'];
			    		if($form->validate()) {
			    			$user = User::model()->findbyPk($form->user_id);
			    			$headers="From: ".Yii::app()->params['adminEmail']."\r\nReply-To: ".Yii::app()->params['adminEmail'];
							$activation_url = 'http://' . $_SERVER['HTTP_HOST'].$this->createUrl('user/recovery',array("activkey" => $user->activkey, "email" => $user->email));
							mail($user->email,"You have requested the password recovery site ".Yii::app()->name,"You have requested the password recovery site ".Yii::app()->name.". To receive a new password, go to $activation_url.",$headers);
			    			Yii::app()->user->setFlash('recoveryMessage',Yii::t("user", "Please check your email. An instructions was sent to your email address."));
			    			$this->refresh();
			    		}
			    	}
		    		$this->render('recovery',array('form'=>$form));
		    	}
		    }
	}
	
	
	/**
	 * Registration user
	 */
	public function actionRegistration() {
            $model = new RegistrationForm;
		    if ($uid = Yii::app()->user->id) {
		    	$this->redirect(Yii::app()->homeUrl);
		    } else {
		    	if(isset($_POST['RegistrationForm'])) {
					$model->attributes=$_POST['RegistrationForm'];
					if($model->validate())
					{
						$soucePassword = $model->password;
						$model->password=Yii::app()->User->encrypting($model->password);
						$model->verifyPassword=Yii::app()->User->encrypting($model->verifyPassword);
						$model->activkey=Yii::app()->User->encrypting(microtime().$model->password);
						$model->createtime=time();
						$model->lastvisit=0;
						$model->superuser=0;
						$model->status=0;
						
						if ($model->save()) {
							$headers="From: ".Yii::app()->params['adminEmail']."\r\nReply-To: ".Yii::app()->params['adminEmail'];
							$activation_url = 'http://' . $_SERVER['HTTP_HOST'].$this->createUrl('user/activation',array("activkey" => $model->activkey, "email" => $model->email));
							mail($model->email,"You registered from ".Yii::app()->name,"Please activate you account go to $activation_url.",$headers);
							if (Yii::app()->User->loginNotActiv) {
								if (Yii::app()->User->autoLogin) {
									$identity=new UserIdentity($model->username,$soucePassword);
									$identity->authenticate();
									Yii::app()->user->login($identity,0);
									$this->redirect(Yii::app()->User->returnUrl);
								} else {
									Yii::app()->user->setFlash('registration',Yii::t("user", "Thank you for your registration. Please check your email or login."));
									$this->refresh();
								}
							} else {
								Yii::app()->user->setFlash('registration',Yii::t("user", "Thank you for your registration. Please check your email."));
								$this->refresh();
							}
						}
					}
				}
			    $this->render('registration',array('form'=>$model));
		    }
	}

	/**
	 * Shows a particular model.
	 */
	public function actionProfile()
	{
		if ($uid = Yii::app()->user->id) {
		    $this->render('profile',array('model'=>$this->loadUser($uid = Yii::app()->user->id)));
		}
		
	}


	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$form=new LoginForm;
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$form->attributes=$_POST['LoginForm'];
			// validate user input and redirect to previous page if valid
			if($form->validate()) {
				#echo "<pre>"; print_r(Yii::app()->user->id); die();
				$lastVisit = User::model()->findByPk(Yii::app()->user->id);
				$lastVisit->lastvisit = time();
				$lastVisit->save();
				$this->redirect(Yii::app()->User->returnUrl);
			}
		}
		// display the login form
		$this->render('login',array('form'=>$form));
	}

	/**
	 * Logout the current user and redirect to returnLogoutUrl.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->User->returnLogoutUrl);
	}
	

	/**
	 * Shows a particular model.
	 */
	public function actionShow()
	{
		$this->render('show',array('model'=>$this->loadUser()));
	}
	
	

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionCreate()
	{
		$model=new User;
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('show','id'=>$model->id));
		}
		$this->render('create',array('model'=>$model));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadUser();
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('show','id'=>$model->id));
		}
		$this->render('update',array('model'=>$model));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'list' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadUser()->delete();
			$this->redirect(array('list'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionList()
	{
		$criteria=new CDbCriteria;

		$pages=new CPagination(User::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$models=User::model()->findAll($criteria);

		$this->render('list',array(
			'models'=>$models,
			'pages'=>$pages,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->processAdminCommand();

		$criteria=new CDbCriteria;

		$pages=new CPagination(User::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('User');
		$sort->applyOrder($criteria);

		$models=User::model()->findAll($criteria);

		$this->render('admin',array(
			'models'=>$models,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadUser($id=null)
	{
		if($this->_model===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_model=User::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

	/**
	 * Executes any command triggered on the admin page.
	 */
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
		{
			$this->loadUser($_POST['id'])->delete();
			// reload the current page to avoid duplicated delete actions
			$this->refresh();
		}
	}
}
