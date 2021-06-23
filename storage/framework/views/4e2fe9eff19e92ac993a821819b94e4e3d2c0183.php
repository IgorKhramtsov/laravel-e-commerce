<?php $__env->startSection("content"); ?>
    <product :product="<?php echo e($product); ?>"></product>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layouts.app", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>