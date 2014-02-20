<?php
/**
 * @package Buy2Game\modules\user\components
 */
class LoginWidget extends CWidget
{

    public function run()
    {
        $model = new UserLogin();

        if(!Yii::app()->user->isGuest){
            $model=User::model()->findByPk(Yii::app()->user->id);
        }
        $this->render('loginWidget', array('model' => $model));
    }
}
?>