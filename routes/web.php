<?php

use App\Http\Controllers\Auth\LoginController as UserLoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\DepositController;
use App\Http\Controllers\User\NotificationPermissionController;
use App\Http\Controllers\User\PayoutController;
use App\Http\Controllers\ManualRecaptchaController;
use App\Http\Controllers\khaltiPaymentController;
use App\Http\Controllers\User\ProjectController;
use App\Http\Controllers\User\ProjectInvestController;
use App\Http\Controllers\User\PurchasePlanController;
use App\Http\Controllers\User\TwoStepSecurityController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InAppNotificationController;
use App\Http\Controllers\User\SupportTicketController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\User\VerificationController;
use App\Http\Controllers\Frontend\BlogController;





$basicControl = basicControl();
Route::get('language/{locale}', [FrontendController::class,'language'])->name('language');

Route::get('maintenance-mode', function () {

    $data['maintenanceMode'] = \App\Models\MaintenanceMode::first();
    return view(template() . 'maintenance', $data);
})->name('maintenance');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPassword'])->name('passwords.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset')->middleware('guest');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset.update');

Route::get('instruction/page', function () {
    return view('instruction-page');
})->name('instructionPage');

Route::group(['middleware' => ['maintenanceMode']], function () use ($basicControl) {
    Route::group(['middleware' => ['guest']], function () {
        Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [UserLoginController::class, 'login'])->name('login.submit');
    });

    Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as' => 'user.'], function () {

        Route::get('check', [VerificationController::class, 'check'])->name('check');
        Route::get('resend_code', [VerificationController::class, 'resendCode'])->name('resendCode');
        Route::post('mail-verify', [VerificationController::class, 'mailVerify'])->name('mailVerify');
        Route::post('sms-verify', [VerificationController::class, 'smsVerify'])->name('smsVerify');
        Route::post('twoFA-Verify', [VerificationController::class, 'twoFAverify'])->name('twoFA-Verify');

        Route::middleware('userCheck')->group(function () {
            Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard');

            Route::middleware('kyc')->group(function () {

                Route::post('save-token', [HomeController::class, 'saveToken'])->name('save.token');
                Route::get('add-fund', [HomeController::class, 'addFund'])->name('add.fund');
                Route::get('funds', [HomeController::class, 'fund'])->name('fund.index');
                Route::get('transactions', [HomeController::class, 'transactions'])->name('transaction.list');

                /* PAYMENT REQUEST BY USER */
                Route::get('payout-list', [PayoutController::class, 'index'])->name('payout.index');

                Route::get('payout', [PayoutController::class, 'payout'])->name('payout');
                Route::get('payout-supported-currency', [PayoutController::class, 'payoutSupportedCurrency'])->name('payout.supported.currency');
                Route::get('payout-check-amount', [PayoutController::class, 'checkAmount'])->name('payout.checkAmount');
                Route::post('request-payout', [PayoutController::class, 'payoutRequest'])->name('payout.request');

                Route::match(['get', 'post'], 'confirm-payout/{trx_id}', [PayoutController::class, 'confirmPayout'])->name('payout.confirm');
                Route::post('confirm-payout/flutterwave/{trx_id}', [PayoutController::class, 'flutterwavePayout'])->name('payout.flutterwave');
                Route::post('confirm-payout/paystack/{trx_id}', [PayoutController::class, 'paystackPayout'])->name('payout.paystack');
                Route::post('payout-bank-form', [PayoutController::class, 'getBankForm'])->name('payout.getBankForm');
                Route::post('payout-bank-list', [PayoutController::class, 'getBankList'])->name('payout.getBankList');



                Route::group(['prefix' => 'ticket', 'as' => 'ticket.'], function () {
                    Route::get('/', [SupportTicketController::class, 'index'])->name('list');
                    Route::get('/create', [SupportTicketController::class, 'create'])->name('create');
                    Route::post('/create', [SupportTicketController::class, 'store'])->name('store');
                    Route::get('/view/{ticket}', [SupportTicketController::class, 'ticketView'])->name('view');
                    Route::put('/reply/{ticket}', [SupportTicketController::class, 'reply'])->name('reply');
                    Route::get('/download/{ticket}', [SupportTicketController::class, 'download'])->name('download');
                    Route::post('close/{ticket}', [SupportTicketController::class, 'close'])->name('close');
                });

                /* Purchase Plan */
                Route::post('invest/plan',[HomeController::class,'investPlan'])->name('investPlan');
                Route::get('payment', [PaymentController::class, 'index'])->name('payment');
                Route::post('plan/purchase/request',[PurchasePlanController::class,'paymentRequest'])->name('plan.purchase.request');
                Route::get('/plan/investment',[HomeController::class,'investment'])->name('plan.investment');

                /* Project Invest */
                Route::post('project/invest',[ProjectInvestController::class,'invest'])->name('projectInvest');
                Route::get('project/invest/payment',[ProjectInvestController::class,'payment'])->name('project.payment');
                Route::post('invest/request',[ProjectInvestController::class,'investRequest'])->name('invest.request');
                Route::get('project/investment',[HomeController::class,'projectInvestment'])->name('project.investment');
            });


            Route::get('projects',[FrontendController::class,'projects'])->name('projects');
            Route::get('investment-plan',[FrontendController::class,'plans'])->name('plans');

            /* ===== Referral ===== */

            Route::get('/referral',[HomeController::class,'referral'])->name('referral');
            Route::get('/referral/bonus',[HomeController::class,'referralBonus'])->name('referral.bonus');
            Route::get('referral/bonus/history',[HomeController::class,'getReferralsBonus'])->name('referral.bonus.history');
            Route::post('get-referral-user',[HomeController::class,'getReferralUser'])->name('myGetDirectReferralUser');

            /* ===== Push Notification ===== */
            Route::get('push-notification-show', [InAppNotificationController::class, 'show'])->name('push.notification.show');
            Route::get('push.notification.readAll', [InAppNotificationController::class, 'readAll'])->name('push.notification.readAll');
            Route::get('push-notification-readAt/{id}', [InAppNotificationController::class, 'readAt'])->name('push.notification.readAt');

            /* Get Chart Data */

            Route::get('/invest/history',[HomeController::class,'investHistory'])->name('invest.history');
            Route::get('/deposit-payout/history',[HomeController::class,'depositPayout'])->name('depositPayout.history');
            Route::get('transaction/history',[HomeController::class,'transactionHistory'])->name('transaction.history');

            /* user notification permission */

            Route::get('notification/permission', [NotificationPermissionController::class, 'index'])->name('notification.permission');
            Route::post('notification/permission/update', [NotificationPermissionController::class, 'notifyPermissionUpdate'])->name('notification.permission.update');


            /* ===== Manage Two Step ===== */
            Route::get('two-step-security', [TwoStepSecurityController::class, 'twoStepSecurity'])->name('twostep.security');
            Route::post('twoStep-enable', [TwoStepSecurityController::class, 'twoStepEnable'])->name('twoStepEnable');
            Route::post('twoStep-disable', [TwoStepSecurityController::class, 'twoStepDisable'])->name('twoStepDisable');
            Route::post('twoStep/re-generate', [TwoStepSecurityController::class, 'twoStepRegenerate'])->name('twoStepRegenerate');


            Route::get('profile', [HomeController::class, 'profile'])->name('profile');
            Route::post('profile-update', [HomeController::class, 'profileUpdate'])->name('profile.update');
            Route::post('profile-update/image', [HomeController::class, 'profileUpdateImage'])->name('profile.update.image');
            Route::post('update/password', [HomeController::class, 'updatePassword'])->name('updatePassword');
            Route::post('kyc/submit', [HomeController::class, 'kycVerificationSubmit'])->name('kyc.verification.submit');
            Route::get('verification/center',[HomeController::class,'getUserKyc'])->name('show.user.kyc');

        });
    });

    /* Subscribe */
    Route::post('subscribe',[SubscriberController::class,'subscribe'])->name('subscribe');

    /*===== Contact ======*/

    Route::post('send/contact/info',[FrontendController::class,'sentContactInfo'])->name('sent.contact.info');

    /* Manual Recaptcha */
    Route::get('/captcha', [ManualRecaptchaController::class, 'reCaptCha'])->name('captcha');

    /* Manage Project Route */
    Route::get('/project/details/{slug}',[ProjectController::class,'details'])->name('project.details');

    /* Manage Blog */
    Route::get('/blogs',[BlogController::class,'index'])->name('blog');
    Route::get('/blog/details/{slug}',[BlogController::class,'details'])->name('blog.details');
    Route::get('/category/blog/{id}',[BlogController::class,'categoryBlogs'])->name('category.blogs');
    Route::get('/search/blogs',[BlogController::class,'search'])->name('search');

    /* Manage User Deposit */

    Route::get('supported-currency', [DepositController::class, 'supportedCurrency'])->name('supported.currency');
    Route::post('payment-request', [DepositController::class, 'paymentRequest'])->name('payment.request');
    Route::get('deposit-check-amount', [DepositController::class, 'checkAmount'])->name('deposit.checkAmount');
    Route::get('payment-check-amount', [PaymentController::class, 'checkAmount'])->name('payment.checkAmount');

    Route::get('payment-process/{trx_id}', [PaymentController::class, 'depositConfirm'])->name('payment.process');
    Route::post('addFundConfirm/{trx_id}', [PaymentController::class, 'fromSubmit'])->name('addFund.fromSubmit');
    Route::match(['get', 'post'], 'success', [PaymentController::class, 'success'])->name('success');
    Route::match(['get', 'post'], 'failed', [PaymentController::class, 'failed'])->name('failed');
    Route::match(['get', 'post'], 'payment/{code}/{trx?}/{type?}', [PaymentController::class, 'gatewayIpn'])->name('ipn');

    Route::post('khalti/payment/verify/{trx}', [\App\Http\Controllers\khaltiPaymentController::class, 'verifyPayment'])->name('khalti.verifyPayment');
    Route::post('khalti/payment/store', [khaltiPaymentController::class, 'storePayment'])->name('khalti.storePayment');

    Route::get('auth/{socialite}', [SocialiteController::class, 'socialiteLogin'])->name('socialiteLogin');
    Route::get('auth/callback/{socialite}', [SocialiteController::class, 'socialiteCallback'])->name('socialiteCallback');
    Auth::routes();

    /*= Frontend Manage Controller =*/

    Route::get("/{slug?}", [FrontendController::class, 'page'])->name('page');
});

