<div class="modal-body" style="max-height:450px; overflow-y:scroll;">
    <div class="page-header">
        <div class='page-heading text-center'>
            <h1>Amin Brothers</h1>
            <h3>Mohajonpotti road,khalighat Sylhet</h3>
            <u><h3>Statement of account</h3></u>
        </div>
    </div>
    <table>
        <thead>
            <? foreach($result as $details) { if( $details['type'] == "openingBalance" ) {?>
            <tr>
                <th>Customer Code: </th>
                <td><?=$details['code']?></td>
            </tr>
            <tr>
                <th>Customer Name: </th>
                <td><?=$details['name']?></td>
            </tr>
            <tr>
                <th>Business Name: </th>
                <td><?=$details['businessName']?></td>
            </tr>
            <tr>
                <th>Home Address: </th>
                <td><?=$details['address']?></td>
            </tr>
            <tr>
                <th>Business Address: </th>
                <td><?=$details['businessAddress']?></td>
            </tr>
            <tr>
                <th>Area: </th>
                <td><?=$details['area']?></td>
            </tr>
            <tr>
                <th>Thana: </th>
                <td><?=$details['thana']?></td>
            </tr>
            <tr>
                <th>District: </th>
                <td><?=$details['district']?></td>
            </tr>
            <tr>
                <th>Phone: </th>
                <td><?=$details['phone']?></td>
            </tr>
            <tr>
                <th>Email: </th>
                <td><?=$details['email']?></td>
            </tr>
            <tr>
                <th>Period: </th>
                <td><?=$this->uri->segment(4)." "."to"." ".$this->uri->segment(4)?></td>
            </tr>
            
        <? } }?>
        </thead>
    </table>
<h1>Details</h1>
<table class="table table-report">
    <thead>
        <tr>
            <td><b>Date</b></td>
            <td><b>Sales/Purchase Description</b></td>
            <td><b>Account Description</b></td>
            <td><b>Payable Amounts</b></td>
            <td><b>Paid Amounts</b></td>
            <td><b>Balance</b></td>
        </tr>
    </thead>
    <? foreach( $result as $result) { ?>
  
    <tr>
        <td><?=$result["date"]?></td>
        <? if ( $result['type'] == "openingBalance" ) { $paid = $result['openingBalance']; ?>
        <td></td>
        <td>Opening Balance</td>
        <td></td>
        <td><?//=$result['openingBalance']?></td>
        <? }  if ( $result['type'] == "sales" ) { $payable  = $result["total"]; $paid = $paid - $payable; ?>
            <td><?=$result["journalID"]?></td>
            <td></td>
            <td><?=$payable?></td>
            <td></td>
        <? } if ( $result['type'] == "purchase" ) {   $supplierpayable  = $result["quantity"]*$result["unit_price"];   
            $paid = $paid + $supplierpayable; ?>
            
            <td><?=$result["purchaseDescription"]?></td>
            <td></td>
            <td></td>
            <td><?=$supplierpayable?></td>
        <? } if ( $result['type'] == "deposit" ) { $paid = $paid+$result["depositAmount"];  ?>
            <td></td>
            <td><?=$result["depositDescription"]?></td>
            <td></td>
            <td><?=$result["depositAmount"]?></td>
        <? } if ( $result['type'] == "refund" ) { $totalRefund = $result["quantity"]*$result["unit_price"]; $paid = $paid+$totalRefund ?>
            <td></td>
            <td><?=$result["refundDescription"]?></td>
            <td></td>
            <td><?=$totalRefund?></td>
        <? } if ( $result['type'] == "cashBack" ) {   $cash  = $result["backAmount"]; $paid = $paid - $cash; ?>
            <td></td> 
            <td><?=$result["backDescription"]?></td>
            <td><?=$cash?></td>
            <td></td>
        <? } ?>
        <!-- <td></td> -->
        <td><?=$paid?></td>
    </tr>
    <? } ?>
    </table>    
</div>
