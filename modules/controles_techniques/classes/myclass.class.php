<?php
    class myclass {
        /**
         * Fonction de générer le type d'ecriture de numéro d'immatriculation
         * @param   $imm : immatriculation du vehicule à chercher
         * @return  $imm0: deuxième ecriture del'immatriculation
         */
        function immatriculation($imm){
            $immalpha = ""; $immnum = ""; $l=strlen($imm); $espace = false; $imm0 = "";
            for($i=0; $i<$l; $i++){
                $pimm = substr($imm, $i, 1);
                if(preg_match("#[a-zA-Z]#", $pimm)){
                    $immalpha .= $pimm;
                }else{
                    $immnum .= $pimm;
                }
            }

            for($j=0; $j<$l; $j++){
                $part = substr($imm, $j, 1);
                if(!preg_match("#[a-zA-Z0-9]#", $part)) $espace = true;
            }

            if($espace == false){
                if(preg_match("#[a-zA-Z]#", substr($imm, 0, 1))){
                    $imm0 = $immalpha." ".$immnum;
                }else{
                    $imm0 = $immnum." ".$immalpha;
                }
            }else{
                for($k=0; $k<$l; $k++){
                    $subimm = substr($imm, $k, 1);
                    if(preg_match("#[a-zA-Z0-9]#", $subimm)) $imm0 .= $subimm;
                }
            }

            return $imm0;
        }

        /**
         *  Fonction permettant de récupérer le numéro de série ou
         *  l'immatriculation d'un véhicule donnée
         *  @param  $imm : Immatriculation donnée
         *  @return $ImmOrNserie : resultat suivant condition entrée (imm. ou N° série)
         */
        function getImmOrNserie($imm){
            $im0 = $this->immatriculation($imm);

            $cartegrise = jDao::get('ct_carte_grise_ct_vehicule');
            $papier = $cartegrise->findFirstBy($imm, $im0, $imm);
            (isset($papier)) ? (($papier->vhc_num_serie == $imm) ? $ImmOrNserie = $papier->cg_immatriculation :  $ImmOrNserie = $papier->vhc_num_serie) : $ImmOrNserie = $papier->cg_immatriculation;

            return $ImmOrNserie;
        }

        /**
         *  Fonction permettant de récupérer tous les renseignements des visites d'un véhicule donné
         *  @param  $imm : immatriculation ou numéro de série du véhicule
         *  @return $res : tableau portant tous les renseignements des visites d'un véhicule
         */
        function findVisiteByImm($imm){
            $res    = array();
            $im0    = $this->immatriculation($imm);
            $nsr    = $this->getImmOrNserie($imm);

            $db     = jDb::getConnection();
            $sql    = "SELECT ct_verificateur.usr_name AS verificateur,
                            ct_user.usr_name AS secretaire,
                            ct_visite.id AS ID,
                            ct_visite.*,
                            ct_carte_grise.*,
                            ct_vehicule.*,
                            ct_centre.*,
                            ct_type_visite.*,
                            ct_user.*,
                            ct_verificateur.*,
                            ct_utilisation.*
                        FROM    ct_visite INNER JOIN
                                ct_carte_grise ON ct_visite.ct_carte_grise_id = ct_carte_grise.id INNER JOIN
                                ct_vehicule ON ct_carte_grise.ct_vehicule_id = ct_vehicule.id INNER JOIN
                                ct_centre ON ct_visite.ct_centre_id = ct_centre.id INNER JOIN
                                ct_type_visite ON ct_visite.ct_type_visite_id = ct_type_visite.id INNER JOIN
                                ct_usage ON ct_visite.ct_usage_id = ct_usage.id INNER JOIN
                                ct_user ON ct_visite.ct_user_id = ct_user.id INNER JOIN
                                ct_user AS ct_verificateur ON ct_visite.ct_verificateur_id = ct_verificateur.id INNER JOIN
                                ct_utilisation ON ct_visite.ct_utilisation_id = ct_utilisation.id
                        WHERE   ct_carte_grise.cg_immatriculation = '".$imm."'
                                OR ct_carte_grise.cg_immatriculation = '".$im0."'
                                OR ct_vehicule.vhc_num_serie = '".$nsr."'
                        ORDER BY ct_visite.id DESC";
            $res    = $db->query($sql);
            return $res;
        }

        /**
         * Fonction permettant de compter les VT par type d'inaptitude
         * @param $annee    : Annee a traiter pour le statistique
         */
        function ListeVTbyIsApte($annee){
            $db = jDb::getConnection();
            $sql= " SELECT DISTINCT ct_centre.ctr_nom, ct_carte_grise.cg_immatriculation, ct_visite.vst_date_expiration, ct_carte_grise.cg_nom,
                    ct_visite.vst_is_apte, ct_carte_grise.cg_prenom, ct_carte_grise.cg_nom_cooperative, Max(ct_visite.vst_created) AS vst_created FROM ct_visite
                    LEFT JOIN ct_carte_grise ON ct_visite.ct_carte_grise_id = ct_carte_grise.id LEFT JOIN ct_centre ON ct_centre.id = ct_visite.ct_centre_id
                    WHERE ct_visite.vst_created LIKE '".$annee."%' AND ct_visite.ct_utilisation_id = 1 AND ISNULL(ct_carte_grise.cg_immatriculation) = FALSE
                    GROUP BY ct_centre.ctr_nom, ct_carte_grise.cg_immatriculation, ct_visite.vst_date_expiration, ct_carte_grise.cg_nom_cooperative,
                    ct_carte_grise.cg_nom, ct_visite.vst_is_apte, ct_carte_grise.cg_prenom ORDER BY ct_carte_grise.cg_immatriculation";
            $res = $db->query($sql);
            return $res;
        }

        /**
         * Fonction permettant de compter les VT par type d'inaptitude
         * @param $annee    : Annee a traiter pour le statistique
         */
        function ListeVTbyIsApteLimit($annee, $offset){
            $db = jDb::getConnection();
            $sql= " SELECT DISTINCT ct_centre.ctr_nom, ct_carte_grise.cg_immatriculation, ct_visite.vst_date_expiration, ct_carte_grise.cg_nom,
                    ct_visite.vst_is_apte, ct_carte_grise.cg_prenom, ct_carte_grise.cg_nom_cooperative, Max(ct_visite.vst_created) AS vst_created FROM ct_visite
                    LEFT JOIN ct_carte_grise ON ct_visite.ct_carte_grise_id = ct_carte_grise.id LEFT JOIN ct_centre ON ct_centre.id = ct_visite.ct_centre_id
                    WHERE ct_visite.vst_created LIKE '".$annee."%' AND ct_visite.ct_utilisation_id = 1 AND ISNULL(ct_carte_grise.cg_immatriculation) = FALSE
                    GROUP BY ct_centre.ctr_nom, ct_carte_grise.cg_immatriculation, ct_visite.vst_date_expiration, ct_carte_grise.cg_nom_cooperative,
                    ct_carte_grise.cg_nom, ct_visite.vst_is_apte, ct_carte_grise.cg_prenom ORDER BY ct_carte_grise.cg_immatriculation";
            $res = $db->LimitQuery($sql, $offset, 100);
            return $res;
        }

        /**
         * Fonction permettant de compter les VT par type d'inaptitude
         * @param $annee    : Annee a traiter pour le statistique
         * @param $IsApte   : Type d'aptitude de visite
         */
        public function CompteVTbyIsApte($annee, $IsApte) {
            $nbr = NULL;
            $db = jDb::getConnection();
            $sql= " SELECT DISTINCT ct_carte_grise.cg_immatriculation, ct_visite.vst_date_expiration, ct_carte_grise.cg_nom, ct_visite.vst_is_apte,
                    ct_carte_grise.cg_prenom, ct_carte_grise.cg_nom_cooperative, Max(ct_visite.vst_created) AS vst_created FROM ct_visite
                    LEFT JOIN ct_carte_grise ON ct_visite.ct_carte_grise_id = ct_carte_grise.id WHERE ct_visite.vst_created
                    LIKE '".$annee."%' AND ct_visite.ct_utilisation_id = 1 AND ISNULL(ct_carte_grise.cg_immatriculation) = FALSE
                    GROUP BY  ct_carte_grise.cg_immatriculation, ct_visite.vst_date_expiration, ct_carte_grise.cg_nom, ct_carte_grise.cg_nom_cooperative,
                    ct_visite.vst_is_apte, ct_carte_grise.cg_prenom ORDER BY ct_carte_grise.cg_immatriculation";
                    
            $res = $db->query($sql);
            foreach($res as $res){
                if($res->vst_is_apte == $IsApte) $nbr++;
            }
            return $nbr;
        }

        /**
         * Fonction permettant de compter les VT de la GN
         * @param $annee    : Annee a traiter pour le statistique
         */
        public function CompteVTGN($annee) {
            $nbr = NULL;
            $db = jDb::getConnection();
            $sql= " SELECT DISTINCT ct_carte_grise.cg_immatriculation, ct_visite.vst_date_expiration, ct_carte_grise.cg_nom, ct_visite.vst_is_apte,
                    ct_carte_grise.cg_prenom, ct_carte_grise.cg_nom_cooperative, Max(ct_visite.vst_created) AS vst_created FROM ct_visite
                    LEFT JOIN ct_carte_grise ON ct_visite.ct_carte_grise_id = ct_carte_grise.id WHERE ct_visite.vst_created
                    LIKE '".$annee."%' AND ct_visite.ct_utilisation_id = 1 AND ISNULL(ct_carte_grise.cg_immatriculation) = FALSE
                    GROUP BY ct_carte_grise.cg_immatriculation, ct_visite.vst_date_expiration, ct_carte_grise.cg_nom, ct_carte_grise.cg_nom_cooperative,
                    ct_visite.vst_is_apte, ct_carte_grise.cg_prenom ORDER BY ct_carte_grise.cg_immatriculation";
                    
            $res = $db->query($sql);
            foreach($res as $res){
                if(preg_match("/GENDARMERIE/i", $res->cg_nom, $matches0) || preg_match("/GN\b/", $res->cg_nom, $matches1) || preg_match("/G N\b/", $res->cg_nom, $matches1) || preg_match("/DGSR\b/", $res->cg_nom, $matches1) || preg_match("/D G S R\b/", $res->cg_nom, $matches1) || preg_match("/SECURITE ROUTIERE/", $res->cg_nom, $matches1)) $nbr++;
            }
            return $nbr;
        }

        /**
         * Fonction permettant de compter les VT de la GN
         * @param $annee    : Annee a traiter pour le statistique
         * @param $IsApte   : Type d'aptitude de visite
         */
        public function CompteVTGNbyIsApte($annee, $IsApte) {
            $nbr = NULL;
            $db = jDb::getConnection();
            $sql= " SELECT DISTINCT ct_carte_grise.cg_immatriculation, ct_visite.vst_date_expiration, ct_carte_grise.cg_nom, ct_visite.vst_is_apte,
                    ct_carte_grise.cg_prenom, ct_carte_grise.cg_nom_cooperative, Max(ct_visite.vst_created) AS vst_created FROM ct_visite
                    LEFT JOIN ct_carte_grise ON ct_visite.ct_carte_grise_id = ct_carte_grise.id WHERE ct_visite.vst_created
                    LIKE '".$annee."%' AND ct_visite.ct_utilisation_id = 1 AND ISNULL(ct_carte_grise.cg_immatriculation) = FALSE
                    GROUP BY ct_carte_grise.cg_immatriculation, ct_visite.vst_date_expiration, ct_carte_grise.cg_nom, ct_carte_grise.cg_nom_cooperative,
                    ct_visite.vst_is_apte, ct_carte_grise.cg_prenom ORDER BY ct_carte_grise.cg_immatriculation";
                    
            $res = $db->query($sql);
            foreach($res as $res){
                if((preg_match("/GENDARMERIE/i", $res->cg_nom, $matches0) || preg_match("/GN\b/", $res->cg_nom, $matches1) || preg_match("/G N\b/", $res->cg_nom, $matches1) || preg_match("/DGSR\b/", $res->cg_nom, $matches1) || preg_match("/D G S R\b/", $res->cg_nom, $matches1) || preg_match("/SECURITE ROUTIERE/", $res->cg_nom, $matches1)) AND $res->vst_is_apte == $IsApte) $nbr++;
            }
            return $nbr;
        }

        /**
         * Fonction permettant de verifier les données d'un utlisateur
         * @param $login    : Login de l'utilisateur
         * @param $password : Mot de passe de l'utilisateur
         */
        function checkUser($login, $password){
            $checked = 'NON';
            $db = jDb::getDbWidget();
            $sql = "SELECT ct_user.password FROM ct_user WHERE ct_user.username = '".$login."'";
            $res = $db->fetchFirst($sql);
            $motdepasse = ($res)?$res->password:NULL;
            echo $motdepasse."<br/>";
            $options = [
                'salt' => "%secret%",
                'cost' => 12
            ];
            $password2 = password_hash($password, PASSWORD_BCRYP);
            echo $password2."<br/>";
            if(password_verify($motdepasse, $password)) $checked = 'OUI';
            echo "LA VALEUR EST : ".$checked;
            return $checked;
        }

        /**
         * Fonction permettant de lister les centres ayant des activités pour une date donnée $d
         * @param $date : date d'activité
         */
        function ListCenter($date){
            $db =   jDb::getConnection();
            $sql=   "
                    SELECT  DISTINCT ct_centre.id, ct_centre.ctr_nom, ct_centre.ctr_code, ct_centre.ct_province_id, ct_province.prv_nom
                            FROM    ct_centre INNER JOIN
                                    ct_visite ON ct_centre.id = ct_visite.ct_centre_id INNER JOIN
                                    ct_province ON ct_centre.ct_province_id = ct_province.id
                            WHERE   ct_visite.vst_created LIKE '".$date."%' ORDER BY ct_centre.ct_province_id ASC, ct_centre.id ASC, ct_centre.ctr_code ASC";
            $res=   $db->query($sql);
            return $res;
        }

        /**
         * Fonction permettant de compter le nombre de visite d'une journée donnée
         * avec des conditions de comptabilité
         * @param $center   : nom du centre de visite
         * @param $date     : date de visite
         * @param $issursite: type de visite sur site ou à domicile
         * @param $isadm    : type d'utilisation véhicule
         * @param $isapte   : resultat de la visite technique en question
         * @param $iscontre : type de visite effectuée (visite ou contre visite)
         */
        function compteVT($center, $date, $issursite, $isadm, $isapte, $iscontre){
            $db = jDb::getConnection();
            $center == 7 ? $center = "(ct_visite.ct_centre_id = 7 OR ct_visite.ct_centre_id = 8)" : $center = "ct_visite.ct_centre_id = ".$center."";
            if(empty($isadm)){
                if(empty($isapte)){
                    if(empty($iscontre)){
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND ct_visite.ct_type_visite_id = ".$issursite."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
                    }
                }else{
                    if(empty($iscontre)){
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.vst_is_apte =".$isapte."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.vst_is_apte =".$isapte." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
                    }
                }
            }else{
                if(empty($isapte)){
                    if(empty($iscontre)){
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_contre_visite = ".$iscontre."";

                    }
                }else{
                    if(empty($iscontre)){
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_apte =".$isapte."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_apte =".$isapte." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
                    }
                }
            }
            $res = $db->query($sql);
            $nbr = $res->rowCount();
            return $nbr;
        }

        /**
         * Fonction transformant le code d'un centre en code portant la liste des centres rattachés
         * @param $code : Code du centre concerné
         * @return $script : text contenant la liste des centres rattachés
         */
        function convertCodeCentreToScript($code){
            $db = jDb::getConnection();
            $sql= "SELECT * FROM ct_centre WHERE ct_centre.ctr_code LIKE '".$code."' AND ct_centre.ctr_nom NOT LIKE '%ITINERANTE%'";
            $res= $db->query($sql);
            $nbr= $res->rowCount();
            $script = NULL;
            $i = 0;
            foreach($res as $ctr){
                $i != ($nbr - 1) ? $link = ' OR ' : $link = '';
                $script .= 'ct_visite.ct_centre_id = '.$ctr->id.$link;
                $i++;
            }
            return $script = !empty($script) ? '('.$script.') AND ' : NULL;
        }

        /**
         * Fonction transformant le code d'un centre en code portant la liste des centres rattachés
         * @param $code : Code du centre concerné
         * @return $array : text contenant la liste des centres rattachés
         */
        function convertCodeCentreToArray($code){
            $db = jDb::getConnection();
            $sql= "SELECT ct_centre.id FROM ct_centre WHERE ct_centre.ctr_code LIKE '".$code."' AND ct_centre.ctr_nom NOT LIKE '%ITINERANTE%'";
            $res= $db->query($sql);
            return $res;
        }

        /**
         * Fonction permettant de compter le nombre de visite d'une journée donnée avec des conditions de comptabilité
         * @param $code : nom du centre de visite
         * @param $date : date de visite
         * @param $issursite : type de visite sur site ou à domicile
         * @param $isadm : type d'utilisation véhicule
         * @param $isapte : resultat de la visite technique en question
         * @param $iscontre : type de visite effectuée (visite ou contre visite)
         */
        function newCompteVT($code, $date, $issursite, $isadm, $isapte, $iscontre){
            $db = jDb::getConnection();
            $center = $this->convertCodeCentreToScript($code);
            if(is_null($isadm)){
                if(is_null($isapte)){
                    if(is_null($iscontre)){
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." ct_visite.ct_type_visite_id = ".$issursite."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
                    }
                }else{
                    if(is_null($iscontre)){
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.vst_is_apte =".$isapte."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.vst_is_apte =".$isapte." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
                    }
                }
            }else{
                if(is_null($isapte)){
                    if(is_null($iscontre)){
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_contre_visite = ".$iscontre."";

                    }
                }else{
                    if(is_null($iscontre)){
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_apte =".$isapte."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_apte =".$isapte." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
                    }
                }
            }
            $res = $db->query($sql);
            $nbr = $res->rowCount();
            return $nbr;
        }

        /**
         * Fonction permettant de compter le nombre de visite inapte pour fumée excessive
         * d'une journée donnée avec des conditions de comptabilité
         * @param $code : nom du centre de visite
         * @param $date : date de visite
         * @param $issursite : type de visite effectué
         * @return $nombre : nombre des VHL inapte pour fumée excessive
         */
        function newCompteVTIFE($code, $date, $issursite, $itinerante){
            $nombre = 0;
            $db = jDb::getDbWidget();
            $code = $this->getAllSubCentersByCodeCenter($code, $itinerante);
            if($code !== '()'){
                $sql = "SELECT COUNT(*) AS NOMBRE FROM  ct_visite
                                                        INNER JOIN ct_visite_anomalie ON ct_visite.id = ct_visite_anomalie.ct_anomalie_id 
                                                        INNER JOIN ct_anomalie ON ct_visite_anomalie.ct_visite_id = ct_anomalie.id
                        WHERE ct_visite.vst_created LIKE '".$date."%' AND ct_anomalie.anml_code IN ('MOT1', 'MOT2', 'EM20')";
                !is_null($code) ? $sql .=" AND ct_centre_id IN $code " : $sql .= "";
                !is_null($issursite) ? $sql .=" AND ct_visite.ct_type_visite_id = $issursite " : $sql .= "";
                $nombre = $db->fetchFirst($sql)->NOMBRE;
            }
            return $nombre;     
        }

        /**
         * Fonction permettant de compter le nombre de visite inapte pour fumée excessive
         * d'une journée donnée avec des conditions de comptabilité
         * @param $center   : nom du centre de visite
         * @param $date     : date de visite
         * @param $issursite: type de visite effectué
         */
        function compteinapteife($center, $date, $issursite){
            $db = jDb::getConnection();
            if(!is_null($center)){
                $sql = "SELECT * FROM ct_visite INNER JOIN ct_visite_anomalie ON ct_visite.id = ct_visite_anomalie.ct_anomalie_id INNER JOIN ct_anomalie ON ct_visite_anomalie.ct_visite_id = ct_anomalie.id WHERE ct_visite.vst_created LIKE '".$date."%' AND ct_visite.ct_centre_id = ".$center." AND ct_visite.ct_type_visite_id = ".$issursite." AND ct_anomalie.anml_code IN ('MOT1', 'MOT2', 'EM20')";
                $res = $db->query($sql);
                $nbr = $res->rowCount();
            }else{
                $nbr = 0;
            }
            return $nbr;
        }

        /**
         * Fonction permettant de reccuperer tous les centres itinérantes d'un censero par son code centre
         * @param $code : Code centre du centre concerné
         * @return $array : tabeau des ID des centres itinérantes d'un censero
         */
        function getAllSubCentersByCodeCenter($code, $itinerante){
            $db = jDb::getConnection();
            if($itinerante == 'ITINERANTE'){
                $sql = "SELECT ct_centre.id FROM ct_centre WHERE ct_centre.ctr_code = '".$code."' AND ct_centre.ctr_nom LIKE '%ITINERANTE%'";
            }else{
                $sql = "SELECT ct_centre.id FROM ct_centre WHERE ct_centre.ctr_code = '".$code."' AND ct_centre.ctr_nom NOT LIKE '%ITINERANTE%'";
            }
            $all = $db->query($sql);
            $array = array();
            foreach($all as $one){array_push($array, $one->id);}
            $array = "(".implode($array, ",").")";
            return $array;
        }

        /**
         * Fonction permettant de recuperer nombre visite itinérante d'un censero
         * @param $code : Code du centre
         * @param $date : Date du visite à recupérer
         * @param $site : Type de visite, SUR SITE ou A DOMICILE
         * @param $used : VHL administratif ou particulier
         * @param $apte : Aptitude du VHL
         * @param $type : Visite ou contre visite
         * @return $size: Nombre des visites effectuées
         */
        function getNombreVisiteBy($code, $date, $site, $used, $apte, $type, $itinerante){
            $nombre = 0;
            $db = jDb::getDbWidget();
            $code = $this->getAllSubCentersByCodeCenter($code, $itinerante);
            if($code !== '()'){
                $sql = "SELECT COUNT(*) AS NOMBRE FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%'";
                !is_null($code) ? $sql .=" AND ct_centre_id IN $code "          : $sql .= "";
                !is_null($site) ? $sql .=" AND ct_type_visite_id = $site "      : $sql .= "";
                !is_null($used) ? $sql .=" AND ct_utilisation_id = $used "      : $sql .= "";
                !is_null($apte) ? $sql .=" AND vst_is_apte = $apte "            : $sql .= "";
                !is_null($type) ? $sql .=" AND vst_is_contre_visite = $type "   : $sql .= "";
                $nombre = $db->fetchFirst($sql)->NOMBRE;
            }
            return $nombre;
        }

        /**
         * Fonction permettant d'organiser les centres itinérantes rattachés à un centre
         */
        function tousitinerante($center){
            $db0 = jDb::getDbWidget();
            $db1 = jDb::getConnection();
            // $condition = "";
            $condition = "";
            // $center == 6 ? $center = 26 : $center = $center;
            $sql0 = "SELECT * FROM ct_centre WHERE ct_centre.id = ".$center."";
            $crt = $db0->fetchFirst($sql0);
            if(!empty($crt)){
                $sql1 = "SELECT * FROM ct_centre WHERE ct_centre.id != ".$center." AND ct_centre.ctr_code = '".$crt->ctr_code."' AND ct_centre.id != 8";
                $ctr = $db1->query($sql1);
                $k = $ctr->rowCount();
                $i = 0;
                foreach($ctr as $ctr){
                    if($i < ($k-1)){
                        $condition .= "".$ctr->id.", ";
                    }else{
                        $condition .= "".$ctr->id."";
                    }
                    $i++;
                }
            }
            return $condition = !empty($condition) ? 'ct_centre_id IN ('.$condition.')' : "";
        }

        /**
         * Fonction permettant de organiser la liste des unités traitants les receptions tecchniques effectuées
         * par le centre de reception technique d'alasora pour une date donnée avec le type d'utilisation du véhicule
         */
        function touscentrescrt($center){
            $db0 = jDb::getDbWidget();
            $db1 = jDb::getConnection();
            $condition = "";
            $center == 6 ? $center = 26 : $center = $center;
            $sql0 = "SELECT * FROM ct_centre WHERE ct_centre.id = ".$center."";
            $crt = $db0->fetchFirst($sql0);
            if(!empty($crt)){
                $sql1 = "SELECT * FROM ct_centre WHERE ct_centre.ctr_code = '".$crt->ctr_code."'";
                $ctr = $db1->query($sql1);
                $k = $ctr->rowCount();
                $i = 0;
                foreach($ctr as $ctr){
                    if($i < ($k-1)){
                        $condition .= " ct_centre_id = ".$ctr->id." OR";
                    }else{
                        $condition .= " ct_centre_id = ".$ctr->id."";
                    }
                    $i++;
                }
            }
            return $condition;
        }

        /**
         * Fonction permettant de compter le nombre de receeption technique effectuée
         * par un centre donné à une date donnée avec le type d'utilisation du véhicule
         * @param $center : identifiant du centre en question
         * @param $date : date de la reception technique
         * @param $isadm : type d'utilisation du véhicule
         * @param $motif : Motif de reception du véhicule
         */
        function comptert($center, $date, $isadm, $motif){
            $db = jDb::getConnection();
            if($center != 6){
                if(empty($motif)){
                    if(empty($isadm)){
                        $sql = "SELECT ct_reception.* FROM ct_reception WHERE ct_reception.ct_centre_id = ".$center." AND ct_reception.rcp_created LIKE '".$date."%'";
                    }else{
                        $sql = "SELECT ct_reception.* FROM ct_reception WHERE ct_reception.ct_centre_id = ".$center." AND ct_reception.rcp_created LIKE '".$date."%' AND ct_reception.ct_utilisation_id = ".$isadm."";
                    }
                }else{
                    if(empty($isadm)){
                        $sql = "SELECT ct_reception.* FROM ct_reception WHERE ct_reception.ct_centre_id = ".$center." AND ct_reception.rcp_created LIKE '".$date."%' AND ct_reception.ct_motif_id = ".$motif."";
                    }else{
                        $sql = "SELECT ct_reception.* FROM ct_reception WHERE ct_reception.ct_centre_id = ".$center." AND ct_reception.rcp_created LIKE '".$date."%' AND ct_reception.ct_utilisation_id = ".$isadm." AND ct_reception.ct_motif_id = ".$motif."";
                    }
                }
            }else{
                if(empty($motif)){
                    if(empty($isadm)){
                        $sql = "SELECT ct_reception.* FROM ct_reception WHERE (".$this->touscentrescrt($center).") AND ct_reception.rcp_created LIKE '".$date."%'";
                    }else{
                        $sql = "SELECT ct_reception.* FROM ct_reception WHERE (".$this->touscentrescrt($center).") AND ct_reception.rcp_created LIKE '".$date."%' AND ct_reception.ct_utilisation_id = ".$isadm."";
                    }
                }else{
                    if(empty($isadm)){
                        $sql = "SELECT ct_reception.* FROM ct_reception WHERE (".$this->touscentrescrt($center).") AND ct_reception.rcp_created LIKE '".$date."%' AND ct_reception.ct_motif_id = ".$motif."";
                    }else{
                        $sql = "SELECT ct_reception.* FROM ct_reception WHERE (".$this->touscentrescrt($center).") AND ct_reception.rcp_created LIKE '".$date."%' AND ct_reception.ct_utilisation_id = ".$isadm." AND ct_reception.ct_motif_id = ".$motif."";
                    }
                }
            }
            $res = $db->query($sql);
            $nbr = $res->rowCount();
            return $nbr;
        }

        /**
         * Fonction permettant de compter le nombre de receeption technique itinérante
         * effectuée par un centre donné à une date donnée avec le type d'utilisation du véhicule
         * @param $center : identifiant du centre en question
         * @param $date : date de la reception technique
         * @param $isadm : type d'utilisation du véhicule
         */
        function compterti($center, $date, $isadm){
            $db = jDb::getConnection();
            $condition = ($this->tousitinerante($center)!=="")?"(".$this->tousitinerante($center).") AND ":"";
            if($condition != ""){
                if(empty($isadm)){
                    $sql = "SELECT ct_reception.* FROM ct_reception WHERE ".$condition." ct_reception.rcp_created LIKE '".$date."%'";
                }else{
                    $sql = "SELECT ct_reception.* FROM ct_reception WHERE ".$condition." ct_reception.rcp_created LIKE '".$date."%' AND ct_reception.ct_utilisation_id = ".$isadm."";
                }
                $res = $db->query($sql);
                $nbr = $res->rowCount();
            }else{
                $nbr = 0;
            }
            return $nbr;
        }

        /**
         * Fonction permettant de recuperer le nombre de reception d'un censero
         */
        function newCompteRT($code, $date, $used, $motif, $itin){
            $nombre = 0;
            $db = jDb::getDbWidget();
            if($code === '004') $code = '024';
            $code = $this->getAllSubCentersByCodeCenter($code, $itin);
            if($code !== "()"){
                $sql = "SELECT COUNT(*) AS NOMBRE FROM ct_reception WHERE ct_reception.rcp_created LIKE '".$date."%'";
                !is_null($code) ? $sql .= " AND ct_centre_id IN $code " : $sql .= "";
                !is_null($used) ? $sql .= " AND ct_utilisation_id = $used " : $sql .= "";
                !is_null($motif) ? $sql .= " AND ct_reception.ct_motif_id = $motif " : $sql .= "";
                $nombre = $db->fetchFirst($sql)->NOMBRE;
            }
            return $nombre;
        }

        /**
         * Fonction permettant de mofidier l'affichage du nom du centre
         * suivant le nom de centre entré comme parametre
         * @param $center   : Nom centre à traiter
         */
        function transformcenter($center){
            switch($center){
                case "ALAROBIA"     : $centre = 'DOR'; break;

                case "ALASORA"      : $centre = 'CENSERO ALS'; break;
                case "ANTSIRABE"    : $centre = 'CENSERO ABE'; break;
                case "BETONGOLO"    : $centre = 'CENSERO BGL'; break;
                case "IVATO"        : $centre = 'CENSERO IVT'; break;
                case "TSIROANOMANDIDY": $centre = 'CENSERO TDD'; break;
                
                case "AMBATONDRAZAKA": $centre = 'CENSERO AKA'; break;
                case "FENERIVE-EST" : $centre = 'CENSERO FVE'; break;
                case "MORAMANGA"    : $centre = 'CENSERO MOG'; break;
                case "TANAMBOROZANO": $centre = 'CENSERO TNA'; break;
                case "BARIKADIMY"   : $centre = 'CENSERO TNA'; break;

                case "AMBOSITRA"    : $centre = 'CENSERO ATR'; break;
                case "FARAFANGANA"  : $centre = 'CENSERO FNA'; break;
                case "BESOROHITRA"  : $centre = 'CENSERO FNR'; break;
                case "MANAKARA"     : $centre = 'CENSERO MRA'; break;

                case "TRANOBOZAKA"  : $centre = 'CENSERO ANA'; break;
                case "NOSY BE"      : $centre = 'CENSERO NSB'; break;
                case "SAMBAVA"      : $centre = 'CENSERO SVA'; break;

                case "ANTSOHIHY"    : $centre = 'CENSERO ATH'; break;
                case "AMBOROVY"     : $centre = 'CENSERO MGA'; break;

                case "AMBOVOMBE"    : $centre = 'CENSERO ABA'; break;
                case "IHOSY"        : $centre = 'CENSERO IHO'; break;
                case "MORONDAVA"    : $centre = 'CENSERO MVA'; break;
                case "SANFIL"       : $centre = 'CENSERO TLR'; break;
                case "TAOLAGNARO"   : $centre = 'CENSERO TRO'; break;
                default : $centre = $center;
            }
            return $centre;
        }

        /**
         * Fonction permettant de compter les nombres visites
         * itinérante effectuées par un centre donné à une date données
         * @param $center : liste des centres itinérantes du centre en question
         * @param $date : date de visite
         * @param $issursite : type de visite sur site ou à domicile
         * @param $isadm : type d'utilisation véhicule
         * @param $isapte : resultat de la visite technique en question
         * @param $iscontre : type de visite effectuée (visite ou contre visite)
         */
        function comptevtitinerante($center, $date, $issursite, $isadm, $isapte, $iscontre){
            $db = jDb::getConnection();
            $condition = ($this->tousitinerante($center)!=="")?"(".$this->tousitinerante($center).") AND ":"";
            if($condition != ""){
                if(empty($isadm)){
                    if(empty($isapte)){
                        if(empty($iscontre)){
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." ct_visite.ct_type_visite_id = ".$issursite."";
                        }else{
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
                        }
                    }else{
                        if(empty($iscontre)){
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.vst_is_apte =".$isapte."";
                        }else{
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.vst_is_apte =".$isapte." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
                        }
                    }
                }else{
                    if(empty($isapte)){
                        if(empty($iscontre)){
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm."";
                        }else{
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_contre_visite = ".$iscontre."";

                        }
                    }else{
                        if(empty($iscontre)){
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_apte =".$isapte."";
                        }else{
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." ct_visite.ct_type_visite_id = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_apte =".$isapte." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
                        }
                    }
                }
                $res = $db->query($sql);
                $nbr = $res->rowCount();
            }else{
                $nbr = 0;
            }
            return $nbr;
        }

        /**
         * Fonction permettant de compter le nombre de visite inapte pour fumée excessive
         * d'une journée donnée avec des conditions de comptabilité
         * @param $center : nom du centre de visite
         * @param $date : date de visite
         * @param $issursite : type de visite effectué
         */
        function compteitinerife($center, $date, $issursite){
            $db = jDb::getConnection();
            $condition = ($this->tousitinerante($center)!=="")?"(".$this->tousitinerante($center).") AND ":"";
            if($condition !== ""){
                $sql = "SELECT * FROM ct_visite INNER JOIN ct_visite_anomalie ON ct_visite.id = ct_visite_anomalie.ct_anomalie_id INNER JOIN ct_anomalie ON ct_visite_anomalie.ct_visite_id = ct_anomalie.id WHERE   ct_visite.vst_created LIKE '".$date."%' AND ".$condition." ct_visite.ct_type_visite_id = ".$issursite." AND ct_anomalie.anml_code IN ('MOT1', 'MOT2', 'EM20')";
                $res = $db->query($sql);
                $nbr = $res->rowCount();
            }else{
                $nbr = 0;
            }
            return $nbr;
        }

        /**
         * Fonction permettant de compter le nombre de receeption technique effectuée
         * par un centre donné à une date donnée avec le type d'utilisation du véhicule
         * @param $center   : identifiant du centre en question
         * @param $date     : date de la reception technique
         * @param $isadm    : type d'utilisation du véhicule
         */
        function comptecad($center, $date){
            $db = jDb::getConnection();
            if($center != 6){
                $sql = "SELECT ct_const_av_ded.* FROM ct_const_av_ded WHERE ct_const_av_ded.ct_centre_id = ".$center." AND ct_const_av_ded.cad_created LIKE '".$date."%'";
            }else{
                $sql = "SELECT ct_const_av_ded.* FROM ct_const_av_ded WHERE (".$this->touscentrescrt($center).") AND ct_const_av_ded.cad_created LIKE '".$date."%'";
            }
            $res = $db->query($sql);
            $nbr = $res->rowCount();
            return $nbr;
        }

        /**
         * Fonction permettant de compter le nombre de CAD effectuée par code un centre donné à une date donnée
         * @param $center   : identifiant du centre en question
         * @param $date     : date de la reception technique
         * @param $isadm    : type d'utilisation du véhicule
         */
        function newCompteCAD($code, $date, $itin){
            $nombre = 0;
            $db = jDb::getDbWidget();
            if($code === '004') $code = '024';
            $code = $this->getAllSubCentersByCodeCenter($code, $itin);
            if($code !== "()"){
                $sql = "SELECT COUNT(*) AS NOMBRE FROM ct_const_av_ded WHERE ct_const_av_ded.cad_created LIKE '".$date."%'";
                !is_null($code) ? $sql .= " AND ct_centre_id IN $code " : $sql .= "";
                $nombre = $db->fetchFirst($sql)->NOMBRE;
            }
            return $nombre;
        }

        /**
         * Conversion trimestre en 3 mois
         * @param integer $tr   : trimestre choisi
         * @return array $mois  : liste des mois concernés
         */
        function convertToMonth($tr)
        {
            if(strlen($tr) == 1){
                if($tr == 1){
                    $mois = "('1', '2', '3')";
                }else if($tr == 2){
                    $mois = "('4', '5', '6')";
                }else if($tr == 3){
                    $mois = "('7', '8', '9')";
                }else{
                    $mois = "('10', '11', '12')";
                }
            }else{
                $mois = $tr;
            }
            return $mois;
        }

        /**
         * Recupération tous usages effectis
         */
        function getUsageAll()
        {
            $db = jDb::getConnection();
            // $sql = "SELECT * FROM ct_usage ORDER BY id ASC";
            $sql = "SELECT * FROM ct_usage ORDER BY usg_libelle ASC";
            $r = $db->query($sql);
            return $r;
        }

        /**
         * Récupération des centres mères
         */
        function getCentreParent()
        {
            $list_ctr = '(
                "ALAROBIA", "ALASORA", "ANTSIRABE", "BETONGOLO", "IVATO", "TSIROANOMANDIDY", "AMBATONDRAZAKA", "FENERIVE-EST",
                "MORAMANGA", "TANAMBOROZANO", "AMBOSITRA", "FARAFANGANA", "BESOROHITRA", "MANAKARA", "TRANOBOZAKA", "NOSY BE",
                "SAMBAVA", "ANTSOHIHY", "AMBOROVY", "AMBOVOMBE", "IHOSY", "MORONDAVA", "SANFIL", "TAOLAGNARO"
            )';
            $d = jDb::getConnection();
            $s = "SELECT * FROM ct_centre where ctr_nom IN $list_ctr ORDER BY ct_province_id ASC, id ASC";
            $r = $d->query($s);
            return $r;
        }

        /**
         * Récupération des centres mères
         */
        function getCentreParent2()
        {
            $list_ctr = '(
                "ALAROBIA", "ALASORA", "ANTSIRABE", "BETONGOLO", "IVATO", "TSIROANOMANDIDY", "AMBATONDRAZAKA", "FENERIVE-EST",
                "MORAMANGA", "TANAMBOROZANO", "AMBOSITRA", "FARAFANGANA", "BESOROHITRA", "MANAKARA", "TRANOBOZAKA", "NOSY BE",
                "SAMBAVA", "ANTSOHIHY", "AMBOROVY", "AMBOVOMBE", "IHOSY", "MORONDAVA", "SANFIL", "TAOLAGNARO"
            )';
            $d = jDb::getConnection();
            $s = "SELECT * FROM ct_centre where ctr_nom IN $list_ctr ORDER BY ctr_nom ASC";
            $r = $d->query($s);
            return $r;
        }

        /**
         * Récupération des centres par code centre
         * @param $c    : Code centre à recherche
         * @return $r   : Liste des centres trouvés
         */
        function getCentreByCode($c)
        {
            $d = jDb::getConnection();
            $s = "SELECT id FROM ct_centre WHERE ctr_code = '$c'";
            $r = $d->query($s);
            return $r;
        }

        /**
         * Récupération des centres par code centre
         * @param $c    : Code centre à recherche
         * @return $r   : Liste des centres trouvés
         */
        function getCentreById($i)
        {
            $d = jDb::getDbWidget();
            $s = "SELECT * FROM ct_centre WHERE id = $i";
            $c = $d->fetchFirst($s)->ctr_code;
            return $c;
        }

        /**
         * Récupération des centres par code centre
         * @param $c    : Code centre à recherche
         * @return $r   : Liste des centres trouvés
         */
        function getNomCentreById($i)
        {
            $d = jDb::getDbWidget();
            $s = "SELECT * FROM ct_centre WHERE id = $i";
            $c = $d->fetchFirst($s)->ctr_nom;
            return $c;
        }

        /**
         * Récupération nombre de visite suivant les conditions
         * @param $code     : Identifiant du centre et ces sous centres
         * @param $usage    : Usage effectif du véhicule
         * @param $typevst  : Type de visite (Sur site ou A domicile)
         * @param $isadmin  : Utilisation du véhicule
         * @param $isapte   : Aptitude du véhicule
         * @param $iscontre : Genre de visite première ou contre
         * @return $nombre  : Nombre de visite remplicant les condition;
         */
        function getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, $typevst, $isadmin, $isapte, $iscontre, $isitin)
        {
            if(is_null($code))      $_c_code    = NULL;
            if(is_null($usage))     $_c_usage   = NULL;
            if(is_null($periode))   $_c_periode = NULL;
            if(is_null($typevst))   $_c_cvisite = NULL;
            if(is_null($isadmin))   $_c_isadmin = NULL;
            if(is_null($isapte))    $_c_isapte  = NULL;
            if(is_null($iscontre))  $_c_iscontre= NULL;

            if(isset($code) and !empty($code)){
                switch($isitin){
                    case 0 : 
                        if($code == 7){
                            $_c_code = 'AND ct_visite.ct_centre_id IN (7, 8)';break;
                        }else{
                            $_c_code = 'AND ct_visite.ct_centre_id = '.$code.'';break;
                        }
                    case 1 :
                        $_ctr_code = $this->getCentreById($code);
                        if($code == 7){
                            $_c_code = 'AND ct_visite.ct_centre_id IN (SELECT id FROM ct_centre WHERE ctr_code = "'.$_ctr_code.'" AND id NOT IN (7, 8))';break;
                        }else{
                            $_c_code = 'AND ct_visite.ct_centre_id IN (SELECT id FROM ct_centre WHERE ctr_code = "'.$_ctr_code.'" AND id != '.$code.')';break;
                        }
                    default:
                        if($code == 7){
                            $_c_code = 'AND ct_visite.ct_centre_id IN (SELECT id FROM ct_centre WHERE ctr_code IN (7, 8)';break;
                        }else{
                            $_c_code = 'AND ct_visite.ct_centre_id IN (SELECT id FROM ct_centre WHERE ctr_code = "'.$code.'")';break;
                        }
                } 
            }
            if(isset($usage) and !empty($usage)){
                if(is_null($_c_code)){
                    $_c_usage = 'ct_usage_id = '.$usage;
                }ELSE{
                    $_c_usage = ' AND ct_usage_id = '.$usage;
                }
            }
            if(strlen($periode) != 7 AND strlen($periode) != 10 AND $annee != 1000){
                if(isset($annee) and isset($periode) and !empty($annee) and !empty($periode)){
                    $_c_periode = ' AND (Year(vst_created) = '.$annee.' AND MONTH(vst_created) IN '.$periode.')';
                }
            }else{
                $_c_periode = ' AND vst_created LIKE "'.$periode.'%"';
            }
            switch($typevst){
                case 1 : $_c_cvisite = ' AND ct_type_visite_id = 1';break;
                case 2 : $_c_cvisite = ' AND ct_type_visite_id = 2';break;
                case 1000 : $_c_cvisite = '';break;
            }
            switch($isadmin){
                case 1 : $_c_isadmin = ' AND ct_utilisation_id = 1';break;
                case 2 : $_c_isadmin = ' AND ct_utilisation_id = 2';break;
                case 1000 : $_c_isadmin = '';break;
            }
            switch($isapte){
                case 0 : $_c_isapte = ' AND vst_is_apte = 0';break;
                case 1 : $_c_isapte = ' AND vst_is_apte = 1';break;
                case 1000 : $_c_isapte = '';break;
            }
            switch($iscontre){
                case 0 : $_c_iscontre= ' AND vst_is_contre_visite = 0';break;
                case 1 : $_c_iscontre= ' AND vst_is_contre_visite = 1';break;
                case 1000 : $_c_iscontre= '';break;
            }
            $d = jDb::getDbWidget();
            $s = "SELECT COUNT(*) AS nombre_vt FROM ct_visite INNER JOIN ct_carte_grise ON ct_visite.ct_carte_grise_id = ct_carte_grise.id WHERE ISNULL(cg_immatriculation) = FALSE $_c_code $_c_usage $_c_periode $_c_cvisite $_c_isadmin $_c_isapte $_c_iscontre";
            $nombre = $d->fetchFirst($s)->nombre_vt;
            return $nombre;
        }

        /**
         * Récupération tous motifs de réception
         */
        public function getAllCtMotif()
        {
            $db = jDb::getConnection();
            $sql = "SELECT * FROM ct_motif ORDER BY mtf_libelle ASC";
            $r = $db->query($sql);
            return $r;
        }

        /**
         * Récupération tous genres de CAD
         */
        public function getAllCtGenre()
        {
            $db = jDb::getConnection();
            $sql = "SELECT ct_genre.gr_libelle, ct_genre.ct_genre_categorie_id FROM ct_droit_ptac INNER JOIN
                    ct_genre_categorie ON ct_genre_categorie.id = ct_droit_ptac.ct_genre_categorie_id INNER JOIN
                    ct_genre ON ct_genre_categorie.id = ct_genre.ct_genre_categorie_id WHERE ct_droit_ptac.ct_type_droit_ptac_id = 2
                    GROUP BY ct_genre.gr_libelle, ct_genre.ct_genre_categorie_id ORDER BY ct_genre.ct_genre_categorie_id";
            $r = $db->query($sql);
            return $r;
        }

        /**
         * récupération de donnée du genre selectionné
         */
        public function getOneCtGenreByLibelle($lib)
        {
            $db = jDb::getDbWidget();
            $sq = "SELECT * FROM ct_genre WHERE gr_libelle = '".$lib."'";
            $rs = $db->fetchFirst($sq);
            return $rs;  
        }

        /**
         * Récupération toutes catégories genres de CAD
         */
        public function getAllCtGenreCategorie()
        {
            $db = jDb::getConnection();
            $sql = "SELECT * FROM ct_genre_categorie";
            $r = $db->query($sql);
            return $r;
        }

        /**
         * Récupérer motifs par ID
         * @param $id : Identifiant du motif
         */
        public function getCtMotifById($id)
        {
            $d = jDb::getDbWidget();
            $s = "SELECT * FROM ct_motif WHERE id = $id";
            $m = $d->fetchFirst($s);
            return $m;
        }

        /**
         * Récupération nombre de reception suivant les conditions
         * @param $code     : Identifiant du centre et ces sous centres
         * @param $motif    : Motif de reception du véhicule
         * @param $isadmin  : Utilisation du véhicule (Particulier ou administratif)
         * @return $nombre  : Nombre de visite remplicant les condition;
         */
        function getCompteRtByMotifByCentre($code, $motif, $annee, $periode, $isadmin, $isvhlimmmga, $tonnage)
        {
            $_c_code = NULL; $_c_motif = NULL; $_c_periode = NULL;

            if(isset($code) and !empty($code)){
                $_c_code = 'ct_centre_id IN (SELECT id FROM ct_centre WHERE ctr_code = "'.$code.'")';
            }
            if(isset($motif) and !empty($motif)){
                if(is_null($_c_code)){
                    $_c_motif = 'ct_motif_id = '.$motif;
                }else{
                    $_c_motif = ' AND ct_motif_id = '.$motif;
                }
            }
            if(strlen($periode) != 7 AND $annee != 1000){
                if(isset($annee) and isset($periode) and !empty($annee) and !empty($periode)){
                    $_c_periode = ' AND (Year(rcp_created) = '.$annee.' AND MONTH(rcp_created) IN '.$periode.')';
                }
            }else{
                $_c_periode = ' AND rcp_created LIKE "'.$periode.'%"';
            }
            switch($isadmin){
                case 1 : $_c_isadmin = ' AND ct_utilisation_id = 1';break;
                case 2 : $_c_isadmin = ' AND ct_utilisation_id = 2';break;
                case 1000 : $_c_isadmin = '';break;
            }
            switch($isvhlimmmga){
                case 0 : $_c_isvhlimmmga = ' AND mtf_is_calculable = 0';break;
                case 1 : $_c_isvhlimmmga = ' AND mtf_is_calculable = 1';break;
                case 1000 : $_c_isvhlimmmga = '';break;
            }
            if($isvhlimmmga == 1){
                switch($tonnage){
                    case '< 3500'           : $_c_tonnage = ' AND vhc_poids_total_charge < 3500';break;
                    case '3.5T ≤ PTAC < 7T' : $_c_tonnage = ' AND vhc_poids_total_charge >= 3500 AND vhc_poids_total_charge < 7000';break;
                    case '7T ≤ PTAC < 10T'  : $_c_tonnage = ' AND vhc_poids_total_charge >= 7000 AND vhc_poids_total_charge < 10000';break;
                    case '10T ≤ PTAC < 19T' : $_c_tonnage = ' AND vhc_poids_total_charge >= 10000 AND vhc_poids_total_charge < 19000';break;
                    case '19T ≤ PTAC < 26T' : $_c_tonnage = ' AND vhc_poids_total_charge >= 19000 AND vhc_poids_total_charge < 26000';break;
                    case '26T ≤ PTAC < 32T' : $_c_tonnage = ' AND vhc_poids_total_charge >= 26000 AND vhc_poids_total_charge < 32000';break;
                    case '32T ≤ PTAC < 44T' : $_c_tonnage = ' AND vhc_poids_total_charge >= 32000';break;
                    default                 : $_c_tonnage = '';break;
                }
            }else{
                $_c_tonnage = '';
            }
            $d = jDb::getDbWidget();
            $s = "SELECT COUNT(*) AS nombre_rt FROM ct_reception INNER JOIN ct_motif ON ct_motif.id = ct_reception.ct_motif_id
                INNER JOIN ct_vehicule ON ct_vehicule.id = ct_reception.ct_vehicule_id
                WHERE $_c_code $_c_motif $_c_periode $_c_isadmin $_c_isvhlimmmga $_c_tonnage";
            $nombre = $d->fetchFirst($s)->nombre_rt;
            return $nombre;
        }

        public function getCentresAndSubcentres($id, $ct, $itin)
        {
            if($ct == 'Réception' || $ct == 'Constatation'){
                if($itin == 0){
                    switch($id){
                        case 7 :
                        case 8 : $s = "SELECT id FROM ct_centre WHERE id IN (7, 8)";break;
                        case 3 :
                        case 4 :
                        case 6 :
                        case 12: $s = "SELECT id FROM ct_centre WHERE ctr_code = (SELECT ctr_code FROM ct_centre WHERE id IN (26))";break;
                        default: $s = "SELECT id FROM ct_centre WHERE id = $id";break;
                    }
                }else if($itin == 1){
                    switch($id){
                        case 7 :
                        case 8 : $s = "SELECT id FROM ct_centre WHERE ctr_code = (SELECT ctr_code FROM ct_centre WHERE id IN (7, 8)) AND id NOT IN (7, 8)";break;
                        case 3 :
                        case 4 :
                        case 6 :
                        case 12: $s = "SELECT id FROM ct_centre WHERE ctr_code = (SELECT id FROM ct_centre WHERE id NOT IN (3, 4, 6, 12))";break;
                        default: $s = "SELECT id FROM ct_centre WHERE ctr_code = (SELECT id FROM ct_centre WHERE id = $id) AND id != $id";break;
                    }
                }else if($itin == 2){
                    switch($id){
                        case 7 :
                        case 8 : $s = "SELECT id FROM ct_centre WHERE ctr_code = (SELECT ctr_code FROM ct_centre WHERE id IN (7, 8))";break;
                        case 3 :
                        case 4 :
                        case 6 :
                        case 12: $s = "SELECT id FROM ct_centre WHERE ctr_code = (SELECT id FROM ct_centre WHERE id IN (3, 4, 6, 12, 26))";break;
                        default: $s = "SELECT id FROM ct_centre WHERE ctr_code = (SELECT id FROM ct_centre WHERE id = $id)";break;
                    }
                }
            }elseif($ct == 'Visite'){
                if($itin == 0){
                    switch($id){
                        case 7 :
                        case 8 : $s = "SELECT id FROM ct_centre WHERE id IN (7, 8)";break;
                        default: $s = "SELECT id FROM ct_centre WHERE id = $id";break;
                    }
                }elseif($itin == 1){
                    switch($id){
                        case 7 :
                        case 8 : $s = "SELECT id FROM ct_centre WHERE ctr_code = (SELECT ctr_code FROM ct_centre WHERE id IN (7, 8)) AND id NOT IN (7, 8)";break;
                        default: $s = "SELECT id FROM ct_centre WHERE ctr_code = (SELECT ctr_code FROM ct_centre WHERE id = $id) AND id != $id";break;
                    }
                }elseif($itin == 2){
                    switch($id){
                        case 7 :
                        case 8 : "SELECT id FROM ct_centre WHERE ctr_code = (SELECT ctr_code FROM ct_centre WHERE id IN (7, 8))";break;
                        default: "SELECT id FROM ct_centre WHERE ctr_code = (SELECT ctr_code FROM ct_centre WHERE id = $id";break;
                    }
                }
            }
            return $s;
        }

        public function getCompteRtByMotifGenreTonnage($centre, $periode, $payante, $motif, $iscalculable, $genre, $min, $max)
        {
            $d = jDb::getDbWidget();
            switch($payante){
                case 1 :
                case 2 : $cpayante = " AND ct_reception.ct_utilisation_id = ".$payante."";break;
                default: $cpayante = "";
            }
            if($iscalculable == 1){
                ($min > 0) ? $cmin = " AND ct_vehicule.vhc_poids_total_charge >= ".$min."" : $cmin = "";
                ($max > 0) ? $cmax = " AND ct_vehicule.vhc_poids_total_charge < ".$max."" : $cmax = "";
            }else{
                $cmin = "";
                $cmax = "";
            }
            is_null($genre) ? $cgenre = "" : $cgenre = " AND ct_reception.ct_genre_id IN ".$genre."";
            is_null($motif) ? $cmotif = "" : $cmotif = " AND ct_reception.ct_motif_id IN (".$motif.")";
            $s = "SELECT COUNT(*) AS NBRE FROM ct_reception INNER JOIN ct_vehicule ON ct_vehicule.id = ct_reception.ct_vehicule_id
                    INNER JOIN ct_motif ON ct_motif.id = ct_reception.ct_motif_id
                    WHERE   ct_reception.ct_centre_id IN (".$centre.")
                            AND ct_reception.rcp_created LIKE '".$periode."%'
                            $cpayante
                            $cmotif
                            AND ct_motif.mtf_is_calculable = ".$iscalculable."
                            $cgenre
                            $cmin $cmax
                ";
            $res = $d->fetchFirst($s)->NBRE;
            return $res;
        }

        public function getStatistiqueReception($centre, $periode, $genre, $iscalculable){
            $centre = $this->getCentresAndSubcentres($centre, 'Réception', 0);

            if($genre == '(5, 6, 9, 12, 13, 14, 20)' OR $genre == '(4, 11, 16, 17)'){
                // Vhl à moteur isolé 0 à 3.5T
                $res[0]['motif'] = htmlspecialchars('PTAC < 3,5T');
                $res[0]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, NULL, $iscalculable, $genre, 0, 3500);
                $res[0]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, NULL, $iscalculable, $genre, 0, 3500);
                $res[0]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, NULL, $iscalculable, $genre, 0, 3500);
                // Vhl à moteur isolé 3.5 à 7T
                $res[1]['motif'] = htmlspecialchars('3,5T ≤ PTAC < 7T');
                $res[1]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, NULL, $iscalculable, $genre, 3500, 7000);
                $res[1]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, NULL, $iscalculable, $genre, 3500, 7000);
                $res[1]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, NULL, $iscalculable, $genre, 3500, 7000);
                // Vhl à moteur isolé 7 à 10T
                $res[2]['motif'] = htmlspecialchars('7T ≤ PTAC < 10T');
                $res[2]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, NULL, $iscalculable, $genre, 7000, 10000);
                $res[2]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, NULL, $iscalculable, $genre, 7000, 10000);
                $res[2]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, NULL, $iscalculable, $genre, 7000, 10000);
                // Vhl à moteur isolé 10 à 19T
                $res[3]['motif'] = htmlspecialchars('10T ≤ PTAC < 19T');
                $res[3]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, NULL, $iscalculable, $genre, 10000, 19000);
                $res[3]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, NULL, $iscalculable, $genre, 10000, 19000);
                $res[3]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, NULL, $iscalculable, $genre, 10000, 19000);
                // Vhl à moteur isolé 19 à 26T
                $res[4]['motif'] = htmlspecialchars('19T ≤ PTAC < 26T');
                $res[4]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, NULL, $iscalculable, $genre, 19000, 26000);
                $res[4]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, NULL, $iscalculable, $genre, 19000, 26000);
                $res[4]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, NULL, $iscalculable, $genre, 19000, 26000);
                // Vhl à moteur isolé 26 à 32T
                $res[5]['motif'] = htmlspecialchars('26T ≤ PTAC < 32T');
                $res[5]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, NULL, $iscalculable, $genre, 26000, 32000);
                $res[5]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, NULL, $iscalculable, $genre, 26000, 32000);
                $res[5]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, NULL, $iscalculable, $genre, 26000, 32000);
                // Vhl à moteur isolé 32 à 44T
                $res[6]['motif'] = htmlspecialchars('32T ≤ PTAC < 44T');
                $res[6]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, NULL, $iscalculable, $genre, 32000, NULL);
                $res[6]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, NULL, $iscalculable, $genre, 32000, NULL);
                $res[6]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, NULL, $iscalculable, $genre, 32000, NULL);
            }elseif($genre == '(1, 2, 3, 7, 8, 18)'){
                $res[0]['motif'] = htmlspecialchars('MOTOCYCLETTES, VELOMOTEURS, CYCLOMOTEURS, TRICYCLES ET QUATRICYCLES');
                $res[0]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, NULL, $iscalculable, $genre, NULL, NULL);
                $res[0]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, NULL, $iscalculable, $genre, NULL, NULL);
                $res[0]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, NULL, $iscalculable, $genre, NULL, NULL);
            }elseif($genre == '(10, 15)'){
                $res[0]['motif'] = htmlspecialchars('VÉHICULES ET APPAREILS AGRICOLES OUFORESTIERS, MATÉRIELS DE TP ET ENGINS SPÉCIAUX');
                $res[0]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, NULL, $iscalculable, $genre, NULL, NULL);
                $res[0]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, NULL, $iscalculable, $genre, NULL, NULL);
                $res[0]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, NULL, $iscalculable, $genre, NULL, NULL);
            }else{
                $res[0]['motif'] = htmlspecialchars('PESAGE TOUS VÉHICULES');
                $res[0]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, 3, $iscalculable, NULL, NULL, NULL);
                $res[0]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, 3, $iscalculable, NULL, NULL, NULL);
                $res[0]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, 3, $iscalculable, NULL, NULL, NULL);
                $res[1]['motif'] = htmlspecialchars('CHGMT NB DE PL PTAC 3,5T ET PLUS (SANS TRANSF° GENRE OU DE CARROS)');
                $res[1]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, 5, $iscalculable, NULL, NULL, NULL);
                $res[1]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, 5, $iscalculable, NULL, NULL, NULL);
                $res[1]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, 5, $iscalculable, NULL, NULL, NULL);
                $res[2]['motif'] = htmlspecialchars('REMPLACEMENT DE CADRE OU DE COQUE');
                $res[2]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, 6, $iscalculable, NULL, NULL, NULL);
                $res[2]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, 6, $iscalculable, NULL, NULL, NULL);
                $res[2]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, 6, $iscalculable, NULL, NULL, NULL);
                $res[3]['motif'] = htmlspecialchars('TRANSFORMATION DE CHASSIS');
                $res[3]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, 7, $iscalculable, NULL, NULL, NULL);
                $res[3]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, 7, $iscalculable, NULL, NULL, NULL);
                $res[3]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, 7, $iscalculable, NULL, NULL, NULL);
                $res[4]['motif'] = htmlspecialchars('TRANSFORMATION ENTRAINANT CHANGEMENT DE GENRE');
                $res[4]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, 9, $iscalculable, NULL, NULL, NULL);
                $res[4]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, 9, $iscalculable, NULL, NULL, NULL);
                $res[4]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, 9, $iscalculable, NULL, NULL, NULL);
                $res[5]['motif'] = htmlspecialchars('CHANGEMENT DE MOTEUR');
                $res[5]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, 10, $iscalculable, NULL, NULL, NULL);
                $res[5]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, 10, $iscalculable, NULL, NULL, NULL);
                $res[5]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, 10, $iscalculable, NULL, NULL, NULL);
                $res[6]['motif'] = htmlspecialchars('ERREUR DE TRANSCRIPTION');
                $res[6]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, 11, $iscalculable, NULL, NULL, NULL);
                $res[6]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, 11, $iscalculable, NULL, NULL, NULL);
                $res[6]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, 11, $iscalculable, NULL, NULL, NULL);
                $res[7]['motif'] = htmlspecialchars('PERTE DE PLAQUE DE CONSTRUCTEUR');
                $res[7]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, 12, $iscalculable, NULL, NULL, NULL);
                $res[7]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, 12, $iscalculable, NULL, NULL, NULL);
                $res[7]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, 12, $iscalculable, NULL, NULL, NULL);
                $res[8]['motif'] = htmlspecialchars('CHGMT NB DE PL PTAC MOINS 3,5T (SANS TRANSF° GENRE OU DE CARROS)');
                $res[8]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, 14, $iscalculable, NULL, NULL, NULL);
                $res[8]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, 14, $iscalculable, NULL, NULL, NULL);
                $res[8]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, 14, $iscalculable, NULL, NULL, NULL);
                $res[9]['motif'] = htmlspecialchars('TRANSFORMATION DE CARROSSERIE');
                $res[9]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, 20, $iscalculable, NULL, NULL, NULL);
                $res[9]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, 20, $iscalculable, NULL, NULL, NULL);
                $res[9]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, 20, $iscalculable, NULL, NULL, NULL);
                $res[10]['motif'] = htmlspecialchars('DISCORDANCE CG ET VHL ET ERREUR DE TRANSCRIPTION');
                $res[10]['parti'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 2, 21, $iscalculable, NULL, NULL, NULL);
                $res[10]['admin'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, 1, 21, $iscalculable, NULL, NULL, NULL);
                $res[10]['total'] = $this->getCompteRtByMotifGenreTonnage($centre, $periode, NULL, 21, $iscalculable, NULL, NULL, NULL);
            }
            return $res;
        }

        /**
         * Récupération nombre de CAD suivant les conditions
         * @param $code     : Identifiant du centre et ces sous centres
         * @param $motif    : Motif de reception du véhicule
         * @param $isadmin  : Utilisation du véhicule (Particulier ou administratif)
         * @return $nombre  : Nombre de visite remplicant les condition;
         */
        function getCompteCadByMotifByCentre($code, $annee, $periode, $tonnage, $genre, $isadm)
        {
            $_c_periode = NULL;

            if(strlen($periode) != 7 AND !is_null($annee)){
                if(isset($annee) and isset($periode) and !empty($annee) and !empty($periode)){
                    $_c_periode = ' AND (Year(cad_created) = '.$annee.' AND MONTH(cad_created) IN '.$periode.')';
                }
            }else{
                $_c_periode = ' AND cad_created LIKE "'.$periode.'%"';
            }
            switch($tonnage){
                case '3.5T ≤ PTAC < 7T' : $_c_tonnage = ' AND (cad_poids_total_charge >= 3500 AND cad_poids_total_charge < 7000)';break;
                case '7T ≤ PTAC < 10T'  : $_c_tonnage = ' AND (cad_poids_total_charge >= 7000 AND cad_poids_total_charge < 10000)';break;
                case '10T ≤ PTAC < 19T' : $_c_tonnage = ' AND (cad_poids_total_charge >= 10000 AND cad_poids_total_charge < 19000)';break;
                case '19T ≤ PTAC < 26T' : $_c_tonnage = ' AND (cad_poids_total_charge >= 19000 AND cad_poids_total_charge < 26000)';break;
                case '26T ≤ PTAC < 32T' : $_c_tonnage = ' AND (cad_poids_total_charge >= 26000 AND cad_poids_total_charge < 32000)';break;
                case '32T ≤ PTAC < 44T' : $_c_tonnage = ' AND cad_poids_total_charge >= 32000';break;
                default                 : $_c_tonnage = ' AND cad_poids_total_charge >= 3500';break;
            }

            if($isadm == 1){
                $genre ? $_c_genre = ' AND ct_genre.id IN '.$genre.' AND ct_genre.id NOT IN (19)' : $_c_genre = '';
            }elseif($isadm == 2){
                $genre ? $_c_genre = ' AND ct_genre.id NOT IN (19)' : $_c_genre = '';
            }elseif($isadm == NULL){
                $genre = NULL;
            }

            $d = jDb::getDbWidget();
            $s = "SELECT COUNT(*) AS nombre_cad FROM ct_const_av_ded INNER JOIN ct_const_av_deds_const_av_ded_caracs ON ct_const_av_ded.id = ct_const_av_deds_const_av_ded_caracs.const_av_ded_id
                INNER JOIN ct_const_av_ded_carac ON ct_const_av_deds_const_av_ded_caracs.const_av_ded_carac_id = ct_const_av_ded_carac.id
                INNER JOIN ct_genre ON ct_genre.id = ct_const_av_ded_carac.ct_genre_id
                WHERE ct_const_av_ded_type_id = 2 AND ct_centre_id IN (".$code.") $_c_periode $_c_tonnage $_c_genre";
            $nombre = $d->fetchFirst($s)->nombre_cad;
            return $nombre;
        }

        public function getStatitstiqueCAD($centre, $genre, $periode)
        {
            $centre = $this->getCentresAndSubcentres($centre, 'Constatation', 0);
            
            if($genre == '(5, 6, 9, 12, 13, 14, 20)' OR $genre == '(4, 11, 16, 17)'){
                // Vhl à moteur isolé 3.5 à 7T
                $res[0]['motif'] = htmlspecialchars('3.5T ≤ PTAC < 7T');
                $res[0]['parti'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '3.5T ≤ PTAC < 7T', $genre, 1);
                // $res[0]['admin'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '3.5T ≤ PTAC < 7T', $genre, 2);
                // $res[0]['total'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '3.5T ≤ PTAC < 7T', NULL, NULL);

                // Vhl à moteur isolé 7 à 10T
                $res[1]['motif'] = htmlspecialchars('7T ≤ PTAC < 10T');
                $res[1]['parti'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '7T ≤ PTAC < 10T', $genre, 1);
                // $res[1]['admin'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '7T ≤ PTAC < 10T', '(19)');
                // $res[1]['total'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '7T ≤ PTAC < 10T', NULL);

                // Vhl à moteur isolé 10 à 19T
                $res[2]['motif'] = htmlspecialchars('10T ≤ PTAC < 19T');
                $res[2]['parti'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '10T ≤ PTAC < 19T', $genre, 1);
                // $res[2]['admin'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '10T ≤ PTAC < 19T', '(19)');
                // $res[2]['total'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '10T ≤ PTAC < 19T', NULL);
                
                // Vhl à moteur isolé 19 à 26T
                $res[3]['motif'] = htmlspecialchars('19T ≤ PTAC < 26T');
                $res[3]['parti'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '19T ≤ PTAC < 26T', $genre, 1);
                // $res[3]['admin'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '19T ≤ PTAC < 26T', '(19)');
                // $res[3]['total'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '19T ≤ PTAC < 26T', NULL);

                // Vhl à moteur isolé 26 à 32T
                $res[4]['motif'] = htmlspecialchars('26T ≤ PTAC < 32T');
                $res[4]['parti'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '26T ≤ PTAC < 32T', $genre, 1);
                // $res[4]['admin'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '26T ≤ PTAC < 32T', $genre, 2);
                // $res[4]['total'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '26T ≤ PTAC < 32T', NULL, NULL);

                // Vhl à moteur isolé 32 à 44T
                $res[5]['motif'] = htmlspecialchars('32T ≤ PTAC < 44T');
                $res[5]['parti'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '32T ≤ PTAC < 44T', $genre, 1);
                // $res[5]['admin'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '32T ≤ PTAC < 44T', '(19)');
                // $res[5]['total'] = $this->getCompteCadByMotifByCentre($centre, NULL, $periode, '32T ≤ PTAC < 44T', NULL);
            }
            return $res;
        }
    }
?>