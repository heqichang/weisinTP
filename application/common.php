<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

if (!function_exists('p')) {
    /**
     * 格式化打印
     * @param array $data
     */
    function p($data = [])
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}

if (!function_exists('input_get')) {
    /**
     * 获取$_GET数据，参数参考input_base函数
     * @param null $index string|array
     * @param string $filter 过滤的函数  如果=1使用intval()
     * @param null $default 默认的值
     * @return mixed
     */
    function input_get($index = NULL, $filter = '', $default = NULL)
    {
        return input_base('get', $index, $default, $filter);
    }
}

if (!function_exists('input_post')) {
    /**
     * 获取$_POST数据，参数参考input_base函数
     * @param null $index string|array
     * @param string $filter 过滤的函数  如果=1使用intval()
     * @param null $default 默认的值
     * @return mixed
     */
    function input_post($index = NULL, $filter = '', $default = null)
    {
        return input_base('post', $index, $default, $filter);
    }
}

if (!function_exists('input_base')) {
    /**
     * @param string $method 输入类型：get|post
     * @param null $index string|array
     * @param null $default
     * @param string $filter 过滤的函数  如果=1使用intval()
     * @return mixed
     */
    function input_base($method, $index = NULL, $default = null, $filter = '')
    {
        if (!in_array($method, array('get', 'post'))) {
            exit('error_input_method');
        }

        //如果等于1的时候就是int
        if ($filter == 1) {
            $filter = 'my_intval';
        }

        //可以是数组
        if (is_array($index)) {
            foreach ($index as $k => $input_name) {

                //别名
                if (!is_numeric($k)) {
                    $field = $k;
                } else {
                    $field = $input_name;
                }

                $data[$input_name] = app()->request->$method($field, $default, $filter);
            }
        } else {
            $data = app()->request->$method($index, $default, $filter);
        }


        return $data;
    }
}

if (!function_exists('my_intval')) {
    /**
     * 获取变量的整数值
     *
     * @param  mixed $var 字符串或数组
     *
     * @return mixed
     */
    function my_intval($var)
    {
        if (is_array($var)) {
            foreach ($var as $k => $v) {
                $var[$k] = my_intval($v);
            }
        } else {
            $var = intval($var);
        }

        return $var;
    }
}

if (!function_exists('html_escape')) {
    /**
     * Returns HTML escaped variable.
     *
     * @param    mixed $var The input string or array of strings to be escaped.
     * @param    bool $double_encode $double_encode set to FALSE prevents escaping twice.
     * @return    mixed            The escaped string or array of strings as a result.
     */
    function html_escape($var, $double_encode = TRUE)
    {
        if (empty($var)) {
            return $var;
        }

        if (is_array($var)) {
            foreach (array_keys($var) as $key) {
                $var[$key] = html_escape($var[$key], $double_encode);
            }

            return $var;
        }

        return htmlspecialchars($var, ENT_QUOTES, 'UTF-8', $double_encode);
    }
}

if (!function_exists('site_url')) {
    /**
     * 组合url  加上域名前缀的
     * @param $url
     * @param array $params 参数   string|array
     * @param bool|string $suffix 生成的URL后缀
     * @return $suffix
     */
    function site_url($url = '', $params = [], $suffix = false)
    {
        return url($url, $params, $suffix, true);
    }
}

if (!function_exists("my_curl")) {
    /**
     * CURL GET or POST
     *
     * my_curl('http://example.com', array('foo'=>'bar'), 'post', array(CURLOPT_HEADER=>true));
     *
     * @param string $url 请求URL
     * @param mixed $data 请求数据，关联数组，例如:array('foo'=>'bar')；或查询字符串，例如：foo=bar&foo1=bar1
     * @param string $method 请求方法 get|post
     * @param int $timeout 超时（默认3秒）
     * @param array $opts curl_setopt选项数组，例如：array(CURLOPT_HEADER=>true);
     *
     * @return string 请求网址返回的数据
     */
    function my_curl($url, $data = NULL, $method = 'get', $timeout = 3, $opts = array())
    {
        $ch = curl_init();

        //固定选项
        $innateOpt = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false
        );

        // 区分请求方法
        switch (strtolower($method)) {
            case 'get':
                if ($data) {
                    $data = is_array($data) ? http_build_query($data) : trim($data, '&');
                    $url = rtrim($url, '&') . (strpos($url, '?') === FALSE ? '?' : '&') . $data;
                    $innateOpt[CURLOPT_URL] = $url;
                }
                break;
            case 'post':
                $innateOpt[CURLOPT_POST] = true;
                $innateOpt[CURLOPT_POSTFIELDS] = $data;
                break;
            default:
                throw new \Exception("ERR_MY_CURL_METHOD_NOT_SUPPORT", 1);
                break;
        }

        $opts += $innateOpt; //$opts可传入与$innateOpt相同的参数，前者会覆盖后者
        curl_setopt_array($ch, $opts);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}

