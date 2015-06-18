# TAG MODEL

# Temporary table for generating id
CREATE TABLE `{temp}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,

  PRIMARY KEY     (`id`)
);

# Tag details
CREATE TABLE `{detail}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `name`            char(32)                        NOT NULL DEFAULT '',
  # Submitter, user ID from your user center other than account here
  `uid`             int(10) UNSIGNED                NOT NULL DEFAULT 0,
  # Marked count
  `count`           int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `category`        int(10) UNSIGNED                NOT NULL DEFAULT 0,
  # Tag type, whether is core tag or tag from user
  `type`            enum('core','user')             NOT NULL DEFAULT 'user',
  # Count of item include the tag
  `item_count`      int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `active`          tinyint(1) UNSIGNED             NOT NULL DEFAULT 0,
  `time_created`    int(10) UNSIGNED                NOT NULL DEFAULT 0,

  PRIMARY KEY     (`id`),
  KEY             (`uid`),
  KEY             (`name`),
  KEY             (`category`)
);

# @todo, category
CREATE TABLE `{category}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `left`            int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `right`           int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `depth`           int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `title`           varchar(255)                    NOT NULL DEFAULT '',

  PRIMARY KEY     (`id`),
  KEY             (`depth`)
);

# Relevancy between tags
CREATE TABLE `{relevancy}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `tid`             int(10) UNSIGNED                NOT NULL DEFAULT 0,
  # Related tag ID
  `rtid`            int(10) UNSIGNED                NOT NULL DEFAULT 0,
  # Relevancy result source, marked by editor or calculated by script
  `source`          enum('editor','script')         NOT NULL DEFAULT 'editor',
  `relevancy`       float(8,6)                      NOT NULL DEFAULT 0,

  PRIMARY KEY     (`id`),
  UNIQUE KEY      (`tid`, `rtid`, `source`),
  KEY             (`tid`, `source`),
  KEY             (`relevancy`)
);
