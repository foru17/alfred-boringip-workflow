<?php
require_once('workflows.php');
define('IP_QUERY_URL','http://ip.taobao.com/service/getIpInfo.php?ip=');
$w = new Workflows();
$query = '{query}';
if (strpos($query, '://') === false) {
    $query = 'http://' . $query;
}
$host = parse_url($query);
$domain = $host['host'];
function getIp($q){
    $ipNum = gethostbyname($q);
    return $ipNum;
}
$ip = getIp($domain);
if(strpos($query,$ip) == true){
    $ip ='未找到IP地址';
}
$w->result('lookupdomainip', $ip,'IP地址是:'. $ip , '搜索域名:'. $domain, 'icon.png', 'yes');
echo $w->toxml();

?>
