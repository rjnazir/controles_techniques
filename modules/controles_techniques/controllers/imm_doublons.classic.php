<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class imm_doublonsCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');

        $ct_carte_grise = jDao::get('ct_carte_grise');
        $rs_ct_carte_grise = $ct_carte_grise->findAll();
        $doublons = []; $i = 0;
        while ($row = $rs_ct_carte_grise->fetch()) {
            $t_imm = str_split($row->cg_immatriculation);
            $pchar = null; $pnum = null;
            foreach($t_imm as $p_imm){
                if(in_array($p_imm, ['0','1','2','3','4','5','6','7','8','9'])){
                    $pnum.=$p_imm;
                }
                else if(in_array($p_imm, [a-zA-Z])){
                    $pchar.=$p_imm;
                }
            }
            $ct_carte_grise2 = jDao::get('ct_carte_grise');
            $conditions = jDao::createConditions('ct_carte_grise');
            $conditions->startGroup('AND');
            $conditions->addCondition('cg_immatriculation', 'LIKE', trim($pnum)."%");
            $conditions->addCondition('cg_immatriculation', 'LIKE', "%".trim($pchar));
            $conditions->endGroup();
            $nbre = $ct_carte_grise2->countBy($conditions, 'cg_immatriculation');
            if($nbre > 1){
                $ct_carte_grise3 = jDao::get('ct_carte_grise');
                $conditions = jDao::createConditions('ct_carte_grise');
                $conditions->startGroup('AND');
                $conditions->addCondition('cg_immatriculation', 'LIKE', trim($pnum)."%");
                $conditions->addCondition('cg_immatriculation', 'LIKE', "%".trim($pchar));
                $conditions->endGroup();
                $liste = $ct_carte_grise3->findBy($conditions, 'cg_immatriculation');
                while ($double = $liste->fetch()) {
                    $doublons[$i]->cg_immatriculation = $double->cg_immatriculation;
                    $doublons[$i]->vhc_num_serie = $double->vhc_num_serie;
                    $doublons[$i]->cg_nom = $double->cg_nom;
                    $doublons[$i]->cg_prenom = $double->cg_prenom;
                    $doublons[$i]->cg_adresse = $double->cg_adresse;
                    $i++;
                }
            }
        }
        // Titre du fichier
        $fichier = ("LISTE DES IMMATRICULATIONS DE VEHICULE AVEC DOUBLONS");
        $fichier .= "\n";

        // Titre des colonnes de votre fichier .CSV ou .XLS
        $fichier .= utf8_decode("N°; IMM.; N° SERIE, NOM PROPRIETAIRE; ADRESSE; OBSERVATIONS");
        $fichier .= "\n";

        $j = 1;
        foreach($doublons as $doublon){
            $fichier .= "".$j.";".$doublon->cg_immatriculation.";".$doublon->vhc_num_serie.";".$doublon->cg_nom." ".$doublon->cg_prenom.";".$doublon->cg_adresse.";".";"."\n";
            $j++;
        }

        // Declaration du type de contenu
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=liste_imm_doublons.csv"); /* Remplacer .csv par .xls pour exporter en .XLS */
        print ($fichier);
        exit;

        $rep->bodyTpl = "controles_techniques~main";
        return $rep;
    }
}

