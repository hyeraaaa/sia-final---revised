-- This script was generated by the ERD tool in pgAdmin 4.
-- Please log an issue at https://github.com/pgadmin-org/pgadmin4/issues/new/choose if you find any bugs, including reproduction steps.

CREATE DATABASE I-SMS;

BEGIN;


CREATE TABLE IF NOT EXISTS public.admin
(
    admin_id serial NOT NULL,
    first_name character varying(100) COLLATE pg_catalog."default" NOT NULL,
    last_name character varying(100) COLLATE pg_catalog."default" NOT NULL,
    email character varying(100) COLLATE pg_catalog."default" NOT NULL,
    password character varying(100) COLLATE pg_catalog."default" NOT NULL,
    contact_number character varying(15) COLLATE pg_catalog."default",
    department_id integer,
    otp character varying(6) COLLATE pg_catalog."default",
    otp_expiry timestamp without time zone,
    profile_picture character varying(255) COLLATE pg_catalog."default",
    cover_photo character varying(255) COLLATE pg_catalog."default",
    role character varying(15) COLLATE pg_catalog."default",
    bio text COLLATE pg_catalog."default",
    CONSTRAINT admin_pkey PRIMARY KEY (admin_id),
    CONSTRAINT admin_contact_number_key UNIQUE (contact_number),
    CONSTRAINT admin_email_key UNIQUE (email)
);

CREATE TABLE IF NOT EXISTS public.announcement
(
    announcement_id serial NOT NULL,
    title character varying(255) COLLATE pg_catalog."default" NOT NULL,
    description text COLLATE pg_catalog."default" NOT NULL,
    message character varying(255) COLLATE pg_catalog."default",
    admin_id integer NOT NULL,
    image text COLLATE pg_catalog."default",
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    view_count integer DEFAULT 0,
    CONSTRAINT announcement_pkey PRIMARY KEY (announcement_id)
);

CREATE TABLE IF NOT EXISTS public.announcement_course
(
    announcement_id integer NOT NULL,
    course_id integer NOT NULL,
    CONSTRAINT announcement_course_pkey PRIMARY KEY (announcement_id, course_id)
);

CREATE TABLE IF NOT EXISTS public.announcement_department
(
    announcement_id integer NOT NULL,
    department_id integer NOT NULL,
    CONSTRAINT announcement_department_pkey PRIMARY KEY (announcement_id, department_id)
);

CREATE TABLE IF NOT EXISTS public.announcement_year_level
(
    announcement_id integer NOT NULL,
    year_level_id integer NOT NULL,
    CONSTRAINT announcement_year_level_pkey PRIMARY KEY (announcement_id, year_level_id)
);

CREATE TABLE IF NOT EXISTS public.course
(
    course_id serial NOT NULL,
    course_name character varying(100) COLLATE pg_catalog."default" NOT NULL,
    CONSTRAINT course_pkey PRIMARY KEY (course_id),
    CONSTRAINT course_course_name_key UNIQUE (course_name)
);

CREATE TABLE IF NOT EXISTS public.department
(
    department_id serial NOT NULL,
    department_name character varying(100) COLLATE pg_catalog."default" NOT NULL,
    CONSTRAINT department_pkey PRIMARY KEY (department_id),
    CONSTRAINT department_department_name_key UNIQUE (department_name)
);

CREATE TABLE IF NOT EXISTS public.feedback
(
    feedback_id serial NOT NULL,
    name character varying(255) COLLATE pg_catalog."default" NOT NULL,
    email character varying(255) COLLATE pg_catalog."default" NOT NULL,
    message text COLLATE pg_catalog."default" NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT feedback_pkey PRIMARY KEY (feedback_id)
);

CREATE TABLE IF NOT EXISTS public.logs
(
    log_id serial NOT NULL,
    user_id integer,
    user_type character varying(50) COLLATE pg_catalog."default" NOT NULL,
    action character varying(50) COLLATE pg_catalog."default" NOT NULL,
    affected_table character varying(100) COLLATE pg_catalog."default" NOT NULL,
    affected_record_id integer,
    description text COLLATE pg_catalog."default",
    "timestamp" timestamp without time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT logs_pkey PRIMARY KEY (log_id)
);

