
<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!--Header-->
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Lots</h4>
                        </div>
                        <!--End header-->
                        <!--Body-->
                        <div class="card-body">
                            <div class="row">
                                <!-- first -->
                                <div class="col-md-12">
                                    <div class="card card-user">
                                        <div class="card-body">
                                            <p class="card-text">
                                            <div class="author">
                                                <div class="block block-one"></div>
                                                <div class="block block-two"></div>
                                                <div class="block block-three"></div>
                                                <div class="block block-four"></div>
                                                <a href="#">
                                                    <h5 class="title mt-3">Lot Name : <small><?php echo e($lots->title); ?></small>
                                                    </h5>
                                                </a>

                                                <table class="table align-items-center mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th
                                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                                Fields</th>
                                                            <th
                                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                                Details</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Description</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    <?php echo e($lots->description); ?></p>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Category</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    <?php echo e($lots->categories->title); ?>

                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Material Location</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    <?php echo e($lots->materialLocation); ?>

                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Lot Quantity</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0"><?php echo e($lots->Quantity); ?>

                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Start Date</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    <?php echo e($lots->StartDate); ?>

                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">End Date</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0"><?php echo e($lots->EndDate); ?>

                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Lot Price</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0"><?php echo e($lots->Price); ?>

                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Lot Status</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    <?php echo e($lots->lot_status); ?>

                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Seller</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0"><?php echo e($lots->Seller); ?>

                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Plant</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0"><?php echo e($lots->Plant); ?>

                                                                </p>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Participation Fees</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    <?php echo e($lots->participate_fee); ?>

                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <?php if($materialilist): ?>
                                                <h4>Materials</h4>
                                                <div class="col-12">
                                                    <table class="table" >
                                                        <thead>
                                                            <tr>
                                                                <th> Id</th>
                                                                <th> Product</th>
                                                                <th> Thickness</th>
                                                                <th> Width</th>
                                                                <th> Length</th>
                                                                <th> Weight</th>
                                                                <th> Grade</th>
                                                                <th> Remark</th>
                                                                <th> images</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody id='tablebody'>
                                                            <?php $__currentLoopData = $materialilist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo e($material->id); ?>

                                                                    </td>
                                                                    <td>

                                                                        <?php echo e($material->Product); ?>

                                                                    </td>

                                                                    <td>

                                                                        <?php echo e($material->Thickness); ?>

                                                                    </td>

                                                                    <td>
                                                                        <?php echo e($material->Width); ?>


                                                                    </td>

                                                                    <td>

                                                                        <?php echo e($material->Length); ?>

                                                                    </td>

                                                                    <td>

                                                                        <?php echo e($material->Weight); ?>

                                                                    </td>

                                                                    <td>

                                                                        <?php echo e($material->Grade); ?>

                                                                    </td>

                                                                    <td>

                                                                        <?php echo e($material->Remark); ?>

                                                                    </td>

                                                                    <td>
                                                                        <img src="<?php echo e(url('files/' . $material->images)); ?>"
                                                                            style="width: 150px;height: 75px;;">
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php endif; ?>
                                            </p>
                                        </div>

                                        <div class="card-footer">
                                            <div class="button-container">
                                                <a href="<?php echo e(url('admin/lots')); ?>" class="btn btn-primary">Back</a>
                                                <a href="<?php echo e(url("admin/lots/edit/{$lots->id}")); ?>"
                                                    class="btn btn-success">Edit</a>

                                                <a href="<?php echo e(url("admin/materialslots/{$lots->id}")); ?>"
                                                    class="btn btn-success">Edit Materials</a>

                                                <a href="<?php echo e(url("admin/lotsterms/{$lots->id}")); ?>"
                                                    class="btn btn-success">Edit Terms
                                                </a>

                                                <a href="<?php echo e(url("admin/lots/remove/{$lots->id}")); ?>"
                                                    onclick="return confirm('Once deleted, you will not be able to recover this Lot!?')"
                                                    class="btn btn-danger remove">Remove</a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end first-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--End card body-->
                    </div>
                    <!--End card-->
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>


<?php echo $__env->make('admin.layouts.main', ['activePage' => 'lots', 'titlePage' => 'Details'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steel24\resources\views/admin/lots/show.blade.php ENDPATH**/ ?>