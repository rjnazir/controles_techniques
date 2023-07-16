<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class rt_stat_bycentrebycatb_dayCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->bodyTpl = "controles_techniques~rt_stat_bycentrebycatb_day";
        $rep->title = "STATISTIQUE RECEPTIONS JOURNIALIERE PAR CATEGORIE";

        //Initialisation
        $myclass= jClasses::getService("controles_techniques~myclass");
        $erreur = false;
        $_result = null;
        $_i = 0;

        // Recupération des variables
        $OK = $this->param("OK");
        $annee = null;
        $centre = $this->param("centre");
        $trimestre = $this->param("trimestre");
        $centres = $myclass->getCentreParent2();

        if($OK == true)
        {
            if(empty($annee) AND empty($centre) AND empty($trimestre)){
                jMessage::add("Veuillez entrer les paramètres, svp!");
                $erreur = true;
            }else{
                /* Véhicule à moteur (isolé) */
                $genre0 = '(5, 6, 9, 12, 13, 14, 20)';
                $_result = $myclass->getStatistiqueReception($centre, $trimestre, $genre0, 1);
                /* Remorques et semi-remorques */
                $genre1 = '(4, 11, 16, 17)';
                $_remorq = $myclass->getStatistiqueReception($centre, $trimestre, $genre1, 1);
                /* Motocyclettes, velomoteurs, cyclomoteurs, tricycles,quatricycles */
                $genre2 = '(1, 2, 3, 7, 8, 18)';
                $_cyclom = $myclass->getStatistiqueReception($centre, $trimestre, $genre2, 1);
                /* Véhicules et appareils agricoles ouforestiers, matériels de TP et engins spéciaux */
                $genre3 = '(10, 15)';
                $_agrico = $myclass->getStatistiqueReception($centre, $trimestre, $genre3, 1);
                /* Remplacement de cadre ou de coque */
                $_rcoque = $myclass->getStatistiqueReception($centre, $trimestre, NULL, 0);
                $rep->body->assign('result', $_result);
                $rep->body->assignZone(
                    'res_rt_stat_by_day',
                    'controles_techniques~res_rt_stat_bycentrebycatb_day',
                    array(
                        'result' => $_result,
                        'remorq' => $_remorq,
                        'cyclom' => $_cyclom,
                        'agrico' => $_agrico,
                        'rcoque' => $_rcoque,
                    )
                );
            }
        }

        // renvoi des valeurs
        $rep->body->assign('OK', $OK);
        $rep->body->assign('annee', $annee);
        $rep->body->assign('erreur', $erreur);
        $rep->body->assign('centre', $centre);
        $rep->body->assign('centres', $centres);
        $rep->body->assign('trimestre', $trimestre);

        $rep->addCSSLink('https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
        $rep->addJSLink('https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js');
        $rep->addJSLink('https://kit.fontawesome.com/13957d2282.js');
        $rep->body->assignZone('MENU', 'controles_techniques~menu');
        return $rep;
    }
}

