<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1>Daily Purchase Report By Item</h1>        
    <table id = "printTable" class="table table-report">
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
<div class="row">
    <div class="col-xs-12 text-right">
    <button class="btn btn-primary" onclick="printDiv()">Print</button>
    </div>
</div>
<script>
    function printDiv() {
        var divToPrint=document.getElementById("printTable");
        var htmlToPrint = '' +
            '<style type="text/css">' +
            'table th, table td {' +
            'border:1px solid #000;' +
            'padding;0.5em;' +
            '}' +
            '</style>';
        htmlToPrint += divToPrint.outerHTML;
        newWin= window.open("");
        newWin.document.write(htmlToPrint);
        newWin.print();
        newWin.close();
    }
</script>