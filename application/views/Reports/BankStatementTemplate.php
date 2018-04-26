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
                <div class="col-md-4 form-group">
                    <label>Bank Account :</label>
                    <select name = "bankId" class="form-control autocomplete">
                        <? foreach( $banklist as $banklist ){?>
                        <option value=<?=$banklist['id']?>><?=$banklist['name']?></option>
                        <? } ?>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                        <label>Date ( From ) :</label>
                        <input type="text" name="datFrom" value="" class="form-control date" data-date-format="YYYY-MM-DD">
                </div>
                <div class="col-md-3 form-group">
                        <label>Date ( To ) :</label>
                        <input type="text" name="datTo" value="" class="form-control date" data-date-format="YYYY-MM-DD">
                </div>  
            </fieldset>
        </div>
        <div id = "reportdetails" ></div>
        <div id ="buttonGroup" class="panel-footer submit">
            <div class="btn-group submit">
            <a href="#" class = "btn btn-primary pull-right" onclick="return submitBankData()">Submit</a>
            <div class="clearfix"></div>            
        </div>
    </div>
</div>
</div>
<script>
    function submitBankData(){
        var bankID = $('select[name=bankId]').val();
        var datFrom = $('input[name=datFrom]').val();
        var datTo = $('input[name=datTo]').val();
        if(datFrom.length  ==  ' ' || datTo.length  ==  ' ' ){
            alert('Insert Date');
            return false;
        }
        $('#reportdetails').load('<?=site_url('report/getbankreport')?>/'+bankID+'/'+datFrom+'/'+datTo+'/');
    }
</script>