if (!function_exists('my_each')) {
    /**
     * 代替each函数
     * @param $array
     * @return array|bool
     */
    function my_each(&$array)
    {
        $res = array();
        $key = key($array);
        if ($key !== null) {
            next($array);
            $res[1] = $res['value'] = $array[$key];
            $res[0] = $res['key'] = $key;
        } else {
            $res = false;
        }

        return $res;
    }
}

if (!function_exists('urlsafe_base64_encode')) {
    /**
     * url
     * @param $string
     * @return mixed|string
     */
    function urlsafe_base64_encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);

        return $data;
    }
}

if (!function_exists("add_field_prefix")) {
    /**
     * 在字段前加上表做为前缀  比如  name  则变成  table.name
     * @param null $fields
     * @param string $prefix string|array
     * @param int $type 0:返回字符串 1：返回数组
     * @return array|string
     */
    function add_field_prefix($fields = null, $prefix = '', $type = 0)
    {
        if (empty($fields)) return '';
        if (!is_array($fields)) {
            $fields = explode(',', $fields);
        }
        $return = array();
        $prefix = !empty($prefix) ? $prefix . "." : '';
        foreach ($fields as $field) {
            $return[] = $prefix . $field;
        }
        return $type ? $return : implode(',', $return);
    }

}
if (!function_exists("remove_field_prefix")) {
    /**
     * 去掉字段前缀 比如 table.name   则变成  name
     * @param null $fields
     * @param string $prefix string|array
     * @return array|string
     */
    function remove_field_prefix($fields = null, $prefix = '')
    {
        if (empty($prefix)) {
            return $fields;
        }

        if (is_array($prefix)) {
            foreach ($prefix as $pre) {
                $fields = remove_field_prefix($fields, $pre);
            }
        } else {
            $prefix = rtrim($prefix, ".") . '.';
            $fields = str_replace($prefix, '', $fields);
        }

        return $fields;
    }

}

if (!function_exists("get_part_of_param")) {
    /**
     * 获取一部分参数
     * @param null $fields
     * @param string $prefix string|array
     * @return array|string
     */
    function get_part_of_param($file_name, $fields = '')
    {
        if (empty($fields)) return config($file_name . '.');
        $datas = array();
        if (!is_array($fields)) {
            $fields = explode(',', $fields);
        }
        foreach ($fields as $field) {
            if ($data = config($file_name . '.' . $field)) $datas[$field] = $data;
        }
        return $datas;
    }
}
// ------------------------------------------------------------------------

if (!function_exists('array_to_object')) {
    /**
     * 数组转成对象  如果为空的数组转成null
     * @param $array
     * @return ArrayObject|null
     */
    function array_to_object($array)
    {
        if (empty($array)) {
            return null;
        }

        return new ArrayObject($array);
    }
}

if (!function_exists('pd')) {
    /**
     * 格式化打印
     * @param array $data
     */
    function pd($data = [])
    {
        echo '<pre>';
        print_r($data);
        die;
        echo '</pre>';
    }
}

if (!function_exists('md5_password')) {
    /**
     * 加密密码
     * @param $password
     * @return string
     */
    function md5_password($password)
    {
        //干扰字符串
        $password_rand = mt_rand(100000, 999999);

        $password = md5($password_rand . $password . $password_rand);

        return [
            'password_rand' => $password_rand,
            'password' => $password,
        ];
    }
}

if (!function_exists('check_md5_password')) {
    /**
     * 检测用户输入的密码
     * @param $input_password  用户输入的密码
     * @param $encryption_password   加密的密码
     * @param $password_rand    数据库里面的随机字符串
     * @return bool
     */
    function check_md5_password($input_password, $encryption_password, $password_rand)
    {
        return md5($password_rand . $input_password . $password_rand) == $encryption_password;
    }
}

