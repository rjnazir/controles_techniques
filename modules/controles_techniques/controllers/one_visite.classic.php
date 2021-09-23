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

        $reponses = $this->getResponse('json');

        //formatage immatriculation
        $myclass = jClasses::getService("controles_techniques~myclass");
        $IMM0 = $this->param('IMM');
        $IMM1 = $myclass->immatriculation($IMM0);

        //recuperation rgts carte grise
        $db0 = jDb::getDbWidget("controles_techniques");
        $db1 = jDb::getConnection("controles_techniques");

        $sql0 = "   SELECT
                        ctcg.ct_zone_deserte_id, ctcg.cg_zone_deserte,
                        ctcg.cg_num_identification, ctcg.cg_is_transport,
                        ctcg.cg_itineraire, ctcg.cg_nom_cooperative,
                        ctcg.cg_created, ctcg.cg_immatriculation,
                        ctcg.cg_lieu_vignette, ctcg.cg_date_vignette,
                        ctcg.cg_lieu_carte_violette, ctcg.cg_num_vignette,
                        ctcg.cg_date_carte_violette,
                        ctcg.cg_num_carte_violette, ctcg.cg_rta,
                        ctcg.cg_ani, ctcg.cg_patente,
                        ctcg.cg_puissance_admin, ctcg.cg_mise_en_service,
                        ctcg.cg_nbr_debout, ctcg.cg_nbr_assis,
                        ctcg.cg_adresse, ctcg.cg_commune,
                        ctcg.cg_profession, ctcg.cg_prenom, ctcg.cg_phone,
                        ctcg.cg_nom, ctcg.cg_date_emission,
                        ctcg.ct_vehicule_id, ctcg.ct_source_energie_id,
                        ctcg.ct_centre_id, ctcg.ct_carosserie_id,
                        ctcg.id,

                        ctcsr.crs_libelle,

                        ctse.sre_libelle,

                        ctvhl.ct_genre_id, ctvhl.ct_marque_id,
                        ctvhl.vhc_puissance, ctvhl.vhc_cylindre,
                        ctvhl.vhc_poids_vide, ctvhl.vhc_charge_utile,
                        ctvhl.vhc_hauteur, ctvhl.vhc_largeur,
                        ctvhl.vhc_longueur, ctvhl.vhc_num_serie,
                        ctvhl.vhc_num_moteur, ctvhl.vhc_provenance,
                        ctvhl.vhc_type, ctvhl.vhc_poids_total_charge,

                        ctmrq.mrq_libelle,

                        ctgr.gr_libelle
                    FROM    ct_carte_grise AS ctcg
                            INNER JOIN ct_carosserie AS ctcsr ON ctcg.ct_carosserie_id = ctcsr.id
                            INNER JOIN ct_source_energie AS ctse ON ctcg.ct_source_energie_id = ctse.id
                            INNER JOIN ct_vehicule AS ctvhl ON ctcg.ct_vehicule_id = ctvhl.id
                            INNER JOIN ct_genre AS ctgr ON ctvhl.ct_genre_id = ctgr.id
                            INNER JOIN ct_marque AS ctmrq ON ctvhl.ct_marque_id = ctmrq.id
                    WHERE
                        (ctcg.cg_immatriculation = '".$IMM0."' OR ctcg.cg_immatriculation = '".$IMM1."')
                        OR ctvhl.vhc_num_serie = '".$IMM0."'";

        $res = $db1->query($sql0);
        
        if(!empty($res)){
            while($row = $res->fetch()){

                $sql1 = "   SELECT
                                ctvt.id,
                                ctvt.ct_carte_grise_id,
                                ctvt.vst_num_pv,
                                ctvt.vst_date_expiration,
                                ctvt.vst_created,
                                ctvt.vst_is_apte,
                                ctvt.vst_is_contre_visite,
                                ctvt.vst_duree_reparation,

                                ctctr.ctr_nom,

                                ctprv.prv_nom,

                                cttpv.tpv_libelle,

                                ctusg.usg_libelle,

                                ctsiat.usr_name AS siat_name,

                                ctvta.usr_name AS vta_name,

                                ctutl.ut_libelle AS ut_libelle
                            FROM
                                ct_visite AS ctvt
                                INNER JOIN ct_centre AS ctctr ON ctvt.ct_centre_id = ctctr.id
                                INNER JOIN ct_province AS ctprv ON ctctr.ct_province_id = ctprv.id
                                INNER JOIN ct_type_visite AS cttpv ON ctvt.ct_type_visite_id = cttpv.id
                                INNER JOIN ct_usage AS ctusg ON ctvt.ct_usage_id = ctusg.id
                                INNER JOIN ct_user AS ctsiat ON ctvt.ct_user_id = ctsiat.id
                                INNER JOIN ct_user AS ctvta ON ctvt.ct_verificateur_id = ctvta.id
                                INNER JOIN ct_utilisation AS ctutl ON ctvt.ct_utilisation_id = ctutl.id
                            WHERE
                                ctvt.ct_carte_grise_id = ".$row->id."
                                AND ctvt.vst_created = (SELECT MAX(ct_visite.vst_created) FROM ct_visite WHERE ct_visite.ct_carte_grise_id = ".$row->id.")";
                $res1 = $db0->fetchFirst($sql1);

                $anomalies = NULL;
                if(isset($res1) AND ($res1->vst_is_apte == 0)){
                    $sql2 = "SELECT * FROM ct_visite_anomalie LEFT JOIN ct_anomalie ON ct_visite_anomalie.ct_visite_id = ct_anomalie.id WHERE ct_visite_anomalie.ct_anomalie_id = $res1->id";
                    $res2 = $db1->query($sql2);
                    foreach($res2 as $res2){
                        if(!empty($anomalies)) $anomalies .= ", ";
                        $anomalies .= $res2->anml_libelle;
                    }
                }

                if(isset($res1)){
                    $reponses->data[] = array(
                        'cg_immatriculation'    => utf8_encode($row->cg_immatriculation),
                        'cg_nom'                => utf8_encode($row->cg_nom),
                        // 'cg_prenom'             => utf8_encode($row->cg_prenom),
                        // 'cg_phone'              => utf8_encode($row->cg_phone),
                        'cg_prenom'              => utf8_encode($row->cg_phone),
                        'cg_profession'         => utf8_encode($row->cg_profession),
                        'cg_nom_cooperative'    => utf8_encode($row->cg_nom_cooperative),
                        'cg_adresse'            => utf8_encode($row->cg_adresse),
                        'cg_commune'            => utf8_encode($row->cg_commune),
                        'cg_puissance_admin'    => utf8_encode($row->cg_puissance_admin),
                        'cg_nbr_assis'          => utf8_encode($row->cg_nbr_assis),
                        'cg_nbr_debout'         => utf8_encode($row->cg_nbr_debout),
                        'cg_mise_en_service'    => utf8_encode($row->cg_mise_en_service),
                        'cg_num_carte_violette' => utf8_encode($row->cg_num_carte_violette),
                        'cg_date_carte_violette'=> utf8_encode($row->cg_date_carte_violette),
                        'cg_patente'            => utf8_encode($row->cg_patente),
                        'cg_ani'                => utf8_encode($row->cg_ani),
                        'cg_num_vignette'       => utf8_encode($row->cg_num_vignette),
                        'cg_date_vignette'      => utf8_encode($row->cg_date_vignette),
                        'crs_libelle'           => utf8_encode($row->crs_libelle),
                        'sre_libelle'           => utf8_encode($row->sre_libelle),
        
                        'vhc_num_serie'         => utf8_encode($row->vhc_num_serie),
                        'vhc_num_moteur'        => utf8_encode($row->vhc_num_moteur),
                        'vhc_type'              => utf8_encode($row->vhc_type),
                        'vhc_charge_utile'      => utf8_encode($row->vhc_charge_utile),
                        'vhc_poids_vide'        => utf8_encode($row->vhc_poids_vide),
                        'vhc_poids_total_charge'=> utf8_encode($row->vhc_poids_total_charge),
                        'mrq_libelle'           => utf8_encode($row->mrq_libelle),
                        'gr_libelle'            => utf8_encode($row->gr_libelle),
        
                        'vst_num_pv'            => utf8_encode($res1->vst_num_pv),
                        'vst_date_expiration'   => utf8_encode($res1->vst_date_expiration),
                        'vst_is_apte'           => utf8_encode($res1->vst_is_apte),
                        'vst_is_contre_visite'  => utf8_encode($res1->vst_is_contre_visite),
                        'vst_created'           => utf8_encode($res1->vst_created),
                        'ctr_nom'               => utf8_encode($res1->ctr_nom),
                        'prv_nom'               => utf8_encode($res1->prv_nom),
                        'usg_libelle'           => utf8_encode($res1->usg_libelle),
                        'nom_verificateur'      => utf8_encode($res1->vta_name),
                        'usr_name'              => utf8_encode($res1->siat_name),
                        'ut_libelle'            => utf8_encode($res1->ut_libelle),
                        
                        'vst_anomalies'         => utf8_encode($anomalies)
                    );
                }else{
                    $reponses->data[] = array(
                        'cg_immatriculation'    => utf8_encode($row->cg_immatriculation),
                        'cg_nom'                => utf8_encode($row->cg_nom),
                        // 'cg_prenom'             => utf8_encode($row->cg_prenom),
                        // 'cg_phone'              => utf8_encode($row->cg_phone),
                        'cg_prenom'             => utf8_encode($row->cg_phone),
                        'cg_profession'         => utf8_encode($row->cg_profession),
                        'cg_nom_cooperative'    => utf8_encode($row->cg_nom_cooperative),
                        'cg_adresse'            => utf8_encode($row->cg_adresse),
                        'cg_commune'            => utf8_encode($row->cg_commune),
                        'cg_puissance_admin'    => utf8_encode($row->cg_puissance_admin),
                        'cg_nbr_assis'          => utf8_encode($row->cg_nbr_assis),
                        'cg_nbr_debout'         => utf8_encode($row->cg_nbr_debout),
                        'cg_mise_en_service'    => utf8_encode($row->cg_mise_en_service),
                        'cg_num_carte_violette' => utf8_encode($row->cg_num_carte_violette),
                        'cg_date_carte_violette'=> utf8_encode($row->cg_date_carte_violette),
                        'cg_patente'            => utf8_encode($row->cg_patente),
                        'cg_ani'                => utf8_encode($row->cg_ani),
                        'cg_num_vignette'       => utf8_encode($row->cg_num_vignette),
                        'cg_date_vignette'      => utf8_encode($row->cg_date_vignette),
                        'crs_libelle'           => utf8_encode($row->crs_libelle),
                        'sre_libelle'           => utf8_encode($row->sre_libelle),
        
                        'vhc_num_serie'         => utf8_encode($row->vhc_num_serie),
                        'vhc_num_moteur'        => utf8_encode($row->vhc_num_moteur),
                        'vhc_type'              => utf8_encode($row->vhc_type),
                        'vhc_charge_utile'      => utf8_encode($row->vhc_charge_utile),
                        'vhc_poids_vide'        => utf8_encode($row->vhc_poids_vide),
                        'vhc_poids_total_charge'=> utf8_encode($row->vhc_poids_total_charge),
                        'mrq_libelle'           => utf8_encode($row->mrq_libelle),
                        'gr_libelle'            => utf8_encode($row->gr_libelle),
        
                        'vst_num_pv'            => NULL,
                        'vst_date_expiration'   => NULL,
                        'vst_is_apte'           => NULL,
                        'vst_is_contre_visite'  => NULL,
                        'vst_created'           => NULL,
                        'ctr_nom'               => NULL,
                        'prv_nom'               => NULL,
                        'usg_libelle'           => NULL,
                        'nom_verificateur'      => NULL,
                        'usr_name'              => NULL,
                        'ut_libelle'            => NULL,
                        
                        'vst_anomalies'         => NULL
                    );
                }
                

            }
        }else{
            $reponses->data[] = array(
                'cg_immatriculation'    => NULL,
                'cg_nom'                => NULL,
                'cg_prenom'             => NULL,
                'cg_phone'              => NULL,
                'cg_profession'         => NULL,
                'cg_nom_cooperative'    => NULL,
                'cg_adresse'            => NULL,
                'cg_commune'            => NULL,
                'cg_puissance_admin'    => NULL,
                'cg_nbr_assis'          => NULL,
                'cg_nbr_debout'         => NULL,
                'cg_mise_en_service'    => NULL,
                'cg_num_carte_violette' => NULL,
                'cg_date_carte_violette'=> NULL,
                'cg_patente'            => NULL,
                'cg_ani'                => NULL,
                'cg_num_vignette'       => NULL,
                'cg_date_vignette'      => NULL,
                'crs_libelle'           => NULL,
                'sre_libelle'           => NULL,

                'vhc_num_serie'         => NULL,
                'vhc_num_moteur'        => NULL,
                'vhc_type'              => NULL,
                'vhc_charge_utile'      => NULL,
                'vhc_poids_vide'        => NULL,
                'vhc_poids_total_charge'=> NULL,
                'mrq_libelle'           => NULL,
                'gr_libelle'            => NULL,

                'vst_num_pv'            => NULL,
                'vst_date_expiration'   => NULL,
                'vst_is_apte'           => NULL,
                'vst_is_contre_visite'  => NULL,
                'vst_created'           => NULL,
                'ctr_nom'               => NULL,
                'prv_nom'               => NULL,
                'usg_libelle'           => NULL,
                'nom_verificateur'      => NULL,
                'usr_name'              => NULL,
                'ut_libelle'            => NULL,
                
                'vst_anomalies'         => NULL
            );
        }
       
        return $reponses;
    }
}