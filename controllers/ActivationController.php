<?php

class ActivationController extends Controller
{
	public $defaultAction = 'activation';

	
	/**
	 * Activation user account
	 */
	public function actionActivation () {
		$email = $_GET['email'];
		$activkey = $_GET['activkey'];
		if ($email&&$activkey) {
			$find = User::model()->notsafe()->findByAttributes(array('email'=>$email));
			if (isset($find)&&$find->status) {
                Yii::app()->user->setFlash('success', UserModule::t("You account is active."));
                $this->redirect(Yii::app()->user->returnUrl);
			} elseif(isset($find->activkey) && ($find->activkey==$activkey)) {
				$find->activkey = UserModule::encrypting(microtime());
				$find->status = 1;
                if($find->save()){
                    /*
                     * After user account has been verified, add default user Personal store info
                     */
                    $personalStore = new PersonalStore;
                    $personalStore->user_id = $find->id;
                    if (!$personalStore->save())
                        print_r($personalStore->errors);
                    else
                    {
                        $workingHours = new WorkingHours;
                        $workingHours->store_id = $personalStore->id;
                        if (!$workingHours->save())
                            print_r($workingHours->errors);
                    }
                    Yii::app()->user->setFlash('success', UserModule::t("You account is active."));
                    $this->redirect(Yii::app()->user->returnUrl);
                }
			} else {
                Yii::app()->user->setFlash('error', UserModule::t("Incorrect activation URL."));
                $this->redirect(Yii::app()->user->returnUrl);
			}
		} else {
            Yii::app()->user->setFlash('error', UserModule::t("Incorrect activation URL."));
            $this->redirect(Yii::app()->user->returnUrl);
		}

	}

}