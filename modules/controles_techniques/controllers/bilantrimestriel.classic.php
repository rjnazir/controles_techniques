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
                    $l = 1;
                    foreach($_usage as $_usage)
                    {
                        $usg = $_usage->id;
                        $_result[$k]['usg_libelle'] = utf8_encode($_usage->usg_libelle);
                        //CENSERO DOR
                        $_result[$k]['vt_total001'] = $myclass->getCompteVisiteByUsageByCentre('001', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO BGL
                        $_result[$k]['vt_total002'] = $myclass->getCompteVisiteByUsageByCentre('002', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO ALS
                        $_result[$k]['vt_total004'] = $myclass->getCompteVisiteByUsageByCentre('004', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO IVT
                        $_result[$k]['vt_total010'] = $myclass->getCompteVisiteByUsageByCentre('010', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO ABE
                        $_result[$k]['vt_total023'] = $myclass->getCompteVisiteByUsageByCentre('023', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO TDD
                        $_result[$k]['vt_total027'] = $myclass->getCompteVisiteByUsageByCentre('027', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO TNA
                        $_result[$k]['vt_total005'] = $myclass->getCompteVisiteByUsageByCentre('005', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO AKA
                        $_result[$k]['vt_total016'] = $myclass->getCompteVisiteByUsageByCentre('016', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO FVE
                        $_result[$k]['vt_total017'] = $myclass->getCompteVisiteByUsageByCentre('017', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO MOG
                        $_result[$k]['vt_total018'] = $myclass->getCompteVisiteByUsageByCentre('018', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO ANA
                        $_result[$k]['vt_total009'] = $myclass->getCompteVisiteByUsageByCentre('009', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO SVA
                        $_result[$k]['vt_total019'] = $myclass->getCompteVisiteByUsageByCentre('019', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO NSB
                        $_result[$k]['vt_total029'] = $myclass->getCompteVisiteByUsageByCentre('029', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO FNR
                        $_result[$k]['vt_total007'] = $myclass->getCompteVisiteByUsageByCentre('007', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO ATR
                        $_result[$k]['vt_total015'] = $myclass->getCompteVisiteByUsageByCentre('015', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO MRA
                        $_result[$k]['vt_total021'] = $myclass->getCompteVisiteByUsageByCentre('021', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO FNA
                        $_result[$k]['vt_total022'] = $myclass->getCompteVisiteByUsageByCentre('022', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO IHO
                        $_result[$k]['vt_total025'] = $myclass->getCompteVisiteByUsageByCentre('025', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO TLR
                        $_result[$k]['vt_total008'] = $myclass->getCompteVisiteByUsageByCentre('008', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO MVA
                        $_result[$k]['vt_total011'] = $myclass->getCompteVisiteByUsageByCentre('011', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO TRO
                        $_result[$k]['vt_total013'] = $myclass->getCompteVisiteByUsageByCentre('013', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO ABA
                        $_result[$k]['vt_total014'] = $myclass->getCompteVisiteByUsageByCentre('014', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO MGA
                        $_result[$k]['vt_total020'] = $myclass->getCompteVisiteByUsageByCentre('020', $usg, $annee, $_cmois, null, null, null, null);
                        //CENSERO ATH
                        $_result[$k]['vt_total026'] = $myclass->getCompteVisiteByUsageByCentre('026', $usg, $annee, $_cmois, null, null, null, null);
                        $k++;
                    }

                    //renvoi des valeurs recupérées
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
        
        $rep->addCSSLink('https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
        $rep->addJSLink('https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js');        
        $rep->body->assignZone('MENU', 'controles_techniques~menu');
        
        return $rep;
    }
}