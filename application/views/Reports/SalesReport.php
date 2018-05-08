<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1>Daily Sales Report By Customer</h1>        
    <table id="printTable" class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>Date</td> 
               <!-- <td>Customer ID</td> -->
               <td>Business Name</td>
               <td>Sales Labour Charge</td>
               <td>Amount</td>
            </tr>
        </thead>
        <? $i = 1; $totalLabourCost=0; $total=0; $cusID = " ";  foreach( $salesData as $sales) foreach( $sales as $salesData) {
            
             $total +=$salesData["totalSales"];
             
             $totalLabourCost += $salesData["labourCost"];
             ?>
        <tr>
        <?//if( $cusID != $salesData["customerID"]) {?>
            
            <td><?=$i++?></td>
            <td><?=$salesData["journalDate"]?></td>
            <!-- <td><?//=$salesData["customerID"]?></td> -->
            <td><?=$salesData["businessName"]?></td>
            <td><?=$salesData["labourCost"]?></td>
            <td><?=$salesData["totalSales"]?></td>
            <? } ?>
           
         <?  //$cusID = $salesData["customerID"]; ?>
         </tr>   
        <? //} ?>
        <tr>
            <td><b>Total</b></td>
            <td></td>
            <td></td>
            <td><?=$totalLabourCost?></td>
            <td><?=$total?></td>
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
        newWin= window.open("");
        newWin.document.write(htmlToPrint);
        newWin.print();
        newWin.close();
    }
</script>