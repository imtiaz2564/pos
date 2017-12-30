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
        <td><input type="text" name="total" value="" class="form-control" placeholder="total"></td>
        </tr>
    </table>
    
<?=$form_close?>

<script>
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
            }
        });  

         });
         
        <?  } ?> 
$(function(){
    $('select[name=uom]').on('change', function() {
        $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '<?=site_url('item/getLabourCost')?>'+'/'+$(this).val()+'/', 
        success: function (data) {
            labourCost = parseInt(data["labourCost"]);
        }
        });
        });
       $('input[name=unit_price],input[name=quantity]').on('keyup',function(){
        //var unit_price = $('input[name=unit_price]').val(mrp);
        var quantity = $('input[name=quantity]').val();
        
        var total = (labourCost * quantity) + (mrp * quantity);
         
        $('input[name=total]').val(total);
    });
});

$('form').submit(function() {
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
    return false;
});
function clearFields(){
   // $('input[name=item_id]').val('');
    $('input[name=quantity]').val(' ');
    $('input[name=unit_price]').val(' ');
    $('input[name=total]').val(' ');
    $('input[name=item_name]').val(' ');
  //  $('input[name=date]').val(' ');
    $('input[name=uom]').val(' ');
}    
</script>