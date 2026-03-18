<?php

namespace App\Services\Agora;

class RtcTokenBuilder
{
    const ROLE_PUBLISHER = 1;
    const ROLE_SUBSCRIBER = 2;

    /**
     * Build the RTC token with uid.
     *
     * @param string $appId The App ID issued by Agora
     * @param string $appCertificate The App Certificate issued by Agora
     * @param string $channelName The unique channel name for the AgoraRTC session
     * @param int|string $uid User ID. A 32-bit unsigned integer or a string. 0 means any UID.
     * @param int $role ROLE_PUBLISHER (1) or ROLE_SUBSCRIBER (2)
     * @param int $tokenExpire The timestamp when the token expires (in seconds)
     * @param int $privilegeExpire The timestamp when the privilege expires (in seconds). Usually the same as tokenExpire.
     * @return string The generated token.
     */
    public static function buildTokenWithUid($appId, $appCertificate, $channelName, $uid, $role, $tokenExpire, $privilegeExpire = 0)
    {
        if ($privilegeExpire === 0) {
            $privilegeExpire = $tokenExpire;
        }

        $token = new AccessToken($appId, $appCertificate, $tokenExpire);
        $rtcService = new ServiceRtc($channelName, $uid);

        $rtcService->addPrivilege(ServiceRtc::PRIVILEGE_JOIN_CHANNEL, $privilegeExpire);
        
        if ($role == self::ROLE_PUBLISHER) {
            $rtcService->addPrivilege(ServiceRtc::PRIVILEGE_PUBLISH_AUDIO_STREAM, $privilegeExpire);
            $rtcService->addPrivilege(ServiceRtc::PRIVILEGE_PUBLISH_VIDEO_STREAM, $privilegeExpire);
            $rtcService->addPrivilege(ServiceRtc::PRIVILEGE_PUBLISH_DATA_STREAM, $privilegeExpire);
        }

        $token->addService($rtcService);

        return $token->build();
    }
}
