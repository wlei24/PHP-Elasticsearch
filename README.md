# PHP-Elasticsearch
一套全文检索解决方案，涉及到的技术有elasticsearch、mongodb、php等。<br/>
具体流程：<br/>
1. PHP程序添加文章写入Mongodb中。<br/>
2. 通过mongodb-connector同步Mongodb数据到elasticsearch中。<br/>
3. PHP程序([elasticsearch-php](https://github.com/elastic/elasticsearch-php))全文检索elasticsearch。<br/>
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
#####1. 下载[MongoDB](http://www.mongodb.org/downloads)，也可以采用以下方式进行安装（linux下）
<pre>$ brew install mongodb</pre>
#####2. 解压压缩包到某个自定义目录下
#####3. 进入解压目录,新建data目录，然后在data目录下新建db目录，用来存放mongodb数据
#####4. 进入bin目录下，启动mongod,切记命令为
<pre>$ ./mongod</pre>
首次需配置MongoDB数据存放位置
<pre>$ sudo ./mongod --dbpath /Users/wlei24/es/mongodb-osx-x86_64-3.0.0/data/db/</pre>
后面运行时可能出现
<pre>ERROR：dbpath (/data/db) does not exist.</pre>
这是由于mongod启动时没有找到mongodb.conf导致的，因此我们的启动mongodb的时候手动添加 --dbpath即可
#####5. 测试MongoDB运行情况
进入bin目录，运行 `./mongo` 进入mongodb控制台，输入
<pre>$ show dbs</pre>
显示结果：
<pre>
article  0.078GB
local    0.328GB
</pre>
同样你可以通过 `127.0.0.1:27017` 访问，页面显示:
<pre>It looks like you are trying to access MongoDB over HTTP on the native driver port.</pre>
## Mongo-Connector准备
#####1. 下载[mongo-connector](https://github.com/mongodb-labs/mongo-connector)
首先需要确保你已经安装pip，否则执行以下命令
<pre>$ easy_install pip</pre>
若已安装，执行以下命令
<pre>pip install mongo-connector</pre>
同样你也可以这样安装 - 下载完成后执行`sudo python setup.py install`
<pre>
git clone https://github.com/10gen-labs/mongo-connector.git
cd mongo-connector
python setup.py install
</pre>
#####2. 确保开启MongoDB复制集
<pre>
mongod --replSet myDevReplSet
</pre>
接着在mongodb控制台执行 `rs.initiate()`
#####3. 运行 `mongodb-connector`
<pre>mongo-connector -m 127.0.0.1:27017 -t 127.0.0.1:9200 -d elastic_doc_manager</pre>
你会惊奇发现报了一大堆的错误
<pre>
No handlers could be found for logger "mongo_connector.util"
Traceback (most recent call last):
  File "/usr/local/bin/mongo-connector", line 9, in <module>
    load_entry_point('mongo-connector==2.3', 'console_scripts', 'mongo-connector')()
  File "/Library/Python/2.7/site-packages/mongo_connector-2.3-py2.7.egg/mongo_connector/util.py", line 85, in wrapped
    func(*args, **kwargs)
  File "/Library/Python/2.7/site-packages/mongo_connector-2.3-py2.7.egg/mongo_connector/connector.py", line 1041, in main
    conf.parse_args()
  File "/Library/Python/2.7/site-packages/mongo_connector-2.3-py2.7.egg/mongo_connector/config.py", line 118, in parse_args
    option, dict((k, values.get(k)) for k in option.cli_names))
  File "/Library/Python/2.7/site-packages/mongo_connector-2.3-py2.7.egg/mongo_connector/connector.py", line 824, in apply_doc_managers
    module = import_dm_by_name(dm['docManager'])
  File "/Library/Python/2.7/site-packages/mongo_connector-2.3-py2.7.egg/mongo_connector/connector.py", line 814, in import_dm_by_name
    "vailable doc managers." % full_name)
mongo_connector.errors.InvalidConfiguration: Could not import mongo_connector.doc_managers.elastic_doc_manager. It could be that this doc manager has been moved out of this project and is maintained elsewhere. Make sure that you have the doc manager installed alongside mongo-connector. Check the README for a list of available doc managers.
</pre>
<pre>
花了大半天没有解决问题，怪自己没仔细看错误输出，偌大的错误提示-没有找到elastic_doc_manager
不过感觉mongodb-connector也有点坑，默认doc_managers里面只有solr_doc_manageir
</pre>
这时就需要你去[elastic2-doc-manager](https://github.com/mongodb-labs/elastic2-doc-manager)<br/>
将elastic2-doc-manager.py拷贝到本地doc_manaers目录<br/>
执行之前命令，发现继续报错~
<pre>
IOError: [Errno 13] Permission denied: '/Library/Python/2.7/site-packages/mongo_connector-2.3-py2.7.egg/mongo_connector/doc_managers/mongo-connector.log'
</pre>
这个错误只需要根据报错信息，新建此文件，并赋予读写权限即可。<br/>
继续执行之前命令，惊奇发现已经显示正常迹象，不过随即退出。<br/>
解决此问题只需采用在命令前面加上`sudo`即可(意思你懂得~)
<pre>Logging to mongo-connector.log.</pre>

#####<b>参考资料：<a href="https://www.gitbook.com/book/looly/elasticsearch-the-definitive-guide-cn/details" target="_blank">Elasticsearch权威指南</a></b>、[mongo-connector](https://github.com/mongodb-labs/mongo-connector/blob/master/README.rst)、[MongoDB 数据自动同步到 ElasticSearch](https://segmentfault.com/a/1190000003773614)

## 总结
<pre>
1.遇到问题时，没有仔细查看错误信息。
2.英文能力有待提高。
</pre>
