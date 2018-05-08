<div class="modal-body" style="max-height:450px; overflow-y:scroll;">
    <h1>Live Supplier Stock</h1>
    <table id="printTable" class = "table table-report">
        <thead>
        <tr>
            <td><b>Supplier</b></td>
            <td><b>Item Name</b></td>
            <td><b>Available Quantity</b></td>
        </tr>
        </thead>    
        <?php foreach($supplierInfo as $info) { ?>
    
        <tr>
            <td><?=$info['businessName']?></td>
            <td><?=$info['name']?></td>
            <td><?=$info['avail']?></td>
        </tr>
        <? } ?>
    
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
