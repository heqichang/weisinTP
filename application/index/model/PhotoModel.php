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

    public function getUrlAttr($value) {
        return env('URL.IMAGE_BASE_URL') . $value;
    }

    /**
     * 批量添加图片到相册
     * @param $userId
     * @param $albumId
     * @param array $photos
     * @throws \Exception
     */
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
    }

    /**
     * 获取相册内图片列表
     * @param $albumId
     * @param $page
     * @return \think\Paginator
     */
    public static function getList($albumId, $page) {

        return self::field('id, user_id, album_id, url')
            ->where('album_id', $albumId)
            ->paginate(10, false, ['page' => $page]);
    }

}