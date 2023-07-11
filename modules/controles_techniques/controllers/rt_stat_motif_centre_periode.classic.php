<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class rt_stat_motif_centre_periodeCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->bodyTpl = "controles_techniques~rt_stat_motif_centre_periode";
        $rep->title = "STATISTIQUE MENSUELLE PAR MOTIF PAR CENTRE DES RECEPTIONS";

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
                $_motifs = $myclass->getAllCtMotif();
                $periode = $myclass->convertToMonth($trimestre);
                
                foreach($_motifs as $_motifs){
                    $_idmtf = $_motifs->id;
                    $_mtf   = $myclass->getCtMotifById($_idmtf);
                    $_result[$_i]['motif'] = utf8_encode($_motifs->mtf_libelle);
                    in_array($centre, array(3,4,6,12)) ? $_c = 26 : $_c = $centre;
                    $code = $centre != 1000 ? $myclass->getCentreById($_c) : '';
                    $_result[$_i]['rtpartvhlimmmga'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 2, 0, '');
                    $_result[$_i]['rtadmnvhlimmmga'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1, 0, '');
                    $_result[$_i]['rtttalvhlimmmga'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1000, 0, '');
                    $_result[$_i]['rtpevimpinf3500'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 2, 1, '< 3500');
                    $_result[$_i]['rtadvimpinf3500'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1, 1, '< 3500');
                    $_result[$_i]['rtpevimpinf7000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 2, 1, '3.5T ≤ PTAC < 7T');
                    $_result[$_i]['rtadvimpinf7000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1, 1, '3.5T ≤ PTAC < 7T');
                    $_result[$_i]['rtpevimpinf10000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 2, 1, '7T ≤ PTAC < 10T');
                    $_result[$_i]['rtadvimpinf10000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1, 1, '7T ≤ PTAC < 10T');
                    $_result[$_i]['rtpevimpinf19000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 2, 1, '10T ≤ PTAC < 19T');
                    $_result[$_i]['rtadvimpinf19000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1, 1, '10T ≤ PTAC < 19T');
                    $_result[$_i]['rtpevimpinf26000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 2, 1, '19T ≤ PTAC < 26T');
                    $_result[$_i]['rtadvimpinf26000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1, 1, '19T ≤ PTAC < 26T');
                    $_result[$_i]['rtttalvhlimport'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1000, 1, '');

                    $_i++;
                }
                $rep->body->assign('result', $_result);
                $rep->body->assign('motifs', $_motifs);
                $rep->body->assignZone('res_rt_stat', 'controles_techniques~res_rt_stat_motif_centre_periode', array('result'=>$_result));
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

