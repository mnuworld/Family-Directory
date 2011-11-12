
DROP TABLE IF EXISTS `dir_cities`;

CREATE TABLE IF NOT EXISTS `dir_cities` (
  `city_id` int(5) NOT NULL AUTO_INCREMENT,
  `city` varchar(50) NOT NULL,
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;


DROP TABLE IF EXISTS `dir_countries`;

CREATE TABLE IF NOT EXISTS `dir_countries` (
  `country_id` int(5) NOT NULL AUTO_INCREMENT,
  `country` varchar(50) NOT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

DROP TABLE IF EXISTS `dir_education`;

CREATE TABLE IF NOT EXISTS `dir_education` (
  `education_id` int(5) NOT NULL AUTO_INCREMENT,
  `education` varchar(50) NOT NULL,
  PRIMARY KEY (`education_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

DROP TABLE IF EXISTS `dir_logs_activity`;

CREATE TABLE IF NOT EXISTS `dir_logs_activity` (
  `user_id` int(5) NOT NULL,
  `add_date` varchar(255) DEFAULT NULL,
  `add_by_id` int(5) DEFAULT NULL,
  `edit_date` varchar(255) DEFAULT NULL,
  `edit_by_id` int(5) DEFAULT NULL,
  `view_date` varchar(255) DEFAULT NULL,
  `view_by_id` int(5) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `dir_logs_emails`;

CREATE TABLE IF NOT EXISTS `dir_logs_emails` (
  `email_id` int(11) NOT NULL AUTO_INCREMENT,
  `from_addr` varchar(50) NOT NULL,
  `to_addr` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` varchar(255) NOT NULL,
  `sent_date` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`email_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=352 ;

DROP TABLE IF EXISTS `dir_logs_errors`;

CREATE TABLE IF NOT EXISTS `dir_logs_errors` (
  `error_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(5) DEFAULT NULL,
  `referer_page` varchar(255) DEFAULT NULL,
  `error_code` varchar(255) DEFAULT NULL,
  `error_date` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`error_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;



DROP TABLE IF EXISTS `dir_logs_visits`;

CREATE TABLE IF NOT EXISTS `dir_logs_visits` (
  `unique_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(5) DEFAULT NULL,
  `visit_date` varchar(255) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `page_visited` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`unique_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6854 ;






DROP TABLE IF EXISTS `dir_marital_status`;
CREATE TABLE IF NOT EXISTS `dir_marital_status` (
  `marital_status_id` tinyint(1) NOT NULL,
  `marital_status` varchar(50) NOT NULL,
  PRIMARY KEY (`marital_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `dir_profession`;

CREATE TABLE IF NOT EXISTS `dir_profession` (
  `profession_id` int(5) NOT NULL AUTO_INCREMENT,
  `profession` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`profession_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;



DROP TABLE IF EXISTS `dir_relations`;

CREATE TABLE IF NOT EXISTS `dir_relations` (
  `user_id` int(5) NOT NULL,
  `father_id` int(5) NOT NULL DEFAULT '0',
  `mother_id` int(5) NOT NULL DEFAULT '0',
  `spouse_id` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `dir_states`;

CREATE TABLE IF NOT EXISTS `dir_states` (
  `state_id` int(5) NOT NULL AUTO_INCREMENT,
  `state` varchar(50) NOT NULL,
  PRIMARY KEY (`state_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;


DROP TABLE IF EXISTS `dir_users_access`;

CREATE TABLE IF NOT EXISTS `dir_users_access` (
  `user_id` int(5) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `password` blob NOT NULL,
  `email` varchar(50) NOT NULL,
  `admin_access` tinyint(1) NOT NULL DEFAULT '0',
  `add_access` tinyint(1) NOT NULL DEFAULT '0',
  `edit_access` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=296 ;


DROP TABLE IF EXISTS `dir_users_data`;

CREATE TABLE IF NOT EXISTS `dir_users_data` (
  `user_id` int(5) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `preferred_name` varchar(50) DEFAULT NULL,
  `gender` tinyint(1) NOT NULL,
  `street_1` varchar(100) DEFAULT NULL,
  `street_2` varchar(100) DEFAULT NULL,
  `street_3` varchar(100) DEFAULT NULL,
  `city_id` int(5) NOT NULL DEFAULT '0',
  `state_id` int(5) NOT NULL DEFAULT '0',
  `zipcode` varchar(10) DEFAULT NULL,
  `country_id` int(5) NOT NULL DEFAULT '0',
  `dob` varchar(25) DEFAULT NULL,
  `pob` varchar(50) DEFAULT NULL,
  `dod` varchar(255) DEFAULT NULL,
  `pod` varchar(50) DEFAULT NULL,
  `dom` varchar(255) DEFAULT NULL,
  `pom` varchar(50) DEFAULT NULL,
  `education_id` int(5) NOT NULL DEFAULT '0',
  `profession_id` int(5) NOT NULL DEFAULT '0',
  `number_home` varchar(25) DEFAULT NULL,
  `number_cell` varchar(25) DEFAULT NULL,
  `marital_status_id` tinyint(1) NOT NULL DEFAULT '1',
  `show_picture` tinyint(1) NOT NULL DEFAULT '1',
  `show_email` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `dir_users_forgot_password`;

CREATE TABLE IF NOT EXISTS `dir_users_forgot_password` (
  `token_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(5) NOT NULL,
  `token` blob NOT NULL,
  `request_date` varchar(255) NOT NULL,
  `active_token` tinyint(1) NOT NULL DEFAULT '1',
  `deactivate_date` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`token_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;