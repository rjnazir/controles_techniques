<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class cad_stat_motif_centre_periodeCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->bodyTpl = "controles_techniques~cad_stat_motif_centre_periode";
        $rep->title = "STATISTIQUE MENSUELLE PAR MOTIF PAR CENTRE DES CAD";

        //Initialisation
        $myclass= jClasses::getService("controles_techniques~myclass");
        $erreur = false;
        $vhlamoteurs = null;
        $_i = 0;

        // Recupération des variables
        $OK = $this->param("OK");
        $annee = 1000;
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
                $vhlamoteurs = $myclass->getStatitstiqueCAD($centre, $genre0, $trimestre);

                /* Véhicule à moteur (isolé) */
                $genre1 = '(4, 11, 16, 17)';
                $semiremorqs = $myclass->getStatitstiqueCAD($centre, $genre1, $trimestre);

                $rep->body->assign('vhlamoteurs', $vhlamoteurs);
                $rep->body->assignZone(
                    'res_cad_stat',
                    'controles_techniques~res_cad_stat_motif_centre_periode',
                    array(
                        'vhlamoteurs' => $vhlamoteurs,
                        'semiremorqs' => $semiremorqs,
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

