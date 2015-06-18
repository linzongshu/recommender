# General tables

# Site configuration
CREATE TABLE `{config}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `name`            char(32)                        NOT NULL DEFAULT '',
  `title`           varchar(255)                    NOT NULL DEFAULT '',
  `category`        char(32)                        NOT NULL DEFAULT '',
  `value`           text                            DEFAULT NULL,
  `visible`         tinyint(1) UNSIGNED             NOT NULL DEFAULT 1,

  PRIMARY KEY     (`id`),
  UNIQUE KEY      (`name`),
  KEY             (`category`, `visible`)
);

# Account
CREATE TABLE `{account}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `identity`        char(32)                        NOT NULL DEFAULT '',
  `email`           char(64)                        NOT NULL DEFAULT '',
  `name`            varchar(255)                    NOT NULL DEFAULT '',
  `credential`      varchar(255)                    NOT NULL DEFAULT '',
  `salt`            varchar(255)                    NOT NULL DEFAULT '',
  `active`          tinyint(1) UNSIGNED             NOT NULL DEFAULT 0,
  `time_created`    int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `time_activated`  int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `time_disabled`   int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `time_deleted`    int(10) UNSIGNED                NOT NULL DEFAULT 0,

  PRIMARY KEY     (`id`),
  UNIQUE KEY      (`identity`),
  UNIQUE KEY      (`email`),
  UNIQUE KEY      (`name`),
  KEY             (`active`)
);

# @todo, Profile

# Activation token
CREATE TABLE `{activation}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `uid`             int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `token`           varchar(255)                    NOT NULL DEFAULT '',
  `time_created`    int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `expire`          int(10) UNSIGNED                NOT NULL DEFAULT 0,

  PRIMARY KEY     (`id`),
  KEY             (`uid`),
  UNIQUE KEY      (`token`)
);

# Role
CREATE TABLE `{role}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `title`           varchar(255)                    NOT NULL DEFAULT '',
  `active`          tinyint(1) UNSIGNED             NOT NULL DEFAULT 0,

  PRIMARY KEY     (`id`)
);

# User role relation
CREATE TABLE `{user_role}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `uid`             int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `role`            int(10) UNSIGNED                NOT NULL DEFAULT 0,

  PRIMARY KEY     (`id`),
  UNIQUE KEY      (`uid`, `role`)
);

# @todo, resource

# @todo, permission

# Business definition
CREATE TABLE `{business}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `title`           varchar(255)                    NOT NULL DEFAULT '',
  `url`             varchar(255)                    NOT NULL DEFAULT '',
  `logo`            varchar(255)                    NOT NULL DEFAULT '',
  `name`            char(32)                        NOT NULL DEFAULT '',
  `key`             varchar(64)                     NOT NULL DEFAULT '',
  `secret`          varchar(255)                    NOT NULL DEFAULT '',
  `active`          tinyint(1) UNSIGNED             NOT NULL DEFAULT 0,
  `time_created`    int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `in_use`          tinyint(1) UNSIGNED             NOT NULL DEFAULT 0,

  PRIMARY KEY     (`id`),
  UNIQUE KEY      (`key`),
  UNIQUE KEY      (`name`)
);
