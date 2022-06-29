<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class crbilanquotidienCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->bodyTpl = "controles_techniques~crbilanquotidien";
        $rep->title = "CR BILAN QUOTIDIEN";
        // Initialisation des variable et classes utiles
        $myclass= jClasses::getService("controles_techniques~myclass");
        $erreur = false;
        $total  = NULL;
        $inapte = NULL;
        $adm    = NULL;
        $contre = NULL;
        $ife    = NULL;
        $domicile   = NULL;
        $admdom = NULL;
        $contredom  = NULL;
        $inaptedom  = NULL;
        $ifedom = NULL;
        $rt = NULL;
        $rtadm  = NULL;
        $rttecg = NULL;
        $itiner = NULL;
        $cvitiner   = NULL;
        $admitiner  = NULL;
        $inptitiner = NULL;
        $ifeitiner = NULL;
        $rtitiner   = NULL;
        $cad    = NULL;
        $res    = array();
        $codes  = array();

        // Recuperation des conditions
        $ok = $this->param("ok");
        $annee = $this->param("annee");
        $offset = $this->param("offset");
        $k = 1;

        if($ok == true){
            if(empty($annee)){
                jMessage::add("Veuillez entrer la date de l'activité, svp!");
                $erreur = true;
                $res = null;
            }else{
                $centres = $myclass->ListCenter($annee);
                $i = 0;

                foreach($centres as $center){
                    $res[$i]    = new \stdClass();
                    if(!in_array($center->ctr_code, $codes)){
                        /* Rgts CENSERO concerné */
                        $res[$i]->ctr_code  = $center->ctr_code;
                        $res[$i]->ctr_nom   = $myclass->transformcenter($center->ctr_nom);
                        $res[$i]->ctr_lib   = $center->ctr_nom;

                        /***************** SUR SITE *****************/
                        $nbrtbycenter       = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 1, NULL, NULL, NULL, NULL);
                        $res[$i]->total_vt  = $nbrtbycenter;
                        $nbrcontrebycenter  = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 1, NULL, NULL, 1, NULL);
                        $res[$i]->total_contre  = $nbrcontrebycenter;
                        $nbradmbycenter     = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 1, 1, NULL, NULL, NULL);
                        $res[$i]->total_adm = $nbradmbycenter;
                        $nbraptebycenter    = $myclass->newCompteVT($center->ctr_code, $annee, 1, NULL, 1, NULL);
                        $nbrinaptebycenter  = $nbrtbycenter - $nbraptebycenter;
                        $res[$i]->total_inapte  = $nbrinaptebycenter;
                        $nbrifebycenter     = $myclass->newCompteVTIFE($center->ctr_code, $annee, 1, NULL);
                        $res[$i]->total_ife = $nbrifebycenter;
                        $total  += $nbrtbycenter;
                        $contre += $nbrcontrebycenter;
                        $adm    += $nbradmbycenter;
                        $inapte += $nbrinaptebycenter;
                        $ife    += $nbrifebycenter;
                        /***************** FIN SUR SITE *****************/

                        /****************** ITINERANTE ******************/
                        $nbr_itine      = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 1, NULL, NULL, NULL, 'ITINERANTE');
                        $res[$i]->itine = $nbr_itine;
                        $nbr_cvitine    = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 1, NULL, NULL, 1, 'ITINERANTE');
                        $res[$i]->cvitine = $nbr_cvitine;
                        $nbr_admitine   = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 1, 1, NULL, NULL, 'ITINERANTE');
                        $res[$i]->admitine  = $nbr_admitine;
                        $nbr_aptitine   = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 1, NULL, 1, NULL, 'ITINERANTE');
                        $nbr_inptitiner = $nbr_itine - $nbr_aptitine;
                        $res[$i]->inptitine = $nbr_inptitiner;
                        $nbr_ifeitine   = $myclass->newCompteVTIFE($center->ctr_code, $annee, 1, 'ITINERANTE');
                        $res[$i]->ifeitine  = $nbr_ifeitine;
                        $nbr_rtitine    = $myclass->newCompteRT($center->ctr_code, $annee, NULL, NULL, 'ITINERANTE');
                        $res[$i]->rtitine   = $nbr_rtitine;
                        $itiner     += $nbr_itine;
                        $cvitiner   += $nbr_cvitine;
                        $admitiner  += $nbr_admitine;
                        $inptitiner += $nbr_inptitiner;
                        $ifeitiner  += $nbr_ifeitine;
                        $rtitiner   += $nbr_rtitine;
                        /**************** FIN ITINERANTE ****************/

                        /****************** A DOMICILE ******************/
                        $nbrvtdomicile  = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 2, NULL, NULL, NULL, NULL);
                        $res[$i]->total_dom = $nbrvtdomicile;
                        $nbrcontredom   = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 2, NULL, NULL, 1, NULL);
                        $res[$i]->total_cvdom   = $nbrcontredom;
                        $nbradmdom      = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 2, 1, NULL, NULL, NULL);
                        $res[$i]->total_admdom  = $nbradmdom;
                        $nbrinaptedom   = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 2, NULL, 1, NULL, NULL);
                        $nbrinaptedom   = $nbrvtdomicile - $nbrinaptedom;
                        $res[$i]->total_inaptedom   = $nbrinaptedom;
                        $nbrifedom      = $myclass->newCompteVTIFE($center->ctr_code, $annee, 2, NULL);
                        $res[$i]->total_ifedom  = $nbrifedom;
                        $domicile   += $nbrvtdomicile;
                        $contredom  += $nbrcontredom;
                        $admdom     += $nbradmdom;
                        $inaptedom  += $nbrinaptedom;
                        $ifedom     += $nbrifedom;
                        /**************** FIN A DOMICILE ****************/

                        /****************** RECEPTION TECHNIQUE ******************/
                        $nbrrt      = $myclass->newCompteRT($center->ctr_code, $annee, NULL, NULL, NULL);
                        $res[$i]->total_rt = $nbrrt;
                        $nbrrtadm   = $myclass->newCompteRT($center->ctr_code, $annee, 1, NULL, NULL);
                        $res[$i]->total_rtadm = $nbrrtadm;
                        $nbrrttecg  = $myclass->newCompteRT($center->ctr_code, $annee, NULL, 9, NULL);
                        $res[$i]->total_rttecg = $nbrrttecg;
                        $rt     += $nbrrt;
                        $rtadm  += $nbrrtadm;
                        $rttecg += $nbrrttecg;
                        /**************** FIN RECEPTION TECHNIQUE ****************/

                        /****************** COSTATATION AVANT DEDOUANEMENT ******************/
                        $nbrcad = $myclass->newCompteCAD($center->ctr_code, $annee, NULL);
                        $res[$i]->total_cad  = $nbrcad;
                        $cad += $nbrcad;
                        /**************** FIN COSTATATION AVANT DEDOUANEMENT ****************/

                        /* Ajout du code centre dans le tableau des code centre */
                        array_push($codes, $center->ctr_code);
                        $i++;
                    }
                }

                $rep->body->assign('k', $k);

                $rep->body->assign('total', $total);
                $rep->body->assign('adm', $adm);
                $rep->body->assign('inapte', $inapte);
                $rep->body->assign('ife', $ife);
                $rep->body->assign('contre', $contre);

                $rep->body->assign('itiner', $itiner);
                $rep->body->assign('cvitiner', $cvitiner);
                $rep->body->assign('admitiner', $admitiner);
                $rep->body->assign('inptitiner', $inptitiner);
                $rep->body->assign('ifeitiner', $ifeitiner);
                $rep->body->assign('rtitiner', $rtitiner);

                $rep->body->assign('domicile', $domicile);
                $rep->body->assign('contredom', $contredom);
                $rep->body->assign('inaptedom', $inaptedom);
                $rep->body->assign('admdom', $admdom);
                $rep->body->assign('ifedom', $ifedom);

                $rep->body->assign('rt', $rt);
                $rep->body->assign('rtadm', $rtadm);
                $rep->body->assign('rttecg', $rttecg);

                $rep->body->assign('cad', $cad);
            }
        }

        $rep->body->assign('ok', $ok);
        $rep->body->assign('annee', $annee);
        $rep->body->assign('erreur', $erreur);
        $rep->body->assign('res', $res);

        return $rep;
    }
}