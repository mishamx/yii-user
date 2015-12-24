<?php

class m121001_001625_userSalt extends CDbMigration
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

        switch ($this->dbType()) {
            case "mysql":
                $this->addColumn(Yii::app()->getModule('user')->tableUsers,'salt',"VARCHAR(40) NULL DEFAULT NULL AFTER `password`");
                break;
            case "sqlite":
            default:
				$this->addColumn(Yii::app()->getModule('user')->tableUsers,'salt',"VARCHAR(40) DEFAULT NULL");
                break;
        }
	}

	public function safeDown()
	{
		switch ($this->dbType()) {
			case "mysql":
				$this->dropColumn(Yii::app()->getModule('user')->tableUsers,'salt');
				break;
			case "sqlite":
				
				$this->execute('ALTER TABLE `'.Yii::app()->getModule('user')->tableUsers.'` RENAME TO `'.__CLASS__.'_'.Yii::app()->getModule('user')->tableUsers.'`');
				$this->createTable(Yii::app()->getModule('user')->tableUsers, array(
					"id" => "pk",
					"username" => "varchar(20) NOT NULL",
					"password" => "varchar(128) NOT NULL",
					"email" => "varchar(128) NOT NULL",
					"activkey" => "varchar(128) NOT NULL",
					"superuser" => "int(1) NOT NULL",
					"status" => "int(1) NOT NULL",
					"create_at" => "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
					"lastvisit_at" => "TIMESTAMP",
				));
				$this->execute('INSERT INTO `'.Yii::app()->getModule('user')->tableUsers.'` SELECT "id","username","password","email","activkey","superuser","status","create_at","lastvisit_at" FROM `'.__CLASS__.'_'.Yii::app()->getModule('user')->tableUsers.'`');
				$this->dropTable(__CLASS__.'_'.Yii::app()->getModule('user')->tableUsers);
				
				break;
		}
	}

    public function dbType()
    {
        list($type) = explode(':',Yii::app()->db->connectionString);
        echo "type db: ".$type."\n";
        return $type;
    }
}