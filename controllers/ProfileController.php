<?php

class ProfileController extends Controller
{
	public $defaultAction = 'profile';
	public $layout='//layouts/column2';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

    public function actionDashboard()
    {
        Yii::app()->theme = 'buy2game_v1';
        $this->layout = '//layouts/column2';

        $model = $this->loadUser(true);
        $model->create_at=date('d-M-Y',strtotime($model->create_at));
        $model->personalStore->workingHours=$model->getWorkingDays($model);
        $model->feedbackReceived = $model->getLatestFeedback(5);

        /* 6 month interval */
        $interval = -5;
        $startDate =  date('Y-m-d');
        $endDate = date('Y-m', strtotime($interval.' months')).'-01';

        $sql = "SELECT tr.sales
                FROM (
                  SELECT  COUNT(t.id) as sales, MONTHNAME(t.start_date) as month
                  FROM transaction AS t
                  INNER JOIN offer AS o ON  o.id = t.offer_id
                  INNER JOIN personal_store AS s ON s.id = o.store_id
                  WHERE t.start_date < STR_TO_DATE('{$startDate}', '%Y-%m-%d') + INTERVAL 1 DAY AND t.start_date >= STR_TO_DATE('{$endDate}', '%Y-%m-%d') AND s.user_id={$model->id} AND t.status = {$model->transactionStatus['complete']}
                  GROUP BY MONTHNAME(t.start_date)
                ) as tr
                ORDER BY tr.sales DESC
                LIMIT 1";
        $command = Yii::app()->db->createCommand($sql);
        $mostSalesInMonth = $command->queryRow();

//        var_dump($mostSalesInMonth['sales']);
        $salesMonth = array();
        for($i=0; $i >= $interval; $i--)
        {
            $month = date('Y-m', strtotime($i.' months'));

            $sql = "SELECT COUNT(t.id) AS transactions FROM transaction AS t
                INNER JOIN offer AS o ON  o.id = t.offer_id
                INNER JOIN personal_store AS s ON s.id = o.store_id
                WHERE t.start_date LIKE '{$month}%' AND s.user_id={$model->id} AND t.status = {$model->transactionStatus['complete']}
                GROUP BY s.user_id LIMIT 1";
            $command = Yii::app()->db->createCommand($sql);
            $sales = $command->queryRow();
            $salesMonth[$i*(-1)]['sale'] = ($sales['transactions']) ? $sales['transactions'] : 0;
//            var_dump($sales['transactions']);
            $salesMonth[$i*(-1)]['month'] = date('M, y', strtotime($i.' months'));
            $salesMonth[$i*(-1)]['percent'] = round($model->getPercent($sales['transactions'], $mostSalesInMonth['sales']), 0);
        }
        $offerTypes = OfferType::model()->findAll();
        $offer_type_id=OfferType::model()->findByAttributes(array('slug'=>'buy'))->id;

        $position=New CDbCriteria;
        $position->order='position ASC';
        $this->render('dashboard',array(
            'model'=>$model,
            'sales'=>$salesMonth,
            'offerTypes'=>$offerTypes,
            'offer_type_id'=>$offer_type_id,
            'promoBlock'=>PromoBlock::model()->findAll($position),
        ));
    }

    public function actionProfile()
    {
        // The message
        $message = "Line 1\r\nLine 2\r\nLine 3";

// In case any of our lines are larger than 70 characters, we should use wordwrap()
        $message = wordwrap($message, 70, "\r\n");

// Send
        mail('andrejsuibo@gmail.com', 'My Subject', $message);

        Yii::app()->theme = 'buy2game_v1';
        $this->layout = '//layouts/column2';
        $model = $this->loadUser(true);

        $model = $this->loadUser(true);
        $model->create_at=date('d-M-Y',strtotime($model->create_at));
        $model->personalStore->workingHours=$model->getWorkingDays($model);
        $model->feedbackReceived = $model->getLatestFeedback(5);

        $offerTypes = OfferType::model()->findAll();
        $offer_type_id=OfferType::model()->findByAttributes(array('slug'=>'buy'))->id;
        $this->render('profile',array(
            'model'=>$model,
            'offerTypes'=>$offerTypes,
            'offer_type_id'=>$offer_type_id,
        ));
    }

