<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428125840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP FOREIGN KEY FK_Address_User
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address CHANGE user_id user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_address_user ON address
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D4E6F81A76ED395 ON address (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD CONSTRAINT FK_Address_User FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` DROP FOREIGN KEY FK_Order_User
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` ADD total INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_order_user ON `order`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F5299398A76ED395 ON `order` (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` ADD CONSTRAINT FK_Order_User FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_details DROP FOREIGN KEY FK_OrderDetails_Order
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_details CHANGE price price INT NOT NULL, CHANGE total total INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_orderdetails_order ON order_details
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_845CA2C17C78A4E3 ON order_details (binded_order_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_details ADD CONSTRAINT FK_OrderDetails_Order FOREIGN KEY (binded_order_id) REFERENCES `order` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP FOREIGN KEY FK_Product_Category
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP stock
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_product_category ON product
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT FK_Product_Category FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COMMENT '(DC2Type:json)'
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX uniq_user_email ON user
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address CHANGE user_id user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_d4e6f81a76ed395 ON address
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX FK_Address_User ON address (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` DROP total
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_f5299398a76ed395 ON `order`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX FK_Order_User ON `order` (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_details DROP FOREIGN KEY FK_845CA2C17C78A4E3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_details CHANGE price price DOUBLE PRECISION NOT NULL, CHANGE total total DOUBLE PRECISION NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_845ca2c17c78a4e3 ON order_details
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX FK_OrderDetails_Order ON order_details (binded_order_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C17C78A4E3 FOREIGN KEY (binded_order_id) REFERENCES `order` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD stock INT DEFAULT 0 NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_d34a04ad12469de2 ON product
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX FK_Product_Category ON product (category_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX uniq_8d93d649e7927c74 ON `user`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_USER_EMAIL ON `user` (email)
        SQL);
    }
}
