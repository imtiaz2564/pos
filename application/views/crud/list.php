<?php if(! $hide_controls){?>
    <div class="btn-group">
        <?=get_insert_button($modal)?>
        <?=get_edit_button('',$modal)?>
        <?=get_delete_button('',$modal)?>
        <?=get_view_button('',$modal)?>
        <?php
        if(isset($extraButtons)){
            foreach($extraButtons as $tool){
                $toolClass = '';
                $toolAttr = '';
                $iconColor = '';
                if(array_key_exists('class',$tool)){
                    $toolClass = ' '.$tool['class'];
                }
                if(array_key_exists('id',$tool)){
                    $toolAttr .= ' id="'.$tool['id'].'"';
                }
                if(array_key_exists('onclick',$tool)){
                    $toolAttr .= ' onclick="'.$tool['onclick'].'"';
                }
                if(array_key_exists('color',$tool)){
                    $iconColor = ' '.$tool['color'];
                }
                ?>
                <a href="<?=site_url($tool['href'])?>" class="btn btn-default btn-sm<?=$toolClass?>"<?=$toolAttr?>>
                    <i class="fa <?=$tool['icon'].$iconColor?>"></i> <?=$tool['title']?>
                </a>
                <?php
            }
        }
        ?>
    </div>
    <br /><br />
<?php } ?>
<table class="display compact" id="crud-table">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <?php foreach($labels as $label){
                ?><th><?=$label?></th><?php
            }
            ?>
        </tr>
    </thead>
    <tfoot></tfoot>
</table>
<script>
$.ajaxSetup({ cache: false });

$(document).ready(function() {
    <?php // destroy modal?>
    
    $('body').on('hidden.bs.modal', '.modal', function () {
      $(this).removeData('bs.modal');
    });
    
    $('#crud-table').dataTable( {
        "processing": true,
        "serverSide": true,
        "columnDefs":[{
                "targets": [ 0 ],
                "visible": false
            }],
        "fnDrawCallback" : function(){
            $('#crud-table tr').click( function () {
                $('#crud-table tr').removeClass('selected');
                $(this).addClass('selected');
                var datatable = $('#crud-table').dataTable();
                var data = datatable.fnGetData( this );
                if(data!==null)
                $('#crud-edit').prop('href','<?=get_edit_url('')?>/'+data[0]);
                $('#crud-delete').on('click',function() {
                if (confirm("Are you sure you want to delete this?") == true) {
                    $('#crud-delete').prop('href','<?=get_delete_url('')?>/'+data[0]);
                }
                });
                $('#crud-view').prop('href','<?=get_view_url('')?>/'+data[0]);
            });
        },
        "ajax": "<?=site_url($this->uri->segment(1).'/'.$this->uri->segment(2).'/ajax/');?>"
    });
});
function reload_data(){
    $('#crudModal').modal('toggle');
    var table = $('#crud-table').DataTable(); <?php // Yes it's capital "D" ?>
    table.ajax.reload();
}
</script>
<?php if($modal == true ){ ?>
<div class="modal fade" id="crudModal" tabindex="-1" role="dialog" aria-labelledby="crudModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title">Loading...</h4>

            </div>
            <div class="modal-body">Loading...</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">OK</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>