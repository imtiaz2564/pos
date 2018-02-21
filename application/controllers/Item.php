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
        
        $data['title'] = 'Items List';
        $this->crud->init('items',[
            'name' => 'Item Name',
            'parent' => 'Parent',
            'mrp' => 'MRP',
            'discount' => 'Discount',
            'truck' => 'Delivery By Truck',
            'thela' => 'Delivery By Thela',
        ]);
        $this->crud->set_hidden('type','1'); // 1 for item
        if($this->uri->segment(3) == 'ajax') {   
            $this->crud->ci->db->where('items.type','1'); // 1 for item
        }
        $this->crud->join('parent','items','id','name','items.type=0');
        $this->crud->set_rule('name','required');
        $this->crud->set_search('name');
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    public function category(){
        $data['title'] = 'Category List';
        $this->crud->init('items',[
            'name' => 'Category Name',
        ]);
        $this->crud->set_hidden('type','0'); // 1 for Category
        $this->crud->ci->db->where('type','0'); // 1 for Category
        $this->crud->set_rule('name','required');
        $this->crud->set_search('name');
        $this->crud->use_modal();
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    } 
	public function stock(){
        $data['title'] = 'Item Stock';
        $this->crud->init('items',[
            'name' => 'Item Name',
        ]);
        $this->crud->ci->db->where('type','1'); // 1 for item
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
    public function stockbysupplier() {
        $data['title'] = ' ';
        $this->load->model('item_model');
        $data['supplierInfo'] = $this->item_model->getRemainingBySupplier();
        $data['content'] = $this->load->view('StockBySupplier.php',$data,true);
        $this->load->view('template',$data);
    }
    function importregister(){
        $data['title'] = 'Import Register';

        $this->crud->init('stock',[
            'item_name' => 'Item Name',
            'warehouse' => 'Supplier',
            'quantity' => 'Quantity',
            'transportCost' => 'Transport Cost',
            'labourCost' => 'Labour Cost',
            'date' => 'Import Date',
            'unloadDate' => 'Unload Date',
            'transport' => 'Transport Info',
            'receiver' => 'Receiver Info',
            'driverName' => 'Driver Info',

        ]);
        $this->crud->join('item_name','items','id','name','type=1');
        $this->crud->join('warehouse','people','id','name','type=1');
        $this->crud->before_save($this, 'checkStock');
        $this->crud->after_save($this, 'stockUpdate');
        $this->crud->change_type('date','date');
        $this->crud->change_type('unloadDate','date');
        $this->crud->set_rule('item_name','required');
        $this->crud->set_hidden('type','2'); // 2 for transfer
        $this->crud->order(['10','9','8','7','6','4','5','0','2','1','3']);
        $this->crud->custom_form('items/import_form');
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    public function getRemaining($id){
        $this->load->model('item_model');
        return $this->item_model->getRemaining($id);
    }
    public function purchase(){
        $data['title'] = '';
        $data['content'] = $this->load->view('purchaseView.php',[],true);
        $this->load->view('template',$data);
    }
    public function sale(){
        $data['title'] = '';
        $data['content'] = $this->load->view('salesView.php',[],true);
        $this->load->view('template',$data);
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
            //$sup = $this->input->post('idSupplier');
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
//
        $this->crud->init('journals',[
            'date' => 'Posting Date',
            //'supplier_id' => 'Supplier ID',
            //'phone' => 'Phone',
            //'customer' => 'Supplier Name', 
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
            // 'phone' => 'Phone',
            // 'customer' => 'Customer Name',
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
        // die();
        // $this->session->userdata('out',$this->uri->segment(2));
        // $this->session->set_userdata('seg',$ur);
        $this->crud->init('stock', [
           // 'warehouse' => 'Supplier',
            'item_name' => 'Item Name',
            'unit_price' => 'Unit Price',
            'quantity' => 'Quantity',
        ]);
        // $this->crud->change_type('date','date');
        if( $this->session->userdata('type') == 1 ) {
            $this->crud->init('stock', [
                // 'warehouse' => 'Supplier',
                 'item_name' => 'Item Name',
                 'unit_price' => 'Unit Price',
                 'quantity' => 'Quantity',
             ]);
         $this->crud->display_fields(['Item Name','Unit Price','Quantity','Discount','Total']);
        }
        else{
            $this->crud->init('stock', [
                 'warehouse' => 'Supplier',
                 'item_name' => 'Item Name',
                 'unit_price' => 'Unit Price',
                 'quantity' => 'Quantity',
             ]);
            $this->crud->display_fields(['Supplier','Item Name','Unit Price','Quantity','Total']);
            $this->crud->join('warehouse','people','id','name','type=1'); // Medicine only
            $this->crud->set_hidden('date',date('Y-m-d'));
        

        }
         //$this->crud->join('item_id','items','id','code','type=0'); // Medicine only
        

         $this->crud->join('item_name','items','id','name','type=1');
        //$this->crud->join('uom','uom','id','uom');
        //!important    
        $this->crud->where(['journal_id='.$id]);
        $this->crud->extra_fields($this,['getDiscount'=>'Discount','getTotal'=>'Total' , 'getTotalLabourCost'=>'Labour Cost']);
        //$this->crud->order(['2','0','1','3','4']); // don't forget, we have a hidden field
        // $this->crud->order(['4','0','5','1','2','3','6','7']);
        $this->crud->order(['2','3','4','0','1','5']);
        $this->crud->set_hidden('journal_id',$id);
        //$this->crud->set_hidden('warehouse',$supplier);

        $this->crud->set_hidden('type',$this->session->userdata('type')); // Stock type. 0: in, 1: out
       // $this->crud->set_hidden('supplier_id',$suplr);
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
    // public function getLabourCost($id){
    //     $this->load->model('item_model');
    //     $data = [];
    //     $result  = $this->item_model->getLabourCost($id);
    //     $data['labourCost'] = $result->labourCost;
    //     echo json_encode($data); 
    
    // }
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
        
        $balance =  $this->item_model->getSupplierBalance($cusid);
       
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
    function getUnitPrice($item) {
        $this->load->model('item_model');
        $data = [];
        $result = $this->item_model->getUnitPrice($item);
        $data['mrp'] = $result->mrp;
        $data['discount'] = $result->discount;
        $data['labourCost'] = $result->labourCost;
        echo json_encode($data);
    }
    // function getTotalLabourCost($id) {
    //     $this->load->model('item_model');
    //     $query = $this->item_model->getTotalLabourCost($id);
    //     return $query->labourCost; 
    // }
    function getDiscount($id) {
        $this->load->model('item_model');
        $query = $this->item_model->getDiscount($id);
        return $query->discount;
    } 
    function getStockData($journalId,$labourCost = 0,$totalDiscount = 0) {
        $this->load->model('item_model');
        $data['salesData'] = $this->item_model->getStockData($journalId);
        $data['labourCost'] = $labourCost;
        $data['totalDiscount'] = $totalDiscount;
        $this->load->view('SalesInvoice',$data);
      
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
    function insertSupplierId($sup_id , $journalid){
        $this->load->model('item_model');
     
        $this->item_model->insertSupplier($sup_id , $journalid);

    }
    public function stockUpdate($post){
        $this->load->model('item_model');
        $this->item_model->insertStock($post['warehouse'] , $post['quantity'],$post['item_name']);
        die( json_encode(['error'=>'Updated Stock']));
  
    }
    public function checkStock($post){
        $this->load->model('item_model');
        $stock = $this->item_model->checkStock($post['warehouse'] , $post['item_name']);
        if($stock < $post['quantity'] ){
            die( json_encode(['error'=>'Number of items are not available']));
        }
        return $post;
    }
    public function refund(){
        $data['title'] = 'Item Refund';

        $this->crud->init('stock',[
            'customer_id' => 'Customer ID',
            'item_name' => 'Item Name',
            'quantity' => 'Quantity',
            'date' => 'Refund Date',
            'reason' => 'Reason',    
        ]);
        $this->crud->join('item_name','items','id','name','type=1');
        $this->crud->change_type('date','date');
        $this->crud->join('customer_id','people','id','businessAddress','type=0');
        $this->crud->set_rule('item_name','required');
        $this->crud->change_type('reason','textarea');
        $this->crud->set_hidden('type','3'); // 3 for refund
        $this->crud->after_save($this, 'refundStockUpdate');
        
        $this->crud->order(['4','3','0','1','2','5']);
        $this->crud->custom_form('items/refund_form');
        $data['content']=$this->crud->run();
        $this->load->view('template',$data);
    }
    function refundStockUpdate($post){
        $this->load->model('item_model');
        $this->item_model->updateStock($post['item_name'] , $post['quantity']);
        die( json_encode(['error'=>'Updated Stock']));
    }

}