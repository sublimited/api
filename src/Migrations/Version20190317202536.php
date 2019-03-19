<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190317202536 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');


        // customers
        $this->addSql("
          create table customers (
          id bigint(20) not null auto_increment,
          uuid bigint(20) default null,
          first_name varchar(255),
          last_name varchar(255),
          date_of_birth int(11),
          status enum('new', 'pending', 'in review', 'approved', 'inactive', 'deleted') not null default 'new',
          created_at int(11) default null,
          updated_at int(11) default null,
          deleted_at int(11) default null,
          products bigint(20),
          primary key(id),
          key uuid_status (uuid, status)
          ) ENGINE=InnoDB AUTO_INCREMENT=1000000 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        // products
        $this->addSql("
          create table products (
          id bigint(20) not null auto_increment,
          issn bigint(20) default null,
          name varchar(255),
          status enum('new', 'pending', 'in review', 'approved', 'inactive', 'deleted') not null default 'new',
          created_at int(11) default null,
          updated_at int(11) default null,
          deleted_at int(11) default null,
          customer bigint(20) default null,
          primary key(id),
          key customer (customer),
          key status (status),
          foreign key (customer) references customers (id) on delete set null
          )
          ENGINE=InnoDB AUTO_INCREMENT=1000000 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        // lets have some api keys
        $this->addSql("
          create table customer_api_keys (
          id bigint(20) not null auto_increment,
          api_key varchar(37) not null,
          status enum('new', 'pending', 'in review', 'approved', 'inactive', 'deleted') not null default 'new',
          created_at int(11) default null,
          updated_at int(11) default null,
          deleted_at int(11) default null,
          customer bigint(20) default null,
          primary key(id),
          key api_key_status (api_key, status),
          foreign key (customer) references customers (id)
          )
          ENGINE=InnoDB AUTO_INCREMENT=1000000 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        // logs
        $this->addSql("
          create table logs (
          id int(11) not null auto_increment,
          info text default null,
          created_at int(11) default null,
          primary key(id)
          )
          ENGINE=InnoDB AUTO_INCREMENT=1000000 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        // lets add some triggers
        $this->addSql('
          create trigger customers_insert before insert on customers for each row
          begin
            if (new.created_at is null)
            then
              set new.created_at = unix_timestamp();
            end if;
            end
          ');

        $this->addSql('
          create trigger customers_update before update on customers for each row
          begin
            if(new.status = \'deleted\' and new.deleted_at is null) then
              set new.deleted_at = unix_timestamp();
              update customer_api_keys set status=\'deleted\' where customer=new.id;
            end if;
            set new.updated_at = unix_timestamp();
          end
          ');

        $this->addSql('
        create trigger products_insert before insert on products for each row
        begin
          if (new.created_at is null)
          then
            set new.created_at = unix_timestamp();
          end if;
        end
          ');

        $this->addSql('
        create trigger products_update before update on products for each row
        begin
          if(new.status = \'deleted\' and new.deleted_at is null) then
            set new.deleted_at = unix_timestamp();
          end if;
          set new.updated_at = unix_timestamp();
        end
          ');



    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE customers');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE customer_api_keys');

        $this->addSql('drop trigger if exists customers_insert');
        $this->addSql('drop trigger if exists customers_update');
        $this->addSql('drop trigger if exists products_insert');
        $this->addSql('drop trigger if exists products_update');

    }
}
