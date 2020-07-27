<?php

namespace Hiland\Biz\Ali;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use Hiland\Biz\ThinkAddon\TPCompatibleHelper;
use Hiland\Utils\Data\ObjectHelper;

/**
 * Class SmsHelper
 * @package Hiland\Biz\Ali
 */
class SmsHelper
{
    public static function send($phoneNumbers, $templateParamJson, $identificationNumber = "", &$returnValue)
    {
        $projectName = input("PN");
        if (ObjectHelper::isEmpty($projectName)) {
            $projectName = "";
        } else {
            $projectName .= "_";
        }

        $accessKeyID = TPCompatibleHelper::config("{$projectName}Machine.AliSMS.accessKeyID");
        $accessKeySecret = TPCompatibleHelper::config("{$projectName}Machine.AliSMS.accessKeySecret");
        $smsServerName = TPCompatibleHelper::config("{$projectName}Machine.AliSMS.smsServerName");
        $signName = TPCompatibleHelper::config("{$projectName}Machine.AliSMS.signName");
        $templateCode = TPCompatibleHelper::config("{$projectName}Machine.AliSMS.templateCode");

        AlibabaCloud::accessKeyClient($accessKeyID, $accessKeySecret)
            ->regionId($smsServerName)
            ->asDefaultClient();

        $returnValue["IdentificationNumber"] = $identificationNumber;
        $returnValue["PhoneNumbers"] = $phoneNumbers;
        $returnValue["TemplateParamJson"] = $templateParamJson;
        $returnValue["ReturnStatus"] = "fail";
        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => $smsServerName,
                        'PhoneNumbers' => $phoneNumbers,
                        'SignName' => $signName,
                        'TemplateCode' => $templateCode,
                        'TemplateParam' => $templateParamJson,
                    ],
                ])
                ->request();

            $result = $result->toArray();
            $returnValue = array_merge($returnValue + $result);

            if ($result["Code"] == "OK") {
                $returnValue["ReturnStatus"] = "success";
                return true;
            } else {
                return false;
            }
        } catch (ClientException $e) {
            $returnValue["Message"] = $e->getErrorMessage();
            return false;
        } catch (ServerException $e) {
            $returnValue["Message"] = $e->getErrorMessage();
            return false;
        }
    }
}