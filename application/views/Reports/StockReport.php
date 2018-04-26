<div class="modal-body" style="max-height:450px; overflow-y:scroll;">
    <h1>Daily AB Stock</h1>
    <h3>Date: <?=Date('Y-m-d')?></h3>
    <div id = "printTable">
    <table class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>Item Name</td> 
               <td>OLD Stock</td>
               <td>Today IN</td>
               <td>Today OUT</td>
               <td>Current Stock</td>
            </tr>
        </thead>
        <? $i = 0; foreach( $previousstock as $stock ) { $i++; ?>
        <tr>
            <td><?=$i?></td>
            <td><?=$stock['name']?></td>
            <td><?=$stock['previous']?></td>
            <td><?=$stock['import']?></td>
            <td><?=$stock['sold']?></td>
            <td><?=$stock['previous']+$stock['import']-$stock['sold']?></td>
        </tr>
        <? } ?> 
    </table>
    </div>    
</div>
<div class="row">
    <div class="col-xs-12 text-right">
    <button class="btn btn-primary" onclick="printDiv()">Print</button>
    </div>
</div>
<script>
    function printDiv() {
        var divToPrint=document.getElementById("printTable");
        newWin= window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
</script>
