<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211215164733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE `companies` (`id` INT NOT NULL AUTO_INCREMENT, `name` VARCHAR ( 255 ) COLLATE utf8_unicode_ci NOT NULL, `url` VARCHAR ( 255 ) COLLATE utf8_unicode_ci NOT NULL, `date_updated` datetime DEFAULT NULL, `date_created` datetime DEFAULT NULL, PRIMARY KEY ( `id` )) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = INNODB");
        $this->addSql("CREATE DEFINER = CURRENT_USER TRIGGER `companies_BEFORE_INSERT` BEFORE INSERT ON `companies` FOR EACH ROW SET NEW.date_created = NOW()");
        $this->addSql("CREATE DEFINER = CURRENT_USER TRIGGER `companies_BEFORE_UPDATE` BEFORE UPDATE ON `companies` FOR EACH ROW SET NEW.date_updated = NOW()");

        $this->addSql("CREATE TABLE `products` (`id` INT AUTO_INCREMENT NOT NULL, `sku` VARCHAR ( 12 ) NOT NULL, `name` VARCHAR ( 255 ) NOT NULL, `url` VARCHAR ( 255 ) NOT NULL, `minim_price` DOUBLE ( 10, 2 ) NOT NULL, `companies_id` INT NOT NULL, `date_updated` DATETIME NULL, `date_created` DATETIME NULL, PRIMARY KEY ( `id` ), INDEX fk_products_companies1_idx ( `companies_id` ASC ), CONSTRAINT fk_products_companies1 FOREIGN KEY ( `companies_id` ) REFERENCES `companies` ( `id` ) ON DELETE CASCADE ON UPDATE NO ACTION) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = INNODB");
        $this->addSql("CREATE DEFINER = CURRENT_USER TRIGGER `products_BEFORE_INSERT` BEFORE INSERT ON `products` FOR EACH ROW SET NEW.date_created = NOW()");
        $this->addSql("CREATE DEFINER = CURRENT_USER TRIGGER `products_BEFORE_UPDATE` BEFORE UPDATE ON `products` FOR EACH ROW SET NEW.date_updated = NOW()");

        $this->addSql("CREATE TABLE `process` (`id` INT AUTO_INCREMENT NOT NULL, `status` enum('NOT_STARTED', 'RUNNING', 'DONE', 'ERROR') NOT NULL, `report` JSON DEFAULT NULL, `date_updated` DATETIME NULL, `date_created` DATETIME NULL, PRIMARY KEY ( `id` )) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = INNODB");
        $this->addSql("CREATE DEFINER = CURRENT_USER TRIGGER `process_BEFORE_INSERT` BEFORE INSERT ON `process` FOR EACH ROW SET NEW.date_created = NOW()");
        $this->addSql("CREATE DEFINER = CURRENT_USER TRIGGER `process_BEFORE_UPDATE` BEFORE UPDATE ON `process` FOR EACH ROW SET NEW.date_updated = NOW()");

        $this->addSql("CREATE TABLE `process_product` (`id` INT AUTO_INCREMENT NOT NULL, `process_id` INT NOT NULL, `product_id` INT NOT NULL, `current_price` DOUBLE ( 10, 2 ) DEFAULT NULL, `updated_price` DOUBLE ( 10, 2 ) DEFAULT NULL, `status` ENUM('PRICE_SHOULD_BE_UPDATED', 'PRICE_UPDATED', 'PRICE_SHOULD_NOT_BE_UPDATED', 'ERROR') NOT NULL, `reason` VARCHAR ( 255 ) NOT NULL, `date_updated` DATETIME NULL, `date_created` DATETIME NULL, PRIMARY KEY ( `id` ), INDEX fk_process_product_process1_idx ( `process_id` ASC ), CONSTRAINT fk_process_product_process1 FOREIGN KEY ( `process_id` ) REFERENCES `process` ( `id` ) ON DELETE CASCADE ON UPDATE NO ACTION, INDEX fk_process_product_products1_idx ( `product_id` ASC ), CONSTRAINT fk_process_product_products1 FOREIGN KEY ( `product_id` ) REFERENCES `products` ( `id` ) ON DELETE CASCADE ON UPDATE NO ACTION) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = INNODB");
        $this->addSql("CREATE DEFINER = CURRENT_USER TRIGGER `process_product_BEFORE_INSERT` BEFORE INSERT ON `process_product` FOR EACH ROW SET NEW.date_created = NOW()");
        $this->addSql("CREATE DEFINER = CURRENT_USER TRIGGER `process_product_BEFORE_UPDATE` BEFORE UPDATE ON `process_product` FOR EACH ROW SET NEW.date_updated = NOW()");

    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE IF EXISTS `companies`");
        $this->addSql("DROP TABLE IF EXISTS `products`");
        $this->addSql("DROP TABLE IF EXISTS `process`");
        $this->addSql("DROP TABLE IF EXISTS `process_product`");
    }
}
