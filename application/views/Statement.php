<div class="panel-dialog" role="document">
    <div class="panel-content"> 
        <div class="panel-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="panel-title">
                <!-- <i class="fa fa-file-o">Statement</i> -->
            </h4>
        </div>
    <div class="panel-body">
        <div class="row">
            <fieldset>
                <div class="col-md-4 form-group">
                <? $uri = $this->uri->segment(3); if( $this->uri->segment(3) == 'history' || $this->uri->segment(3) == 'statement' ) { ?>
                    
                        <label>Customer ID  :</label>
                        <select name = "customerId" class="form-control autocomplete">
                        <? foreach($customers as $customer) {?>
                        <option  value = <?=$customer['id']?>><?=$customer['name']?></option>
                        <? } ?>
                        </select>
     
                <? } ?>
                <? $uri = $this->uri->segment(3); if( $this->uri->segment(3) == 'supplierhistory' || $this->uri->segment(3) == 'supplierstatement') { ?>
                    
                    <label>Supplier ID  :</label>
                    <select name = "supplierId" class="form-control autocomplete">
                    <? foreach($suppliers as $supplier) {?>
                    <option  value = <?=$supplier['id']?>><?=$supplier['name']?></option>
                    <? } ?>
                    </select>
 
                <? } ?>
                </div>
                <div class="col-md-3 form-group">
                        <label>Date ( From ) :</label>
                        <input type="text" name="salesFrom" value="" class="form-control date" data-date-format="YYYY-MM-DD">
                </div>
                <div class="col-md-3 form-group">
                        <label>Date ( To ) :</label>
                        <input type="text" name="salesTo" value="" class="form-control date" data-date-format="YYYY-MM-DD">
                </div>
           </fieldset>   
        </div>
        <div id = "reportdetails" ></div>
    <div class="panel-footer submit">
        <div class="btn-group submit">
            <a href="#" class = "btn btn-primary pull-right" onclick="return submitCustomerData()">Submit</a>
            <div class="clearfix"></div>            
        </div>
    </div>
</div>
</div>
<script>
    function submitCustomerData(){
        <? $uri = $this->uri->segment(3); if( $this->uri->segment(3) == 'history' ) { ?>
        var customerID = $('select[name=customerId]').val();
        var salesFrom = $('input[name=salesFrom]').val();
        var salesTo = $('input[name=salesTo]').val();
        $('#reportdetails').load('<?=site_url('finance/getSalesReport')?>/'+customerID+'/'+salesFrom+'/'+salesTo+'/');
        <? } ?>
        <? $uri = $this->uri->segment(3); if( $this->uri->segment(3) == 'statement' ) { ?>
        var customerID = $('select[name=customerId]').val();
        var salesFrom = $('input[name=salesFrom]').val();
        var salesTo = $('input[name=salesTo]').val();
        $('#reportdetails').load('<?=site_url('finance/getcustomerstatement')?>/'+customerID+'/'+salesFrom+'/'+salesTo+'/');
        <? } ?>
        <? $uri = $this->uri->segment(3); if( $this->uri->segment(3) == 'supplierhistory' ) { ?>
        var supplierId = $('select[name=supplierId]').val();
        var salesFrom = $('input[name=salesFrom]').val();
        var salesTo = $('input[name=salesTo]').val();
        $('#reportdetails').load('<?=site_url('finance/getSalesReport')?>/'+supplierId+'/'+salesFrom+'/'+salesTo+'/');
        <? } ?>
        <? $uri = $this->uri->segment(3); if( $this->uri->segment(3) == 'supplierstatement' ) { ?>
        var supplierId = $('select[name=supplierId]').val();
        var salesFrom = $('input[name=salesFrom]').val();
        var salesTo = $('input[name=salesTo]').val();
        $('#reportdetails').load('<?=site_url('finance/getcustomerstatement')?>/'+supplierId+'/'+salesFrom+'/'+salesTo+'/');
        <? } ?>
    }
</script>