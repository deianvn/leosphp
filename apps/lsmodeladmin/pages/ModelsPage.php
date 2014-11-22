<!doctype html>
<html lang="<?php echo wget(LOCALE); ?>">
    <head>
        <title><?php $this->ll('Title'); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL . '/' . wget('App:Name'); ?>/styles/style.css" />
    </head>
    <body>
        <div class="Page">
<?php $this->snippet('ModelsList'); ?>
        </div>
    </body>
</html>