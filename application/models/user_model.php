<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
	function getAllModules()
	{
		//$this->db->from('modules');
		return $this->db->get('modules')->result_array();
    }
    function getPrivilege($user)
    {
       $access =$this->db->select('modules as modules')->where('id',$user)->get('users');
       return explode(',',$access->row()->modules);
    }
}