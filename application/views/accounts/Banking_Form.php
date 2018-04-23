<?=$error;?>
<?=$this->session->flashdata('');?>
<div class="panel panel-white">
    <div class="panel-header">
        <h4 class="panel-title"></h4>
    </div>
    <?=$form_open?>
    <div class="panel-body">
        <div class="row">
           <div class="error alert alert-danger" role="alert" style="display:none"></div>
            <?php foreach($inputs as $input){
            if($input['label'] != ''){?>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-addon">
                    <?=$input['label']?> :</span>
                    <?=$input['html']?>
                </div>
            </div>
        <?php }else{
                echo $input['html'];
            }
        }?>
    </div>
    </div>    
    <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
        <?=$submit?>
    </div>
    <?=$form_close?>
</div>
<script>

$(function(){
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
               }
           }
         });
    return false;
});
}); 
</script>