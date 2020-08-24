<?php $__env->startSection('content'); ?>
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> <?php echo e(__('Inventory summary')); ?></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-2">
                        <div class="gray-panel pn">
                            <div class="gray-header">
                                <h5><p><?php echo e(__('Total Items')); ?></p></h5>
                            </div>
                            <p class="user"><i class="fa fa-object-group"></i>&nbsp; <?php echo e($itemCount); ?></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="yellow-panel pn">
                            <div class="yellow-header">
                                <h5><p><?php echo e(__('Total Folders')); ?></p></h5>
                            </div>
                            <p class="user"><i class="fa fa-users"></i>&nbsp;<?php echo e($clientCount); ?></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="green-panel pn">
                            <div class="green-header">
                                <h5><p><?php echo e(__('Total Stocks')); ?></p></h5>
                            </div>
                            <p class="user"><i class="fa fa-database"></i>&nbsp;<?php echo e($totalStocks); ?></p>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="red-panel pn">
                            <div class="red-header">
                                <h5><p><?php echo e(__('Total Values')); ?></p></h5>
                            </div>
                            <p class="user"><i class="fa fa-money"></i>&nbsp;$<?php echo e($totalValues); ?></p>
                        </div>
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /col-lg-3 -->
        </div>
        <!-- /row -->
    </section>

    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> <?php echo e(__('Recent activity')); ?></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 ds">
                <div class="desc">
                    <div class="thumb">
                        <span class="badge bg-theme"><i class="fa fa-clock-o"></i></span>
                    </div>
                    <div class="details">
                        <p>
                            <a href="#">Brad Davidson</a> purchased an item.<br>
                        </p>
                    </div>
                </div>

                <div class="desc">
                    <div class="thumb">
                        <span class="badge bg-theme"><i class="fa fa-clock-o"></i></span>
                    </div>
                    <div class="details">
                        <p>
                            <a href="#">Brad Davidson</a> purchased an item.<br>
                        </p>
                    </div>
                </div>
            </div>
        </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Jaydeep Mor\Work\Jessica\BRAD DAVIDSON\brad_davidson\resources\views/dashboard.blade.php ENDPATH**/ ?>