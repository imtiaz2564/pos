<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1>Daily Purchase Report By Supplier</h1>        
    <table class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>Date</td> 
               <td>Business Name</td>
               <td>Item Name</td>
               <td>Quantity</td>
               <td>Amount</td>
            </tr>
        </thead>
        <? $i = 1; $total=0; $totalQuantity=0; foreach( $purchaseData as $purchaseData) { $total +=$purchaseData["totalPurchase"];
            $totalQuantity += $purchaseData["quantity"];
            ?>
        <tr>
            <td><?=$i++?></td>
            <td><?=$purchaseData["date"]?></td>
            <td><?=$purchaseData["businessName"]?></td>
            <td><?=$purchaseData["itemName"]?></td>
            <td><?=$purchaseData["quantity"]?></td>
            <td><?=$purchaseData["totalPurchase"]?></td>
         </tr>   
        <? } ?>
        <tr>
            <td><b>Total</b></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$totalQuantity?></td>
            <td><?=$total?></td>
        </tr>   
    </table>  
</div>
