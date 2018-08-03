#!/usr/bin/php

<?php
$url = 'http://m.beta.job.com';
$limit = 50;

$file = file('mobile_user.csv');

$uas = array();
$chs = array();
$mh = null;
$info = array(
    array(
        'HTTP_CODE',
        'FILETIME',
        'TOTAL_TIME',
        'NAMELOOKUP_TIME',
        'CONNECT_TIME',
        'PRETRANSFER_TIME',
        'START_TRANSFER_TIME',
        'REDIRECT_COUNT',
        'REDIRECT_TIME',
        'SIZE_UPLOAD',
        'SIZE_DOWNLOAD',
        'SPEED_DOWNLOAD',
        'SPEED_UPLOAD',
        'HEADER_SIZE',
        'HEADER_OUT',
        'REQUEST_SIZE',
        'SSL_VERIFYRESULT',
        'CONTENT_LENGTH_DOWNLOAD',
        'CONTENT_LENGTH_UPLOAD',
        'CONTENT_TYPE'
    )
);
$total = 0;

$c = count($file);
$slices = ceil($c / $limit);

$startTime = microtime(TRUE);
for ($z = 0; $z < $slices; $z ++) {
    $tmpUas = array_slice($file, $z * $limit, $limit);
    
    $uas = array_map('str_getcsv', $tmpUas);
    
    createChs();
    runLimit();
    cleanUp();
    
    $uas = array();
}
$endTime = microtime(TRUE);

function createChs()
{
    global $u, $limit, $url, $chs, $mh, $uas;
    
    $mh = curl_multi_init();
    
    for ($i = 0; $i < $limit && $i < count($uas); $i ++) {
        $chs[$i] = curl_init();
        curl_setopt($chs[$i], CURLOPT_URL, $url);
        curl_setopt($chs[$i], CURLOPT_USERAGENT, $uas[$i][1]);
        curl_setopt($chs[$i], CURLOPT_RETURNTRANSFER, TRUE);
        curl_multi_add_handle($mh, $chs[$i]);
    }
}

function runLimit()
{
    global $mh, $chs;
    
    $active = null;
    // execute the handles
    do {
        $mrc = curl_multi_exec($mh, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    
    while ($active && $mrc == CURLM_OK) {
        if (curl_multi_select($mh) != - 1) {
            do {
                $mrc = curl_multi_exec($mh, $active);
                if ($mrc > 0) {
                    echo 'ERROR: ' . curl_multi_strerror($mrc);
                }
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
    }
    unset($active, $mrc);
}

function cleanUp()
{
    global $mh, $chs, $info, $total;
    
    $errors = array();
    
    foreach ($chs as $ch) {
        $erorrs[] = curl_error($ch) . "\n";
        $info[] = array(
            curl_getinfo($ch, CURLINFO_HTTP_CODE),
            curl_getinfo($ch, CURLINFO_FILETIME),
            curl_getinfo($ch, CURLINFO_TOTAL_TIME),
            curl_getinfo($ch, CURLINFO_NAMELOOKUP_TIME),
            curl_getinfo($ch, CURLINFO_CONNECT_TIME),
            curl_getinfo($ch, CURLINFO_PRETRANSFER_TIME),
            curl_getinfo($ch, CURLINFO_STARTTRANSFER_TIME),
            curl_getinfo($ch, CURLINFO_REDIRECT_COUNT),
            curl_getinfo($ch, CURLINFO_REDIRECT_TIME),
            curl_getinfo($ch, CURLINFO_SIZE_UPLOAD),
            curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD),
            curl_getinfo($ch, CURLINFO_SPEED_DOWNLOAD),
            curl_getinfo($ch, CURLINFO_SPEED_UPLOAD),
            curl_getinfo($ch, CURLINFO_HEADER_SIZE),
            curl_getinfo($ch, CURLINFO_HEADER_OUT),
            curl_getinfo($ch, CURLINFO_REQUEST_SIZE),
            curl_getinfo($ch, CURLINFO_SSL_VERIFYRESULT),
            curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD),
            curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_UPLOAD),
            curl_getinfo($ch, CURLINFO_CONTENT_TYPE)
        );
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }
    
    curl_multi_close($mh);
    
    if (empty($errors)) {
        echo count($chs) . " REQUESTS OK\n";
    } else {
        var_dump($errors);
    }
    
    $total += count($chs);
    
    $chs = array();
    
    unset($errors, $ch);
}

if (! empty($info)) {
    $ih = fopen('request_info.csv', 'w');
    foreach ($info as $m) {
        fputcsv($ih, $m);
    }
    fclose($ih);
} else {
    echo "NO INFORMATION\n";
}

echo 'TOTAL Requests: ' . $total . "\n";
echo 'START TIME: ' . date('Y-m-d H:i:s', $startTime) . "\n";
echo 'END TIME: ' . date('Y-m-d H:i:s', $endTime) . "\n";
echo 'RUN TIME: ' . ($endTime - $startTime) . "\n";

unset($total, $ih, $m, $info, $url, $limit, $file, $uas, $chs, $mh, $c, $slices, $z, $file, $tmpUas, $uas);
