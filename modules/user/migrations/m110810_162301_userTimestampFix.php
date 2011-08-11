<?php

class m110810_162301_userTimestampFix extends CDbMigration
{
	public function safeUp()
	{
        if (!Yii::app()->getModule('user')) {
            echo "\n\nAdd to console.php :\n"
                 ."'modules'=>array(\n"
                 ."...\n"
                 ."    'user'=>array(\n"
                 ."        ... # copy settings from main config\n"
                 ."    ),\n"
                 ."...\n"
                 ."),\n"
                 ."\n";
            return false;
        }

        $this->addColumn(Yii::app()->getModule('user')->tableUsers,'create_at',"TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->addColumn(Yii::app()->getModule('user')->tableUsers,'lastvisit_at',"TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $this->execute("UPDATE {{users}} SET create_at = FROM_UNIXTIME(createtime), lastvisit_at = FROM_UNIXTIME(lastvisit)");
        $this->dropColumn(Yii::app()->getModule('user')->tableUsers,'createtime');
        $this->dropColumn(Yii::app()->getModule('user')->tableUsers,'lastvisit');
	}

	public function safeDown()
	{
        $this->addColumn(Yii::app()->getModule('user')->tableUsers,'createtime',"int(10) NOT NULL");
        $this->addColumn(Yii::app()->getModule('user')->tableUsers,'lastvisit',"int(10) NOT NULL");
        $this->execute("UPDATE {{users}} SET createtime = UNIX_TIMESTAMP(create_at), lastvisit = UNIX_TIMESTAMP(lastvisit_at)");
        $this->dropColumn(Yii::app()->getModule('user')->tableUsers,'create_at');
        $this->dropColumn(Yii::app()->getModule('user')->tableUsers,'lastvisit_at');
	}
}