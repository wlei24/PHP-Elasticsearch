# PHP-Elasticsearch
一套全文检索解决方案，涉及到的技术有elasticsearch、mongodb、php等。
## Elasticsearch准备
#####1. 安装新版 <a href="http://www.java.com" target="_blank">java环境</a><br/>
#####2. 下载 <a href="http://www.elasticsearch.org/download" target="_blank">Elasticsearch</a><br/>
#####3. 解压压缩包
<pre> $ unzip elasticsearch-{version}.zip</pre>
#####4. 运行elasticsearch
<pre>$ ./elasticsearch-{version}/bin/elasticsearch</pre>
#####5. 测试运行情况  <sup>守护进程模式，加-d</sup>
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
## MongoDB准备
#####1. 下载[MongoDB](http://www.mongodb.org/downloads)，也可以采用一下方式进行安装（linux下）
<pre>$ brew install mongodb</pre>
#####2. 解压压缩包到某个自定义目录下
#####3. 进入解压目录,新建data目录，然后在data目录下新建db目录，用来存放mongodb数据
#####4. 进入bin目录下，启动mongod,切记命令为
<pre>$ ./mongod</pre>
首次需配置mongdo数据存放位置
<pre>$ sudo ./mongod --dbpath /Users/wlei24/es/mongodb-osx-x86_64-3.0.0/data/db/</pre>
后面运行时可能出现
<pre>ERROR：dbpath (/data/db) does not exist.</pre>
这是由于mongod启动时没有找到mongodb.conf导致的，因此我们的启动mongodb的时候手动添加 --dbpath即可
#####5. 测试mongodb运行情况
进入bin目录，运行./mongo进入mongodb控制台，输入
<pre>$ show dbs</pre>
显示结果：
<pre>
article  0.078GB
local    0.328GB
</pre>
同样你可以通过127.0.0.1:27017访问，页面显示:
<pre>It looks like you are trying to access MongoDB over HTTP on the native driver port.</pre>
## Mongo-Connector准备
#####1. 下载[mongo-connector](https://github.com/mongodb-labs/mongo-connector)
首先需要确保你已经安装pip，否则执行以下命令
<pre>$ easy_install pip</pre>
若已安装，执行以下命令
<pre>pip install mongo-connector</pre>
同样你也可以这样安装,下载完成后执行`sudo python setup.py install`
<pre>
git clone https://github.com/10gen-labs/mongo-connector.git
cd mongo-connector
python setup.py install
</pre>
#####2. 确保开启MongoDB复制集
<pre>
mongod --replSet myDevReplSet
</pre>
然后在mongodb控制台执行`rs.initiate()`
#####<b>参考书籍：<a href="https://www.gitbook.com/book/looly/elasticsearch-the-definitive-guide-cn/details" target="_blank">Elasticsearch权威指南</a></b>
