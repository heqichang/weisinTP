<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/23/19
 * Time: 10:13 AM
 */

namespace app\index\model;

use \think\Model;

class BaseModel extends Model {

    use \think\model\concern\SoftDelete;

    protected $defaultSoftDelete = 0;

}