<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
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
<body>
<div class="container">
  <form method="post" action="<?=site_url('auth/verifyotp');?>">
    <div class="col-md-3">
      <div class="form-group">
        <label for="otp">INSERT OTP:</label>
        <input type="text" class="form-control" id="text" placeholder="Enter code" name="otpvalue">
      </div>
    </div>
    <div class="row">
    <div class="col-xs-12">
      <button type="submit" name="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </form>
</div>
</body>
</html>
<script>
    (function() {
      history.pushState(null, null, location.href);
        window.onpopstate = function () {
        history.go(1);
    };
    });
</script>