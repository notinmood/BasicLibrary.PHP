说明
--
1. 如果ModelMate的 queryObject或modelObject属性内，没有某数据库查询方法，那么可以使用
   queryObject属性的Connection对象上的方法：
```shell
queryObject->getConnection()->query($sql)
```