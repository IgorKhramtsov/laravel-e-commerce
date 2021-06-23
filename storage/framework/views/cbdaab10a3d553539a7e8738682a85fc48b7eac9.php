<?php $__env->startSection('js-data'); ?>
    <script> window.Laravel.js_data = <?php echo json_encode($data) ?> </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <main-products></main-products>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>