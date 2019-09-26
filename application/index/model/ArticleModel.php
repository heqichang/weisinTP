<?php
/**
 * Created by PhpStorm.
 * User: heqichang
 * Date: 9/24/19
 * Time: 9:02 AM
 */

namespace app\index\model;


class ArticleModel extends BaseModel
{
    protected $table = 'article';
    protected $pk = 'id';

    /**
     * 关联 user
     * @return \think\model\relation\BelongsTo
     */
    public function user() {
        return $this->belongsTo('UserModel', 'user_id', 'id');
    }

    /**
     * 关联目录
     * @return \think\model\relation\BelongsTo
     */
    public function category() {
        return $this->belongsTo('CategoryModel', 'category_id', 'id');
    }

    /**
     * 文章列表
     * @param $get
     * @return \think\Paginator
     */
    public static function getList($get) {

        $hidden = [
            'user' => ['secret', 'status', 'delete_time', 'create_time', 'update_time'],
            'category' => ['delete_time', 'create_time', 'update_time']
        ];

        // 坑：字符串内多个关联不要有空格
        $db = self::with('user,category')->hidden($hidden);

        // 用户过滤
        if (isset($get['user_id'])) {
            $db = $db->where('user_id', $get['user_id']);
        }

        // 目录查找
        if (isset($get['category_id'])) {
            $db = $db->where('category_id', $get['category_id']);
        }

        // 开始时间
        if (isset($get['start_time'])) {
            $db = $db->whereTime('create_time', '>=', $get['start_time']);
        }

        // 结束时间
        if (isset($get['end_time'])) {
            $db = $db->whereTime('create_time', '<=', $get['end_time']);
        }

        // 分页
        $page = 1;
        if (isset($get['page'])) {
            $page = $get['page'];
        }

        $fields = [
            'id', 'user_id', 'title', 'category_id'
        ];

        return $db->field($fields)->order('create_time', 'desc')->paginate(10, false, ['page' => $page]);
    }

    public static function getDetail($id) {

        return self::with('user,category')
            ->hidden([
                'user' => ['secret', 'status', 'delete_time', 'create_time', 'update_time'],
                'category' => ['delete_time', 'create_time', 'update_time']
            ])
            ->field(['id', 'user_id', 'title', 'category_id', 'content', 'create_time'])
            ->get($id);
    }


}