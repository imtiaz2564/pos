<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1>Details</h1>        
    <table class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>Date</td> 
               <td>Business Name</td>
               <td>Item Name</td>
               <td>Quantity</td>
               <td>Receiver Name</td> 
               <td>Transport Name</td>
               <td>Transport Cost</td>
               <td>Unload Labour Charge</td>
            </tr>
        </thead>
        <? $i = 1; $totalTransportCost = 0; $totalLabourCost = 0; foreach( $importData as $importData) { 
            $labourCost = $importData["labourCost"] * $importData["quantity"];  
            $totalTransportCost += $importData["transportCost"]; $totalLabourCost += $labourCost; ?>
        <tr>
            <td><?=$i++?></td>
            <td><?=$importData["unloadDate"]?></td>
            <td><?=$importData["businessName"]?></td>
            <td><?=$importData["itemName"]?></td>
            <td><?=$importData["quantity"]?></td>
            <td><?=$importData["receiverName"]?></td>
            <td><?=$importData["transport"]?></td>
            <td><?=$importData["transportCost"]?></td>
            <td><?=$labourCost?></td>
            <!-- <td><?//=$importData["labourCost"]?></td> -->
        </tr>
        <? } ?>   
        <tr>
            <td><b>Total</b></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$totalTransportCost?></td>
            <td><?=$totalLabourCost?></td>
        </tr>   
    </table>    
</div>
