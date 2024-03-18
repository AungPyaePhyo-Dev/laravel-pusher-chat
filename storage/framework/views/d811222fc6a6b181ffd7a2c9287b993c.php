<?php $__env->startSection('content'); ?>
<home :user="<?php echo e(Session::get('user')); ?>" :token=`<?php echo e(Session::get('token')); ?>` />
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aung/Project/Practice/laravel-pusher-chat/resources/views/home.blade.php ENDPATH**/ ?>