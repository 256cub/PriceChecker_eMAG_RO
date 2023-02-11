-- -----------------------------------------------------
-- Table `products`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `sku` VARCHAR ( 12 ) NOT NULL,
    `name` VARCHAR ( 255 ) NOT NULL,
    `url` VARCHAR ( 255 ) NOT NULL,
    `minim_price` DOUBLE ( 10, 2 ) NOT NULL,
    `companies_id` INT NOT NULL,
    `date_updated` DATETIME NULL,
    `date_created` DATETIME NULL,
    PRIMARY KEY ( `id` ),
    INDEX fk_products_companies1_idx ( `companies_id` ASC ),
    CONSTRAINT fk_products_companies1 FOREIGN KEY ( `companies_id` ) REFERENCES `companies` ( `id` ) ON DELETE CASCADE ON UPDATE NO ACTION
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = INNODB;


-- CREATE DATETIME TRIGGERS
DROP TRIGGER IF EXISTS `products_BEFORE_INSERT`;
CREATE DEFINER = CURRENT_USER TRIGGER `products_BEFORE_INSERT` BEFORE INSERT ON `products` FOR EACH ROW SET NEW.date_created = NOW();

DROP TRIGGER IF EXISTS `products_BEFORE_UPDATE`;
CREATE DEFINER = CURRENT_USER TRIGGER `products_BEFORE_UPDATE` BEFORE UPDATE ON `products` FOR EACH ROW SET NEW.date_updated = NOW();