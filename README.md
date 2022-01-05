企业级的php类库
--

## 配置说明

0. 因为功能经常更新和增强，使用的时候请注意版本信息。

1. 里面发送短信使用的aliyun的短信接口，其中composer.json里面引入了"alibabacloud/sdk": "^1.8"
   ，这个库还会引入其他的库，被引入的库guzzle如果是7.X版本，需要手动修改为6.3。因为7.X版本是php7的语法。
   （先删除掉guzzlehttp目录，然后把composer.json,composer.lock中涉及的guzzle从 6.3|7.0,改为6.3；最后在composer update）

   或者暂时先把这个功能去掉

```shell
"require": {
  "alibabacloud/sdk": "^1.8"
},
```

2. 进行单元测试时候，请按照文件 test/_README.md的内容进行简单配置。
3. 使用数据库访问的时候，请按照文件 Utils/Config/_README.md 的内容进行配置。

## 开发注意事项

1. 判断某件事情的时候用单词 determine [dɪ'tɜːrmɪn]
2. 是非判断的时候，用is****
