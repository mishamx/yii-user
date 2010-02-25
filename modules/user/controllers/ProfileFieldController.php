<?php

class ProfileFieldController extends Controller
{
	const PAGE_SIZE=10;

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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('*'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('*'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index', 'create','update','view','admin','delete'),
				'users'=>UserModule::getAdmins(),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		$this->render('view',array(
			'model'=>$this->loadModel(),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new ProfileField;
		if(isset($_POST['ProfileField']))
		{
			$model->attributes=$_POST['ProfileField'];
			
			if($model->validate()) {
				$sql = 'ALTER TABLE '.Profile::tableName().' ADD `'.$model->varname.'` ';
				$sql .= $model->field_type;
				if ($model->field_type!='TEXT'&&$model->field_type!='DATE')
					$sql .= '('.$model->field_size.')';
				$sql .= ' NOT NULL ';
				if ($model->default)
					$sql .= " DEFAULT '".$model->default."'";
				else
					$sql .= (($model->field_type=='TEXT'||$model->field_type=='VARCHAR')?" DEFAULT ''":" DEFAULT 0");
				
				$model->dbConnection->createCommand($sql)->execute();
				$model->save();
				$this->redirect(array('view','id'=>$model->id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();
		if(isset($_POST['ProfileField']))
		{
			$model->attributes=$_POST['ProfileField'];
			
			// ALTER TABLE `test` CHANGE `profiles` `field` INT( 10 ) NOT NULL 
			// ALTER TABLE `test` CHANGE `profiles` `description` INT( 1 ) NOT NULL DEFAULT '0'
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$model = $this->loadModel();
			$sql = 'ALTER TABLE '.Profile::tableName().' DROP `'.$model->varname.'`';
			if ($model->dbConnection->createCommand($sql)->execute()) {
				$model->delete();
			}
			// ALTER TABLE `profiles` DROP `field`

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_POST['ajax']))
				$this->redirect(array('index'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('ProfileField', array(
			'pagination'=>array(
				'pageSize'=>self::PAGE_SIZE,
			),
			'sort'=>array(
				'defaultOrder'=>'position',
			),
		));

		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$dataProvider=new CActiveDataProvider('ProfileField', array(
			'pagination'=>array(
				'pageSize'=>self::PAGE_SIZE,
			),
			'sort'=>array(
				'defaultOrder'=>'position',
			),
		));

		$this->render('admin',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=ProfileField::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
