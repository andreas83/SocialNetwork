CREATE TABLE User (
  id INT(10) unsigned not null AUTO_INCREMENT PRIMARY KEY,
  mail VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  about text not null,
  settings text not null,
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 


RENAME TABLE content to Content;


CREATE TABLE  Comment (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 

CREATE TABLE  Score (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `content_id` int(10) unsigned NOT NULL,
  `type` enum('add', 'sub') NOT NULL,
  `date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`), UNIQUE(`user_id`, `content_id`, `type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

Alter table User add column 
auth_cookie varchar(32);

CREATE TABLE  hashtags (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hashtag` VARCHAR(255) NOT NULL,
  `pop` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`), UNIQUE(`id`, `hashtag`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8

Alter table User add column 
api_key varchar(32);

RENAME TABLE hashtags to Hashtags;
Alter table Hashtags drop index id;
Alter table Hashtags add unique index hashtag (hashtag);

