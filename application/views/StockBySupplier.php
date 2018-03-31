<div class="modal-body" style="max-height:450px; overflow-y:scroll;">
    <h1>Stock By Supplier</h1>
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
            <td><?=$info['businessName']?></td>
            <td><?=$info['name']?></td>
            <td><?=$info['avail']?></td>
        </tr>
        <? } ?>
    
    </table>    
</div>