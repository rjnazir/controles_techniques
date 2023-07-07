<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    R.J. Nazir
* @copyright 2011 DGSR/DT/SIT
* @link      https://dgsrmada.com/
* @license    All rights reserved
*/

class storiesCtrl extends jController {
    /* Initialisations classes utiles */
    /* protected $class = jClasses::get("controles_techniques~myclass"); */

    /* Initialisations connection à la BDD */
    /* protected $db = jDb::getConnection(); */

    /**
    *
    */
    function index() {
        // $reponses = $this->getResponse('html');
        header("Access-Control-Allow-Origin:*");
        $reponses   =   $this->getResponse('json');
        $cls        =   jClasses::getService("controles_techniques~myclass");
        $visites    =   $cls->findVisiteByImm($this->param('IMM'));
        foreach($visites as $visite){
            $reponses->data[]   =   array(
                    'id'    => $visite->ID,
                    'vst_num_pv'    => $visite->vst_num_pv,
                    'vst_num_feuille_caisse'=> $visite->vst_num_feuille_caisse,
                    'vst_date_expiration'   => $visite->vst_date_expiration,
                    'vst_created'   => $visite->vst_created,
                    'vst_is_apte'   => $visite->vst_is_apte,
                    'vst_is_contre_visite'  => $visite->vst_is_contre_visite,
                    'vst_duree_reparation'  => $visite->vst_duree_reparation,

                    'cg_immatriculation'    => $visite->cg_immatriculation,
                    'vhc_num_serie' => $visite->vhc_num_serie,

                    'ctr_nom'   => $visite->ctr_nom,

                    'secretaire'    => $visite->secretaire,
                    'verificateur'  => $visite->verificateur,

                    'ut_libelle'    => $visite->ut_libelle,
                    'usg_libelle'   => $visite->usg_libelle,
                    'tpv_libelle'   => $visite->tpv_libelle
            );
        }
        return $reponses;
    }
}

