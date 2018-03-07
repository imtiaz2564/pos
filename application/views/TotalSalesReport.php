<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
<h1>Details</h1>        
<table class="table table-report">
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
        <td><?=$sales["date"]?></td>
        <td><?=$sales["businessName"]?></td>
        <td><?=$sales["code"]?></td>
        <td><?=$sales["journalID"]?></td>
        <td><?=$sales["total"]?></td>
    </tr>
    <? } ?>
    </table>    
</div>