CREATE TABLE IF NOT EXISTS public.sms_log
(
    sms_log_id serial NOT NULL,
    announcement_id integer NOT NULL,
    student_id integer NOT NULL,
    status character varying(20) COLLATE pg_catalog."default" NOT NULL,
    sent_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT sms_log_pkey PRIMARY KEY (sms_log_id)
);

CREATE TABLE IF NOT EXISTS public.student
(
    student_id serial NOT NULL,
    email character varying(100) COLLATE pg_catalog."default" NOT NULL,
    password character varying(100) COLLATE pg_catalog."default" NOT NULL,
    first_name character varying(100) COLLATE pg_catalog."default" NOT NULL,
    last_name character varying(100) COLLATE pg_catalog."default" NOT NULL,
    contact_number character varying(15) COLLATE pg_catalog."default",
    year_level_id integer NOT NULL,
    department_id integer NOT NULL,
    course_id integer NOT NULL,
    otp character varying(6) COLLATE pg_catalog."default",
    otp_expiry timestamp without time zone,
    token character varying(255) COLLATE pg_catalog."default",
    profile_picture character varying(255) COLLATE pg_catalog."default",
    CONSTRAINT student_pkey PRIMARY KEY (student_id),
    CONSTRAINT student_contact_number_key UNIQUE (contact_number),
    CONSTRAINT student_email_key UNIQUE (email)
);

CREATE TABLE IF NOT EXISTS public.year_level
(
    year_level_id serial NOT NULL,
    year_level character varying(50) COLLATE pg_catalog."default" NOT NULL,
    CONSTRAINT year_level_pkey PRIMARY KEY (year_level_id),
    CONSTRAINT year_level_year_level_key UNIQUE (year_level)
);

ALTER TABLE IF EXISTS public.announcement
    ADD CONSTRAINT announcement_staff_id_fkey FOREIGN KEY (admin_id)
    REFERENCES public.admin (admin_id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE CASCADE;


ALTER TABLE IF EXISTS public.announcement_course
    ADD CONSTRAINT announcement_course_announcement_id_fkey FOREIGN KEY (announcement_id)
    REFERENCES public.announcement (announcement_id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE CASCADE;


ALTER TABLE IF EXISTS public.announcement_course
    ADD CONSTRAINT announcement_course_course_id_fkey FOREIGN KEY (course_id)
    REFERENCES public.course (course_id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE CASCADE;


ALTER TABLE IF EXISTS public.announcement_department
    ADD CONSTRAINT announcement_department_announcement_id_fkey FOREIGN KEY (announcement_id)
    REFERENCES public.announcement (announcement_id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE CASCADE;


ALTER TABLE IF EXISTS public.announcement_department
    ADD CONSTRAINT announcement_department_department_id_fkey FOREIGN KEY (department_id)
    REFERENCES public.department (department_id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE CASCADE;


ALTER TABLE IF EXISTS public.announcement_year_level
    ADD CONSTRAINT announcement_year_level_announcement_id_fkey FOREIGN KEY (announcement_id)
    REFERENCES public.announcement (announcement_id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE CASCADE;


ALTER TABLE IF EXISTS public.announcement_year_level
    ADD CONSTRAINT announcement_year_level_year_level_id_fkey FOREIGN KEY (year_level_id)
    REFERENCES public.year_level (year_level_id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE CASCADE;


ALTER TABLE IF EXISTS public.sms_log
    ADD CONSTRAINT sms_log_announcement_id_fkey FOREIGN KEY (announcement_id)
    REFERENCES public.announcement (announcement_id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE CASCADE;


ALTER TABLE IF EXISTS public.sms_log
    ADD CONSTRAINT sms_log_student_id_fkey FOREIGN KEY (student_id)
    REFERENCES public.student (student_id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE CASCADE;


ALTER TABLE IF EXISTS public.student
    ADD CONSTRAINT student_course_id_fkey FOREIGN KEY (course_id)
    REFERENCES public.course (course_id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE CASCADE;


ALTER TABLE IF EXISTS public.student
    ADD CONSTRAINT student_department_id_fkey FOREIGN KEY (department_id)
    REFERENCES public.department (department_id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE CASCADE;


ALTER TABLE IF EXISTS public.student
    ADD CONSTRAINT student_year_level_id_fkey FOREIGN KEY (year_level_id)
    REFERENCES public.year_level (year_level_id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE CASCADE;

END;