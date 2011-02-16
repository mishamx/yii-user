<?php

class Profile extends UActiveRecord
{
	/**
	 * The followings are the available columns in table 'profiles':
	 * @var integer $user_id
	 * @var boolean $regMode
	 */
	public $regMode = false;
	
	private $_model;
	private $_modelReg;

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
		return Yii::app()->getModule('user')->tableProfiles;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$required = array();
		$numerical = array();		
		$rules = array();
		
		$model=$this->getFields();
		
		foreach ($model as $field) {
			$field_rule = array();
			if ($field->required==ProfileField::REQUIRED_YES_NOT_SHOW_REG||$field->required==ProfileField::REQUIRED_YES_SHOW_REG)
				array_push($required,$field->varname);
			if ($field->field_type=='FLOAT'||$field->field_type=='INTEGER')
				array_push($numerical,$field->varname);
			if ($field->field_type=='VARCHAR'||$field->field_type=='TEXT') {
				$field_rule = array($field->varname, 'length', 'max'=>$field->field_size, 'min' => $field->field_size_min);
				if ($field->error_message) $field_rule['message'] = UserModule::t($field->error_message);
				array_push($rules,$field_rule);
			}
			if ($field->other_validator) {
				if (strpos($field->other_validator,'{')===0) {
					$validator = (array)CJavaScript::jsonDecode($field->other_validator);
					foreach ($validator as $name=>$val) {
						$field_rule = array($field->varname, $name);
						$field_rule = array_merge($field_rule,(array)$validator[$name]);
						if ($field->error_message) $field_rule['message'] = UserModule::t($field->error_message);
						array_push($rules,$field_rule);
					}
				} else {
					$field_rule = array($field->varname, $field->other_validator);
					if ($field->error_message) $field_rule['message'] = UserModule::t($field->error_message);
					array_push($rules,$field_rule);
				}
			} elseif ($field->field_type=='DATE') {
				$field_rule = array($field->varname, 'type', 'type' => 'date', 'dateFormat' => 'yyyy-mm-dd', 'allowEmpty'=>true);
				if ($field->error_message) $field_rule['message'] = UserModule::t($field->error_message);
				array_push($rules,$field_rule);
			}
			if ($field->match) {
				$field_rule = array($field->varname, 'match', 'pattern' => $field->match);
				if ($field->error_message) $field_rule['message'] = UserModule::t($field->error_message);
				array_push($rules,$field_rule);
			}
			if ($field->range) {
				$field_rule = array($field->varname, 'in', 'range' => self::rangeRules($field->range));
				if ($field->error_message) $field_rule['message'] = UserModule::t($field->error_message);
				array_push($rules,$field_rule);
			}
		}
		
		array_push($rules,array(implode(',',$required), 'required'));
		array_push($rules,array(implode(',',$numerical), 'numerical', 'integerOnly'=>true));
		return $rules;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		$relations = array(
			'user'=>array(self::HAS_ONE, 'User', 'id'),
		);
		if (isset(Yii::app()->getModule('user')->profileRelations)) $relations = array_merge($relations,Yii::app()->getModule('user')->profileRelations);
		return $relations;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		$labels = array(
			'user_id' => UserModule::t('User ID'),
		);
		$model=$this->getFields();
		
		foreach ($model as $field)
			$labels[$field->varname] = ((Yii::app()->getModule('user')->fieldsMessage)?UserModule::t($field->title,array(),Yii::app()->getModule('user')->fieldsMessage):UserModule::t($field->title));
			
		return $labels;
	}
	
	private function rangeRules($str) {
		$rules = explode(';',$str);
		for ($i=0;$i<count($rules);$i++)
			$rules[$i] = current(explode("==",$rules[$i]));
		return $rules;
	}
	
	static public function range($str,$fieldValue=NULL) {
		$rules = explode(';',$str);
		$array = array();
		for ($i=0;$i<count($rules);$i++) {
			$item = explode("==",$rules[$i]);
			if (isset($item[0])) $array[$item[0]] = ((isset($item[1]))?$item[1]:$item[0]);
		}
		if (isset($fieldValue)) 
			if (isset($array[$fieldValue])) return $array[$fieldValue]; else return '';
		else
			return $array;
	}
	
	public function widgetAttributes() {
		$data = array();
		$model=$this->getFields();
		
		foreach ($model as $field) {
			if ($field->widget) $data[$field->varname]=$field->widget;
		}
		return $data;
	}
	
	public function widgetParams($fieldName) {
		$data = array();
		$model=$this->getFields();
		
		foreach ($model as $field) {
			if ($field->widget) $data[$field->varname]=$field->widgetparams;
		}
		return $data[$fieldName];
	}
	
	public function getFields() {
		if ($this->regMode) {
			if (!$this->_modelReg)
				$this->_modelReg=ProfileField::model()->forRegistration()->findAll();
			return $this->_modelReg;
		} else {
			if (!$this->_model)
				$this->_model=ProfileField::model()->forOwner()->findAll();
			return $this->_model;
		}
	}
}