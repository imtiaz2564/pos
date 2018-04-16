<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
<h1>Details</h1>        
<table id="printTable" class="table table-report">
    <thead>
        <tr>
            <td><b>Date</b></td>
            <td><b>Customer Name</b></td>
            <td><b>Customer ID</b></td>
            <td><b>Invoice No</b></td>
            <td><b>Amonuts</b></td>
        </tr>
    </thead>
    <? foreach( $salesData as $sales) 
    foreach( $sales as $sales) { ?>
    <tr>
        <td><?=$sales["journalDate"]?></td>
        <td><?=$sales["businessName"]?></td>
        <td><?=$sales["code"]?></td>
        <td><?=$sales["journalID"]?></td>
        <td><?=$sales["total"]?></td>
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
        newWin= window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
</script>
