<?php

class m110810_162301_userTimestampFix extends CDbMigration
{
	public function safeUp()
	{
        $this->addColumn('{{user}}','create_at',"TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->addColumn('{{user}}','lastvisit_at',"TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $this->execute("UPDATE {{user}} SET create_at = FROM_UNIXTIME(createtime), lastvisit_at = FROM_UNIXTIME(lastvisit)");
        $this->dropColumn('{{user}}','createtime');
        $this->dropColumn('{{user}}','lastvisit');
	}

	public function safeDown()
	{
        $this->addColumn('{{user}}','createtime',"int(10) NOT NULL");
        $this->addColumn('{{user}}','lastvisit',"int(10) NOT NULL");
        $this->execute("UPDATE {{user}} SET createtime = UNIX_TIMESTAMP(create_at), lastvisit = UNIX_TIMESTAMP(lastvisit_at)");
        $this->dropColumn('{{user}}','create_at');
        $this->dropColumn('{{user}}','lastvisit_at');
	}
}