<?php

class ProfileField extends CActiveRecord
{
	const VISIBLE_ALL=3;
	const VISIBLE_REGISTER_USER=2;
	const VISIBLE_ONLY_OWNER=1;
	const VISIBLE_NO=0;
	
	const REQUIRED_NO = 0;
	const REQUIRED_YES_SHOW_REG = 1;
	const REQUIRED_NO_SHOW_REG = 2;
	const REQUIRED_YES_NOT_SHOW_REG = 3;
	
	/**
	 * The followings are the available columns in table 'profiles_fields':
	 * @var integer $id
	 * @var string $varname
	 * @var string $title
	 * @var string $field_type
	 * @var integer $field_size
	 * @var integer $field_size_mix
	 * @var integer $required
	 * @var integer $match
	 * @var string $range
	 * @var string $error_message
	 * @var string $other_validator
	 * @var string $default
	 * @var integer $position
	 * @var integer $visible
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
		return Yii::app()->getModule('user')->tableProfileFields;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('varname, title, field_type', 'required'),
			array('varname', 'match', 'pattern' => '/^[A-Za-z_0-9]+$/u','message' => UserModule::t("Variable name may consist of A-z, 0-9, underscores, begin with a letter.")),
			array('varname', 'unique', 'message' => UserModule::t("This field already exists.")),
			array('varname, field_type', 'length', 'max'=>50),
			array('field_size_min, required, position, visible', 'numerical', 'integerOnly'=>true),
			array('field_size', 'match', 'pattern' => '/^\s*[-+]?[0-9]*\,*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/'),
			array('title, match, error_message, other_validator, default, widget', 'length', 'max'=>255),
			array('range, widgetparams', 'length', 'max'=>5000),
			array('id, varname, title, field_type, field_size, field_size_min, required, match, range, error_message, other_validator, default, widget, widgetparams, position, visible', 'safe', 'on'=>'search'),
		);
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
		return array(
			'id' => UserModule::t('Id'),
			'varname' => UserModule::t('Variable name'),
			'title' => UserModule::t('Title'),
			'field_type' => UserModule::t('Field Type'),
			'field_size' => UserModule::t('Field Size'),
			'field_size_min' => UserModule::t('Field Size min'),
			'required' => UserModule::t('Required'),
			'match' => UserModule::t('Match'),
			'range' => UserModule::t('Range'),
			'error_message' => UserModule::t('Error Message'),
			'other_validator' => UserModule::t('Other Validator'),
			'default' => UserModule::t('Default'),
			'widget' => UserModule::t('Widget'),
			'widgetparams' => UserModule::t('Widget parametrs'),
			'position' => UserModule::t('Position'),
			'visible' => UserModule::t('Visible'),
		);
	}
	
	public function scopes()
    {
        return array(
            'forAll'=>array(
                'condition'=>'visible='.self::VISIBLE_ALL,
                'order'=>'position',
            ),
            'forUser'=>array(
                'condition'=>'visible>='.self::VISIBLE_REGISTER_USER,
                'order'=>'position',
            ),
            'forOwner'=>array(
                'condition'=>'visible>='.self::VISIBLE_ONLY_OWNER,
                'order'=>'position',
            ),
            'forRegistration'=>array(
                'condition'=>'required='.self::REQUIRED_NO_SHOW_REG.' OR required='.self::REQUIRED_YES_SHOW_REG,
                'order'=>'position',
            ),
            'sort'=>array(
                'order'=>'position',
            ),
        );
    }
    
    /**
     * @param $value
     * @return formated value (string)
     */
    public function widgetView($model) {
    	if ($this->widget && class_exists($this->widget)) {
			$widgetClass = new $this->widget;
			
    		$arr = $this->widgetparams;
			if ($arr) {
				$newParams = $widgetClass->params;
				$arr = (array)CJavaScript::jsonDecode($arr);
				foreach ($arr as $p=>$v) {
					if (isset($newParams[$p])) $newParams[$p] = $v;
				}
				$widgetClass->params = $newParams;
			}
			
			if (method_exists($widgetClass,'viewAttribute')) {
				return $widgetClass->viewAttribute($model,$this);
			}
		} 
		return false;
    }
    
    public function widgetEdit($model,$params=array()) {
    	if ($this->widget && class_exists($this->widget)) {
			$widgetClass = new $this->widget;
			
    		$arr = $this->widgetparams;
			if ($arr) {
				$newParams = $widgetClass->params;
				$arr = (array)CJavaScript::jsonDecode($arr);
				foreach ($arr as $p=>$v) {
					if (isset($newParams[$p])) $newParams[$p] = $v;
				}
				$widgetClass->params = $newParams;
			}
			
			if (method_exists($widgetClass,'editAttribute')) {
				return $widgetClass->editAttribute($model,$this,$params);
			}
		}
		return false;
    }
	
	public static function itemAlias($type,$code=NULL) {
		$_items = array(
			'field_type' => array(
				'INTEGER' => UserModule::t('INTEGER'),
				'VARCHAR' => UserModule::t('VARCHAR'),
				'TEXT'=> UserModule::t('TEXT'),
				'DATE'=> UserModule::t('DATE'),
				'FLOAT'=> UserModule::t('FLOAT'),
				'DECIMAL'=> UserModule::t('DECIMAL'),
				'BOOL'=> UserModule::t('BOOL'),
				'BLOB'=> UserModule::t('BLOB'),
				'BINARY'=> UserModule::t('BINARY'),
			),
			'required' => array(
				self::REQUIRED_NO => UserModule::t('No'),
				self::REQUIRED_NO_SHOW_REG => UserModule::t('No, but show on registration form'),
				self::REQUIRED_YES_SHOW_REG => UserModule::t('Yes and show on registration form'),
				self::REQUIRED_YES_NOT_SHOW_REG => UserModule::t('Yes'),
			),
			'visible' => array(
				self::VISIBLE_ALL => UserModule::t('For all'),
				self::VISIBLE_REGISTER_USER => UserModule::t('Registered users'),
				self::VISIBLE_ONLY_OWNER => UserModule::t('Only owner'),
				self::VISIBLE_NO => UserModule::t('Hidden'),
			),
		);
		if (isset($code))
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		else
			return isset($_items[$type]) ? $_items[$type] : false;
	}
	
	/**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('varname',$this->varname,true);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('field_type',$this->field_type,true);
        $criteria->compare('field_size',$this->field_size);
        $criteria->compare('field_size_min',$this->field_size_min);
        $criteria->compare('required',$this->required);
        $criteria->compare('match',$this->match,true);
        $criteria->compare('range',$this->range,true);
        $criteria->compare('error_message',$this->error_message,true);
        $criteria->compare('other_validator',$this->other_validator,true);
        $criteria->compare('default',$this->default,true);
        $criteria->compare('widget',$this->widget,true);
        $criteria->compare('widgetparams',$this->widgetparams,true);
        $criteria->compare('position',$this->position);
        $criteria->compare('visible',$this->visible);

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->controller->module->fields_page_size,
			),
			'sort'=>array(
				'defaultOrder'=>'position',
			),
        ));
    }
}