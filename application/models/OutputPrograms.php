<?php defined('BASEPATH') OR exit('No direct script access allowed');

class OutputPrograms extends MY_Model {

  function __construct () {
    parent::__construct();
    $this->table = 'output_program';
    $this->thead = array(
      (object) array('mData' => 'urutan', 'sTitle' => 'No', 'visible' => false),
      (object) array('mData' => 'kode_output', 'sTitle' => 'Kode', 'className' => 'text-right'),
      (object) array('mData' => 'uraian_output', 'sTitle' => 'Output', 'width' => '30%'),
      (object) array('mData' => 'pagu', 'sTitle' => 'Pagu', 'className' => 'text-right', 'searchable' => false),
      (object) array('mData' => 'realisasi', 'sTitle' => 'Realisasi', 'searchable' => 'false', 'className' => 'text-right'),
      (object) array('mData' => 'sisa', 'sTitle' => 'Sisa', 'searchable' => 'false', 'className' => 'text-right'),
      (object) array('mData' => 'prosentase', 'sTitle' => 'Serapan', 'searchable' => 'false', 'className' => 'text-right'),
    );

    $this->childs[] = array('label' => '', 'controller' => 'SubOutputProgram', 'model' => 'SubOutputPrograms');

  }

  function getListItem ($uuid) {
    $this->load->model('Users');
    $this->Users->filterListItem();
    return $this->db
      ->where("{$this->table}.uuid", $uuid)
      ->select("{$this->table}.*")
      ->select("{$this->table}.kegiatan_program parent", false)
      ->select("FORMAT(SUM(detail.vol * detail.hargasat), 0) pagu", false)
      ->select("FORMAT(SUM(spj.vol * spj.hargasat), 0) realisasi", false)
      ->select("GROUP_CONCAT(DISTINCT sub_output_program.uuid) childUuid", false)
      ->select("'SubOutputProgram' childController", false)
      ->select('output.kode kode', false)
      ->select('output.uraian uraian', false)
      ->group_by("{$this->table}.uuid")
      ->get()
      ->row_array();
  }

  function dt () {
    $this->load->model('Users');
    $this->Users->filterDt();
    return $this->datatables
      ->select("{$this->table}.uuid")
      ->select("{$this->table}.urutan")
      ->select('output.kode as kode_output', false)
      ->select('output.uraian as uraian_output', false)
      ->select("SUM(detail.hargasat * detail.vol) as pagu", false)
      ->select("SUM(spj.hargasat * spj.vol) as realisasi", false)
      ->select("IF(SUM(detail.hargasat * detail.vol) - SUM(spj.hargasat * spj.vol) > 0, SUM(detail.hargasat * detail.vol) - SUM(spj.hargasat * spj.vol), 0) as sisa")
      ->select("SUM(spj.hargasat * spj.vol) / SUM(detail.hargasat * detail.vol) * 100 as prosentase")
      ->group_by("{$this->table}.uuid")
      ->generate();
  }

  function select2 ($field, $term) {
    return $this->db
      ->select("{$this->table}.uuid id", false)
      ->select("CONCAT(output.kode, ' ', output.uraian) text", false)
      ->limit(10)
      ->like("CONCAT(output.kode, ' ', output.uraian)", $term)
      ->join('output', "{$this->table}.output = output.uuid", 'left')
      ->get($this->table)->result();
  }

  function findIn ($field, $value) {
    $this->db
      ->select("{$this->table}.*")
      ->select("CONCAT(output.kode, ' ', output.uraian) uraian", false)
      ->join('output', "{$this->table}.output = output.uuid", 'left');
    return parent::findIn("{$this->table}.{$field}", $value);
  }

}