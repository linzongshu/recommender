# ITEM MODEL

# Item details
CREATE TABLE `{detail}` (
  # Item ID
  `iid`             int(10) UNSIGNED                NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `business`        tinyint(1)                      NOT NULL DEFAULT 0,
  # Item title
  `title`           varchar(255)                    NOT NULL DEFAULT '',
  `url`             varchar(255)                    NOT NULL DEFAULT ''
);

# Item extend details
CREATE TABLE `{meta}` (
  # Item ID
  `iid`             int(10) UNSIGNED                NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `meta`            text                            DEFAULT NULL
);

# Relevancy between items
CREATE TABLE `{relevancy}` (
  `id`              int(10) UNSIGNED                NOT NULL PRIMARY KEY AUTO_INCREMENT,
  # Item ID
  `iid`             int(10) UNSIGNED                NOT NULL DEFAULT 0,
  # Related item ID
  `rid`             int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `relevancy`       float(8,6)                      NOT NULL DEFAULT 0,

  UNIQUE KEY      (`iid`, `rid`),
  KEY             (`relevancy`)
);

# Relevancy between items and tags
CREATE TABLE `{tag}` (
  `id`              int(10) UNSIGNED                NOT NULL PRIMARY KEY AUTO_INCREMENT,
  # Item ID
  `iid`             int(10) UNSIGNED                NOT NULL DEFAULT 0,
  # Tag ID
  `tid`             int(10) UNSIGNED                NOT NULL DEFAULT 0,
  # Tag source
  `source`          enum('keyword','title','content','user') NOT NULL DEFAULT 'keyword',
  `relevancy`       int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `business`        tinyint(1)                      NOT NULL DEFAULT 0,

  KEY             (`iid`, `tid`),
  KEY             (`relevancy`)
);
