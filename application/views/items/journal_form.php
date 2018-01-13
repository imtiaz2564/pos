<?=$error;?>
<?=$this->session->flashdata('');?>
<div class="panel panel-white">
    <div class="panel-heading">
        <i class="fa fa-bar-chart-o"></i>
    </div>
    <div class="panel-body">
        <?=$form_open?>
        <div class="row">
                <?php foreach($inputs as $input){
                    ?><div class="col-md-6"><?php
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
    <? $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'out' ) { ?>
    <div><h1>Customer Info</h1></div>
    <div>
    <label>Customer Name: </label>
    <label style="color:#0000FF" id="cusName"></label>
    </div>
    <div>
    <div>
    <label>Business Name: </label>
    <label style="color:#0000FF" id="businessName"></label>
    </div>
    <label>Customer Email: </label>
    <label style="color:#0000FF" id="email"></label>
    </div>
    <div>
    <label>Address: </label>
    <label style="color:#0000FF" id="address"></label>
    </div>
    <div>
    <label>Business Address: </label>
    <label style="color:#0000FF" id="businessAddress"></label>
    </div>
    <div>
    <label>Area: </label>
    <label style="color:#0000FF" id="area"></label>
    </div>
    <div>
    <label>District: </label>
    <label style="color:#0000FF" id="district"></label>
    </div>
    <div>
    <label>Opening Balance: </label>
    <label style="color:#0000FF" id="openingBalance"></label>
    <div>
    <label>Current Balance: </label>
    <label style="color:#0000FF" id="currentBalance"></label>
    </div>
    <? } ?>
    <div id="transactions"></div>
    <? $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'out' ) { ?>
    
    <div class="form-group" style='float: right;'>
        <label>Total Discount: </label>
        <label><input type="text" name="totalDiscount" value="" class="form-control" placeholder="Total Discount"></label>
    </div>
    <div class="form-group" style='float: right;'>
        <label>Labour Cost: </label>
        <label><input type="text" name="labourCost" value="" class="form-control" placeholder="Labour Cost"></label>
    </div>
    <!-- <div class="form-group" style='float: right;'>
        <label>Delivery Type: </label>
        <label><select></select></label>
    </div> -->
    <div  class="form-group" style='float: right;'>
    <label>Delivery Type: </label> 
    <select name = "deliveryType" >
        <option value="0">None</option>
        <option value="truck">Truck</option>
        <option value="thela">Thela</option>
    </select>
  </div>

  
    <? } ?>
    <!-- <div class="form-group" style='float: right;'>
        <label>Grand Total: </label>
        <label></label>
    </div> -->
    </div>
    </div>
    <div class="panel-footer">
        <div class="btn-group pull-right">
            <?//=anchor($this->uri->segment(1).'/'.$this->uri->segment(2),'Cancel','class="btn btn-default"');?>
            <a href="<?=site_url()?>" class="btn btn-default">Close</a>
            <a href="#" onclick="$('#formJournal').submit();" class="btn btn-primary">Save</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<script>
// $(function(){

var journalId = '<?=$this->uri->segment(4)?>';
$('select[name=deliveryType]').on('change', function(){
delivery = $('select[name=deliveryType]').val();
//console.log(delivery);
$.ajax({
            type: 'GET',
            dataType: 'json',
            url: '<?=site_url('item/getdeliverytype')?>'+'/'+$(this).val()+'/'+journalId+'/', 
            success: function (data) {
                var deliveryCost = data['deliveryCost'];
                //console.log(deliveryCost);
                $('input[name=labourCost]').val(deliveryCost);
            }
        });

});
// });
$.ajaxSetup({ cache: false });
$('#transactions').load('<?=site_url('item/ajax_itemlist/')?>');
var journalId = '<?=$this->uri->segment(4)?>';
$('#result').load('<?=site_url('item/getStockData')?>'+'/'+journalId+'/')




$('input[name=customer_id],input[name=phone],input[name=customer]').keypress(function(e) {
    if(e.which == 13) {
        $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '<?=site_url('item/getCustomerData/')?>'+'/'+$(this).val()+'/', 
        success: function (data) {
             phone = data["phone"];
             name = data["name"];
             customer_id = data["code"];
             businessName = data["businessName"];
             email = data["email"];
             address = data["address"];
             businessAddress = data["businessAddress"];
             area = data["area"];
             district = data["district"];
             openingBalance = data["openingBalance"];
             currentBalance = data["totalBalance"]; 

             $('input[name=customer_id]').val(customer_id);
             $('input[name=phone]').val(phone);
             $('input[name=customer]').val(name);

             $('#cusName').html(name);
             $('#businessName').html(businessName);
             $('#email').html(email);
             $('#address').html(address);
             $('#businessAddress').html(businessAddress);
             $('#area').html(area);
             $('#district').html(district);
             $('#openingBalance').html(openingBalance);
             $('#currentBalance').html(currentBalance);
            }
        });
    }
});
$('#formJournal').submit(function() {
    date = $('input[name=date]').val();
    customer_id = $('input[name=customer_id]').val();
    phone = $('input[name=phone]').val();
    customer = $('input[name=customer]').val();
    description = $('input[name=description]').val();
    labourCost = $('input[name=labourCost]').val();; 
    totalDiscount = $('input[name=totalDiscount]').val();
// console.log(labourCost);
    //var journalId = '<?//=$this->uri->segment(4)?>';
    // $.ajax({
    //    type: 'GET',
    //    url: '<?//=site_url('item/getstockdata')?>'+'/'+journalId+'/',
    //    success: function(data){
    //        var win=window.open();
    //        win.document.write(data)
    //        win.print();
    //        win.close();
    //    }
    //  });
    $.ajax({
       type: "POST",
       dataType: "json",
       url: $('#formJournal').attr('action'),
        data:{ date: date, customer_id:customer_id, phone:phone, customer:customer, description:description, totalDiscount:totalDiscount, labourCost:labourCost},
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