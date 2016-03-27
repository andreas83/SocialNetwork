CREATE TABLE User (
  id INT(10) unsigned not null AUTO_INCREMENT PRIMARY KEY,
  mail VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  about text not null,
  settings text not null,
  auth_cookie varchar(32) default NULL,
  api_key varchar(32) default NULL,
  isAdmin tinyint DEFAULT 0,
  created DATETIME,
  modified DATETIME
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE  Content (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  `media` text COLLATE utf8_unicode_ci NOT NULL,
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE `Comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8;

CREATE TABLE  Score (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `type` enum('add', 'sub') NOT NULL,
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`), UNIQUE(`user_id`, `content_id`, `type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE  Hashtags (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hashtag` VARCHAR(255) NOT NULL,
  `pop` int(10) unsigned NOT NULL,
   modified DATETIME
   PRIMARY KEY (`id`), UNIQUE(`hashtag`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


CREATE TABLE  Notification (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `to_user_id` int(10) unsigned NOT NULL,
  `from_user_id` int(10) unsigned NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `level` int(1) unsigned NOT NULL,
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ;


