<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Controller {
    function __construct(){
        parent::__construct();        

        $this->load->library('ion_auth');
		
        if (!$this->ion_auth->logged_in()){
			redirect('auth/login', 'refresh');
		}
        $this->load->library('crud');
        $this->load->helper(['html_helper','item_helper','common_helper']);
        $this->load->library('crud','','crud');
        //$this->output->enable_profiler(TRUE);
    }
	public function index(){
        if($this->uri->segment(2)=='') redirect('item/index'); // datatable ajax list issue.
        
        //$data['title'] = 'Medicines List';
        $data['title'] = 'Items List';

        $this->crud->init('items',[
//            'name' => 'Medicine Name',
//            'code' => 'Medicine Code',
//            'cp' => 'CP Price',
//            'tp' => 'TP Price',
//            'mrp' => 'MRP',
//            'pack' => 'Pack',
            'name' => 'Item Name',
            //'code' => 'Item Code',
            'uom'=>'UoM',
            'type' => 'Type',
            'parent' => 'Parent',
            //'purchase_price' => 'Purchase Price',
            //'mrp' => 'MRP',
            //'pack' => 'Pack',
        ]);
        //$this->crud->display_fields(['Medicine Name','Medicine Code','Pack']);
        // $this->crud->set_hidden('type','0'); // 1 for Medicine
        // $this->crud->ci->db->where('type','0'); // 1 for Medicine
        $this->crud->join('uom','uom','id','uom');
        $this->crud->join('parent','items','id','name','items.type=0');
        //$this->crud->join('supplier','people','id','name','people.type=1');
        $this->crud->set_rule('name','required');
    //    $this->crud->set_rule('code','required');
        $this->crud->set_option('type',['1'=>'Item','0'=>'Category']);
        $this->crud->set_search('name');
        $this->crud->custom_form('items/item_create_form');
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
	public function stock(){
        $data['title'] = 'Item Stock';

        $this->crud->init('items',[
            'name' => 'Item Name',
            'code' => 'Item Code',
            'pack' => 'Pack',
        ]);
        $this->crud->display_fields(['Item Code','Item Name','Stock Amount']);
        $this->crud->set_hidden('type','0'); // 1 for Medicine
        
        $this->crud->ci->db->where('type','0'); // 1 for Medicine

        $this->crud->extra_fields($this,['getRemaining'=>'Stock Amount']);

        $this->crud->set_rule('name','required');
        $this->crud->set_search('name');
        
        $this->crud->use_modal();
        
        $this->crud->hide_controls();
        
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
	}
    public function getRemaining($id){
        $this->load->model('item_model');
        return $this->item_model->getRemaining($id);
    }
    public function in(){
        $this->load->model('item_model');
        
        if($this->uri->segment(3) == 'insert'){
            $id = $this->item_model->insertDraft();
            $this->session->set_userdata('journal_id',$id);
            redirect('item/in/edit/'.$id);
        
        }elseif($this->uri->segment(3) == 'edit'){
            $this->session->set_userdata('journal_id',$this->uri->segment(4));        
            $this->session->set_userdata('type','0'); // Stock Type: IN
            
        
        }elseif($this->uri->segment(3) == 'ajax'){ // when we fetch data

            $this->crud->ci->db->where('journals.type','0'); // Journal Type: IN
            //$this->crud->ci->db->where('item_type','1'); // Item Type: Medicine
        }elseif($this->uri->segment(3) == 'update'){
            /*
            if(!$this->accounting_model->match_dr_cr($this->uri->segment(4))){
                echo json_encode(['error'=>'Dr Cr Does not match! Do you want to make a <a href="#" onclick="javascript:suspenseEntry()" class="btn btn-primary">Difference Entry</a>']); die();
            }
            */
        
        }
        
        $this->crud->init('journals',[
            'date' => 'Posting Date',
            'supplier_id' => 'Supplier',
            'description' => 'Description',
        ]);
        //if($this->uri->segment(3) == 'edit'){
            //$this->crud->join('supplier_id','people','id','name','people.type=1');
        //}
        $this->crud->custom_view('items/journal_in_list');
        $this->crud->custom_form('items/journal_form');
        
        $this->crud->set_rule('date','required');

        $this->crud->set_hidden('type','0'); // Journal Type: IN
        //$this->crud->set_hidden('item_type','1'); // Item Type: Medicine
        $this->crud->join('supplier_id','people','id','name','people.type=1');
        $this->crud->change_type('description','textarea');
        $this->crud->change_type('date','date');
        $this->crud->form_extra('id="formJournal"');
        
        $data = [
            'title' => 'Purchase Register',
        ];    
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    public function out(){
        $this->load->model('item_model');
        
        if($this->uri->segment(3) == 'insert'){
            $id = $this->item_model->insertDraft();
            $this->session->set_userdata('journal_id',$id);
            redirect('item/out/edit/'.$id);
        
        }elseif($this->uri->segment(3) == 'edit'){
            $this->session->set_userdata('journal_id',$this->uri->segment(4));        
            $this->session->set_userdata('type','1'); // Stock Type: OUT     
        
        }elseif($this->uri->segment(3) == 'ajax'){
            $this->crud->ci->db->where('journals.type','1'); // Journal Type: OUT
//            $this->crud->ci->db->where('item_type','1'); // Item Type: Medicine
        }elseif($this->uri->segment(3) == 'update'){

        }
        
        $this->crud->init('journals',[
            'date' => 'Posting Date',
            'customer_id' => 'Customer',
            'description' => 'Description',
            //'price_type' => 'Price Type',
        ]);
//        $this->crud->set_option('price_type',['0'=>'CP Price','1'=>'TP Price']);
        $this->crud->join('customer_id','people','id','name','type=0'); // Customer
    
        $this->crud->custom_form('items/journal_form');
        $this->crud->custom_view('items/journalViewOut');
        
        //$this->crud->extra_fields($this, ['getJournalOutTotal'=>'Total']);
        $this->crud->set_rule('date','required');
        $this->crud->set_hidden('type','1'); // Journal Type: OUT
        
//        $this->crud->set_hidden('item_type','1'); // Item Type: Medicine

        $this->crud->change_type('description','textarea');
        $this->crud->change_type('date','date');
        
        $this->crud->form_extra('id="formJournal"');
        $data = [
            'title' => 'Sales Register',
        ];    
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    public function ajax_itemlist(){
        $id = $this->session->userdata('journal_id');

        $this->crud->init('stock', [
            //'item_id' => 'Item Code',
            'item_name' => 'Item Name',
            'date' => 'Date',
            'uom' => 'UoM',
            'unit_price' => 'Unit Price',
            'quantity' => 'Quantity',
            
            
        ]);
        $this->crud->change_type('date','date');
        
       //  $this->crud->display_fields(['item_name','date','unit_price','quantity']);
       // $this->crud->join('item_id','items','id','code','type=0'); // Medicine only
        
        $this->crud->join('item_name','items','id','name','type=0'); // Medicine only
        $this->crud->join('uom','uom','id','uom');
        // !important    
        $this->crud->where(['journal_id='.$id]);
        $this->crud->extra_fields($this,['getTotal'=>'Total']);
       //$this->crud->order(['2','0','1','3','4']); // don't forget, we have a hidden field
       $this->crud->order(['3','0','4','1','2','5']);
        $this->crud->set_hidden('journal_id',$id);
        $this->crud->set_hidden('type',$this->session->userdata('type')); // Stock type. 0: in, 1: out
        
        $this->crud->before_save($this, 'beforeSave');
       // $this->crud->set_rule('item_id','is_natural_no_zero');

        $this->crud->set_rule('item_name','is_natural_no_zero');
        $this->crud->custom_form('items/item_form');
        $this->crud->custom_list('items/item_list');
        
        $this->crud->hide_controls();
        
        $this->crud->use_modal();
        $this->crud->ajax_list();
        
        $data['content']=$this->crud->run();
    }
    // public function getJournalOutTotal($journal_id){
    //     return $this->item_model->getJournalOutTotal($journal_id);
    // }
    public function getTotal($id){
        $this->load->model('item_model');
        return $this->item_model->getTotal($id);
    }
    public function getLabourCost($id){
        $this->load->model('item_model');
       $data = [];
        $result  = $this->item_model->getLabourCost($id);
      $data['labourCost'] = $result->labourCost;
       echo json_encode($data); 
    
    }
    public function beforeSave($post){
        unset($post['total']); return $post;
    }
}