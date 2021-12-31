<?php
/**
 * @file   : ModelMateTest.php
 * @time   : 10:09
 * @date   : 2021/12/31
 * @emailto: 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: HiLand & RainyTop
 */

namespace Hiland\Test\database;

use Hiland\Utils\DataModel\DatabaseClient;

class ModelMateTest
{
    public function testInteract(){
        $mate= DatabaseClient::getMate("user");
        // $mate-> interact()
    }
}