USE p2430705db;
DROP TABLE IF EXISTS tbl_session;
CREATE TABLE tbl_session(
session_id varbinary(100)  NOT NULL,
session_variable varbinary(100)   NOT NULL,
session_value varbinary(100) NOT NULL,
session_timestamp timestamp   NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
                        );

USE p2430705db;
DROP TABLE IF EXISTS tbl_user;
CREATE TABLE IF NOT EXISTS tbl_user(
user_id INT NOT NULL AUTO_INCREMENT,
user_mobile varbinary(100)  NOT NULL,
user_username varbinary(100)  NOT NULL,
user_password varbinary(100)  NOT NULL,
user_timestamp timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
PRIMARY KEY (user_id)
                                   );

DROP TABLE IF EXISTS `tbl_telemetry`;
CREATE TABLE IF NOT EXISTS `tbl_telemetry` (
  `tele_id` int(10) NOT NULL AUTO_INCREMENT,
  `tele_device_id` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `tele_created_id` int(10) NOT NULL,
  `tele_user_id` int(11) DEFAULT NULL,
  `tele_data` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `tele_switch1` char(3) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `tele_switch2` char(3) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `tele_switch3` char(3) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `tele_switch4` char(3) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `tele_motor` char(10) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `tele_keypad` int(1) NOT NULL,
  `tele_temperature` float NOT NULL,
  `tele_created` timestamp NULL DEFAULT NULL,
  `tele_added_m2m` timestamp NULL DEFAULT NULL,
  `tele_imported` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`tele_id`));
