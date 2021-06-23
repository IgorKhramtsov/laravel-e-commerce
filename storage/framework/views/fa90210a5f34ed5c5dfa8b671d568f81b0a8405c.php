<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">



    <?php if(auth()->guard()->check()): ?>
    <script> window.Laravel = <?php echo json_encode(['api_token' => Auth::user()->api_token]) ?> </script>
    <?php endif; ?>
    <?php if(auth()->guard()->guest()): ?>
        <script> window.Laravel = <?php echo json_encode(['api_token' => '']) ?> </script>
    <?php endif; ?>

    <?php echo $__env->yieldContent('js-data'); ?>

    <script src="<?php echo e(mix('js/app.js')); ?>" defer></script>
    <link href="<?php echo e(mix('css/app.css')); ?>" rel="stylesheet">
</head>
<body>
    <div id="app" >
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="<?php echo e(url('/')); ?>"> <?php echo e(config('app.name', 'Laravel')); ?> </a>
                <?php if(auth()->guard()->guest()): ?>
                    <button class="button text">
                        <a href="<?php echo e(route('login')); ?>"><i class="fa fa-sign-in"></i></a>
                    </button>
                <?php endif; ?>
                <?php if(auth()->guard()->check()): ?>
                    <form action="<?php echo e(route('logout')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <button class="button text"><i class="fa fa-sign-out"></i></button>
                    </form>
                        <?php if(Auth::user()->isAdmin()): ?>
                            <button class="button text">
                                <a href="<?php echo e(route('admin')); ?>"><i class="fa fa-cogs"></i></a>
                            </button>
                        <?php endif; ?>
                <?php endif; ?>
                <?php if(!(Request::is('cart/*') | Request::is('cart'))): ?>
                    <cart-nav-icon  :url="'<?php echo e(route('cart')); ?>'"></cart-nav-icon>
                <?php endif; ?>
            </div>
        </nav>
            <?php echo $__env->yieldContent('content'); ?>
    </div>
</body>
</html>
