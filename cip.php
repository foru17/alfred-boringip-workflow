<?php
use Alfred\Workflows\Workflow;

require_once ('vendor/Workflow.php');
require_once ('vendor/Result.php');
require_once ('util/request.php');
require ('util/ping.php');
const ICON = 'icon.png';

$wf = new Workflow;

function getIPData($ipr) {
    $response = request('https://free.is26.com/api/v1/getIp/' . urlencode($ipr) );
    $json = json_decode($response);
    $result = $json->data;
    return $result;
}

function getIp($q) {
    $ipNum = gethostbyname($q);
    return $ipNum;
}

if (filter_var($query, FILTER_VALIDATE_IP)) {
    $response = request('https://free.is26.com/api/v1/getIp/' . urlencode($query) );
    $json = json_decode($response);
    $result = $json->data;
    $ping = new Ping($result->ip);
    $latency = $ping->ping();

    $wf->result()
        ->title($result->isp . ' ' . $result->area . ' ' . $result->region . ' ' . $result->city . ' ' . $result->country)
        ->subtitle($result->country_id . ' 延迟 ' . $latency)
        ->arg($result->isp . ' ' . $result->region . ' ' . $result->city . ' ' . $result->country)
        ->icon(ICON)
        ->autocomplete($key);
        
    echo $wf->output();

}
else {
    if (strpos($query, '://') === false) {
        $query = 'http://' . $query;
    }
    $host = parse_url($query);
    $domain = $host['host'];

    $ip = getIp($domain);
    $latencyTag = ' 延迟';
    $response = request('https://free.is26.com/api/v1/getIp/' . urlencode($ip) );
    $json = json_decode($response);
    $result = $json->data;

    if (strpos($query, $ip) == true) {
        $ip = 'IP not found';
        $latencyTag = '';
    }

    $ping = new Ping($ip);
    $latency = $ping->ping();

    $wf->result()
        ->title($result->isp . ' ' . $result->area . ' ' . $result->region . ' ' . $result->city . ' ' . $result->country)
        ->subtitle($ip . ' ' . $result->country_id . $latencyTag . $latency)
        ->arg($ip)
        ->icon(ICON)
        ->autocomplete($key);

    echo $wf->output();
}

