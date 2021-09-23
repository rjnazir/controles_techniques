<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    RAHARISON J. Nazir
* @copyright 2020 DSGR/DT/SIT
* @link      http://dgsr.com
* @license    All rights reserved
*/

class etat_vhladm_gnCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->bodyTpl = "controles_techniques~etat_vhladm_gn";
        $rep->title = "Liste de VT VHL ADM";
        // Initialisation des variable et classes utiles
        $myclass = jClasses::getService("controles_techniques~myclass");
        $nbr = null;
        $nbrApte = null;
        $nbrInapte = null;
        $nbrgn = null;
        $nbrgnapte = null;
        $nbrgninapte = null;
        $offset = null;
        // Recuperation des conditions
        $ok = $this->param("ok");
        $annee = $this->param("annee");
        $offset = $this->param("offset");

        if($ok == true){
            $res0= $myclass->ListeVTbyIsApte($annee);
            $nbr = $res0->rowCount();

            $res = $myclass->ListeVTbyIsApteLimit($annee, $offset);

            $rep->body->assign("res", $res);

            $nbrApte = $myclass->CompteVTbyIsApte($annee, 1);
            $nbrInapte = $myclass->CompteVTbyIsApte($annee, 0);

            $nbrgn = $myclass->CompteVTGN($annee);
            $nbrgnapte = $myclass->CompteVTGNbyIsApte($annee, 1);
            $nbrgninapte = $myclass->CompteVTGNbyIsApte($annee, 0);
        }
        $rep->body->assign("ok", $ok);
        $rep->body->assign("annee", $annee);
        $rep->body->assign("offset", $offset);

        $i = date("Y");
        $k = 1;
        $rep->body->assign("i", $i);
        $rep->body->assign("k", $k);
        $rep->body->assign("nbr", $nbr);
        $rep->body->assign("nbrApte", $nbrApte);
        $rep->body->assign("nbrInapte", $nbrInapte);
        $rep->body->assign("nbrgn", $nbrgn);
        $rep->body->assign("nbrgnapte", $nbrgnapte);
        $rep->body->assign("nbrgninapte", $nbrgninapte);
        return $rep;
    }
}

