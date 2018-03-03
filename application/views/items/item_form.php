<?=$this->session->flashdata('');?>
<?=$form_open?>
    <table class="table-inside">
        <tr>
            <?php foreach($inputs as $input){
                if($input['label'] != ''){?>
                <td><?=$input['html']?></td>
            <?php }else{
                    echo $input['html'];
                }
            }?>
            <input type="submit" style="display:none"/>
            <?php  if( $this->session->userdata('type') == 1 ) { ?>
                <td><input type="text" name="discount" value="" class="form-control" placeholder="Discount"></td>
            <? } ?>
            <?php  if( $this->session->userdata('type') == 0 ) { ?>
            <td> 
                <select name = "stockType" >
                    <option value="supplier">Supplier Stock</option>
                    <option value="sylhet">Sylhet Stock</option>
                </select>
            </td> 
            <? } ?> 
              
            <td><input type="text" name="total" value="" class="form-control" placeholder="total"></td>
        </tr>
    </table>
    
<?=$form_close?>
<div class="form-group" style='float: right;'>
        <label>Grand Total: </label>
        <label class="form-group delivery"><input type="text" style="color:#0000FF" name="grandTotal" value="" class="form-control" placeholder="Grand Total" readonly></label>
   
</div>
<script>

$(function(){
<?php   // Bind this on show, to trigger it each time a modal shows. ?>
initialize();
<?php  if( $this->session->userdata('type') == 1 ) { ?>  
         $('select[name=item_name]').on('change', function() {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?=site_url('item/getUnitPrice')?>'+'/'+$(this).val()+'/', 
                success: function (data) {
                mrp = parseInt(data["mrp"]);
                $('input[name=unit_price]').val(mrp);
                discount = parseInt(data["discount"]);
                }
            });  
        });
        var iSum = 0;
        $('input[name=unit_price],input[name=quantity]').on('keyup',function(){
            
            var quantity = $('input[name=quantity]').val();
            var total = mrp * quantity;
            var dis = discount * quantity;
            $('input[name=total]').val(total);
            $('input[name=discount]').val(dis);
    });
    $('input[name=total]').on('keyup',function(){
    $('input[name=total]').each( function() {
           iSum = iSum + parseFloat($(this).val());
    });
    $('input[name=grandTotal]').val(iSum);
    });
<?  } else { ?>
    var purchasetotal = 0;
    //var total = 0;
     var iSum = 0; 
    $('input[name=unit_price],input[name=quantity]').on('keyup',function(){
        var quantity = $('input[name=quantity]').val();
        var unit_price = $('input[name=unit_price]').val();
         total = unit_price * quantity;
        $('input[name=total]').val(total);
       
       
    });
    $('input[name=total]').on('keyup',function(){
    $('input[name=total]').each( function() {
           iSum = iSum + parseFloat($(this).val());
        });
        $('input[name=grandTotal]').val(iSum);
    });
    
<? } ?>
$('form').submit(function() {
    
  
    var stock = $('select[name=stockType]').val();
    
    $.ajax({
           type: "POST",
           dataType: "json",
           url: $(this).attr('action'),
           data: $(this).serialize(),
           success: function(data){
               if( typeof data['error'] !== 'undefined' ){
                   $('.error').html(data['error']).slideDown();
               }else{
                   reloadData();
                   clearFields();
               }
           }
         });
         if( stock == "sylhet") {
        item = $('select[name=item_name]').val();
        //alert(item);
        quantity = $('input[name=quantity]').val();
        $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '<?=site_url('item/localStockUpdate')?>'+'/'+item+'/'+quantity+'/', 
                success: function (data) {
                    if( typeof data['error'] !== 'undefined' ){
                    $('.error').html(data['error']).slideDown();
                    }else{
                        reloadData();
                        clearFields();
            
                    }
                }
            });        
    }
    return false;
});
});
function clearFields(){
    //$('input[name=grandTotal]').val(' ');
    $('select[name=warehouse]').val(' ');
    $('select[name=item_name]').val(' ');
    $('input[name=quantity]').val(' ');
    $('input[name=unit_price]').val(' ');
    $('input[name=total]').val(' ');
    $('input[name=item_name]').val(' ');
    $('input[name=uom]').val(' ');
    $('input[name=discount]').val(' ');
}    
</script>