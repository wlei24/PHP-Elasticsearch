<!DOCTYPE html>
<html>
<head>
    <title>Add Article</title>
    <link rel="stylesheet" href="./lib/css/bootstrap.min.css">
    <script src="./lib/jquery.min.js"></script>
    <style>
        .container{padding: 50px;font-size: 15px;font-size: 14px;}
        select{width: 60px;height: 40px !important;line-height: 40px;}
        input{width: 230px;height: 35px;border-radius: 5px;line-height: 35px;border: 1px solid #D3D3D3;padding: 0 10px;}
    </style>
</head>
<body>
    <div class="container">
        <form action="" method="post">
            <select name="type">
                <option value="title">title</option>
                <option value="content">content</option>
            </select>
            <input type="text" name="searchword" placeholder="please input search word">
            <button type="submit" class="btn btn-primary">search</button>
        </form>


        <?php
            $type = isset($_POST['type']) && !empty($_POST['type']) ? trim($_POST['type']) : '';
            $searchword = isset($_POST['searchword']) && !empty($_POST['searchword']) ? trim($_POST['searchword']) : '';

            if(empty($type) || empty($searchword)){
                echo 'type or searchword is null';
            }
            else {

                echo "<br/><pre>搜索内容：$type---$searchword</pre>";

                include_once './vendor/autoload.php';
                include_once './config/config.php';

                try{
                    $es = new Elasticsearch\Client($config['es']);
                    $params['index'] = 'article';
                    $params['type'] = 'article';
                    $params['body']['query']['match'][$type] = $searchword;
                    $params['body']['highlight']['fields'][$type] = new stdClass();
                    $params['body']['highlight']['pre_tags'] = array("<span style='color:red;font-size: 16px;font-weight: normal;'>","</span>");
                    $params['body']['highlight']['post_tags'] = array("<span style='color:#000;font-weight: normal;font-size: 14px;'>","</span>");
                    $result = $es->search($params);
                } catch(Exception $e){
                    echo '<pre>System error：'.$e->getMessage().'</pre>';
                    exit;
                }

                $result = $result['hits'];

                if($result['total'] == 0){
                    echo 'no result';
                } else {
                     $result = $result['hits'];
                     if($type == 'title'){
                        foreach($result as $v){
                            echo '<pre>';
                            echo $v['highlight'][$type][0]."\t\t";
                            echo date('Y-m-d H:i:s',$v['_source']['create_ts']).'<br/><br/>';
                            echo mb_substr(str_replace(array("\r\n", "\r", "\n"),"",$v['_source']['content']),0,150,'UTF-8').'......';
                            echo '</pre>';
                        }
                    } else {
                         foreach($result as $v){
                             echo '<pre>';
                             echo $v['_source']['title']."\t\t";
                             echo date('Y-m-d H:i:s',$v['_source']['create_ts']).'<br/><br/>';
                             echo "<span style='color:#000;font-weight: normal;font-size: 14px;'>".str_replace(array("\r\n", "\r", "\n"),"",$v['highlight'][$type][0])."……</span>\t\t";
                             echo '</pre>';
                         }

                    }

                }

            }
        ?>


    </div>



</body>
</html>