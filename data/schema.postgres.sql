--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: panel; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA panel;


ALTER SCHEMA panel OWNER TO postgres;

SET search_path = panel, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: tbl_profiles; Type: TABLE; Schema: panel; Owner: postgres; Tablespace: 
--

CREATE TABLE tbl_profiles (
    user_id integer NOT NULL,
    lastname character varying(50),
    firstname character varying(50)
);


ALTER TABLE panel.tbl_profiles OWNER TO postgres;

--
-- Name: tbl_profiles_fields; Type: TABLE; Schema: panel; Owner: postgres; Tablespace: 
--

CREATE TABLE tbl_profiles_fields (
    id integer NOT NULL,
    varname character varying(50),
    title character varying(255),
    field_type character varying(50),
    field_size character varying(15),
    field_size_min character varying(15),
    required integer,
    match character varying(255),
    range character varying(255),
    error_message character varying(255),
    other_validator text,
    "default" text,
    widget text,
    widgetparams text,
    "position" integer,
    visible integer
);


ALTER TABLE panel.tbl_profiles_fields OWNER TO postgres;

--
-- Name: tbl_profiles_fields_id_seq; Type: SEQUENCE; Schema: panel; Owner: postgres
--

CREATE SEQUENCE tbl_profiles_fields_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE panel.tbl_profiles_fields_id_seq OWNER TO postgres;

--
-- Name: tbl_profiles_fields_id_seq; Type: SEQUENCE OWNED BY; Schema: panel; Owner: postgres
--

ALTER SEQUENCE tbl_profiles_fields_id_seq OWNED BY tbl_profiles_fields.id;


--
-- Name: tbl_profiles_user_id_seq; Type: SEQUENCE; Schema: panel; Owner: postgres
--

CREATE SEQUENCE tbl_profiles_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE panel.tbl_profiles_user_id_seq OWNER TO postgres;

--
-- Name: tbl_profiles_user_id_seq; Type: SEQUENCE OWNED BY; Schema: panel; Owner: postgres
--

ALTER SEQUENCE tbl_profiles_user_id_seq OWNED BY tbl_profiles.user_id;


--
-- Name: tbl_users; Type: TABLE; Schema: panel; Owner: postgres; Tablespace: 
--

CREATE TABLE tbl_users (
    id integer NOT NULL,
    username character varying(20),
    password character varying(128),
    email character varying(128),
    activkey character varying(128),
    create_at timestamp without time zone,
    lastvisit_at timestamp without time zone,
    superuser integer,
    status integer
);


ALTER TABLE panel.tbl_users OWNER TO postgres;

--
-- Name: tbl_users_id_seq; Type: SEQUENCE; Schema: panel; Owner: postgres
--

CREATE SEQUENCE tbl_users_id_seq
    START WITH 3
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE panel.tbl_users_id_seq OWNER TO postgres;

--
-- Name: tbl_users_id_seq; Type: SEQUENCE OWNED BY; Schema: panel; Owner: postgres
--

ALTER SEQUENCE tbl_users_id_seq OWNED BY tbl_users.id;


--
-- Name: user_id; Type: DEFAULT; Schema: panel; Owner: postgres
--

ALTER TABLE ONLY tbl_profiles ALTER COLUMN user_id SET DEFAULT nextval('tbl_profiles_user_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: panel; Owner: postgres
--

ALTER TABLE ONLY tbl_profiles_fields ALTER COLUMN id SET DEFAULT nextval('tbl_profiles_fields_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: panel; Owner: postgres
--

ALTER TABLE ONLY tbl_users ALTER COLUMN id SET DEFAULT nextval('tbl_users_id_seq'::regclass);


--
-- Data for Name: tbl_profiles; Type: TABLE DATA; Schema: panel; Owner: postgres
--

INSERT INTO tbl_profiles VALUES (1, 'Admin', 'Administrator');
INSERT INTO tbl_profiles VALUES (2, 'Demo', 'Demo');


--
-- Data for Name: tbl_profiles_fields; Type: TABLE DATA; Schema: panel; Owner: postgres
--

INSERT INTO tbl_profiles_fields VALUES (1, 'lastname', 'Last Name', 'VARCHAR', '50', '3', 1, '', '', 'Incorrect Last Name (length between 3 and 50 characters).', '', '', '', '', 1, 3);
INSERT INTO tbl_profiles_fields VALUES (2, 'firstname', 'First Name', 'VARCHAR', '50', '3', 1, '', '', 'Incorrect First Name (length between 3 and 50 characters).', '', '', '', '', 0, 3);


--
-- Name: tbl_profiles_fields_id_seq; Type: SEQUENCE SET; Schema: panel; Owner: postgres
--

SELECT pg_catalog.setval('tbl_profiles_fields_id_seq', 1, false);


--
-- Name: tbl_profiles_user_id_seq; Type: SEQUENCE SET; Schema: panel; Owner: postgres
--

SELECT pg_catalog.setval('tbl_profiles_user_id_seq', 1, false);


--
-- Data for Name: tbl_users; Type: TABLE DATA; Schema: panel; Owner: postgres
--

INSERT INTO tbl_users VALUES (2, 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 'demo@example.com', '099f825543f7850cc038b90aaff39fac', NULL, NULL, 0, 1);
INSERT INTO tbl_users VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'webmaster@example.com', '9a24eff8c15a6a141ece27eb6947da0f', NULL, '2014-04-07 14:19:37', 1, 1);


--
-- Name: tbl_users_id_seq; Type: SEQUENCE SET; Schema: panel; Owner: postgres
--

SELECT pg_catalog.setval('tbl_users_id_seq', 3, true);


--
-- Name: tbl_profiles_fields_pkey; Type: CONSTRAINT; Schema: panel; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tbl_profiles_fields
    ADD CONSTRAINT tbl_profiles_fields_pkey PRIMARY KEY (id);


--
-- Name: tbl_profiles_pkey; Type: CONSTRAINT; Schema: panel; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tbl_profiles
    ADD CONSTRAINT tbl_profiles_pkey PRIMARY KEY (user_id);


--
-- Name: tbl_users_email_key; Type: CONSTRAINT; Schema: panel; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tbl_users
    ADD CONSTRAINT tbl_users_email_key UNIQUE (email);


--
-- Name: tbl_users_pkey; Type: CONSTRAINT; Schema: panel; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tbl_users
    ADD CONSTRAINT tbl_users_pkey PRIMARY KEY (id);


--
-- Name: tbl_users_username_key; Type: CONSTRAINT; Schema: panel; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tbl_users
    ADD CONSTRAINT tbl_users_username_key UNIQUE (username);


--
-- Name: user_status; Type: INDEX; Schema: panel; Owner: postgres; Tablespace: 
--

CREATE INDEX user_status ON tbl_users USING btree (status);


--
-- Name: user_superuser; Type: INDEX; Schema: panel; Owner: postgres; Tablespace: 
--

CREATE INDEX user_superuser ON tbl_users USING btree (superuser);

--
-- Name: user_profile_id; Type: FK CONSTRAINT; Schema: panel; Owner: postgres
--

ALTER TABLE ONLY tbl_profiles
    ADD CONSTRAINT user_profile_id FOREIGN KEY (user_id) REFERENCES tbl_users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--