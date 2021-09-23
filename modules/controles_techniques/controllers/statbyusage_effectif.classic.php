<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    RAHARISON J. Nazir
* @copyright 2020 DSGR/DT/SIT
* @link      http://dgsr.com
* @license    All rights reserved
*/

class statbyusage_effectifCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->bodyTpl = "controles_techniques~statbyusage_effectif";
        $rep->title = "Statistique usage effectif";

        $ok = $this->param('ok');
        $mois = $this->param('mois');
        $annee = $this->param('annee');
        $ct_centre_id = $this->param('ct_centre_id');
        if($ok == true)
        {
            $daoct_usage = jDao::get('controles_techniques~ct_usage');
            $conditions = jDao::createConditions();
            $conditions->addItemOrder('usg_libelle', 'asc');
            $ct_usage = $daoct_usage->findBy($conditions);
            $rep->body->assign('ct_usage', $ct_usage);
        }
        $rep->body->assign('ok', $ok);

        $daoct_centre = jDao::get('controles_techniques~ct_centre');
        $conditions = jDao::createConditions();
        $conditions->addItemOrder('ctr_nom','asc');
        $ct_centre = $daoct_centre->findBy($conditions);
        $rep->body->assign("ct_centre", $ct_centre);

        $i = date("Y");
        $rep->body->assign("i", $i);
        return $rep;
    }
}

