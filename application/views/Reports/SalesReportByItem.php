<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1>Report By Item</h1>
    <table class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>Date</td> 
               <td>Item Name</td>
               <td>Quantity</td>
            </tr>
        </thead>
        <? $j = 1;  foreach( $salesItemData as $salesItemData) { ?>
        <tr>
            <td><?=$j++?></td>
            <td><?=$salesItemData["date"]?></td>
            <td><?=$salesItemData["itemName"]?></td>
            <td><?=$salesItemData["quantity"]?></td>
        <? } ?>   
    </table>    
</div>
