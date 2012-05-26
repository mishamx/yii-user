<?php

class ProfileFieldController extends Controller
{

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	private static $_widgets = array();
	public $defaultAction = 'admin';
	public $layout='//layouts/column2';

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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('*'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','update','view','admin','delete'),
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
	 * Register Script
	 */
	public function registerScript() {
		$basePath=Yii::getPathOfAlias('application.modules.user.views.asset');
		$baseUrl=Yii::app()->getAssetManager()->publish($basePath);
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerCssFile($baseUrl.'/css/redmond/jquery-ui.css');
		$cs->registerCssFile($baseUrl.'/css/style.css');
		$cs->registerScriptFile($baseUrl.'/js/jquery-ui.min.js');
		$cs->registerScriptFile($baseUrl.'/js/form.js');
		$cs->registerScriptFile($baseUrl.'/js/jquery.json.js');
		
		$widgets = self::getWidgets();
		
		$wgByTypes = ProfileField::itemAlias('field_type');
		foreach ($wgByTypes as $k=>$v) {
			$wgByTypes[$k] = array();
		}
		
		foreach ($widgets[1] as $widget) {
			if (isset($widget['fieldType'])&&count($widget['fieldType'])) {
				foreach($widget['fieldType'] as $type) {
					array_push($wgByTypes[$type],$widget['name']);
				}
			}
		}
		//echo '<pre>'; print_r($widgets[1]); die();
		$js = "

	var name = $('#name'),
	value = $('#value'),
	allFields = $([]).add(name).add(value),
	tips = $('.validateTips');
	
	var listWidgets = jQuery.parseJSON('".str_replace("'","\'",CJavaScript::jsonEncode($widgets[0]))."');
	var widgets = jQuery.parseJSON('".str_replace("'","\'",CJavaScript::jsonEncode($widgets[1]))."');
	var wgByType = jQuery.parseJSON('".str_replace("'","\'",CJavaScript::jsonEncode($wgByTypes))."');
	
	var fieldType = {
			'INTEGER':{
				'hide':['match','other_validator','widgetparams'],
				'val':{
					'field_size':10,
					'default':'0',
					'range':'',
					'widgetparams':''
				}
			},
			'VARCHAR':{
				'hide':['widgetparams'],
				'val':{
					'field_size':255,
					'default':'',
					'range':'',
					'widgetparams':''
				}
			},
			'TEXT':{
				'hide':['field_size','range','widgetparams'],
				'val':{
					'field_size':0,
					'default':'',
					'range':'',
					'widgetparams':''
				}
			},
			'DATE':{
				'hide':['field_size','field_size_min','match','range','widgetparams'],
				'val':{
					'field_size':0,
					'default':'0000-00-00',
					'range':'',
					'widgetparams':''
				}
			},
			'FLOAT':{
				'hide':['match','other_validator','widgetparams'],
				'val':{
					'field_size':'10.2',
					'default':'0.00',
					'range':'',
					'widgetparams':''
				}
			},
			'DECIMAL':{
				'hide':['match','other_validator','widgetparams'],
				'val':{
					'field_size':'10,2',
					'default':'0',
					'range':'',
					'widgetparams':''
				}
			},
			'BOOL':{
				'hide':['field_size','field_size_min','match','widgetparams'],
				'val':{
					'field_size':0,
					'default':0,
					'range':'1==".UserModule::t('Yes').";0==".UserModule::t('No')."',
					'widgetparams':''
				}
			},
			'BLOB':{
				'hide':['field_size','field_size_min','match','widgetparams'],
				'val':{
					'field_size':0,
					'default':'',
					'range':'',
					'widgetparams':''
				}
			},
			'BINARY':{
				'hide':['field_size','field_size_min','match','widgetparams'],
				'val':{
					'field_size':0,
					'default':'',
					'range':'',
					'widgetparams':''
				}
			}
		};
			
	function showWidgetList(type) {
		$('div.widget select').empty();
		$('div.widget select').append('<option value=\"\">".UserModule::t('No')."</option>');
		if (wgByType[type]) {
			for (var k in wgByType[type]) {
				$('div.widget select').append('<option value=\"'+wgByType[type][k]+'\">'+widgets[wgByType[type][k]]['label']+'</option>');
			}
		}
	}
		
	function setFields(type) {
		if (fieldType[type]) {
			if (".((isset($_GET['id']))?0:1).") {
				showWidgetList(type);
				$('#widgetlist option:first').attr('selected', 'selected');
			}
			
			$('div.row').addClass('toshow').removeClass('tohide');
			if (fieldType[type].hide.length) $('div.'+fieldType[type].hide.join(', div.')).addClass('tohide').removeClass('toshow');
			if ($('div.widget select').val()) {
				$('div.widgetparams').removeClass('tohide');
			}
			$('div.toshow').show(500);
			$('div.tohide').hide(500);
			".((!isset($_GET['id']))?"
			for (var k in fieldType[type].val) { 
				$('div.'+k+' input').val(fieldType[type].val[k]);
			}":'')."
		}
	}
	
	function isArray(obj) {
		if (obj.constructor.toString().indexOf('Array') == -1)
			return false;
		else
			return true;
	}
		
	$('#dialog-form').dialog({
		autoOpen: false,
		height: 400,
		width: 400,
		modal: true,
		buttons: {
			'".UserModule::t('Save')."': function() {
				var wparam = {};
				var fparam = {};
				$('#dialog-form fieldset .wparam').each(function(){
					if ($(this).val()) wparam[$(this).attr('name')] = $(this).val();
				});
				
				var tab = $('#tabs ul li.ui-tabs-selected').text();
				fparam[tab] = {};
				$('#dialog-form fieldset .tab-'+tab).each(function(){
					if ($(this).val()) fparam[tab][$(this).attr('name')] = $(this).val();
				});
				
				if ($.JSON.encode(wparam)!='{}') $('div.widgetparams input').val($.JSON.encode(wparam));
				if ($.JSON.encode(fparam[tab])!='{}') $('div.other_validator input').val($.JSON.encode(fparam)); 
				
				$(this).dialog('close');
			},
			'".UserModule::t('Cancel')."': function() {
				$(this).dialog('close');
			}
		},
		close: function() {
		}
	});


	$('#widgetparams').focus(function() {
		var widget = widgets[$('#widgetlist').val()];
		var html = '';
		var wparam = ($('div.widgetparams input').val())?$.JSON.decode($('div.widgetparams input').val()):{};
		var fparam = ($('div.other_validator input').val())?$.JSON.decode($('div.other_validator input').val()):{};
		
		// Class params
		for (var k in widget.params) {
			html += '<label for=\"name\">'+((widget.paramsLabels[k])?widget.paramsLabels[k]:k)+'</label>';
			html += '<input type=\"text\" name=\"'+k+'\" id=\"widget_'+k+'\" class=\"text wparam ui-widget-content ui-corner-all\" value=\"'+((wparam[k])?wparam[k]:widget.params[k])+'\" />';
		}
		// Validator params		
		if (widget.other_validator) {
			var tabs = '';
			var li = '';
			for (var t in widget.other_validator) {
				tabs += '<div id=\"tab-'+t+'\" class=\"tab\">';
				li += '<li'+((fparam[t])?' class=\"ui-tabs-selected\"':'')+'><a href=\"#tab-'+t+'\">'+t+'</a></li>';
				
				for (var k in widget.other_validator[t]) {
					tabs += '<label for=\"name\">'+((widget.paramsLabels[k])?widget.paramsLabels[k]:k)+'</label>';
					if (isArray(widget.other_validator[t][k])) {
						tabs += '<select type=\"text\" name=\"'+k+'\" id=\"filter_'+k+'\" class=\"text fparam ui-widget-content ui-corner-all tab-'+t+'\">';
						for (var i in widget.other_validator[t][k]) {
							tabs += '<option value=\"'+widget.other_validator[t][k][i]+'\"'+((fparam[t]&&fparam[t][k])?' selected=\"selected\"':'')+'>'+widget.other_validator[t][k][i]+'</option>';
						}
						tabs += '</select>';
					} else {
						tabs += '<input type=\"text\" name=\"'+k+'\" id=\"filter_'+k+'\" class=\"text fparam ui-widget-content ui-corner-all tab-'+t+'\" value=\"'+((fparam[t]&&fparam[t][k])?fparam[t][k]:widget.other_validator[t][k])+'\" />';
					}
				}
				tabs += '</div>';
			}
			html += '<div id=\"tabs\"><ul>'+li+'</ul>'+tabs+'</div>';
		}
		
		$('#dialog-form fieldset').html(html);
		
		$('#tabs').tabs();
		
		// Show form
		$('#dialog-form').dialog('open');
	});
	
	$('#field_type').change(function() {
		setFields($(this).val());
	});
	
	$('#widgetlist').change(function() {
		if ($(this).val()) {
			$('div.widgetparams').show(500);
		} else {
			$('div.widgetparams').hide(500);
		}
		
	});
	
	// show all function 
	$('div.form p.note').append('<br/><a href=\"#\" id=\"showAll\">".UserModule::t('Show all')."</a>');
 	$('#showAll').click(function(){
		$('div.row').show(500);
		return false;
	});
	
	// init
	setFields($('#field_type').val());
	
	";
		$cs->registerScript(__CLASS__.'#dialog', $js);
	} 

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new ProfileField;
		$scheme = get_class(Yii::app()->db->schema);
		if(isset($_POST['ProfileField']))
		{
			$model->attributes=$_POST['ProfileField'];
			
			if($model->validate()) {
				$sql = 'ALTER TABLE '.Profile::model()->tableName().' ADD `'.$model->varname.'` ';
				$sql .= $this->fieldType($model->field_type);
				if (
						$model->field_type!='TEXT'
						&& $model->field_type!='DATE'
						&& $model->field_type!='BOOL'
						&& $model->field_type!='BLOB'
						&& $model->field_type!='BINARY'
					)
					$sql .= '('.$model->field_size.')';
				$sql .= ' NOT NULL ';
				
				if ($model->field_type!='TEXT'&&$model->field_type!='BLOB'||$scheme!='CMysqlSchema') {
					if ($model->default)
						$sql .= " DEFAULT '".$model->default."'";
					else
						$sql .= ((
									$model->field_type=='TEXT'
									||$model->field_type=='VARCHAR'
									||$model->field_type=='BLOB'
									||$model->field_type=='BINARY'
								)?" DEFAULT ''":(($model->field_type=='DATE')?" DEFAULT '0000-00-00'":" DEFAULT 0"));
				}
				$model->dbConnection->createCommand($sql)->execute();
				$model->save();
				$this->redirect(array('view','id'=>$model->id));
			}
		}
		
		$this->registerScript();
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
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
		$this->registerScript();

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
			$scheme = get_class(Yii::app()->db->schema);
			$model = $this->loadModel();
			if ($scheme=='CSqliteSchema') {
				$attr = Profile::model()->attributes;
				unset($attr[$model->varname]);
				$attr = array_keys($attr);
				$connection=Yii::app()->db;
				$transaction=$connection->beginTransaction();
				$status=true;
				try
				{
					$sql = '';
				    $connection->createCommand(
				    	"CREATE TEMPORARY TABLE ".Profile::model()->tableName()."_backup (".implode(',',$attr).")"
				    )->execute();
				    
				    $connection->createCommand(
				    	"INSERT INTO ".Profile::model()->tableName()."_backup SELECT ".implode(',',$attr)." FROM ".Profile::model()->tableName()
				    )->execute();
				    
				    $connection->createCommand(
				    	"DROP TABLE ".Profile::model()->tableName()
				    )->execute();
				    
				    $connection->createCommand(
				    	"CREATE TABLE ".Profile::model()->tableName()." (".implode(',',$attr).")"
				    )->execute();
				    
				    $connection->createCommand(
				    	"INSERT INTO ".Profile::model()->tableName()." SELECT ".implode(',',$attr)." FROM ".Profile::model()->tableName()."_backup"
				    )->execute();
				    
				    $connection->createCommand(
				    	"DROP TABLE ".Profile::model()->tableName()."_backup"
				    )->execute();
				    
				    $transaction->commit();
				}
				catch(Exception $e) 
				{
				    $transaction->rollBack();
				    $status=false;
				}
				if ($status) {
					$model->delete();
				}
				
			} else {
				$sql = 'ALTER TABLE '.Profile::model()->tableName().' DROP `'.$model->varname.'`';
				if ($model->dbConnection->createCommand($sql)->execute()) {
					$model->delete();
				}
			}

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_POST['ajax']))
				$this->redirect(array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ProfileField('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['ProfileField']))
            $model->attributes=$_GET['ProfileField'];

        $this->render('admin',array(
            'model'=>$model,
        ));
		/*
		$dataProvider=new CActiveDataProvider('ProfileField', array(
			'pagination'=>array(
				'pageSize'=>Yii::app()->controller->module->fields_page_size,
			),
			'sort'=>array(
				'defaultOrder'=>'position',
			),
		));

		$this->render('admin',array(
			'dataProvider'=>$dataProvider,
		));//*/
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
	
	/**
	 * MySQL field type
	 * @param $type string
	 * @return string
	 */
	public function fieldType($type) {
		$type = str_replace('UNIX-DATE','INTEGER',$type);
		return $type;
	}
	
	public static function getWidgets($fieldType='') {
		$basePath=Yii::getPathOfAlias('application.modules.user.components');
		$widgets = array();
		$list = array(''=>UserModule::t('No'));
		if (self::$_widgets) {
			$widgets = self::$_widgets;
		} else {
			$d = dir($basePath);
			while (false !== ($file = $d->read())) {
				if (strpos($file,'UW')===0) {
					list($className) = explode('.',$file);
					if (class_exists($className)) {
						$widgetClass = new $className;
						if ($widgetClass->init()) {
							$widgets[$className] = $widgetClass->init();
							if ($fieldType) {
								if (in_array($fieldType,$widgets[$className]['fieldType'])) $list[$className] = $widgets[$className]['label'];
							} else {
								$list[$className] = $widgets[$className]['label'];
							}
						}
					}
				}
			}
			$d->close();
		}
		return array($list,$widgets);		
	}
	

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='profile-field-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
