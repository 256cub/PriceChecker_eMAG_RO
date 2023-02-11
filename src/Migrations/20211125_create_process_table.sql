-- -----------------------------------------------------
-- Table `process`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `process`;

CREATE TABLE `process` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `status` enum('NOT_STARTED', 'RUNNING', 'DONE', 'ERROR') NOT NULL,
    `report` JSON DEFAULT NOT NULL,
    `date_updated` DATETIME NULL,
    `date_created` DATETIME NULL,
    PRIMARY KEY ( `id` )
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = INNODB;


-- CREATE DATETIME TRIGGERS
DROP TRIGGER IF EXISTS `process_BEFORE_INSERT`;
CREATE DEFINER = CURRENT_USER TRIGGER `process_BEFORE_INSERT` BEFORE INSERT ON `process` FOR EACH ROW SET NEW.date_created = NOW();

DROP TRIGGER IF EXISTS `process_BEFORE_UPDATE`;
CREATE DEFINER = CURRENT_USER TRIGGER `process_BEFORE_UPDATE` BEFORE UPDATE ON `process` FOR EACH ROW SET NEW.date_updated = NOW();