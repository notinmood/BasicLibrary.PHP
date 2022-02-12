<?php
/**
 * @file   : UserMocker.php
 * @time   : 15:04
 * @date   : 2022/1/1
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\database\_res;

use Symfony\Component\OptionsResolver\OptionsResolver;

class UserMocker
{
    /**
     * @var array
     */
    private $option;

    public function __construct($option = [])
    {
        $resolver = new OptionsResolver();
        /**
         * 设置初始默认值
         */
        $resolver->setDefaults([
            "id" => "3",
            "name" => "zhangsan",
            "birthday" => "2021-12-24 09:07:05",
            "email" => "aa@qq.com",
            "class" => "二",
            "score" => 88,
        ]);

        /**
         * 解析传递过来的参数数组
         */
        $this->option = $resolver->resolve($option);
    }

    public function getMocker()
    {
        return $this->option;
    }
}