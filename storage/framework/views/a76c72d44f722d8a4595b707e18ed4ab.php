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
    <title><?php echo e(__('Verify OTP')); ?> - Zaya Wellness</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        .bg-primary { background-color: #2E4B3D; }
        .text-primary { color: #2E4B3D; }
        .otp-input {
            width: 52px;
            height: 58px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            outline: none;
            transition: all 0.2s;
            background: white;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .otp-input:focus {
            border-color: #8B3A8A;
            box-shadow: 0 0 0 2px rgba(139, 58, 138, 0.2);
        }
        .otp-input.filled {
            border-color: #8B3A8A;
            background: #fdf4ff;
        }
    </style>
</head>

<body class="bg-white min-h-screen flex gap-10 xl:gap-20 p-2 md:p-10 max-lg:pb-15! relative"
    style="background-image: url('<?php echo e(asset('frontend/assets/login-bg.webp')); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="absolute inset-0 bg-black/50 z-0"></div>

    <!-- Mobile Back Link -->
    <div class="lg:hidden absolute bottom-5 left-1/2 transform -translate-x-1/2 flex flex-col items-center gap-6 z-10">
        <a href="<?php echo e(route('client.forgot-password')); ?>"
            class="text-white flex items-center gap-2 hover:opacity-80 transition text-sm font-normal">
            <i class="ri-arrow-left-line"></i> <?php echo e(__('Back')); ?>

        </a>
    </div>

    <!-- Left Side -->
    <div class="relative hidden lg:flex w-1/2 bg-cover bg-center items-end p-16 z-10">
        <div class="absolute top-0 right-0 py-6 px-10 flex items-center gap-10 z-10">
            <a href="<?php echo e(route('client.forgot-password')); ?>"
                class="text-white flex items-center gap-2 hover:opacity-80 transition z-10 text-sm font-normal">
                <i class="ri-arrow-left-line"></i> <?php echo e(__('Back')); ?>

            </a>
        </div>
        <div class="relative z-10 text-white max-w-xl">
            <h1 class="text-4xl xl:text-5xl font-sans! font-bold mb-6 leading-tight"><?php echo e(__('Verify Your Identity')); ?></h1>
            <p class="text-white/80 text-lg font-light leading-relaxed"><?php echo e(__('We\'ve sent a 6-digit verification code to your email. Please enter it below to continue.')); ?></p>
        </div>
    </div>

    <!-- Right Side - Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-4 py-6 lg:p-8 bg-white overflow-y-auto z-20 rounded-3xl">
        <div class="w-full max-w-md">
            <div class="text-center mb-2">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-r from-[#422251]/10 to-[#AA349F]/10 mb-4">
                    <i class="ri-mail-check-line text-3xl text-[#8B3A8A]"></i>
                </div>
            </div>
            <h2 class="text-lg md:text-3xl font-sans! font-bold text-center text-gray-900 lg:mb-2"><?php echo e(__('Enter OTP')); ?></h2>
            <p class="text-gray-500 text-center mb-2 text-md md:text-base"><?php echo e(__('A verification code has been sent to')); ?></p>
            <p class="text-center mb-6 md:mb-8 font-semibold text-gray-700"><?php echo e(Str::mask(session('client_reset_email', ''), '*', 3, -10)); ?></p>

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

            <form method="POST" action="<?php echo e(route('client.forgot-password.otp.verify')); ?>" class="space-y-6" id="otp-form">
                <?php echo csrf_field(); ?>

                <!-- OTP Input Boxes -->
                <div class="flex justify-center gap-2 md:gap-3">
                    <input type="text" maxlength="1" class="otp-input" data-index="0" inputmode="numeric" pattern="[0-9]" autofocus>
                    <input type="text" maxlength="1" class="otp-input" data-index="1" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-input" data-index="2" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-input" data-index="3" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-input" data-index="4" inputmode="numeric" pattern="[0-9]">
                    <input type="text" maxlength="1" class="otp-input" data-index="5" inputmode="numeric" pattern="[0-9]">
                </div>
                <input type="hidden" name="otp" id="otp-hidden">

                <?php $__errorArgs = ['otp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-red-500 text-sm mt-1 text-center block"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-[#422251] to-[#AA349F] text-white py-4 rounded-full font-medium text-base lg:text-lg hover:opacity-90 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 duration-200 cursor-pointer">
                    <?php echo e(__('Verify OTP')); ?>

                </button>
            </form>

            <div class="text-center mt-6">
                <p class="text-gray-500 text-sm mb-2"><?php echo e(__("Didn't receive the code?")); ?></p>
                <form method="POST" action="<?php echo e(route('client.forgot-password.send')); ?>" class="inline">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="email" value="<?php echo e(session('client_reset_email')); ?>">
                    <button type="submit" class="text-[#8B3A8A] font-medium hover:underline text-sm cursor-pointer">
                        <?php echo e(__('Resend OTP')); ?>

                    </button>
                </form>
            </div>

            <div class="text-center mt-6 text-gray-600 text-sm lg:text-base">
                <a href="<?php echo e(route('login')); ?>" class="text-[#8B3A8A] font-medium hover:underline">
                    <i class="ri-arrow-left-s-line"></i> <?php echo e(__('Back to Login')); ?>

                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.otp-input');
            const hiddenInput = document.getElementById('otp-hidden');
            const form = document.getElementById('otp-form');

            function updateHidden() {
                let otp = '';
                inputs.forEach(input => { otp += input.value; });
                hiddenInput.value = otp;
            }

            inputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    const val = this.value.replace(/[^0-9]/g, '');
                    this.value = val;
                    if (val) {
                        this.classList.add('filled');
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                    } else {
                        this.classList.remove('filled');
                    }
                    updateHidden();
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].value = '';
                        inputs[index - 1].classList.remove('filled');
                        updateHidden();
                    }
                });

                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
                    for (let i = 0; i < Math.min(pasted.length, inputs.length); i++) {
                        inputs[i].value = pasted[i];
                        inputs[i].classList.add('filled');
                    }
                    if (pasted.length >= inputs.length) {
                        inputs[inputs.length - 1].focus();
                    } else {
                        inputs[Math.min(pasted.length, inputs.length - 1)].focus();
                    }
                    updateHidden();
                });
            });

            form.addEventListener('submit', function() {
                updateHidden();
            });
        });
    </script>
</body>
</html>
<?php /**PATH C:\wamp64\www\zaya\resources\views\auth\client-passwords\otp.blade.php ENDPATH**/ ?>