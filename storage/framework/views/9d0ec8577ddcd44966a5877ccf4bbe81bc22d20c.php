<?php $__env->startSection('content'); ?>
    <div class="container main">
        <div class="login row">
            <span class="login-error"><?php echo e($errors->first()); ?></span>
            <div class="login-form js-login-panel col-md-5">
                <form class="js-form login-form js-login-form" method="post" action="<?php echo e(route('login')); ?>" novalidate >
                    <?php echo csrf_field(); ?>
                    <div class="input">
                        <input type="text" name="email" id="login" required value="<?php echo e(old('email')); ?>">
                        <label for="login">Логин</label>
                    </div>
                    <div class="input">
                        <input type="password" name="password" id="password" required>
                        <label for="password">Пароль</label>
                        <a class="forgot-pass" href="<?php echo e(route('password.request')); ?>"> Забыли пароль? </a>
                    </div>
                    <div class="material-switch">
                        <span>Запомнить</span>
                        <input id="remember" name="remember" type="checkbox" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                        <label for="remember"></label>
                    </div>
                    <button type="submit" class="button" id="js-login">Войти</button>
                </form>

                <form class="js-form register-form js-register-form" method="post" action="<?php echo e(route('register')); ?>" novalidate>
                    <?php echo csrf_field(); ?>
                    <div class="input">
                        <input type="text" name="name" id="name-reg" value="<?php echo e(old('name')); ?>" required >
                        <label for="name-reg">Имя</label>
                    </div>
                    <div class="input">
                        <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required>
                        <label for="email">E-mail</label>
                    </div>
                    <div class="input">
                        <input type="password" name="password" id="password-reg" required>
                        <label for="password-reg">Пароль</label>
                    </div>
                    <div class="input">
                        <input type="password" name="password_confirmation" id="password_confirm" data-rule-equalTo="#password-reg" required>
                        <label for="password_confirm">Повторите пароль</label>
                    </div>
                    <button type="submit" class="button" >Зарегистрироваться</button>
                </form>

            </div>
            <div class="image-container col-md-7">
                <login-image></login-image>
                <button class="button button-outlined" id="login-switch" data-text="Войти">Регистрация</button>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>