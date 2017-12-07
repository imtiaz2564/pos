<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends CI_Controller {
    function __construct(){
        parent::__construct();        

        $this->load->library('ion_auth');
		
        if (!$this->ion_auth->logged_in()){
			redirect('auth/login', 'refresh');
		}
        $this->load->library('crud');
        $this->load->helper('html_helper');
        $this->load->helper('common_helper');
        $this->load->library('crud','','crud');
    }
	public function index(){
    }
	public function payments(){
        $data['title'] = 'Payments';
        
        $this->crud->init('finance',[
            'peopleID' => 'Suplier Name',
            'date' => 'Date',
            'amount' => 'Amount',
            'paymentType' => 'Payment Type',
            'description' => 'Detail',
        ]);
        $this->crud->set_option('paymentType',['0'=>'Cash','1'=>'TT','2'=>'Online','3'=>'bKash']);
        
        $this->crud->join('peopleID','people','id','name','type=1'); // Supplier

        $this->crud->set_hidden('type','1'); // Payment
        
        if($this->uri->segment(3) == 'ajax')
            $this->crud->ci->db->where('finance.type','1'); // Payment. Apply where clause only when fetch data.
        
        $this->crud->set_rule('peopleID','required');
        $this->crud->set_rule('amount','required');
        $this->crud->change_type('date','date');
        $this->crud->change_type('description','textarea');
        $this->crud->order([3,0,1,4,2,5]);
        
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
	public function receives(){
        $data['title'] = 'Receives';
        
        $this->crud->init('finance',[
            'peopleID' => 'Customer Name',
            'date' => 'Date',
            'amount' => 'Amount',
            'paymentType' => 'Payment Type',
            'description' => 'Detail',
        ]);
        $this->crud->set_option('paymentType',['0'=>'Cash','1'=>'TT','2'=>'Online','3'=>'bKash']);

        $this->crud->join('peopleID','people','id','name','type=0'); // Customer

        $this->crud->set_hidden('type','0'); // Receive
        
        if($this->uri->segment(3) == 'ajax')
            $this->crud->ci->db->where('finance.type','0'); // Receive. Apply where clause only when fetch data.
        
        $this->crud->set_rule('peopleID','required');
        $this->crud->set_rule('amount','required');
        $this->crud->change_type('date','date');
        $this->crud->change_type('description','textarea');
        $this->crud->order([3,0,1,4,2,5]);
        
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
}