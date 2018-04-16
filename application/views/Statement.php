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
                <? $uri = $this->uri->segment(3); if( $this->uri->segment(3) == 'history'  ) { ?>
                    
                        <label>Business Name ( Customer ) :</label>
                        <select name = "customerId" class="form-control autocomplete">
                        <option  value = "0" >ALL</option>
                        <? foreach($people as $people) {?>
                        <option  value = <?=$people['id']?>><?=$people['businessName']?></option>
                        <? } ?>
                        </select>
     
                <? }  if(  $this->uri->segment(3) == 'statement' ) { ?>
                    
                    <label>Business Name ( Customer ) :</label>
                    <select name = "customerId" class="form-control autocomplete">
                    <? foreach($people as $people) {?>
                    <option  value = <?=$people['id']?>><?=$people['businessName']?></option>
                    <? } ?>
                    </select>
 
            <? }  if( $this->uri->segment(3) == 'supplierhistory') { ?>
                    
                    <label>Compnay Name ( Supplier ):</label>
                    <select name = "supplierId" class="form-control autocomplete">
                    <option  value = "0" >ALL</option>
                    <? foreach($people as $people) {?>
                    <option  value = <?=$people['id']?>><?=$people['businessName']?></option>
                    <? } ?>
                    </select>
 
                <? } if(  $this->uri->segment(3) == 'supplierstatement') { ?>
                    
                    <label>Compnay Name ( Supplier ):</label>
                    <select name = "supplierId" class="form-control autocomplete">
                    <? foreach($people as $people) {?>
                    <option  value = <?=$people['id']?>><?=$people['businessName']?></option>
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
        <div id ="buttonGroup" class="panel-footer submit">
            <div class="btn-group submit">
            <? if( $this->uri->segment(3) == 'supplierhistory' || $this->uri->segment(3) == 'supplierstatement') { ?>
                <a href="<?=site_url('item/purchase')?>" class = "btn btn-primary pull-right">Close</a>
            <? } ?>
            <? if( $this->uri->segment(3) == 'history' || $this->uri->segment(3) == 'statement') { ?>
                <a href="<?=site_url('item/sale')?>" class = "btn btn-primary pull-right">Close</a>
            <? } ?>   
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
        if(salesFrom.length  ==  ' ' || salesTo.length  ==  ' ' ){
            alert('Insert Date');
            return false;
        }
        $('#reportdetails').load('<?=site_url('finance/getSalesReport')?>/'+customerID+'/'+salesFrom+'/'+salesTo+'/');
        <? } ?>
        <? $uri = $this->uri->segment(3); if( $this->uri->segment(3) == 'statement' ) { ?>
        var customerID = $('select[name=customerId]').val();
        var salesFrom = $('input[name=salesFrom]').val();
        var salesTo = $('input[name=salesTo]').val();
        if(salesFrom.length  ==  ' ' || salesTo.length  ==  ' ' ){
            alert('Insert Date');
            return false;
        }
        $('#reportdetails').load('<?=site_url('finance/getcustomerstatement')?>/'+customerID+'/'+salesFrom+'/'+salesTo+'/');
        <? } ?>
        <? $uri = $this->uri->segment(3); if( $this->uri->segment(3) == 'supplierhistory' ) { ?>
        var supplierId = $('select[name=supplierId]').val();
        var salesFrom = $('input[name=salesFrom]').val();
        var salesTo = $('input[name=salesTo]').val();
        if(salesFrom.length  ==  ' ' || salesTo.length  ==  ' ' ){
            alert('Insert Date');
            return false;
        }
        $('#reportdetails').load('<?=site_url('finance/getSupplierHistory')?>/'+supplierId+'/'+salesFrom+'/'+salesTo+'/');
        <? } ?>
        <? $uri = $this->uri->segment(3); if( $this->uri->segment(3) == 'supplierstatement' ) { ?>
        var supplierId = $('select[name=supplierId]').val();
        var salesFrom = $('input[name=salesFrom]').val();
        var salesTo = $('input[name=salesTo]').val();
        if(salesFrom.length  ==  ' ' || salesTo.length  ==  ' ' ){
            alert('Insert Date');
            return false;
        }
        $('#reportdetails').load('<?=site_url('finance/getSupplierStatement')?>/'+supplierId+'/'+salesFrom+'/'+salesTo+'/');
        <? } ?>
    }
</script>