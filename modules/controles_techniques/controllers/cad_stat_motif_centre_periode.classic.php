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
                in_array($centre, array(3,4,6,12)) ? $_c = 26 : $_c = $centre;
                $code = $myclass->getCentreById($_c);
                $periode = $myclass->convertToMonth($trimestre);
                $genre = $myclass->getAllCtGenre();

                foreach($genre as $genre){
                    $cgenre = $genre->gr_libelle;
                    $_result[$_i]['GENREVHL']    = $cgenre;
                    $_result[$_i]['VHL07000']    = $myclass->getCompteCadByMotifByCentre($code, $annee, $periode, '3.5T ≤ PTAC < 7T', $cgenre);
                    $_result[$_i]['VHL10000']    = $myclass->getCompteCadByMotifByCentre($code, $annee, $periode, '7T ≤ PTAC < 10T', $cgenre);
                    $_result[$_i]['VHL19000']    = $myclass->getCompteCadByMotifByCentre($code, $annee, $periode, '10T ≤ PTAC < 19T', $cgenre);
                    $_result[$_i]['VHL26000']    = $myclass->getCompteCadByMotifByCentre($code, $annee, $periode, '19T ≤ PTAC < 26T', $cgenre);
                    $_result[$_i]['TOTALGAL']    = $myclass->getCompteCadByMotifByCentre($code, $annee, $periode, '', $cgenre);

                    $_i++;
                }

                $rep->body->assign('result', $_result);
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
