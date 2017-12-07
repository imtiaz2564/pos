<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Raw extends CI_Controller {
    function __construct(){
        parent::__construct();        

        $this->load->library('ion_auth');
		
        if (!$this->ion_auth->logged_in()){
			redirect('auth/login', 'refresh');
		}
        $this->load->library('crud');
        $this->load->helper(['html_helper','common_helper','item_helper']);
        $this->load->library('crud','','crud');
        //$this->output->enable_profiler(TRUE);
    }
	public function index(){
        if($this->uri->segment(2)=='') redirect('raw/index'); // datatable ajax list issue.

        $data['title'] = 'Raw Material List';

        $this->crud->init('items',[
            'name' => 'Item Name',
            'code' => 'Item Code',
            'unit' => 'Unit',
        ]);
        $this->crud->display_fields(['Item Name','Item Code','Unit']);
        $this->crud->set_hidden('type','2'); // 2 for Raw
        $this->crud->ci->db->where('type','2'); // 2 for Raw

        $this->crud->extra_fields($this,['Stock Amount'=>'getRemaining']);

        $this->crud->set_rule('name','required');
        $this->crud->set_rule('code','required');
        $this->crud->set_search('name');
        
        $this->crud->use_modal();
        
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
	public function stock(){
        $data['title'] = 'Raw Material Stock';

        $this->crud->init('items',[
            'name' => 'Item Name',
            'code' => 'Item Code',
            'unit' => 'Unit',
        ]);
        $this->crud->display_fields(['Item Code','Item Name','Stock Amount']);
        $this->crud->set_hidden('type','2'); // 2 for Raw
        $this->crud->ci->db->where('type','2'); // 2 for Raw

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
            redirect('raw/in/edit/'.$id);
        
        }elseif($this->uri->segment(3) == 'edit'){
            $this->session->set_userdata('journal_id',$this->uri->segment(4));        
            $this->session->set_userdata('type','0'); // Stock Type: IN     
        
        }elseif($this->uri->segment(3) == 'ajax'){
            $this->crud->ci->db->where('item_type','2'); // Raw Items
            $this->crud->ci->db->where('journals.type','0'); // Journal Type: IN
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
            
        $this->crud->custom_form('raw/journal_form');
        $this->crud->custom_view('raw/journal_in_view');
        $this->crud->set_rule('date','required');
        

        $this->crud->set_hidden('type','0'); // Journal Type: IN
        $this->crud->set_hidden('item_type','2'); // Item Type: Raw
        
        $this->crud->join('supplier_id','people','id','name','type=1'); // Supplier

        $this->crud->change_type('description','textarea');
        $this->crud->change_type('date','date');
        
        $this->crud->extra_fields($this,['getJournalInTotal'=>'Total']);
        
        $this->crud->form_extra('id="formJournal"');
        $data = [
            'title' => 'Raw Material Stock In',
        ];    
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    public function out(){
        $this->load->model('item_model');
        
        if($this->uri->segment(3) == 'insert'){
            $id = $this->item_model->insertDraft();
            $this->session->set_userdata('journal_id',$id);
            redirect('raw/out/edit/'.$id);
        
        }elseif($this->uri->segment(3) == 'edit'){
            $this->session->set_userdata('journal_id',$this->uri->segment(4));        
            $this->session->set_userdata('type','1'); // Stock Type: OUT     
        
        }elseif($this->uri->segment(3) == 'ajax'){
            $this->crud->ci->db->where('item_type','2'); // Raw Items
            $this->crud->ci->db->where('type','1'); // Journal Type: OUT
        }elseif($this->uri->segment(3) == 'update'){
            // validation
        }
        
        $this->crud->init('journals',[
            'date' => 'Posting Date',
            'description' => 'Description',
        ]);
        
        $this->crud->set_hidden('type','1'); // Journal Type: OUT
        $this->crud->set_hidden('item_type','2'); // Raw Material

        $this->crud->custom_view('raw/journal_out_view');
        $this->crud->custom_form('raw/journal_form');
        $this->crud->set_rule('date','required');
        
        $this->crud->change_type('description','textarea');
        $this->crud->change_type('date','date');
        $this->crud->form_extra('id="formJournal"');
        
        $data = [
            'title' => 'Raw Material Stock Out',
        ];    
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    public function ajax_itemlist_in(){
        $id = $this->session->userdata('journal_id');

        $this->crud->init('stock', [
            'item_id' => 'Item Code',
            'date' => 'Date',
            'unit_price' => 'Unit Price',
            'quantity' => 'Quantity',
        ]);
        $this->crud->change_type('date','date');
        
        // $this->crud->display_fields(['quantity','item_id','date']);
        $this->crud->join('item_id','items','id','code','type=2'); // Raw Materials only
        
        // !important    
        $this->crud->where(['journal_id='.$id]);
        
        $this->crud->order(['3' ,'0','1','2','4']); // don't forget, we have a hidden field
        
        $this->crud->set_hidden('journal_id',$id);
        $this->crud->set_hidden('type',$this->session->userdata('type')); // Stock type. 0: in, 1: out
        
        $this->crud->set_rule('item_id','is_natural_no_zero');
        $this->crud->custom_form('raw/item_in_form');
        $this->crud->custom_list('raw/item_in_list');
        
        $this->crud->hide_controls();
        
        $this->crud->use_modal();
        $this->crud->ajax_list();
        
        $data['content']=$this->crud->run();
    }
    public function ajax_itemlist_out(){
        $id = $this->session->userdata('journal_id');

        $this->crud->init('stock', [
            'item_id' => 'Item Code',
            'date' => 'Date',
            'quantity' => 'Quantity',
        ]);
        $this->crud->change_type('date','date');
        
        // $this->crud->display_fields(['quantity','item_id','date']);
        $this->crud->join('item_id','items','id','code','type=2'); // Raw Materials only
        
        // !important    
        $this->crud->where(['journal_id='.$id]);
        
        $this->crud->order(['2','0','1','3']); // don't forget, we have a hidden field
        
        $this->crud->set_hidden('journal_id',$id);
        $this->crud->set_hidden('type',$this->session->userdata('type')); // Stock type. 0: in, 1: out
        
        $this->crud->set_rule('item_id','is_natural_no_zero');
        $this->crud->custom_form('raw/item_out_form');
        $this->crud->custom_list('raw/item_out_list');
        
        $this->crud->hide_controls();
        
        $this->crud->use_modal();
        $this->crud->ajax_list();
        
        $data['content']=$this->crud->run();
    }
    public function getJournalInTotal($journal_id){
        return $this->item_model->getJournalInTotal($journal_id);
    }
}