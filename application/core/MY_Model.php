<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 继承框架原始模型
 * @author 王永强
 * @date 2016/05/14 09:34
 */
class MY_Model  extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
}