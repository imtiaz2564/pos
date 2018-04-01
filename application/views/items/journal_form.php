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
            <?//$uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'in' ) { ?>   
            <!-- <div class="col-md-6">                        
                <div class="form-group">
                    <label>Supplier ID :</label>
                    <input type="text" name="idSupplier" id="idSupplier" value="" class="form-control" placeholder="Supplier ID" />
                </div>
                <div class="form-group">
                    <label>Supplier Name :</label>
                        <input type="text" name="customer" value="" class="form-control" placeholder="Supplier Name">
                </div>
            </div> -->
        <? //} ?>
        <!-- <div class="col-md-6">
        <div class="form-group">
            <label>Phone :</label>
            <input type="text" name="phone" value="" class="form-control" placeholder="Phone">
        </div>
        </div> --> 
    <? //$uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'out' ) { ?>
    <div class="row">
        <div  class= "input-group customerInfo" id = "cusinfo" style="">
            <div class= "input-group customerInfo"><h1>Customer Info</h1></div>
                <div class= "input-group customerInfo">
                    <label>Customer ID: </label>
                    <label style="color:#0000FF" id="cusCode"></label>
                </div>
                <div class= "input-group customerInfo">
                    <label>Business Name( Customer ): </label>
                    <label style="color:#0000FF" id="businessName"></label>
                </div>
                <div class= "input-group customerInfo">
                    <label>Customer Name / Contact Person: </label>
                    <label style="color:#0000FF" id="cusName"></label>
                </div>
                <!-- <div class= "input-group customerInfo">
                    <label>Customer Email: </label>
                    <label style="color:#0000FF" id="email"></label>
                </div>
                <div class= "input-group customerInfo">
                    <label>Address: </label>
                    <label style="color:#0000FF" id="address"></label>
                </div>
                <div class= "input-group customerInfo">
                    <label>Business Address: </label>
                    <label style="color:#0000FF" id="businessAddress"></label>
                </div> -->
                <div class= "input-group customerInfo">
                    <label>Area: </label>
                    <label style="color:#0000FF" id="area"></label>
                </div>
                <!-- <div class= "input-group customerInfo">
                    <label>District: </label>
                    <label style="color:#0000FF" id="district"></label>
                </div>
                <div class= "input-group customerInfo">
                    <label>Opening Balance: </label>
                    <label style="color:#0000FF" id="openingBalance"></label>
                <div class= "input-group customerInfo">
                    <label>Current Balance: </label>
                    <label style="color:#0000FF" id="currentBalance"></label>
                </div> -->
        </div>
    </div>
<? //} ?>
<?// $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'in' ) { ?>
    <!-- <div><h1>Supplier Info</h1></div>
    <div>
        <label>Supplier Name: </label>
        <label style="color:#0000FF" id="supName"></label>
    </div>
    <div>
    <div>
        <label>Business Name: </label>
        <label style="color:#0000FF" id="supBusinessName"></label>
    </div>
        <label>Supplier Email: </label>
        <label style="color:#0000FF" id="supEmail"></label>
    </div>
    <div>
        <label>Address: </label>
        <label style="color:#0000FF" id="supAddress"></label>
    </div>
    <div>
        <label>Business Address: </label>
        <label style="color:#0000FF" id="supBusinessAddress"></label>
    </div>
    <div>
        <label>Area: </label>
        <label style="color:#0000FF" id="supArea"></label>
    </div>
    <div>
        <label>District: </label>
        <label style="color:#0000FF" id="supDistrict"></label>
    </div>
    <div>
        <label>Opening Balance: </label>
        <label style="color:#0000FF" id="supOpeningBalance"></label>
    <div>
        <label>Current Balance: </label>
        <label style="color:#0000FF" id="supCurrentBalance"></label>
    </div> -->
<? //} ?>
    <div id="transactions"></div>
    <div class= "row">
    <!-- <div class="form-group" style='float: right;'>
        <label>Grand Total: </label>
        <label>     </label>
    </div> -->
    </div>
        <!-- <div class="form-group" style='float: right;'>
            <label>Labour Cost: </label>
            <label><input type="text" name="labourCost" value="" class="form-control" placeholder="Labour Cost"></label>
        </div> -->
    <? //$uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'out' ) { ?>
    <div  class="form-group delivery" id = "deliveryType" style='float: right;'>
        <label class="form-group delivery">Delivery Type: </label> 
        <select class="form-group delivery"  name = "deliveryType" >
            <option class="form-group delivery" value=" ">None</option>
            <option class="form-group delivery" value="truck">Truck</option>
            <option class="form-group delivery" value="thela">Thela</option>
        </select>
        <label class="form-group delivery">Labour Cost: </label>
        <label class="form-group delivery"><input type="text" name="labourCost" value="" class="form-control" placeholder="Labour Cost"></label>

    <!-- </div>
    <div class="form-group" style='float: right;'> -->
        <label class="form-group delivery">Final Discount: </label>
        <label class="form-group delivery"><input type="text" name="totalDiscount" value="" class="form-control" placeholder="Total Discount"></label>
    </div>
    <? //} ?>
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
    $(function(){
        $('#cusinfo').hide();
        $('#deliveryType').hide();
    });
