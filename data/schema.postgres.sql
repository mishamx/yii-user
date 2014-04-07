create table tbl_users (
  id serial,
  username varchar(20),
  password varchar(128),
  email varchar(128),
  activkey varchar(128) ,
  create_at timestamp ,
  lastvisit_at timestamp ,
  superuser integer ,
  status integer ,
  primary key (id)
);
create index on tbl_users (status);
create index on tbl_users (superuser);
create unique index tbl_users_username on tbl_users (username);
create unique index tbl_users_email on tbl_users (email);

    
alter sequence tbl_users_id_seq
start with 3
increment by 1
no minvalue
no maxvalue
cache 1;

create table tbl_profiles (
  user_id serial,
  lastname varchar(50) ,
  firstname varchar(50),
  primary key (user_id)
);


alter table tbl_profiles
add constraint user_profile_id foreign key (user_id) references tbl_users (id) on delete cascade;

create table tbl_profiles_fields (
  id serial,
  varname varchar(50),
  title varchar(255) ,
  field_type varchar(50) ,
  field_size varchar(15) ,
  field_size_min varchar(15) ,
  required integer ,
  match varchar(255) ,
  range varchar(255) ,
  error_message varchar(255) ,
  other_validator varchar(5000) ,
  "default" varchar(255) ,
  widget varchar(255) ,
  widgetparams varchar(5000) ,
  "position" integer ,
  visible integer ,
  primary key (id)
);


create index on tbl_profiles_fields (varname);
create index on tbl_profiles_fields (widget);
create index on tbl_profiles_fields (visible);


insert into tbl_users (id, username, password, email, activkey, superuser, status) values
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'webmaster@example.com', '9a24eff8c15a6a141ece27eb6947da0f', 1, 1),
(2, 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 'demo@example.com', '099f825543f7850cc038b90aaff39fac', 0, 1);

insert into tbl_profiles (user_id, lastname, firstname) values
(1, 'admin', 'administrator'),
(2, 'demo', 'demo');

insert into tbl_profiles_fields (id, varname, title, field_type, field_size, field_size_min, required, match, range, error_message, other_validator, "default", widget, widgetparams, "position", visible) values
(1, 'lastname', 'last name', 'varchar', 50, 3, 1, '', '', 'incorrect last name (length between 3 and 50 characters).', '', '', '', '', 1, 3),
(2, 'firstname', 'first name', 'varchar', 50, 3, 1, '', '', 'incorrect first name (length between 3 and 50 characters).', '', '', '', '', 0, 3);
