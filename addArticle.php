<?php

    include_once './core/core.php';

    $title = $_POST['title'];
    $content = $_POST['content'];
    $create_ts = strtotime(date('Y-m-d H:i:s'));
    $result = $collection->insert(array('title'=>$title,'content'=>$content,'create_ts'=>$create_ts));

    if(strval($result['ok']) == '1')
        echo 'ok';
    else
        echo $result;
