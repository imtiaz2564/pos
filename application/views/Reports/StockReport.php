<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1>Daily AB Stock</h1>
    <table class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>Date</td> 
               <td>Item Name</td> 
               <td>Previous Stock</td>
               <td>Import</td>
               <td>Sold</td>
               <td>Balance</td>
            </tr>
        </thead>
        <? $i = 0; foreach( $previousstock as $stock ) {  ?>
        <tr>
            <td><?=$i++?></td>
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
