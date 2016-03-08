# PHP-Elasticsearch
##部署准备
1. 安装新版 <a href="http://www.java.com" target="_blank">java环境</a><br/>
2. 下载 <a href="http://www.elasticsearch.org/download" target="_blank">Elasticsearch</a><br/>
3. 解压压缩包
<pre> $ unzip elasticsearch-{version}.zip</pre>
4. 运行elasticsearch
<pre>$ ./elasticsearch-{version}/bin/elasticsearch</pre>
5. 测试elasticsearch运行情况
<pre>$ curl 127.0.0.1:9200/?pretty</pre>
Notice - 正常可以看到以下返回信息：
<pre>
{
  "name" : "Martinex",
  "cluster_name" : "elasticsearch",
  "version" : {
    "number" : "2.2.0",
    "build_hash" : "8ff36d139e16f8720f2947ef62c8167a888992fe",
    "build_timestamp" : "2016-01-27T13:32:39Z",
    "build_snapshot" : false,
    "lucene_version" : "5.4.1"
  },
  "tagline" : "You Know, for Search"
}
</pre>
#####参考书籍：Elasticsearch权威指南 - https://www.gitbook.com/book/looly/elasticsearch-the-definitive-guide-cn/details
