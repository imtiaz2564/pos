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
 
    <div><h1>Customer Info</h1></div>
    <div>
    <label>Customer Name: </label>
    <label style="color:#0000FF" id="cusName"></label>
    </div>
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
    </div>
    <div class="panel-footer">
        <div class="btn-group pull-right">
            <?=anchor($this->uri->segment(1).'/'.$this->uri->segment(2),'Cancel','class="btn btn-default"');?>
            <a href="#" onclick="$('#customerAccounts').submit();" class="btn btn-primary">Save</a>
        </div>
        <div class="clearfix"></div>
    </div>
    </div>
    </div>
   <script>
    $('input[name=peopleID],input[name=phone],input[name=name]').keypress(function(e) {
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

             $('input[name=peopleID]').val(customer_id);
             $('input[name=phone]').val(phone);
             $('input[name=name]').val(name);

             $('#cusName').html(name);
             $('#businessName').html(businessName);
             $('#email').html(email);
             $('#address').html(address);
             $('#businessAddress').html(businessAddress);
             $('#area').html(area);
             $('#district').html(district);
             $('#openingBalance').html(openingBalance);
            }
        });
    }
});
$('#customerAccounts').submit(function() {
    
    $.ajax({
       type: "POST",
       dataType: "json",
       url: $('#customerAccounts').attr('action'),
       data: $('#customerAccounts').serialize(),
       success: function(data){
           if( typeof data['error'] !== 'undefined' ){
               $('.error').html(data['error']).slideDown();
           }else{
               window.location = '<?=site_url($this->uri->segment(1).'/'.$this->uri->segment(2));?>';
           }
       }
     });
    return false;
});
</script>