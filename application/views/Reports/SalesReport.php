<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1>Daily Sales Report By Customer</h1>        
    <table class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>Date</td> 
               <!-- <td>Customer ID</td> -->
               <td>Business Name</td>
               <td>Sales Labour Charge</td>
               <td>Amount</td>
            </tr>
        </thead>
        <? $i = 1; $totalLabourCost=0; $total=0; $cusID = " ";  foreach( $salesData as $sales) foreach( $sales as $salesData) {
            
             $total +=$salesData["totalSales"];
             
             $totalLabourCost += $salesData["labourCost"];
             ?>
        <tr>
        <?//if( $cusID != $salesData["customerID"]) {?>
            
            <td><?=$i++?></td>
            <td><?=$salesData["journalDate"]?></td>
            <!-- <td><?//=$salesData["customerID"]?></td> -->
            <td><?=$salesData["businessName"]?></td>
            <td><?=$salesData["labourCost"]?></td>
            <td><?=$salesData["totalSales"]?></td>
            <? } ?>
           
         <?  //$cusID = $salesData["customerID"]; ?>
         </tr>   
        <? //} ?>
        <tr>
            <td><b>Total</b></td>
            <td></td>
            <td></td>
            <td><?=$totalLabourCost?></td>
            <td><?=$total?></td>
        </tr>   
    </table>  
</div>
