<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends CI_Controller {
    function __construct(){
        parent::__construct();        

        $this->load->library('ion_auth');
		
        if (!$this->ion_auth->logged_in()){
			redirect('auth/login', 'refresh');
        }
        $this->load->model('user_model');
        $this->load->library('crud');
        $this->load->helper('html_helper');
        $this->load->helper('common_helper');
        $this->load->library('crud','','crud');
    }
	public function index(){
    }
	public function payments(){
        $user = $this->ion_auth->user()->row()->id;
        $privilege = $this->user_model->getPrivilege($user);
        if(!in_array(8,$privilege)){
            redirect('auth', 'refresh');
        }
        $data['title'] = 'Payments( Khoroch )';
        
        $this->crud->init('finance',[
            'peopleID' => 'Party( Business Name )',
            'name' => 'Customer Name',
            'phone' => 'Phone',
            'date' => 'Date',
            'amount' => 'Amount',
            'paymentType' => 'Payment Type',
            'bankAccount' => 'Bank Account',
            'description' => 'Detail',
        ]);
        $this->crud->set_option('paymentType',['3'=>'None','0'=>'Cash','1'=>'Cheque & Bank']);
        $this->crud->join('peopleID','people','id','businessName');
        $this->crud->join('bankAccount','banks','id','name');
        $this->crud->set_rule('name','is_natural_no_zero');
      
        $this->crud->set_hidden('type','1'); // Payment
        $this->crud->set_hidden('user',$user); 
       

        if($this->uri->segment(3) == 'ajax')
            $this->crud->ci->db->where('finance.type','1'); // Payment. Apply where clause only when fetch data.
        
        $this->crud->set_rule('amount','required');
        $this->crud->change_type('date','date');
        $this->crud->set_default('date',date('Y-m-d'));
        //$this->crud->change_type('description','textarea');
        //$this->crud->order([3,0,1,2,4,5]);
        $this->crud->order([5,0,1,2,3,4,7,6,8,9]);
        
        //$this->crud->use_modal();
        $this->crud->custom_form('accounts/Supplier_Accounts_Form');
        $this->crud->form_extra('id="supplierAccounts"');
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
	public function receives() {
        $user = $this->ion_auth->user()->row()->id;
        $privilege = $this->user_model->getPrivilege($user);
        if(!in_array(7,$privilege)){
            redirect('auth', 'refresh');
        }
        $data['title'] = 'Receives( Joma )';
        
        $this->crud->init('finance',[
            'peopleID' => 'Party( Business Name )',
            'name' => 'Customer Name',
            'phone' => 'Phone',
            'date' => 'Date',
            'amount' => 'Amount',
            'paymentType' => 'Receive Type',
            'bankAccount' => 'Bank Account',
            'description' => 'Detail',
        ]);
        $this->crud->set_option('paymentType',['3'=>'None','0'=>'Cash & Cheque','1'=>'Bank','2'=>'Cash Back']);
        //$this->crud->join('peopleID','people','id','name','type=0'); // Customer
        $this->crud->set_hidden('type','0'); // Receive
        $this->crud->set_hidden('user',$user); 
        $this->crud->join('peopleID','people','id','businessName');
        $this->crud->join('bankAccount','banks','id','name');
        $this->crud->set_default('date',date('Y-m-d'));
       
        if($this->uri->segment(3) == 'ajax')
            $this->crud->ci->db->where('finance.type','0'); // Receive. Apply where clause only when fetch data.
        
        //$this->crud->set_rule('peopleID','required');
        // $this->crud->set_rule('amount','required');
         $this->crud->set_rule('date','required');
       
        $this->crud->change_type('date','date');
        //$this->crud->change_type('description','textarea');
        $this->crud->order([5,0,1,2,3,4,7,6,8,9]);
        
        //$this->crud->use_modal();
        $this->crud->custom_form('accounts/Customer_Accounts_Form');
        $this->crud->form_extra('id="customerAccounts"');
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    function banking() {
        $user = $this->ion_auth->user()->row()->id;
        $privilege = $this->user_model->getPrivilege($user);
        if(!in_array(20,$privilege)){
            redirect('auth', 'refresh');
        }
        $data['title'] = 'Banking';
        $this->crud->init('finance',[
            'bankAccount' => 'Bank Account',
            'type' => 'Type',
            'date' => 'Date',
            'amount' => 'Amount',
            'description' => 'Detail',
        ]);
        $this->crud->join('bankAccount','banks','id','name');
        $this->crud->set_rule('date','required');
        $this->crud->change_type('date','date');
        $this->crud->set_default('date',date('Y-m-d'));
        $this->crud->set_option('type',['2'=>'Diposit','3'=>'Withdraw']);
        $this->crud->set_hidden('user',$user); 
        $this->crud->set_hidden('peopleID',-3); // 3 for AB STOCK 
        $this->crud->custom_form('accounts/Banking_Form');
        $this->crud->before_save($this, 'beforeBanking');
        $this->crud->after_save($this, 'afterBanking');
        $this->crud->order([3,0,1,2,4,5,6]);
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    public function customer($id) {
        $this->load->model('item_model');
        $data['title'] = ' ';
        $data['people'] = $this->item_model->getPeople();
        $data['content'] = $this->load->view('Statement.php',$data,true);
        $this->load->view('template',$data);
    }
    function getSalesReport( $customerID , $datfrom , $datto) {
        $this->load->model('item_model');
        $data['salesData'] = $this->item_model->getSalesData( $customerID , $datfrom , $datto );
        $this->load->view('TotalSalesReport.php',$data);
       
    }
    // public function supplier($id) {
    //     $this->load->model('item_model');
    //     $data['title'] = '';
    //     $data['suppliers'] = $this->item_model->getSuppliers();
    //     $data['content'] = $this->load->view('Statement.php',$data,true);
    //     //$data['content'] = $this->load->view('SupplierReport\SupplierHistoryView.php',$data,true);
    //     $this->load->view('template',$data);
    // }
    function getcustomerstatement( $customerID , $datfrom , $datto ) {
        $user = $this->ion_auth->user()->row()->id;
        $privilege = $this->user_model->getPrivilege($user);
        if(!in_array(9,$privilege)){
            redirect('auth', 'refresh');
        }
        $this->load->model('item_model');
        $data['title'] = '';
        $data['datFrom'] =$datfrom;
        $data['datTo'] =$datto;
        $data['oldbalance'] = $this->item_model->getPreviousStatement($customerID , $datfrom);
        $data['result'] = $this->item_model->getCustomerStatement($customerID , $datfrom , $datto);
        
        //die();
        // $data['salesData'] = $this->item_model->getSalesData( $customerID , $datfrom , $datto );
       
        // $data['history'] = $this->item_model->getSupplierHistory( $customerID , $datfrom , $datto );//new
        
        // $data['statement'] = $this->item_model->getCustomerStatement($customerID , $datfrom , $datto);
    
        // $data['cashBack'] = $this->item_model->getCashBack($customerID , $datfrom , $datto);
      
        // $data['info'] = $this->item_model->getOpeningBalance($customerID);
        // $data['refund'] = $this->item_model->getRefund($customerID , $datfrom , $datto);
        //$this->load->view('CustomerStatement.php',$data);
        $this->load->view('accounts/StatementView.php',$data);
    }
    public function getSupplierHistory($supplierID , $datfrom , $datto) {
        $this->load->model('item_model');
        $data['history'] = $this->item_model->getSupplierHistory( $supplierID , $datfrom , $datto );
        $this->load->view('supplierReport/SupplierHistoryView.php',$data);
    }
    function getSupplierStatement( $supplierID , $datfrom , $datto ) {
        $this->load->model('item_model');
        $data['history'] = $this->item_model->getSupplierHistory( $supplierID , $datfrom , $datto );
        //$data['openingBalance'] = $this->item_model->getOpeningBalance($supplierID);
        $data['info'] = $this->item_model->getOpeningBalance($supplierID);
        $data['statement'] = $this->item_model->getCustomerStatement( $supplierID , $datfrom , $datto );
        $this->load->view('supplierReport/SupplierStatement.php',$data);
    }
    function afterBanking(){
        die( json_encode(['error'=>'Saved']));
    }
    function beforeBanking($post){
        if(empty($post['bankAccount'])){
            die( json_encode(['error'=>'Select Bank Account']));
        }
        if(empty($post['amount'])){
            die( json_encode(['error'=>'Insert Amount']));
        }
        if(empty($post['description'])){
            die( json_encode(['error'=>'Insert Detail']));
        }
        return $post;
    }
    
}