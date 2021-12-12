企业级的php类库
--

## 注意事项

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

