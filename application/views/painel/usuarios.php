<?php
/**
 * Created by PhpStorm.
 * User: caio
 * Date: 27/04/2016
 * Time: 17:13
 */

switch ($tela):
    case 'login':

        echo '<div class="row">';
        echo '<div class="columns medium-4 medium-centered">';
        echo form_open('usuarios/valida',array('class'=>'custom loginform'));
        echo form_fieldset('Identifique-se',array('class' => 'fieldset'));
        erros_validacao();
        echo form_label('Usuário');
        echo form_input(array('name'=>'usuario'),set_value('usuario'),'autofocus');
        echo form_label('Senha');
        echo form_password(array('name'=>'senha'),set_value('senha'));
        echo '<div class="row">';
        echo '<div class="columns medium-6">';
        echo '<p>'.anchor('usuarios/nova_senha','Esqueci minha senha').'</p>';
        echo '</div>';
        echo '<div class="columns medium-4 text-right">';
        echo form_submit(array('name'=>'logar','class'=> 'button radius right'),'Login');
        echo '</div>';
        echo '</div>';
        echo form_fieldset_close();
        echo '</div>';
        echo '</div>';
        break;
        break;
    default:
        echo '<div class="Alert-box alert"><p>A tela solicitada não existe</p></div>';
        break;
endswitch;