CREATE TABLE `ssca_sunday_msg` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` char(11) NOT NULL DEFAULT '',
  `date_ts` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `speaker` char(50) NOT NULL DEFAULT '',
  `theme`   char(100) NOT NULL DEFAULT '',
  `gospel`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `audio_file` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ssca_special_msg` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` char(11) NOT NULL DEFAULT '',
  `date_ts` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `speaker` char(50) NOT NULL DEFAULT '',
  `theme`   char(100) NOT NULL DEFAULT '',
  `gospel`  tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `audio_file` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ssca_request_error` (
  `ip` char(15) NOT NULL DEFAULT '0.0.0.0',
  `dateline` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `count` tinyint(2) UNSIGNED NOT NULL DEFAULT '0',
  `lockout` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`ip`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ssca_admin` (
  `user_id`          smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,    -- 用户ID，唯一标识
  `username`      char(16) NOT NULL,                                   -- 登录名
  `password`       char(32) NOT NULL,                                     -- 密码
  `name`              varchar(60) NOT NULL DEFAULT '',           -- 真实姓名
   `phone`            char(12) NOT NULL DEFAULT '',                -- 电话
  `email`              varchar(60) NOT NULL DEFAULT '',            --  电邮
  `last_ip`            char(15) NOT NULL DEFAULT '',                   --  上次登录IP
  `last_date`        int(10) UNSIGNED NOT NULL DEFAULT '0',    -- 上次登录时间
  `created_date` int(10) UNSIGNED NOT NULL DEFAULT '0',    -- 账户创建时间
  `hash`                 char(40) NOT NULL DEFAULT '',                 -- 哈希值，用于加密
 `type`                tinyint(1) NOT NULL DEFAULT '0',     -- 账户类型 0, 1, 2，3
 `group_id`          smallint(5) UNSIGNED NOT NULL DEFAULT 0, -- 细胞小组ID
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ssca_admin_active` (
  `sess_id` char(32) NOT NULL DEFAULT '',
  `user_id` smallint(5) UNSIGNED NOT NULL,
  `ip` char(15) NOT NULL DEFAULT '0.0.0.0',
  `dateline` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `expires` int(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`sess_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ssca_saints` (
  `id`               int(10) UNSIGNED NOT NULL AUTO_INCREMENT, 
  `name`             varchar(255) NOT NULL DEFAULT '',   
  `phone`            char(12) NOT NULL DEFAULT '',                -- 电话
  `email`              varchar(60) NOT NULL DEFAULT '',            --  电邮
  `address`         varchar(255) NOT NULL DEFAULT '',
  `house_head`      tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `head_id`         int(10) UNSIGNED NOT NULL DEFAULT 0,  -- 如果不是户主，则id of 户主
  `memo`            varchar(1024) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ssca_new_comers` (
  `user_id`          smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,    
  `name`             varchar(255) NOT NULL DEFAULT '',   
  `phone`            char(12) NOT NULL DEFAULT '',                -- 电话
  `email`              varchar(60) NOT NULL DEFAULT '',            --  电邮
  `referral`          varchar(255) NOT NULL DEFAULT '',  
  `address`         varchar(255) NOT NULL DEFAULT '',
  `baptized`        tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `contact`         tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `ts`              int(10) UNSIGNED NOT NULL DEFAULT '0',
  `created_date` char(10) NOT NULL DEFAULT '2014-03-09', 
  `email_sent`      tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ssca_cell_groups` (
  `id`          smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,    
  `name`             varchar(255) NOT NULL DEFAULT '',   -- 如：richmond 细胞小组 
  `leader_id`        smallint(5) UNSIGNED NOT NULL DEFAULT 0,                -- 电话
  `leader_name`      varchar(60) NOT NULL DEFAULT '',
  `total_members`    smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ssca_cellgroup_members` (
  `member_id`          int(10) UNSIGNED NOT NULL AUTO_INCREMENT, 
  `group_id`         smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `name`             varchar(255) NOT NULL DEFAULT '',   
  `phone`            char(12) NOT NULL DEFAULT '',                -- 电话
  `email`              varchar(60) NOT NULL DEFAULT '',            --  电邮
  `address`         varchar(255) NOT NULL DEFAULT '',
  `house_head`      tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `head_id`         int(10) UNSIGNED NOT NULL DEFAULT 0,  -- 如果不是户主，则member_id of 户主
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ssca_hymns` (
    `language` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
    `song_id`  smallint(5) UNSIGNED NOT NULL DEFAULT 0,
    `author_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
    `category_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
    `title`    varchar(128) NOT NULL DEFAULT '',
    `enter_date` char(32) NOT NULL DEFAULT '',
    PRIMARY KEY(`language`,`song_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ssca_praise` (
    `language` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
    `ps_id`  smallint(5) UNSIGNED NOT NULL DEFAULT 0, -- praise song id
    `song_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY(`language`,`ps_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ssca_hymn_sentences` (
    `sentence_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `sentence`varchar(128) NOT NULL DEFAULT '',
    PRIMARY KEY(`sentence_id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ssca_hymn_match` (
    `language` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
    `song_id`  smallint(5) UNSIGNED NOT NULL DEFAULT 0,
    `section` smallint(5) UNSIGNED NOT NULL DEFAULT 0,  -- 第几节
    `sentence_seq` smallint(5) UNSIGNED NOT NULL DEFAULT 0, -- 每节的歌词顺序
    `sentence_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY(`language`,`song_id`,`section`,`sentence_seq`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ssca_hymn_sections` (
    `language` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
    `song_id`  smallint(5) UNSIGNED NOT NULL DEFAULT 0,
    `section` smallint(5) UNSIGNED NOT NULL DEFAULT 0,  -- 第几节
    `chorus_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0, -- 每节的副歌，0表示没有
    PRIMARY KEY(`language`,`song_id`,`section`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ssca_hymn_chorus_match` (
    `language` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
    `chorus_id`  smallint(5) UNSIGNED NOT NULL DEFAULT 0,
    `sentence_seq` smallint(5) UNSIGNED NOT NULL DEFAULT 0,  -- 
    `sentence_id` smallint(5) UNSIGNED NOT NULL DEFAULT 0, -- 副歌对应的句子
    PRIMARY KEY(`language`,`chorus_id`,`sentence_seq`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

