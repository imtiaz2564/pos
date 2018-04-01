<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {
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
        $this->load->model('item_model');
    }
	public function index(){
        redirect('item/index');
    }
	public function suppliers(){
        $data['title'] = 'Suppliers List';
        $default = $this->item_model->getSupplierId();
        $this->crud->set_default('code',$default);
        
        $this->crud->init('people',[
            'code' => 'Company ID/Supplier ID',
            'name' => 'Suplier Name/Contact Person',
            'businessName' => 'Company Name',
            'businessAddress' => 'Business Address',
            'openingBalance' => 'Opening Balance',
            'district' => 'District',
            'phone' => 'Phone',
            'email' => 'Email',
        ]);
        $this->crud->set_hidden('type','1'); // Supplier
        $this->crud->ci->db->where('type','1'); // Supplier
        $this->crud->extra_fields($this,['getPayable'=>'Payable']);
        $this->crud->before_save($this , 'checkSupplier');

        $this->crud->set_rule('name','required');
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
	public function customers(){
        $data['title'] = 'Customers List';
        $default = $this->item_model->getCustomerId();
        $this->crud->set_default('code',$default); 
        $this->crud->init('people',[
            'code' => 'Customer Code',
            'name' => 'Customer Name / Contact Person',
            'businessName' => 'Business Name( Customer )',
            'address' => 'Home Address',
            'businessAddress' => 'Business Address',
            'area' => 'Area',
            'thana' => 'Thana',
            'openingBalance' => 'Opening Balance',
            'district' => 'District',
            'phone' => 'Phone 1',
            'phone2' => 'Phone 2',
            'email' => 'Email',
        ]);
        $this->crud->set_option('thana',['0'=>'Kanaighat','1'=>'Companiganj','2'=>'Gowainghat','3'=>'Golabganj',4=>'Zakiganj','5'=>'Jaintiapur','6'=>'Dakshin Surma','7'=>'Fenchuganj','8'=>'Balaganj','9'=>'Beanibazar','10'=>'Bishwanath','11'=>'Sylhet Sadar']);
        $this->crud->set_hidden('type','0'); // Customer
        $this->crud->ci->db->where('type','0'); // Customer
        $this->crud->set_rule('name','required');
        $this->crud->before_save($this , 'beforeSave');
        //$this->crud->extra_fields($this, ['getDue'=>'Current Due']);
        
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
    public function warehouse(){
        $data['title'] = 'Warehouses List';
        $this->crud->init('warehouse',[
            'name' => 'Warehouse Name',
            'supplier' => 'Supplier Name',
        ]);
        $this->crud->use_modal();
        $this->crud->set_rule('name','required');
        $this->crud->join('supplier','people','id','name','people.type=1');
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
     }
    public function sales(){
        $data['title'] = 'Sale Price Revision';
        $this->crud->init('items',[
            'name' => 'Item Name',
            // 'code' => 'Item Code',
            //'purchase_price' => 'Purchase Price',
            'mrp' => 'MRP',
            // 'pack' => 'Pack',
            'discount' => 'Discount',
        ]);
        //$this->crud->display_fields(['Medicine Name','Medicine Code','Pack']);
        //$this->crud->set_hidden('type','0'); // 1 for Medicine
        $this->crud->ci->db->where('type','1'); // 1 for Medicine
        //$this->crud->set_rule('name','required');
        //$this->crud->set_rule('code','required');
        //$this->crud->set_search('name')
        $this->crud->disable_insert();
        $this->crud->disable_delete();
        $this->crud->use_modal();
        $this->crud->set_rule('name','required');
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    function getDue( $people_id ){
        return $this->item_model->getDue( $people_id );
    }
    function getPayable( $people_id ){
        return $this->item_model->getPayable( $people_id );
    }
    function beforeSave($post){
        $data = $this->item_model->checkPhoneNumber($post['phone']);
        if( isset( $data->phone ) ){
             die( json_encode(['error'=>'Phone Number is exist already']));
        }
        $query = $this->item_model->checkBusinessName($post['businessName']);
        if( isset( $query->businessName ) ){
            die( json_encode(['error'=>'Business Name is exist already']));
        }
        return $post;
    }
    function checkSupplier($post){
        $data = $this->item_model->checkPhoneNumber($post['phone']);
        if( isset( $data->phone ) ){
             die( json_encode(['error'=>'Phone Number is exist already / Insert Phone Number']));
        }
        $query = $this->item_model->checkBusinessName($post['businessName']);
        if( isset( $query->businessName ) ){
            die( json_encode(['error'=>'Business Name is exist already / Insert Business Name']));
        }
        return $post;
    }
}