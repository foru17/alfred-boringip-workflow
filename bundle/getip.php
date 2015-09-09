<?php

require_once('workflows.php');
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
	$ip ='IP not found';
}
$w->result('lookupdomainip', $ip,'IP Address:'. $ip , 'Domain:'. $domain, 'icon.png', 'yes');
echo $w->toxml();


 ?>