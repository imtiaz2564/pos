<div class="panel-dialog" role="document">
    <div class="panel-content"> 
        <div class="panel-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="panel-title">
            </h4>
        </div>
        <div class="panel-body">
            <div class="row">
                    <div class="col-md-3 form-group">
                        <label>Date ( From ) :</label>
                        <input type="text" name="salesFrom" value="" class="form-control date" data-date-format="YYYY-MM-DD">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Date ( To ) :</label>
                        <input type="text" name="salesTo" value="" class="form-control date" data-date-format="YYYY-MM-DD">
                    </div>
                    <? $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'salesreport' ) { ?>            
                    <div class="col-md-3 form-group">
                        <label>Criteria: </label> 
                        <select class="form-control report"  name = "reportType" >
                            <option class="form-group report" value="0">None</option>
                            <option class="form-group report" value="customer">Filter By Customer</option>
                            <option class="form-group report" value="item">Filter By Item</option>
                        </select>
                    </div>
                    <? } if( $this->uri->segment(2) == 'purchasereport' ) {?>
                        <div class="col-md-3 form-group">
                        <label>Criteria: </label> 
                        <select class="form-control report"  name = "reportType" >
                            <option class="form-group report" value="0">None</option>
                            <option class="form-group report" value="supplier">Filter By Supplier</option>
                            <option class="form-group report" value="item">Filter By Item</option>
                        </select>
                    </div>
                    <? } ?> 
            </div>
            <div id = "reportdetails" ></div>
    </div>
</div>
<script>

$('select[name=reportType]').on('change', function(){
    var salesFrom = $('input[name=salesFrom]').val();
        var salesTo = $('input[name=salesTo]').val();
        if(salesFrom.length  ==  ' ' || salesTo.length  ==  ' ' ){
            alert('Insert Date');
            return false;
        }
        reportType = $('select[name=reportType]').val();
        <? $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'salesreport' ) { ?>
            $('#reportdetails').load('<?=site_url('report/getsalesreport')?>/'+reportType+'/'+salesFrom+'/'+salesTo+'/');
        <? } ?>
        <? $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'purchasereport' ) { ?>
            $('#reportdetails').load('<?=site_url('report/getpurchasereport')?>/'+reportType+'/'+salesFrom+'/'+salesTo+'/');
        <? } ?>      
    });
   
</script>






