<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class bilantrimestrielCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->bodyTpl = "controles_techniques~bilantrimestriel";
        $rep->title = "STATISTIQUE TRIMESTRIELLE VISITE";

        //Initialisation
        $myclass= jClasses::getService("controles_techniques~myclass");
        $erreur = false;
        $_result = null;

        // Recupération des variables
        $ok = $this->param("ok");
        $trimestre = $this->param("trimestre");
        $annee = $this->param("annee");

        //Test et actions après click
        if($ok == true){
            if(empty($trimestre)){
                if(empty($annee)){
                    jMessage::add("Veuillez entrer les paramètres, svp!");
                    $erreur = true;
                }else{
                    jMessage::add("Veuillez entrer le trimestre à traiter, svp!");
                    $erreur = true;
                }
            }else{
                if(empty($annee)){
                    jMessage::add("Veuillez entrer l'année à traiter, svp!");
                    $erreur = true;
                }else{
                    $_cmois = $myclass->convertToMonth($trimestre);
                    $_usage = $myclass->getUsageAll();
                    $_ctres  = $myclass->getCentreParent();
                    $k = 0;
                    $l = 0;

                    foreach($_ctres as $_ctres){
                        $ctr_subctr = $myclass->getCentreByCode($_ctres->ctr_cod);
                        $ctr_idf    = $_ctres->ctr_idf;
                        foreach($_usage as $_usage){
                            $_result = new ArrayObject();
                            $_result[$k]->usg_libelle = $_usage->usg_libelle;
                            $l++;
                        }
                        $k++;
                    }

                    //renvoi des valeurs recupérées
                    $rep->body->assign('usage', $_usage);
                    $rep->body->assign('usage', $_usage);
                    $rep->body->assign('ctres', $_ctres);
                }
            }
        }

        // renvoi des valeurs
        $rep->body->assign('ok', $ok);
        $rep->body->assign('res', $_result);
        $rep->body->assign('annee', $annee);
        $rep->body->assign('erreur', $erreur);
        $rep->body->assign('trimestre', $trimestre);

        return $rep;
    }
}

