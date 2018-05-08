<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1>Daily Sales Report By Item</h1>
    <table id="printTable" class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>Date</td> 
               <td>Item Name</td>
               <td>Quantity</td>
               <!-- <td>Amount</td> -->
            </tr>
        </thead>
        <? $j = 1; $total = 0; $totalQuantity = 0; foreach( $salesItemData as $salesItemData) { $totalQuantity += $salesItemData["quantity"];//$total += $salesItemData["totalSales"]; ?>
        <tr>
            <td><?=$j++?></td>
            <td><?=$salesItemData["date"]?></td>
            <td><?=$salesItemData["itemName"]?></td>
            <td><?=$salesItemData["quantity"]?></td>
            <td><?//=$salesItemData["totalSales"]?></td>
        </tr>
        <? } ?>
        <tr>
            <td><b>Total</b></td>
            <td></td>
            <td></td>
            <td><?=$totalQuantity?></td>
            <td><?//=$total?></td>                       
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
            'border: 1px solid black;' +
        '}' +
        'table {' +
            'border-collapse: collapse;' +
            'width: 100%;' +
        '}'+
        '</style>';
        htmlToPrint += divToPrint.outerHTML;
        newWin= window.open("");
        newWin.document.write(htmlToPrint);
        newWin.print();
        newWin.close();
    }
</script>