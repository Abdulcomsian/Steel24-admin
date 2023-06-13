<div class="sidebar" data-color="purple" data-image="<?php echo e(asset('img/sidebar-1.jpg')); ?>">
    <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
    <div class="logo">
        <a href="<?php echo e(url('admin')); ?>" class="simple-text logo-normal">
            <?php echo e(__('Steel24')); ?>

        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item<?php echo e($activePage == 'dashboard' ? ' active' : ''); ?>">

                <a class="nav-link" href="<?php echo e(url('admin')); ?>">
                    <i class="material-icons"></i>
                    <p><?php echo e(__('Dashboard')); ?></p>
                </a>
            </li>
            
            <li class="nav-item<?php echo e($activePage == 'Notification' ? ' active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('admin/notification')); ?>">
                    <i class="material-icons"></i>
                    <p><?php echo e(__('Notification')); ?></p>
                </a>
            </li>
            

            <li class="nav-item<?php echo e($activePage == 'customers' ? ' active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('admin.customers.index')); ?>">
                    <i class="material-icons"></i>
                    <p><?php echo e(__('Customers')); ?></p>
                </a>
            </li>
            
            

            <li class="nav-item<?php echo e($activePage == 'categories' ? ' active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('admin/categories')); ?>">
                    <i class="material-icons"></i>
                    <p><?php echo e(__('Lot Categories')); ?></p>
                </a>
            </li>
            

            <li class="nav-item<?php echo e($activePage == 'lots' ? ' active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('admin/lots')); ?>">
                    <i class="material-icons"></i>
                    <p><?php echo e(__('Lots')); ?></p>
                </a>
            </li>

            <li class="nav-item<?php echo e($activePage == 'Payment Plan' ? ' active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('admin/payment_plan')); ?>">
                    <i class="material-icons"></i>
                    <p><?php echo e(__('Payments Plan')); ?></p>
                </a>
            </li>



            <li class="nav-item<?php echo e($activePage == 'Live Lots' ? ' active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('admin/live_lots')); ?>">
                    <i class="material-icons"></i>
                    <p><?php echo e(__('Live Lots')); ?></p>
                </a>
            </li>

            <li class="nav-item<?php echo e($activePage == '' ? ' active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(url('admin/complete_lots')); ?>">
                    <i class="material-icons"></i>
                    <p><?php echo e(__('Completed Lots')); ?></p>
                </a>
            </li>
            <li class="nav-item<?php echo e($activePage == 'payments' ? ' active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('admin.payments.index')); ?>">
                    <i class="material-icons"></i>
                    <p><?php echo e(__('Payments')); ?></p>
                </a>
            </li>

            <div class="dropdown-divider"></div>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('logout')); ?>"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();"><?php echo e(__('Log out')); ?>

                    <i class="material-icons"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\Steel24-admin\resources\views/admin/layouts/navbars/sidebar.blade.php ENDPATH**/ ?>