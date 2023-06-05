<?php echo $__env->make('admin.layouts.navbars.navs.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="wrapper wrapper-full-page">
  <div class="page-header login-page header-filter" filter-color="black" style="background-image: url('<?php echo e(asset('img/steel2.jpg')); ?>'); background-size: cover; background-position: top center;align-items: center;" data-color="purple">
  <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
    <?php echo $__env->yieldContent('content'); ?>
    <?php echo $__env->make('admin.layouts.footers.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>

</div>
<?php /**PATH C:\xampp\htdocs\steel24\resources\views/admin/layouts/page_templates/guest.blade.php ENDPATH**/ ?>