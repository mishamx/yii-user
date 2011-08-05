<?php

class WebUser extends CWebUser
{

    public function getRole()
    {
        return $this->getState('__role');
    }
    
    public function getId()
    {
        return $this->getState('__id') ? $this->getState('__id') : 0;
    }

    protected function afterLogin($fromCookie)
	{
        parent::afterLogin($fromCookie);
        $this->updateSession();
	}

    public function updateSession() {
        $user = Yii::app()->getModule('user')->user($this->id);
        $userAttributes = CMap::mergeArray(array(
                                                'email'=>$user->email,
                                                'username'=>$user->username,
                                                'createtime'=>$user->createtime,
                                                'lastvisit'=>$user->lastvisit,
                                           ),$user->profile->getAttributes());
        foreach ($userAttributes as $attrName=>$attrValue) {
            $this->setState($attrName,$attrValue);
        }
    }

    public function model($id=0) {
        return Yii::app()->getModule('user')->user($id);
    }

    public function user($id=0) {
        return $this->model($id);
    }

    public function getUserByName($username) {
        return Yii::app()->getModule('user')->getUserByName($username);
    }

    public function getAdmins() {
        return Yii::app()->getModule('user')->getAdmins();
    }

    public function isAdmin() {
        return Yii::app()->getModule('user')->isAdmin();
    }

}