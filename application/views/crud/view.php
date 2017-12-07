<div class="container white">
    <div class="row">
        <a class="btn btn-default pull-right" onclick="javascript:window.print();">Print</a>
    </div>
    <div id="print">
    <?
    foreach( $labels as $label ){
        ?><label><?=$label?></label> :<?=$row[$label]?><br /><?
    }
    ?>
    </div>
</div>