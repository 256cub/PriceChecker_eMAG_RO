-- -----------------------------------------------------
-- Table `process_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `process_product`;

CREATE TABLE `process_product` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `process_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `current_price` DOUBLE ( 10, 2 ) DEFAULT NULL,
    `updated_price` DOUBLE ( 10, 2 ) DEFAULT NULL,
    `status` ENUM('PRICE_SHOULD_BE_UPDATED', 'PRICE_UPDATED', 'PRICE_SHOULD_NOT_BE_UPDATED', 'ERROR') NOT NULL,
    `reason` VARCHAR ( 255 ) NOT NULL,
    `date_updated` DATETIME NULL,
    `date_created` DATETIME NULL,
    PRIMARY KEY ( `id` ),
    INDEX fk_process_product_process1_idx ( `process_id` ASC ),
    CONSTRAINT fk_process_product_process1 FOREIGN KEY ( `process_id` ) REFERENCES `process` ( `id` ) ON DELETE CASCADE ON UPDATE NO ACTION,
    INDEX fk_process_product_products1_idx ( `product_id` ASC ),
    CONSTRAINT fk_process_product_products1 FOREIGN KEY ( `product_id` ) REFERENCES `products` ( `id` ) ON DELETE CASCADE ON UPDATE NO ACTION
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = INNODB;


-- CREATE DATETIME TRIGGERS
DROP TRIGGER IF EXISTS `process_product_BEFORE_INSERT`;
CREATE DEFINER = CURRENT_USER TRIGGER `process_product_BEFORE_INSERT` BEFORE INSERT ON `process_product` FOR EACH ROW SET NEW.date_created = NOW();

DROP TRIGGER IF EXISTS `process_product_BEFORE_UPDATE`;
CREATE DEFINER = CURRENT_USER TRIGGER `process_product_BEFORE_UPDATE` BEFORE UPDATE ON `process_product` FOR EACH ROW SET NEW.date_updated = NOW();