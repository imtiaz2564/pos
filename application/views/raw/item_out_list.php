<div class="panel panel-white">
	<div class="panel-heading">
        <?php if(! $hide_controls){ ?>
            <div class="btn-group pull-right">
                <?=get_insert_button($modal)?>
                <?=get_edit_button('',$modal)?>
            </div>
        <?php } ?>
		<div class="clearfix"></div>
	</div>
	<div class="panel-body">
        
        <div class="error alert alert-danger" role="alert" style="display:none"></div>

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
		</table>
        <div id="transactionRow" style="position:relative"></div>
	</div>
</div>
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
        "iDisplayLength": 100,
        "sDom": '<"top"flip>rt<"bottom"><"clear">',
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
            });
            $('#crud-table tr').dblclick( function () {
                alert('double-clicked');
            });
        },
        "ajax": "<?=site_url($this->uri->segment(1).'/'.$this->uri->segment(2).'/ajax/');?>"
    });
    initialize();
    
    $('#transactionRow').load('<?=site_url('raw/ajax_itemlist_out/insert')?>');
});
function reloadData(){
    var table = $('#crud-table').DataTable(); <?php // Yes it's capital "D" ?>
    table.ajax.reload();
}
function clearForm(){
    $('input[name:dr]').val('');
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