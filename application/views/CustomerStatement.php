<?
// foreach( $details as $details){
//     print_r($details);
// }

?>
<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
<h1>Details</h1>        
<table class="table table-report">
    <thead>
    <tr>
        <td><b>Date</b></td>
        <td><b>Payable Amount</b></td>
        <td><b>Paid Amount</b></td>
        <td><b>Details</b></td>
        <td><b>Balance</b></td>

    </tr>
    </thead>
    <? foreach( $salesData as $sales) 
    foreach( $sales as $sales)
    { ?>
        <tr>
        <td><?=$sales["date"]?></td>
        <td><?=$sales["quantity"]*$sales["unit_price"]?></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <? } ?>
   <? $paid = 0; foreach($statement as $state) {  // print_r($state); die(); ?>
 
    <tr>
        <td><?=$state["date"]?></td>
        <td></td>
        <td><?=$state["amount"]?></td>
        <? $paid += $state["amount"]; ?>
        <td></td>
        <td><?=$paid?></td>
    </tr>
    <? } ?>
</table>    
</div>
