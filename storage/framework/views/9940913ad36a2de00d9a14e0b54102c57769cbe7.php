<?php $__env->startSection('content'); ?>
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> <?php echo e(__('Items')); ?></h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <h4><?php echo e(__('Search Form : ')); ?></h4>
                <form class="form-inline search-form" method="__GET" action="<?php echo e(route('items.index')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="">
                        <div class="form-group">
                            <input type="text" name="s" class="form-control" placeholder="<?php echo e(__('Search by name')); ?>">
                            <input type="number" name="q" class="form-control" placeholder="<?php echo e(__('Search by qty')); ?>">
                            <select class="form-control" name="ml">
                                <option><?php echo e(__('Min level')); ?></option>
                            </select>
                            <select class="form-control" name="t">
                                <option><?php echo e(__('Tags')); ?></option>
                            </select>
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                        </div>
                    </div>

                    <div class="pull-right add-new-button">
                        <a class="btn btn-primary" href="<?php echo e(route('items.create')); ?>"><i class="fa fa-plus"></i></a>
                    </div>
                </form>

            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <h3>11 <?php echo e(__('Items')); ?></h3>
            </div>
        </div>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 mb">
                        <div class="content-panel sp">
                            <div id="blog-bg">
                                <img src="../img/blog-bg.jpg" />
                                <div class="pull-left">
                                    <div class="blog-title">Incredible Title</div>
                                </div>
                                <div class="pull-right">
                                    <div class="blog-title-right base-quantity">50qty | $520.667</div>
                                </div>
                            </div>
                            <div class="blog-text">
                                <p>Shivay test test</p>
                                <div>
                                    <div class="text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-primary btn-sm" title="<?php echo e(__('Edit')); ?>"><i class="fa fa-edit"></i></button>
                                            <button class="btn btn-dark btn-sm" title="<?php echo e(__('Change Quantities')); ?>"><i class="fa fa-sort-amount-desc"></i></button>
                                            <button class="btn btn-warning btn-sm" title="<?php echo e(__('Move to folder')); ?>"><i class="fa fa-arrows"></i></button>
                                            <button class="btn btn-info btn-sm" title="<?php echo e(__('History')); ?>"><i class="fa fa-history"></i></button>
                                            <button class="btn btn-danger btn-sm" title="<?php echo e(__('Remove')); ?>"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Jaydeep Mor\Work\Jessica\BRAD DAVIDSON\brad_davidson\resources\views/items/index.blade.php ENDPATH**/ ?>