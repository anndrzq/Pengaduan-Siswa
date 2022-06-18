<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title; ?></title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="<?= base_url(); ?>fonts/material-icon/css/material-design-iconic-font.min.css">

    <link href="<?= base_url(); ?>/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Main css -->
    <link rel="stylesheet" href="<?= base_url(); ?>/css/style.css">
</head>

<body>

    <div class="main">

        <!-- Content Section -->
        <?= $this->renderSection('content'); ?>

    </div>

    <!-- JS -->
    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url(); ?>/vendor/jquery/jquery.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url(); ?>js/main.js"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>