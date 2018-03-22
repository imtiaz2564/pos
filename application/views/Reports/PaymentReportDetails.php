<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1>Details</h1>        
    <table class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>Date</td> 
               <td>Business Name</td>
               <td>Description</td>
               <td>Type</td>
               <td>Money IN</td>
               <td>Money OUT</td> 
            </tr>
        </thead>
        <? $i = 1; $totalIn = 0; $totalOut = 0; foreach( $paymentData as $paymentData) { ?>
        <tr>
            <td><?=$i++?></td>
            <td><?=$paymentData["date"]?></td>
            <td><?=$paymentData["businessName"]?></td>
            <td><?=$paymentData["description"]?></td>
            <? if($paymentData["paymentType"] == 0) { ?>
                <td><?="Cash"?></td>
            <? } if($paymentData["paymentType"] == 1) { ?>
                <td><?="Bank"?></td>
            <? } if($paymentData["paymentType"] == 2) { ?>
                <td><?="Cash Back"?></td>
            <? } ?>    
            <? if( $paymentData["type"] == 0 ) { $totalIn += $paymentData["amount"]; ?>

            <td><?=$paymentData["amount"]?></td>
            <? } else {?>
                <td><?=0?></td>
            <? } ?>
            <? if( $paymentData["type"] == 1 ) { $totalOut += $paymentData["amount"];?>
            <td><?=$paymentData["amount"]?></td>
            <? } else {?>
                <td><?=0?></td>
            <? } ?>
        </tr>
        <? } ?>
        <tr>
            <td>Total</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$totalIn?></td>
            <td><?=$totalOut?></td>
        </tr>
    </table>    
</div>
