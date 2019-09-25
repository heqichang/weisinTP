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

    public static function getList($get) {

        $db = self::field('id, name');

        $page = 1;

        if (isset($get['page'])) {
            $page = $get['page'];
        }

        if (isset($get['userId'])) {
            $db = $db->where('user_id', $get['userId']);
        }

        return $db->paginate(10, false, ['page' => $page]);
    }

}