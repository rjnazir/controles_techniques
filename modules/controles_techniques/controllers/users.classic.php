<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    RAHARISON J. Nazir
* @copyright 2020 DSGR/DT/SIT
* @link      http://dgsr.com
* @license    All rights reserved
*/

class usersCtrl extends jController {
    /**
    *@param string $username
    *@param string $password
    */
    function index() {
        $reponses = $this->getResponse('json');
        $username = $this->param('username');
        $password = $this->param('password');
        $db = jDb::getDbWidget("controles_techniques");
        $sql0= "SELECT * FROM ct_user WHERE username = '".$username."' ORDER BY username";
        $res0= $db->fetchFirst($sql0);
        $sql= "SELECT * FROM ct_user WHERE username = '".$username."' AND password = '".crypt($password, $res0->password)."' ORDER BY username";
        $res= $db->fetchFirst($sql);
        $reponses->data = array();
        if(isset($res)) {
            $reponses->data[] = array(
                'id' => $res->id,
                'username' => utf8_encode($res->username),
                'username_canonical' => utf8_encode($res->username_canonical),
                'email' => utf8_encode($res->email),
                'email_canonical' => utf8_encode($res->email_canonical),
                'enabled' => $res->enabled,
                'salt' => $res->salt,
                'password' => $res->password,
                'last_login' => $res->last_login,
                'confirmation_token' => $res->confirmation_token,
                'password_requested_at' => $res->password_requested_at,
                'roles' => utf8_encode($res->roles),
                'usr_name' => utf8_encode($res->usr_name),
                'usr_email' => utf8_encode($res->usr_email),
                'usr_locked' => $res->usr_locked,
                'usr_password' => $res->usr_password,
                'usr_adresse' => utf8_encode($res->usr_adresse),
                'usr_token' => $res->usr_token,
                'usr_created_at' => $res->usr_created_at,
                'usr_updated_at' => $res->usr_updated_at,
                'usr_locked_update' => $res->usr_locked_update,
                'usr_request_update' => $res->usr_request_update,
                'usr_profession' => utf8_encode($res->usr_profession),
                'usr_telephone' => $res->usr_telephone,
                'usr_is_connected' => $res->usr_is_connected,
                'usr_presence' => $res->usr_presence,
                'ct_centre_id' => $res->ct_centre_id,
                'ct_role_id' => $res->ct_role_id,
            );
        }
        return $reponses;
    }
}

