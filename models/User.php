<?php

class User extends CActiveRecord
{
	const STATUS_NOACTIVE=0;
	const STATUS_ACTIVE=1;
	const STATUS_BANNED=-1;
	
	//TODO: Delete for next version (backward compatibility)
	const STATUS_BANED=-1;

    public $latestFeedback;
    public $transactionStatus = array(
        'new' => 0,
        'complete' => 1,
        'open' => 0,
        'feedback' => 1,
    );

	/**
	 * The followings are the available columns in table 'users':
	 * @var integer $id
	 * @var string $username
	 * @var string $password
	 * @var string $email
	 * @var string $activkey
	 * @var integer $createtime
	 * @var integer $lastvisit
	 * @var integer $superuser
	 * @var integer $status
     * @var timestamp $create_at
     * @var timestamp $lastvisit_at
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
		return Yii::app()->getModule('user')->tableUsers;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.CConsoleApplication
		return ((get_class(Yii::app())=='CConsoleApplication' || (get_class(Yii::app())!='CConsoleApplication' && Yii::app()->getModule('user')->isAdmin()))?array(
			array('username', 'length', 'max'=>20, 'min' => 3,'message' => UserModule::t("Incorrect username (length between 3 and 20 characters).")),
			array('password', 'length', 'max'=>128, 'min' => 4,'message' => UserModule::t("Incorrect password (minimal length 4 symbols).")),
			array('email', 'email'),
			array('username', 'unique', 'message' => UserModule::t("This user's name already exists.")),
			array('email', 'unique', 'message' => UserModule::t("This user's email address already exists.")),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => UserModule::t("Incorrect symbols (A-z0-9).")),
			array('status', 'in', 'range'=>array(self::STATUS_NOACTIVE,self::STATUS_ACTIVE,self::STATUS_BANNED)),
			array('superuser', 'in', 'range'=>array(0,1)),
            array('create_at', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
            array('lastvisit_at', 'default', 'value' => '0000-00-00 00:00:00', 'setOnEmpty' => true, 'on' => 'insert'),
			array('username, email, superuser, status', 'required'),
			array('superuser, status', 'numerical', 'integerOnly'=>true),
			array('id, username, password, email, activkey, create_at, lastvisit_at, superuser, status', 'safe', 'on'=>'search'),
		):((Yii::app()->user->id==$this->id)?array(
			array('username, email', 'required'),
			array('username', 'length', 'max'=>20, 'min' => 3,'message' => UserModule::t("Incorrect username (length between 3 and 20 characters).")),
			array('email', 'email'),
			array('username', 'unique', 'message' => UserModule::t("This user's name already exists.")),
			array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => UserModule::t("Incorrect symbols (A-z0-9).")),
			array('email', 'unique', 'message' => UserModule::t("This user's email address already exists.")),
		):array()));
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
        return array(
            'profile' => array(self::HAS_ONE, 'Profile', 'user_id'),
            'personalStore' => array(self::HAS_ONE, 'PersonalStore', 'user_id'),
            'feedbackReceived' => array(self::HAS_MANY, 'UserFeedback', 'user_to_id'),
            'feedbackGiven' => array(self::HAS_MANY, 'UserFeedback', 'user_from_id'),

            'avgRatingAsSeller' => array(self::STAT, 'UserFeedback', 'user_to_id', 'select'=>'AVG(rating)', 'condition'=>'t.user_to_id = store.user_id',
                'join'=>'
                    INNER JOIN transaction ON transaction.id = t.transaction_id
                    INNER JOIN offer ON offer.id = transaction.offer_id
                    INNER JOIN personal_store AS store ON store.id = offer.store_id'
            ),

            'feedbackPositiveTotal' => array(self::STAT, 'UserFeedback', 'user_to_id', 'condition'=>'rating>3'),
            'feedbackPositiveAsBuyer' => array(self::STAT, 'UserFeedback', 'user_to_id', 'condition'=>'rating>3 AND t.user_to_id = transaction.buyer_id',
                'join'=>'INNER JOIN transaction ON transaction.id = t.transaction_id'
            ),
            'feedbackNeutralAsBuyer' => array(self::STAT, 'UserFeedback', 'user_to_id', 'condition'=>'rating=3 AND t.user_to_id = transaction.buyer_id',
                'join'=>'INNER JOIN transaction ON transaction.id = t.transaction_id'
            ),
            'feedbackNegativeAsBuyer' => array(self::STAT, 'UserFeedback', 'user_to_id', 'condition'=>'rating<3 AND t.user_to_id = transaction.buyer_id',
                'join'=>'INNER JOIN transaction ON transaction.id = t.transaction_id'
            ),

            'feedbackPositiveAsSeller' => array(self::STAT, 'UserFeedback', 'user_to_id', 'condition'=>'rating>3 AND t.user_to_id = store.user_id',
                'join'=>'
                    INNER JOIN transaction ON transaction.id = t.transaction_id
                    INNER JOIN offer ON offer.id = transaction.offer_id
                    INNER JOIN personal_store AS store ON store.id = offer.store_id'
            ),
            'feedbackNeutralAsSeller' => array(self::STAT, 'UserFeedback', 'user_to_id', 'condition'=>'rating=3 AND t.user_to_id = store.user_id',
                'join'=>'
                    INNER JOIN transaction ON transaction.id = t.transaction_id
                    INNER JOIN offer ON offer.id = transaction.offer_id
                    INNER JOIN personal_store AS store ON store.id = offer.store_id'
            ),
            'feedbackNegativeAsSeller' => array(self::STAT, 'UserFeedback', 'user_to_id', 'condition'=>'rating<3 AND t.user_to_id = store.user_id',
                'join'=>'
                    INNER JOIN transaction ON transaction.id = t.transaction_id
                    INNER JOIN offer ON offer.id = transaction.offer_id
                    INNER JOIN personal_store AS store ON store.id = offer.store_id'
            ),

            'feedbackTotalAsBuyer' => array(self::STAT, 'UserFeedback', 'user_to_id', 'condition'=>'t.user_to_id = transaction.buyer_id',
                'join'=>'INNER JOIN transaction ON transaction.id = t.transaction_id'
            ),
            'feedbackTotalAsSeller' => array(self::STAT, 'UserFeedback', 'user_to_id', 'condition'=>'t.user_to_id = store.user_id',
                'join'=>'
                    INNER JOIN transaction ON transaction.id = t.transaction_id
                    INNER JOIN offer ON offer.id = transaction.offer_id
                    INNER JOIN personal_store AS store ON store.id = offer.store_id'
            ),
            'feedbackTotal' => array(self::STAT, 'UserFeedback', 'user_to_id'),

            'new_transactions' => array(self::STAT, 'PersonalStore', 'user_id',
                'select'=>'COUNT(transaction.id)',
                'condition'=>"transaction.status = {$this->transactionStatus['new']}",
                'join'=>'
                    INNER JOIN offer ON offer.store_id = t.id
                    INNER JOIN transaction ON transaction.offer_id = offer.id'
            ),
            'open_transactions' => array(self::STAT, 'PersonalStore', 'user_id',
                'select'=>'COUNT(transaction.id)',
                'condition'=>"transaction.status = {$this->transactionStatus['open']}",
                'join'=>'
                    INNER JOIN offer ON offer.store_id = t.id
                    INNER JOIN transaction ON transaction.offer_id = offer.id'
            ),
            'complete_transactions' => array(self::STAT, 'PersonalStore', 'user_id',
                'select'=>'COUNT(transaction.id)',
                'condition'=>"transaction.status = {$this->transactionStatus['complete']}",
                'join'=>'
                    INNER JOIN offer ON offer.store_id = t.id
                    INNER JOIN transaction ON transaction.offer_id = offer.id'
            ),
            'seller_feedback_transactions' => array(self::STAT, 'PersonalStore', 'user_id',
                'select'=>'COUNT(transaction.id)',
                'condition'=>"transaction.status = {$this->transactionStatus['feedback']}",
                'join'=>'
                    INNER JOIN offer ON offer.store_id = t.id
                    INNER JOIN transaction ON transaction.offer_id = offer.id'
            ),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => UserModule::t("Id"),
			'username'=>UserModule::t("username"),
			'password'=>UserModule::t("password"),
			'verifyPassword'=>UserModule::t("Retype Password"),
			'email'=>UserModule::t("E-mail"),
			'verifyCode'=>UserModule::t("Verification Code"),
			'activkey' => UserModule::t("activation key"),
			'createtime' => UserModule::t("Registration date"),
			'create_at' => UserModule::t("Registration date"),
			
			'lastvisit_at' => UserModule::t("Last visit"),
			'superuser' => UserModule::t("Superuser"),
			'status' => UserModule::t("Status"),
		);
	}
	
	public function scopes()
    {
        return array(
            'active'=>array(
                'condition'=>'status='.self::STATUS_ACTIVE,
            ),
            'notactive'=>array(
                'condition'=>'status='.self::STATUS_NOACTIVE,
            ),
            'banned'=>array(
                'condition'=>'status='.self::STATUS_BANNED,
            ),
            'superuser'=>array(
                'condition'=>'superuser=1',
            ),
            'notsafe'=>array(
            	'select' => 'id, username, password, email, activkey, create_at, lastvisit_at, superuser, status',
            ),
        );
    }
	
	public function defaultScope()
    {
        return CMap::mergeArray(Yii::app()->getModule('user')->defaultScope,array(
            'alias'=>'user',
            'select' => 'user.id, user.username, user.email, user.create_at, user.lastvisit_at, user.superuser, user.status',
        ));
    }
	
	public static function itemAlias($type,$code=NULL) {
		$_items = array(
			'UserStatus' => array(
				self::STATUS_NOACTIVE => UserModule::t('Not active'),
				self::STATUS_ACTIVE => UserModule::t('Active'),
				self::STATUS_BANNED => UserModule::t('Banned'),
			),
			'AdminStatus' => array(
				'0' => UserModule::t('No'),
				'1' => UserModule::t('Yes'),
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
        $criteria->compare('username',$this->username,true);
        $criteria->compare('password',$this->password);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('activkey',$this->activkey);
        $criteria->compare('create_at',$this->create_at);
        $criteria->compare('lastvisit_at',$this->lastvisit_at);
        $criteria->compare('superuser',$this->superuser);
        $criteria->compare('status',$this->status);

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
        	'pagination'=>array(
				'pageSize'=>Yii::app()->getModule('user')->user_page_size,
			),
        ));
    }

    public function getCreatetime() {
        return strtotime($this->create_at);
    }

    public function setCreatetime($value) {
        $this->create_at=date('Y-m-d H:i:s',$value);
    }

    public function getLastvisit() {
        return strtotime($this->lastvisit_at);
    }

    public function setLastvisit($value) {
        $this->lastvisit_at=date('Y-m-d H:i:s',$value);
    }

    public function afterSave() {
        if (get_class(Yii::app())=='CWebApplication'&&Profile::$regMode==false) {
            Yii::app()->user->updateSession($this->id);
        }
        return parent::afterSave();
    }

    public function getWorkingDays($model)
    {
        $workingDays = array();
        $week = array('mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday');
        foreach($model->personalStore->workingHours as $day=>$hours)
        {
            if(isset($week[$day]))
            {
                $workingDays[$day]=array($week[$day],$hours);
            }
        }
        return $workingDays;
    }
    public function getLatestFeedback($limit)
    {
        $criteria = new CDbCriteria;
        $criteria->limit = $limit;
        $criteria->offset = 0;
        $criteria->condition = 'text != ""';
        $criteria->order = 'add_date ASC';
        return UserFeedback::model()->findAll($criteria);
    }

    public function getPercent($count, $total)
    {
        return ($count && $count > 0) ? round(($count / $total) *100, 1) : 0.00;
    }

    public function getFeedbackInIntervalCount($interval, $type = '> 0', $feedback = 'all')
    {
        $startDate =  date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("2009-01-31"));
        if(array_key_exists('month',$interval))
        {
            $interval = ($interval['month']-1)*-1;

            $endDate = date('Y-m-d', strtotime("first day of {$interval} month"));

        }
        $typeCondition = '';
        if($feedback == 'seller')
        {
            $typeCondition=' AND f.user_to_id = s.user_id';
        }
        elseif($feedback == 'buyer')
        {
            $typeCondition=' AND f.user_to_id = t.buyer_id';
        }

        $id = Yii::app()->user->id;

        $sql = "SELECT COUNT(*) AS feedback
                FROM user_feedback AS f
                INNER JOIN transaction AS t ON t.id = f.transaction_id
                INNER JOIN offer AS o ON o.id = t.offer_id
                INNER JOIN personal_store AS s ON s.id = o.store_id
                WHERE f.add_date < STR_TO_DATE('{$startDate}', '%Y-%m-%d') + INTERVAL 1 DAY AND f.add_date >= STR_TO_DATE('{$endDate}', '%Y-%m-%d')
                      AND s.user_id = {$id}
                      AND f.rating {$type}
                      {$typeCondition}
                GROUP BY f.user_to_id
                LIMIT 1";
        $command = Yii::app()->db->createCommand($sql);
        $feedbackCount = $command->queryRow()['feedback'];

        return ($feedbackCount && $feedbackCount > 0) ? $feedbackCount : 0;
    }

    public function getFeedbackColumn($data, $column)
    {
        if($column == 1)
        {
            if($data->rating > 3)
                $smily = 'fa-smile-o';
            elseif($data->rating == 3)
                $smily = 'fa-meh-o';
            elseif($data->rating < 3)
                $smily = 'fa-frown-o';

            return "<i class='fa {$smily}'></i>{$data->text}<div>{$data->transaction->offer->name}</div>";
        }
        elseif($column == 3)
        {
            return date('d.m.Y', strtotime($data->add_date))."<div>".date('H:i', strtotime($data->add_date))."</div>";
        }
    }

    /**
     * Returns User model by its email
     *
     * @param string $email
     * @access public
     * @return User
     */
    public function findByEmail($email)
    {
        return self::model()->findByAttributes(array('email' => $email));
    }
}