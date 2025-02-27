<div class="modal-body" style="max-height:450px; overflow-y:scroll;">
    <div class="page-header">
        <div class='page-heading text-center'>
            <h1>Amin Brothers</h1>
            <h3>Mohajonpotti road,khalighat Sylhet</h3>
            <u><h3>Statement of bank account</h3></u>
        </div>
    </div>
    <? $prevBalance = 0; foreach($previousBalance as $previousBalance) {
          if($previousBalance['type']=="0") { $prevBalance +=$previousBalance['amount']; 
          } if($previousBalance['type']=="1") { $prevBalance -=$previousBalance['amount']; 
          } if($previousBalance['type']=="2") { $prevBalance +=$previousBalance['amount'];
          } if($previousBalance['type']=="3") { $prevBalance -=$previousBalance['amount'];
          } 
    }?>
    <? foreach($bankDetails as $bank) ?>
    <table id = "printInfo">
        <thead>
            <tr>
                <th>Account Name: </th>
                <td><?=$bankName?></td>
            </tr>
            <tr>
                <th>Period: </th>
                <td><?=$datFrom?> To <?=$datTo?></td>
            </tr>
            <tr>
                <th>Date: </th>
                <td><?=Date('Y-m-d')?></td>
            </tr>
        </thead>
    </table>
    <table id = "printTable" class="table table-report">
    <thead>
        <tr>
            <th>Date</th>
            <th>Party(Business Name)</th>
            <th>Descriptions</th>
            <th>Paid In</th>
            <th>Paid Out</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tr>
        <td></td>
        <td></td>
        <td>Balance Brought Forward</td>
        <td></td>
        <td></td>
        <td><?=$prevBalance?></td>

    </tr>
    <? $balance = $prevBalance; foreach($bankDetails as $bankDetail) { ?>
    <tr>
        <td><?=$bankDetail['date']?></td>
        <? if($bankDetail['peopleID'] == 3) {?>
            <td>AB</td>
        <? } else { ?>
            <td><?=$bankDetail['businessName']?></td>
        <?}?>
        <td><?=$bankDetail['description']?></td>
        <? if($bankDetail['type']=="0") { $balance +=$bankDetail['amount']; ?>
            <td><?=$bankDetail['amount']?></td>
            <td></td>
        <? } else if($bankDetail['type']=="1") { $balance -=$bankDetail['amount']; ?>
            <td></td>
            <td><?=$bankDetail['amount']?></td>
        <? }else if($bankDetail['type']=="2") { $balance +=$bankDetail['amount'];?>
            <td><?=$bankDetail['amount']?></td>
            <td></td>
        <? } else if($bankDetail['type']=="3") { $balance -=$bankDetail['amount'];?>
            <td></td>
            <td><?=$bankDetail['amount']?></td>
        <? }?>
        <td><?=$balance?></td>
    </tr>
    <? } ?>
    <tr>
        <td></td>
        <td></td>
        <td>Balance Carried Forward</td>
        <td></td>
        <td></td>
        <td><?=$balance?></td>
    </tr>
   </table>    
<div class="row">
    <div class="col-xs-12 text-right">
    <button class="btn btn-primary" onclick="printDiv()">Print</button>
    </div>
</div>
</div>
<script>
$('#buttonGroup').hide();
    function printDiv() {
        var divToPrintInfo=document.getElementById("printInfo");
        var divToPrint=document.getElementById("printTable");
        var htmlToPrint = '' +
        '<style type="text/css">' +
            'table#printTable th, table#printTable  td {' +
            'border: 1px solid black;' +
        '}' +
        'table#printTable {' +
            'border-collapse: collapse;' +
            'width: 100%;' +
        '}'+
        '</style>';
        htmlToPrint += divToPrint.outerHTML;
        newWin= window.open("");
        newWin.document.write(divToPrintInfo.outerHTML);
        newWin.document.write(htmlToPrint);
        newWin.print();
        newWin.close();
    }
</script>
