<div class="panel-dialog" role="document">
    <div class="panel-content"> 
        <div class="panel-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="panel-title">
            </h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <fieldset>
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
            <div id ="submitButton" class="panel-footer submit">
                <div class="btn-group submit">
                <a href="#" class = "btn btn-primary pull-right" onclick="return submitPaymentData()">Submit</a>
                <div class="clearfix"></div>            
            </div>
        </div>
    </div>
</div>
<script>
    function submitPaymentData(){
        var salesFrom = $('input[name=salesFrom]').val();
        var salesTo = $('input[name=salesTo]').val();
        if(salesFrom.length  ==  ' ' || salesTo.length  ==  ' ' ){
            alert('Insert Date');
            return false;
        }
        <? $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'paymentreport' ) { ?>
        $('#reportdetails').load('<?=site_url('report/getpaymentreport')?>/'+salesFrom+'/'+salesTo+'/');
        <? } ?>
        <? $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'importreport' ) { ?>
        $('#reportdetails').load('<?=site_url('report/getimportreport')?>/'+salesFrom+'/'+salesTo+'/');
        <? } ?>
        <? $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'salesreport' ) { ?>
        $('#reportdetails').load('<?=site_url('report/getsalesreport')?>/'+salesFrom+'/'+salesTo+'/');
        <? } ?>
    }
</script>