var journalId = '<?=$this->uri->segment(4)?>';
// var delivery = $('select[name=deliveryType]').val();
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

});
$.ajaxSetup({ cache: false });

   // var idSupplier = 2 ;
    // idSupplier =  $('input[name=idSupplier]').val();
    // //alert(idSupplier);
    // if(idSupplier == undefined){
    //     //alert('Insert ID');
    //     idSupplier = 0;
    // }
   /// $('#transactions').load('<?//=site_url('item/ajax_itemlist')?>'+'/'+idSupplier+'/');
    
//    $('#transactions').load('<?//=site_url('item/ajax_itemlist/')?>');
//var journalId = '<?//=$this->uri->segment(4)?>';
//$('#result').load('<?//=site_url('item/getStockData')?>'+'/'+journalId+'/')

<? $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'in' ) { ?>
    $('#transactions').load('<?=site_url('item/ajax_itemlist/')?>');
// $('input[name=idSupplier],input[name=phone],input[name=customer]').keypress(function(e) {
   
//     if(e.which == 13) {
  
//         $.ajax({
//         type: 'POST',
//         dataType: 'json',
//         url: '<?//=site_url('item/getSupplierData/')?>'+'/'+$(this).val()+'/', 
//         success: function (data) {
//              supplierID = data["id"];
//              phone = data["phone"];
//              name = data["name"];
//              customer_id = data["code"];
//              businessName = data["businessName"];
//              email = data["email"];
//              address = data["address"];
//              businessAddress = data["businessAddress"];
//              area = data["area"];
//              district = data["district"];
//              openingBalance = data["openingBalance"];
//              currentBalance = data["totalBalance"]; 

//              $('input[name=idSupplier]').val(customer_id);
//              $('input[name=phone]').val(phone);
//              $('input[name=customer]').val(name);

//              $('#supId').val(supplierID);
//              $('#supName').html(name);
//              $('#supBusinessName').html(businessName);
//              $('#supEmail').html(email);
//              $('#supAddress').html(address);
//              $('#supBusinessAddress').html(businessAddress);
//              $('#supArea').html(area);
//              $('#supDistrict').html(district);
//              $('#supOpeningBalance').html(openingBalance);
//              $('#supCurrentBalance').html(currentBalance);
            
//             }
//         });
       
//     }
// });
<?}?>

<? $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'out' ) { ?>
   
// $('input[name=idCustomer],input[name=phone],input[name=customer]').keypress(function(e) {
    $('select[name=customer_id]').change(function() {         
    // if(e.which == 13) {
        $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '<?=site_url('item/getCustomerData/')?>'+'/'+$(this).val()+'/', 
        success: function (data) {
            customerID = data["id"]; 
            phone = data["phone"];
            name = data["name"];
            customer_code = data["code"];
            businessName = data["businessName"];
            email = data["email"];
            address = data["address"];
            businessAddress = data["businessAddress"];
            area = data["area"];
            district = data["district"];
            openingBalance = data["openingBalance"];
            currentBalance = data["totalBalance"]; 
            //$('input[name=idCustomer]').val(customer_code);
            // $('input[name=phone]').val(phone);
            // $('input[name=customer]').val(name);
            
            $('#cusCode').html(customer_code);
            $('#cusId').val(customerID);
            $('#cusName').html(name);
            $('#businessName').html(businessName);
           // $('#email').html(email);
            // $('#address').html(address);
            // $('#businessAddress').html(businessAddress);
            $('#area').html(area);
            // $('#district').html(district);
            // $('#openingBalance').html(openingBalance);
            // $('#currentBalance').html(currentBalance);
        }
    });
        
        
    // }
    text =$('select[name=customer_id]').val();
    if( text.length > 0 ) {
        $('#cusinfo').show();    
        $('#transactions').load('<?=site_url('item/ajax_itemlist/')?>');
        $('#deliveryType').show();
    }
    // $.ajax({
    //    type: 'POST',
    //    url: '<?//=site_url('item/getstockdata')?>'+'/'+journalId+'/'+labourCost+'/'+totalDiscount+'/',
    //    async: false,
    //    success: function(data) {
    //     $('.grandTotal').val(data['quantity']);
    //    }
    //  });
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
    //transportCost = $('input[name=transportCost]').val(); 
    totalDiscount = $('input[name=totalDiscount]').val();
    var journalId = '<?=$this->uri->segment(4)?>';
//    $.ajax({
//        type: 'POST',
//        url: '<?//=site_url('item/insertsupplierid')?>'+'/'+sup_ID+'/'+journalId+'/',
//        success: function(data) {
//         if( typeof data['error'] !== 'undefined' ){
//                    $('.error').html(data['error']).slideDown();
//                }else{
//                    reloadData();
//                }
//        }
//      });
    
<? $uri = $this->uri->segment(2); if( $this->uri->segment(2) == 'out' ) { ?>
    // var journalId = '<?//=$this->uri->segment(4)?>';
    $.ajax({
        type: 'POST',
        url: '<?=site_url('item/getstockdata')?>'+'/'+journalId+'/'+labourCost+'/'+totalDiscount+'/',
        async: false,
        success: function(data) {
            var win=window.open();
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