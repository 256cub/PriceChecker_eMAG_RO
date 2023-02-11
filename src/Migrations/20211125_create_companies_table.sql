-- -----------------------------------------------------
-- Table `companies`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `companies`;

CREATE TABLE `companies` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR ( 255 ) COLLATE utf8_unicode_ci NOT NULL,
    `url` VARCHAR ( 255 ) COLLATE utf8_unicode_ci NOT NULL,
    `date_updated` datetime DEFAULT NULL,
    `date_created` datetime DEFAULT NULL,
    PRIMARY KEY ( `id` )
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = INNODB;


-- CREATE DATETIME TRIGGERS
DROP TRIGGER IF EXISTS `companies_BEFORE_INSERT`;
CREATE DEFINER = CURRENT_USER TRIGGER `companies_BEFORE_INSERT` BEFORE INSERT ON `companies` FOR EACH ROW SET NEW.date_created = NOW();

DROP TRIGGER IF EXISTS `companies_BEFORE_UPDATE`;
CREATE DEFINER = CURRENT_USER TRIGGER `companies_BEFORE_UPDATE` BEFORE UPDATE ON `companies` FOR EACH ROW SET NEW.date_updated = NOW();