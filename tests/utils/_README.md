说明
--
## 对私有方法的单元测试
使用反射的方式,访问private成员:
```shell
ReflectionHelper::executeInstanceMethod()
ReflectionHelper::executeStaticMethod()
```
具体参见 ReflectionHelperTest.php内的示例

## 配置读取的测试
由于ConfigHelperTest.php中几个方法内使用的ConfigHelper 和 ConfigMate 会相互影响，本类型内的几个方法请分别单独测试。