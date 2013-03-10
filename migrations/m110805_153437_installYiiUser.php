<?php

class m110805_153437_installYiiUser extends CDbMigration
{
	protected $MySqlOptions = 'ENGINE=InnoDB CHARSET=utf8';
    private $_model;
    
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
        Yii::import('user.models.User');
        //*
        switch ($this->dbType()) {
            case "mysql":
                    $this->createTable(Yii::app()->getModule('user')->tableUsers, array(
                        "id" => "pk",
                        "username" => "varchar(20) NOT NULL DEFAULT ''",
                        "password" => "varchar(128) NOT NULL DEFAULT ''",
                        "email" => "varchar(128) NOT NULL DEFAULT ''",
                        "activkey" => "varchar(128) NOT NULL DEFAULT ''",
                        "createtime" => "int(10) NOT NULL DEFAULT 0",
                        "lastvisit" => "int(10) NOT NULL DEFAULT 0",
                        "superuser" => "int(1) NOT NULL DEFAULT 0",
                        "status" => "int(1) NOT NULL DEFAULT 0",
                    ), $this->MySqlOptions);
                    $this->createIndex('user_username', Yii::app()->getModule('user')->tableUsers, 'username', true);
                    $this->createIndex('user_email', Yii::app()->getModule('user')->tableUsers, 'email', true);
                    $this->createTable(Yii::app()->getModule('user')->tableProfiles, array(
                        'user_id' => 'pk',
                        'first_name' => 'string',
                        'last_name' => 'string',
                    ), $this->MySqlOptions);
                    $this->addForeignKey('user_profile_id', Yii::app()->getModule('user')->tableProfiles, 'user_id', Yii::app()->getModule('user')->tableUsers, 'id', 'CASCADE', 'RESTRICT');
                    $this->createTable(Yii::app()->getModule('user')->tableProfileFields, array(
                        "id" => "pk",
                        "varname" => "varchar(50) NOT NULL DEFAULT ''",
                        "title" => "varchar(255) NOT NULL DEFAULT ''",
                        "field_type" => "varchar(50) NOT NULL DEFAULT ''",
                        "field_size" => "int(3) NOT NULL DEFAULT 0",
                        "field_size_min" => "int(3) NOT NULL DEFAULT 0",
                        "required" => "int(1) NOT NULL DEFAULT 0",
                        "match" => "varchar(255) NOT NULL DEFAULT ''",
                        "range" => "varchar(255) NOT NULL DEFAULT ''",
                        "error_message" => "varchar(255) NOT NULL DEFAULT ''",
                        "other_validator" => "text",
                        "default" => "varchar(255) NOT NULL DEFAULT ''",
                        "widget" => "varchar(255) NOT NULL DEFAULT ''",
                        "widgetparams" => "text",
                        "position" => "int(3) NOT NULL DEFAULT 0",
                        "visible" => "int(1) NOT NULL DEFAULT 0",
                    ), $this->MySqlOptions);
                break;
            
            case "sqlite":
            default:
                    $this->createTable(Yii::app()->getModule('user')->tableUsers, array(
                        "id" => "pk",
                        "username" => "varchar(20) NOT NULL",
                        "password" => "varchar(128) NOT NULL",
                        "email" => "varchar(128) NOT NULL",
                        "activkey" => "varchar(128) NOT NULL",
                        "createtime" => "int(10) NOT NULL",
                        "lastvisit" => "int(10) NOT NULL",
                        "superuser" => "int(1) NOT NULL",
                        "status" => "int(1) NOT NULL",
                    ));
                    $this->createIndex('user_username', Yii::app()->getModule('user')->tableUsers, 'username', true);
                    $this->createIndex('user_email', Yii::app()->getModule('user')->tableUsers, 'email', true);
                    $this->createTable(Yii::app()->getModule('user')->tableProfiles, array(
                        'user_id' => 'pk',
                        'first_name' => 'string',
                        'last_name' => 'string',
                    ));
                    $this->createTable(Yii::app()->getModule('user')->tableProfileFields, array(
                        "id" => "pk",
                        "varname" => "varchar(50) NOT NULL",
                        "title" => "varchar(255) NOT NULL",
                        "field_type" => "varchar(50) NOT NULL",
                        "field_size" => "int(3) NOT NULL",
                        "field_size_min" => "int(3) NOT NULL",
                        "required" => "int(1) NOT NULL",
                        "match" => "varchar(255) NOT NULL",
                        "range" => "varchar(255) NOT NULL",
                        "error_message" => "varchar(255) NOT NULL",
                        "other_validator" => "text NOT NULL",
                        "default" => "varchar(255) NOT NULL",
                        "widget" => "varchar(255) NOT NULL",
                        "widgetparams" => "text NOT NULL",
                        "position" => "int(3) NOT NULL",
                        "visible" => "int(1) NOT NULL",
                    ));

                break;
        }//*/

