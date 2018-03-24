<div class="panel-dialog" role="document">
    <div class="panel-content"> 
        <div class="panel-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="panel-title">
                <i class="fa fa-file-o"></i>Daily Customer Transaction Report            
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
            <div class="panel-footer submit">
                <div class="btn-group submit">
                    <a href="#" class = "btn btn-primary pull-right" onclick="return submitCustomerData()">Submit</a>
                    <div class="clearfix"></div>            
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function submitCustomerData(){
        var salesFrom = $('input[name=salesFrom]').val();
        var salesTo = $('input[name=salesTo]').val();
        if(salesFrom.length  ==  ' ' || salesTo.length  ==  ' ' ){
            alert('Insert Date');
            return false;
        }
        $('#reportdetails').load('<?=site_url('report/getcustomertransactiondata')?>/'+salesFrom+'/'+salesTo+'/');
    }
</script>




