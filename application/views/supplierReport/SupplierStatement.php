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
    <? $paid = 0; foreach( $history as $history) {
          $payable  = $history["quantity"]*$history["unit_price"];
        $paid = $paid - $payable;
        ?>
        <tr>
        <td><?=$history["date"]?></td>
        <td><?=$payable?></td>
        <td></td>
        <td></td>
        <td><?=$paid?></td>
    </tr>

    <? } ?>
   <?  foreach($statement as $state) {   ?>
 
    <tr>
        <td><?=$state["date"]?></td>
        <td></td>
        <td><?=$state["amount"]?></td>
        <? $paid = $paid+$state["amount"] ?>
        <td></td>
        <td><?=$paid?></td>
    </tr>
    <? } ?>
</table>    
</div>
