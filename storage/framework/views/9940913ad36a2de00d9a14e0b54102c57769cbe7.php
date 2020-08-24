<?php $__env->startSection('content'); ?>
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> <?php echo e(__('Items')); ?></h3>
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
                <form class="form-inline search-form" method="__GET" action="<?php echo e(route('items.index')); ?>">
                    <div>
                        <div class="form-group">
                            <input type="text" name="s" class="form-control" placeholder="<?php echo e(__('Search by name')); ?>" value="<?php echo e($request->get('s', '')); ?>">
                            <input type="number" name="q" class="form-control" placeholder="<?php echo e(__('Search by qty')); ?>" value="<?php echo e($request->get('q', '')); ?>">
                            <input type="number" name="v" class="form-control" placeholder="<?php echo e(__('Search by value')); ?>" value="<?php echo e($request->get('v', '')); ?>">
                            <select class="form-control" name="ml">
                                <option value=""><?php echo e(__('Min level')); ?></option>

                                <?php if(!empty($levels)): ?>
                                    <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($index); ?>" <?php echo e($request->get('ml', '') == $index ? 'selected' : ''); ?>><?php echo e($level); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            <select class="form-control" name="t">
                                <option value=""><?php echo e(__('Tags')); ?></option>

                                <?php if(!empty($tags)): ?>
                                    <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($tag->id); ?>" <?php echo e($request->get('t', '') == $tag->id ? 'selected' : ''); ?>><?php echo e($tag->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                            <?php if($isFiltered == true): ?>
                                <a href="<?php echo e(route('items.index')); ?>" class="btn btn-light">
                                    <i class="fa fa-trash"></i>
                                </a>
                            <?php endif; ?>
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
                <div class="content-panel" style="height: 100%;">
                    <div class="col-md-8">
                        <h4><i class="fa fa-angle-right"></i>&nbsp;<?php echo e(__('Total')); ?> <?php echo e($total); ?> <?php echo e(__('Items')); ?></h4>
                    </div>
                    <div class="col-md-4">
                        <h5 class="float-right text-muted">
                            <?php echo e(__('Showing')); ?> <?php echo e($records->firstItem()); ?> - <?php echo e($records->lastItem()); ?> / <?php echo e($records->total()); ?> (<?php echo e(__('page')); ?> <?php echo e($records->currentPage()); ?> )&nbsp;
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt">
            <div class="col-lg-12">
                <div class="row">
                    <?php if(!empty($records) && !$records->isEmpty()): ?>
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-lg-3 col-md-3 col-sm-3 mb">
                                <div class="content-panel sp">
                                    <div id="blog-bg">
                                        <img src="<?php echo e((!empty($record->photo)) ? $record->photo->photo : asset('img/no-item-image.png')); ?>" style="width: 100%;height: 150px;" />
                                        <div class="pull-left">
                                            <div class="blog-title"><?php echo e($record->name); ?></div>
                                        </div>
                                        <div class="pull-right">
                                            <div class="blog-title-right base-quantity">$<?php echo e($record->qty); ?> | $<?php echo e($record->value); ?></div>
                                        </div>
                                    </div>
                                    <div class="blog-text">
                                        <p><?php echo e($record->notes); ?></p>
                                        <div>
                                            <div class="text-center">
                                                <div class="btn-group">
                                                    <a class="btn btn-primary btn-sm" title="<?php echo e(__('Edit')); ?>" href="<?php echo e(route('items.edit', $record->id)); ?>"><i class="fa fa-edit"></i></a>
                                                    <form action="<?php echo e(route('items.change.quantity', $record->id)); ?>" method="POST" style="display: inline-block;">
                                                        <?php echo csrf_field(); ?>
                                                        <button class="changeQuantity btn btn-dark btn-sm" title="<?php echo e(__('Change Quantities')); ?>" data-title="<?php echo e(__('Change Quantities')); ?>" data-value="<?php echo e($record->qty); ?>"><i class="fa fa-sort-amount-desc"></i></button>

                                                        <input type="hidden" name="qty" id="qty" value="<?php echo e($record->qty); ?>">
                                                    </form>
                                                    <form action="<?php echo e(route('items.moveto.folder', $record->id)); ?>" method="POST" style="display: inline-block;margin-left: -4px;">
                                                        <?php echo csrf_field(); ?>
                                                        <button class="btn btn-warning btn-sm moveItem" title="<?php echo e(__('Move to folder')); ?>" data-html="moveto-model-<?php echo e($record->id); ?>"><i class="fa fa-arrows"></i></button>
                                                    </form>
                                                    <div style="display: inline-block;margin-left: -4px;">
                                                        <button class="btn btn-info btn-sm" title="<?php echo e(__('History')); ?>"><i class="fa fa-history"></i></button>
                                                    </div>
                                                    <form action="<?php echo e(route('items.destroy', $record->id)); ?>" method="POST" style="display: inline-block;margin-left: -4px;">
                                                        <?php echo method_field('DELETE'); ?>
                                                        <?php echo csrf_field(); ?>
                                                        <a href="#" class="deleteBtn btn btn-danger btn-sm" data-confirm-message="<?php echo e(__("Are you sure you want to delete this?")); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo e(__('Delete')); ?>"><i class="fa fa-trash"></i></a>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="col-lg-12 mb text-center">
                            <mark><?php echo e(__('No record found.')); ?></mark>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="float-left ml-10">
                    <?php if(!empty($request)): ?>
                        <?php echo e($records->appends($request->all())->links()); ?>

                    <?php else: ?>
                        <?php echo e($records->links()); ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php if(!empty($records) && !$records->isEmpty()): ?>
        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="moveto-model-<?php echo e($record->id); ?> d-none">
                <form action="<?php echo e(route('items.moveto.folder', $record->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="border-head h3">
                                        <?php echo e(__('Moving')); ?>

                                        <span class="h4"><?php echo e($record->name); ?></span>
                                    </div>
                                </div>
                            </div>

                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <label><?php echo e(__('What quantity of this item do you want to move?')); ?></label>
                                </div>
                            </div>

                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <label><?php echo e(__('Choose destination folder')); ?>&nbsp;:&nbsp;</label>

                                    <div class="row col-md-10 col-xs-12">
                                        <input type="number" min="0" max="<?php echo e($record->qty); ?>" name="amount" class="form-control" placeholder="Enter Amount" value="1" />
                                    </div>
                                    <div class="row col-md-2">
                                        <label style="margin-top: 6px;">&nbsp;<mark><?php echo e(__('of')); ?>&nbsp;<span style="font-weight: bold;"><?php echo e($record->qty); ?></span></mark></label>
                                    </div>
                                </div>
                            </div>

                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <select name="folder" class="form-control">
                                        <option value=""><?php echo e(__('Select')); ?></option>

                                        <?php if(!empty($folders) && !$folders->isEmpty()): ?>
                                            <?php $__currentLoopData = $folders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($folder->id); ?>" title="<?php echo e($folder->notes); ?>"><?php echo e($folder->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary"><i class="fa fa-save"></i></button>
                                    <button type="button" class="btn btn-secondary btn-default bootbox-cancel close-model" style="float: none;"><i class="fa fa-close"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Jaydeep Mor\Work\Jessica\BRAD DAVIDSON\brad_davidson\resources\views/items/index.blade.php ENDPATH**/ ?>