<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1>Report By Item</h1>
    <table class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>Date</td> 
               <td>Item Name</td>
               <td>Quantity</td>
               <td>Amount</td>
            </tr>
        </thead>
        <? $j = 1; $total = 0; foreach( $salesItemData as $salesItemData) { $total += $salesItemData["totalSales"]; ?>
        <tr>
            <td><?=$j++?></td>
            <td><?=$salesItemData["date"]?></td>
            <td><?=$salesItemData["itemName"]?></td>
            <td><?=$salesItemData["quantity"]?></td>
            <td><?=$salesItemData["totalSales"]?></td>
        </tr>
        <? } ?>
        <tr>
            <td><b>Total</b></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$total?></td>                       
        </tr>   
    </table>    
</div>
