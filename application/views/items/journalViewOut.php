<div class="container white">
    <div class="row">
        <a class="btn btn-default pull-right" onclick="javascript:window.print();">Print</a>
    </div>
    <div id="print">
    <div class="row">
        <center><h1>Sylhet POS</h1></center>
    </div>
    <?
    foreach( $labels as $label ){
        ?><label><?=$label?></label> :<?=$row[$label]?><br /><?
    }
    printTransactions($row['id']);
    ?>
    </div>
</div>