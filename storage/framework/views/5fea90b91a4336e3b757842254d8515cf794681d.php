<?php $__env->startSection('content'); ?>
    <section class="wrapper site-min-height">
        <div class="row">
            <div class="col-lg-12">
                <div class="border-head">
                    <h3><i class="fa fa-angle-right"></i> <?php echo e(__('Items Create')); ?></h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="content-panel">
                    <form class="form-group p-10" enctype="multipart/form-data" action="<?php echo e(route('items.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label><?php echo e(__('Item Name')); ?> : </label>

                                <input type="text" class="form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>" name="name" value="<?php echo e(old('name')); ?>" autofocus="" />

                                <?php if($errors->has('name')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('name')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-3">
                                <label><?php echo e(__('Quantity')); ?> : </label>

                                <input type="number" class="form-control<?php echo e($errors->has('qty') ? ' is-invalid' : ''); ?>" name="qty" value="<?php echo e(old('qty')); ?>" autofocus="" id="item-quantity" onblur="getValue(this)" />

                                <?php if($errors->has('qty')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('qty')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-3">
                                <label><?php echo e(__('Min Level')); ?> : </label>

                                <input type="number" class="form-control<?php echo e($errors->has('min_level') ? ' is-invalid' : ''); ?>" name="min_level" value="<?php echo e(old('min_level')); ?>" autofocus="" />

                                <?php if($errors->has('min_level')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('min_level')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3">
                                <label><?php echo e(__('Price')); ?> ($) : </label>

                                <input type="number" class="form-control<?php echo e($errors->has('price') ? ' is-invalid' : ''); ?>" name="price" value="<?php echo e(old('price')); ?>" autofocus="" id="item-price" onblur="getValue(this)" step="0.01" min="0" />

                                <?php if($errors->has('price')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('price')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-3">
                                <label><?php echo e(__('Value')); ?> ($) : </label>

                                <input type="number" class="form-control<?php echo e($errors->has('value') ? ' is-invalid' : ''); ?>" name="value" value="<?php echo e(old('value', 0)); ?>" autofocus="" readonly=""  id="price-value" />

                                <?php if($errors->has('value')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('value')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label><?php echo e(__('Tags')); ?> : </label>

                                <select name="tags[]" class="form-control<?php echo e($errors->has('tags.0') ? ' is-invalid' : ''); ?>" multiple="">
                                    <option value="" <?php echo e(old('tags.0') == '' ? 'selected=""' : ''); ?>><?php echo e(__('Select')); ?></option>

                                    <?php if(!empty($tags)): ?>
                                        <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($tag->id); ?>" <?php echo e(old('tags.'.$index) == $tag->id ? 'selected' : ''); ?>><?php echo e($tag->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>

                                <?php if($errors->has('tags.0')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('tags.0')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><?php echo e(__('Notes')); ?> : </label>

                                <textarea class="form-control<?php echo e($errors->has('notes') ? ' is-invalid' : ''); ?>" name="notes"><?php echo e(old('notes')); ?></textarea>

                                <?php if($errors->has('notes')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('notes')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="fileupload-buttonbar">
                                    <span class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span><?php echo e(__('Photos')); ?></span>
                                        <input type="file" name="photos[]" id="imgUpload" multiple="" accept="image/*">
                                    </span>
                                </div>
                            </div>

                        </div>
                        <div class="form-group row" id="preview-image"></div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i></button>
                                <a class="btn btn-default" href="<?php echo e(route('items.index')); ?>"><i class="fa fa-arrow-left"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\Jaydeep Mor\Work\Jessica\BRAD DAVIDSON\brad_davidson\resources\views/items/create.blade.php ENDPATH**/ ?>