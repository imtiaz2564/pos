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
               <td>Transport Name</td>
               <td>Receiver Name</td> 
            </tr>
        </thead>
        <? $i = 1;  foreach( $importData as $importData) { ?>
        <tr>
            <td><?=$i++?></td>
            <td><?=$importData["unloadDate"]?></td>
            <td><?=$importData["businessName"]?></td>
            <td><?=$importData["itemName"]?></td>
            <td><?=$importData["quantity"]?></td>
            <td><?=$importData["transport"]?></td>
            <td><?=$importData["receiverName"]?></td>
        <? } ?>   
    </table>    
</div>
