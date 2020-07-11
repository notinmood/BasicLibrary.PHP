<?php

namespace Hiland\Biz\Ali;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use Hiland\Biz\ThinkAddon\TPCompatibleHelper;

/**
 * Class SmsHelper
 * @package Hiland\Biz\Ali
 */
class SmsHelper
{
    public static function send($phoneNumbers, $templateParamJson, $identificationNumber = "", &$returnValue)
    {
        $accessKeyID = TPCompatibleHelper::config("anlianMachine.AliSMS.accessKeyID");
        $accessKeySecret = TPCompatibleHelper::config("anlianMachine.AliSMS.accessKeySecret");
        $smsServerName = TPCompatibleHelper::config("anlianMachine.AliSMS.smsServerName");
        $signName = TPCompatibleHelper::config("anlianMachine.AliSMS.signName");
        $templateCode = TPCompatibleHelper::config("anlianMachine.AliSMS.templateCode");

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