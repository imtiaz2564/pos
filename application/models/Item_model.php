<?php
class Item_Model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    function getRemaining($id){
        
        // $query = $this->db->select('sum(quantity) as total')->where('item_name',$id)->where('type','0')->get('stock');
        // $in = $query->row()->total;

        // $query = $this->db->select('sum(quantity) as total')->where('item_name',$id)->where('type','1')->get('stock');
        // $out = $query->row()->total;
        
        // return $in-$out;
        $type = ['3','5','7'];
        $query = $this->db->select('sum(quantity) as total')->where('item_name',$id)->where_in('type',$type)->where('warehouse','3')->get('stock');
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
        //$query = $this->db->where('type',0)->where('code',$id)->or_where('name',$id)->or_where('phone',$id)->get('people');
        $query = $this->db->where('id',$id)->get('people');
        return $query->row();
    }
    function getSupplierData($id){
        //$query = $this->db->where('type',1)->where('code',$id)->or_where('name',$id)->or_where('phone',$id)->get('people');
        $query = $this->db->where('type',1)->where('id',$id)->get('people');
        return $query->row();
    }
    function getUnitPrice($item){
        $query = $this->db->where('id',$item)->get('items');
        return $query->row();
    }
    function getSalesData( $customerID , $datfrom , $datto) {
        if($customerID == 0 ){ // 0 for all customer 
            $salesData = $this->db->select('people.name as cusName ,people.businessName as businessName ,people.code as cusID, journals.id as journalId , journals.totalDiscount as totalDiscount , journals.description as salesDescription')->join('people','people.id=customer_id','left')->where('journals.type', 1)->where('date >=',$datfrom)->where('date <=',$datto)->get('journals')->result_array();
        }
        else{
            $salesData = $this->db->select('people.name as cusName ,people.businessName as businessName ,people.code as cusID, journals.id as journalId , journals.totalDiscount as totalDiscount , journals.description as salesDescription')->join('people','people.id=customer_id','left')->where('customer_id', $customerID)->where('date >=',$datfrom)->where('date <=',$datto)->get('journals')->result_array();
        }
        $total = [];
        foreach( $salesData as $salesData ) {
            $data = $this->db->select('stock.date,stock.journal_id as journalID,sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - '.$salesData['totalDiscount'].' as total ')->join('journals','journals.id=journal_id','left')->where('journal_id', $salesData['journalId'])->get('stock')->result_array();
            $data[0]['name'] = $salesData['cusName'];
            $data[0]['businessName'] = $salesData['businessName'];
            $data[0]['code'] = $salesData['cusID'];
            // $data[0]['journalID'] = $salesData['journalID'];

            $total[] = $data;
            
        }
        return $total;

    }
    function checkPhoneNumber($phone) {
        $query = $this->db->where('phone', $phone)->get('people');
        return $query->row();
    
    }
    function getCustomerBalance($id) {
       // $subTotal = 0;
        $total = 0;
        $type = ['0','1'];
        //$peopleID = $this->db->where('id',$id)->get('people')->row();
        
        //$peopleID = $this->db->where('type',0)->where('code',$id)->or_where('name',$id)->or_where('phone',$id)->get('people')->row(); 
        
        //$query = $this->db->select('sum(amount) as total')->where('type',0)->where('peopleID',$id)->or_where('name',$id)->or_where('phone',$id)->get('finance')->row();
        $query = $this->db->select('sum(amount) as total')->where('peopleID',$id)->where_in('paymentType',$type)->get('finance')->row();
        $cashback = $this->db->select('sum(amount) as cashback')->where('peopleID',$id)->where_in('paymentType','2')->get('finance')->row();
        
        //$openingBalance = $this->db->where('type',0)->where('code',$id)->or_where('name',$id)->or_where('phone',$id)->get('people')->row();
        $openingBalance = $this->db->where('id',$id)->get('people')->row();
        
        
        //$data = $this->db->where('customer_id', $id)->or_where('customer',$id)->or_where('phone',$id)->get('journals')->result();
            
       // $data = $this->db->where('customer_id', $peopleID->id)->get('journals')->result();
        
        $total = $openingBalance->openingBalance+$query->total-$cashback->cashback;
        // foreach($data as $data) {
        //     $stock = $this->db->where('journal_id' , $data->id)->get('stock')->result();
        //     foreach($stock as $stock){
        //         $subTotal += $stock->quantity*$stock->unit_price;
        //     }
            
        // }
        
        //new
        $purchase = $this->db->select('sum(stock.quantity * stock.unit_price) as purchase ')->where('type', 0)->where('warehouse', $id)->get('stock')->row();
        $refund = $this->db->select('sum(stock.quantity * stock.unit_price) as refund ')->where('type', 4)->where('customer_id', $id)->get('stock')->row(); 
        $salesData = $this->db->select('people.name as cusName ,people.code as cusID, journals.id as journalId , journals.totalDiscount as totalDiscount , journals.description as salesDescription')->join('people','people.id=customer_id','left')->where('customer_id', $id)->get('journals')->result_array();
        $totalSales = 0;
        foreach( $salesData as $salesData ) {
        $data = $this->db->select('sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - '.$salesData['totalDiscount'].' as total ')->join('journals','journals.id=journal_id','left')->where('journal_id', $salesData['journalId'])->get('stock')->row();
        
        $totalSales += $data->total;
        
    }
    return $total+$refund->refund+$purchase->purchase-$totalSales;
        
      //  return $total - $subTotal; 
    }
    function getSupplierBalance($id) {
        // $subTotal = 0;
        // $total = 0;
        // //$peopleID = $this->db->where('type',1)->where('code',$id)->or_where('name',$id)->or_where('phone',$id)->get('people')->row(); 
        // $peopleID = $this->db->where('type',1)->where('id',$id)->get('people')->row();

        // $query = $this->db->select('sum(amount) as total')->where('peopleID',$peopleID->id)->get('finance')->row();
       
        // //$openingBalance = $this->db->where('type',1)->where('code',$id)->or_where('name',$id)->or_where('phone',$id)->get('people')->row();
       
        // $openingBalance = $this->db->where('type',1)->where('id',$id)->get('people')->row();
       
        // //$data = $this->db->where('supplier_id', $peopleID->id)->get('journals')->result();
        // $data = $this->db->where('warehouse', $peopleID->id)->get('stock')->result();
       
        // $total = $openingBalance->openingBalance+$query->total;
       
        // foreach($data as $data) {
        //     // $stock = $this->db->where('journal_id' , $data->id)->get('stock')->result();
        //     // foreach($stock as $stock){
        //    //     $subTotal += $stock->quantity*$stock->unit_price;
        //     //}
        //     $subTotal += $data->quantity*$data->unit_price;
            
        // }
        // return $total - $subTotal;
        $total = 0;
        
        $query = $this->db->select('sum(amount) as total')->where('peopleID',$id)->where('type',1)->get('finance')->row();
        $openingBalance = $this->db->where('id',$id)->get('people')->row();
       
        $total = $openingBalance->openingBalance+$query->total;
       
        $purchase = $this->db->select('sum(stock.quantity * stock.unit_price) as purchase ')->where('type', 0)->where('warehouse', $id)->get('stock')->row();
        $refund = $this->db->select('sum(stock.quantity * stock.unit_price) as refund ')->where('type', 4)->where('customer_id', $id)->get('stock')->row(); 
         
        $salesData = $this->db->select('people.name as cusName ,people.code as cusID, journals.id as journalId , journals.totalDiscount as totalDiscount , journals.description as salesDescription')->join('people','people.id=customer_id','left')->where('customer_id', $id)->get('journals')->result_array();
        $totalSales = 0;
        foreach( $salesData as $salesData ) {
        $data = $this->db->select('sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - '.$salesData['totalDiscount'].' as total ')->join('journals','journals.id=journal_id','left')->where('journal_id', $salesData['journalId'])->get('stock')->row();
            $totalSales += $data->total;
        }
        return $total+$refund->refund+$purchase->purchase-$totalSales;
         
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
        $query =  $this->db->join('items','items.id=item_name','left')->join('journals','journals.id=journal_id','left')->where('journal_id',$journalId)->get('stock');
        return $query->result_array();
    }
    function getDeliveryType(){
        $query =  $this->db->get('uom');
        return $query->result();
    }
    function getDeliveryCost($deliveryType,$journalId){
        //$deliveryType = $deliveryType =='truck':'thela';
        //echo $deliveryType;
        //echo $journalId;
        $query =  $this->db->select('sum((quantity*items.'.$deliveryType.')) as deliveryCost')
        ->join('items','items.id=item_name','left')
        ->where('journal_id',$journalId)->get('stock')->row();
        return $query; 
    }
    function getRemainingBySupplier(){
        $total = []; 
        $query = $this->db->select('people.id as supplierid,stock.item_name as itemid, people.name as customerName,items.name,sum(stock.quantity) as quantity,')->join('items','items.id=item_name','left')->join('people','stock.warehouse = people.id','left')->where('stock.type',0)->where('stock.stockType',0)->group_by('people.name')->group_by('items.name')->get('stock')->result_array();
        foreach( $query as $data){
            $res = $this->db->select('sum(stock.quantity) as quantity')->where('stock.warehouse',$data['supplierid'])->where('stock.item_name',$data['itemid'])->where('stock.type',2)->get('stock')->row();
        
            $avail = $data['quantity'] - $res->quantity;
            $data['avail'] = $avail;
         
         
            $total[]  = $data;  
       
        }
        return $total;
    }
    // function insertSupplier($sup_id , $journalid){
    //     $this->db->set('warehouse', $sup_id); //value that used to update column  
    //     $this->db->where('journal_id', $journalid); //which row want to upgrade  
    //     $this->db->update('stock');
    // } 
    function updateStock( $item_name , $quantity,  $warehouse , $type ){
        // $query = $this->db->select('sum(quantity) as totalquantity')->where('warehouse',$warehouse)->where('item_name',$item_name)->get('stock')->row();
        // $rest = $query->totalquantity-$quantity;
        return $this->db->insert('stock',['item_name'=>$item_name,'quantity'=>$quantity,'warehouse'=>$warehouse ,'type'=>$type]);
    }
    function checkStock( $warehouse ,  $item_name ) {
        $query = $this->db->select('sum(quantity) as totalquantity,')->join('items','items.id=item_name','left')->where('warehouse',$warehouse)->where('item_name',$item_name)->where('stock.type',0)->where('stock.stockType',0)->get('stock')->row();
        $data = $this->db->select('sum(quantity) as transfer')->where('warehouse',$warehouse)->where('item_name',$item_name)->where('type',2)->get('stock')->row();
        $reminder =  $query->totalquantity - $data->transfer;
        return $reminder;
    }
    function getPeople(){
        $query =  $this->db->get('people')->result_array();
        return $query;
    }
    // function getSuppliers(){
    //     $query =  $this->db->where('type','1')->get('people')->result_array();
    //     return $query;
    // }
    // function getCustomerStatement( $customerID , $datfrom , $datto ) {
    //     $salesData = $this->db->where('customer_id', $customerID)->where('date >=',$datfrom)->where('date <=',$datto)->get('journals')->result();
    //     $data2 = $this->db->where('peopleID', $customerID)->get('finance')->result_array();
           
    //     $total = [];
    //     foreach( $salesData as $salesData ) {
       
    //         $data = $this->db->where('journal_id', $salesData->id)->get('stock')->result_array();
    //         $total['details'] = $data;
            
    //     }
    //     $total['statement'] = $data2;
    //     // print_r($total);
    //     // die();
    //     return $total;

    // }
    
    //commented
    // function getCustomerStatement( $customerID , $datfrom , $datto ){
    //     $type = ['0','1'];
    //     $statement = $this->db->where('peopleID', $customerID)->where('date >=',$datfrom)->where('date <=',$datto)->where_in('paymentType',$type)->get('finance')->result_array();
        
    //     return $statement;
    // }
    // function getCashBack( $customerID , $datfrom , $datto ){
        
    //     $cashBack = $this->db->where('peopleID', $customerID)->where('date >=',$datfrom)->where('date <=',$datto)->where('paymentType',2)->get('finance')->result_array();
    //     return $cashBack;
    // }
    // function getSupplierHistory( $supplierID , $datfrom , $datto){
    //     if( $supplierID == 0 ) {
    //         $salesData = $this->db->select('people.name as supplier,people.businessName as businessName,people.code as supplierID,items.name as itemName,stock.date as date,stock.unit_price as unit_price,stock.quantity as quantity,journals.description as purchaseDescription')->join('people','people.id=warehouse','left')->join('items','items.id=item_name','left')->join('journals','journals.id=journal_id','left')->where('stock.type', 0)->where('stock.date >=',$datfrom)->where('stock.date <=',$datto)->get('stock')->result_array();
    //         return $salesData;
    //     }    
    //     $salesData = $this->db->select('people.name as supplier,people.businessName as businessName,people.code as supplierID,items.name as itemName,stock.date as date,stock.unit_price as unit_price,stock.quantity as quantity,journals.description as purchaseDescription')->join('people','people.id=warehouse','left')->join('items','items.id=item_name','left')->join('journals','journals.id=journal_id','left')->where('warehouse', $supplierID)->where('stock.date >=',$datfrom)->where('stock.date <=',$datto)->get('stock')->result_array();
    //     return $salesData;
    // }
    // function getOpeningBalance($customerID){
    //     $data = $this->db->where('id',$customerID)->get('people')->result_array();
    //     return $data;//->openingBalance; 
    // }
    // // function updateStock($item_name, $quantity){
    // //     return $this->db->insert('stock',['item_name'=>$item_name,'quantity'=>$quantity,'warehouse'=>3,'type'=>5]);

    // // }
    // function getRefund($customerID , $datfrom , $datto){
    //     $refund = $this->db->where('customer_id', $customerID)->where('date >=',$datfrom)->where('date <=',$datto)->get('stock')->result_array();
    //     return $refund;
    // }
    //yet
    // function localStockUpdate($item_name, $quantity) {
    //     return $this->db->insert('stock',['item_name'=>$item_name,'quantity'=>$quantity,'warehouse'=>3,'type'=>3]);
    // }
    function getStockType($id) {
        $stock = $this->db->where('id',$id)->get('stock')->row();
        return $stock->stockType; 
    }
    function getCustomerStatement( $customerID , $datfrom , $datto ) {
        $final = [];
        $type = ['0','1'];
        $statements = $this->db->select('finance.date as date,finance.amount as depositAmount,finance.description as depositDescription ,"deposit" as type')->where('peopleID', $customerID)->where('date >=',$datfrom)->where('date <=',$datto)->where_in('paymentType',$type)->get('finance')->result_array();
        $final[] = $statements;
        // print_r($final);
        // die();
        // return $statement;
        
        $cashBack = $this->db->select('finance.date as date,finance.amount as backAmount,finance.description as backDescription ,"cashBack" as type')->where('peopleID', $customerID)->where('date >=',$datfrom)->where('date <=',$datto)->where('paymentType',2)->get('finance')->result_array();
        $final[] = $cashBack;
        // return $cashBack;
        
        // if( $customerID == 0 ) {
        //     $salesData = $this->db->select('people.name as supplier,people.businessName as businessName,people.code as supplierID,items.name as itemName,stock.date as date,stock.unit_price as unit_price,stock.quantity as quantity,journals.description as purchaseDescription')->join('people','people.id=warehouse','left')->join('items','items.id=item_name','left')->join('journals','journals.id=journal_id','left')->where('stock.type', 0)->where('stock.date >=',$datfrom)->where('stock.date <=',$datto)->get('stock')->result_array();
        //     $final[] = $salesData;
        //     //return $salesData;
        // }    
        
        //return $salesData;
        
        $openingBalance = $this->db->select(' "00-00-0000" as date,people.code as code,people.name as name,people.businessName as businessName,people.address as address,people.businessAddress as businessAddress,people.area as area,people.thana as thana,people.district as district,people.phone as phone,people.email as email,people.openingBalance as openingBalance, "openingBalance" as type')->where('id',$customerID)->get('people')->result_array();
        $final[] = $openingBalance;
        //return $data;
        
        $refund = $this->db->select('stock.date as date,stock.unit_price as unit_price,stock.quantity as quantity,stock.reason as refundDescription,"refund" as type')->where('customer_id', $customerID)->where('date >=',$datfrom)->where('date <=',$datto)->get('stock')->result_array();
        $final[] = $refund;
        
        $purchaseData = $this->db->select('stock.date as date,stock.unit_price as unit_price,stock.quantity as quantity,journals.description as purchaseDescription,"purchase" as type')->join('people','people.id=warehouse','left')->join('items','items.id=item_name','left')->join('journals','journals.id=journal_id','left')->where('warehouse', $customerID)->where('stock.date >=',$datfrom)->where('stock.date <=',$datto)->get('stock')->result_array();
        $final[]  = $purchaseData; 
        
        $salesData = $this->db->select('people.name as cusName ,people.businessName as businessName ,people.code as cusID, journals.id as journalId , journals.totalDiscount as totalDiscount , journals.description as salesDescription')->join('people','people.id=customer_id','left')->where('customer_id', $customerID)->where('date >=',$datfrom)->where('date <=',$datto)->get('journals')->result_array();
        $totalSales = [];
        foreach( $salesData as $salesData ) {
        $sales = $this->db->select('stock.date as date,stock.journal_id as journalID,sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - '.$salesData['totalDiscount'].' as total, "sales" as type')->join('journals','journals.id=journal_id','left')->where('journal_id', $salesData['journalId'])->get('stock')->result_array();
        // $data[0]['name'] = $salesData['cusName'];
        // $data[0]['businessName'] = $salesData['businessName'];
        // $data[0]['code'] = $salesData['cusID'];
        // $data[0]['journalID'] = $salesData['journalID'];

        //$totalSales[] = $data;
    
        $final[] = $sales; 
           
    }
        //$final[] = $totalSales;
       
        //return $total;
        foreach( $final as $data)
        foreach( $data as $data){
            $result[] =$data;
         
        }
        function date_compare($a , $b){
            $t1 = strtotime($a['date']);
            $t2 = strtotime($b['date']);
            return $t1 - $t2; 
        }
        usort($result,'date_compare');
        // usort($result, function($a, $b) {
        //     return $a['date'] - $b['date'];
        // });
       
        //  print_r($result);
        //  die();
        return $result;
    }
}
