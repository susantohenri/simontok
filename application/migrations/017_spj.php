<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_spj extends CI_Migration {

  function up () {

    /*
      STATUS means personal status for loggedin user
      GLOBAL STATUS: unverified, verified, paid 
    */

    $this->db->query("
      CREATE TABLE `spj` (
        `uuid` varchar(255) NOT NULL,
        `detail` varchar(255) NOT NULL,
        `uraian` varchar(255) NOT NULL,
        `vol` float NOT NULL,
        `sat` varchar(255) NOT NULL,
        `hargasat` float NOT NULL,
        `global_status` varchar(255) NOT NULL DEFAULT 'unverified',
        `unverify_reason` varchar(255) NOT NULL,
        `unpaid_reason` varchar(255) NOT NULL,
        `ppn` float NOT NULL,
        `pph` float NOT NULL,
        `urutan` INT(11) UNIQUE NOT NULL AUTO_INCREMENT ,
        PRIMARY KEY (`uuid`),
        KEY `program` (`detail`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ");

  }

  function down () {
    $this->db->query("DROP TABLE IF EXISTS `spj`");
  }

}