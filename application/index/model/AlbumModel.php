<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 4:08 PM
 */

namespace app\index\model;


class AlbumModel extends BaseModel
{
    protected $pk = 'id';
    protected $table = 'album';


    public function photos() {
        return $this->hasMany('PhotoModel', 'album_id', 'id');
    }

    public static function getList($param) {

        $db = self::field('id, name');

        $page = 1;

        if (isset($param['page'])) {
            $page = $param['page'];
        }

        if (isset($param['userId'])) {
            $db = $db->where('user_id', $param['userId']);
        }

        return $db->paginate(10, false, ['page' => $page]);
    }

}