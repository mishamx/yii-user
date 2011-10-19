<?php

class UWfile {
	
	/**
	 * @var array
	 * @name widget parametrs
	 */
	public $params = array('path'=>'assets');
	
	/**
	 * Widget initialization
	 * @return array
	 */
	public function init() {
		return array(
			'name'=>__CLASS__,
			'label'=>UserModule::t('File field'),
			'fieldType'=>array('VARCHAR'),
			'params'=>$this->params,
			'paramsLabels' => array(
				'path'=>UserModule::t('Upload path'),
			),
			'other_validator'=>array(
				'file'=>array(
					'allowEmpty'=>array('','false','true'),
					'maxFiles'=>'',
					'maxSize'=>'',
					'minSize'=>'',
					'tooLarge'=>'',
					'tooMany'=>'',
					'tooSmall'=>'',
					'types'=>'',
					'wrongType'=>'',
				),
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
		$value = CUploadedFile::getInstance($model,$field_varname);
		
		if ($value) {
			$old_file = $model->getAttribute($field_varname);
			$file_name = $this->params['path'].'/'.$value->name;
			if (file_exists($file_name)) {
				$file_name = str_replace('.'.$value->extensionName,'-'.time().'.'.$value->extensionName,$file_name);
			}
			if ($model->validate()) {
				if ($old_file&&file_exists($old_file))
					unlink($old_file);
				$value->saveAs($file_name);
			}
			$value = $file_name;
		} else {
			if (isset($_POST[get_class($model)]['uwfdel'][$field_varname])&&$_POST[get_class($model)]['uwfdel'][$field_varname]) {
				$old_file = $model->getAttribute($field_varname);
				if ($old_file&&file_exists($old_file))
					unlink($old_file);
				$value='';
			} else {
				$value = $model->getAttribute($field_varname);
			}
		}
		return $value;
	}
		
	/**
	 * @param $value
	 * @return string
	 */
	public function viewAttribute($model,$field) {
		$file = $model->getAttribute($field->varname);
		if ($file) {
			$file = Yii::app()->baseUrl.'/'.$file;
			return CHtml::link($file,$file);
		} else
			return '';
	}
		
	/**
	 * @param $value
	 * @return string
	 */
	public function editAttribute($model,$field,$params=array()) {
		if (!isset($params['options'])) $params['options'] = array();
		$options = $params['options'];
		unset($params['options']);
		
		return CHtml::activeFileField($model,$field->varname,$params)
		.(($model->getAttribute($field->varname))?'<br/>'.CHtml::activeCheckBox($model,'[uwfdel]'.$field->varname,$params)
		.' '.CHtml::activeLabelEx($model,'[uwfdel]'.$field->varname,array('label'=>UserModule::t('Delete file'),'style'=>'display:inline;')):'')
		;
	}
	
}