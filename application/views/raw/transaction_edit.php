<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">
            <i class="fa fa-bar-chart-o"></i>
        </h4>

    </div>
    <?=$form_open?>
    <div class="modal-body">
        <div class="error alert alert-danger" role="alert" style="display:none"></div>
            <div class="row">
                <?php foreach($inputs as $input){
                    if($input['label'] != ''){?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?=$input['label']?> :</label>
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <?=$submit?>
    </div>
    <?=$form_close?>
</div>
<script>
<?php // Bind this on show, to trigger it each time a modal shows. ?>
$('.modal').on('shown.bs.modal', function() {
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
                       reload_data();
                   }
               }
             });
        return false;
    });
})
</script>