<?=$error;?>
<?=$this->session->flashdata('');?>
<div class="panel panel-white">
    <div class="panel-heading">
        <i class="fa fa-bar-chart-o"></i>
    </div>
    <div class="panel-body">
        <div class="row">
            <?=$form_open?>
                <?php foreach($inputs as $input) { ?>
                    <div class="col-md-3"><?php
                        if($input['label'] !=''){?>
                            <div class="form-group">
                                <label><?=$input['label']?> :</label>
                                <?=$input['html']?>
                            </div>
                            <?php }else{
                                    echo $input['html'];
                                }
                            ?></div><?php
                        }?>
                    </div>
            <?=$form_close?>
        <div class="row">
            <div  class= "input-group customerInfo" id = "cusinfo">
                <div class= "input-group customerInfo"><h1>Customer Info</h1></div>
                <div class= "input-group customerInfo">
                    <label>Customer ID :</label>
                    <label style="color:#A9A9A9" id="cusCode"></label>
                </div>
                <div class= "input-group customerInfo">
                    <label>Business Name( Customer ) :</label>
                    <label style="color:#A9A9A9" id="businessName"></label>
                </div>
                <div class= "input-group customerInfo">
                    <label>Customer Name / Contact Person :</label>
                    <label style="color:#A9A9A9" id="cusName"></label>
                </div>
                <div class= "input-group customerInfo">
                    <label>Area :</label>
                    <label style="color:#A9A9A9" id="area"></label>
                </div>
            </div>
        </div>
        <div id="transactions"></div>
            <div class= "row">
                <div  class="form-group delivery" id = "deliveryType" style='float: right;'>
                    <label class="form-group delivery">Delivery Type: </label> 
                        <select class="form-group delivery"  name = "deliveryType" >
                            <option class="form-group delivery" value=" ">None</option>
                            <option class="form-group delivery" value="truck">Truck</option>
                            <option class="form-group delivery" value="thela">Van</option>
                        </select>
                    <label class="form-group delivery">Labour Cost: </label>
                    <label class="form-group delivery"><input type="text" name="labourCost" value="" class="form-control" placeholder="Labour Cost" readonly></label>
                    <label class="form-group delivery">Final Discount: </label>
                    <label class="form-group delivery"><input type="text" name="totalDiscount" value="" class="form-control" placeholder="Total Discount"></label>
                    <label class="form-group delivery">Grand Total: </label>
                    <label class="form-group delivery"><input type="text" name="grandtotal" value="" class="form-control" placeholder="Grand Total" readonly></label>
                </div>
            <div>
                <input id="cusId" name="cus" type="hidden" value="">
            </div>
            <div>
                <input id="supId" name="sup" type="hidden" value="">
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <div class="btn-group pull-right">
            <a href="<?=site_url()?>" class="btn btn-default">Close</a>
            <a href="#" onclick="$('#formJournal').submit();" class="btn btn-primary">Save</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<script>
    $.ajaxSetup({ cache: false });
    var grandtotal = 0;
    var totalDiscount = 0;
    $(function(){
        $('input[name=date]').val('<?=date('Y-m-d')?>');
        $('input[name=id]').attr('readonly', true);
        $('#cusinfo').hide();
        $('#deliveryType').hide();
    });
    var journalId = '<?=$this->uri->segment(4)?>';
    $('select[name=deliveryType]').on('change', function(){
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '<?=site_url('item/getdeliverytype')?>'+'/'+$(this).val()+'/'+journalId+'/', 
            success: function (data) {
                var deliveryCost = data['deliveryCost'];
                $('input[name=labourCost]').val(deliveryCost);
            }
        });
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: '<?=site_url('item/getgrandtotal')?>'+'/'+$(this).val()+'/'+journalId+'/', 
            success: function (data) {
                grandtotal = data['grandtotal'];
                $('input[name=grandtotal]').val(grandtotal);
            }
        });
    
    });
    $('input[name=totalDiscount]').on('keyup',function(){
        totalDiscount =$('input[name=totalDiscount]').val()
        grandtotal = parseInt(grandtotal);
        totalDiscount = parseInt(totalDiscount);
        var final = grandtotal-totalDiscount;
        $('input[name=grandtotal]').val(final);
          
    });    
    <? $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'in' ) { ?>
        $('#transactions').load('<?=site_url('item/ajax_itemlist/')?>');
       
    <? } ?>

    <? $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'out' ) { ?>
        $('select[name=customer_id]').change(function() {         
            $.ajax({
                type: 'POST',
                dataType: 'json',
                //url: '<?//=site_url('item/getCustomerData/')?>'+'/'+$(this).val()+'/', 
                url: '<?=site_url('item/getCustomerInfo/')?>'+'/'+$(this).val()+'/', 
                success: function (data) {
                    customerID = data["id"]; 
                    //phone = data["phone"];
                    name = data["name"];
                    customer_code = data["code"];
                    businessName = data["businessName"];
                    // email = data["email"];
                    // address = data["address"];
                    //businessAddress = data["businessAddress"];
                    area = data["area"];
                    //district = data["district"];
                    //openingBalance = data["openingBalance"];
                    //currentBalance = data["totalBalance"]; 
                    
                    $('#cusCode').html(customer_code);
                    $('#cusId').val(customerID);
                    $('#cusName').html(name);
                    $('#businessName').html(businessName);
                    $('#area').html(area);
                }
            });
            text =$('select[name=customer_id]').val();
            if( text.length > 0 ) {
                $('#cusinfo').show();    
                $('#transactions').load('<?=site_url('item/ajax_itemlist/')?>');
                $('#deliveryType').show();
            
            }
        });
    <?}?>
$('#formJournal').submit(function() {
    <? $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'out' ) { ?>
        var delivery = $('select[name=deliveryType]').val();
        if( delivery == " " ){
            alert('Select delivery type');
            return false;
        }
<? } ?>
    cus_ID = parseInt($('#cusId').val());
    sup_ID = parseInt($('#supId').val());
   
    if(isNaN(sup_ID)){
        sup_ID = 0;
    }
    if(isNaN(cus_ID)){
        cus_ID  = 0;
    }
    date = $('input[name=date]').val();
    phone = $('input[name=phone]').val();
    customer = $('input[name=customer]').val();
    description = $('input[name=description]').val();
    labourCost = $('input[name=labourCost]').val();
   
    var journalId = '<?=$this->uri->segment(4)?>';

    
<? $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'out' ) { ?>
    $.ajax({
        type: 'POST',
        url: '<?=site_url('item/getstockdata')?>'+'/'+journalId+'/'+labourCost+'/'+totalDiscount+'/',
        async: false,
        success: function(data) {
            var win=window.open();
            win.focus();
            win.document.write(data)
            win.print();
            win.close();
        }
    });
    <? } ?>  
    
    $.ajax({
        type: "POST",
        dataType: "json",
        url: $('#formJournal').attr('action'),
        data:{ date: date, customer_id:cus_ID, supplier_id:sup_ID, phone:phone, customer:customer, description:description, totalDiscount:totalDiscount, labourCost:labourCost},
        success: function(data){
            if( typeof data['error'] !== 'undefined' ){
               $('.error').html(data['error']).slideDown();
            }else{
            <? if( $this->uri->segment(2) == 'out' ) { ?>
             window.location = '<?=site_url('item/out/insert');?>';
            <? } else if($this->uri->segment(2) == 'in') {?>   
            
            window.location = '<?=site_url('item/in/insert');?>';
            <? } ?>
           }
       }
    });
    return false;
});
</script>