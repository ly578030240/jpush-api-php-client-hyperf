<?php
namespace JPush;
use InvalidArgumentException;

class ReportPayload {
    private static $EFFECTIVE_TIME_UNIT = array('HOUR', 'DAY', 'MONTH');

    private $client;
    private $coHttp;
    /**
     * ReportPayload constructor.
     * @param $client JPush
     */
    public function __construct($client)
    {
        $this->client = $client;
        $this->coHttp=new CoHttp();
    }

    public function getReceived($msgIds) {
        $queryParams = '?msg_ids=';
        if (is_array($msgIds) && !empty($msgIds)) {
            $msgIdsStr = implode(',', $msgIds);
            $queryParams .= $msgIdsStr;
        } elseif (is_string($msgIds)) {
            $queryParams .= $msgIds;
        } else {
            throw new InvalidArgumentException("Invalid msg_ids");
        }

        $url = $this->client->makeURL('report') . 'received' . $queryParams;
        return $this->coHttp->get($this->client, $url);
    }

    /*
     送达统计详情（新）
     https://docs.jiguang.cn/jpush/server/push/rest_api_v3_report/#_7
    */
    public function getReceivedDetail($msgIds) {
        $queryParams = '?msg_ids=';
        if (is_array($msgIds) && !empty($msgIds)) {
            $msgIdsStr = implode(',', $msgIds);
            $queryParams .= $msgIdsStr;
        } elseif (is_string($msgIds)) {
            $queryParams .= $msgIds;
        } else {
            throw new InvalidArgumentException("Invalid msg_ids");
        }

        $url = $this->client->makeURL('report') . 'received/detail' . $queryParams;
        return $this->coHttp->get($this->client, $url);
    }

    public function getMessageStatus($msgId, $rids, $data = null) {
        $url = $this->client->makeURL('report') . 'status/message';
        $registrationIds = is_array($rids) ? $rids : array($rids);
        $body = [
            'msg_id' => $msgId,
            'registration_ids' => $registrationIds
        ];
        if (!is_null($data)) {
            $body['data'] = $data;
        }
        return $this->coHttp->post($this->client, $url, $body);
    }

    public function getMessages($msgIds) {
        $queryParams = '?msg_ids=';
        if (is_array($msgIds) && !empty($msgIds)) {
            $msgIdsStr = implode(',', $msgIds);
            $queryParams .= $msgIdsStr;
        } elseif (is_string($msgIds)) {
            $queryParams .= $msgIds;
        } else {
            throw new InvalidArgumentException("Invalid msg_ids");
        }

        $url = $this->client->makeURL('report') . 'messages/' .$queryParams;
        return $this->coHttp->get($this->client, $url);
    }

    /*
     消息统计详情（VIP 专属接口，新）
     https://docs.jiguang.cn/jpush/server/push/rest_api_v3_report/#vip_1
    */
    public function getMessagesDetail($msgIds) {
        $queryParams = '?msg_ids=';
        if (is_array($msgIds) && !empty($msgIds)) {
            $msgIdsStr = implode(',', $msgIds);
            $queryParams .= $msgIdsStr;
        } elseif (is_string($msgIds)) {
            $queryParams .= $msgIds;
        } else {
            throw new InvalidArgumentException("Invalid msg_ids");
        }

        $url = $this->client->makeURL('report') . 'messages/detail' .$queryParams;
        return $this->coHttp->get($this->client, $url);
    }

    public function getUsers($time_unit, $start, $duration) {
        $time_unit = strtoupper($time_unit);
        if (!in_array($time_unit, self::$EFFECTIVE_TIME_UNIT)) {
            throw new InvalidArgumentException('Invalid time unit');
        }

        $url = $this->client->makeURL('report') . 'users/?time_unit=' . $time_unit . '&start=' . $start . '&duration=' . $duration;
        return $this->coHttp->get($this->client, $url);
    }
}
