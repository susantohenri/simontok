<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends MY_Model {

  function __construct () {
    parent::__construct();
    $this->table = 'permission';
    $this->form = array();
    $this->thead = array();

    $this->form[]= array(
    	'name' => 'entity',
    	'label'=> 'Entitas',
      'options' => $this->getEntities()
    );

    $this->form[]= array(
    	'name' => 'action',
    	'label'=> 'Aksi',
      'options' => $this->getActions()
    );
  }

  function getEntities () {
    return array(
      array('text' => 'Program', 'value' => 'Program'),
      array('text' => 'Kegiatan', 'value' => 'KegiatanProgram'),
      array('text' => 'Output', 'value' => 'OutputProgram'),
      array('text' => 'Sub Output', 'value' => 'SubOutputProgram'),
      array('text' => 'Komponen', 'value' => 'KomponenProgram'),
      array('text' => 'Sub Komponen', 'value' => 'SubKomponenProgram'),
      array('text' => 'Akun', 'value' => 'AkunProgram'),
      array('text' => 'Detail', 'value' => 'Detail'),
      array('text' => 'SPJ', 'value' => 'Spj'),
      array('text' => 'User', 'value' => 'User'),
      array('text' => 'Jabatan', 'value' => 'Jabatan'),
    );
  }

  function getActions () {
    return array(
      array('text' => 'Index', 'value' => 'index'),
      array('text' => 'Create', 'value' => 'create'),
      array('text' => 'Read', 'value' => 'read'),
      array('text' => 'Update', 'value' => 'update'),
      array('text' => 'Delete', 'value' => 'delete')
    );
  }

  function setPermission ($jabatan, $entity, $action) {
    return $this->create(array(
      'jabatan' => $jabatan,
      'entity' => $entity,
      'action' => $action,
    ));
  }

  function getGeneralEntities () {
    return array (
    'Program', 'KegiatanProgram', 'OutputProgram', 'SubOutputProgram',
    'KomponenProgram', 'SubKomponenProgram', 'AkunProgram');
  }

  function setGeneralPermission ($jabatan) {
    foreach ($this->getGeneralEntities() as $entity) {
      foreach (array('index', 'read', 'update') as $action) {
        $this->setPermission($jabatan, $entity, $action);
      }
    }
    foreach (array('Detail', 'Spj') as $entity) $this->setPermission($jabatan, $entity, 'read');
  }

  function getPermittedActions ($entity) {
    $actions = array();
    foreach ($this->find(
      array(
        'jabatan'=> $this->session->userdata('jabatan'),
        'entity' => $entity
      ))
    as $perm) $actions[] = $perm->action;
    return $actions;
  }

  function getPermittedMenus () {
    $allowed = array();
    foreach ($this->find(array(
      'jabatan'=> $this->session->userdata('jabatan'),
      'action' => 'index'
    )) as $perms) $allowed[] = $perms->entity;
    return $allowed;
  }

}