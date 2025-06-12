-- Adminer 5.0.5 PostgreSQL 15.13 (Debian 15.13-0+deb12u1) dump

DROP FUNCTION IF EXISTS "update_software_rating";;
CREATE FUNCTION "update_software_rating" () RETURNS trigger LANGUAGE plpgsql AS '
BEGIN
  UPDATE softwares
  SET
    rating_count = (
      SELECT COUNT(*) FROM softwares_ratings WHERE sw_id = COALESCE(NEW.sw_id, OLD.sw_id)
    ),
    rating_avg   = COALESCE(
      ROUND((
        SELECT AVG(rating) FROM softwares_ratings WHERE sw_id = COALESCE(NEW.sw_id, OLD.sw_id)
      )::numeric, 2),
      0
    )
  WHERE id = COALESCE(NEW.sw_id, OLD.sw_id);
  RETURN NULL;
END;
';

DROP TABLE IF EXISTS "accounts";
DROP SEQUENCE IF EXISTS accounts_id_seq;
CREATE SEQUENCE accounts_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 422 CACHE 1;

CREATE TABLE "progaccess"."accounts" (
    "id" bigint DEFAULT nextval('accounts_id_seq') NOT NULL,
    "id64" character varying(88) DEFAULT '' NOT NULL,
    "username" character varying(32) DEFAULT '' NOT NULL,
    "email" character varying(255) DEFAULT '' NOT NULL,
    "password" character varying(255) DEFAULT '' NOT NULL,
    "signup_date" bigint DEFAULT '0' NOT NULL,
    "confirmed" boolean DEFAULT false NOT NULL,
    "settings" jsonb NOT NULL,
    "rank" character(1) DEFAULT '0' NOT NULL,
    "subscribed_comments" boolean DEFAULT false NOT NULL,
    "rights" jsonb DEFAULT '{}' NOT NULL,
    "twofa_secret" character varying(255),
    "twofa_enabled" boolean DEFAULT false,
    CONSTRAINT "idx_16396_primary" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "count_visitors";
DROP SEQUENCE IF EXISTS count_visitors_id_seq;
CREATE SEQUENCE count_visitors_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 315770 CACHE 1;

CREATE TABLE "progaccess"."count_visitors" (
    "id" bigint DEFAULT nextval('count_visitors_id_seq') NOT NULL,
    "addr" character varying(40) NOT NULL,
    "lastvisit" bigint NOT NULL,
    "domain" character varying(16) DEFAULT '' NOT NULL,
    CONSTRAINT "idx_16425_primary" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "count_visits";
DROP SEQUENCE IF EXISTS count_visits_id_seq;
CREATE SEQUENCE count_visits_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 90560 CACHE 1;

CREATE TABLE "progaccess"."count_visits" (
    "id" bigint DEFAULT nextval('count_visits_id_seq') NOT NULL,
    "date" date NOT NULL,
    "page" character varying(255) DEFAULT '' NOT NULL,
    "domain" character varying(16) DEFAULT '' NOT NULL,
    "visits" bigint DEFAULT '0' NOT NULL,
    CONSTRAINT "idx_16431_primary" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "cpt_connectes";
CREATE TABLE "progaccess"."cpt_connectes" (
    "ip" character varying(255) NOT NULL,
    "timestamp" character varying(255) NOT NULL
) WITH (oids = false);


DROP TABLE IF EXISTS "daily_visitors";
DROP SEQUENCE IF EXISTS daily_visitors_id_seq;
CREATE SEQUENCE daily_visitors_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 5235 CACHE 1;

CREATE TABLE "progaccess"."daily_visitors" (
    "id" bigint DEFAULT nextval('daily_visitors_id_seq') NOT NULL,
    "date" date NOT NULL,
    "visitors" bigint NOT NULL,
    "domain" character varying(16) NOT NULL,
    CONSTRAINT "idx_16444_primary" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "languages";
DROP SEQUENCE IF EXISTS languages_id_seq;
CREATE SEQUENCE languages_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 6 CACHE 1;

CREATE TABLE "progaccess"."languages" (
    "id" bigint DEFAULT nextval('languages_id_seq') NOT NULL,
    "lang" character varying(5) NOT NULL,
    "name" character varying(255) NOT NULL,
    "priority" bigint DEFAULT '0' NOT NULL,
    CONSTRAINT "idx_16449_primary" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE UNIQUE INDEX idx_16449_lang ON progaccess.languages USING btree (lang);


DROP TABLE IF EXISTS "newsletter_mails";
DROP SEQUENCE IF EXISTS newsletter_mails_id_seq;
CREATE SEQUENCE newsletter_mails_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 261 CACHE 1;

CREATE TABLE "progaccess"."newsletter_mails" (
    "id" bigint DEFAULT nextval('newsletter_mails_id_seq') NOT NULL,
    "hash" character varying(80) NOT NULL,
    "mail" character varying(255) NOT NULL,
    "expire" bigint NOT NULL,
    "freq" smallint NOT NULL,
    "freq_n" smallint NOT NULL,
    "notif_site" boolean NOT NULL,
    "notif_upd" boolean NOT NULL,
    "notif_upd_n" boolean NOT NULL,
    "confirm" boolean DEFAULT false NOT NULL,
    "lastmail" bigint DEFAULT '0' NOT NULL,
    "lastmail_n" bigint NOT NULL,
    "lang" character varying(5) NOT NULL,
    CONSTRAINT "idx_16455_primary" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "notifs";
DROP SEQUENCE IF EXISTS notifs_id_seq;
CREATE SEQUENCE notifs_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 14241 CACHE 1;

CREATE TABLE "progaccess"."notifs" (
    "id" bigint DEFAULT nextval('notifs_id_seq') NOT NULL,
    "date" bigint NOT NULL,
    "account" bigint NOT NULL,
    "data" character varying(1024) NOT NULL,
    "mail_sent" boolean DEFAULT false NOT NULL,
    "unread" boolean DEFAULT true NOT NULL,
    CONSTRAINT "idx_16462_primary" PRIMARY KEY ("id")
) WITH (oids = false);

COMMENT ON COLUMN "progaccess"."notifs"."data" IS 'JSON data';


DROP TABLE IF EXISTS "password_resets";
DROP SEQUENCE IF EXISTS password_resets_id_seq;
CREATE SEQUENCE password_resets_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 3 CACHE 1;

CREATE TABLE "progaccess"."password_resets" (
    "id" integer DEFAULT nextval('password_resets_id_seq') NOT NULL,
    "user_id" integer NOT NULL,
    "token" character(64) NOT NULL,
    "expires_at" timestamp NOT NULL,
    "used" boolean DEFAULT false NOT NULL,
    "created_at" timestamp DEFAULT now() NOT NULL,
    CONSTRAINT "password_resets_pkey" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE UNIQUE INDEX password_resets_token_key ON progaccess.password_resets USING btree (token);

CREATE INDEX idx_password_resets_token ON progaccess.password_resets USING btree (token);


DROP TABLE IF EXISTS "sessions";
DROP SEQUENCE IF EXISTS sessions_id_seq;
CREATE SEQUENCE sessions_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1067 CACHE 1;

CREATE TABLE "progaccess"."sessions" (
    "id" bigint DEFAULT nextval('sessions_id_seq') NOT NULL,
    "account" bigint NOT NULL,
    "session" character varying(255) NOT NULL,
    "connectid" character varying(64) NOT NULL,
    "expire" bigint NOT NULL,
    "created" bigint NOT NULL,
    "token" character varying(44) NOT NULL,
    CONSTRAINT "idx_16471_primary" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "site_updates";
DROP SEQUENCE IF EXISTS site_updates_id_seq;
CREATE SEQUENCE site_updates_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 135 CACHE 1;

CREATE TABLE "progaccess"."site_updates" (
    "id" bigint DEFAULT nextval('site_updates_id_seq') NOT NULL,
    "name" character varying(255) NOT NULL,
    "text" text NOT NULL,
    "date" bigint NOT NULL,
    "uptype" character varying(8) DEFAULT '' NOT NULL,
    "authors" character varying(255) DEFAULT '' NOT NULL,
    "codestat" character varying(255) DEFAULT '[-1,-1,-1]' NOT NULL,
    CONSTRAINT "idx_16476_primary" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "slides";
DROP SEQUENCE IF EXISTS slides_id_seq;
CREATE SEQUENCE slides_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 27 CACHE 1;

CREATE TABLE "progaccess"."slides" (
    "id" bigint DEFAULT nextval('slides_id_seq') NOT NULL,
    "lang" character varying(5) NOT NULL,
    "label" character varying(255) NOT NULL,
    "style" text NOT NULL,
    "title" character varying(512) NOT NULL,
    "title_style" text NOT NULL,
    "contain" text NOT NULL,
    "contain_style" text NOT NULL,
    "date" bigint NOT NULL,
    "todo_level" smallint DEFAULT '2' NOT NULL,
    "published" boolean DEFAULT true NOT NULL,
    CONSTRAINT "idx_16486_primary" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "slides_tr";
DROP SEQUENCE IF EXISTS slides_tr_id_seq;
CREATE SEQUENCE slides_tr_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 2 CACHE 1;

CREATE TABLE "progaccess"."slides_tr" (
    "id" bigint DEFAULT nextval('slides_tr_id_seq') NOT NULL,
    "slide_id" bigint NOT NULL,
    "lang" character varying(5) NOT NULL,
    "date" bigint NOT NULL,
    "title" character varying(512) NOT NULL,
    "contain" text NOT NULL,
    "author" character varying(255) NOT NULL,
    "published" boolean DEFAULT false NOT NULL,
    "todo_level" smallint DEFAULT '2' NOT NULL,
    CONSTRAINT "idx_16495_primary" PRIMARY KEY ("id")
) WITH (oids = false);

COMMENT ON COLUMN "progaccess"."slides_tr"."todo_level" IS '0:reference, 1:ok, 2:to be checked, 3:to be modified';


DROP TABLE IF EXISTS "softwares";
DROP SEQUENCE IF EXISTS softwares_id_seq;
CREATE SEQUENCE softwares_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 265 CACHE 1;

CREATE TABLE "progaccess"."softwares" (
    "id" bigint DEFAULT nextval('softwares_id_seq') NOT NULL,
    "name" character varying(255) NOT NULL,
    "category" bigint NOT NULL,
    "text" text DEFAULT '' NOT NULL,
    "date" bigint NOT NULL,
    "hits" bigint DEFAULT '0' NOT NULL,
    "description" character varying(511) DEFAULT '' NOT NULL,
    "keywords" character varying(511) DEFAULT '' NOT NULL,
    "website" character varying(255) DEFAULT '' NOT NULL,
    "downloads" bigint DEFAULT '0' NOT NULL,
    "author" character varying(255) DEFAULT '' NOT NULL,
    "archive_after" bigint,
    "rating_avg" numeric(3,2) DEFAULT '0' NOT NULL,
    "rating_count" integer DEFAULT '0' NOT NULL,
    CONSTRAINT "idx_16504_primary" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE INDEX idx_16504_category ON progaccess.softwares USING btree (category);


DROP TABLE IF EXISTS "softwares_categories";
DROP SEQUENCE IF EXISTS softwares_categories_id_seq;
CREATE SEQUENCE softwares_categories_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 27 CACHE 1;

CREATE TABLE "progaccess"."softwares_categories" (
    "id" bigint DEFAULT nextval('softwares_categories_id_seq') NOT NULL,
    "name" character varying(255) NOT NULL,
    "text" text NOT NULL,
    CONSTRAINT "idx_16518_primary" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "softwares_categories_tr";
DROP SEQUENCE IF EXISTS softwares_categories_tr_id_seq;
CREATE SEQUENCE softwares_categories_tr_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 2 CACHE 1;

CREATE TABLE "progaccess"."softwares_categories_tr" (
    "id" bigint DEFAULT nextval('softwares_categories_tr_id_seq') NOT NULL,
    "lang" character varying(5) NOT NULL,
    "name" character varying(255) NOT NULL,
    "text" text NOT NULL,
    "published" boolean DEFAULT false NOT NULL,
    "todo_level" smallint DEFAULT '2' NOT NULL,
    CONSTRAINT "idx_16525_primary" PRIMARY KEY ("id")
) WITH (oids = false);

COMMENT ON COLUMN "progaccess"."softwares_categories_tr"."todo_level" IS '0:reference, 1:ok, 2:to be checked, 3:to be modified';


DROP TABLE IF EXISTS "softwares_comments";
DROP SEQUENCE IF EXISTS softwares_comments_id_seq;
CREATE SEQUENCE softwares_comments_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 696 CACHE 1;

CREATE TABLE "progaccess"."softwares_comments" (
    "id" bigint DEFAULT nextval('softwares_comments_id_seq') NOT NULL,
    "sw_id" bigint NOT NULL,
    "date" bigint NOT NULL,
    "nickname" bigint NOT NULL,
    "text" text NOT NULL,
    CONSTRAINT "idx_16534_primary" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE INDEX idx_16534_softwares_comments_ibfk_1 ON progaccess.softwares_comments USING btree (sw_id);


DROP TABLE IF EXISTS "softwares_files";
DROP SEQUENCE IF EXISTS softwares_files_id_seq;
CREATE SEQUENCE softwares_files_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 3172 CACHE 1;

CREATE TABLE "progaccess"."softwares_files" (
    "id" bigint DEFAULT nextval('softwares_files_id_seq') NOT NULL,
    "sw_id" bigint NOT NULL,
    "name" character varying(255) DEFAULT '' NOT NULL,
    "hash" character varying(80) DEFAULT '' NOT NULL,
    "filetype" character varying(255) DEFAULT '' NOT NULL,
    "title" character varying(255) DEFAULT '' NOT NULL,
    "date" bigint DEFAULT '0' NOT NULL,
    "filesize" bigint DEFAULT '0' NOT NULL,
    "total_hits" bigint DEFAULT '0',
    "hits" bigint,
    "label" character varying(1000) DEFAULT '' NOT NULL,
    "md5" character varying(32) DEFAULT '' NOT NULL,
    "sha1" character varying(40) DEFAULT '' NOT NULL,
    "arch" character varying(40) NOT NULL,
    "platform" character varying(40) NOT NULL,
    CONSTRAINT "idx_16541_primary" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE INDEX idx_16541_sw_id ON progaccess.softwares_files USING btree (sw_id);


DROP TABLE IF EXISTS "softwares_mirrors";
DROP SEQUENCE IF EXISTS softwares_mirrors_id_seq;
CREATE SEQUENCE softwares_mirrors_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 25 CACHE 1;

CREATE TABLE "progaccess"."softwares_mirrors" (
    "id" bigint DEFAULT nextval('softwares_mirrors_id_seq') NOT NULL,
    "sw_id" bigint NOT NULL,
    "links" text NOT NULL,
    "title" character varying(255) NOT NULL,
    "date" bigint NOT NULL,
    "hits" bigint DEFAULT '0' NOT NULL,
    "label" character varying(1000) DEFAULT '' NOT NULL,
    CONSTRAINT "idx_16558_primary" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE INDEX idx_16558_softwares_mirrors_ibfk_1 ON progaccess.softwares_mirrors USING btree (sw_id);


DROP TABLE IF EXISTS "softwares_packages";
DROP SEQUENCE IF EXISTS softwares_packages_id_seq;
CREATE SEQUENCE softwares_packages_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 36 CACHE 1;

CREATE TABLE "progaccess"."softwares_packages" (
    "id" bigint DEFAULT nextval('softwares_packages_id_seq') NOT NULL,
    "sw_id" bigint NOT NULL,
    "manager" character varying(64) NOT NULL,
    "name" character varying(255) NOT NULL,
    "comment" text,
    CONSTRAINT "idx_16567_primary" PRIMARY KEY ("id")
) WITH (oids = false);

CREATE INDEX idx_16567_sw_id ON progaccess.softwares_packages USING btree (sw_id);


DROP TABLE IF EXISTS "softwares_ratings";
DROP SEQUENCE IF EXISTS softwares_ratings_id_seq;
CREATE SEQUENCE softwares_ratings_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 2147483647 START 9 CACHE 1;

CREATE TABLE "progaccess"."softwares_ratings" (
    "id" integer DEFAULT nextval('softwares_ratings_id_seq') NOT NULL,
    "sw_id" integer NOT NULL,
    "account" integer,
    "rating" smallint NOT NULL,
    "created_at" timestamptz DEFAULT now() NOT NULL,
    CONSTRAINT "softwares_ratings_pkey" PRIMARY KEY ("id"),
    CONSTRAINT "softwares_ratings_rating_check" CHECK (((rating >= 1) AND (rating <= 5)))
) WITH (oids = false);

CREATE UNIQUE INDEX softwares_ratings_sw_id_account_key ON progaccess.softwares_ratings USING btree (sw_id, account);


DELIMITER ;;

CREATE TRIGGER "tr_software_ratings_ins" AFTER INSERT ON "progaccess"."softwares_ratings" FOR EACH ROW EXECUTE FUNCTION update_software_rating();;

CREATE TRIGGER "tr_software_ratings_del" AFTER DELETE ON "progaccess"."softwares_ratings" FOR EACH ROW EXECUTE FUNCTION update_software_rating();;

CREATE TRIGGER "tr_software_ratings_upd" AFTER UPDATE ON "progaccess"."softwares_ratings" FOR EACH ROW EXECUTE FUNCTION update_software_rating();;

DELIMITER ;

DROP TABLE IF EXISTS "softwares_tr";
DROP SEQUENCE IF EXISTS softwares_tr_id_seq;
CREATE SEQUENCE softwares_tr_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 202 CACHE 1;

CREATE TABLE "progaccess"."softwares_tr" (
    "id" bigint DEFAULT nextval('softwares_tr_id_seq') NOT NULL,
    "sw_id" bigint NOT NULL,
    "lang" character varying(5) NOT NULL,
    "date" bigint NOT NULL,
    "name" character varying(255) NOT NULL,
    "text" text NOT NULL,
    "keywords" character varying(512) NOT NULL,
    "description" character varying(512) NOT NULL,
    "website" character varying(255) NOT NULL,
    "author" character varying(255) NOT NULL,
    "published" boolean DEFAULT false NOT NULL,
    "todo_level" smallint DEFAULT '2' NOT NULL,
    CONSTRAINT "idx_16574_primary" PRIMARY KEY ("id")
) WITH (oids = false);

COMMENT ON COLUMN "progaccess"."softwares_tr"."todo_level" IS '0:reference, 1:ok, 2:to be checked, 3:to be modified';


DROP TABLE IF EXISTS "subscriptions_comments";
DROP SEQUENCE IF EXISTS subscriptions_comments_id_seq;
CREATE SEQUENCE subscriptions_comments_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 167 CACHE 1;

CREATE TABLE "progaccess"."subscriptions_comments" (
    "id" bigint DEFAULT nextval('subscriptions_comments_id_seq') NOT NULL,
    "account" bigint NOT NULL,
    "article" bigint NOT NULL,
    CONSTRAINT "idx_16583_primary" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "team";
DROP SEQUENCE IF EXISTS team_id_seq;
CREATE SEQUENCE team_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 12 CACHE 1;

CREATE TABLE "progaccess"."team" (
    "id" bigint DEFAULT nextval('team_id_seq') NOT NULL,
    "name" character varying(255) NOT NULL,
    "status" character varying(255) NOT NULL,
    "date" bigint NOT NULL,
    "age" bigint NOT NULL,
    "account_id" bigint,
    "short_name" text,
    "bio" text NOT NULL,
    "works" character varying(8) DEFAULT '' NOT NULL,
    "mastodon" text NOT NULL,
    "nvda_expert" integer,
    "rights" jsonb DEFAULT '{}' NOT NULL,
    CONSTRAINT "idx_16593_primary" PRIMARY KEY ("id")
) WITH (oids = false);


DROP TABLE IF EXISTS "tickets";
DROP SEQUENCE IF EXISTS tickets_id_seq;
CREATE SEQUENCE tickets_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 166 CACHE 1;

CREATE TABLE "progaccess"."tickets" (
    "id" bigint DEFAULT nextval('tickets_id_seq') NOT NULL,
    "subject" character varying(255) NOT NULL,
    "expeditor_email" character varying(255) NOT NULL,
    "expeditor_name" character varying(255) NOT NULL,
    "messages" text NOT NULL,
    "status" smallint NOT NULL,
    "hash" character varying(128) NOT NULL,
    "date" bigint NOT NULL,
    "lastadmreply" character varying(256),
    CONSTRAINT "idx_16601_primary" PRIMARY KEY ("id")
) WITH (oids = false);


ALTER TABLE ONLY "progaccess"."softwares" ADD CONSTRAINT "softwares_ibfk_1" FOREIGN KEY (category) REFERENCES softwares_categories(id) ON UPDATE CASCADE ON DELETE RESTRICT NOT DEFERRABLE;

ALTER TABLE ONLY "progaccess"."softwares_comments" ADD CONSTRAINT "softwares_comments_ibfk_1" FOREIGN KEY (sw_id) REFERENCES softwares(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE;

ALTER TABLE ONLY "progaccess"."softwares_files" ADD CONSTRAINT "softwares_files_ibfk_1" FOREIGN KEY (sw_id) REFERENCES softwares(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE;

ALTER TABLE ONLY "progaccess"."softwares_mirrors" ADD CONSTRAINT "softwares_mirrors_ibfk_1" FOREIGN KEY (sw_id) REFERENCES softwares(id) ON UPDATE CASCADE ON DELETE CASCADE NOT DEFERRABLE;

ALTER TABLE ONLY "progaccess"."softwares_packages" ADD CONSTRAINT "softwares_packages_ibfk_1" FOREIGN KEY (sw_id) REFERENCES softwares(id) ON UPDATE RESTRICT ON DELETE CASCADE NOT DEFERRABLE;

-- 2025-05-20 13:53:07 UTC
