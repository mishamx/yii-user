<?php

class UWjuidate {
	
	/**
	 * @var array
	 */
	public $params = array(
		'ui-theme'=>'base',
		'language'=>'en',
	);
	
	/**
	 * Initialization
	 * @return array
	 */
	public function init() {
		return array(
			'name'=>__CLASS__,
			'label'=>UserModule::t('jQueryUI datepicker'),
			'fieldType'=>array('DATE','VARCHAR'),
			'params'=>$this->params,
			'paramsLabels' => array(
				'dateFormat'=>UserModule::t('Date format'),
			),
		);
	}
	
	/**
	 * @param $model - profile model
	 * @param $field - profile fields model item
	 * @return string
	 */
	public function viewAttribute($model,$field) {
		return $model->getAttribute($field->varname);
	}
	
	/**
	 * @param $model - profile model
	 * @param $field - profile fields model item
	 * @param $params - htmlOptions
	 * @return string
	 */
	public function editAttribute($model,$field,$htmlOptions=array()) {
		if (!isset($htmlOptions['size'])) $htmlOptions['size'] = 60;
		if (!isset($htmlOptions['maxlength'])) $htmlOptions['maxlength'] = (($field->field_size)?$field->field_size:10);
		if (!isset($htmlOptions['id'])) $htmlOptions['id'] = get_class($model).'_'.$field->varname;
		
		$id = $htmlOptions['id'];
		$options['dateFormat'] = 'yy-mm-dd';
		$options=CJavaScript::encode($options);
		
		$basePath=Yii::getPathOfAlias('user.views.asset');
		$baseUrl=Yii::app()->getAssetManager()->publish($basePath);
		$cs = Yii::app()->getClientScript();
		$cs->registerCssFile($baseUrl.'/css/'.$this->params['ui-theme'].'/jquery-ui.css');
		$cs->registerScriptFile($baseUrl.'/js/jquery-ui.min.js');
		
		$language = $this->params['language'];
		if ($language!='en') {
			$js = "jQuery('#{$id}').datepicker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['{$language}'], {$options}));";
			$cs->registerScriptFile($baseUrl.'/js/jquery-ui-i18n.min.js');
		} else $js = "jQuery('#{$id}').datepicker({$options});";

		$cs->registerScript('ProfileFieldController'.'#'.$id, $js);
		
		return CHtml::activeTextField($model,$field->varname,$htmlOptions);
	}
	
}