if (!function_exists('qiuniu_url')) {
    /**
     * 获取七牛图片的完整路径
     * @param $key  图片的路径
     * @param $policy 七牛上传的策略模式
     * @return string
     */
    function qiniu_url($key, $policy = '')
    {
        if (preg_match("#^http(s?)://#i", $key)) {
            return $key;
        }

        if (empty($key)) {
            return '';
        }

        //一次只执行1个实例化
        static $obj = [];

        if (!isset($obj[$policy])) {
            $obj[$policy] = new \qiniu\QiniuUploadImage(['policy' => $policy]);
        }

        return $obj[$policy]->getPersistentUrl($key);
    }
}

if (!function_exists('qiniu_imgage_info')) {
    /**
     * 获取七牛图片的详细信息
     * @param $key  图片的路径
     * @param $policy 七牛上传的策略模式
     * @return mixed
     */
    function qiniu_imgage_info($key, $policy)
    {
        $url = qiniu_url($key, $policy) . '?imageInfo';

        return json_decode(my_curl($url), true);
    }
}

if (!function_exists('make_key_array')) {
    /**
     * 按数组某字段组成一个新的数组
     * @param $array 数组
     * @param $key 用来做键的字段
     * @param string $type 单个或多个
     */
    function make_key_array($array, $key, $type = 'one')
    {
        if (empty($array)) return array();
        $result = array();
        foreach ($array as $row) {
            if ($type == 'one') {
                if (!isset($result[$row[$key]])) $result[$row[$key]] = $row;
            }
            if ($type == 'all') $result[$row[$key]][] = $row;
        }
        return $result;
    }
}

if (!function_exists('get_page_num')) {
    /**
     * 获取页码数量
     * @param $total_count
     * @param int $limit
     * @return float
     */
    function get_page_num($total_count, $limit = 20)
    {
        return ceil($total_count / $limit);
    }
}

if (!function_exists('prefix_format_money')) {
    /**
     * 有前缀的格式化格式   +20,000.01    -20000.00
     * @param $money  钱的金额
     * @param int $decimals 小数的
     * @return string
     */
    function prefix_format_money($money, $decimals = 2)
    {
        if ($money > 0) {
            return '+' . format_money($money, $decimals);
        }

        if ($money < 0) {
            $money = abs($money);
            return '-' . format_money($money, $decimals);
        }

        return 0;
    }
}

if (!function_exists('format_money')) {
    /**
     * 格式化金额     2,,000.00
     * @param $money
     * @param int $decimals
     * @return string
     */
    function format_money($money, $decimals = 2)
    {
        //    return number_format($money, $decimals);
        if ($money > 0 && $money > intval($money)) {
            return number_format($money, $decimals);
        }

        if ($money > 0 && $money == intval($money)) {
            return number_format($money);
        }

        if ($money > 0 && $money < intval($money)) {
            return number_format($money, $decimals);
        }

        if ($money < 0 && $money == intval($money)) {
            return number_format($money);
        }

        return 0;
    }
}

if (!function_exists('format_decimals_money')) {
    /**
     * 格式化金额     2,,000.00
     * @param $money
     * @param int $decimals
     * @return string
     */
    function format_decimals_money($money, $decimals = 2)
    {
        if ($money > 0 && $money > intval($money)) {
            return number_format($money, $decimals);
        }

        if ($money > 0 && $money == intval($money)) {
            return number_format($money);
        }

        if ($money > 0 && $money < intval($money)) {
            return number_format($money, $decimals);
        }

        if ($money < 0 && $money == intval($money)) {
            return number_format($money);
        }

        return $money;
    }
}

if (!function_exists('format_number')) {
    /**
     * 格式化金额
     * @param $money
     * @param int $decimals
     * @return string
     */
    function format_number($number, $decimals = 2)
    {
        if ($number > 0 && $number > intval($number)) {
            return number_format($number, $decimals, '.', '');
        }

        if ($number > 0 && $number == intval($number)) {
            return number_format($number, 0, '.', '');
        }

        if ($number > 0 && $number < intval($number)) {
            return number_format($number, $decimals, '.', '');
        }

        if ($number < 0 && $number == intval($number)) {
            return number_format($number, 0, '.', '');
        }

        if ($number == 0 && $number == intval($number)) {
            return number_format($number, 0, '.', '');
        }

        return $number;
    }
}


if (!function_exists("format_time_stamp")) {
    /**
     * 格式化时间戳
     * @param $time_stamp 时间戳
     * @return string
     */
    function format_time_stamp($time_stamp)
    {
        $flag = time() - $time_stamp;
        if ($flag < 300) {
            return "刚刚";
        } elseif ($flag < 3600) {
            return intval($flag / 60) . '分钟前';
        } elseif ($flag < (3600 * 24)) {
            return intval($flag / 3600) . '小时前';
        } else {
            $tmp = floor($flag / (3600 * 24));
            if ($tmp < 15) {
                return $tmp . '天前';
            } else {
                return '半个月前';
            }
        }
    }
}

