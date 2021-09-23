<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    RAHARISON J. Nazir
* @copyright 2020 DSGR/DT/SIT
* @link      http://dgsr.com
* @license    All rights reserved
*/

class usercheckCtrl extends jController {
    /**
    *
    */
    function index() {
        $reponses = $this->getResponse('html');
        header("Access-Control-Allow-Origin:*");
        // $reponses = $this->getResponse('json');
        $reponses->data[] = array(
            'checked' => false,
        );
        $myclass = jClasses::getService("controles_techniques~myclass");

        $reponses->data = array();

        $login = $this->param('login');
        $password = $this->param('password');
        // $password = bin2hex($this->param('password'));
        // echo $password.'<br/>';
        // $password = hex2bin($password);
        // echo $password.'<br/>';
        // $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 13]);
        // echo $password.'<br/>';
        $result = $myclass->checkUser($login, $password);
        $reponses->data[] = array(
            'checked' => $result,
        );

        return $reponses;
    }
}

