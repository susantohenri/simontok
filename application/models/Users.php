<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Model {

  function __construct () {
    parent::__construct();
    $this->table = 'user';
    $this->thead = array(
      (object) array('mData' => 'urutan', 'sTitle' => 'No', 'visible' => false),
      (object) array('mData' => 'email', 'sTitle' => 'Email'),
      (object) array('mData' => 'nama_jabatan', 'sTitle' => 'Jabatan'),
    );
    $this->form  = array ();

    $this->form[]= array(
    	'name' => 'email',
    	'label'=> 'Email'
    );

    $this->form[]= array(
    	'type' => 'password',
    	'name' => 'password',
    	'label'=> 'Password'
    );

    $this->form[]= array(
        'type' => 'password',
        'name' => 'confirm_password',
        'label'=> 'Ulangi Password'
    );
  
    $this->form[]= array(
      'name' => 'jabatan',
      'label'=> 'Jabatan',
      'options' => array(),
      'attributes' => array(
        array('data-autocomplete' => 'true'), 
        array('data-model' => 'Jabatans'), 
        array('data-field' => 'nama')
      ),
    );
  }

  function delete ($uuid) {
    $user = $this->findOne($uuid);
    if ('admin' !== $user['email']) return parent::delete($uuid);
  }

  function save ($data) {
    if (strlen ($data['password']) > 0) {
      if ($data['password'] !== $data['confirm_password']) return array('error' => array('message' => 'Password tidak sesuai'));
      else $data['password'] = md5($data['password']);
    } else unset ($data['password']);
    unset ($data['confirm_password']);
    return parent::save ($data);
  }

  function findOne ($param) {
    $record = parent::findOne ($param);
    $record['confirm_password'] = '';
    return $record;
  }

  function dt () {
    $this->datatables
      ->select("{$this->table}.uuid")
      ->select("{$this->table}.urutan")
      ->select("{$this->table}.email")
      ->select("jabatan.nama as nama_jabatan", false)
      ->join('jabatan', 'user.jabatan = jabatan.uuid', 'left');
    return parent::dt();
  }

  function filterByRole () {
    $filters = array();
    $this->load->model(array('Jabatans'));
    $topdown = $this->Jabatans->getTopDown($this->session->userdata('jabatan'));
    $groups = $this->db
      ->distinct()
      ->select('jabatan_group')
      ->where_in('uuid', $topdown)
      ->get('jabatan')
      ->result();
    $include_group = array();
    foreach ($groups as $g) $include_group[] = $g->jabatan_group;
    $details = $this->db
      ->distinct()
      ->select('detail')
      ->where_in('jabatan_group', $include_group)
      ->get('assignment')
      ->result();
    $include_detail = array();
    foreach ($details as $d) $include_detail[] = $d->detail;
    if (count ($include_detail) > 0) $filters[] = array('fn' => 'where_in', 'field' => 'detail.uuid', 'value' => $include_detail);
    return $filters;
  }

  function filterDt () {
    foreach ($this->filterByRole() as $filter) {
      $fn = $filter['fn'];
      $field = $filter['field'];
      $value = $filter['value'];
      if ('or_where_in' === $fn) $this->datatables->or_where("{$field} IN ('{$value}')");
      else $this->datatables->$fn($field, $value);
    }
    $this->joinRelatedTables($this->datatables);
  }

  function filterListItem () {
    foreach ($this->filterByRole() as $filter) {
      $fn = $filter['fn'];
      $field = $filter['field'];
      $value = $filter['value'];
      $this->db->$fn($field, $value);
    }
    $this->joinRelatedTables($this->db);
  }

  function joinRelatedTables ($obj) {
    $obj
      ->join('kegiatan', "program.uuid = kegiatan.program", 'left')
      ->join('output', "kegiatan.uuid = output.kegiatan", 'left')
      ->join('sub_output', "output.uuid = sub_output.output", 'left')
      ->join('komponen', "sub_output.uuid = komponen.sub_output", 'left')
      ->join('sub_komponen', "komponen.uuid = sub_komponen.komponen", 'left')
      ->join('akun', "sub_komponen.uuid = akun.sub_komponen", 'left')

      ->join('detail', "akun.uuid = detail.akun", 'left')
      ->join('spj', "detail.uuid = spj.detail", 'left')
      ->join('(SELECT payment.spj, SUM(payment.amount) as paid_amount FROM payment GROUP BY payment.spj) as payment_sent', "payment_sent.spj = spj.uuid", 'left')
      ->join('(SELECT item.spj, SUM(IFNULL(item.vol, 0) * IFNULL(item.hargasat, 0)) as submitted_amount FROM item GROUP BY item.spj) as spj_item', "spj_item.spj = spj.uuid", 'left')
      ->from('program');
  }

}