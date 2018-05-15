<? 
$oldpaid = 0; foreach($oldbalance as $prebalance) {
        if ( $prebalance['type'] == "sales" ) {
            $oldpayable  = $prebalance["total"]; 
            $oldpaid = $oldpaid - $oldpayable; 
        } if ( $prebalance['type'] == "purchase" ) {   
            $oldsupplierpayable  = $prebalance["quantity"]*$prebalance["unit_price"];   
            $oldpaid = $oldpaid + $oldsupplierpayable; 
        } if ( $prebalance['type'] == "deposit" && $prebalance['paymentType'] == "1") { 
            $oldpaid = $oldpaid-$oldprebalance["depositAmount"];  
        } if ( $prebalance['type'] == "deposit" && $prebalance['paymentType'] == "0") { 
            $oldpaid = $oldpaid+$prebalance["depositAmount"];  
        } if ( $prebalance['type'] == "refund" ) { 
            $oldtotalRefund = $prebalance["quantity"]*$prebalance["unit_price"]; 
            $oldpaid = $oldpaid+$oldtotalRefund;
        } if ( $prebalance['type'] == "cashBack" ) {   
            $oldcash  = $prebalance["backAmount"]; 
            $oldpaid = $oldpaid - $oldcash; 
        } 
    } 
?>
<div class="modal-body" style="max-height:450px; overflow-y:scroll;">
    <div id = "printTitle" class="page-header">
        <div class='page-heading text-center'>
            <h1>Amin Brothers</h1>
            <h3>Mohajonpotti road,khalighat Sylhet</h3>
            <u><h3>Party statement of account</h3></u>
        </div>
    </div>
    <table id = "printInfo" cellspacing="5">
        <thead>
            <?  $openingBalance = 0;foreach($oldbalance as $details) { if( $details['type'] == "openingBalance" ) { $openingBalance = $details['openingBalance'];?>
            <tr>
                <th>Party ID: </th>
                <td><?=$details['code']?></td>
                <th>Party Name: </th>
                <td><?=$details['name']?></td>
           
            </tr>
            <tr>
                <th>Business Name: </th>
                <td><?=$details['businessName']?></td>
                <th>Business Address: </th>
                <td><?=$details['businessAddress']?></td>
            </tr>
            <tr>
                <th>Area: </th>
                <td><?=$details['area']?></td>
                <th>Thana: </th>
                <? $thana = [''=>'','0'=>'Kanaighat','1'=>'Companiganj','2'=>'Gowainghat','3'=>'Golabganj','4'=>'Zakiganj','5'=>'Jaintiapur','6'=>'Dakshin Surma','7'=>'Fenchuganj','8'=>'Balaganj','9'=>'Beanibazar','10'=>'Bishwanath','11'=>'Sylhet Sadar']; ?>
                <td><?=$thana[$details['thana']]?></td>
           
            </tr>
            <tr>
                <th>District: </th>
                <td><?=$details['district']?></td>
                <th>Phone: </th>
                <td><?=$details['phone']?></td>
            </tr>
            <tr>
                <th>Email: </th>
                <td><?=$details['email']?></td>
                <th>Period: </th>
                <td><?=$this->uri->segment(4)." "."to"." ".$this->uri->segment(4)?></td>
            </tr>
            <tr>
                <th>Date: </th>
                <td><?=Date('Y-m-d')?></td>
            </tr>
            
        <? } }?>
        </thead>
    </table>
