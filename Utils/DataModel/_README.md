说明
--

## 配置说明

以下两个文件复制到项目跟目录，并修改名称

```shell
1. .default.env 修改为 .env 
2. .default.config.php 修改为 config.php
```
并且修改后的.env文件不嵌入vcs中。

## 从Connection上执行方法

如果ModelMate的 queryObject或modelObject属性内，没有某数据库查询方法，那么可以使用 queryObject 对象的属性 Connection 上的方法：

```shell
queryObject->getConnection()->query($sql)
```

## 筛选数据

使用ModelMate选取数据(或数据集)时过滤条件的设定规则：

1. 用OR连接的Where过滤条件
   ```shell
   $condition[DatabaseEnum::WHEREOR] = ["sid" => 2, "class" => "三"];
   ```
2. 用AND连接的Where过滤条件
   ```shell
   $condition[DatabaseEnum::WHEREAND] = ["score" => 100, "class" => "三"];
   ```
   > 因为AND连接是最常用的设置.因此 DatabaseEnum::WHEREAND 部分可以**不写**，直接将具体的过滤条件写在$condition内，
   ```shell
   $condition = ["score" => 100, "class" => "三"]
   ```

3. 如果涉及到对范围进行筛选，那么可以将符号也一起加入到 $condition 中
   ```shell
   $condition = ["score" => [">=" => 80]];
   ```
   也可以多个范围配合使用
   ```shell
   $condition = ["score" =>[["<" => 90] ,[">=" => 80]]];
   或者
   $condition["score"] = [["<" => 90], [">=" => 80]];
   ```
   建议使用第二种方式$condition["score"],因为还可以指定更多的筛选条件,更加直观。
   ```shell
   $condition["score"] = [["<" => 90], [">=" => 80]];
   $condition["class"] = "三";
   ```

> 无论是$condition[DatabaseEnum::WHEREOR] 还是$condition[DatabaseEnum::WHEREAND]设置的 AND 或者 OR 关系,都是对本数组元素对应值(这个值是一个子数组)的 AND 或者 OR(就是子数组各个元素的AND或者OR);跟另外一个$condition[DatabaseEnum::WHEREOR] 或者 $condition[DatabaseEnum::WHEREAND] 没有关系。
