<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    RAHARISON J. Nazir
* @copyright 2020 DSGR/DT/SIT
* @link      http://dgsr.com
* @license    All rights reserved
*/

class one_visiteCtrl extends jController {
    /**
    *
    */
    function index() {
        header("Access-Control-Allow-Origin:*");
        $cls0 = jClasses::getService("controles_techniques~myclass");
        $IMM = $this->param('IMM');
        $IMM0 = $cls0->immatriculation($IMM);
        $reponses = $this->getResponse('json');
        $db = jDb::getDbWidget("controles_techniques");
        $db0 = jDb::getConnection("controles_techniques");
        $sql= " SELECT
            ct_visite.id, ct_visite.vst_num_pv, ct_visite.vst_date_expiration, ct_visite.vst_is_apte, ct_visite.vst_is_contre_visite,
            ct_visite.vst_created, ct_carte_grise.cg_immatriculation, ct_carte_grise.cg_nom, ct_carte_grise.cg_prenom,ct_carte_grise.cg_phone
            ct_carte_grise.cg_profession, ct_carte_grise.cg_nom_cooperative, ct_carte_grise.cg_adresse, ct_carte_grise.cg_commune,
            ct_carte_grise.cg_puissance_admin, ct_carte_grise.cg_nbr_assis, ct_carte_grise.cg_nbr_debout, ct_carte_grise.cg_num_carte_violette,
            ct_carte_grise.cg_date_carte_violette, ct_carte_grise.cg_patente, ct_carte_grise.cg_ani, ct_carte_grise.cg_mise_en_service,
            ct_carte_grise.cg_num_vignette, ct_carte_grise.cg_date_vignette,
            ct_centre.ctr_nom,
            ct_carosserie.crs_libelle,
            ct_source_energie.sre_libelle,
            ct_usage.usg_libelle,
            ct_vehicule.vhc_num_serie, ct_vehicule.vhc_num_moteur,ct_vehicule.vhc_type, ct_vehicule.vhc_num_moteur, ct_vehicule.vhc_charge_utile, ct_vehicule.vhc_poids_vide, ct_vehicule.vhc_poids_total_charge,
            (SELECT ct_user.usr_name FROM ct_user WHERE ct_user.id = ct_visite.ct_user_id) as usr_name,
            (SELECT ct_user.usr_name FROM ct_user WHERE ct_user.id = ct_visite.ct_verificateur_id) as nom_verificateur,
            ct_marque.mrq_libelle,
            ct_province.prv_nom,
            ct_utilisation.ut_libelle,
            ct_genre.gr_libelle
        FROM    ct_visite LEFT JOIN
                ct_carte_grise ON ct_carte_grise.id = ct_visite.ct_carte_grise_id LEFT JOIN
                ct_carosserie ON ct_carosserie.id = ct_carte_grise.ct_carosserie_id LEFT JOIN
                ct_source_energie ON ct_source_energie.id = ct_carte_grise.ct_source_energie_id LEFT JOIN
                ct_vehicule ON ct_vehicule.id = ct_carte_grise.ct_vehicule_id LEFT JOIN
                ct_marque ON ct_marque.id = ct_vehicule.ct_marque_id LEFT JOIN
                ct_centre ON ct_centre.id = ct_visite.ct_centre_id LEFT JOIN
                ct_province ON ct_province.id = ct_centre.ct_province_id LEFT JOIN
                ct_usage ON ct_usage.id = ct_visite.ct_usage_id LEFT JOIN
                ct_utilisation ON ct_utilisation.id = ct_visite.ct_utilisation_id LEFT JOIN
                ct_genre ON ct_genre.id = ct_vehicule.ct_genre_id
        WHERE
            (
                ct_carte_grise.cg_immatriculation = '".$IMM."'
                OR ct_carte_grise.cg_immatriculation = '".$IMM0."'
                OR ct_vehicule.vhc_num_serie = '".$IMM."'
            )
            AND `ct_visite`.`vst_created` =  (SELECT MAX(`ct_visite`.`vst_created`) FROM ct_visite
            LEFT JOIN `ct_carte_grise` ON `ct_carte_grise`.`id` = `ct_visite`.`ct_carte_grise_id`
            LEFT JOIN ct_vehicule ON ct_carte_grise.ct_vehicule_id = ct_vehicule.id
            WHERE
                (
                    `ct_carte_grise`.`cg_immatriculation` = '".$IMM."'
                    OR `ct_carte_grise`.`cg_immatriculation` = '".$IMM0."'
                    OR ct_vehicule.vhc_num_serie = '".$IMM."'
                ))";
        
        $res= $db->fetchFirst($sql);
        $reponses->data = array();
        if(isset($res)) {
            $anomalies = null;
            if($res->vst_is_apte == 0){
                $sql1 = "SELECT * FROM ct_visite_anomalie LEFT JOIN ct_anomalie ON ct_visite_anomalie.ct_visite_id = ct_anomalie.id WHERE ct_visite_anomalie.ct_anomalie_id = $res->id";
                $res1 = $db0->query($sql1);
                foreach($res1 as $res1){
                    if(!empty($anomalies)) $anomalies .= ", ";
                    $anomalies .= $res1->anml_libelle;
                }
            }
            $reponses->data[] = array(
                'vst_num_pv' => utf8_encode($res->vst_num_pv),
                'vst_date_expiration' => $res->vst_date_expiration,
                'vst_is_apte' => $res->vst_is_apte,

                'vst_anomalies' => utf8_encode($anomalies),

                'vst_is_contre_visite' => $res->vst_is_contre_visite,
                'vst_created' => $res->vst_created,

                'ctr_nom' => utf8_encode($res->ctr_nom),

                'prv_nom' => utf8_encode($res->prv_nom),

                'cg_immatriculation' => $res->cg_immatriculation,
                'cg_nom' => utf8_encode($res->cg_nom),
                'cg_prenom' => utf8_encode($res->cg_prenom),
                'cg_phone' => utf8_encode($res->cg_phone),
                'cg_profession' => utf8_encode($res->cg_profession),
                'cg_nom_cooperative' => utf8_encode($res->cg_nom_cooperative),
                'cg_adresse' => utf8_encode($res->cg_adresse),
                'cg_commune' => utf8_encode($res->cg_commune),
                'cg_puissance_admin' => intval($res->cg_puissance_admin),
                'cg_nbr_assis' => intval($res->cg_nbr_assis),
                'cg_nbr_debout' => intval($res->cg_nbr_debout),
                'cg_mise_en_service' => $res->cg_mise_en_service,
                'cg_num_carte_violette' => utf8_encode($res->cg_num_carte_violette),
                'cg_date_carte_violette' => $res->cg_date_carte_violette,
                'cg_patente' => $res->cg_patente,
                'cg_ani' => $res->cg_ani,
                'cg_num_vignette' => utf8_encode($res->cg_num_vignette),
                'cg_date_vignette' => $res->cg_date_vignette,

                'crs_libelle' => utf8_encode($res->crs_libelle),

                'sre_libelle' => utf8_encode($res->sre_libelle),

                'usg_libelle' => utf8_encode($res->usg_libelle),

                'vhc_num_serie' => utf8_encode($res->vhc_num_serie),
                'vhc_num_moteur' => utf8_encode($res->vhc_num_moteur),
                'vhc_type' => utf8_encode($res->vhc_type),
                'vhc_charge_utile' => intval($res->vhc_charge_utile),
                'vhc_poids_vide' => intval($res->vhc_poids_vide),
                'vhc_poids_total_charge' => intval($res->vhc_poids_total_charge),

                'mrq_libelle' => utf8_encode($res->mrq_libelle),

                'nom_verificateur' => utf8_encode($res->nom_verificateur),

                'usr_name' => utf8_encode($res->usr_name),

                'ut_libelle' => utf8_encode($res->ut_libelle),

                'gr_libelle' => utf8_encode($res->gr_libelle),

            );
        }
        return $reponses;
    }
}

