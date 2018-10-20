<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_subkomponenprogram extends CI_Migration {

  function up () {

    $this->db->query("
      CREATE TABLE `sub_komponen_program` (
        `uuid` varchar(255) NOT NULL,
        `komponen_program` varchar(255) NOT NULL,
        `sub_komponen` varchar(255) NOT NULL,
        PRIMARY KEY (`uuid`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    ");

  }

  function down () {
    $this->db->query("DROP TABLE `sub_komponen_program`");
  }

}