    public function actionFeedback()
    {
        Yii::app()->theme = 'buy2game_v1';
        $this->layout = '//layouts/column2';

        $model = $this->loadUser(true);
        $model->create_at=date('d-M-Y',strtotime($model->create_at));
        $model->personalStore->workingHours=$model->getWorkingDays($model);
        $model->latestFeedback = $model->getLatestFeedback(5);

//        $model->feedbackPositiveAsBuyer = array(
//            'count'=>$model->feedbackPositiveAsBuyer,
//            'percent'=>$model->getPercent($model->feedbackPositiveAsBuyer, $model->feedbackTotalAsBuyer),
//        );
//        $model->feedbackPositiveAsSeller = array(
//            'count'=>$model->feedbackPositiveAsSeller,
//            'percent'=>$model->getPercent($model->feedbackPositiveAsSeller, $model->feedbackTotalAsSeller),
//        );
//        $model->feedbackNeutralAsSeller = array(
//            'count'=>$model->feedbackNeutralAsSeller,
//            'percent'=>$model->getPercent($model->feedbackNeutralAsSeller, $model->feedbackTotalAsSeller),
//        );
//        $model->feedbackNegativeAsSeller = array(
//            'count'=>$model->feedbackNegativeAsSeller,
//            'percent'=>$model->getPercent($model->feedbackNegativeAsSeller, $model->feedbackTotalAsSeller),
//        );

        $feedbackFilters = array();
        $selectedFeedbackType = 'all';
        $selectedRatingType = 'all';
        $selectedPeriod = 1;
        if(isset($_GET['UserFeedback']))
        {
            $feedbackFilters = $_GET['UserFeedback'];

            if(isset($_GET['UserFeedback']['feedback_type']))
                $selectedFeedbackType = $_GET['UserFeedback']['feedback_type'];

            if(isset($_GET['UserFeedback']['rating_type']))
                $selectedRatingType = $_GET['UserFeedback']['rating_type'];

            if(isset($_GET['UserFeedback']['period']))
                $selectedPeriod = (int)$_GET['UserFeedback']['period'];
        }

        $feedback=new UserFeedback('search');
        $feedback->unsetAttributes();  // clear any default values
        $feedback->attributes=$feedbackFilters;


        $offerTypes = OfferType::model()->findAll();
        $offer_type_id=OfferType::model()->findByAttributes(array('slug'=>'buy'))->id;
        $this->render('feedback',array(
            'model'=>$model,
            'feedback'=>$feedback,
            'selectedFeedbackType'=>$selectedFeedbackType,
            'selectedRatingType'=>$selectedRatingType,
            'selectedPeriod'=>$selectedPeriod,
            'offerTypes'=>$offerTypes,
            'offer_type_id'=>$offer_type_id,
        ));
    }

