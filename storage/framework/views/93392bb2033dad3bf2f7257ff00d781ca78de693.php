<?php $__env->startSection('content'); ?>
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> <?php echo e(__('Tags')); ?></h3>
                </div>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success" role="alert">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-12">
                <h4><?php echo e(__('Search Form : ')); ?></h4>
                <form class="form-inline search-form" method="__GET" action="<?php echo e(route('tags.index')); ?>">
                    <div class="">
                        <div class="form-group">
                            <input type="text" name="s" class="form-control" placeholder="<?php echo e(__('Search by name')); ?>" value="<?php echo e($request->get('s', '')); ?>">
                            <?php if($isFiltered == true): ?>
                                <a href="<?php echo e(route('tags.index')); ?>" class="btn btn-light">
                                    <i class="fa fa-trash"></i>
                                </a>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                        </div>
                    </div>

                    <div class="pull-right add-new-button">
                        <a class="btn btn-primary" href="<?php echo e(route('tags.create')); ?>"><i class="fa fa-plus"></i></a>
                    </div>
                </form>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="content-panel">
                    <div class="col-md-8">
                        <h4><i class="fa fa-angle-right"></i>&nbsp;<?php echo e(__('Total')); ?> <?php echo e($total); ?> <?php echo e(__('Tags')); ?></h4>
                    </div>
                    <div class="col-md-4">
                        <h5 class="float-right text-muted">
                            <?php echo e(__('Showing')); ?> <?php echo e($records->firstItem()); ?> - <?php echo e($records->lastItem()); ?> / <?php echo e($records->total()); ?> (<?php echo e(__('page')); ?> <?php echo e($records->currentPage()); ?> )&nbsp;
                        </h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-stripped">
                            <thead>
                                <th>
                                    <?php echo e(__('#')); ?>

                                </th>
                                <th>
                                    <?php echo e(__('Tag Name')); ?>

                                </th>
                                <th>
                                    <?php echo e(__('Created Date')); ?>

                                </th>
                                <th>
                                    <?php echo e(__('Created By')); ?>

                                </th>
                                <th>
                                    <?php echo e(__('Operations')); ?>

                                </th>
                            </thead>

                            <tbody>
                                <?php if(!empty($records) && !$records->isEmpty()): ?>
                                    <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($record->id); ?></td>
                                            <td><?php echo e($record->name); ?></td>
                                            <td><?php echo e($record->created_at); ?></td>
                                            <td><?php echo e($record->user->name); ?></td>
                                            <td class="form-inline">
                                                <a href="<?php echo e(route('tags.edit', $record->id)); ?>" title="<?php echo e(__('Edit')); ?>">
                                                    <i class="fa fa-edit fa-2x"></i>
                                                </a>
                                                &nbsp;
                                                <form action="<?php echo e(route('tags.destroy', $record->id)); ?>" method="POST">
                                                    <?php echo method_field('DELETE'); ?>
                                                    <?php echo csrf_field(); ?>
                                                    <a href="#" class="deleteBtn" data-confirm-message="<?php echo e(__("Are you sure you want to delete this?")); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo e(__('Delete')); ?>"><i class="fa fa-trash fa-2x"></i></a>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <mark><?php echo e(__('No record found.')); ?></mark>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <div class="float-left ml-10">
                            <?php if(!empty($request)): ?>
                                <?php echo e($records->appends($request->all())->links()); ?>

                            <?php else: ?>
                                <?php echo e($records->links()); ?>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Jaydeep Mor\Work\Jessica\BRAD DAVIDSON\brad_davidson\resources\views/tags/index.blade.php ENDPATH**/ ?>