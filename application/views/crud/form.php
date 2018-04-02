<?=$error;?>
<?=$form_open?>
<?php foreach($inputs as $input){
    if($input['label'] !=''){?>
    <div class="form-group">
        <label><?=$input['label']?> :</span>
        <?=$input['html']?>
    </div>
<?php }else{
        echo $input['html'];
      }
}?>
<div class="btn-group pull-right">
    <?=anchor($this->uri->segment(1).'/'.$this->uri->segment(2),'Cancel','class="btn btn-default"');?>
    <?=$submit?>
</div>
<?=$form_close?>
