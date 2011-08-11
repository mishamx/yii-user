CREATE TABLE tbl_users (
  id INTEGER NOT NULL  PRIMARY KEY AUTOINCREMENT,
  username varchar(20) NOT NULL,
  password varchar(128) NOT NULL,
  email varchar(128) NOT NULL,
  activkey varchar(128) NOT NULL DEFAULT '',
  create_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  lastvisit_at TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  superuser int(1) NOT NULL DEFAULT '0',
  status int(1) NOT NULL DEFAULT '0'
);

CREATE TABLE tbl_profiles (
  user_id INTEGER NOT NULL PRIMARY KEY,
  lastname varchar(50) NOT NULL DEFAULT '',
  firstname varchar(50) NOT NULL DEFAULT ''
);

CREATE TABLE tbl_profiles_fields (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  varname varchar(50) NOT NULL,
  title varchar(255) NOT NULL,
  field_type varchar(50) NOT NULL,
  field_size int(3) NOT NULL DEFAULT '0',
  field_size_min int(3) NOT NULL DEFAULT '0',
  required int(1) NOT NULL DEFAULT '0',
  match varchar(255) NOT NULL DEFAULT '',
  range varchar(255) NOT NULL DEFAULT '',
  error_message varchar(255) NOT NULL DEFAULT '',
  other_validator TEXT NOT NULL DEFAULT '',
  'default' varchar(255) NOT NULL DEFAULT '',
  widget varchar(255) NOT NULL DEFAULT '',
  widgetparams TEXT NOT NULL DEFAULT '',
  position int(3) NOT NULL DEFAULT '0',
  visible int(1) NOT NULL DEFAULT '0'
);

INSERT INTO tbl_users (id, username, password, email, activkey, superuser, status) VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'webmaster@example.com', '21232f297a57a5a743894a0e4a801fc3', 1, 1);
INSERT INTO tbl_users (id, username, password, email, activkey, superuser, status) VALUES (2, 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 'demo@example.com', 'fe01ce2a7fbac8fafaed7c982a04e229', 0, 1);

INSERT INTO tbl_profiles (user_id, lastname, firstname) VALUES (1, 'Admin', 'Administrator');
INSERT INTO tbl_profiles (user_id, lastname, firstname) VALUES (2, 'Demo', 'Demo');

INSERT INTO tbl_profiles_fields (id, varname, title, field_type, field_size, field_size_min, required, 'match', range, error_message, other_validator, 'default', widget, widgetparams, position, visible) VALUES (1, 'lastname', 'Last Name', 'VARCHAR', 50, 3, 1, '', '', 'Incorrect Last Name (length between 3 and 50 characters).', '', '', '', '', 1, 3);
INSERT INTO tbl_profiles_fields (id, varname, title, field_type, field_size, field_size_min, required, 'match', range, error_message, other_validator, 'default', widget, widgetparams, position, visible) VALUES (2, 'firstname', 'First Name', 'VARCHAR', 50, 3, 1, '', '', 'Incorrect First Name (length between 3 and 50 characters).', '', '', '', '', 0, 3);
