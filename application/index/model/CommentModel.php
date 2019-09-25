<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/25/19
 * Time: 9:20 AM
 */

namespace app\index\model;


class CommentModel extends BaseModel
{

    protected $pk = 'id';
    protected $table = 'comment';

    public function user() {
        return $this->belongsTo('UserModel', 'user_id', 'id');
    }

    /**
     * 文章留言列表
     * @param $articleId
     * @param $page
     * @return \think\Paginator
     */
    public static function getList($articleId, $page) {

        return self::field('id, user_id, article_id, content')
            ->with('user')
            ->visible(['user' => ['id', 'username', 'avatar']])
            ->where('article_id', $articleId)
            ->paginate(10, false, ['page' => $page]);
    }
}