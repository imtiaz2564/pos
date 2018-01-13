<?php
class Item_Model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    function getRemaining($id){
        //return $id;
        $query = $this->db->select('sum(quantity) as total')->where('item_name',$id)->where('type','0')->get('stock');
        $in = $query->row()->total;

        $query = $this->db->select('sum(quantity) as total')->where('item_name',$id)->where('type','1')->get('stock');
        $out = $query->row()->total;
        
        return $in-$out;
    }
    function insertDraft(){
        $this->db->insert('journals',['date'=>date('Y-m-d')]);
        return $this->db->insert_id();
    }
    function getJournalOutTotal($journal_id){
        $type = 'cp';
        $price_type = $this->db->select('price_type')->where('id', $journal_id)->get('journals')->row()->price_type;
        if( $price_type == 1) $type = 'tp';
        $items = $this->db->where('journal_id',$journal_id)->get('stock')->result();
        $price = 0;
        $total = 0;
        $type = 'mrp';
        foreach($items as $item){
            print_r($item->item_id);
            die();
            $price = $this->db->select($type)->where('id',$item->item_id)->get('items')->row()->$type;
            $total += $price*$item->quantity;
        }
        return $total;
    }
    function getJournalInTotal($journal_id){
        $items = $this->db->where('journal_id',$journal_id)->get('stock')->result();
        $total = 0;
        foreach($items as $item){
            $total += $item->unit_price*$item->quantity;
        }
        return $total;
    }
    function getTransactions($journal_id){
        return $this->db->where('journal_id',$journal_id)->join('items','items.id=item_name','left')->get('stock')->result_array();
    }
    function getDue( $people_id ){
        $journals = $this->db->where('customer_id',$people_id)->get('journals')->result();
        $opening = $this->db->where('id',$people_id)->get('people')->row()->openingBalance;
        $due = 0;
        foreach($journals as $journal){
            $due += $this->getJournalOutTotal($journal->id);
        }
        
        $paid = 0;
        $receives = $this->db->where('peopleID',$people_id)->where('type',0)->get('finance')->result();
        foreach( $receives as $receive ){
            $paid += $receive->amount;
        }
        
        return $opening + $due - $paid;
    }
    function getPayable( $people_id ){
        $journals = $this->db->where('supplier_id',$people_id)->get('journals')->result();
        $opening = $this->db->where('id',$people_id)->get('people')->row()->openingBalance;

        $payable = 0;
        foreach($journals as $journal){
            $payable += $this->getJournalInTotal($journal->id);
        }
        
        $paid = 0;
        $payments = $this->db->where('peopleID',$people_id)->where('type',1)->get('finance')->result();
        foreach( $payments as $payment ){
            $paid += $payment->amount;
        }
        
        return $opening + $payable - $paid;
    }
    function getCustomerId(){
        $query =  $this->db->where('type','0')->get('people');
        $maxID = $query->num_rows()+1;
        while($this->CustomerIdExists(sprintf('CI%05d',$maxID)))
        $maxID++;
          return sprintf('CI%05d',$maxID);

    }
    function CustomerIdExists($code, $notID=0) {
          $query =  $this->db->where('code', $code)->get('people');
          if($notID != 0)
              $query =  $this->db->where('code !=', $code)->get('people');
          $count = $query->num_rows();
          if($count > 0) return true; return false;
          
    }
    function getSupplierId(){
        $query =  $this->db->where('type','1')->get('people');
        $maxID = $query->num_rows()+1;
        while($this->SupplierIdExists(sprintf('SI%05d',$maxID)))
        $maxID++;
          return sprintf('SI%05d',$maxID);

    }
    function SupplierIdExists($code, $notID=0) {
          $query =  $this->db->where('code', $code)->get('people');
          if($notID != 0)
              $query =  $this->db->where('code !=', $code)->get('people');
          $count = $query->num_rows();
          if($count > 0) return true; return false;
          
    }
    function getLabourCost($id) {
        // $query =  $this->db->where('id', $id)->get('uom');
        $query =  $this->db->where('id', $id)->get('items');
        return $query->row();
     }
    function getTotal($id){
         $query =  $this->db->where('id', $id)->get('stock')->row();
        // $uomCost = $this->db->where('id', $query->uom)->get('uom')->row();//$query->uom;
        // return ($uomCost->labourCost *  $query->quantity)+($query->unit_price	*  $query->quantity);
        return $query->unit_price*$query->quantity;
    }
    function getSubTotal($id){
        $query =  $this->db->where('journal_id', $id)->get('stock')->row();
        $uomCost = $this->db->where('id', $query->uom)->get('uom')->row();//$query->uom;
        return $uomCost->labourCost;
        // return ($uomCost->labourCost *  $query->quantity)+($query->unit_price	*  $query->quantity);
    }
    function getCustomerData($id){
        $query = $this->db->where('type',0)->where('code',$id)->or_where('name',$id)->or_where('phone',$id)->get('people');
        return $query->row();
    }
    function getSupplierData($id){
        $query = $this->db->where('type',1)->where('code',$id)->or_where('name',$id)->or_where('phone',$id)->get('people');
        return $query->row();
    }
    function getUnitPrice($item){
        $query = $this->db->where('id',$item)->get('items');
        return $query->row();
    }
    function getSalesData( $customerID  ){
        $salesData = $this->db->where('customer_id', $customerID)->get('journals')->result();
         $total = [];
        foreach( $salesData as $salesData ) {
           $data = $this->db->where('journal_id', $salesData->id)->get('stock')->result();
           array_push($total , $data);  
        }
        return $total;
    }
    function checkPhoneNumber($phone) {
        $query = $this->db->where('phone', $phone)->get('people');
        return $query->row();
    
    }
    // function getCustomerBalance($id) {
    //     $query = $this->db->select('sum(amount) as total')->where('type',0)->where('peopleID',$id)->or_where('name',$id)->or_where('phone',$id)->get('finance');
    //     return $query->row();
    //  }   