    public function actionMessagesIndex()
    {
        Yii::app()->theme = 'buy2game_v1';
        $this->layout = '//layouts/column2';
        $model = $this->loadUser(true);

        $model = $this->loadUser(true);
        $model->create_at=date('d-M-Y',strtotime($model->create_at));
        $model->personalStore->workingHours=$model->getWorkingDays($model);
        $model->feedbackReceived = $model->getLatestFeedback(5);

        $offerTypes = OfferType::model()->findAll();
        $offer_type_id=OfferType::model()->findByAttributes(array('slug'=>'buy'))->id;
        $this->render('//user/messages/allMessages',array(
            'model'=>$model,
            'offerTypes'=>$offerTypes,
            'offer_type_id'=>$offer_type_id,
        ));
    }
    public function actionTickets()
    {
        Yii::app()->theme = 'buy2game_v1';
        $this->layout = '//layouts/column2';
        $model = $this->loadUser(true);

        $model = $this->loadUser(true);
        $model->create_at=date('d-M-Y',strtotime($model->create_at));
        $model->personalStore->workingHours=$model->getWorkingDays($model);
        $model->feedbackReceived = $model->getLatestFeedback(5);

        $offerTypes = OfferType::model()->findAll();
        $offer_type_id=OfferType::model()->findByAttributes(array('slug'=>'buy'))->id;
        $this->render('//user/tickets/tickets',array(
            'model'=>$model,
            'offerTypes'=>$offerTypes,
            'offer_type_id'=>$offer_type_id,
        ));
    }
    public function actionTicket()
    {
        Yii::app()->theme = 'buy2game_v1';
        $this->layout = '//layouts/column2';
        $model = $this->loadUser(true);

        $model = $this->loadUser(true);
        $model->create_at=date('d-M-Y',strtotime($model->create_at));
        $model->personalStore->workingHours=$model->getWorkingDays($model);
        $model->feedbackReceived = $model->getLatestFeedback(5);

        $offerTypes = OfferType::model()->findAll();
        $offer_type_id=OfferType::model()->findByAttributes(array('slug'=>'buy'))->id;
        $this->render('//user/tickets/openTicket',array(
            'model'=>$model,
            'offerTypes'=>$offerTypes,
            'offer_type_id'=>$offer_type_id,
        ));
    }


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionEdit()
	{
		$model = $this->loadUser();
		$profile=$model->profile;
		
		// ajax validator
		if(isset($_POST['ajax']) && $_POST['ajax']==='profile-form')
		{
			echo UActiveForm::validate(array($model,$profile));
			Yii::app()->end();
		}
		
		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$profile->attributes=$_POST['Profile'];
			
			if($model->validate()&&$profile->validate()) {
				$model->save();
				$profile->save();
				Yii::app()->user->setFlash('profileMessage',UserModule::t("Changes is saved."));
				$this->redirect(array('/user/profile'));
			} else $profile->validate();
		}

		$this->render('edit',array(
			'model'=>$model,
			'profile'=>$profile,
		));
	}
	
	/**
	 * Change password
	 */
	public function actionChangepassword() {
		$model = new UserChangePassword;
		if (Yii::app()->user->id) {
			
			// ajax validator
			if(isset($_POST['ajax']) && $_POST['ajax']==='changepassword-form')
			{
				echo UActiveForm::validate($model);
				Yii::app()->end();
			}
			
			if(isset($_POST['UserChangePassword'])) {
					$model->attributes=$_POST['UserChangePassword'];
					if($model->validate()) {
						$new_password = User::model()->notsafe()->findbyPk(Yii::app()->user->id);
						$new_password->password = UserModule::encrypting($model->password);
						$new_password->activkey=UserModule::encrypting(microtime().$model->password);
						$new_password->save();
						Yii::app()->user->setFlash('profileMessage',UserModule::t("New password is saved."));
						$this->redirect(array("profile"));
					}
			}
			$this->render('changepassword',array('model'=>$model));
	    }
	}

    public function  actionShowFeedbackTypeFilters(){

        if(isset($_POST['feedback_type']) && isset($_POST['interval']))
        {
            if(isset($_POST['rating_type']))
                $selectedType = $_POST['rating_type'];
            else
                $selectedType = 'all';

            Yii::app()->theme = 'buy2game_v1';
            $feedback_type = ($_POST['feedback_type'] != '')? $_POST['feedback_type'] : 'all';
            $interval = ($_POST['interval'] != '')? $_POST['interval'] : '1';

            $this->renderPartial('//user/feedback/_feedbackFilters', array(
                'selectedType'=>$selectedType,
                'interval'=>$interval,
                'feedback_type'=>$feedback_type,
            ));
        }
    }
    public function  actionShowFeedbackInterval()
    {
        if(isset($_POST['interval']) && (int)$_POST['interval'] > 0)
            $interval = (int)$_POST['interval'];
        else
            $interval = 1;

        var_dump($interval);
        Yii::app()->theme = 'buy2game_v1';
        $this->renderPartial('//user/feedback/_feedbackPeriod', array('interval'=>$interval));
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadUser()
	{
		if($this->_model===null)
		{
			if(Yii::app()->user->id)
				$this->_model=Yii::app()->controller->module->user();
			if($this->_model===null)
				$this->redirect(Yii::app()->controller->module->loginUrl);
		}
		return $this->_model;
	}
}