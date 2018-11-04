<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_suboutput extends CI_Migration {

  function up () {

    $this->db->query("
      CREATE TABLE `sub_output` (
        `uuid` varchar(255) NOT NULL,
        `kode` varchar(255) NOT NULL,
        `uraian` varchar(255) NOT NULL,
        PRIMARY KEY (`uuid`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ");

  }

  function down () {
    $this->db->query("DROP TABLE IF EXISTS `sub_output`");
  }

}