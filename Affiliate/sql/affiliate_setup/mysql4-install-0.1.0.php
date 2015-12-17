<?php
$installer = $this;
$installer->startSetup();
$installer->run ( "
CREATE TABLE IF NOT EXISTS {$this->getTable('affiliate/account')} (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Accoun ID',
  `email` varchar(55) NOT NULL,
  `firstname` varchar(55) NOT NULL,
  `lastname` int(55) NOT NULL,
  `password` varchar(255) NOT NULL,
  `paypal email` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `notification` tinyint(4) NOT NULL,
  `address_id` int(11) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `pending_balance` decimal(10,2) NOT NULL,
  `lifetime_balance` decimal(10,2) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS {$this->getTable('affiliate/program')} (
`program_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `rule_id` int(11) DEFAULT NULL,
  `rate` varchar(20) NOT NULL,
  `rate_calculation_type` int(11) NOT NULL,
  `detail` text,
  `rate_amount` decimal(10,2) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$this->getTable('affiliate/transaction')} (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `program_id` int(11) NOT NULL,
  `earn_amount` decimal(10,2) NOT NULL,
  `rate` int(11) NOT NULL,
  `order_increment_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `comment` text NOT NULL,
  `attrached_amount` decimal(10,2) NOT NULL,
  `affiliate_id` int(11) NOT NULL,
  `currency` varchar(50) NOT NULL DEFAULT '$',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$this->getTable('affiliate/withdrawal')} (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `account_id` int(11) NOT NULL,
  `currency` varchar(50) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(4) NOT NULL,
  `admin_role` varchar(50) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `amout_paid` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE  {$this->getTable('salesrule/rule')}  ADD  `is_affiliate` smallint(6) NULL;
ALTER TABLE  {$this->getTable('salesrule/rule')}  ADD  `program_id` int(11) NULL;
");
$installer->endSetup ();
