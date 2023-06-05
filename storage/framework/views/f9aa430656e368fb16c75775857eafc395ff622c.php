

<script src="<?php echo e(asset('js/app.js')); ?>"></script>

<?php $__env->startSection('content'); ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <h1>WelCome To Steel24</h1>
             <div id="msg"></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<script>
    var user_id = 2;
    console.log('user',user_id);
    window.Echo.channel('live_lots').listen('MessageEvent', function(e) {
                console.log("listan",e);
                $('#msg').html(e.message);
    });

</script>

<?php echo $__env->make('admin.layouts.main',['activePage' => 'dashboard', 'titlePage' => __('Dashboard')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steel24\resources\views/admin/home.blade.php ENDPATH**/ ?>