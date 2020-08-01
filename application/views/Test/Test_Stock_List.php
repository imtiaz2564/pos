<?
// foreach($rows as $row){
// // print_r($row);
// echo $row['Parent'];
// }
?>

<div class="title"></div>
<?php //extract($data); ?>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-header">
                <div class="btn-group">
                    <a href="#" onclick="return openAll();" class="btn btn-light"><i class="fa fa-folder-open-o"></i>Expand All</a>
                    <a href="#" onclick="return collapseAll()" class="btn btn-light"><i class="fa fa-folder-o"></i>Collapse All</a>
                </div>
            </div>
            <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                        <div id="alert" class="alert" role="alert" style="display:none"></div>
                <div id="jstree" class="col-md-9">
                <!-- <div class="loader"></div> -->
                
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>        
</div>

<!-- <script src="dist/jstree.min.js"></script> -->


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script>
$.ajaxSetup({ cache: false });
$(document).ready(function(){    
    $('body').on('hidden.bs.modal', '.modal', function () {
      $(this).removeData('bs.modal');
    });
    $('#jstree').jstree({ 'core' : {
        'data' : [
            <?php
            foreach($rows as $row){
                $icon = '"icon": "fa fa-folder"';
                $parent = $row['Parent'];
                $state = '"state":{"selected":false}}';
                
                if( $row['Parent'] == 0 ){
                    $parent = '#';
                }
                // if($row['Type'] == 'Asset'){
                //     $icon = '"icon": "fa fa-file-text-o"';
                // }
                // if($row['id'] == $this->config['node']){
                //     $state = '"state":{"selected":true}}';
                // }
                //$state = '"state":{"selected":true}}';
                echo '{ "id" : "'.$row['id'].'", "parent" : "'.$parent.'", "text" : "'.$row['Name'].'<span class=\"actions\"></span>", '.$icon.','.$state.','."\n";
            }
            ?>
        ]
    }
    });
    
    $('#jstree').on("changed.jstree", function (e, data) {
        $('.coa-btn-group').remove();
        showOptions(data.selected);
    });
});
</script>