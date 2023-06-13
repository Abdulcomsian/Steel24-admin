<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top text-white">
  <div class="container">
    <div class="navbar-wrapper">
      <a class="navbar-brand" href="<?php echo e(route('home')); ?>"></a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
      <span class="sr-only">Toggle navigation</span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav">
        
        <li class="nav-item<?php echo e($activePage == 'register' ? ' active' : ''); ?>" style="display: none">
          <a href="<?php echo e(route('admin.register')); ?>" class="nav-link">
            <i class="material-icons">person_add</i> <?php echo e(__('Register')); ?>

          </a>
        </li>
        <li class="nav-item<?php echo e($activePage == 'login' ? ' active' : ''); ?>">
          <a href="<?php echo e(route('admin.login')); ?>" class="nav-link">
            <i class="material-icons">fingerprint</i> <?php echo e(__('Login')); ?>

          </a>
        </li>
        
      </ul>
    </div>
  </div>
</nav>
<!-- End Navbar -->
<?php /**PATH C:\xampp\htdocs\Steel24-admin\resources\views/admin/layouts/navbars/navs/guest.blade.php ENDPATH**/ ?>