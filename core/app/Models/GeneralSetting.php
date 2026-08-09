<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model {
    use GlobalStatus;

    protected $casts = [
        'mail_config'           => 'object',
        'sms_config'            => 'object',
        'global_shortcodes'     => 'object',
        'socialite_credentials' => 'object',
        'firebase_config'       => 'object',
        'off_day'               => 'object',
        'withdraw_off_days'     => 'object',
        'trading_setting'       => 'object',
        'stock_setting'         => 'object',
        'forex_setting'         => 'object',
    ];

    protected $hidden = ['email_template', 'mail_config', 'sms_config', 'system_info'];

    /**
     * Get the site name optionally appended with a page title.
     *
     * @param string $pageTitle
     * @return string
     */
    public function siteName($pageTitle = '') {
        $pageTitle = empty($pageTitle) ? '' : ' - ' . $pageTitle;
        return $this->site_name . $pageTitle;
    }

    /**
     * Boot method to clear cache on save.
     */
    protected static function boot() {
        parent::boot();
        static::saved(function () {
            \Cache::forget('GeneralSetting');
        });
    }
}
