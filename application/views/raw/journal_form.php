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
        <div id="transactions"></div>
    </div>
    <div class="panel-footer">
        <div class="btn-group pull-right">
            <?=anchor($this->uri->segment(1).'/'.$this->uri->segment(2),'Cancel','class="btn btn-default"');?>
            <a href="#" onclick="$('#formJournal').submit();" class="btn btn-primary">Save</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<script>
$.ajaxSetup({ cache: false });
$('#transactions').load('<?=site_url('raw/ajax_itemlist_'.$this->uri->segment(2))?>');

$('#formJournal').submit(function() {
    $.ajax({
       type: "POST",
       dataType: "json",
       url: $('#formJournal').attr('action'),
       data: $('#formJournal').serialize(),
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