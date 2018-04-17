<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
      <div class="col-xs-12 text-left">
      <button type="submit" name="submit" class="btn btn-primary">Submit</button>
      </div>
    </div>




   
  </form>
</div>

</body>
</html>