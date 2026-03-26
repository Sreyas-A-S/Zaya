<?php

namespace App\Services\Agora;

abstract class Service
{
    protected $type;
    protected $privileges = [];

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function getServiceType()
    {
        return $this->type;
    }

    public function addPrivilege($privilege, $expire)
    {
        $this->privileges[$privilege] = $expire;
    }

    public function pack()
    {
        $out = pack("v", $this->type);
        $out .= pack("v", count($this->privileges));
        ksort($this->privileges);
        foreach ($this->privileges as $key => $value) {
            $out .= pack("v", $key);
            $out .= pack("V", $value);
        }
        return $out;
    }
}

class ServiceRtc extends Service
{
    const SERVICE_TYPE = 1;
    const PRIVILEGE_JOIN_CHANNEL = 1;
    const PRIVILEGE_PUBLISH_AUDIO_STREAM = 2;
    const PRIVILEGE_PUBLISH_VIDEO_STREAM = 3;
    const PRIVILEGE_PUBLISH_DATA_STREAM = 4;

    public $channelName;
    public $uid;

    public function __construct($channelName, $uid)
    {
        parent::__construct(self::SERVICE_TYPE);
        $this->channelName = $channelName;
        $this->uid = $uid;
    }

    public function pack()
    {
        $out = parent::pack();
        // Pack string with 2-byte length
        $out .= pack("v", strlen($this->channelName)) . $this->channelName;
        // IMPORTANT: Integer UID must be packed as 4-byte uint32 (V) for buildTokenWithUid
        $out .= pack("V", (int)$this->uid);
        return $out;
    }
}

class AccessToken
{
    const VERSION = "007";
    
    public $appId;
    public $appCertificate;
    public $expire;
    public $issueTimestamp;
    public $salt;
    public $services = [];

    public function __construct($appId = "", $appCertificate = "", $expire = 900)
    {
        $this->appId = $appId;
        $this->appCertificate = $appCertificate;
        $this->expire = $expire;
        $this->issueTimestamp = time();
        $this->salt = rand(1, 99999999);
    }

    public function addService($service)
    {
        $this->services[$service->getServiceType()] = $service;
    }

    public function build()
    {
        if (!$this->appId || !$this->appCertificate) {
            return "";
        }

        $data = $this->pack();
        
        // Agora V7 Token structure: 
        // 1. Calculate signature using HMAC-SHA256
        $signature = hash_hmac("sha256", $data, $this->appCertificate, true);
        
        // 2. Add CRC32 of AppID and CRC32 of Data
        $appIdCrc = crc32($this->appId) & 0xFFFFFFFF;
        $dataCrc = crc32($data) & 0xFFFFFFFF;
        
        // 3. The actual content to compress is [Signature (32 bytes) + AppID_CRC (4 bytes) + Data_CRC (4 bytes) + Data]
        $content = $signature . pack("V", $appIdCrc) . pack("V", $dataCrc) . $data;
        
        // 4. Compress using ZLIB (standard deflate)
        $compressed = zlib_encode($content, ZLIB_ENCODING_DEFLATE);
        
        return self::VERSION . base64_encode($compressed);
    }

    public function pack()
    {
        $out = pack("V", $this->issueTimestamp);
        $out .= pack("V", $this->expire);
        $out .= pack("V", $this->salt);
        $out .= pack("v", count($this->services));

        ksort($this->services);
        foreach ($this->services as $service) {
            $out .= $service->pack();
        }

        // AppID must be packed with 2-byte length prefix
        return pack("v", strlen($this->appId)) . $this->appId . $out;
    }
}
