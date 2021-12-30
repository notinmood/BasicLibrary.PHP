说明
--
## 对私有方法的单元测试
使用反射的方式,访问private成员:
```shell
ReflectionHelper::executeInstanceMethod()
ReflectionHelper::executeStaticMethod()
```
具体参见 ReflectionHelperTest.php内的示例