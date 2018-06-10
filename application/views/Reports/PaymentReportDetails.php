<?  $oldTotalCashIn = 0; $oldTotalCashOut = 0;  $oldCashBack = 0;
        foreach( $openingData as $openingData) { 
		    if($openingData["type"] == 0 && $openingData["paymentType"] == 0 || $openingData["type"] == 3){ //new added
                $oldTotalCashIn += $openingData["amount"];
            }
            if($openingData["type"] == 1 && $openingData["paymentType"] == 0 || $openingData["type"] == 2 ){ //new added
                $oldTotalCashOut += $openingData["amount"];
            }
            if($openingData["type"] == 0 && $openingData["paymentType"] == 2){
                $oldCashBack += $openingData["amount"];
            }
        } 
        $oldHandCashCheque =  $oldTotalCashIn - $oldTotalCashOut - $oldCashBack; 
?>
<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1 id = "printHeader">Balance Report</h1>        
    <table id = "printTable" class="table">
        <thead>
            <tr>
               <th>No</th>
               <th>Date</th> 
               <th>Party(Business Name)</th>
               <th>Description</th>
               <th>Type</th>
               <th>Money IN</th>
               <th>Money OUT</th>
               <th>Cash Back</th> 
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
            <? }if($paymentData["type"] == 0 && $paymentData["paymentType"] == 0 || $paymentData["type"] == 3){ //new added
                    $totalCashIn += $paymentData["amount"];
                }
                if($paymentData["type"] == 0 && $paymentData["paymentType"] == 1 ){
                    $totalBankIn += $paymentData["amount"];
                }
                if($paymentData["type"] == 1 && $paymentData["paymentType"] == 0 || $paymentData["type"] == 2 ){ //new added
                    $totalCashOut += $paymentData["amount"];
                }
                if($paymentData["type"] == 1 && $paymentData["paymentType"] == 1){
                    $totalBankOut += $paymentData["amount"];
                }
                if($paymentData["type"] == 0  && $paymentData["paymentType"] != 2){
                    $totalIn += $paymentData["amount"]; ?>
                    <td><?=$paymentData["amount"]?></td>
                    <td><?=0?></td>
                <? }if($paymentData["type"] == 2 ){ $totalOut += $paymentData["amount"]; //new added
                     ?>
                     <td><?=0?></td>
                    <td><?=$paymentData["amount"]?></td>
                    
                <? }
                 if($paymentData["type"] == 3 ){ //$totalOut += $paymentData["amount"]; //new added
                     $totalIn += $paymentData["amount"]; ?>
                    <td><?=$paymentData["amount"]?></td>
                    <td><?=0?></td>
                    
                <? } //end
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
        </table>
        <table style="width: 50%" id = "printTable2" class="table" align="right">
        <tr>
            <th></th>
            <th>Total IN</th>
            <th>Total Out</th>
            <th>Total Cash Back</th>
        </tr>
        <tr>
            <td style="width:155px">Total Cash: </td>
            <td style="width:120px"><?=$totalCashIn?></td>
            <td style="width:140px"><?=$totalCashOut?></td>
            <td></td>
        </tr>
        <tr>
            <td>Total Bank: </td>
            <td><?=$totalBankIn?></td>
            <td><?=$totalBankOut?></td>
            <td></td>
        </tr>
        <tr>
            <td>Total:</td>
            <td><?=$totalIn?></td>
            <td><?=$totalOut?></td>
            <td><?=$cashBack?></td>
        </tr>
        <tr>
            <td>Opening Balance (Cash and Cheque):</td>
            <td><?=$oldHandCashCheque?></td>
            
        </tr>
        <tr>
            <? $handCashCheque = $totalCashIn - $totalCashOut - $cashBack; ?>
            <td>Closing Balance (Cash and Cheque):</td>
            <td><?=$handCashCheque + $oldHandCashCheque?></td>
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
        var divToPrintHeader=document.getElementById("printHeader");
        var divToPrint2=document.getElementById("printTable2");
        var htmlToPrint = '' +
        '<style type="text/css">' +
            'table th, table td {' +
            'border: 1px solid black;' +
        '}' +
        'table {' +
            'border-collapse: collapse;' +
            'width: 100%;' +
        '}'+
        '</style>';
        htmlToPrint += divToPrint.outerHTML;        
        htmlToPrint += divToPrint2.outerHTML;
        newWin= window.open("");
        newWin.document.write(divToPrintHeader.outerHTML);
        newWin.document.write(htmlToPrint);
        newWin.print();
        newWin.close();
    }
</script>