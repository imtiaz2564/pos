<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?=$title?></title>

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
    <div id="loader"></div>
    <div id="wrapper">
        <?php $this->load->view('widgets/sidebar'); ?>
        <div id="page-wrapper">
            <div class="container container-fluid">
                <h1><?=$title?></h1>
                <?=$content?>
            </div>
        </div>
    </div>
    <?php
    // General JS
    foreach($this->config->item('assets')['js'] as $file){
        echo '<script src="'.base_url().'assets/js/'.$file.'.js"></script>';
    }
    // JS on demand
    if(isset($js)){
        foreach($js as $file){
            echo '<script src="'.base_url().'assets/js/'.$file.'.js"></script>';
        }
    }

    ?>
</body>
</html>