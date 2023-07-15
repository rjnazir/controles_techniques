<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class statbycentrebyusagebymonth2Ctrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->bodyTpl = "controles_techniques~statbycentrebyusagebymonth2";
        $rep->title = "STATISTIQUE MENSUELLE PAR USAGE PAR CENTRE DES VISITES";

        //Initialisation
        $myclass= jClasses::getService("controles_techniques~myclass");
        $erreur = false;
        $_result = null;
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
                $_usage = $myclass->getUsageAll();
                $periode = $myclass->convertToMonth($trimestre);

                foreach($_usage as $_usage){
                    $usage = $_usage->id;
                    $_result[$_i]['usage'] = utf8_encode($_usage->usg_libelle);
                    $code = $centre != '99999' ? $centre : '';
                    //sur site
                    $_result[$_i]['sspartprem'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 2, 1, 0, 0);        //aptes
                    $_result[$_i]['sspartcntr'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 2, 0, 0, 0);        //inaptes
                    $_result[$_i]['ssadmiprem'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 2, 1000, 0, 0);     //payantes
                    $_result[$_i]['ssadmicntr'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 1, 1000, 0, 0);     //gratuites
                    $_result[$_i]['ssitetotal'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 1000, 1000, 0, 0);  //total
                    //itinerante
                    $_result[$_i]['ssitinapte']     = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 2, 1, 0, 1);         //aptes
                    $_result[$_i]['ssitininapte']   = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 2, 0, 0, 1);         //inaptes
                    $_result[$_i]['ssitintotalp']   = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 2, 1000, 0, 1);      //payantes
                    $_result[$_i]['ssitinadmin']    = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 1, 1000, 0, 1);      //gratuites
                    $_result[$_i]['ssitintotal']    = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 1000, 1000, 0, 1);   //total
                    //A domicile
                    $_result[$_i]['adpartapte']     = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 2, 2, 1, 0, 0);         //aptes
                    $_result[$_i]['adpartinapte']   = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 2, 2, 0, 0, 0);         //inaptes
                    $_result[$_i]['adtotalpremi']   = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 2, 2, 1000, 0, 0);      //payantes
                    $_result[$_i]['adtotaladmin']   = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 2, 1, 1000, 0, 0);      //gratuites
                    $_result[$_i]['adtotalgener']   = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 2, 1000, 1000, 0, 0);   //total
                    $_i++;
                }
                $rep->body->assign('result', $_result);
                $rep->body->assign('usage', $_usage);
                $rep->body->assignZone('res_vt_month', 'controles_techniques~res_statbycentrebyusagebymonth', array('result'=>$_result));
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

