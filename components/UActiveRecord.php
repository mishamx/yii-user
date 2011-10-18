<?php
class UActiveRecord extends CActiveRecord
{
        /**
         * Extends setAttributes to handle active date fields
         *
         * @param $values array
         * @param $safeOnly boolean
         */
        public function setAttributes($values,$safeOnly=true)
        {
			foreach ($this->widgetAttributes() as $fieldName=>$className) {
				if (isset($values[$fieldName])&&class_exists($className)) {
					$class = new $className;
					$arr = $this->widgetParams($fieldName);
					if ($arr) {
						$newParams = $class->params;
						$arr = (array)CJavaScript::jsonDecode($arr);
						foreach ($arr as $p=>$v) {
							if (isset($newParams[$p])) $newParams[$p] = $v;
						}
						$class->params = $newParams;
					}
					if (method_exists($class,'setAttributes')) {
						$values[$fieldName] = $class->setAttributes($values[$fieldName],$this,$fieldName); 
					}
				}
			}
			parent::setAttributes($values,$safeOnly);
		}
		
		public function behaviors(){
			return Yii::app()->getModule('user')->getBehaviorsFor(get_class($this));
		}
}