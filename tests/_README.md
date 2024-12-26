使用说明
--
##数据库测试用例的说明
1. 测试前数据库单元测试用例前，请确保数据库内有如下文件内的表和数据：
   ```
   1. _misc/_database_demo_data.sql
   ```
2. 确保 config.php 复制到 项目根目录下
3. 目前的测试是基于Mysql数据库开发地测试用例。其他数据库类型尚未测试。

## 配置测试用例的说明
测试配置功能的测试用例的时候，请将_misc目录下的如下文件复制到项目根目录下
```shell
1. .env
2. config.php
3. config_test.php
4. demo.config.ini
5. demo.config.json
6. demo.config.php
```