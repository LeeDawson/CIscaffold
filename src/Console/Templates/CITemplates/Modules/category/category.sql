 

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

 
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_pid` int(10) unsigned NOT NULL DEFAULT '0',
  `category_name` varchar(16) NOT NULL,
  `category_type` tinyint(4) NOT NULL DEFAULT '1',
  `date_add` int(10) unsigned NOT NULL DEFAULT '0',
  `date_update` int(10) unsigned NOT NULL DEFAULT '0',
  `category_image` varchar(512) DEFAULT NULL,
  `category_meta` varchar(64) DEFAULT NULL,
  `if_show` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `sortrank` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;

 
SET FOREIGN_KEY_CHECKS = 1;
