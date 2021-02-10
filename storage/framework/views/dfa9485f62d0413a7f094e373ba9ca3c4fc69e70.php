<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="loginouter">
            <div class="panel panel-default">
                <div class="panel-heading bold"><?php echo e(ucfirst(config('app.name'))); ?> Login</div>
                <div class="panel-body">
                    
                    <?php if(count($errors) > 0): ?>
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were problems with input:
                            <br><br>
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form class="form-horizontal"
                          role="form"
                          method="POST"
                          action="<?php echo e(url('login')); ?>">
                        <input type="hidden"
                               name="_token"
                               value="<?php echo e(csrf_token()); ?>">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input type="email"
                                       class="form-control"
                                       name="email"
                                       value="<?php echo e(old('email')); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input type="password"
                                       class="form-control"
                                       name="password">
                            </div>
                        </div>
                      

                      <?php if(session()->get('login.attempts') > 3): ?> 
                        <div class="row">
                          <div class="col-md-4"></div>
                          <div class="form-group col-md-4">
                             <div class="captcha">
                               <span><?php echo captcha_img(); ?></span>
                               <button type="button" class="btn btn-success"><i class="fa fa-refresh" id="refresh"></i></button>
                               </div>
                            </div>
                        </div>
                         <div class="row">
                          <div class="col-md-4"></div>
                            <div class="form-group col-md-4">
                             <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha" autocomplete="off"></div>
                          </div>
                       <?php endif; ?>   
						<div class="form-group">
                            <div class="col-md-8" style="padding-left: 80px;">
                               <div>
                                <a href="<?php echo e(route('auth.password.reset')); ?>">Forgot your password?</a>
                            	</div>
                            	 <div>
                                <label>
                                    <input type="checkbox"
                                           name="remember"> Remember me
                                </label>
                            </div>
                            </div>
                            <div class="col-md-4 fright">
                                <button type="submit"
                                        class="btn btn-primary"
                                        style="margin-right: 15px;">
                                    Login
                                </button>
                            </div>
                        </div>
                        
 
                    </form>
                </div>
            </div>
        </div>
    </div>
 <?php $__env->startSection('javascript'); ?>
 <script type="text/javascript">
$('#refresh').on('click',function(){
  $.ajax({
     type:'GET',
     url:'refreshcaptcha',
     success:function(data){
        $(".captcha span").html(data.captcha);
     }
  });
});
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>
  
<?php echo $__env->make('layouts.auth', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>