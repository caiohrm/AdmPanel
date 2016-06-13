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

    public function enviar_email($para,$assunto,$mensagem,$formato='html')
    {
        $this->CI->load->library('email');
        $config['mailtype']=$formato;
        $this->CI->email->initialize($config);
        $this->CI->email->from('caio-h-rm@hotmail.com','Administração do site');
        $this->CI->email->to($para);
        $this->CI->email->subject($assunto);
        $this->CI->email->message($mensagem);
        if($this->CI->email->send())
        {
            return true;
        }
        else{
            return $this->CI->email->print_debugger();
        }
    }
}