<?php

class ProfileField extends CActiveRecord
{
	const VISIBLE_ALL=3;
	const VISIBLE_REGISTER_USER=2;
	const VISIBLE_ONLY_OWNER=1;
	const VISIBLE_NO=0;
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
		return '{{profiles_fields}}';
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
			array('varname', 'match', 'pattern' => '/^[a-z_0-9]+$/u','message' => Yii::t("user", "Incorrect symbol's. (a-z)")),
			array('varname', 'unique', 'message' => Yii::t("user", "This field already exists.")),
			array('varname, field_type', 'length', 'max'=>50),
			array('field_size, field_size_min, required, position, visible', 'numerical', 'integerOnly'=>true),
			array('title, match, range, error_message, other_validator, default', 'length', 'max'=>255),
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
			'id' => Yii::t("user", 'Id'),
			'varname' => Yii::t("user", 'Variable name'),
			'title' => Yii::t("user", 'Title'),
			'field_type' => Yii::t("user", 'Field Type'),
			'field_size' => Yii::t("user", 'Field Size'),
			'field_size_min' => Yii::t("user", 'Field Size min'),
			'required' => Yii::t("user", 'Required'),
			'match' => Yii::t("user", 'Match'),
			'range' => Yii::t("user", 'Range'),
			'error_message' => Yii::t("user", 'Error Message'),
			'other_validator' => Yii::t("user", 'Other Validator'),
			'default' => Yii::t("user", 'Default'),
			'position' => Yii::t("user", 'Position'),
			'visible' => Yii::t("user", 'Visible'),
		);
	}
	
	public function scopes()
    {
        return array(
            'forAll'=>array(
                'condition'=>'visible='.self::VISIBLE_ALL,
            ),
            'forUser'=>array(
                'condition'=>'visible>='.self::VISIBLE_REGISTER_USER,
            ),
            'forOwner'=>array(
                'condition'=>'visible>='.self::VISIBLE_ONLY_OWNER,
            ),
            'forRegistration'=>array(
                'condition'=>'required>0',
            ),
            'sort'=>array(
                'order'=>'position',
            ),
            
        );
    }

	
	public function itemAlias($type,$code=NULL) {
		$_items = array(
			'field_type' => array(
				'INTEGER' => Yii::t("user", 'INTEGER'),
				'VARCHAR' => Yii::t("user", 'VARCHAR'),
				'TEXT'=> Yii::t("user", 'TEXT'),
				'DATE'=> Yii::t("user", 'DATE'),
			//	'FLOAT'=> Yii::t("user", 'FLOAT'),
			//	'BOOL'=> Yii::t("user", 'BOOL'),
			//	'BLOB'=> Yii::t("user", 'BLOB'),
			//	'BINARY'=> Yii::t("user", 'BINARY'),
			//	'FILE'=> 'FILE',
			),
			'required' => array(
				'0' => Yii::t("user", 'No'),
				'2' => Yii::t("user", 'No, but show on registration form'),
				'1' => Yii::t("user", 'Yes and show on registration form'),
			),
			'visible' => array(
				self::VISIBLE_ALL => Yii::t("user", 'For all'),
				self::VISIBLE_REGISTER_USER => Yii::t("user", 'Registered users'),
				self::VISIBLE_ONLY_OWNER => Yii::t("user", 'Only owner'),
				'0' => Yii::t("user", 'Hidden'),
			),
		);
		if (isset($code))
			return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
		else
			return isset($_items[$type]) ? $_items[$type] : false;
	}
}