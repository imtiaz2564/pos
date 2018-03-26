<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1>Daily Purchase Report By Item</h1>        
    <table class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>Date</td> 
               <td>Item Name</td>
               <td>Quantity</td>
               <!-- <td>Amount</td> -->
            </tr>
        </thead>
        <? $i = 1; $totalQuantity=0; $total=0; foreach( $purchaseItemData as $purchaseItemData) { $totalQuantity += $purchaseItemData["quantity"];//$total +=$purchaseItemData["totalPurchase"];?>
        <tr>
            <td><?=$i++?></td>
            <td><?=$purchaseItemData["date"]?></td>
            <td><?=$purchaseItemData["itemName"]?></td>
            <td><?=$purchaseItemData["quantity"]?></td>
            <!-- <td><?//=$purchaseItemData["totalPurchase"]?></td> -->
         </tr>   
        <? } ?>
        <tr>
            <td><b>Total</b></td>
            <td></td>
            <td></td>
            <td><?=$totalQuantity?></td>
            <!-- <td><?//=$total?></td> -->
        </tr>   
    </table>  
</div>
