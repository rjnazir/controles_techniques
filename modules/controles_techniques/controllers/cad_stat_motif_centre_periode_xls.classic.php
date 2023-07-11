<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class cad_stat_motif_centre_periode_xlsCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->title = "STATISTIQUE MENSUELLE PAR MOTIF PAR CENTRE DES CAD";

        //Initialisation
        $myclass= jClasses::getService("controles_techniques~myclass");
        $erreur = false;
        $_result = null;
        $_i = 0;

        // Recupération des variables
        $annee = null;
        $centre = $this->param("centre");
        $trimestre = $this->param("trimestre");
        $centres = $myclass->getCentreParent2();

        if($centre == 99999){
            $nom_centre = 'TOUS CENTRES';
        }else{
            $nom_centre = $myclass->transformcenter($myclass->getNomCentreById($centre));
        }

        if(empty($annee) AND empty($centre) AND empty($trimestre)){
            jMessage::add("Veuillez entrer les paramètres, svp!");
            $erreur = true;
        }else{
            in_array($centre, array(3,4,6,12)) ? $_c = 26 : $_c = $centre;
            $code = $centre != 99999 ? $myclass->getCentreById($_c) : '';
            $periode = $myclass->convertToMonth($trimestre);
            $genre = $myclass->getAllCtGenre();
            $categorie = $myclass->getAllCtGenreCategorie();

            foreach($genre as $genre){
                $cgenre = $genre->gr_libelle;
                $catgri = $myclass->getOneCtGenreByLibelle($cgenre)->ct_genre_categorie_id;
                $_result[$_i]['GENREVHL']    = $cgenre;
                $_result[$_i]['CATGRVHL']    = $catgri;
                $_result[$_i]['VHL07000']    = $myclass->getCompteCadByMotifByCentre($code, $annee, $periode, '3.5T ≤ PTAC < 7T', $cgenre);
                $_result[$_i]['VHL10000']    = $myclass->getCompteCadByMotifByCentre($code, $annee, $periode, '7T ≤ PTAC < 10T', $cgenre);
                $_result[$_i]['VHL19000']    = $myclass->getCompteCadByMotifByCentre($code, $annee, $periode, '10T ≤ PTAC < 19T', $cgenre);
                $_result[$_i]['VHL26000']    = $myclass->getCompteCadByMotifByCentre($code, $annee, $periode, '19T ≤ PTAC < 26T', $cgenre);
                $_result[$_i]['TOTALGAL']    = $myclass->getCompteCadByMotifByCentre($code, $annee, $periode, '', $cgenre);

                $_i++;
            }

            $rep->body->assign('categorie', $categorie);
            $rep->body->assign('result', $_result);
        }

        $fichier .= jZone::get('controles_techniques~res_cad_stat_motif_centre_periode', array('result'=>$_result));

        // Declaration du type de contenu
        $file_mane = 'STATISTIQUE CAD ' . $nom_centre .' '. $periode;
        $file_mane = strtolower(str_replace([" ","-"],"_",$file_mane)).".xls";
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=".$file_mane.""); /* Remplacer .csv par .xls pour exporter en .XLS */

        echo "<html>";
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
        echo "<body>";
        echo utf8_decode($fichier);
        echo "</body>";
        echo "</html>";

        $rep->body->assign('annee', $annee);
        $rep->body->assign('erreur', $erreur);
        $rep->body->assign('centre', $centre);
        $rep->body->assign('centres', $centres);
        $rep->body->assign('trimestre', $trimestre);

        $rep->bodyTpl = "controles_techniques~cad_stat_motif_centre_periode";
        
        $rep->addCSSLink('https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
        $rep->addJSLink('https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js');        
        $rep->body->assignZone('MENU', 'controles_techniques~menu');
        
        return $rep;
    }
}

