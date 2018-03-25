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
                    <div class="col-md-3 form-group" class="form-group report" id = "reportType">
                    
                    <label class="form-group report">Select Criteria: </label> 
                    <select class="form-group report"  name = "reportType" >
                        <option class="form-group report" value="0">None</option>
                        <option class="form-group report" value="customer">Filter By Customer</option>
                        <option class="form-group report" value="item">Filter By Item</option>
                    </select>
                    </div>
                </fieldset>   
            </div>
            <div id = "reportdetails" ></div>
    </div>
</div>
<script>
$('select[name=reportType]').on('change', function(){
    reportType = $('select[name=reportType]').val();
    $('#reportdetails').load('<?=site_url('report/getsalesreport')?>/'+reportType+'/');
          
});
   
   // function submitPaymentData(){
        //$('#reportdetails').load('<?//=site_url('report/getpaymentreport')?>/'+salesFrom+'/'+salesTo+'/');
   // }
</script>






