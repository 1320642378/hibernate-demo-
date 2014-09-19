CREATE  TABLE IF NOT EXISTS `#__pf_questionnaires` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `asset_id` INT UNSIGNED NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.' ,
  `title` VARCHAR(255) NOT NULL ,
  `alias` VARCHAR(255) NOT NULL ,
  `description` MEDIUMTEXT NULL ,
  `state` TINYINT NOT NULL DEFAULT '0' ,
  `image` VARCHAR(255) NOT NULL COMMENT 'background image' ,
  `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `created_by` INT UNSIGNED NOT NULL ,
  `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `modified_by` INT UNSIGNED NOT NULL ,
  `publish_up` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `publish_down` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `params` VARCHAR(5201) NOT NULL ,
  `ordering` INT NOT NULL DEFAULT '0' ,
  `metakey` TEXT NULL ,
  `metadesc` TEXT NULL ,
  `access` INT UNSIGNED NOT NULL DEFAULT '0' ,
  `hits` INT UNSIGNED NOT NULL DEFAULT '0' ,
  `metadata` TEXT NULL ,
  `language` CHAR(7) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idx_access` (`access` ASC) ,
  INDEX `idx_state` (`state` ASC) ,
  INDEX `idx_createdby` (`created_by` ASC) ,
  INDEX `idx_language` (`language` ASC) )
ENGINE=MyISAM  AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE  TABLE IF NOT EXISTS `#__pf_questions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `questionnaire_id` INT UNSIGNED NOT NULL DEFAULT '0' ,
  `question` VARCHAR(255) NOT NULL DEFAULT '' ,
  `question_full` MEDIUMTEXT NULL ,
  `question_type` CHAR(20) NOT NULL ,
  `state` TINYINT NOT NULL DEFAULT '0' ,
  `answers_ordering` CHAR(20) NOT NULL DEFAULT '' ,
  `min_answers` INT UNSIGNED NOT NULL DEFAULT '1' ,
  `max_answers` INT UNSIGNED NOT NULL DEFAULT '1' ,
  `auto_next` TINYINT UNSIGNED NOT NULL DEFAULT '1' ,
  `created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `created_by` INT UNSIGNED NOT NULL DEFAULT '0' ,
  `modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' ,
  `modified_by` INT UNSIGNED NOT NULL DEFAULT '0' ,
  `ordering` INT NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) ,
  INDEX `idx_questionnaire` (`questionnaire_id` ASC) ,
  INDEX `idx_state` (`state` ASC) ,
  INDEX `idx_createdby` (`created_by` ASC) )
ENGINE=MyISAM  AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE  TABLE IF NOT EXISTS `#__pf_answers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `question_id` INT UNSIGNED NOT NULL ,
  `value` VARCHAR(255) NULL ,
  `label` MEDIUMTEXT NULL ,
  `is_separator` TINYINT UNSIGNED NOT NULL DEFAULT '0' ,
  `is_default` TINYINT UNSIGNED NOT NULL DEFAULT '0' ,
  `ordering` INT NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) ,
  INDEX `idx_question` (`question_id` ASC) )
ENGINE=MyISAM  AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE  TABLE IF NOT EXISTS `#__pf_rules` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `ref_table` VARCHAR(100) NOT NULL DEFAULT 'content' ,
  `content_id` INT UNSIGNED NOT NULL ,
  `answer_id` INT UNSIGNED NOT NULL ,
  `rule` CHAR(3) NOT NULL DEFAULT '' ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `idx_rules` (`ref_table` ASC, `content_id` ASC, `answer_id` ASC) ,
  INDEX `idx_rule` (`rule` ASC)
   )
ENGINE=MyISAM  AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;