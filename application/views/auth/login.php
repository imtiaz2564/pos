<!DOCTYPE html>
<html>
<head>
    <title><?=$title?></title>

    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta charset="UTF-8">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
    <script src="<?=base_url()?>assets/js/jquery.min.js"></script>
    <?php
    // General CSS
    foreach($this->config->item('assets')['css'] as $file){
        echo link_tag(base_url().'assets/css/'.$file.'.css');
    }
    // CSS on demand
    if(isset($css)){
        foreach($css as $file){
            echo link_tag(base_url().'assets/css/'.$file.'.css');
        }
    }
    ?>
</head>
<body class="page-login">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="login-box panel panel-white">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="<?=base_url('assets/images/logo.jpg')?>" class="img img-responsive" />
                            </div>
                            <div class="col-md-6">
                                <div class="error alert alert-danger" role="alert" style="display:none"></div>

                                <?php echo form_open("auth/login");?>
                                    <div class="form-group">
                                <?php echo form_input(array_merge(['class'=>'form-control','placeholder'=>'Email'],$identity));?>
                                    </div>
                                    <div class="form-group">
                                <?php echo form_input(array_merge(['class'=>'form-control','placeholder'=>'Password'],$password));?>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <div class="checker"><span><input type="checkbox"></span></div> Remember me
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block">Login</button>
                                    <a href="#" class="btn btn-default btn-block m-t-md">Forgot your password?</a>
                                <?php echo form_close();?>
                                <div class="btn-group" role="group">
                                    <a href="#" class="btn btn-facebook"><i class="fa fa-facebook"></i></a>
                                    <a href="#" class="btn btn-twitter"><i class="fa fa-twitter"></i></a>
                                    <a href="#" class="btn btn-google"><i class="fa fa-google-plus"></i></a>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
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
                         location.replace("<?=site_url();?>");
                        
                       //location.replace("<?//=site_url('auth/otp');?>");
                   }
               }
             });

        return false;
    });
    </script>
</body>
</html>