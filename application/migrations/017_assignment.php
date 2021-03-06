<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_assignment extends CI_Migration {

  function up () {

    $this->db->query("
      CREATE TABLE `assignment` (
        `uuid` varchar(255) NOT NULL,
        `jabatan_group` varchar(255) NOT NULL,
        `detail` varchar(255) NOT NULL,
        `urutan` INT(11) UNIQUE NOT NULL AUTO_INCREMENT ,
        PRIMARY KEY (`uuid`),
        KEY `jabatan_group` (`jabatan_group`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ");

    $this->db->query("ALTER TABLE `assignment` ADD INDEX(`jabatan_group`)");
    $this->db->query("ALTER TABLE `assignment` ADD INDEX(`detail`)");

  }

  function down () {
    $this->db->query("DROP TABLE IF EXISTS `assignment`");
  }

}