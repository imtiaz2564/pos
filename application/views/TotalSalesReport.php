<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
<h1>Details</h1>        
<table class="table table-report">
    <thead>
    <tr>
        <td><b>Sl</b></td>
        <td><b>Customer ID</b></td>
        <td><b>Customer Name</b></td>
        <td><b>Date</b></td>
        <td><b>Quantity</b></td>
        <td><b>UoM</b></td>
        <td><b>Unit Of Price</b></td>
        <td><b>Total</b></td>
    </tr>
    </thead>
    <? foreach( $salesData as $sales) 
    foreach( $sales as $sales)
    { 
        //print_r($sales);
        ?>
    <tr>
        <td><?=$sales["id"]?></td>
        <td><?=$sales["item_id"]?></td>
        <td><?=$sales["item_name"]?></td>
        <td><?=$sales["journal_id"]?></td>
        <td><?=$sales["date"]?></td>
        <td><?=$sales["type"]?></td>
        <td><?=$sales["labourCost"]?></td>
        <td><?=$sales["transportCost"]?></td>
    </tr>
    <? } ?>
    </table>    
</div>
