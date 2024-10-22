<?=$error;?>
<?=$this->session->flashdata('');?>
<div class="panel panel-white">
    <div class="panel-heading">
        <i class="fa fa-bar-chart-o"></i>
    </div>
    <div class="panel-body">
        <?=$form_open?>
        <div class="row">
        <? if( $this->uri->segment(2) == 'receives' ) { ?>   
            <!-- <div class="col-md-6">                        
                <div class="form-group">
                    <label>Customer ID :</label>
                    <input type="text" name="peopleID" value="" class="form-control" placeholder="Customer ID" />
                </div>
            </div> -->
            <? } ?>
                <?php foreach($inputs as $input){
                    ?><div class="col-md-3"><?php
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
        <label>Customer ID: </label>
        <label style="color:#0000FF" id="cusID"></label>
    </div>
    <div>
        <label>Party( Business Name ): </label>
        <label style="color:#0000FF" id="businessName"></label>
    </div>
    <div>
        <label>Customer Name: </label>
        <label style="color:#0000FF" id="cusName"></label>
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
        <label>Current Balance: </label>
        <label style="color:#0000FF" id="totalBalance"></label>
    </div>
    <div>
        <input id="pplID" name="pplID" type="hidden" value="">
    </div>
    <div class="panel-footer">
        <div class="btn-group pull-right">
            <?//=anchor($this->uri->segment(1).'/'.$this->uri->segment(2),'Cancel','class="btn btn-default"');?>
            <a href="#" onclick="$('#customerAccounts').submit();" class="btn btn-primary">Save</a>
        </div>
        <div class="clearfix"></div>
    </div>
    </div>
    <script>
        $.ajaxSetup({ cache: false });
        var businessId = 0;
        // $('input[name=peopleID],input[name=phone],input[name=name]').keypress(function(e) {
        // if(e.which == 13) {
        $('select[name=peopleID]').change(function() {    
            businessId = $('select[name=peopleID]').val();
            $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?=site_url('item/getCustomerData/')?>'+'/'+$(this).val()+'/', 
            success: function (data) {
                id = data["id"];
                phone = data["phone"];
                name = data["name"];
                customer_id = data["code"];
                businessName = data["businessName"];
                businessAddress = data["businessAddress"];
                area = data["area"];
                district = data["district"];
                totalBalance = data["totalBalance"];

                $('input[name=peopleID]').val(customer_id);
                $('input[name=phone]').val(phone);
                $('input[name=name]').val(name);
                $('#pplID').val(id);
                $('#cusID').html(customer_id);
                $('#cusName').html(name);
                $('#businessName').html(businessName);
                $('#businessAddress').html(businessAddress);
                $('#area').html(area);
                $('#district').html(district);
                $('#totalBalance').html(totalBalance);
                }
            });
    //}
    });
$('#customerAccounts').submit(function() {
    if(businessId == 0){
        alert('Select Business Name');
        return false;
    }
    name = $('input[name=name]').val();
    phone = $('input[name=phone]').val();
    date = $('input[name=date]').val();
    amount = $('input[name=amount]').val();
    paymentType = $('select[name=paymentType]').val();
    bankAccount = $('select[name=bankAccount]').val();
    type = $('input[name=type]').val();
    user = $('input[name=user]').val();
    if( paymentType == '3'){
        alert('Select Payment Type');
        return false;
    }
    if( paymentType == 1 && bankAccount == 0 ){
        alert('Select Bank Account');
        return false;
    }
    description = $('input[name=description]').val(); 
    if( amount == "" ){
        alert('Insert Amount');
        return false;
    }
    if( description == "" ){
        alert('Insert Description');
        return false;
    }
    ppl_ID = parseInt($('#pplID').val());
    $.ajax({
       type: "POST",
       dataType: "json",
       url: $('#customerAccounts').attr('action'),
       //data: $('#customerAccounts').serialize(),
       data: {  date:date , amount:amount , paymentType:paymentType , description:description , bankAccount:bankAccount, peopleID:ppl_ID , user:user , type:type},
       success: function(data){
        if( typeof data['error'] !== 'undefined' ){
            $('.error').html(data['error']).slideDown();
        }else{
            window.location = '<?=site_url('finance/receives/insert');?>';
        }
    }
     });
    return false;
});
</script>