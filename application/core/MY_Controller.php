<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 继承CI控制器
 * @author 王永强
 * @date 2016/05/09 17:12
 */
class MY_Controller extends  CI_Controller {

    //返回前端的数据
    protected $ret;
    //是否登录
    protected $is_login = false;
    //用户id
    protected $uid;
    //用户邮箱
    protected $mail;
    //是否公司
    protected $is_com;
    //登录页
    protected $login_page = '/live-test/userview/log';
    //首页
    protected $main_page = '/live-test/subjectview/company';

    public  function __construct()
    {
        session_start();
        parent::__construct();

        //登录判断
        if(isset($_SESSION['uid'])){
            $this->is_login = true;
            $this->uid = (int)$_SESSION['uid'];
            $this->mail = $_SESSION['mail'];
            $this->is_com = $_SESSION['is_com'];
        }

        //初始化返回的数据
        $this->ret = array('success' => false);
    }


    /**
     * 获取输入
     */
    protected function get_input()
    {
        //获取post数据
        $this->posts = file_get_contents('php://input');
        //解析json
        $this->posts = json_decode($this->posts, true);
        if($this->posts == null){
            $this->ret['error_info'] = '参数错误';
            $this->flush();
        }
    }


    /**
     * 过滤输入
     * @param $data_keys array 要过滤的数据
     */
    protected function validate($data_keys)
    {
        foreach ($data_keys as $k) {
            if(!isset($this->posts[$k]) || empty($this->posts[$k])){
                $this->ret['error_info'] = '参数错误';
                $this->flush();
            }
            $this->posts[$k] = htmlspecialchars($this->posts[$k]);
        }
    }


    /**
     * 输出
     */
    protected function flush()
    {
        echo json_encode($this->ret);
        die;
    }


    /**
     * 生成缓存的id
     * @return string 缓存的id
     */
    protected function create_cache_id()
    {
        return time() . mt_rand(10000, 99999);
    }


    /**
     * 生成一个随机字符串
     * @param $length int 长度
     * @return $str string 随机字符串
     */
    protected  function create_rand_str($length)
    {
        //生成一个包含大写英文字母, 小写英文字母, 数字的数组
        $arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $str = '';
        for ($i = 0; $i < $length; $i++)
        {
            $rand = mt_rand(0, 61);
            $str .= $arr[$rand];
        }

        return $str;
    }
}
