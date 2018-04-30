<div class="panel-dialog" role="document">
    <div class="panel-content"> 
        <div class="panel-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="panel-title">Balance Report
            </h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>Criteria: </label> 
                        <select class="form-control report"  name = "reportType" >
                            <option class="form-group report" value="0">None</option>
                            <option class="form-group report" value="1">Filter By Supplier</option>
                            <option class="form-group report" value="0">Filter By Customer</option>
                        </select>
                    </div> 
                </div>
            <div id = "reportdetails" ></div>
        </div>
    </div>
<script>
    $('select[name=reportType]').on('change', function(){
        reportType = $('select[name=reportType]').val();
        $('#reportdetails').load('<?=site_url('report/getbalancereportdata')?>/'+reportType+'/');
    });
   
</script>






