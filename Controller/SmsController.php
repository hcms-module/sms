<?php

declare(strict_types=1);

namespace App\Application\Sms\Controller;

use App\Annotation\View;
use App\Application\Admin\Controller\AdminAbstractController;
use App\Application\Admin\Lib\RenderParam;
use App\Application\Admin\Middleware\AdminMiddleware;
use App\Application\Sms\Model\SmsSendRecord;
use App\Application\Sms\Service\SmsService;
use App\Application\Sms\Service\SmsSettingService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;


/**
 * @Middleware(AdminMiddleware::class)
 * @Controller(prefix="sms/sms")
 */
class SmsController extends AdminAbstractController
{

    /**
     * @Inject()
     */
    protected SmsSettingService $sms_setting;

    /**
     * @Inject()
     */
    protected SmsService $sms_service;

    /**
     * @PostMapping(path="setting")
     */
    public function settingSave()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'sms_platform_id' => 'required',
        ], [
            'sms_platform_id.required' => '请选择短信平台',
        ]);
        if ($validator->fails()) {
            return $this->returnErrorJson($validator->errors()
                ->first());
        }
        $sms_platform_id = $this->request->post('sms_platform_id', '');
        $sms_platform_setting = $this->request->post('sms_platform_setting', []);

        $res = $this->sms_setting->setSmsSetting(compact('sms_platform_id', 'sms_platform_setting'));

        return $res ? $this->returnSuccessJson() : $this->returnErrorJson();
    }


    /**
     * @GetMapping(path="setting/info")
     */
    public function settingInfo()
    {
        $setting = $this->sms_setting->getSmsSetting();

        return $this->returnSuccessJson(compact('setting'));
    }

    /**
     * @PostMapping(path="index/test")
     */
    public function smsTest()
    {
        $validator = $this->validationFactory->make($this->request->all(), [
            'phone' => 'required',
            'template' => 'required',
        ], [
            'phone.required' => '请输入接收手机',
            'template.required' => '请输入短信模板',
        ]);
        if ($validator->fails()) {
            return $this->returnErrorJson($validator->errors()
                ->first());
        }
        $phone = $this->request->post('phone', '');
        $template = $this->request->post('template', '');
        $param_data = json_decode($this->request->post('param_data', ''), true);
        if (!is_array($param_data)) {
            return $this->returnErrorJson('参数格式错误');
        }
        $res = $this->sms_service->send($phone, $template, $param_data);

        return $res ? $this->returnSuccessJson() : $this->returnErrorJson();
    }

    /**
     * @PostMapping(path="index/delete")
     */
    public function recordDelete()
    {
        $record_id = (int)$this->request->input('record_id', 0);
        $record = SmsSendRecord::find($record_id);
        if (!$record) {
            return $this->returnErrorJson('找不到该记录');
        }

        return $record->delete() ? $this->returnSuccessJson() : $this->returnErrorJson();
    }

    /**
     * @GetMapping(path="index/lists")
     */
    public function lists()
    {
        $where = [];
        $phone = $this->request->input('phone', '');
        $template = $this->request->input('template', '');
        $result_status = $this->request->input('result_status', '');
        if ($phone) {
            $where[] = ['phone', 'like', "%{$phone}%"];
        }
        if ($template) {
            $where[] = ['template', 'like', "%{$template}%"];
        }
        if ($result_status) {
            $where[] = ['result_status', '=', $result_status];
        }
        $lists = SmsSendRecord::where($where)
            ->orderBy('record_id', 'DESC')
            ->paginate();

        return self::returnSuccessJson(compact('lists'));
    }

    /**
     * @View()
     * @GetMapping(path="setting")
     */
    public function setting()
    {
        return RenderParam::display();
    }

    /**
     * @View()
     * @GetMapping(path="index")
     */
    public function index()
    {
        return RenderParam::display();
    }
}
