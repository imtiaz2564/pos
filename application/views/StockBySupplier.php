<div class="panel panel-default">
    <div class="panel-heading">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4>
                <i class="fa fa-bar-chart-o">Stock By Supplier</i>
            </h4>
    </div>
    <div class="panel-body">
<table class = "table table-report">
    <thead>
    <tr>
        <td><b>Supplier</b></td>
        <td><b>Item Name</b></td>
        <td><b>Available Quantity</b></td>
    </tr>
    </thead>    
    <?php foreach($supplierInfo as $info) { ?>
 
    <tr>
        <td><?=$info['customer']?></td>
        <td><?=$info['name']?></td>
        <td><?=$info['quantity']?></td>
     </tr>
    <? } ?>
    
</table>    
        <div class="modal-footer">
            <a href="#" class="btn btn-default">Save as PDF</a>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div> 
</div>    