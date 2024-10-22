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
        $this->load->model('user_model');
       
    }
	public function index(){
        redirect('item/index');
    }
	public function suppliers(){
        $user = $this->ion_auth->user()->row()->id;
        $privilege = $this->user_model->getPrivilege($user);
        if(!in_array(12,$privilege)){
            redirect('auth', 'refresh');
        }
        $data['title'] = 'Suppliers List';
        $default = $this->item_model->getSupplierId();
        $this->crud->set_default('code',$default);
        
        $this->crud->init('people',[
            'code' => 'Supplier ID',
            'businessName' => 'Party( Business Name )',
            'name' => 'Contact Person',
            'businessAddress' => 'Business Address',
            'openingBalance' => 'Opening Balance',
            'district' => 'District',
            'phone' => 'Phone 1',
            'phone2' => 'Phone 2',
            'email' => 'Email',
        ]);
        $this->crud->set_hidden('type','1'); // Supplier
        $this->crud->set_hidden('user',$user); 
        $this->crud->ci->db->where('type','1'); // Supplier
        $this->crud->before_save($this , 'checkSupplier');
        $this->crud->set_search('businessName');

        $this->crud->set_rule('name','required');
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
	public function customers(){
        $user = $this->ion_auth->user()->row()->id;
        $privilege = $this->user_model->getPrivilege($user);
        if(!in_array(13,$privilege)){
            redirect('auth', 'refresh');
        }
        $data['title'] = 'Customers List';
        $default = $this->item_model->getCustomerId();
        $this->crud->set_default('code',$default); 
        $this->crud->init('people',[
            'code' => 'Customer ID',
            'businessName' => 'Party( Business Name )',
            'name' => 'Contact Person',
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
        $this->crud->set_option('thana',['0'=>'Kanaighat','1'=>'Companiganj','2'=>'Gowainghat','3'=>'Golabganj','4'=>'Zakiganj','5'=>'Jaintiapur','6'=>'Dakshin Surma','7'=>'Fenchuganj','8'=>'Balaganj','9'=>'Beanibazar','10'=>'Bishwanath','11'=>'Sylhet Sadar',
        '12'=>'Barlekja','13'=>'Kamalganj','14'=>'Kulaura','15'=>'Moulvibazar Sadar','16'=>'Rajnagar','17'=>'Sreemangal','18'=>'Juri','19'=>'Bishwamvarpur',
        '20'=>'Chhatak','21'=>'Derai','22'=>'Dakshin Sunamganj','23'=>'Dharampasha','24'=>'Dowarabazar','25'=>'Jagannathpur','26'=>'Jamalganj','27'=>'Sullah',
        '28'=>'Sunamganj Sadar','29'=>'Tahirpur','30'=>'Ajmiriganj','31'=>'Bahubal','32'=>'Baniyachong','33'=>'Chunarughat','34'=>'Habiganj Sadar','35'=>'Lakhai','36'=>'Madhabpur','37'=>'Nabiganj']);
        $this->crud->set_hidden('type','0'); // Customer
        $this->crud->set_hidden('user',$user);
        $this->crud->ci->db->where('type','0'); // Customer
        $this->crud->set_rule('name','required');
        $this->crud->before_save($this , 'beforeSave');
        $this->crud->set_search('businessName');
        //$this->crud->extra_fields($this, ['getDue'=>'Current Due']);
        
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    public function bank(){
        $data['title'] = "Bank Account List";
        $user = $this->ion_auth->user()->row()->id;
        $privilege = $this->user_model->getPrivilege($user);
        if(!in_array(19,$privilege)){
            redirect('auth', 'refresh');
        }
        $this->crud->init('banks',[
            'name'=>'Bank Account Name'
        ]);
        $this->crud->use_modal();
        $this->crud->set_rule('name','required');
        $this->crud->set_hidden('user',$user);
        $data['content'] = $this->crud->run();
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
    function getDue( $people_id ){
        return $this->item_model->getDue( $people_id );
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
    public function stockTest(){
        $user = $this->ion_auth->user()->row()->id;
        $privilege = $this->user_model->getPrivilege($user);
        if(!in_array(12,$privilege)){
            redirect('auth', 'refresh');
        }
        $data['title'] = 'Stock';
        $this->crud->init('test',[
            'name' => 'Name',
            'parent' => 'Parent',
            'lotno' => 'Lot No',
            'mgfyr' => 'MGF Year',
            'expiredate' => 'Expire Date',
            'quantity' => 'Quantity',
            'storeno' => 'Store No',
            'remarks' => 'Remarks',
        ]);
        $this->crud->change_type('mgfyr','date');
        $this->crud->set_default('mgfyr',date('Y-m-d'));
        $this->crud->change_type('expiredate','date');
        $this->crud->set_default('expiredate',date('Y-m-d'));
        $this->crud->change_type('remarks','textarea');
        $this->crud->set_rule('name','required');
        $this->crud->use_modal();
        $this->crud->custom_list('Test/Test_Stock_List');
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
}