        if (in_array('--interactive=0',$_SERVER['argv'])) {
            $this->_model->username = 'admin';
            $this->_model->email = 'webmaster@example.com';
            $this->_model->password = 'admin';
        } else {
            $this->readStdinUser('Admin login', 'username', 'admin');
            $this->readStdinUser('Admin email', 'email', 'webmaster@example.com');
            $this->readStdinUser('Admin password', 'password', 'admin');
        }

        $this->insert(Yii::app()->getModule('user')->tableUsers, array(
            "id" => "1",
            "username" => $this->_model->username,
            "password" => Yii::app()->getModule('user')->encrypting($this->_model->password),
            "email" => $this->_model->email,
            "activkey" => Yii::app()->getModule('user')->encrypting(microtime()),
            "createtime" => time(),
            "lastvisit" => "0",
            "superuser" => "1",
            "status" => "1",
        ));

        $this->insert(Yii::app()->getModule('user')->tableProfiles, array(
            "user_id" => "1",
            "first_name" => "Administrator",
            "last_name" => "Admin",
        ));

		$this->insert(Yii::app()->getModule('user')->tableProfileFields, array(
            "id" => "1",
            "varname" => "first_name",
            "title" => "First Name",
            "field_type" => "VARCHAR",
            "field_size" => "255",
            "field_size_min" => "3",
            "required" => "2",
            "match" => "",
            "range" => "",
            "error_message" => "Incorrect First Name (length between 3 and 50 characters).",
            "other_validator" => "",
            "default" => "",
            "widget" => "",
            "widgetparams" => "",
            "position" => "1",
            "visible" => "3",
        ));
		$this->insert(Yii::app()->getModule('user')->tableProfileFields, array(
            "id" => "2",
            "varname" => "last_name",
            "title" => "Last Name",
            "field_type" => "VARCHAR",
            "field_size" => "255",
            "field_size_min" => "3",
            "required" => "2",
            "match" => "",
            "range" => "",
            "error_message" => "Incorrect Last Name (length between 3 and 50 characters).",
            "other_validator" => "",
            "default" => "",
            "widget" => "",
            "widgetparams" => "",
            "position" => "2",
            "visible" => "3",
        ));
	}

	public function safeDown()
	{
        $this->dropTable(Yii::app()->getModule('user')->tableProfileFields);
        $this->dropTable(Yii::app()->getModule('user')->tableProfiles);
        $this->dropTable(Yii::app()->getModule('user')->tableUsers);
	}

    public function dbType()
    {
        list($type) = explode(':',Yii::app()->db->connectionString);
        echo "type db: ".$type."\n";
        return $type;
    }

    private function readStdin($prompt, $valid_inputs, $default = '') {
        while(!isset($input) || (is_array($valid_inputs) && !in_array($input, $valid_inputs)) || ($valid_inputs == 'is_file' && !is_file($input))) {
            echo $prompt;
            $input = strtolower(trim(fgets(STDIN)));
            if(empty($input) && !empty($default)) {
                $input = $default;
            }
        }
        return $input;
    }

    private function readStdinUser($prompt, $field, $default = '') {
        if (!$this->_model)
            $this->_model = new User;

        while(!isset($input) || !$this->_model->validate(array($field))) {
            echo $prompt.(($default)?" [$default]":'').': ';
            $input = (trim(fgets(STDIN)));
            if(empty($input) && !empty($default)) {
                $input = $default;
            }
            $this->_model->setAttribute($field,$input);
        }
        return $input;
    }
}