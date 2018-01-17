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
//             'code' => 'Item Code',
            'name' => 'Item Name',
 //           'uom'=>'UoM',
//            'type' => 'Type',
            'parent' => 'Parent',
            //'labourCost' => 'Labour Cost',
            'mrp' => 'MRP',
            'discount' => 'Discount',
            'truck' => 'Delivery By Truck',
            'thela' => 'Delivery By Thela',
            //'purchase_price' => 'Purchase Price',
            //'mrp' => 'MRP',
            //'pack' => 'Pack',
        ]);
        //$this->crud->display_fields(['Medicine Name','Medicine Code','Pack']);
         $this->crud->set_hidden('type','1'); // 1 for Medicine
        if($this->uri->segment(3) == 'ajax') {   
       $this->crud->ci->db->where('items.type','1'); // 1 for Medicine
        }
  //  $this->crud->join('uom','uom','id','uom');
       
  $this->crud->join('parent','items','id','name','items.type=0');
  
  //$this->crud->join('supplier','people','id','name','people.type=1');
        $this->crud->set_rule('name','required');
    //    $this->crud->set_rule('code','required');
     //   $this->crud->set_option('type',['1'=>'Item','0'=>'Category']);
        $this->crud->set_search('name');
     //   $this->crud->custom_form('items/item_create_form');
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    public function category(){
        //if($this->uri->segment(2)=='') redirect('item/index'); // datatable ajax list issue.
        
        //$data['title'] = 'Medicines List';
        $data['title'] = 'Category List';

        $this->crud->init('items',[
//            'name' => 'Medicine Name',
//            'code' => 'Medicine Code',
//            'cp' => 'CP Price',
//            'tp' => 'TP Price',
//            'mrp' => 'MRP',
//            'pack' => 'Pack',
//             'code' => 'Item Code',
            'name' => 'Category Name',
 //           'uom'=>'UoM',
  //          'type' => 'Type',
     //       'parent' => 'Parent',
            //'purchase_price' => 'Purchase Price',
            //'mrp' => 'MRP',
            //'pack' => 'Pack',
        ]);
        //$this->crud->display_fields(['Medicine Name','Medicine Code','Pack']);
         $this->crud->set_hidden('type','0'); // 1 for Medicine
            $this->crud->ci->db->where('type','0'); // 1 for Medicine
        //$this->crud->join('uom','uom','id','uom');
       // $this->crud->join('parent','items','id','name','items.type=0');
        //$this->crud->join('supplier','people','id','name','people.type=1');
        $this->crud->set_rule('name','required');
    //    $this->crud->set_rule('code','required');
     //   $this->crud->set_option('type',['1'=>'Item','0'=>'Category']);
        $this->crud->set_search('name');
        //$this->crud->custom_form('items/item_create_form');
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    } 
	public function stock(){
        $data['title'] = 'Item Stock';

        $this->crud->init('items',[
            'name' => 'Item Name',
            //'code' => 'Item Code',
            //'pack' => 'Pack',

        ]);
        //$this->crud->display_fields(['Item Code','Item Name','Stock Amount']);
        $this->crud->set_hidden('type','0'); // 1 for Medicine
        
        $this->crud->ci->db->where('type','1'); // 1 for Medicine
        //$this->crud->extra_buttons($this,['getRemaining'=>'Stock']);

        $this->crud->extra_fields($this,['getRemaining'=>'Stock Amount']);
        $this->crud->extra_buttons([
            ['title'=>'Move Stock', 'href'=>'#','icon'=>''],
        ]);
        $this->crud->set_rule('name','required');
        $this->crud->set_search('name');
        
        $this->crud->use_modal();
        
        $this->crud->hide_controls();
        
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    public function stockbysupplier()
    {
        $data['title'] = ' ';
        $this->load->model('item_model');
        $data['supplierInfo'] = $this->item_model->getRemainingBySupplier();
        $data['content'] = $this->load->view('StockBySupplier.php',[],true);
        $this->load->view('template',$data);
    }
    function importregister(){
    }
    public function getRemaining($id){
        $this->load->model('item_model');
        return $this->item_model->getRemaining($id);
    }
    public function in(){
        $this->load->model('item_model');
        
        if($this->uri->segment(3) == 'insert'){
            $id = $this->item_model->getUnsavedItem();
            if(isset($id->id)){
                $this->session->set_userdata('journal_id',$id->id);
                redirect('item/in/edit/'.$id->id);
            }
            else{
                $id = $this->item_model->insertDraft();
                $this->session->set_userdata('journal_id',$id);
                redirect('item/in/edit/'.$id);
            }
            
        }elseif($this->uri->segment(3) == 'edit'){
            $this->session->set_userdata('journal_id',$this->uri->segment(4));        
            $this->session->set_userdata('type','0'); // Stock Type: IN
            
        
        }elseif($this->uri->segment(3) == 'ajax'){ // when we fetch data

            $this->crud->ci->db->where('journals.type','0'); // Journal Type: IN
            $this->crud->ci->db->where('journals.status','1');
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
            //'supplier_id' => 'Supplier ID',
            'phone' => 'Phone',
            'customer' => 'Supplier Name', 
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
        $this->crud->set_hidden('status','1');
        //$this->crud->join('customer','people','id','name','people.type=1');
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
            // $id = $this->item_model->insertDraft();
            // $this->session->set_userdata('journal_id',$id);
            // redirect('item/out/edit/'.$id);
            $id = $this->item_model->getUnsavedItem();
            if(isset($id->id)){
                $this->session->set_userdata('journal_id',$id->id);
                redirect('item/out/edit/'.$id->id);
            }
            else{
                $id = $this->item_model->insertDraft();
                $this->session->set_userdata('journal_id',$id);
                redirect('item/out/edit/'.$id);
            }
        
        }elseif($this->uri->segment(3) == 'edit'){
            $this->session->set_userdata('journal_id',$this->uri->segment(4));        
            $this->session->set_userdata('type','1'); // Stock Type: OUT     
        
        }elseif($this->uri->segment(3) == 'ajax'){
            $this->crud->ci->db->where('journals.type','1'); // Journal Type: OUT
            //$this->crud->ci->db->where('item_type','1'); // Item Type: Medicine
        }elseif($this->uri->segment(3) == 'update'){

        }
        
        $this->crud->init('journals',[
            'date' => 'Posting Date',
            //'customer_id' => 'Customer ID',
            'phone' => 'Phone',
            'customer' => 'Customer Name',
            'description' => 'Description',
            //'price_type' => 'Price Type',
        ]);
        //$this->crud->set_option('price_type',['0'=>'CP Price','1'=>'TP Price']);
        //$this->crud->join('customer','people','id','name','type=0'); // Customer
    
        $this->crud->custom_form('items/journal_form');
        $this->crud->custom_view('items/journalViewOut');
        
        //$this->crud->extra_fields($this, ['getJournalOutTotal'=>'Total']);
        $this->crud->set_rule('date','required');
        $this->crud->set_hidden('type','1'); // Journal Type: OUT
        $this->crud->set_hidden('status','1');
        //$this->crud->set_hidden('item_type','1'); // Item Type: Medicine

        $this->crud->change_type('description','textarea');
        $this->crud->change_type('date','date');
        
        $this->crud->form_extra('id="formJournal"');
        $data = [
            'title' => 'Sales Register',
        ];
        $data['deliveryType'] = $this->item_model->getDeliveryType();    
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    public function ajax_itemlist(){
        $id = $this->session->userdata('journal_id');
        // echo $uri;
        // echo $uri;
        // echo $uri;
        // die();
        // $this->session->userdata('out',$this->uri->segment(2));
        // $this->session->set_userdata('seg',$ur);
     $this->crud->init('stock', [
            //'item_id' => 'Item Code',
            'item_name' => 'Item Name',
            // 'date' => 'Date',
            //'uom' => 'UoM',
            'unit_price' => 'Unit Price',
            'quantity' => 'Quantity',
           // 'offer' => 'Offer',
            
            
        ]);
        // $this->crud->change_type('date','date');
        if( $this->session->userdata('type') == 1 ) {
         $this->crud->display_fields(['Item Name','Unit Price','Quantity','Discount','Total']);
        }
        else{
            $this->crud->display_fields(['Item Name','Unit Price','Quantity','Total']);

        }
         //$this->crud->join('item_id','items','id','code','type=0'); // Medicine only
        
        $this->crud->join('item_name','items','id','name','type=1'); // Medicine only
        //$this->crud->join('uom','uom','id','uom');
        //!important    
        $this->crud->where(['journal_id='.$id]);
        $this->crud->extra_fields($this,['getDiscount'=>'Discount','getTotal'=>'Total' , 'getTotalLabourCost'=>'Labour Cost']);
        //$this->crud->order(['2','0','1','3','4']); // don't forget, we have a hidden field
        // $this->crud->order(['4','0','5','1','2','3','6','7']);
        $this->crud->order(['2','4','0','1','3','5']);
        $this->crud->set_hidden('journal_id',$id);
        
        $this->crud->set_hidden('type',$this->session->userdata('type')); // Stock type. 0: in, 1: out
        $this->crud->set_hidden('date',date('Y-m-d'));
        // $this->crud->display_fields(['Item Name','Item Code','Unit']);
        $this->crud->before_save($this, 'beforeSave');
       // $this->crud->set_rule('item_id','is_natural_no_zero'); 
       
        $this->crud->set_rule('item_name','is_natural_no_zero');
        $this->crud->custom_form('items/item_form');
        $this->crud->custom_list('items/item_list');
        
        
        //$this->crud->hide_controls();
        
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
    function getCustomerData($cusid){
        $this->load->model('item_model');
        $data = [];
        $result = $this->item_model->getCustomerData($cusid);
        $balance =  $this->item_model->getCustomerBalance($cusid);
     
        $data['id'] = $result->id;
        $data['name'] = $result->name;
        $data['phone'] = $result->phone;
        $data['code'] = $result->code;
        $data['businessName'] = $result->businessName;
        $data['address'] = $result->address;
        $data['email'] = $result->email;
        $data['businessAddress'] = $result->businessAddress;
        $data['area'] = $result->area;
        $data['district'] = $result->district;
        $data['openingBalance'] = $result->openingBalance;
        $data['totalBalance'] = $balance;
        echo json_encode($data);
    }
    function getSupplierData($cusid) {
        $this->load->model('item_model');
        $data = [];
        $result = $this->item_model->getSupplierData($cusid);
        $data['id'] = $result->id;
        $data['name'] = $result->name;
        $data['phone'] = $result->phone;
        $data['code'] = $result->code;
        $data['businessName'] = $result->businessName;
        $data['address'] = $result->address;
        $data['email'] = $result->email;
        $data['businessAddress'] = $result->businessAddress;
        $data['area'] = $result->area;
        $data['district'] = $result->district;
        $data['openingBalance'] = $result->openingBalance;
        echo json_encode($data);
    }
    function getUnitPrice($item) {
        $this->load->model('item_model');
        $data = [];
        $result = $this->item_model->getUnitPrice($item);
        $data['mrp'] = $result->mrp;
        $data['discount'] = $result->discount;
        $data['labourCost'] = $result->labourCost;
        echo json_encode($data);
    }
    function getTotalLabourCost($id) {
        $this->load->model('item_model');
        $query = $this->item_model->getTotalLabourCost($id);
        return $query->labourCost; 
    }
    function getDiscount($id) {
        $this->load->model('item_model');
        $query = $this->item_model->getDiscount($id);
        return $query->discount;
    } 
    function getStockData($journalId) {
        $this->load->model('item_model');
        $query = $this->item_model->getStockData($journalId);
         foreach($query as $result) {

         }
        $this->load->view('SalesInvoice',$result,true);
      
    }
    function getDeliveryType($deliveryType,$journalId){
        $this->load->model('item_model');
        $query = $this->item_model->getDeliveryCost($deliveryType,$journalId);
        $data['deliveryCost'] = $query->deliveryCost;
        echo json_encode($data);
    }
    function getRemainingBySupplier($id) {
        $this->load->model('item_model');
        $query = $this->item_model->getRemainingBySupplier($id);
        return $query->quantity;
        
    }
    function getSupplierName($id) {
       $this->load->model('item_model');
       $query = $this->item_model->getRemainingBySupplier($id);
       return $query->customer;
    
    } 
}