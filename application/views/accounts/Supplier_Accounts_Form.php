<?=$error;?>
<?=$this->session->flashdata('');?>
<div class="panel panel-white">
    <div class="panel-heading">
        <i class="fa fa-bar-chart-o"></i>
    </div>
    <div class="panel-body">
        <?=$form_open?>
        <div class="row">
            <?php foreach( $inputs as $input ) { 
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
        <? if( $this->uri->segment(2) == 'payments' ) { ?>   
                    <!-- <div class="col-md-6">                         -->
                        <!-- <div class="form-group">
                            <label>Supplier ID :</label>
                            <input type="text" name="peopleID" value="" class="form-control" placeholder="Supplier ID" />
                        </div> -->
                        <!-- <div class="form-group">
                            <label>Supplier Name :</label>
                            <input type="text" name="name" value="" class="form-control" placeholder="Supplier Name" />
                        </div>
                        <div class="form-group">
                            <label>Phone :</label>
                            <input type="text" name="phone" value="" class="form-control" placeholder="Phone" />
                        </div>
                    </div> -->
            <? } ?>
    <!-- </div> -->
    <div><h1>Supplier Info</h1></div>
    <div>
        <div>
            <label>Supplier ID: </label>
            <label style="color:#0000FF" id="supID"></label>
        </div>
        <div>
            <label>Business Name: </label>
            <label style="color:#0000FF" id="businessName"></label>
        </div>
        <div>
            <label>Supplier Name( Supplier ): </label>
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
            <label style="color:#0000FF" id="currentBalance"></label>
        </div>
        <div>
            <input id="pplID" name="pplID" type="hidden" value="">
        </div>
        <div class="panel-footer">
            <div class="btn-group pull-right">
                <?//=anchor($this->uri->segment(1).'/'.$this->uri->segment(2),'Cancel','class="btn btn-default"');?>
                <a href="#" onclick="$('#supplierAccounts').submit();" class="btn btn-primary">Save</a>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
<script>
    $.ajaxSetup({ cache: false });
        // $('input[name=peopleID],input[name=phone],input[name=name]').keypress(function(e) {
        // if(e.which == 13) {
        $('select[name=peopleID]').change(function() {    
        $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '<?=site_url('item/getSupplierData/')?>'+'/'+$(this).val()+'/', 
        success: function (data) {
            id = data["id"];
            phone = data["phone"];
            name = data["name"];
            supplier_id = data["code"];
            businessName = data["businessName"];
            
            businessAddress = data["businessAddress"];
            area = data["area"];
            district = data["district"];
            totalBalance = data["totalBalance"];
             
             
            $('input[name=peopleID]').val(supplier_id);
            $('input[name=phone]').val(phone);
            $('input[name=name]').val(name);

            $('#pplID').val(id);
            $('#supID').html(supplier_id);
            $('#cusName').html(name);
            $('#businessName').html(businessName);
            
            $('#businessAddress').html(businessAddress);
            $('#area').html(area);
            $('#district').html(district);
            $('#currentBalance').html(totalBalance);
        }
    });
    //}
});
 $('#supplierAccounts').submit(function() {
    // name = $('input[name=name]').val();
    // phone = $('input[name=phone]').val();
    date = $('input[name=date]').val();
    amount = $('input[name=amount]').val();
    paymentType = $('select[name=paymentType]').val();
    bankAccount = $('select[name=bankAccount]').val();
    if(paymentType == '3'){
        alert("Select Payment Type");
        return false;
    }
    if( paymentType == 1 && bankAccount == 0 ){
        alert('Select Bank Account');
        return false;
    }
    detail = $('input[name=description]').val();
    ppl_ID = parseInt($('#pplID').val());
    type =  $('input[name=type]').val();
    user =  $('input[name=user]').val();

    $.ajax({
        type: "POST",
        dataType: "json",
        url: $('#supplierAccounts').attr('action'),
        //data: $('#supplierAccounts').serialize(),
        data: { date:date , amount:amount , paymentType:paymentType , description:detail , peopleID:ppl_ID ,bankAccount:bankAccount, type:type , user:user },
        success: function(data){
            if( typeof data['error'] !== 'undefined' ){
                $('.error').html(data['error']).slideDown();
            }else{
                window.location = '<?=site_url('finance/payments/insert');?>';
            }
        }
    });
    return false;
});

</script>