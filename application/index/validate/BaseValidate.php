<?php

namespace app\index\validate;

use app\http\exception\FromValidException;
use think\Container;
use think\Validate;

class BaseValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [];

    /**
     * 定义的错误码信息
     * 格式：'字段名.规则名'    =>    '错误码'
     * @var array
     */
    protected $errorCode = [];

    /**
     * 接收字段错误码
     * @var array
     */
    private $_errorCodes = [];

    /**
     * 接受字段错误的信息
     * @var array
     */
    private $_fieldMessage = [];

    /**
     * 验证数据
     * @param $data
     * @param array $rules
     * @param string $scene
     * @param bool $batch_errors
     * @return bool
     * @throws FromValidException
     */
    public function run($data, $rules = [], $scene = '',$batch_errors = false)
    {
        if (!$this->check($data, $rules, $scene)) {
            $errors = $this->getMessageCodes();

            if (empty($errors)){
                return false;
            }

            if ($batch_errors){
                $code = 1;
                $message = $errors;
            }else{
                reset($errors);
                list($code, $message) = my_each($errors);
            }

            throw new FromValidException($message,$code);
        }

        return true;
    }

    /**
     * 获取错误码和错误信息
     * @return array  ['错误码'=> '错误信息']
     */
    public function getMessageCodes()
    {
        //错误数组    错误码=>对应错误信息
        $error = [];

        if (!empty($this->_fieldMessage)) {
            foreach ($this->_fieldMessage as $field=>$message){
                $code = isset($this->_errorCodes[$field]) ? $this->_errorCodes[$field] : 1;

                $error[$code] = $message;
            }
        }

        return $error;
    }


    /**
     * 获取验证规则的错误提示信息
     * @access protected
     * @param  string $attribute 字段英文名
     * @param  string $title 字段描述名
     * @param  string $type 验证规则名称
     * @param  mixed $rule 验证规则数据
     * @return string
     */
    protected function getRuleMsg($attribute, $title, $type, $rule)
    {
        $lang = Container::get('lang');

        if (isset($this->message[$attribute . '.' . $type])) {
            $msg = $this->message[$attribute . '.' . $type];
        } elseif (isset($this->message[$attribute][$type])) {
            $msg = $this->message[$attribute][$type];
        } elseif (isset($this->message[$attribute])) {
            $msg = $this->message[$attribute];
        } elseif (isset(self::$typeMsg[$type])) {
            $msg = self::$typeMsg[$type];
        } elseif (0 === strpos($type, 'require')) {
            $msg = self::$typeMsg['require'];
        } else {
            $msg = $title . $lang->get('not conform to the rules');
        }

        //加入错误码
        if (isset($this->errorCode[$attribute . '.' . $type])) {
            $this->_errorCodes[$attribute] = $this->errorCode[$attribute . '.' . $type];
        } elseif (isset($this->errorCode[$attribute][$type])) {
            $this->_errorCodes[$attribute] = $this->errorCode[$attribute][$type];
        } elseif (isset($this->errorCode[$attribute]) && !is_array($this->errorCode[$attribute])) {
            $this->_errorCodes[$attribute] = $this->errorCode[$attribute];
        } else {
            $this->_errorCodes[$attribute] = 1;
        }

        if (!is_string($msg)) {
            $this->_fieldMessage[$attribute] = $msg;

            return $msg;
        }

        if (0 === strpos($msg, '{%')) {
            $msg = $lang->get(substr($msg, 2, -1));
        } elseif ($lang->has($msg)) {
            $msg = $lang->get($msg);
        }

        if (is_scalar($rule) && false !== strpos($msg, ':')) {
            // 变量替换
            if (is_string($rule) && strpos($rule, ',')) {
                $array = array_pad(explode(',', $rule), 3, '');
            } else {
                $array = array_pad([], 3, '');
            }
            $msg = str_replace(
                [':attribute', ':1', ':2', ':3'],
                [$title, $array[0], $array[1], $array[2]],
                $msg);
            if (strpos($msg, ':rule')) {
                $msg = str_replace(':rule', (string)$rule, $msg);
            }
        }

        $this->_fieldMessage[$attribute] = $msg;

        return $msg;
    }
}
