<?php

class Profile extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'profiles':
	 * @var integer $user_id
	 * @var string $lastname
	 * @var string $firstname
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
		return '{{profiles}}';
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
		
		
		$model=ProfileField::model()->forOwner()->findAll();
		
		foreach ($model as $field) {
			$field_rule = array();
			if ($field->required==1)
				array_push($required,$field->varname);
			if ($field->field_type=='int'||$field->field_type=='FLOAT'||$field->field_type=='INTEGER')
				array_push($numerical,$field->varname);
			if ($field->field_type=='VARCHAR'||$field->field_type=='TEXT') {
				$field_rule = array($field->varname, 'length', 'max'=>$field->field_size, 'min' => $field->field_size_min);
				if ($field->error_message) $field_rule['message'] = UserModule::t($field->error_message);
				array_push($rules,$field_rule);
			}
			if ($field->field_type=='DATE') {
				$field_rule = array($field->varname, 'type', 'type' => 'date', 'dateFormat' => 'yyyy-mm-dd');
				if ($field->error_message) $field_rule['message'] = UserModule::t($field->error_message);
				array_push($rules,$field_rule);
			}
			if ($field->match) {
				$field_rule = array($field->varname, 'match', 'pattern' => $field->match);
				if ($field->error_message) $field_rule['message'] = UserModule::t($field->error_message);
				array_push($rules,$field_rule);
			}
			if ($field->range) {
				$field_rule = array($field->varname, 'in', 'range' => explode(';'.$field->range));
				if ($field->error_message) $field_rule['message'] = UserModule::t($field->error_message);
				array_push($rules,$field_rule);
			}
			if ($field->other_validator) {
				$field_rule = array($field->varname, $field->other_validator);
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
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		$labels = array(
			'user_id' => UserModule::t('User ID'),
		);
		$model=ProfileField::model()->forOwner()->findAll();
		
		foreach ($model as $field)
			$labels[$field->varname] = UserModule::t($field->title);
			
		return $labels;
	}

	
}