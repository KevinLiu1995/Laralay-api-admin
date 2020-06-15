<?php

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

/**
 * 发送短信demo
 * @param $phone // 发送短信的手机号码
 * @param $content // 发送短信的内容 array
 * @param $template_id // 短信模板 id
 * @param $sign_name // 短信签名
 * @return bool
 */
function sendMessage($phone, $content, $template_id, $sign_name)
{
	try {
		AlibabaCloud::accessKeyClient((string)config('app.ALI_ACCESS_KEY_ID'), (string)config('app.ALI_ACCESS_KEY_SECRET'))
			->regionId('cn-hangzhou')
			->asDefaultClient();
	} catch (ClientException $e) {
		Log::error('短信API配置有误' . PHP_EOL . $e->getMessage());
		return false;
	}

	try {
		$result = AlibabaCloud::rpc()
			->product('Dysmsapi')
			->version('2017-05-25')
			->action('SendSms')
			->method('POST')
			->host('dysmsapi.aliyuncs.com')
			->options([
				'query' => [
					'RegionId' => "cn-hangzhou",
					'PhoneNumbers' => $phone,
					'SignName' => $sign_name,
					'TemplateCode' => $template_id,
					'TemplateParam' => json_encode($content),
				],
			])
			->request();

		if ($result->toArray()['Code'] === 'OK') {
			Log::info('短信发送成功' . PHP_EOL . 'to:' . $phone . 'content' . json_encode($content));
			return true;
		}

		Log::error('短信发送失败：{message}', ['message' => $result]);
		return false;

	} catch (ClientException $e) {
		Log::error('短信发送失败' . PHP_EOL . $e->getMessage());
		return false;
	} catch (ServerException $e) {
		Log::error('短信发送失败' . PHP_EOL . $e->getMessage());
		return false;
	}
}
