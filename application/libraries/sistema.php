<?php
/**
 * Created by PhpStorm.
 * User: caio
 * Date: 27/04/2016
 * Time: 17:17
 */

class Sistema {

    protected $CI;
    public $tema = array();

    public function __construct()
    {
        $this->CI= & get_instance();
        $this->CI->load->helper('funcoes');
    }
}