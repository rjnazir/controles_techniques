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
                    if(!preg_match("/ITINERANTE/", $center->ctr_nom) AND !preg_match("/BARIKADIMY/", $center->ctr_nom) AND !preg_match("/ENVIRONNEMENT/", $center->ctr_nom)){
                        $res[$i] = new \stdClass();
                        // $res[$i]->ctr_nom = $myclass->transformcenter($center->ctr_nom)." (".$center->ctr_nom.")";
                        $res[$i]->ctr_nom = $myclass->transformcenter($center->ctr_nom);

                        // VISITE SUR SITE
                        $nbrtbycenter = $myclass->compteVT($center->id, $annee, $issursite=1, NULL, NULL, NULL);
                        $res[$i]->total_vt = $nbrtbycenter;
                        $nbradmbycenter = $myclass->compteVT($center->id, $annee, $issursite=1, $isadm=1, NULL, NULL);
                        $res[$i]->total_adm = $nbradmbycenter;
                        $nbraptebycenter = $myclass->compteVT($center->id, $annee, $issursite=1, NULL, $isapte=1, NULL);
                        $nbrinaptebycenter = $nbrtbycenter - $nbraptebycenter;
                        $res[$i]->total_inapte = $nbrinaptebycenter;
                        $nbrifebycenter = $myclass->compteinapteife($center->id, $annee, $issursite=1);
                        $res[$i]->total_ife = $nbrifebycenter;
                        $nbrcontrebycenter = $myclass->compteVT($center->id, $annee, $issursite=1, NULL, NULL, $iscontre=1);
                        $res[$i]->total_contre = $nbrcontrebycenter;

                        $total += $nbrtbycenter;
                        $adm += $nbradmbycenter;
                        $inapte += $nbrinaptebycenter;
                        $ife += $nbrifebycenter;
                        $contre += $nbrcontrebycenter;

                        // VISITE ITINERANTE
                        $nbr_itine = $myclass->comptevtitinerante($center->id, $annee, $issursite=1, NULL, NULL, NULL);
                        $res[$i]->itine = $nbr_itine;
                        $nbr_cvitine = $myclass->comptevtitinerante($center->id, $annee, $issursite=1, NULL, NULL, $iscontre=1);
                        $res[$i]->cvitine = $nbr_cvitine;
                        $nbr_admitine = $myclass->comptevtitinerante($center->id, $annee, $issursite=1, $isadm=1, NULL, NULL);
                        $res[$i]->admitine = $nbr_admitine;
                        $nbr_aptitine = $myclass->comptevtitinerante($center->id, $annee, $issursite=1, NULL, $isapte=1, NULL);
                        $nbr_inptitiner = $nbr_itine - $nbr_aptitine;
                        $res[$i]->inptitine = $nbr_inptitiner;
                        $nbr_ifeitine = $myclass->compteitinerife($center->id, $annee, $issursite=1);
                        $res[$i]->ifeitine = $nbr_ifeitine;
                        $nbr_rtitine = $myclass->compterti($center->id, $annee, NULL);
                        $res[$i]->rtitine = $nbr_rtitine;

                        $itiner += $nbr_itine;
                        $cvitiner += $nbr_cvitine;
                        $admitiner += $nbr_admitine;
                        $inptitiner += $nbr_inptitiner;
                        $ifeitiner += $nbr_ifeitine;
                        $rtitiner += $nbr_rtitine;

                        // VISITE A DOMICILE
                        $nbrvtdomicile = $myclass->compteVT($center->id, $annee, $issursite=2, NULL, NULL, NULL);
                        $res[$i]->total_dom = $nbrvtdomicile;
                        $nbrcontredom = $myclass->compteVT($center->id, $annee, $issursite=2, NULL, NULL, $iscontre=1);
                        $res[$i]->total_cvdom = $nbrcontredom;
                        $nbradmdom = $myclass->compteVT($center->id, $annee, $issursite=2, $isadm=1, NULL, NULL);
                        $res[$i]->total_admdom = $nbradmdom;
                        $nbrinaptedom = $myclass->compteVT($center->id, $annee, $issursite=2, NULL, $isapte=1, NULL);
                        $nbrinaptedom = $nbrvtdomicile - $nbrinaptedom;
                        $res[$i]->total_inaptedom = $nbrinaptedom;
                        $nbrifedom = $myclass->compteinapteife($center->id, $annee, $issursite=2);
                        $res[$i]->total_ifedom = $nbrifedom;

                        $domicile += $nbrvtdomicile;
                        $contredom += $nbrcontredom;
                        $admdom += $nbradmdom;
                        $inaptedom += $nbrinaptedom;
                        $ifedom += $nbrifedom;

                        // RECEPTION TECHNIQUE
                        $nbrrt = $myclass->comptert($center->id, $annee, NULL, NULL);
                        $res[$i]->total_rt = $nbrrt;
                        $nbrrtadm = $myclass->comptert($center->id, $annee, $isadm=1, NULL);
                        $res[$i]->total_rtadm = $nbrrtadm;
                        $nbrrttecg = $myclass->comptert($center->id, $annee, NULL, $motif=9);
                        $res[$i]->total_rttecg = $nbrrttecg;
    
                        $rt += $nbrrt;
                        $rtadm += $nbrrtadm;
                        $rttecg += $nbrrttecg;

                        // COSTATATION AVANT DEDOUANEMENT
                        $nbrcad = $myclass->comptecad($center->id, $annee);
                        $res[$i]->total_cad  = $nbrcad;

                        $cad += $nbrcad;

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