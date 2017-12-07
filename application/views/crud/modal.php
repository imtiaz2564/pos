<?=$data['formOpen']?>
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">
                <i class="fa <?=$this->config['modalIcon']?>"></i> <?=$this->config['formTitle']?>
            </h4>
        </div>
        <div class="modal-body">
            <?=isset( $this->config['prependForm']) ? $this->config['prependForm']:''?>
            <div class="row">
                <div class="col-md-12"><div id="alert-modal" class="alert" role="alert" style="display:none"></div></div>
            <?php foreach($data['inputs'] as $input){
                if($input['label'] != ''){?>
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon"><?=$input['label']?> :</span>
                        <?=$input['html']?>
                    </div>
                </div>
            <?php }else{
                    echo $input['html'];
                }
            }?>
            </div>
            <?=isset( $this->config['appendForm']) ? $this->config['appendForm']:''?>
        </div>
        <div class="modal-footer">
            <div class="btn-group">
                <a class="btn btn-default btn-xs" data-dismiss="modal">Close</a>
                <?=$data['submit']?>
            </div>
        </div>
    </div>
<?=$data['formClose']?>
<?=$data['modalScript']?>