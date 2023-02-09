<?php
/**
 * Created by: zhlhuang (364626853@qq.com)
 * Time: 2022/3/1 14:26
 * Blog: https://www.yuque.com/huangzhenlian
 */

declare(strict_types=1);

namespace App\Application\Sms\Service;

use App\Application\Sms\Model\SmsSendRecord;
use App\Exception\ErrorException;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use Overtrue\EasySms\Strategies\OrderStrategy;

class SmsService
{
    protected EasySms $easy_sms;

    protected SmsSettingService $sms_service;

    public function __construct(SmsSettingService $sms_service)
    {
        $this->sms_service = $sms_service;
        $gateway = $sms_service->getSmsSetting('sms_platform_id', '');
        $sms_platform_setting = $sms_service->getSmsSetting('sms_platform_setting', []);
        var_dump($sms_platform_setting);
        $config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,
            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => OrderStrategy::class,
                // 默认可用的发送网关
                'gateways' => [$gateway],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => BASE_PATH . '/runtime/log/easy-sms.log',
                ],
                $gateway => $sms_platform_setting,
            ],
        ];

        $this->easy_sms = new EasySms($config);
    }

    /**
     * @param $phone
     * @param $content
     * @return bool
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     * @throws ErrorException
     */
    public function sendByContent($phone, $content): bool
    {
        return $this->send($phone, '', [], $content);
    }

    /**
     * @param        $phone
     * @param        $template
     * @param        $data
     * @param string $content
     * @return bool
     * @throws \Throwable
     */
    public function send($phone, $template, $data, string $content = ''): bool
    {
        $gateway = $this->sms_service->getSmsSetting('sms_platform_id', '');
        $send_record = new SmsSendRecord();
        $send_record->sms_platform_id = $gateway;
        $send_record->phone = $phone;
        $send_record->template = $template;
        $send_record->content = $content;
        $send_record->param_data = json_encode($data);
        $res_exception = null;
        try {
            $res = $this->easy_sms->send($phone, compact('template', 'content', 'data'));
            if (!empty($res[$gateway])) {
                $result_status = $res[$gateway]['status'] ?? "";
                $result_info = $res[$gateway]['result'] ?? [];
                $send_record->result_status = $result_status;
                $send_record->result_info = is_array($result_info) ? json_encode($result_info) : $result_info;
                if ($result_status !== 'success') {
                    //返回失败
                    $res_exception = new ErrorException('平台执行错误' . $send_record->result_info);
                }
            } else {
                $send_record->result_status = 'failed';
                $send_record->result_info = is_array($res) ? json_encode($res) : $res;
                //没有该平台返回结果
                $res_exception = new ErrorException('短信配置信息错误' . json_encode($res));
            }
        } catch (NoGatewayAvailableException $exceptions) {
            var_dump('NoGatewayAvailableException');
            $exception = $exceptions->getExceptions()[0] ?? $exceptions;
            //执行错误
            $send_record->result_status = 'failed';
            $send_record->result_info = $exception->getMessage();
            $res_exception = new ErrorException($exception->getMessage());
        } catch (\Throwable $throwable) {
            $send_record->result_status = 'failed';
            $send_record->result_info = $throwable->getMessage();
            $res_exception = new ErrorException($throwable->getMessage());
        }
        if ($res_exception) {
            //执行错误，将记录保存扔出抛出异常
            $send_record->save();
            throw $res_exception;
        }

        return $send_record->save();
    }
}