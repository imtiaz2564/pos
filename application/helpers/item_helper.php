<?php
function printTransactions($journal_id){
    $ci =& get_instance();
    $ci->load->model('item_model');
    $rows = $ci->item_model->getTransactions($journal_id);
    ?><table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th><th>Item Code</th><th>Item Name</th><th>Item Quantity</th>
            </tr>
        </thead>
    <?
    $total = 0;
    foreach($rows as $row){
        $total += 0;
        ?>
        <tr>
            <td><?=$row['date']?></td><td><?=$row['code']?></td><td><?=$row['name']?></td><td><?=$row['quantity']?></td>
        </tr>
        <?
    }
    ?>
    </table><?
}
function printInTransactions($journal_id){
    $ci =& get_instance();
    $ci->load->model('item_model');
    $rows = $ci->item_model->getTransactions($journal_id);
    ?><table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th><th>Item Code</th><th>Item Name</th><th>Unit Price</th><th>Item Quantity</th><th>Total</th>
            </tr>
        </thead>
    <?
    $total = 0;
    foreach($rows as $row){
        $total += $row['quantity']*$row['unit_price'];
        ?>
        <tr>
            <td><?=$row['date']?></td><td><?=$row['code']?></td><td><?=$row['name']?></td><td><?=$row['unit_price']?></td><td><?=$row['quantity']?></td><td><?=$row['quantity']*$row['unit_price']?></td>
        </tr>
        <?
    }
    ?>
        <tfoot>
            <tr>
                <td></td><td></td><td></td><td></td><td><strong>Grand Total:</strong></td><td><strong><?=$total?></strong></td>
            </tr>
        </tfoot>
    </table><?
}
function printOutTransactions($journal_id){
    $ci =& get_instance();
    $ci->load->model('item_model');
    $rows = $ci->item_model->getTransactions($journal_id);
    ?><table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th><th>Item Code</th><th>Item Name</th><th>Item Quantity</th>
            </tr>
        </thead>
    <?
    $total = 0;
    foreach($rows as $row){
        $total += $row['quantity'];
        ?>
        <tr>
            <td><?=$row['date']?></td><td><?=$row['code']?></td><td><?=$row['name']?></td><td><?=$row['quantity']?></td>
        </tr>
        <?
    }
    ?>
        <tfoot>
            <tr>
                <td></td><td></td><td><strong>Grand Total:</strong></td><td><strong><?=$total?></strong></td>
            </tr>
        </tfoot>
    </table><?
}