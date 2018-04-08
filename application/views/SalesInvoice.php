<? foreach($salesData as $data) ?>
<div class="modal-body" >
<h1>Sales Invoices</h1>        
<table class="table table-report">
     <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                            </td>
                            <td>
                                Invoice #: <?=$data['journal_id']?><br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="heading">
                <td>Item Name</td>
                <td>Unit Price</td>
                <td>Quantity</td>
                <td>Discount</td>
                <td>Total</td>

            </tr>
            <? $total = 0; $payable = 0;  foreach($salesData as $salesData) { ?>
                <tr>
                    <td><?=$salesData['name']?></td>
                    <td><?=$salesData['unit_price']?></td>
                    <td><?=$salesData['quantity']?></td>
                    <td><?=$salesData['discount']?></td>
                    <td><?=($salesData['unit_price']*$salesData['quantity'])-($salesData['quantity']*$salesData['discount'])?></td>
                </tr>
                <? $total += ($salesData['unit_price']*$salesData['quantity'])-($salesData['quantity']*$salesData['discount']);}?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Grand Total</td>
                    <td><?=$total?></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Labour Cost</td>
                    <td><?=$labourCost?></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Final Discount</td>
                    <td><?=$totalDiscount?></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total Payable</td>
                    <!-- <td><?//=($total+$salesData['labourCost'])-$salesData['totalDiscount']?></td> -->
                    <td><?=($total+$labourCost)-$totalDiscount?></td>
                </tr>
        </table>    
</div>
