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
        $query =  $this->db->where('id', $id)->get('uom');
        return $query->row();

    }
    function getTotal($id){
        $query =  $this->db->where('id', $id)->get('stock')->row();
        $uomCost = $this->db->where('id', $query->uom)->get('uom')->row();//$query->uom;
        return ($uomCost->labourCost *  $query->quantity)+($query->unit_price	*  $query->quantity);
    }
    function getSubTotal($id){
        $query =  $this->db->where('journal_id', $id)->get('stock')->row();
        $uomCost = $this->db->where('id', $query->uom)->get('uom')->row();//$query->uom;
        return $uomCost->labourCost;
        // return ($uomCost->labourCost *  $query->quantity)+($query->unit_price	*  $query->quantity);
    }
    function getCustomerData($id){
        $query = $this->db->where('code',$id)->or_where('name',$id)->or_where('phone',$id)->get('people');
        return $query->row();
    }
}