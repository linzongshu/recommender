# USER MODEL

# User details
CREATE TABLE `{detail}` (
  # User ID
  `id`              int(10) UNSIGNED                NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `username`        char(32)                        NOT NULL DEFAULT '',
  # Nick name
  `name`            varchar(255)                    NOT NULL DEFAULT '',

  UNIQUE KEY      (`username`)
);

# User extend details
CREATE TABLE `{meta}` (
  `uid`             int(10) UNSIGNED                NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `meta`            text                            DEFAULT NULL
);

# Relevancy between users
CREATE TABLE `{relevancy}` (
  `id`              int(10) UNSIGNED                NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `uid`             int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `ruid`            int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `relevancy`       float(8,6)                      NOT NULL DEFAULT 0,

  UNIQUE KEY      (`uid`, `ruid`)
);

# Relevancy between users and tags
CREATE TABLE `{tag}` (
  `id`              int(10) UNSIGNED                NOT NULL PRIMARY KEY AUTO_INCREMENT,
  # User ID
  `uid`             int(10) UNSIGNED                NOT NULL DEFAULT 0,
  # Tag ID
  `tid`             int(10) UNSIGNED                NOT NULL DEFAULT 0,
  # Tag source
  `source`          enum('self','stats','user')     NOT NULL DEFAULT 'stats',
  `relevancy`       int(10) UNSIGNED                NOT NULL DEFAULT 0,

  KEY             (`uid`, `tid`),
  KEY             (`relevancy`)
);
