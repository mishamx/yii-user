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
			array('varname', 'match', 'pattern' => '/^[a-z_0-9]+$/u','message' => UserModule::t("Incorrect symbol's. (a-z)")),
			array('varname', 'unique', 'message' => UserModule::t("This field already exists.")),
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
			'position' => UserModule::t('Position'),
			'visible' => UserModule::t('Visible'),
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
				'INTEGER' => UserModule::t('INTEGER'),
				'VARCHAR' => UserModule::t('VARCHAR'),
				'TEXT'=> UserModule::t('TEXT'),
				'DATE'=> UserModule::t('DATE'),
			//	'FLOAT'=> UserModule::t('FLOAT'),
			//	'BOOL'=> UserModule::t('BOOL'),
			//	'BLOB'=> UserModule::t('BLOB'),
			//	'BINARY'=> UserModule::t('BINARY'),
			//	'FILE'=> 'FILE',
			),
			'required' => array(
				'0' => UserModule::t('No'),
				'2' => UserModule::t('No, but show on registration form'),
				'1' => UserModule::t('Yes and show on registration form'),
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
}