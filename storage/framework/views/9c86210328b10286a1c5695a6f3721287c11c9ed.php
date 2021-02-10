<?php $__env->startSection('content'); ?>
    <h3 class="page-title"><i class="fa fa-user-circle ifont"></i>  Pages</h3>
    
    <?php echo Form::model($page, ['method' => 'PUT', 'route' => ['admin.pages.update', $page->id]]); ?>


    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo app('translator')->getFromJson('quickadmin.qa_edit'); ?>
        </div>

         <div class="panel-body">
            <div class="row">
                
                    <div class="col-xs-12 form-group">
                            <?php echo Form::label('name', 'Subject *', ['class' => 'control-label']); ?>

                            <?php echo Form::text('page_subject', old('page_subject'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']); ?>

                            <p class="help-block"></p>
                            <?php if($errors->has('page_subject')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('page_subject')); ?>

                                </p>
                            <?php endif; ?>
                        </div>
                    <div class="col-xs-12 form-group">
                            <?php echo Form::label('name', 'Content *', ['class' => 'control-label']); ?>

                            <?php echo Form::textarea('page_content',old('page_content'),['class'=>'form-control', 'rows' => 5, 'cols' => 20,'id'=> 'page_content']); ?>

                            <p class="help-block"></p>
                            <?php if($errors->has('page_content')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('page_content')); ?>

                                </p>
                            <?php endif; ?>
                    </div>     
                  
        </div>
    </div>
    
    <?php echo Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']); ?>

    <?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
    ##parent-placeholder-b6e13ad53d8ec41b034c49f131c64e99cf25207a##
    <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
    <script>
         CKEDITOR.replace( 'page_content' );
        // $('.textarea').ckeditor(); // if class is prefered.
    </script>
    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>