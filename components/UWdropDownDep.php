<?php
/**
 * UWdropDownDep Widget
 *
 * @author Juan Fernando Gaviria <juan.gaviria@dsotogroup.com>
 * @link http://www.dsotogroup.com/
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @version $Id: UWdropDownDep.php 123 2013-01-26 10:04:33Z juan.gaviria $
 */

class UWdropDownDep {
  
	public $params = array(
		'modelName'=>'',
		'optionName'=>'',
		'emptyField'=>'',
		'relationName'=>'',
		'modelDestName'=>'',
		'destField'=>'',
		'optionDestName'=>'',
	);
	
	/**
	 * Widget initialization
	 * @return array
	 */
	public function init() {
		return array(
			'name'=>__CLASS__,
			'label'=>UserModule::t('DropDown List Dependent',array(),__CLASS__),
			'fieldType'=>array('INTEGER'),
			'params'=>$this->params,
			'paramsLabels' => array(
				'modelName'=>UserModule::t('Model Name',array(),__CLASS__),
				'optionName'=>UserModule::t('Lable field name',array(),__CLASS__),
				'emptyField'=>UserModule::t('Empty item name',array(),__CLASS__),
				'relationName'=>UserModule::t('Profile model relation name',array(),__CLASS__),
				'modelDestName'=>UserModule::t('Model Dest Name',array(),__CLASS__),
				'destField'=>UserModule::t('Dest Field',array(),__CLASS__),
				'optionDestName'=>UserModule::t('Label Dest field name',array(),__CLASS__),
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
			return (($this->params['optionName'])?$m->getAttribute($this->params['optionName']):$m->getAttribute($m->tableSchema->primaryKey));
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
			$list[$m->getAttribute($m->tableSchema->primaryKey)] = 
                                (($this->params['optionName'])?$m->getAttribute($this->params['optionName']):$m->getAttribute($m->tableSchema->primaryKey));
		return CHtml::activeDropDownList($model,$field->varname,$list,$htmlOptions=array(
				'ajax'=>array(
						'type'=>'POST',
						'url'=>CController::createUrl('/user/profileField/getDroDownDepValues'),
						'data'=>array('model'=>$this->params['modelDestName'], 'field_dest'=>$this->params['destField'], 'varname'=>$field->varname, $field->varname=>'js:this.value', 'optionDestName'=>$this->params['optionDestName']),
						'success'=>'function(data){
        						$("#ajax_loader").hide();
        						$("#Profile_'.$this->params['destField'].'").html(data)
        				}',
						'beforeSend'=>'function(){
	        					$("#ajax_loader").fadeIn();
	        			}',
				)
				));
	}
	
}
