<div class="modal-dialog" role="document">
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">
            <i class="fa fa-file-o">Customer Sales Report</i>
        </h4>
    </div>
   <div class="modal-body">
       <div class="row">
           <fieldset>
           <div class="col-md-3 form-group">
                <label>Customer ID :</label>
                <td><input type="text" name="customerId" value="" class="form-control" placeholder="customer ID"></td>
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
       
    <div class="modal-footer submit">
        <div class="btn-group submit">
            <a href="#" class = "btn btn-primary pull-right" onclick="return submitCustomerData()">Submit</a>
                <div class="clearfix"></div>            
        </div>
    </div>
</div>
</div>
<script>
    function submitCustomerData(){
        var customerID = $('input[name=customerId]').val();
        var salesFrom = $('input[name=salesFrom]').val();
        var salesTo = $('input[name=salesTo]').val();
        $('#reportdetails').load('<?=site_url('finance/getSalesReport')?>/'+customerID+'/');
        // $(function(){
        //     $.ajax({
        //     type: 'POST',
        //     dataType: 'json',
        //     //url: '<?//=site_url('finance/getSalesReport')?>'+'/'+customerID+'/'+salesFrom+'/'+salesTo+'/',
        //     url: '<?//=site_url('finance/getSalesReport')?>'+'/'+customerID+'/', 
        //     success: function (data) {
        //         // labourCost = parseInt(data["labourCost"]);
        //     }
        //  });
        // });
    }
</script>