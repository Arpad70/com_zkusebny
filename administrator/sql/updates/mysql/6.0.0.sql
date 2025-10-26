CREATE TABLE IF NOT EXISTS `#__zkusebny_reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `slot_start` datetime NOT NULL,
  `slot_end` datetime NOT NULL,
  `paid_hours` tinyint(2) NOT NULL DEFAULT 1,
  `unlock_time` datetime DEFAULT NULL,
  `real_end` datetime DEFAULT NULL,
  `state` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `#__zkusebny_config` (
  `key` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `#__zkusebny_config` (`key`, `value`) VALUES
('packup_minutes', '10'),
('shelly_ip', '192.168.1.100');

CREATE TABLE IF NOT EXISTS `#__zkusebny_push` (  
  `user_id` int(11) NOT NULL,  
  `player_id` varchar(64) NOT NULL,  
  PRIMARY KEY (`user_id`)  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;  

ALTER TABLE `#__users` ADD `phone` VARCHAR(20) NULL DEFAULT NULL AFTER `email`; 
ALTER TABLE `#__zkusebny_reservations` ADD `canceled_at` DATETIME NULL DEFAULT NULL AFTER `real_end`;
ALTER TABLE `#__zkusebny_reservations` ADD `cancellation_reason` VARCHAR(255) NULL DEFAULT NULL AFTER `canceled_at`;
UPDATE `#__zkusebny_config` SET `value` = '15' WHERE `key` = 'packup_minutes';
UPDATE `#__zkusebny_config` SET `value` = '192.168.1.100' WHERE `key` = 'shelly_ip';
