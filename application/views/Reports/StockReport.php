<div class="modal-body" style="max-height:450px; overflow-y:scroll;">
    <h1>Daily AB Stock</h1>
    <table class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>Date</td> 
               <td>Item Name</td> 
               <td>OLD Stock</td>
               <td>Today IN</td>
               <td>Today OUT</td>
               <td>Current Stock</td>
            </tr>
        </thead>
        <? $i = 0; foreach( $previousstock as $stock ) { $i++; ?>
        <tr>
            <td><?=$i?></td>
            <td><?=date('Y-m-d')?></td>
            <td><?=$stock['name']?></td>
            <td><?=$stock['previous']?></td>
            <td><?=$stock['import']?></td>
            <td><?=$stock['sold']?></td>
            <td><?=$stock['previous']+$stock['import']-$stock['sold']?></td>
        </tr>
        <? } ?> 
    </table>    
</div>
