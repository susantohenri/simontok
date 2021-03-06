<?php defined('BASEPATH') OR exit('No direct script access allowed');

class KlasifikasiAkuns extends MY_Model {

  function __construct () {
    parent::__construct();
    $this->table = 'klasifikasi_akun';
    $this->form = array();
    $this->thead = array(
      (object) array('mData' => 'kode', 'sTitle' => 'Kode', 'className' => 'text-right'),
      (object) array('mData' => 'nama', 'sTitle' => 'Nama'),
    );

    $this->form[]= array(
    	'name' => 'kode',
    	'label'=> 'kode'
    );

    $this->form[]= array(
    	'name' => 'nama',
    	'label'=> 'nama'
    );
  }

}