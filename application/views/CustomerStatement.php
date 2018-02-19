<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
<h1>Details</h1>        
<table class="table table-report">
    <thead>
    <tr>
        <td><b>Date</b></td>
        <td><b>Customer Name</b></td>
        <td><b>Customer ID</b></td>
        <td><b>Payable Amount</b></td>
        <td><b>Sales Detail</b></td>
        <td><b>Paid Amount</b></td>
        <td><b>Account Detail</b></td>
        <td><b>Balance</b></td>

    </tr>
    </thead>
    <tr>
        <td></td>
        <td></td>
        <td></td>        
        <td></td>
        <td></td>
        <td></td>
        <td>Opening Balance</td>
        <td><?=$openingBalance?></td>
    </tr>
    <? $paid = $openingBalance; foreach( $salesData as $sales) 
    foreach( $sales as $sales)
    { //$payable  = $sales["quantity"]*$sales["unit_price"];
        $payable  = $sales["total"];
        $paid = $paid - $payable;
        ?>
        <tr>
        <td><?=$sales["date"]?></td>
        <td><?=$sales["name"]?></td>
        <td><?=$sales["code"]?></td>
        <td><?=$payable?></td>
        <td><?=$sales["salesDescription"]?></td>
        <td></td>
        <td></td>
        <td><?=$paid?></td>
    </tr>

    <? } ?>
   <?  foreach($statement as $state) {   ?>
 
    <tr>
        <td><?=$state["date"]?></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><?=$state["amount"]?></td>
        <? $paid = $paid+$state["amount"] ?>
        <td><?=$state["description"]?></td>
        <td><?=$paid?></td>
    </tr>
    <? } ?>
</table>    
</div>
