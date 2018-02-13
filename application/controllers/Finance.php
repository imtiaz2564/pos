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
        $data['title'] = 'Suplier Payments';
        
        $this->crud->init('finance',[
            //'peopleID' => 'Suplier ID',
            //  'name' => 'Suplier Name',
            //  'phone' => 'Phone',
            'date' => 'Date',
            'amount' => 'Amount',
            'paymentType' => 'Payment Type',
            'description' => 'Detail',
        ]);
        $this->crud->set_option('paymentType',['0'=>'Cash','1'=>'TT','2'=>'Online','3'=>'bKash']);
        
        //$this->crud->join('peopleID','people','id','name','type=1'); // Supplier

        $this->crud->set_hidden('finance.type','1'); // Payment
        
        if($this->uri->segment(3) == 'ajax')
            $this->crud->ci->db->where('finance.type','1'); // Payment. Apply where clause only when fetch data.
        
        //$this->crud->set_rule('peopleID','required');
        $this->crud->set_rule('amount','required');
        $this->crud->change_type('date','date');
        $this->crud->change_type('description','textarea');
        //$this->crud->order([0,1,3,4,2,5,6]);
        
        //$this->crud->use_modal();
        $this->crud->custom_form('accounts/Supplier_Accounts_Form');
        $this->crud->form_extra('id="supplierAccounts"');
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
	public function receives() {
        $data['title'] = 'Customer Receives';
        
        $this->crud->init('finance',[
            //'peopleID' => 'Customer ID',
            'name' => 'Customer Name',
            'phone' => 'Phone',
            'date' => 'Date',
            'amount' => 'Amount',
            'paymentType' => 'Payment Type',
            'description' => 'Detail',
        ]);
        $this->crud->set_option('paymentType',['0'=>'Cash','1'=>'TT','2'=>'Online','3'=>'bKash']);

        //$this->crud->join('peopleID','people','id','name','type=0'); // Customer

        $this->crud->set_hidden('type','0'); // Receive
        
        if($this->uri->segment(3) == 'ajax')
            $this->crud->ci->db->where('finance.type','0'); // Receive. Apply where clause only when fetch data.
        
        //$this->crud->set_rule('peopleID','required');
        $this->crud->set_rule('amount','required');
        $this->crud->change_type('date','date');
        $this->crud->change_type('description','textarea');
       // $this->crud->order([0,1,3,4,2,5,6]);
        
        //$this->crud->use_modal();
        $this->crud->custom_form('accounts/Customer_Accounts_Form');
        $this->crud->form_extra('id="customerAccounts"');
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    public function customer($id) {
        $this->load->model('item_model');
        $data['title'] = '';
        $data['customers'] = $this->item_model->getCustomers();
        $data['content'] = $this->load->view('Statement.php',$data,true);
        $this->load->view('template',$data);
    }
    function getSalesReport( $customerID , $datfrom , $datto) {
        $this->load->model('item_model');
        $data['salesData'] = $this->item_model->getSalesData( $customerID , $datfrom , $datto );
        $this->load->view('TotalSalesReport.php',$data);
       
    }
    public function supplier($id) {
        $this->load->model('item_model');
        $data['title'] = '';
        $data['suppliers'] = $this->item_model->getSuppliers();
        $data['content'] = $this->load->view('Statement.php',$data,true);
        //$data['content'] = $this->load->view('SupplierReport\SupplierHistoryView.php',$data,true);
        $this->load->view('template',$data);
    }
    function getcustomerstatement( $customerID , $datfrom , $datto ) {
        $this->load->model('item_model');
        $data['title'] = '';
        $data['salesData'] = $this->item_model->getSalesData( $customerID , $datfrom , $datto );
        $data['statement'] = $this->item_model->getCustomerStatement( $customerID , $datfrom , $datto );
        $this->load->view('CustomerStatement.php',$data);
    }
    public function getSupplierHistory($supplierID , $datfrom , $datto) {
        $this->load->model('item_model');
        $data['history'] = $this->item_model->getSupplierHistory( $supplierID , $datfrom , $datto );
        $this->load->view('supplierReport/SupplierHistoryView.php',$data);
    }
    function getSupplierStatement( $supplierID , $datfrom , $datto ){
        $this->load->model('item_model');
        $data['history'] = $this->item_model->getSupplierHistory( $supplierID , $datfrom , $datto );
       $data['statement'] = $this->item_model->getCustomerStatement( $supplierID , $datfrom , $datto );
        $this->load->view('supplierReport/SupplierStatement.php',$data);
    }
    
}