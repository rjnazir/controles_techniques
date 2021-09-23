<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    RAHARISON J. Nazir
* @copyright 2020 DSGR/DT/SIT
* @link      http://dgsr.com
* @license    All rights reserved
*/

class etat_vhladm_gn_xlsCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        // Initialisation des classes et variables utiles
        $myclass = jClasses::getService("controles_techniques~myclass");
        $annee = $this->param("annee");
        $nbrapte = null;
        $nbrinapte = null;

        // Titre du fichier
        $fichier = ("ETAT FAISANT CONNAITRE LISTE DE VISITE TECHNIQUE DE VEHICULES ADMINISTRATIFS ET GN");
        $fichier .= "\n";

        // Titre des colonnes de votre fichier .CSV ou .XLS
        $fichier .= utf8_decode("N°; CENTRE; IMM.; NOM PROPRIETAIRE; DATE VISITE; VALIDITE VISITE;	APTITUDE; OBSERVATIONS");
        $fichier .= "\n";

        // Affichage des donnees
        $res = $myclass->ListeVTbyIsApte($annee);
        $nbr = $res->rowCount();
        $i = 1;
        foreach($res as $res){
            ($res->vst_is_apte == 1) ? $aptitude = "Apte" : $aptitude = "Inapte";
            ($res->vst_is_apte == 1) ? $nbrapte++ : $nbrinapte++;
            $fichier .= "".$i.";".$res->ctr_nom.";".$res->cg_immatriculation.";".$res->cg_nom." ".$res->cg_prenom.";".substr($res->vst_created,0,10).";".$res->vst_date_expiration.";".$aptitude.";".";"."\n";
            $i++;
        }

        $nbrvtgn = $myclass->CompteVTGN($annee);
        $nbrvtgnapte = $myclass->CompteVTGNbyIsApte($annee,1);
        $nbrvtgninapte = $myclass->CompteVTGNbyIsApte($annee,0);

        $fichier .= "\n";
        $fichier .= "STATISTIQUE DE VT ADM.".";".";".";"."STATISTIQUE DE VT GN"."\n";
        $fichier .= "APTE".";".$nbrapte.";".";"."APTE".";".$nbrvtgnapte.";"."\n";
        $fichier .= "INAPTE".";".$nbrinapte.";".";"."INAPTE".";".$nbrvtgninapte.";"."\n";
        $fichier .= "TOTAL".";".$nbr.";".";"."TOTAL".";".$nbrvtgn.";"."\n";

        // Declaration du type de contenu
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=etat_vt_adm_et_gn.csv"); /* Remplacer .csv par .xls pour exporter en .XLS */
        print ($fichier);
        exit;

        $rep->bodyTpl = "controles_techniques~etat_vhladm_gn";
        return $rep;
    }
}

