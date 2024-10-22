<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <div class="page-header">
            <div class='page-heading text-center'>
                <h1>Amin Brothers</h1>
                <h3>Mohajonpotti road,khalighat Sylhet</h3>
                <u><h3>Statement of account</h3></u>
            </div>
    </div>
    <table>
    <? foreach($info as $info) ?>
        <thead>
            <tr>
                <th>Supplier Code: </th>
                <td><?=$info['code']?></td>
            </tr>
            <tr>
                <th>Supplier Name: </th>
                <td><?=$info['name']?></td>
            </tr>
            <tr>
                <th>Business Name: </th>
                <td><?=$info['businessName']?></td>
            </tr>
            <tr>
                <th>Home Address: </th>
                <td><?=$info['address']?></td>
            </tr>
            <tr>
                <th>Business Address: </th>
                <td><?=$info['businessAddress']?></td>
            </tr>
            <tr>
                <th>Area: </th>
                <td><?=$info['area']?></td>
            </tr>
            <tr>
                <th>Thana: </th>
                <td><?=$info['thana']?></td>
            </tr>
            <tr>
                <th>District: </th>
                <td><?=$info['district']?></td>
            </tr>
            <tr>
                <th>Phone: </th>
                <td><?=$info['phone']?></td>
            </tr>
            <tr>
                <th>Email: </th>
                <td><?=$info['email']?></td>
            </tr>
            <tr>
                <th>Period: </th>
                <td><?=$this->uri->segment(4)." "."to"." ".$this->uri->segment(4)?></td>
            </tr>
        </thead>
    </table>
    <h1>Details</h1>        
    <table class="table table-report">
        <thead>
        <tr>
            <td><b>Date</b></td>
            <td><b>Purchase Description</b></td>
            <td><b>Payable Amounts</b></td>
            <td><b>Account Description</b></td>
            <td><b>Paid Amounts</b></td>
            <td><b>Balance</b></td>

        </tr>
        </thead>
        <tr>
            <td></td>
            <td>Opening Balance</td>
            <td></td>
            <td></td> 
            <td></td>        
            <td><?=$info['openingBalance']?></td>
        </tr>
        <? $paid = $info['openingBalance']; foreach( $history as $history) {
            $payable  = $history["quantity"]*$history["unit_price"];
            $paid = $paid - $payable;
            ?>
        <tr>
            <td><?=$history["date"]?></td>
            <td><?=$history["purchaseDescription"]?></td>
            <td><?=$payable?></td>
            <td></td>
            <td></td>
            <td><?=$paid?></td>
        </tr>

        <? } ?>
        <?  foreach($statement as $state) {   ?>
        <tr>
            <td><?=$state["date"]?></td>
            <td></td>
            <td></td>
            <td><?=$state["description"]?></td>
            <td><?=$state["amount"]?></td>
            <? $paid = $paid+$state["amount"] ?>
            <td><?=$paid?></td>
        </tr>
        <? } ?>
    </table>    
</div>
