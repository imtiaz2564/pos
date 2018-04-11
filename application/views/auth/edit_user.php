<div class="panel panel-white">
      <div class="panel-heading">
            <i class="fa fa-bar-chart-o"></i>
      </div>
      <div class="panel-body">
      <h1><?php echo lang('edit_user_heading');?></h1>
      <p><?php echo lang('edit_user_subheading');?></p>
      <div id="infoMessage"><?php echo $message;?></div>
      <?php echo form_open(uri_string());?>
      <div class="row">
            <div class="error alert alert-danger" role="alert" style="display:none"></div>
            <div class="col-md-6">
                  <div class="input-group">
                        <span class="input-group-addon">
                              <? echo lang('edit_user_fname_label', 'first_name'); ?> :</span>
                              <? echo form_input($first_name); ?>
                        </div>
                  </div>
            <div class="col-md-6">
                  <div class="input-group">
                        <span class="input-group-addon">
                              <? echo lang('edit_user_lname_label', 'last_name'); ?> :</span>
                              <? echo form_input($last_name); ?>
                  </div>
            </div>
            <div class="col-md-6">
                  <div class="input-group">
                        <span class="input-group-addon">
                              <? echo lang('edit_user_company_label', 'company'); ?> :</span>
                              <? echo form_input($company); ?>
                  </div>
            </div>
            <div class="col-md-6">
                  <div class="input-group">
                        <span class="input-group-addon">
                        <? echo lang('edit_user_phone_label', 'phone'); ?> :</span>
                        <? echo form_input($phone); ?>
                  </div>
            </div>
            <div class="col-md-6">
                  <div class="input-group">
                        <span class="input-group-addon">
                        <? echo lang('edit_user_password_label', 'password'); ?> :</span>
                        <? echo form_input($password); ?>
                  </div>
            </div>
            <div class="col-md-6">
                  <div class="input-group">
                        <span class="input-group-addon">
                              <? echo lang('edit_user_password_confirm_label', 'password_confirm'); ?> :</span>
                              <? echo form_input($password_confirm); ?>
                  </div>
            </div>
            <div class="col-md-6">
                  <div class="input-group">
                        <?php if ($this->ion_auth->is_admin()): ?>
                        <h3><?php echo lang('edit_user_groups_heading');?></h3>
                        <?php foreach ($groups as $group):?>
                              <label class="checkbox">
                              <?php
                                    $gID=$group['id'];
                                    $checked = null;
                                    $item = null;
                                    foreach($currentGroups as $grp) {
                                          if ($gID == $grp->id) {
                                                $checked= ' checked="checked"';
                                          break;
                                          }
                                    }
                              ?>
                              <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
                              <?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
                              </label>
                              <?php endforeach?>
                              <?php endif ?>
                              <?php echo form_hidden('id', $user->id);?>
                              <?php echo form_hidden($csrf); ?>
                        </div>
                  </div>
            </div>
      </div>  
      <ul class="list-group" id="permission_list">
      <?php $accessCheck  = null;
            foreach($modules as $module){
                  if(in_array($module['id'],$privilege)){
                        $accessCheck  = TRUE;   
                  }
                  else{
                        $accessCheck  = FALSE;
                  }
      ?>
      <li>
            <?php echo form_checkbox("permissions[]",$module['id'],$accessCheck); ?>	
            <span class="medium"><?php echo $module['name'];?></span>
      </li>
      <?php
      }
      ?>
      </ul>
      <p><?php echo form_submit('submit', lang('edit_user_submit_btn'));?></p>
      <?php echo form_close();?>
</div>