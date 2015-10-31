ALTER TABLE `#__questions_userprofile` ADD COLUMN `logdate` date AFTER `rank`;
ALTER TABLE `#__questions_userprofile` drop `image`;