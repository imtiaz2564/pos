<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine extends CI_Controller {
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
        //$this->output->enable_profiler(TRUE);
    }
	public function index(){
        redirect('medicine/stock');
    }
	public function suppliers()
	{
        $data['title'] = 'Suppliers';
        
        $this->crud->init('people',[
            'name' => 'Suplier Name',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
        ]);
        $this->crud->set_hidden('type','1'); // Supplier
        $this->crud->ci->db->where('type','1'); // Supplier
        $this->crud->set_rule('name','required');
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
	public function distributors()
	{
        $data['title'] = 'Distributors';

        $this->crud->init('people',[
            'name' => 'Customer Name',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
        ]);
        $this->crud->set_hidden('type','0'); // Customer
        $this->crud->ci->db->where('type','0'); // Customer
        $this->crud->set_rule('name','required');
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
    // Raw Material Section
    
	public function raw_items()
	{
        $data['title'] = 'Raw Materials';

        $this->crud->init('items',[
            'name' => 'Material Name',
            'code' => 'Material Code',
            'unit' => 'Unit',
        ]);
        $this->crud->set_hidden('type','0'); // 0 for Raw Material
        $this->crud->ci->db->where('type','0'); // 0 for Raw Material
        
        $this->crud->extra_fields($this,['Remaining Amount'=>'getRemaining']);
        
        $this->crud->set_rule('name','required');
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
    public function getRemaining($id){
        $this->load->model('item_model');
        return $this->item_model->getRemaining($id);
    }
	public function raw_stock()
	{
        $data['title'] = 'Raw Material Purchase';

        $this->crud->init('stock',[
            'item_id' => 'Raw Material',
            'supplier_id' => 'Supplier',
            'quantity' => 'Quantity',
            'purchase_price' => 'Purchase Price / Unit',
        ]);
        $this->crud->join('item_id','items','id','name','type=0'); // 0 for Raw Materials
        $this->crud->join('supplier_id','people','id','name','type=1'); // 1 for Supplier

        $this->crud->set_rule('item_id','required');
        

        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
	public function raw_out()
	{
        $data['title'] = 'Raw Material Used';
        
        $this->crud->init('out',[
            'item_id' => 'Raw Material',
            'quantity' => 'Quantity',
        ]);
        $this->crud->join('item_id','items','id','name','type=0'); // 0 for Raw Materials

        $this->crud->set_rule('item_id','required');
        //$this->crud->ci->db->group_by('name');

        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
    
    // Medicine Section
    
	public function medicine_items()
	{
        $data['title'] = 'Medicines';

        $this->crud->init('items',[
            'name' => 'Medicine Name',
            'code' => 'Medicine Code',
        ]);
        $this->crud->set_hidden('type','1'); // 1 for Medicine
        $this->crud->ci->db->where('type','1'); // 1 for Medicine

        $this->crud->extra_fields($this,['Remaining Amount'=>'getRemaining']);

        $this->crud->set_rule('name','required');
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
	public function medicine_stock()
	{
        $data['title'] = 'Medicine Production';
        
        $this->crud->init('stock',[
            'item_id' => 'Medicine',
            'supplier_id' => 'Supplier',
            'quantity' => 'Quantity',
            'purchase_price' => 'Purchase Price / Unit',
        ]);
        $this->crud->join('item_id','items','id','name','type=1'); // 1 for Medicine
        $this->crud->join('supplier_id','people','id','name','type=1'); // 1 for Supplier

        $this->crud->set_rule('item_id','required');
        

        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
    /*
	public function medicine_out()
	{
        $data['title'] = 'Distribution';
        
        $this->crud->init('out',[
            'item_id' => 'Medicine',
            'quantity' => 'Quantity',
        ]);
        $this->crud->join('item_id','items','id','name','type=1'); // 1 for Medicine

        $this->crud->set_rule('item_id','required');
        

        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
    */
    // Office Material Section
    
	public function office_items()
	{
        $data['title'] = 'Office Materials';
        
        $this->crud->init('items',[
            'name' => 'Material Name',
            'code' => 'Material Code',
        ]);
        $this->crud->set_hidden('type','2'); // 2 for Office Material
        $this->crud->ci->db->where('type','2'); // 2 for Office Material

        $this->crud->extra_fields($this,['Remaining Amount'=>'getRemaining']);

        $this->crud->set_rule('name','required');
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
	public function office_stock()
	{
        $data['title'] = 'Office Materials Purchase';
        
        $this->crud->init('stock',[
            'item_id' => 'Material',
            'supplier_id' => 'Supplier',
            'quantity' => 'Quantity',
            'purchase_price' => 'Purchase Price / Unit',
        ]);
        $this->crud->join('item_id','items','id','name','type=2'); // 2 for Office Material
        $this->crud->join('supplier_id','people','id','name','type=2'); // 2 for Office Material

        $this->crud->set_rule('item_id','required');
        

        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
	public function office_out()
	{
        $data['title'] = 'Office Material Used';
        
        $this->crud->init('out',[
            'item_id' => 'Material',
            'quantity' => 'Quantity',
        ]);
        
        $this->crud->join('item_id','items','id','name','type=2'); // 2 for Office Material

        $this->crud->set_rule('item_id','required');
        

        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
	public function employees()
	{
        $data['title'] = 'Employees';

        $this->crud->init('people',[
            'name' => 'Employee Name',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
            'designation' => 'Designation',
            'department' => 'Department',
            'salary' => 'Monthly Salary',
        ]);
        $this->crud->set_hidden('type','2'); // Employee
        $this->crud->ci->db->where('type','2'); // Employee
        $this->crud->set_rule('name','required');
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
	public function salary()
	{
        $data['title'] = 'Salary Disbursement';

        $this->crud->init('salaries',[
            'employee' => 'Employee',
            'month' => 'Month',
            'year' => 'Year',
        ]);
        $this->crud->set_rule('employee','required');
        
        $this->crud->join('employee','people','id','name','type=2'); // 2 for Employee
        $months = [
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];
        
        $this->crud->set_option('month',$months);
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
	public function units(){
        $data['title'] = 'Item Units';

        $this->crud->init('units',[
            'name' => 'Unit Name',
        ]);
        $this->crud->set_rule('name','required');
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
    public function medicine_in(){
        $this->load->model('item_model');
        
        if($this->uri->segment(3) == 'insert'){
            $id = $this->item_model->insertDraft();
            $this->session->set_userdata('journal_id',$id);
            redirect('welcome/medicine_in/edit/'.$id);
        
        }elseif($this->uri->segment(3) == 'edit'){
            $this->session->set_userdata('journal_id',$this->uri->segment(4));        
            $this->session->set_userdata('type','0'); // Stock Type: IN     
        
        }elseif($this->uri->segment(3) == 'update'){
            /*
            if(!$this->accounting_model->match_dr_cr($this->uri->segment(4))){
                echo json_encode(['error'=>'Dr Cr Does not match! Do you want to make a <a href="#" onclick="javascript:suspenseEntry()" class="btn btn-primary">Difference Entry</a>']); die();
            }
            */
        
        }
        
        $this->crud->init('journals',[
            'date' => 'Posting Date',
            'description' => 'Description',
        ]);
            
        $this->crud->custom_form('items/medicine_in');
        $this->crud->set_rule('date','required');
        $this->crud->set_hidden('type','0'); // Journal Type: IN

        $this->crud->ci->db->where('type','0'); // Journal Type: IN
        
        $this->crud->change_type('description','textarea');
        $this->crud->change_type('date','date');
        $this->crud->form_extra('id="formJournal"');
        $data = [
            'title' => 'Journals',
        ];    
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    
    public function medicine_out(){
        $this->load->model('item_model');
        
        if($this->uri->segment(3) == 'insert'){
            $id = $this->item_model->insertDraft();
            $this->session->set_userdata('journal_id',$id);
            redirect('welcome/medicine_out/edit/'.$id);
        
        }elseif($this->uri->segment(3) == 'edit'){
            $this->session->set_userdata('journal_id',$this->uri->segment(4));        
            $this->session->set_userdata('type','1'); // Stock Type: OUT     
        
        }elseif($this->uri->segment(3) == 'update'){
        
        }
        
        $this->crud->init('journals',[
            'date' => 'Posting Date',
            'description' => 'Description',
            'type' => 'Type',
        ]);
            
        $this->crud->custom_form('items/medicine_in');
        $this->crud->set_rule('date','required');
        
        $this->crud->set_hidden('type','1'); // Journal Type: OUT
        $this->crud->ci->db->where('type','1'); // Journal Type: OUT
        
        $this->crud->change_type('description','textarea');
        $this->crud->change_type('date','date');
        $this->crud->form_extra('id="formJournal"');
        $data = [
            'title' => 'Journals',
        ];    
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    public function ajax_itemlist(){
        $id = $this->session->userdata('journal_id');

        $this->crud->init('stock',[
            'item_id' => 'Medicine',
            'date' => 'Date',
            'quantity' => 'Quantity',
        ]);
        $this->crud->change_type('date','date');
        
        $this->crud->display_fields(['item_id','date','quantity']); // controls the order too.
        $this->crud->join('item_id','items','id','name','type=1'); // Medicine only
        
        // !important    
        $this->crud->where(['journal_id='.$id]);
        
        $this->crud->set_hidden('journal_id',$id);
        $this->crud->set_hidden('type',$this->session->userdata('type')); // Stock type. 0: in, 1: out
        
        $this->crud->set_rule('item_id','is_natural_no_zero');
        $this->crud->custom_form('items/item_form');
        $this->crud->custom_list('items/item_list');
        $this->crud->use_modal();
        $this->crud->ajax_list();
        
        $data['content']=$this->crud->run();
    }
}