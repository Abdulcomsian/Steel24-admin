


<?php $__env->startSection('content'); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css"
        rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>

    <style>
        .bootstrap-select.btn-group.show-tick .dropdown-menu li.selected a span.check-mark {
            position: relative;
            display: inline-block;
            right: 15px;
            /*margin-top: 5px;*/
            left: 2px;
        }

        .bootstrap-select.btn-group.show-tick .dropdown-menu li a span.text {
            margin-right: 34px;
        }

        .dropdown-menu {
            width: 100%;
        }

        .dropdown-toggle {
            padding: 5px 20px 10px 9px;
        }

        .bootstrap-select>.dropdown-toggle.bs-placeholder,
            {
            color: black !important;
            background: white !important;
        }

        ..bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
            color:
        }
    </style>
    <div class="container">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="<?php echo e(url('admin/newlots')); ?>" class="form-horizontal">
                        <?php echo csrf_field(); ?>
                        <div class="card ">
                            <!--Header-->
                            <div class="card-header card-header-primary m-0">
                                <h4 class="card-title">Lots</h4>
                            </div>
                            <!--End header-->
                            <!--Body-->
                            <div class="card-body">
                            <div class="form-row " >
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Title</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="title" name="title" placeholder="Title" required>
                                    </div>
                                    <!-- <label for="description" class="col-sm-2 col-form-label">Description</label> -->
                                    <div class="col-sm-4">
                                        <textarea class="form_customer" placeholder="Description" id="description" name="description" >  <?php echo e($lots ? $lots->description :''); ?> </textarea>
                                    </div>

                                    <!-- <label for="categoryId" class="col-sm-2 col-form-label">Category</label> -->
                                    <div class="col-sm-4">
                                        <select class="custom-select" id="categoryId" name="categoryId" 
                                            style="margin-top: 10px;">
                                            <?php $__currentLoopData = $categorys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value=<?php echo e($category->id); ?>><?php echo e($category->title); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <!-- <label for="seller" class="col-sm-2 col-form-label">Seller</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="seller" name="Seller" placeholder="Seller" required>
                                    </div>
                                    <!-- <label for="plant" class="col-sm-2 col-form-label">Plant</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="plant" name="Plant" placeholder="Plant" required>
                                    </div>
                                    <!-- <label for="materialLocation" class="col-sm-2 col-form-label">Material Location</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="materialLocation"
                                            name="materialLocation" placeholder="Material Location" required>
                                    </div>
                                    <!-- <label for="quantity" class="col-sm-2 col-form-label">Quantity</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="quantity" name="Quantity" placeholder="Quantity" required>
                                    </div>

                                    <!-- <label for="startDate" class="col-sm-2 col-form-label">Start Date</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="startDate" name="StartDate"  placeholder="Start Date"
                    onfocus="(this.type='datetime-local')" onblur="(this.type='text')"
                                            required>
                                    </div>
                                    <!-- <label for="endDate" class="col-sm-2 col-form-label">End Date</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="endDate" name="EndDate" placeholder="End Date"
                    onfocus="(this.type='datetime-local')" onblur="(this.type='text')"
                                            required>
                                    </div>

                                

                                    <!-- <label for="startPrice" class="col-sm-2 col-form-label">Start Price</label> -->
                                    <div class="col-sm-4">
                                        <input type="number" class="form_customer" id="startPrice" name="Price" placeholder="Start Price"
                                            min="0" autocomplete="off" autofocus>
                                    </div>
                                    <!-- <label for="participate_fee" class="col-sm-2 col-form-label">Participation Fee</label> -->
                                    <div class="col-sm-4">
                                        <input type="number" class="form_customer" step="0.01" id="participate_fee" placeholder="Participation Fee"
                                            min="0" name="participate_fee" autocomplete="off" autofocus>
                                    </div>
                                

                                    <!-- <label for="paymentId" class="col-sm-2 col-form-label">Payment Terms</label> -->
                                    <div class="col-sm-4">
                                        <select class="custom-select" id="paymentId" name="paymentId"
                                            style="margin-top: 10px;">
                                            <?php $__currentLoopData = $paymentTerms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value=<?php echo e($payment->id); ?>><?php echo e($payment->Payment_Terms); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                     <!--Footer-->
                            <div class="card-footer ml-auto mr-auto col-sm-12">
                                <a href="<?php echo e(url('admin/lots')); ?>" class="btn btn-primary mr-3 Back_btn_customer">Back</a>
                                <button type="submit" class="btn btn-primary">Save And Add Material</button>
                            </div>
                            <!--End footer-->
                                    </div>
                            </div>
                            <!--End body-->

                           
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
                                </div>
<?php $__env->stopSection(); ?>

<script>
    $(document).ready(function() {
        // Select2 Multiple
        $('.select2-multiple').select2({
            placeholder: "Select",
            allowClear: true
        });

    });
</script>

<?php echo $__env->make('admin.layouts.main', ['activePage' => 'lots', 'titlePage' => 'New Lots'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Steel24-admin\resources\views/admin/lots/create.blade.php ENDPATH**/ ?>