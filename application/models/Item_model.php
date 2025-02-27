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
        $type = ['-3','5','7'];
        $query = $this->db->select('sum(quantity) as total')->where('item_name',$id)->where_in('type',$type)->where('warehouse',-3)->get('stock');
        $in = $query->row()->total;

       // $query = $this->db->select('sum(quantity) as total')->where('item_name',$id)->where('type',1)->get('stock');
        $data3 = $this->db->select('sum(stock.quantity) as total')->join('stock','stock.journal_id=journals.id','left')->where('stock.item_name',$id)->where('journals.type = ',1)->get('journals');
        
        
        // $out = $query->row()->total;
        $out =  $data3->row()->total; 
        return $in-$out;
    }
    function insertDraft($user){
         $this->db->insert('journals',['date'=>date('Y-m-d'),'user'=>$user]);
        //$this->db->insert('journals',['status'=>0]);
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
    function getCustomerId(){
        $query =  $this->db->where('type','0')->get('people');
        $maxID = $query->num_rows()+1;
        while($this->CustomerIdExists(sprintf('RC%05d',$maxID)))
        $maxID++;
          return sprintf('RC%05d',$maxID);

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
        while($this->SupplierIdExists(sprintf('RS%05d',$maxID)))
        $maxID++;
          return sprintf('RS%05d',$maxID);

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
        $query = $this->db->where('id',$id)->get('people');
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
            $data = $this->db->select('journals.date as journalDate,stock.date,stock.journal_id as journalID,sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - '.$salesData['totalDiscount'].' as total ')->join('journals','journals.id=journal_id','left')->where('journal_id', $salesData['journalId'])->get('stock')->result_array();
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
    // function getCustomerBalance($id) {
    //    // $subTotal = 0;
    //     $total = 0;
    //     $type = [0,1];
    //     //$peopleID = $this->db->where('id',$id)->get('people')->row();
        
    //     //$peopleID = $this->db->where('type',0)->where('code',$id)->or_where('name',$id)->or_where('phone',$id)->get('people')->row(); 
        
    //     //$query = $this->db->select('sum(amount) as total')->where('type',0)->where('peopleID',$id)->or_where('name',$id)->or_where('phone',$id)->get('finance')->row();
    //     $query = $this->db->select('sum(amount) as total')->where('peopleID',$id)->where_in('paymentType',$type)->get('finance')->row();
       
    //     $cashback = $this->db->select('sum(amount) as cashback')->where('peopleID',$id)->where('paymentType',2)->get('finance')->row();
    //     //$openingBalance = $this->db->where('type',0)->where('code',$id)->or_where('name',$id)->or_where('phone',$id)->get('people')->row();
    //     $openingBalance = $this->db->where('id',$id)->get('people')->row();
    //     //$data = $this->db->where('customer_id', $id)->or_where('customer',$id)->or_where('phone',$id)->get('journals')->result();
    //    // $data = $this->db->where('customer_id', $peopleID->id)->get('journals')->result();
    //     $total = ($openingBalance->openingBalance+$query->total)-$cashback->cashback;
    //     // foreach($data as $data) {
    //     //     $stock = $this->db->where('journal_id' , $data->id)->get('stock')->result();
    //     //     foreach($stock as $stock){
    //     //         $subTotal += $stock->quantity*$stock->unit_price;
    //     //     }
            
    //     // }
    //     //new
    //     $purchase = $this->db->select('sum(stock.quantity * stock.unit_price) as purchase ')->where('type', 0)->where('warehouse', $id)->get('stock')->row();
    //     $refund = $this->db->select('sum(stock.quantity * stock.unit_price) as refund ')->where('type', 4)->where('customer_id', $id)->get('stock')->row(); 
    //     $salesData = $this->db->select('people.name as cusName ,people.code as cusID, journals.id as journalId , journals.totalDiscount as totalDiscount , journals.description as salesDescription')->join('people','people.id=customer_id','left')->where('customer_id', $id)->get('journals')->result_array();
    //     $totalSales = 0;
    //     foreach( $salesData as $salesData ) {
    //     $data = $this->db->select('sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - '.$salesData['totalDiscount'].' as total ')->join('journals','journals.id=journal_id','left')->where('journal_id', $salesData['journalId'])->get('stock')->row();
        
    //         $totalSales += $data->total;
        
    //     }
    //     return ($total+$refund->refund+$purchase->purchase)-$totalSales;
        
    //   //  return $total - $subTotal; 
    // }
    function getCustomerBalance($id) {
        $total = 0;
        $type = [0,1];
        $query = $this->db->select('sum(amount) as totalreceive')->where('type',0)->where('peopleID',$id)->where_in('paymentType',$type)->get('finance')->row();
        $payment = $this->db->select('sum(amount) as totalpayment')->where('type',1)->where('peopleID',$id)->where_in('paymentType',$type)->get('finance')->row();

        $cashback = $this->db->select('sum(amount) as cashback')->where('peopleID',$id)->where('paymentType',2)->get('finance')->row();
        $openingBalance = $this->db->where('id',$id)->get('people')->row();
        $total = ($openingBalance->openingBalance+$query->totalreceive)-$payment->totalpayment-$cashback->cashback;
        //new
        $purchase = $this->db->select('sum(stock.quantity * stock.unit_price) as purchase ')->where('type', 0)->where('warehouse', $id)->get('stock')->row();
        $refund = $this->db->select('sum(stock.quantity * stock.unit_price) as refund ')->where('type', 4)->where('customer_id', $id)->get('stock')->row(); 
        $salesData = $this->db->select('people.name as cusName ,people.code as cusID, journals.id as journalId , journals.totalDiscount as totalDiscount , journals.description as salesDescription')->join('people','people.id=customer_id','left')->where('customer_id', $id)->get('journals')->result_array();
        $totalSales = 0;
        foreach( $salesData as $salesData ) {
            $data = $this->db->select('sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - '.$salesData['totalDiscount'].' as total ')->join('journals','journals.id=journal_id','left')->where('journal_id', $salesData['journalId'])->get('stock')->row();
            $totalSales += $data->total;
        }
        return ($total+$refund->refund+$purchase->purchase)-$totalSales;
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
       
        $total = $openingBalance->openingBalance-$query->total;
       
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
    function getUnsavedItem($user) {
        $query =  $this->db->where('user',$user)->where('status',0)->get('journals');
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
    // function getDeliveryType(){
    //     $query =  $this->db->get('uom');
    //     return $query->result();
    // }
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
        $query = $this->db->select('people.id as supplierid,stock.item_name as itemid, people.name as customerName,people.businessName as businessName,items.name,sum(stock.quantity) as quantity,')->join('items','items.id=item_name','left')->join('people','stock.warehouse = people.id','left')->where('stock.type',0)->where('stock.stockType',0)->group_by('people.businessName')->group_by('items.name')->get('stock')->result_array();
        foreach( $query as $data){
            $res = $this->db->select('sum(stock.quantity) as quantity')->where('stock.warehouse',$data['supplierid'])->where('stock.item_name',$data['itemid'])->where('stock.type',2)->get('stock')->row();
        
            $avail = $data['quantity'] - $res->quantity;
            $data['avail'] = $avail;
         
         
            $total[]  = $data;  
       
        }
        return $total;
    }
    function updateStock( $item_name , $quantity, $warehouse , $type ){
        $date = date('Y-m-d');
        // $query = $this->db->select('sum(quantity) as totalquantity')->where('warehouse',$warehouse)->where('item_name',$item_name)->get('stock')->row();
        // $rest = $query->totalquantity-$quantity;
        return $this->db->insert('stock',['item_name'=>$item_name,'quantity'=>$quantity,'date'=>$date,'warehouse'=>$warehouse ,'type'=>$type]);
   
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
    function getSupplierHistory( $supplierID , $datfrom , $datto){
        if( $supplierID == 0 ) {
            $salesData = $this->db->select('people.name as supplier,people.businessName as businessName,people.code as supplierID,items.name as itemName,stock.date as date,stock.unit_price as unit_price,stock.quantity as quantity,journals.description as purchaseDescription')->join('people','people.id=warehouse','left')->join('items','items.id=item_name','left')->join('journals','journals.id=journal_id','left')->where('stock.type', 0)->where('stock.date >=',$datfrom)->where('stock.date <=',$datto)->get('stock')->result_array();
            return $salesData;
        }    
        $salesData = $this->db->select('people.name as supplier,people.businessName as businessName,people.code as supplierID,items.name as itemName,stock.date as date,stock.unit_price as unit_price,stock.quantity as quantity,journals.description as purchaseDescription')->join('people','people.id=warehouse','left')->join('items','items.id=item_name','left')->join('journals','journals.id=journal_id','left')->where('warehouse', $supplierID)->where('stock.date >=',$datfrom)->where('stock.date <=',$datto)->get('stock')->result_array();
        return $salesData;
    }
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
        $statements = $this->db->select('finance.date as date,finance.amount as depositAmount,finance.type as paymentType,finance.description as depositDescription ,"deposit" as type')->where('peopleID', $customerID)->where('date >=',$datfrom)->where('date <=',$datto)->where_in('paymentType',$type)->get('finance')->result_array();
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
        
        // $openingBalance = $this->db->select(' " " as date,people.code as code,people.name as name,people.businessName as businessName,people.address as address,people.businessAddress as businessAddress,people.area as area,people.thana as thana,people.district as district,people.phone as phone,people.email as email,people.openingBalance as openingBalance, "openingBalance" as type')->where('id',$customerID)->get('people')->result_array();
        // $final[] = $openingBalance;
        //return $data;
        
        $refund = $this->db->select('stock.date as date,items.name as itemName,stock.unit_price as unit_price,stock.quantity as quantity,stock.reason as refundDescription,"refund" as type')->join('items','items.id=item_name','left')->where('customer_id', $customerID)->where('date >=',$datfrom)->where('date <=',$datto)->where('stock.type =',4)->get('stock')->result_array();
        $final[] = $refund;
        
        $purchaseData = $this->db->select('stock.date as date,items.name as itemName,stock.unit_price as unit_price,stock.quantity as quantity,journals.description as purchaseDescription,"purchase" as type')->join('people','people.id=warehouse','left')->join('items','items.id=item_name','left')->join('journals','journals.id=journal_id','left')->where('warehouse', $customerID)->where('stock.date >=',$datfrom)->where('stock.date <=',$datto)->where('stock.type =',0)->get('stock')->result_array();
        $final[]  = $purchaseData; 
        
        $salesData = $this->db->select('people.name as cusName ,people.businessName as businessName ,people.code as cusID, journals.id as journalId , journals.totalDiscount as totalDiscount , journals.description as salesDescription')->join('people','people.id=customer_id','left')->where('customer_id', $customerID)->where('date >=',$datfrom)->where('date <=',$datto)->where('journals.type =',1)->get('journals')->result_array();
        $totalSales = [];
        foreach( $salesData as $salesData ) {
            $sales = $this->db->select('journals.date as date,stock.journal_id as journalID,sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - '.$salesData['totalDiscount'].' as total, "sales" as type')->join('journals','journals.id=journal_id','left')->where('journal_id', $salesData['journalId'])->get('stock')->result_array();
            // $data[0]['name'] = $salesData['cusName'];
            // $data[0]['businessName'] = $salesData['businessName'];
            // $data[0]['code'] = $salesData['cusID'];
            // $data[0]['journalID'] = $salesData['journalID'];

            //$totalSales[] = $data;
        
            $final[] = $sales; 
        }
        //$final[] = $totalSales;
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
        return $result;
    }
    function getPaymentData($from , $to) {
        $paymentType = [0,1,2];
        $result = $this->db->select('people.businessName as businessName,finance.type as type,finance.amount as amount,finance.paymentType as paymentType,finance.description as description,finance.date as date')->join('people','people.id=peopleID','left')->where('date >=',$from)->where('date <=',$to)->where_in('paymentType',$paymentType)->get('finance')->result_array();
        
        return $result;
    }
    function getPaymentOpening($from){
        $paymentType = [0,1,2];
        $result = $this->db->select('people.businessName as businessName,finance.type as type,finance.amount as amount,finance.paymentType as paymentType,finance.description as description,finance.date as date')->join('people','people.id=peopleID','left')->where('date <',$from)->where_in('paymentType',$paymentType)->get('finance')->result_array();
        return $result;
    }
    function getCustomerTransaction($datfrom , $datto){
        //$salesData = $this->db->select('people.businessName , journals.date  ,journals.type ,journals.customer_id as customerID, sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - journals.totalDiscount as totalSales , "sales" as type')->join('people','journals.customer_id=people.id','left')->join('stock','stock.journal_id=journals.id','left')->where('journals.date >=',$datfrom)->where('journals.date <=',$datto)->where('people.type',0)->group_by('journals.customer_id')->group_by('journals.date')->get('journals')->result_array();
         
        $totalsales =0;
        $sales = $this->db->select('people.name as cusName ,people.code as cusID, journals.id as journalId , journals.totalDiscount as totalDiscount , journals.description as salesDescription')->join('people','people.id=customer_id','left')->where('journals.date >=',$datfrom)->where('journals.date <=',$datto)->get('journals')->result_array();
            
        foreach( $sales as $sales ) {
            $res = $this->db->select('sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - '.$sales['totalDiscount'].' as total ')->join('journals','journals.id=journal_id','left')->where('journal_id', $sales['journalId'])->get('stock')->row();
            $totalsales += $res->total;
            print_r($sales);
        
        }
        // echo $totalsales;
        // die();
        $transaction[] = $totalsales;

        $purchaseData = $this->db->select('people.id as customerID,people.businessName,stock.date as date,sum(stock.unit_price * stock.quantity) as purchaseprice ,"purchase" as type')->join('people','people.id=warehouse','left')->join('journals','journals.id=journal_id','left')->where('stock.date >=',$datfrom)->where('stock.date <=',$datto)->group_by('stock.warehouse')->group_by('stock.date')->get('stock')->result_array();
        $transaction[] = $purchaseData;
        $finance = $this->db->select('people.id as customerID,people.businessName,finance.date as date,sum(finance.amount) as amount ,finance.paymentType as paymentType,"finance" as type')->join('people','people.id=finance.peopleID','left')->where('finance.date >=',$datfrom)->where('finance.date <=',$datto)->group_by('finance.date')->group_by('finance.peopleID')->get('finance')->result_array();
        $transaction[] = $finance;
        $total = 0;
        $totalSales = 0;
        foreach($transaction as $data)
        foreach($data as $data) {
            print_r($data);
            if(!empty($data['customerID'])) {
        
            // $query = $this->db->select('sum(amount) as total')->where('peopleID',$data['customerID'])->where('date >',$datfrom)->where('type',1)->get('finance')->row();
           
            // $openingBalance = $this->db->select('people.openingBalance as balance')->where('id',$data['customerID'])->get('people')->row();
            // $total = $openingBalance->balance+$query->total;    
        
            
            // $purchase = $this->db->select('sum(stock.quantity * stock.unit_price) as purchase ')->where('date > ',$datfrom)->where('type', 0)->where('warehouse', $data['customerID'])->get('stock')->row();
            // $refund = $this->db->select('sum(stock.quantity * stock.unit_price) as refund ')->where('date > ',$datfrom)->where('type', 4)->where('customer_id', $data['customerID'])->get('stock')->row(); 
            // $salesData = $this->db->select('people.name as cusName ,people.code as cusID, journals.id as journalId , journals.totalDiscount as totalDiscount , journals.description as salesDescription')->join('people','people.id=customer_id','left')->where('date > ',$datfrom)->where('customer_id', $data['customerID'])->get('journals')->result_array();
            // $totalSales = 0;
            // foreach( $salesData as $salesData ) {
            // $data2 = $this->db->select('sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - '.$salesData['totalDiscount'].' as total ')->join('journals','journals.id=journal_id','left')->where('journal_id', $salesData['journalId'])->get('stock')->row();
            //     $totalSales += $data2->total;
            // }
            // $pevBalance =  $total+$refund->refund+$purchase->purchase-$totalSales;
            // $data ['pevBalance'] = $pevBalance;
            // $final[] = $data;
            //$total = 0;
            $type = [0,1];
            $query = $this->db->select('sum(amount) as total')->where('peopleID',$data['customerID'])->where_in('paymentType',$type)->get('finance')->row();
           
            $cashback = $this->db->select('sum(amount) as cashback')->where('peopleID',$data['customerID'])->where('paymentType',2)->get('finance')->row();
            $openingBalance = $this->db->where('id',$data['customerID'])->get('people')->row();
          
            
            
          
            $total = ($openingBalance->openingBalance+$query->total)-$cashback->cashback;
          
    
            $purchase = $this->db->select('sum(stock.quantity * stock.unit_price) as purchase ')->where('type', 0)->where('warehouse', $data['customerID'])->get('stock')->row();
            $refund = $this->db->select('sum(stock.quantity * stock.unit_price) as refund ')->where('type', 4)->where('customer_id', $data['customerID'])->get('stock')->row(); 
            $salesData = $this->db->select('people.name as cusName ,people.code as cusID, journals.id as journalId , journals.totalDiscount as totalDiscount , journals.description as salesDescription')->join('people','people.id=customer_id','left')->where('customer_id', $data['customerID'])->get('journals')->result_array();
            
            foreach( $salesData as $salesData ) {
                $data2 = $this->db->select('sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - '.$salesData['totalDiscount'].' as total ')->join('journals','journals.id=journal_id','left')->where('journal_id', $salesData['journalId'])->get('stock')->row();
                $totalSales += $data2->total;
            
            }
            //echo $totalSales;
            //$final[] = $data;
            $prevbalance = ($total+$refund->refund+$purchase->purchase)-$totalSales; 
            echo $prevbalance;
            die();       
        }
        
   
    }
    
    }
    function getImportData($datfrom , $datto){
        $importData = $this->db->select('people.id as customerID,people.businessName,stock.unloaddate as unloadDate,stock.unit_price as unitPrice ,stock.labourCost as labourCost ,stock.transportCost as transportCost, stock.transport as transport, stock.quantity as quantity,stock.receiver as receiverName,items.name as itemName')->join('items','items.id=stock.item_name','left')->join('people','people.id=stock.warehouse','left')->where('stock.unloaddate >=',$datfrom)->where('stock.unloaddate <=',$datto)->where('stock.type',2)->get('stock')->result_array();
        
        return $importData;
    }
    function getsalesreportData($datFrom , $datTo){
    // $dat = date('Y-m-d');
  
    //     // $salesData = $this->db->select('people.businessName , journals.date  ,journals.type ,journals.customer_id as customerID, sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - journals.totalDiscount as totalSales , "sales" as type')->join('people','journals.customer_id=people.id','left')->join('stock','stock.journal_id=journals.id','left')->where('journals.date >=',$datfrom)->where('journals.date <=',$datto)->group_by('journals.customer_id')->group_by('journals.date')->get('journals')->result_array();
        //$salesData = $this->db->select('people.businessName , journals.date  ,journals.type ,journals.customer_id as customerID, sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - journals.totalDiscount as totalSales , "sales" as type')->join('people','journals.customer_id=people.id','left')->join('stock','stock.journal_id=journals.id','left')->where('journals.date >=',$datFrom)->where('journals.date <=',$datTo)->where('journals.type = ',1)->group_by('journals.customer_id')->group_by('journals.date')->get('journals')->result_array();
       // $salesData = $this->db->select('people.businessName ,journals.labourCost as labourCost,journals.date  ,journals.type ,journals.customer_id as customerID, sum(stock.quantity * stock.unit_price) + sum(journals.labourCost) - sum(stock.discount) - journals.totalDiscount as totalSales , "sales" as type')->join('people','journals.customer_id=people.id','left')->join('stock','stock.journal_id=journals.id','left')->where('journals.date >=',$datFrom)->where('journals.date <=',$datTo)->where('journals.type = ',1)->group_by('journals.customer_id')->group_by('journals.date')->get('journals')->result_array();
        //return $salesData;
        
        //$salesData = $this->db->select('stock.date as date ,sum(stock.quantity * stock.unit_price) +journals.labourCost -stock.discount - journals.totalDiscount as totalSales , stock.journal_id ,people.businessName,  journals.customer_id as customerID, journals.labourCost as labourCost')->join('journals','journals.id = stock.journal_id')->join('people','people.id=journals.customer_id')->where('stock.date >=',$datFrom)->where('stock.date <=',$datTo)->where('stock.type = ',1)->group_by('stock.journal_id')->get('stock')->result_array();
        //return $salesData;
        
        $salesData = $this->db->select('people.name as cusName ,people.businessName as businessName ,people.code as cusID, journals.id as journalId , journals.totalDiscount as totalDiscount , journals.description as salesDescription')->join('people','people.id=customer_id','left')->where('journals.type', 1)->where('date >=',$datFrom)->where('date <=',$datTo)->get('journals')->result_array();
      
        $total = [];
        foreach( $salesData as $salesData ) {
            $data = $this->db->select('journals.date as journalDate, journals.labourCost as labourCost,stock.date,stock.journal_id as journalID,sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - '.$salesData['totalDiscount'].' as totalSales ')->join('journals','journals.id=journal_id','left')->where('journal_id', $salesData['journalId'])->get('stock')->result_array();
            $data[0]['name'] = $salesData['cusName'];
            $data[0]['businessName'] = $salesData['businessName'];
            $data[0]['code'] = $salesData['cusID'];
            $data[0]['journalID'] = $salesData['journalId'];

            $total[] = $data;
            
        }
        return $total;
 
    }
    function getsalesItemData($datFrom , $datTo){
        //$dat = date('Y-m-d');
        // $salesItemData = $this->db->select('stock.date as date , items.name as itemName , sum(stock.quantity) as quantity')->join('items','items.id=stock.item_name','left')->where('stock.type =',1)->where('stock.date =',$dat)->group_by('stock.item_name')->group_by('stock.date')->get('stock')->result_array();
        $salesItemData = $this->db->select('items.name as itemName , people.businessName , journals.date  ,journals.type ,journals.customer_id as customerID,sum(stock.quantity) as quantity, sum(stock.quantity * stock.unit_price) + sum(journals.labourCost) - sum(stock.discount) - sum(journals.totalDiscount) as totalSales')->join('people','journals.customer_id=people.id','left')->join('stock','stock.journal_id=journals.id','left')->join('items','items.id=stock.item_name','left')->where('journals.date >=',$datFrom)->where('journals.date <=',$datTo)->where('journals.type = ',1)->group_by('stock.item_name')->group_by('journals.date')->get('journals')->result_array();
        return $salesItemData;
 
    }
    function getpurchasereportData($datFrom , $datTo){
        //  $purchaseData = $this->db->select('stock.date as date ,items.name as itemName,sum(stock.quantity) as quantity , people.businessName  , sum(stock.quantity * stock.unit_price) as totalPurchase ')->join('people','stock.warehouse=people.id','left')->join('items','items.id=stock.item_name','left')->where('stock.date >=',$datFrom)->where('stock.date <=',$datTo)->where('stock.type = ',0)->group_by('stock.warehouse')->group_by('stock.item_name')->group_by('stock.date')->get('stock')->result_array();
        //  return $purchaseData;
        // $salesData = $this->db->select('people.name as cusName ,people.businessName as businessName ,people.code as cusID, journals.id as journalId , journals.totalDiscount as totalDiscount , journals.description as salesDescription')->join('people','people.id=customer_id','left')->where('journals.type', 0)->where('date >=',$datFrom)->where('date <=',$datTo)->get('journals')->result_array();
        // $total = [];
        // foreach( $salesData as $salesData ) {
        //     $data = $this->db->select('journals.date as journalDate,people.businessName as businessName ,items.name as itemName,stock.journal_id as journalID,sum(stock.quantity) as quantity , sum(stock.quantity * stock.unit_price) as totalPurchase ')
        //     ->join('journals','journals.id=journal_id','left')
        //     ->join('items','items.id=stock.item_name','left')
        //     ->where('journal_id', $salesData['journalId'])->get('stock')->result_array();
        //     $total[] = $data;
            
        // }
        // return $total;
         $purchaseData = $this->db->select('journals.date as journalDate ,items.name as itemName,sum(stock.quantity) as quantity , people.businessName  , sum(stock.quantity * stock.unit_price) as totalPurchase')->join('stock','stock.journal_id=journals.id','left')->join('people','stock.warehouse=people.id','left')->join('items','items.id=stock.item_name','left')->where('journals.date >=',$datFrom)->where('journals.date <=',$datTo)->where('stock.type = ',0)->group_by('stock.warehouse')->group_by('stock.item_name')->get('journals')->result_array();
         return $purchaseData;

    }
    function getpurchaseItemData($datFrom , $datTo){
        // $purchaseItemData = $this->db->select('stock.date as date , items.name as itemName , sum(stock.quantity) as quantity, sum(stock.quantity * stock.unit_price) as totalPurchase')->join('people','stock.warehouse=people.id','left')->join('items','items.id=stock.item_name','left')->where('stock.date >=',$datFrom)->where('stock.date <=',$datTo)->where('stock.type = ',0)->group_by('stock.item_name')->group_by('stock.date')->get('stock')->result_array();
        // return $purchaseItemData;
        // $purchaseItemData = $this->db->select('items.name as itemName, sum(stock.quantity) as quantity, stock.date')->join('people','stock.warehouse=people.id','left')->join('items','items.id=stock.item_name','left')->where('stock.date >=',$datFrom)->where('stock.date <=',$datTo)->where('stock.type = ',0)->group_by('stock.item_name')->get('stock')->result_array();
        // return $purchaseItemData;
        
        $purchaseItemData = $this->db->select('journals.date as date ,items.name as itemName,sum(stock.quantity) as quantity')->join('stock','stock.journal_id=journals.id','left')->join('items','items.id=stock.item_name','left')->where('journals.date >=',$datFrom)->where('journals.date <=',$datTo)->where('stock.type = ',0)->group_by('stock.item_name')->get('journals')->result_array();
        return $purchaseItemData;
        
    }
    function getPreviousStock(){
        $dat = date('Y-m-d');
        $total = [];
        $data = [];
        $type = ['-3','5','7']; // import refund local purchase 
         
        $items = $this->db->select('items.name as itemName , items.id as itemId')->where('items.type = ',1)->get('items')->result_array();
        //$items = $this->db->select('sum(stock.quantity) as importQuantity,stock.item_name as itemId,items.name as itemName')->join('items','items.id=stock.item_name','left')->where('stock.type = ',2)->where('stock.date = ',$dat)->group_by('stock.item_name')->get('stock')->result_array();
        foreach($items as $item){
            // print_r($item);
            // die();
           
            $data1 = $this->db->select('sum(quantity) as previousQuantity')->where_in('stock.type',$type)->where('stock.warehouse',-3)->where('stock.date < ',$dat)->where('stock.item_name = ',$item['itemId'])->get('stock');
            //$query = $this->db->select('sum(quantity) as total')->where('item_name',$item['itemId'])->where('type',1)->where('stock.date < ',$dat)->get('stock');
            $query = $this->db->select('sum(stock.quantity) as oldSold')->join('stock','stock.journal_id=journals.id','left')->where('journals.date < ',$dat)->where('stock.item_name = ',$item['itemId'])->where('journals.type = ',1)->get('journals');
      
            $data2 = $this->db->select('sum(quantity) as importQuantity')->where('stock.item_name',$item['itemId'])->where('stock.type',2)->where('stock.date =',$dat)->get('stock');
            
            $localPurchase = $this->db->select('sum(quantity) as localQuantity')->where('stock.item_name',$item['itemId'])->where('stock.type',7)->where('stock.date =',$dat)->get('stock');
            

            
            //$data3 = $this->db->select('sum(quantity) as soldQuantity')->where('stock.item_name',$item['itemId'])->where('stock.type',1)->where('stock.date = ',$dat)->get('stock');
            
            $data3 = $this->db->select('sum(stock.quantity) as soldQuantity')->join('stock','stock.journal_id=journals.id','left')->where('journals.date =',$dat)->where('stock.item_name = ',$item['itemId'])->where('journals.type = ',1)->get('journals');
            $refund = $this->db->select('sum(quantity) as refundQuantity')->where_in('stock.type',5)->where('stock.warehouse',-3)->where('stock.date = ',$dat)->where('stock.item_name = ',$item['itemId'])->get('stock');
            
            
            
            $data['name'] = $item['itemName']; 
            $data['previous'] = $data1->row()->previousQuantity - $query->row()->oldSold;
            $data['import'] = $data2->row()->importQuantity+$localPurchase->row()->localQuantity+$refund->row()->refundQuantity;
            //$data['import'] = $item['importQuantity'];;
            $data['sold'] = $data3->row()->soldQuantity;
            $total[] = $data;
        }
        return $total;
    }
    function getUnloadCost($itemId){
        $query = $this->db->where('id',$itemId)->get('items')->row();
        return $query->labourCost;
    }
    function updateStockDate($date , $id){
        $this->db->set('date', $date);
        $this->db->where('journal_id', $id);
        $this->db->update('stock');

    }
    function getBankList(){
        return $this->db->get('banks')->result_array();
    }
    function getBankReportDetails($bankId  , $datFrom , $datTo){
        $bankDetails = $this->db->select('finance.peopleID as peopleID,finance.date as date,people.businessName as businessName,banks.name as bankName,finance.type as type, finance.paymentType as paymentType,finance.bankAccount as bankAccount,finance.amount as amount,finance.description as description')->join('people','finance.peopleID=people.id','left')->join('banks','finance.bankAccount=banks.id','left')->where('finance.bankAccount =',$bankId)->where('finance.date >=',$datFrom)->where('finance.date <=',$datTo)->get('finance')->result_array();
        return $bankDetails;
    }
    function getPreviousBalance($bankId,$datFrom){
        $previousBalance = $this->db->where('finance.bankAccount =',$bankId)->where('finance.date < ',$datFrom)->get('finance')->result_array();
        return $previousBalance;
    }
    function getBankName($bankId){
        $bankName = $this->db->select('name as name')->where('id',$bankId)->get('banks');
        return $bankName->row()->name;
    }
    function getBalanceDetails($type){
        $query =  $this->db->where('type',$type)->get('people')->result_array();
        foreach($query as $query){
            $data['name'] = $query['businessName'];
            $data['id'] = $query['code'];
            $data['thana'] = $query['thana'];
            if( $type == 0){
                $data['balance'] = $this->getCustomerBalance($query['id']);
                
            }
            if( $type == 1){
                $data['balance'] = $this->getSupplierBalance($query['id']);
                
            }
            $result[] = $data;
        }
        return $result;
    }
    function getPreviousStatement( $customerID , $datfrom ){
        $final = [];
        $type = ['0','1'];

        $openingBalance = $this->db->select('people.code as code,people.name as name,people.businessName as businessName,people.address as address,people.businessAddress as businessAddress,people.area as area,people.thana as thana,people.district as district,people.phone as phone,people.email as email,people.openingBalance as openingBalance, "openingBalance" as type')->where('id',$customerID)->get('people')->result_array();
        $final[] = $openingBalance;

        
        $statements = $this->db->select('finance.date as date,finance.amount as depositAmount,finance.type as paymentType,finance.description as depositDescription ,"deposit" as type')->where('peopleID', $customerID)->where('date < ',$datfrom)->where_in('paymentType',$type)->get('finance')->result_array();
        $final[] = $statements;
        
        $cashBack = $this->db->select('finance.date as date,finance.amount as backAmount,finance.description as backDescription ,"cashBack" as type')->where('peopleID', $customerID)->where('date < ',$datfrom)->where('paymentType',2)->get('finance')->result_array();
        $final[] = $cashBack;
        
       
        $refund = $this->db->select('stock.date as date,stock.unit_price as unit_price,stock.quantity as quantity,stock.reason as refundDescription,"refund" as type')->where('customer_id', $customerID)->where('date < ',$datfrom)->where('stock.type =',4)->get('stock')->result_array();
        $final[] = $refund;
        
        $purchaseData = $this->db->select('stock.date as date,stock.unit_price as unit_price,stock.quantity as quantity,journals.description as purchaseDescription,"purchase" as type')->join('people','people.id=warehouse','left')->join('items','items.id=item_name','left')->join('journals','journals.id=journal_id','left')->where('warehouse', $customerID)->where('stock.date < ',$datfrom)->where('stock.type =',0)->get('stock')->result_array();
        $final[]  = $purchaseData; 
        
        $salesData = $this->db->select('people.name as cusName ,people.businessName as businessName ,people.code as cusID, journals.id as journalId , journals.totalDiscount as totalDiscount , journals.description as salesDescription')->join('people','people.id=customer_id','left')->where('customer_id', $customerID)->where('date < ',$datfrom)->where('journals.type =',1)->get('journals')->result_array();
        $totalSales = [];
        foreach( $salesData as $salesData ) {
            $sales = $this->db->select('journals.date as date,stock.journal_id as journalID,sum(stock.quantity * stock.unit_price) + journals.labourCost - sum(stock.discount) - '.$salesData['totalDiscount'].' as total, "sales" as type')->join('journals','journals.id=journal_id','left')->where('journal_id', $salesData['journalId'])->get('stock')->result_array();
            $final[] = $sales; 
        }
        foreach( $final as $data)
        foreach( $data as $data){
            $preresult[] =$data;
         
        }
        return $preresult;
    }
    function getGrandTotal($journalId){
        $stockData = $this->db->where('journal_id',$journalId)->get('stock')->result_array();
        $price =0;
        foreach($stockData as $stockData){
            $price += $stockData['quantity']*$stockData['unit_price']-$stockData['discount'];
        }
        return $price; 

    }
    function deleteInvoice($invoice){
        $this->db->where('journal_id', $invoice);
        return $this->db->delete('stock');
        // return true; 
    }
}
