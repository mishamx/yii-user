<?php

class UWrelBelongsTo {
	
	public $params = array(
		'modelName'=>'',
		'optionName'=>'',
		'emptyField'=>'',
		'relationName'=>'',
	);
	
	/**
	 * Widget initialization
	 * @return array
	 */
	public function init() {
		return array(
			'name'=>__CLASS__,
			'label'=>UserModule::t('Relation Belongs To',array(),__CLASS__),
			'fieldType'=>array('INTEGER'),
			'params'=>$this->params,
			'paramsLabels' => array(
				'modelName'=>UserModule::t('Model Name',array(),__CLASS__),
				'optionName'=>UserModule::t('Lable field name',array(),__CLASS__),
				'emptyField'=>UserModule::t('Empty item name',array(),__CLASS__),
				'relationName'=>UserModule::t('Profile model relation name',array(),__CLASS__),
			),
		);
	}
	
	/**
	 * @param $value
	 * @param $model
	 * @param $field_varname
	 * @return string
	 */
	public function setAttributes($value,$model,$field_varname) {
		return $value;
	}
	
	/**
	 * @param $model - profile model
	 * @param $field - profile fields model item
	 * @return string
	 */
	public function viewAttribute($model,$field) {
		$relation = $model->relations();
		if ($this->params['relationName']&&isset($relation[$this->params['relationName']])) {
			$m = $model->__get($this->params['relationName']);
		} else {
			$m = CActiveRecord::model($this->params['modelName'])->findByPk($model->getAttribute($field->varname));
		}
		
		if ($m)
			return (($this->params['optionName'])?$m->getAttribute($this->params['optionName']):$m->id);
		else
			return $this->params['emptyField'];
		
	}
	
	/**
	 * @param $model - profile model
	 * @param $field - profile fields model item
	 * @param $params - htmlOptions
	 * @return string
	 */
	public function editAttribute($model,$field,$htmlOptions=array()) {
		$list = array();
		if ($this->params['emptyField']) $list[0] = $this->params['emptyField'];
		
		$models = CActiveRecord::model($this->params['modelName'])->findAll();
		foreach ($models as $m)
			$list[$m->id] = (($this->params['optionName'])?$m->getAttribute($this->params['optionName']):$m->id);
		return CHtml::activeDropDownList($model,$field->varname,$list,$htmlOptions=array());
	}
	
}