<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Lampirans extends MY_Model {

  function __construct () {
    parent::__construct();
    $this->table = 'lampiran';
    $this->thead = array();

    $this->form = array();
    $this->form[]= array(
      'name' => 'spj',
      'label'=> 'SPJ',
      'options' => array(),
      'attributes' => array(
        array('data-autocomplete' => 'true'),
        array('data-model' => 'Spjs'),
        array('data-field' => 'uraian')
      ),
      'width' => 6
    );

    $this->form[]= array(
      'name' => 'uraian',
      'label'=> 'Uraian',
      'width'=> 8
    );

    $this->form[]= array(
      'name' => 'sat',
      'label'=> 'Satuan',
      'width'=> 2
    );

    $this->form[]= array(
      'name' => 'vol',
      'label'=> 'Volume',
      'attributes' => array(
        array('data-number' => 'true')
      ),
      'width' => 3
    );

    $this->form[]= array(
      'name' => 'hargasat',
      'label'=> 'Harga Satuan',
      'attributes' => array(
        array('data-number' => 'true')
      ),
      'width' => 3
    );

    $this->form[]= array(
      'name' => 'total',
      'label'=> 'Total',
      'value'=> 0,
      'attributes' => array(
        array('disabled' => 'disabled'),
        array('data-number' => 'true')
      ),
      'width'=> 4
    );

  }

  function getForm ($uuid = false, $isSubform = false) {
    if ($isSubform)  unset($this->form[0]);
    return parent::getForm ($uuid, $isSubform);
  }

  function findOne ($param) {
    $this->db
      ->select('*')
      ->select('FORMAT(vol, 0) vol', false)
      ->select('FORMAT(hargasat, 0) hargasat', false)
      ->select('FORMAT(vol * hargasat, 0) total', false);
    return parent::findOne($param);
  }

}