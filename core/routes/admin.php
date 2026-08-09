<?php

use Illuminate\Support\Facades\Route;


Route::namespace('Auth')->group(function () {
    Route::middleware('admin.guest')->group(function(){
        Route::controller('LoginController')->group(function () {
            Route::get('/', 'showLoginForm')->name('login');
            Route::post('/', 'login')->name('login');
            Route::get('logout', 'logout')->middleware('admin')->withoutMiddleware('admin.guest')->name('logout');
        });

        // Admin Password Reset
        Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function(){
            Route::get('reset', 'showLinkRequestForm')->name('reset');
            Route::post('reset', 'sendResetCodeEmail');
            Route::get('code-verify', 'codeVerify')->name('code.verify');
            Route::post('verify-code', 'verifyCode')->name('verify.code');
        });

        Route::controller('ResetPasswordController')->group(function(){
            Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
            Route::post('password/reset/change', 'reset')->name('password.change');
        });
    });
});

Route::middleware('admin')->group(function () {
    Route::controller('AdminController')->group(function(){
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('pending-counts', 'getPendingCounts')->name('pending.counts');
        Route::get('chart/deposit-withdraw', 'depositAndWithdrawReport')->name('chart.deposit.withdraw');
        Route::get('chart/transaction', 'transactionReport')->name('chart.transaction');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');
       
        //Validation
        Route::controller('AdminSecurityController')->middleware(\App\Http\Middleware\AdminRole::class.':admin.security')->group(function(){
            Route::get('security', 'security')->name('security');
            Route::post('security', 'securityStore')->name('security.store');
            Route::post('security/gateways', 'securityGatewaysUpdate')->name('security.gateways.update');
             Route::post('security/route', 'securityRouteUpdate')->name('security.route.update');
            Route::get('verify-security', 'verifySecurityForm')->name('security.verify');
            Route::post('verify-security', 'verifySecurity')->name('security.verify.post');
            Route::get('security/login-history', 'loginHistory')->name('security.login.history');
            Route::post('security/ip-ban', 'ipBan')->name('security.ip.ban');
        });

        //Notification
        Route::get('notifications','notifications')->name('notifications');
        Route::get('notification/read/{id}','notificationRead')->name('notification.read');
        Route::get('notifications/read-all','readAllNotification')->name('notifications.read.all');
        Route::post('notifications/delete-all','deleteAllNotification')->name('notifications.delete.all');
        Route::post('notifications/delete-single/{id}','deleteSingleNotification')->name('notifications.delete.single');



        Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
    });


     // Referral
    Route::controller('ReferralController')->middleware(\App\Http\Middleware\AdminRole::class.':admin.referrals.*')->name('referrals.')->prefix('referrals')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('update', 'update')->name('update');
        Route::get('status/{id}', 'status')->name('status');
    });

    // Time Controller
    Route::controller('TimeSettingController')->name('time.')->prefix('time')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');

    });



    // Plan Controller
    Route::controller('PlanController')->name('plan.')->prefix('plan')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('locked/{id}', 'locked')->name('locked');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('delete/{id}', 'delete')->name('delete');

        Route::post('invest/cancel', 'cancelInvest')->name('invest.cancel');
    });

    Route::controller('MiningPlanController')->name('mining.')->prefix('mining')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    Route::controller('StakingPoolController')->group(function () {


        Route::name('pool.')->prefix('pool')->group(function () {
            Route::get('/', 'pool')->name('index');
            Route::post('save/{id?}', 'savePool')->name('save');
            Route::post('status/{id?}', 'poolStatus')->name('status');
            Route::post('dispatch', 'dispatchPool')->name('dispatch');
            Route::get('/invest', 'poolInvest')->name('invest');
        });

    });

    //Promotional Banner
    Route::controller('PromotionalToolController')->prefix('promotional-tool')->name('promotional.tool.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('remove/{id}', 'remove')->name('remove');
    });

    Route::controller('RankingController')->name('ranking.')->prefix('user/ranking')->group(function () {
        Route::get('list', 'list')->name('list');
        Route::post('store/{id?}', 'store')->name('store');
        Route::post('status/{id}', 'status')->name('status');
    });


    // Salary System
    Route::controller('SalaryController')->middleware(\App\Http\Middleware\AdminRole::class.':admin.salary.*')->name('salary.')->prefix('salary')->group(function(){
        Route::get('/', 'salary')->name('list');
        Route::post('save', 'save')->name('save');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('remove/{id}', 'remove')->name('remove');
    });

    // Promo Code System
    Route::controller('PromoCodeController')->name('promocode.')->prefix('promocode')->group(function () {
        Route::get('list', 'index')->name('list');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('remove/{id}', 'remove')->name('remove');
    });

    // Daily Reward System
    Route::controller('DailyRewardController')->name('daily_reward.')->prefix('daily-reward')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('update/{id}', 'update')->name('update');
    });

    // Games Manager
    Route::controller('GameController')->name('games.')->prefix('games')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::get('aviator/logs', 'aviatorLogs')->name('aviator.logs');
        Route::get('aviator/dashboard', 'aviatorDashboard')->name('aviator.dashboard');
        Route::get('aviator/poll', 'aviatorPoll')->name('aviator.poll');

    });

    // Users Manager
    Route::controller('ManageUsersController')->name('users.')->prefix('users')->group(function(){
        Route::middleware(\App\Http\Middleware\AdminRole::class.':admin.users.*')->group(function(){
            Route::get('/', 'allUsers')->name('all');
            Route::get('active', 'activeUsers')->name('active');
            Route::get('banned', 'bannedUsers')->name('banned');
            Route::get('email-verified', 'emailVerifiedUsers')->name('email.verified');
            Route::get('email-unverified', 'emailUnverifiedUsers')->name('email.unverified');
            Route::get('mobile-unverified', 'mobileUnverifiedUsers')->name('mobile.unverified');
            Route::get('kyc-unverified', 'kycUnverifiedUsers')->name('kyc.unverified');
            Route::get('kyc-pending', 'kycPendingUsers')->name('kyc.pending');
            Route::get('mobile-verified', 'mobileVerifiedUsers')->name('mobile.verified');
            Route::get('with-balance', 'usersWithBalance')->name('with.balance');
    
            Route::get('detail/{id}', 'detail')->name('detail');
            Route::get('kyc-data/{id}', 'kycDetails')->name('kyc.details');
            Route::post('kyc-approve/{id}', 'kycApprove')->name('kyc.approve');
            Route::post('kyc-reject/{id}', 'kycReject')->name('kyc.reject');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('add-sub-balance/{id}', 'addSubBalance')->name('add.sub.balance');
            Route::get('send-notification/{id}', 'showNotificationSingleForm')->name('notification.single');
            Route::post('send-notification/{id}', 'sendNotificationSingle')->name('notification.single');
            Route::get('login/{id}', 'login')->name('login');
            Route::post('status/{id}', 'status')->name('status');
            Route::post('change-pass/{id}', 'userPassChange')->name('change.pass');
    
            Route::get('send-notification', 'showNotificationAllForm')->name('notification.all');
            Route::post('send-notification', 'sendNotificationAll')->name('notification.all.send');
            Route::get('list', 'list')->name('list');
            Route::get('count-by-segment/{methodName}', 'countBySegment')->name('segment.count');
            Route::get('notification-log/{id}', 'notificationLog')->name('notification.log');
            Route::post('activate-plan/{id}', 'activatePlan')->name('activate.plan');
        });

        Route::get('profitable', 'profitableUsers')->middleware(\App\Http\Middleware\AdminRole::class.':admin.users.profitable')->name('profitable');
        Route::get('duplicate', 'duplicateUsers')->middleware(\App\Http\Middleware\AdminRole::class.':admin.users.duplicate')->name('duplicate');
    });

    // Subscriber
    Route::controller('SubscriberController')->prefix('subscriber')->name('subscriber.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('send-email', 'sendEmailForm')->name('send.email');
        Route::post('remove/{id}', 'remove')->name('remove');
        Route::post('send-email', 'sendEmail')->name('send.email');
    });

    // Leaderboard
    Route::controller('LeaderboardController')->middleware(\App\Http\Middleware\AdminRole::class.':admin.leaderboard.*')->name('leaderboard.')->prefix('leaderboard')->group(function(){
        Route::get('/', 'index')->name('index');
    });

    // Deposit Gateway
    Route::name('gateway.')->prefix('gateway')->group(function(){
        // Automatic Gateway
        Route::controller('AutomaticGatewayController')->prefix('automatic')->name('automatic.')->group(function(){
            Route::get('/', 'index')->name('index');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{code}', 'update')->name('update');
            Route::post('remove/{id}', 'remove')->name('remove');
            Route::post('status/{id}', 'status')->name('status');
        });


        // Manual Methods
        Route::controller('ManualGatewayController')->prefix('manual')->name('manual.')->group(function(){
            Route::get('/', 'index')->name('index');
            Route::get('new', 'create')->name('create');
            Route::post('new', 'store')->name('store');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
            Route::post('delete/{id}', 'delete')->name('delete');
        });
    });


    // DEPOSIT SYSTEM
    Route::controller('DepositController')->middleware(\App\Http\Middleware\AdminRole::class.':admin.deposit.*')->prefix('deposit')->name('deposit.')->group(function () {
        Route::get('all/{user_id?}', 'deposit')->name('list');
        Route::get('pending/{user_id?}', 'pending')->name('pending');
        Route::get('rejected/{user_id?}', 'rejected')->name('rejected');
        Route::get('approved/{user_id?}', 'approved')->name('approved');
        Route::get('successful/{user_id?}', 'successful')->name('successful');
        Route::get('initiated/{user_id?}', 'initiated')->name('initiated');
        Route::get('details/{id}', 'details')->name('details');
        Route::post('reject', 'reject')->name('reject');
        Route::post('approve/{id}', 'approve')->name('approve');

    });


    // WITHDRAW SYSTEM
    Route::middleware(\App\Http\Middleware\AdminRole::class.':admin.withdraw.*')->prefix('withdraw')->name('withdraw.')->group(function(){

        Route::controller('WithdrawalController')->name('data.')->group(function(){
            Route::get('pending/{user_id?}', 'pending')->name('pending');
            Route::get('approved/{user_id?}', 'approved')->name('approved');
            Route::get('rejected/{user_id?}', 'rejected')->name('rejected');
            Route::get('all/{user_id?}', 'all')->name('all');
            Route::get('details/{id}', 'details')->name('details');
            Route::post('approve', 'approve')->name('approve');
            Route::post('reject', 'reject')->name('reject');
        });


        // Withdraw Method
        Route::controller('WithdrawMethodController')->prefix('method')->name('method.')->group(function(){
            Route::get('/', 'methods')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('create', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('edit/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
            Route::post('delete/{id}', 'delete')->name('delete');
        });
    });

    // Report
    Route::controller('ReportController')->prefix('report')->name('report.')->group(function(){
        Route::get('transaction/{user_id?}', 'transaction')->name('transaction');
        Route::get('login/history', 'loginHistory')->name('login.history');
        Route::get('login/ipHistory/{ip}', 'loginIpHistory')->name('login.ipHistory');
        Route::get('notification/history', 'notificationHistory')->name('notification.history');
        Route::get('email/detail/{id}', 'emailDetails')->name('email.details');
        Route::get('invest/history', 'investHistory')->name('invest.history');
        Route::get('invest/details/{id}', 'investDetails')->name('invest.details');
    });

     //Invest report
     Route::controller('InvestReportController')->name('invest.report.')->prefix('invest/report')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('invest-statistics', 'investStatistics')->name('statistics');
        Route::get('invest-statistics-by-plan', 'investStatisticsByPlan')->name('statistics.plan');
        Route::get('invest-interest-statistics', 'investInterestStatistics')->name('interest');
        Route::get('invest-interest-chart', 'investInterestChart')->name('interest.chart');
    });


    // // Support Ticket
    Route::controller('SupportTicketController')->middleware(\App\Http\Middleware\AdminRole::class.':admin.ticket.*')->prefix('ticket')->name('ticket.')->group(function(){
        Route::get('/', 'tickets')->name('index');
        Route::get('pending/{user_id?}', 'pendingTicket')->name('pending');
        Route::get('closed', 'closedTicket')->name('closed');
        Route::get('answered', 'answeredTicket')->name('answered');
        Route::get('view/{id}', 'ticketReply')->name('view');
        Route::post('reply/{id}', 'replyTicket')->name('reply');
        Route::post('close/{id}', 'closeTicket')->name('close');
        Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
        Route::post('delete/{id}', 'ticketDelete')->name('delete');
    });


    // Language Manager
    Route::controller('LanguageController')->prefix('language')->name('language.')->group(function(){
        Route::get('/', 'langManage')->name('manage');
        Route::post('/', 'langStore')->name('manage.store');
        Route::post('delete/{id}', 'langDelete')->name('manage.delete');
        Route::post('update/{id}', 'langUpdate')->name('manage.update');
        Route::get('edit/{id}', 'langEdit')->name('key');
        Route::post('import', 'langImport')->name('import.lang');
        Route::post('store/key/{id}', 'storeLanguageJson')->name('store.key');
        Route::post('delete/key/{id}', 'deleteLanguageJson')->name('delete.key');
        Route::post('update/key/{id}', 'updateLanguageJson')->name('update.key');
        Route::get('get-keys', 'getKeys')->name('get.key');
    });

    Route::controller('TradingSettingController')->prefix('trading-setting')->name('trading.setting.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::post('update', 'update')->name('update');
    });

    Route::controller('AiBotController')->prefix('ai-bots')->name('aibots.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('delete/{id}', 'delete')->name('delete');
    });

    Route::controller('GeneralSettingController')->middleware(\App\Http\Middleware\AdminRole::class.':admin.setting.*')->group(function(){

        Route::get('system-setting', 'systemSetting')->name('setting.system');

        // General Setting
        Route::get('general-setting', 'general')->name('setting.general');
        Route::post('general-setting', 'generalUpdate');

        Route::get('setting/social/credentials', 'socialiteCredentials')->name('setting.socialite.credentials');
        Route::post('setting/social/credentials/update/{key}', 'updateSocialiteCredential')->name('setting.socialite.credentials.update');
        Route::post('setting/social/credentials/status/{key}', 'updateSocialiteCredentialStatus')->name('setting.socialite.credentials.status.update');

        //configuration
        Route::get('setting/system-configuration','systemConfiguration')->name('setting.system.configuration');
        Route::post('setting/system-configuration','systemConfigurationSubmit');

        // Logo-Icon
        Route::get('setting/logo-icon', 'logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'logoIconUpdate')->name('setting.logo.icon');

        //Custom CSS
        Route::get('custom-css','customCss')->name('setting.custom.css');
        Route::post('custom-css','customCssSubmit');

        Route::get('sitemap','sitemap')->name('setting.sitemap');
        Route::post('sitemap','sitemapSubmit');

        Route::get('robot','robot')->name('setting.robot');
        Route::post('robot','robotSubmit');

        //Cookie
        Route::get('cookie','cookie')->name('setting.cookie');
        Route::post('cookie','cookieSubmit');

        //maintenance_mode
        Route::get('maintenance-mode','maintenanceMode')->name('maintenance.mode');
        Route::post('maintenance-mode','maintenanceModeSubmit');

         //Holiday
         Route::get('holiday-setting', 'holiday')->name('setting.holiday');
         Route::post('holiday-setting/submit', 'holidaySubmit')->name('setting.holiday.submit');
         Route::post('holiday-remove/{id}', 'remove')->name('setting.remove');
         //Offday
         Route::post('offday-setting', 'offDaySubmit')->name('setting.offday');
         Route::post('system-data-reset', 'deleteAllData')->name('system.reset');
    });


    Route::controller('CronConfigurationController')->name('cron.')->prefix('cron')->group(function () {
        Route::get('index', 'cronJobs')->name('index');
        Route::post('store', 'cronJobStore')->name('store');
        Route::post('update', 'cronJobUpdate')->name('update');
        Route::post('delete/{id}', 'cronJobDelete')->name('delete');
        Route::get('schedule', 'schedule')->name('schedule');
        Route::post('schedule/store', 'scheduleStore')->name('schedule.store');
        Route::post('schedule/status/{id}', 'scheduleStatus')->name('schedule.status');
        Route::get('schedule/pause/{id}', 'schedulePause')->name('schedule.pause');
        Route::get('schedule/logs/{id}', 'scheduleLogs')->name('schedule.logs');
        Route::post('schedule/log/resolved/{id}', 'scheduleLogResolved')->name('schedule.log.resolved');
        Route::post('schedule/log/flush/{id}', 'logFlush')->name('log.flush');
    });


    //KYC setting
    Route::controller('KycController')->group(function(){
        Route::get('kyc-setting','setting')->name('kyc.setting');
        Route::post('kyc-setting','settingUpdate');
    });

    //Notification Setting
    Route::name('setting.notification.')->controller('NotificationController')->prefix('notification')->group(function(){
        //Template Setting
        Route::get('global/email','globalEmail')->name('global.email');
        Route::post('global/email/update','globalEmailUpdate')->name('global.email.update');

        Route::get('global/sms','globalSms')->name('global.sms');
        Route::post('global/sms/update','globalSmsUpdate')->name('global.sms.update');

        Route::get('global/push','globalPush')->name('global.push');
        Route::post('global/push/update','globalPushUpdate')->name('global.push.update');

        Route::get('templates','templates')->name('templates');
        Route::get('template/edit/{type}/{id}','templateEdit')->name('template.edit');
        Route::post('template/update/{type}/{id}','templateUpdate')->name('template.update');

        //Email Setting
        Route::get('email/setting','emailSetting')->name('email');
        Route::post('email/setting','emailSettingUpdate');
        Route::post('email/test','emailTest')->name('email.test');

        //SMS Setting
        Route::get('sms/setting','smsSetting')->name('sms');
        Route::post('sms/setting','smsSettingUpdate');
        Route::post('sms/test','smsTest')->name('sms.test');

        Route::get('notification/push/setting', 'pushSetting')->name('push');
        Route::post('notification/push/setting', 'pushSettingUpdate');
        Route::post('notification/push/setting/upload', 'pushSettingUpload')->name('push.upload');
        Route::get('notification/push/setting/download', 'pushSettingDownload')->name('push.download');
    });

    // Plugin
    Route::controller('ExtensionController')->prefix('extensions')->name('extensions.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
    });


    //System Information
    Route::controller('SystemController')->middleware(\App\Http\Middleware\AdminRole::class.':admin.system.*')->name('system.')->prefix('system')->group(function(){
        Route::get('info','systemInfo')->name('info');
        Route::get('server-info','systemServerInfo')->name('server.info');
        Route::get('optimize', 'optimize')->name('optimize');
        Route::get('optimize-clear', 'optimizeClear')->name('optimize.clear');
        Route::get('system-update','systemUpdate')->name('update');
        Route::post('system-update','systemUpdateProcess')->name('update.process');
        Route::get('system-update/log','systemUpdateLog')->name('update.log');
    });

    // Staff Management
    Route::controller('ManageAdminsController')->middleware(\App\Http\Middleware\AdminRole::class.':admin.staff.*')->name('staff.')->prefix('staff')->group(function(){
        Route::get('admins', 'admins')->name('admins');
        Route::post('admin/store/{id?}', 'adminStore')->name('admin.store');
        Route::post('admin/remove/{id}', 'adminRemove')->name('admin.remove');

        Route::get('roles', 'roles')->name('roles');
        Route::get('role/create', 'roleCreate')->name('role.create');
        Route::post('role/store/{id?}', 'roleStore')->name('role.store');
        Route::get('role/edit/{id}', 'roleEdit')->name('role.edit');
    });


    // SEO
    Route::get('seo', 'FrontendController@seoEdit')->name('seo');


    // Frontend
    Route::middleware(\App\Http\Middleware\AdminRole::class.':admin.frontend.*')->name('frontend.')->prefix('frontend')->group(function () {

        Route::controller('FrontendController')->group(function(){
            Route::get('index', 'index')->name('index');
            Route::get('templates', 'templates')->name('templates');
            Route::post('templates', 'templatesActive')->name('templates.active');
            Route::post('templates/upload', 'templateUpload')->name('templates.upload');
            Route::get('frontend-sections/{key?}', 'frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'frontendElement')->name('sections.element');
            Route::get('frontend-slug-check/{key}/{id?}', 'frontendElementSlugCheck')->name('sections.element.slug.check');
            Route::get('frontend-element-seo/{key}/{id}', 'frontendSeo')->name('sections.element.seo');
            Route::post('frontend-element-seo/{key}/{id}', 'frontendSeoUpdate');
            Route::post('remove/{id}', 'remove')->name('remove');
        });

        // Page Builder
        Route::controller('PageBuilderController')->group(function(){
            Route::get('manage-pages', 'managePages')->name('manage.pages');
            Route::get('manage-pages/check-slug/{id?}', 'checkSlug')->name('manage.pages.check.slug');
            Route::post('manage-pages', 'managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete/{id}', 'managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'manageSectionUpdate')->name('manage.section.update');

            Route::get('manage-seo/{id}','manageSeo')->name('manage.pages.seo');
            Route::post('manage-seo/{id}','manageSeoStore');
        });

    });

        Route::controller('VipPlanController')->name('vip.')->prefix('vip-plans')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
            Route::post('delete/{id}', 'delete')->name('delete');
        });

    // PTC Ads Manager
    Route::controller('PtcController')->name('ptc.')->prefix('ptc')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('store', 'store')->name('store');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
    });

    // Earning Methods Manager
    Route::controller('EarningMethodController')->middleware(\App\Http\Middleware\AdminRole::class.':admin.earning.methods.*')->name('earning.methods.')->prefix('earning-methods')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
    });

    // App Settings
    Route::controller('AppPlayStoreController')->middleware(\App\Http\Middleware\AdminRole::class.':admin.app.settings.*')->name('app.settings.')->prefix('app-settings')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::post('update', 'update')->name('update');
    });

    // Stock Manager
    Route::controller('StockController')->name('stock.')->prefix('stock')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('setting', 'setting')->name('setting');
        Route::post('setting', 'settingUpdate')->name('setting.update');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
    });

    // Forex Manager
    Route::controller('ForexController')->name('forex.')->prefix('forex')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('setting', 'setting')->name('setting');
        Route::post('setting', 'settingUpdate')->name('setting.update');
        Route::post('store', 'store')->name('store');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
    });

    // Analytics
    Route::controller('AnalyticsController')->name('analytics.')->prefix('analytics')->group(function () {
        Route::get('/', 'index')->name('index');
    });

});

