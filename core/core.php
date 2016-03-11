<?php

    define('ROOTPATH',$_SERVER['DOCUMENT_ROOT']);
    include_once ROOTPATH.'/vendor/autoload.php';
    include_once ROOTPATH.'/config/config.php';

    $log = nul;
    $mc = null;
    $db = null;
    $collection = null;

    try{
        $log = new \Monolog\Logger('ES');
        $log->pushHandler(new Monolog\Handler\StreamHandler(ROOTPATH.'/log/es.log'),\Monolog\Logger::WARNING);

//        try{
            $mc = new MongoClient();
//        } catch(Exception $e){
//            $mc = new MongoClient();
//        }

        $db = $mc->selectDB($config['db']['db']);
        $collection = new MongoCollection($db,$config['db']['collection']);
    } catch (Exception $e){
        echo $e->getMessage();
        echo '*sytem error, shut dowm*';
        exit();
    }



