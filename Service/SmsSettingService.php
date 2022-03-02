<?php
/**
 * Created by: zhlhuang (364626853@qq.com)
 * Time: 2022/3/1 13:45
 * Blog: https://www.yuque.com/huangzhenlian
 */

declare(strict_types=1);

namespace App\Application\Sms\Service;

use App\Service\AbstractSettingService;

class SmsSettingService extends AbstractSettingService
{
    /**
     * 获取短信配置
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    function getSmsSetting(string $key = '', $default = '')
    {
        return $this->getSettings('sms', $key, $default);
    }

    /**
     * 保存短信配置
     *
     * @param array $setting
     * @return bool
     */
    function setSmsSetting(array $setting): bool
    {
        if (!empty($setting['sms_platform_setting']) && is_array($setting['sms_platform_setting'])) {
            $setting['sms_platform_setting'] = json_encode($setting['sms_platform_setting']);
        }

        return $this->saveSetting($setting, 'sms');
    }
}