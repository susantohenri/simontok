<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SubKomponenPrograms extends MY_Model {

  function __construct () {
    parent::__construct();
    $this->table = 'sub_komponen_program';
  }

}