if (!function_exists('get_years')) {
    /**
     * 获取年的列表
     * @param $min_year 最新的年
     * @return array
     */
    function get_years($min_year = null)
    {
        if (!$min_year) {
            $min_year = config('back_end.min_year');
        }

        $current_year = date('Y');
        $year_list = [];

        for ($i = $min_year; $i <= $current_year; $i++) {
            $year_list[] = $i;
        }

        return $year_list;
    }
}

if (!function_exists('get_invite_code')) {
    /**
     * 获取随机码
     * @param int $length
     * @return string
     */
    function build_invite_code($length = 6)
    {
        $string = '';
        $key = array(
            '1', '2', '3', '4', '5', '6', '7', '8', '9',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        for ($i = 0; $i < $length; $i++) {
            $char = $key[mt_rand(0, 58)];
            $string .= $char;
        }

        return strtolower($string);
    }
}

if (!function_exists("number_to_wan")) {
    /**
     * 转换成万为单位的数值
     * @param $num
     * @return float|int
     */
    function number_to_wan($num)
    {
        if (empty($num)) return 0;
        $tmp = $num / 10000;
        if ($tmp == intval($tmp)) {
            $tmp = intval($tmp);
        } else {
            $tmp = round($tmp, 2);
        }
        return $tmp;
    }
}

if (!function_exists('remove_emoji')) {
    /**
     * 删除emoji表情
     * @param $text
     * @param string $replace_text
     * @return mixed|string
     */
    function remove_emoji($text, $replace_text = '')
    {
        $clean_text = "";
        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clean_text = preg_replace($regexEmoticons, $replace_text, $text);
        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clean_text = preg_replace($regexSymbols, $replace_text, $clean_text);
        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clean_text = preg_replace($regexTransport, $replace_text, $clean_text);
        // Match Miscellaneous Symbols
        $regexMisc = '/[\x{2600}-\x{26FF}]/u';
        $clean_text = preg_replace($regexMisc, $replace_text, $clean_text);
        // Match Dingbats
        $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
        $clean_text = preg_replace($regexDingbats, $replace_text, $clean_text);
        return $clean_text;
    }
}

if (!function_exists('custom_sort')) {
    /**
     * 根据对应的sort值进行排序
     * @param $array  数组  一维数组和二维数组
     * @param array $sort 排序的值  [1,2,3,4]  等
     * @param string $key 可以根据对应key来做排序
     * @return array
     */
    function custom_sort($array, $sort = [], $key = '')
    {
        if (!is_array($array) || empty($array)) {
            return array();
        }

        $data = array();
        foreach ($sort as $index => $s) {
            foreach ($array as $k => $v) {
                $sort_key = $v;

                if (!empty($key)) {
                    $sort_key = $v[$key];
                }

                if ($sort_key == $s) {
                    $data[] = $v;
                }
            }
        }

        return $data;
    }
}

if (!function_exists('remove_space')) {
    /**
     * 去除字符串空格
     * @param $str
     * @return mixed
     */
    function remove_space($str)
    {
        return preg_replace('# #', '', $str);
    }
}

if (!function_exists('today_what_day')) {
    /**
     * 今天是星期几
     * @return string
     */
    function today_what_day()
    {
        //先定义一个数组
        $week = array('日', '一', '二', '三', '四', '五', '六');

        return '星期' . $week[date('w')];
    }

}

if (!function_exists('array_to_lower')) {
    /**
     * 数组里面的值全部转换成小写
     * @param array $data
     * @return array
     */
    function array_to_lower(array $data)
    {
        $return = [];

        foreach ($data as $k => $item) {
            if (!is_array($item)) {
                $return[$k] = strtolower($item);
            } else {
                $return[$k] = array_to_lower($item);
            }
        }

        return $return;
    }
}

if (!function_exists('format_second')) {
    /**
     * 把秒转换成小时或分 秒
     * @param $second
     */
    function format_second($second)
    {
        if ($second / 3600 >= 1) {
            return floor($second / 3600) . '小时';
        } elseif ($second / 60 < 1)
            return $second . '秒';
        else {
            return floor($second / 60) . '分钟';
        }
    }
}



