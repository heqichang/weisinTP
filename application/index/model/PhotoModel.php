<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 4:14 PM
 */

namespace app\index\model;


class PhotoModel extends BaseModel
{
    protected $pk = 'id';
    protected $table = 'album_photo';


    public static function addPhotos($userId, $albumId, $photos = []) {

        $list = [];

        foreach ($photos as $photo) {
            $list[] = [
                'user_id' => $userId,
                'album_id' => $albumId,
                'url' => $photo,
            ];
        }

        $db = new self;
        $db->saveAll($list);

//        self::saveAll($list);
    }

}