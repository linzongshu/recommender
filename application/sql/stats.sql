# STATS LOG

# Users who mark tags to items or other users
CREATE TABLE `{tag_marker}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  # Item ID
  `item`            int(10) UNSIGNED                NOT NULL DEFAULT 0,
  # Tag ID
  `tid`             int(10) UNSIGNED                NOT NULL DEFAULT 0,
  # Marker ID
  `marker`          int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `business`        tinyint(1)                      NOT NULL DEFAULT 0,
  `time_created`    int(10) UNSIGNED                NOT NULL DEFAULT 0,

  PRIMARY KEY     (`id`),
  KEY             (`item`)
);

# Scores a user show to an item
CREATE TABLE `{user_ratings}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `uid`             int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `item`            int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `business`        tinyint(1)                      NOT NULL DEFAULT 0,
  `rating`          tinyint(1)                      NOT NULL DEFAULT 0,
  `raw_data`        text                            DEFAULT NULL,

  PRIMARY KEY     (`id`),
  KEY             (`uid`),
  KEY             (`item`)
);

# Whether a recommendation is useful
CREATE TABLE `{feedback}` (
  `id`              int(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `uid`             int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `item`            int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `recommend`       int(10) UNSIGNED                NOT NULL DEFAULT 0,
  `business`        tinyint(1)                      NOT NULL DEFAULT 0,
  `useful`          tinyint(1)                      NOT NULL DEFAULT 0,

  PRIMARY KEY     (`id`),
  KEY             (`item`)
);
