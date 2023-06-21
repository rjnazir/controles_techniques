<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class statbycentrebyusageCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->bodyTpl = "controles_techniques~statbycentrebyusage";
        $rep->title = "STATISTIQUE PERIODIQUE PAR CENTRE DES VISITES";

        //Initialisation
        $myclass= jClasses::getService("controles_techniques~myclass");
        $erreur = false;
        $_result = null;
        $_i = 0;

        // Recupération des variables
        $OK = $this->param("OK");
        $annee = $this->param("annee");
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
                    $code = $myclass->getCentreById($centre);
                    $_result[$_i]['sspartprem'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 2, 1000, 0);
                    $_result[$_i]['sspartcntr'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 2, 1000, 1);
                    $_result[$_i]['ssadmiprem'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 1, 1000, 0);
                    $_result[$_i]['ssadmicntr'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 1, 1000, 1);
                    $_result[$_i]['ssitetotal'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 1000, 1000, 1000);
                    $_result[$_i]['adpartprem'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 2, 2, 1000, 0);
                    $_result[$_i]['adpartcntr'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 2, 2, 1000, 1);
                    $_result[$_i]['adadmiprem'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 2, 1, 1000, 0);
                    $_result[$_i]['adadmicntr'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 2, 1, 1000, 1);
                    $_result[$_i]['aditetotal'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 2, 1000, 1000, 1000);
                    $_result[$_i]['totalgener'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1000, 1000, 1000, 1000);
                    $_i++;
                }
                $rep->body->assign('result', $_result);
                $rep->body->assign('usage', $_usage);
            }
        }

        // renvoi des valeurs
        $rep->body->assign('OK', $OK);
        $rep->body->assign('annee', $annee);
        $rep->body->assign('erreur', $erreur);
        $rep->body->assign('centre', $centre);
        $rep->body->assign('centres', $centres);
        $rep->body->assign('trimestre', $trimestre);
        return $rep;
    }
}