<table id = "printTable" class="table table-report">
    <thead>
        <tr>
            <th>Date</th>
            <th>Description</th>
            <!-- <th>Account Description</th> -->
            <th>Debit</th>
            <th>Credit</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tr>
            <td><?=$datFrom?></td>
            <td>Balance Brought Forward</td>
            <td></td>
            <td></td>
            <!-- <td></td> -->
            <? $oldpaid += $openingBalance; ?>
            <td><?=$oldpaid?></td>
        </tr>
    <? $paid = $oldpaid; foreach( $result as $result) { ?>
  
    <tr>
        <td><?=$result["date"]?></td>
        <? //if ( $result['type'] == "openingBalance" ) { $paid = $result['openingBalance']; ?>
        <!--<td></td>
        <td>Opening Balance</td>
        <td></td>
        <td><?//=$result['openingBalance']?></td> -->
        <? //}
          if ( $result['type'] == "sales" ) { $payable  = $result["total"]; $paid = $paid - $payable; ?>
            <td>Invoice #:<?=$result["journalID"]?></td>
            <!-- <td></td> -->
            <td><?=$payable?></td>
            <td></td>
        <? } if ( $result['type'] == "purchase" ) {   $supplierpayable  = $result["quantity"]*$result["unit_price"];   
            $paid = $paid + $supplierpayable; ?>
            <!-- <td><?//=$result["purchaseDescription"]?></td> -->
            <td><?=$result['itemName']?> Quantity: <?=$result['quantity']?> Price: <?=$result['unit_price']?></td>
            <td></td>
            <td><?=$supplierpayable?></td>
        <? } if ( $result['type'] == "deposit" && $result['paymentType'] == "1") { $paid = $paid-$result["depositAmount"];  ?>
            <td><?=$result["depositDescription"]?></td>
            <!-- <td><?//=$result["depositDescription"]?></td> -->
            <td><?=$result["depositAmount"]?></td>
            <td></td>
        <? } if ( $result['type'] == "deposit" && $result['paymentType'] == "0") { $paid = $paid+$result["depositAmount"];  ?>
            <td><?=$result["depositDescription"]?></td>
            <!-- <td><?//=$result["depositDescription"]?></td> -->
            <td></td>
            <td><?=$result["depositAmount"]?></td>
        <? } if ( $result['type'] == "refund" ) { $totalRefund = $result["quantity"]*$result["unit_price"]; $paid = $paid+$totalRefund;?>
            <!-- <td><?//=$result["refundDescription"]?></td> -->
            <td>Refund <?=$result['itemName']?> Quantity: <?=$result['quantity']?> Price: <?=$result['unit_price']?></td>
            <td></td>
            <td><?=$totalRefund?></td>
        <? } if ( $result['type'] == "cashBack" ) {   $cash  = $result["backAmount"]; $paid = $paid - $cash; ?>
            <td><?=$result["backDescription"]?></td> 
            <!-- <td><?//=$result["backDescription"]?></td> -->
            <td><?=$cash?></td>
            <td></td>
        <? } ?>
        <td><?=$paid?></td>
    </tr>
    <? } ?>
    <tr>
        <td><?=$datFrom?></td>
        <td>Balance Carried Forward</td>
        <td></td>
        <td></td>
        <!-- <td></td> -->
        <td><?=$paid?></td>
    </tr>
    </table>    
<!-- </div> -->
<div class="row">
    <div class="col-xs-12 text-right">
    <button class="btn btn-primary" onclick="printDiv()">Print</button>
    </div>
</div>
</div>
<script>
$('#buttonGroup').hide();
    function printDiv() {
        var divToPrint=document.getElementById("printTable");
        var divToPrintInfo=document.getElementById("printInfo");
        var divToPrintTitle=document.getElementById("printTitle");
        newWin = window.open("");
        newWin.document.write(divToPrintTitle.outerHTML);
        newWin.document.write(divToPrintInfo.outerHTML);
       
        var htmlToPrint = '' +
        '<style type="text/css">' +
            'table#printTable th, table#printTable td {' +
            'border: 1px solid black;' +
        '}' +
        'table#printTable {' +
            'border-collapse: collapse;' +
            'width: 100%;' +
        '}'+
        '</style>';
        htmlToPrint += divToPrint.outerHTML;
        newWin.document.write(htmlToPrint);
        newWin.print();
        newWin.close();
    }
</script>
