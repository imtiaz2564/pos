<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
<h1>Details</h1>        
<table class="table table-report">
    <thead>
    <tr>
        <td><b>Date</b></td>
        <td><b>Supplier Name</b></td>
        <td><b>Supplier ID</b></td>
        <td><b>Item Name</b></td>
        <td><b>Quantity</b></td>
        <td><b>Amonuts</b></td>
    </tr>
    </thead>
    <?  foreach( $history as $history) { ?>
    <tr>
        <td><?=$history["date"]?></td>
        <td><?=$history["businessName"]?></td>
        <td><?=$history["supplierID"]?></td>
        <td><?=$history["itemName"]?></td>
        <td><?=$history["quantity"]?></td>
        <td><?=$history["quantity"]*$history["unit_price"]?></td>
    </tr>
    <? } ?>
    </table>    
</div>
