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
        </tr>
    </table>
<?=$form_close?>
<script>
<?php // Bind this on show, to trigger it each time a modal shows. ?>
initialize();
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
    $('input[name=item_id]').val('');
    $('input[name=quantity]').val('');
}    
</script>