<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    RAHARISON J. Nazir
* @copyright 2020 DSGR/DT/SIT
* @link      http://dgsr.com
* @license    All rights reserved
*/

class defaultCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->title = "ACCUEIL OUTILS CT - CAD - RT";

        // this is a call for the 'welcome' zone after creating a new application
        // remove this line !
        $rep->body->assignZone('MAIN', 'jelix~check_install');

        return $rep;
    }
}
