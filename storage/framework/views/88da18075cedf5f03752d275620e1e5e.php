<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?php echo e(asset('frontend/assets/favicon-96x96.png')); ?>" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('frontend/assets/favicon.svg')); ?>" />
    <link rel="shortcut icon" href="<?php echo e(asset('frontend/assets/favicon.ico')); ?>" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('frontend/assets/apple-touch-icon.png')); ?>" />
    <meta name="apple-mobile-web-app-title" content="Zaya Wellness" />
    <link rel="manifest" href="<?php echo e(asset('frontend/assets/site.webmanifest')); ?>">
    <title><?php echo e(__('Forgot Password')); ?> - Zaya Wellness</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        .bg-primary { background-color: #2E4B3D; }
        .text-primary { color: #2E4B3D; }
    </style>
</head>

<body class="bg-white min-h-screen flex gap-10 xl:gap-20 p-2 md:p-10 max-lg:pb-15! relative"
    style="background-image: url('<?php echo e(asset('frontend/assets/login-bg.webp')); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="absolute inset-0 bg-black/50 z-0"></div>

    <!-- Mobile Back Link -->
    <div class="lg:hidden absolute bottom-5 left-1/2 transform -translate-x-1/2 flex flex-col items-center gap-6 z-10">
        <a href="<?php echo e(route('login')); ?>"
            class="text-white flex items-center gap-2 hover:opacity-80 transition text-sm font-normal">
            <i class="ri-arrow-left-line"></i> <?php echo e(__('Back to Login')); ?>

        </a>
    </div>

    <!-- Left Side -->
    <div class="relative hidden lg:flex w-1/2 bg-cover bg-center items-end p-16 z-10">
        <div class="absolute top-0 right-0 py-6 px-10 flex items-center gap-10 z-10">
            <a href="<?php echo e(route('login')); ?>"
                class="text-white flex items-center gap-2 hover:opacity-80 transition z-10 text-sm font-normal">
                <i class="ri-arrow-left-line"></i> <?php echo e(__('Back to Login')); ?>

            </a>
        </div>
        <div class="relative z-10 text-white max-w-xl">
            <h1 class="text-4xl xl:text-5xl font-sans! font-bold mb-6 leading-tight"><?php echo e(__('Reset Your Password')); ?></h1>
            <p class="text-white/80 text-lg font-light leading-relaxed"><?php echo e(__('Enter the email address associated with your account and we will send you an OTP to reset your password.')); ?></p>
        </div>
    </div>

    <!-- Right Side - Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 py-6 lg:p-8 bg-white overflow-y-auto z-20 rounded-3xl">
        <div class="w-full max-w-md">
            <div class="text-center mb-2">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-[#422251]/10 to-[#AA349F]/10 mb-4">
                    <i class="ri-lock-password-line text-3xl text-[#8B3A8A]"></i>
                </div>
            </div>
            <h2 class="text-lg md:text-3xl font-sans! font-bold text-center text-gray-900 lg:mb-[18px]"><?php echo e(__('Forgot Password')); ?></h2>
            <p class="text-gray-500 text-center mb-6 md:mb-8 text-md md:text-base"><?php echo e(__('Enter your email address and we\'ll send you a verification code.')); ?></p>

            <?php if(session('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>

            <?php if(session('status')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo e(session('status')); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('client.forgot-password.send')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>

                <div>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                        placeholder="<?php echo e(__('Enter your email address')); ?>"
                        class="w-full px-6 py-4 rounded-full border border-gray-200 focus:outline-none focus:border-[#8B3A8A] focus:ring-1 focus:ring-[#8B3A8A] text-gray-700 text-sm lg:text-base placeholder-gray-400 bg-white shadow-sm transition-all <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-red-500 text-sm mt-1 pl-4 block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-[#422251] to-[#AA349F] text-white py-4 rounded-full font-medium text-base lg:text-lg hover:opacity-90 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 duration-200 cursor-pointer">
                    <?php echo e(__('Send OTP')); ?>

                </button>
            </form>

            <div class="text-center mt-8 text-gray-600 text-sm lg:text-base">
                <?php echo e(__('Remember your password?')); ?>

                <a href="<?php echo e(route('login')); ?>" class="text-[#8B3A8A] font-medium hover:underline ml-1"><?php echo e(__('Sign in')); ?></a>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\wamp64\www\zaya\resources\views\auth\client-passwords\email.blade.php ENDPATH**/ ?>