<?php
    $domain = base_url();
    
    $section = 'front';
    if (0 === strpos(uri_string(), 'admin')) {
        $section = 'admin';
    }
    $navConfig  = getNavConfig($section);
    $navigation = renderNav($navConfig);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf8">
        <title><?php isset($title) ? $title : 'EEFocus Tag Center'; ?></title>
        <script src="<?php echo $domain; ?>asset/js/jquery-2.1.3.min.js" type="text/javascript"></script>
        <link href="<?php echo $domain; ?>asset/vendor/bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet">
        <script src="<?php echo $domain; ?>asset/vendor/bootstrap/js/bootstrap.js" type="text/javascript"></script>
        <link href="<?php echo $domain; ?>asset/css/style.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <div class="toper-fixed-header">
            <div class="container">
                <div class="site-title">
                    <?php echo 'Tag Center'; ?>
                </div>
                <div class="account-info"></div>
                <?php
                    $CI = &get_instance();
                    $CI->load->helper('route');
                    $url = assembleUrl(array(
                        'section'    => 'user',
                        'controller' => 'login',
                        'action'     => 'state',
                    ));
                ?>
                <script>
                (function($) {
                    $.ajax({
                        cache:    false,
                        async:    false,
                        dataType: 'json',
                        type:     'get',
                        url:      '<?php echo $url; ?>',
                        success:  function (e) {
                            if (e.status) {
                                $(".toper-fixed-header").find(".account-info").html(e.data);
                            }
                        }
                    });
                })(jQuery);
                </script>
            </div>
        </div>
        <div class="container">
            
            <!-- Header -->
            <div class="header">
                
            </div>
            
            <!-- Navigation -->
            <div class="navigation">
                <?php echo $navigation; ?>
            </div>
            
            <div class="content">