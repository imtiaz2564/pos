<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1>Report By Customer</h1>        
    <table class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>Date</td> 
               <td>Business Name</td>
               <td>Sales Labour Charge</td>
               <td>Amount</td>
            </tr>
        </thead>
        <? $i = 1; $total=0; foreach( $salesData as $salesData) { $total +=$salesData["totalSales"];?>
        <tr>
            <td><?=$i++?></td>
            <td><?=$salesData["date"]?></td>
            <td><?=$salesData["businessName"]?></td>
            <td><?=$salesData["labourCost"]?></td>
            <td><?=$salesData["totalSales"]?></td>
         </tr>   
        <? } ?>
        <tr>
            <td><b>Total</b></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$total?></td>
        </tr>   
    </table>  
</div>
