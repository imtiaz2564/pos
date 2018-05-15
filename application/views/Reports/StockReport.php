<div class="modal-body" style="max-height:450px; overflow-y:scroll;">
    <h1 id = "printHeader">Daily AB Stock</h1>
    <h3 id = "printDate">Date: <?=Date('Y-m-d')?></h3>
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
        var divToPrintHeader=document.getElementById("printHeader");
        var divToPrintDate=document.getElementById("printDate");
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
        newWin.document.write(divToPrintHeader.outerHTML);
        newWin.document.write(divToPrintDate.outerHTML);
        newWin.document.write(htmlToPrint);
        newWin.print();
        newWin.close();
    }
</script>
