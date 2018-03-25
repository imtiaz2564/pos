<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1>Report By Customer</h1>        
    <table class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>Date</td> 
               <td>Business Name</td>
               <td>Amount</td>
            </tr>
        </thead>
        <? $i = 1;  foreach( $salesData as $salesData) { ?>
        <tr>
            <td><?=$i++?></td>
            <td><?=$salesData["date"]?></td>
            <td><?=$salesData["businessName"]?></td>
            <td><?=$salesData["totalSales"]?></td>
        <? } ?>   
    </table>  
</div>
