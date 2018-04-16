<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1>Details</h1>        
    <table id = "printTable" class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>Date</td> 
               <td>Business Name</td>
               <td>Description</td>
               <td>Type</td>
               <td>Money IN</td>
               <td>Money OUT</td>
               <td>Cash Back</td> 
            </tr>
        </thead>
        <? $i = 1; $totalIn = 0; $totalOut = 0; $totalCashIn = 0; $totalBankIn = 0;
        $totalCashOut = 0; $totalBankOut = 0; $cashBack = 0;
        foreach( $paymentData as $paymentData) { ?>
        <tr>
            <td><?=$i++?></td>
            <td><?=$paymentData["date"]?></td>
            <td><?=$paymentData["businessName"]?></td>
            <td><?=$paymentData["description"]?></td>
            <? if($paymentData["paymentType"] == 0){ ?>
                <td><?="Cash"?></td>
            <? }if($paymentData["paymentType"] == 1){ ?>
                <td><?="Bank"?></td>
            <? }if($paymentData["paymentType"] == 2){ ?>
                <td><?="Cash Back"?></td>
            <? }if($paymentData["type"] == 0 && $paymentData["paymentType"] == 0 ){
                    $totalCashIn += $paymentData["amount"];
                }
                if($paymentData["type"] == 0 && $paymentData["paymentType"] == 1 ){
                    $totalBankIn += $paymentData["amount"];
                }
                if($paymentData["type"] == 1 && $paymentData["paymentType"] == 0 ){
                    $totalCashOut += $paymentData["amount"];
                }
                if($paymentData["type"] == 1 && $paymentData["paymentType"] == 1){
                    $totalBankOut += $paymentData["amount"];
                }
                if($paymentData["type"] == 0 && $paymentData["paymentType"] != 2){
                    $totalIn += $paymentData["amount"]; ?>
                    <td><?=$paymentData["amount"]?></td>
                    <td><?=0?></td>
                <? }
                if($paymentData["type"] == 0 && $paymentData["paymentType"] == 2){
                    $cashBack += $paymentData["amount"];?>
                    <td><?=0?></td>
                    <td><?=0?></td>
                    <td><?=$paymentData["amount"]?></td>
                <?}else{?>
                    <td><?=0?></td>
                <? } ?>
                <? if( $paymentData["type"] == 1 ) { $totalOut += $paymentData["amount"];?>
                    <td><?=$paymentData["amount"]?></td>
                    <td><?=0?></td>
                <? } ?>
            </tr>
        <? } ?>
        <tr>
            <td>Total Cash</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$totalCashIn?></td>
            <td><?=$totalCashOut?></td>
            <td></td>
        </tr>
        <tr>
            <td>Total Bank</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$totalBankIn?></td>
            <td><?=$totalBankOut?></td>
            <td></td>
        </tr>
        <tr>
            <td>Total</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$totalIn?></td>
            <td><?=$totalOut?></td>
            <td><?=$cashBack?></td>
        </tr>
        <tr>
            <? $handCashCheque = $totalCashIn - $totalCashOut - $cashBack; ?>
            <td>Hand Cash and Cheque</td>
            <td><?=$handCashCheque?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>    
</div>
<div class="row">
    <div class="col-xs-12 text-right">
    <button class="btn btn-primary" onclick="printDiv()">Print</button>
    </div>
</div>
<script>
    function printDiv() {
        var divToPrint=document.getElementById("printTable");
        newWin= window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
</script>