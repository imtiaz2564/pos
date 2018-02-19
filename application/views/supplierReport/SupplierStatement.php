<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
<h1>Details</h1>        
<table class="table table-report">
    <thead>
    <tr>
        <td><b>Date</b></td>
        <td><b>Payable Amount</b></td>
        <td><b>Purchase Detail</b></td>
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
        <td>Opening Balance</td>
        <td><?=$openingBalance?></td>
    </tr>
    <? $paid = $openingBalance; foreach( $history as $history) {
          $payable  = $history["quantity"]*$history["unit_price"];
        $paid = $paid - $payable;
        ?>
        <tr>
        <td><?=$history["date"]?></td>
        <td><?=$payable?></td>
        <td><?=$history["purchaseDescription"]?></td>
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
        <td><?=$state["amount"]?></td>
        <? $paid = $paid+$state["amount"] ?>
        <td><?=$state["description"]?></td>
        <td><?=$paid?></td>
    </tr>
    <? } ?>
</table>    
</div>