function getCustomerBalance($id) {
    $subTotal = 0;
    $total = 0;
    $query = $this->db->select('sum(amount) as total')->where('type',0)->where('peopleID',$id)->or_where('name',$id)->or_where('phone',$id)->get('finance')->row();
    $openingBalance = $this->db->where('type',0)->where('code',$id)->or_where('name',$id)->or_where('phone',$id)->get('people')->row();
    $data = $this->db->where('customer_id', $id)->or_where('customer',$id)->or_where('phone',$id)->get('journals')->result();
    $total = $openingBalance->openingBalance+$query->total;
    foreach($data as $data) {
        $stock = $this->db->where('journal_id' , $data->id)->get('stock')->result();
        foreach($stock as $stock){
            $subTotal += $stock->quantity*$stock->unit_price;}
          
    }
    return $total - $subTotal; 
} 
    function checkBusinessName($businessName) {
        $query = $this->db->where('businessName', $businessName)->get('people');
        return $query->row();
    }
    function getTotalLabourCost($id) {
        $query =  $this->db->where('id', $id)->get('stock');
        return $query->row();
    }
    function getUnsavedItem() {
        $query =  $this->db->where('status',0)->get('journals');
        return $query->row();  
    }
    function getDiscount($id) {
        $query =  $this->db->where('id',$id)->get('stock');
        return $query->row();
    }
    function getStockData($journalId) {
        $query =  $this->db->where('journal_id',$journalId)->get('stock');
        return $query->result();
    }
    function getDeliveryType(){
        $query =  $this->db->get('uom');
        return $query->result();
    }
    function getDeliveryCost($deliveryType,$journalId){
        //$deliveryType = $deliveryType =='truck':'thela';
    //    echo $deliveryType;
    //    echo $journalId;
        $query =  $this->db->select('sum((quantity*items.'.$deliveryType.')) as deliveryCost')
        ->join('items','items.id=item_name','left')
        ->where('journal_id',$journalId)->get('stock')->row();
        return $query; 
        // foreach($query as )
        // $total = 
        ///print_r($query);

    } 
}
