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
            $sql= " SELECT DISTINCT `ct_centre`.`ctr_nom`, `ct_carte_grise`.`cg_immatriculation`, `ct_visite`.`vst_date_expiration`, `ct_carte_grise`.`cg_nom`,
                    `ct_visite`.`vst_is_apte`, `ct_carte_grise`.`cg_prenom`, `ct_carte_grise`.`cg_nom_cooperative`, Max(`ct_visite`.`vst_created`) AS vst_created FROM `ct_visite`
                    LEFT JOIN `ct_carte_grise` ON `ct_visite`.`ct_carte_grise_id` = `ct_carte_grise`.`id` LEFT JOIN `ct_centre` ON `ct_centre`.`id` = `ct_visite`.`ct_centre_id`
                    WHERE `ct_visite`.`vst_created` LIKE '".$annee."%' AND `ct_visite`.`ct_utilisation_id` = 1 AND ISNULL(`ct_carte_grise`.`cg_immatriculation`) = FALSE
                    GROUP BY `ct_centre`.`ctr_nom`, `ct_carte_grise`.`cg_immatriculation`, `ct_visite`.`vst_date_expiration`, `ct_carte_grise`.`cg_nom_cooperative`,
                    `ct_carte_grise`.`cg_nom`, `ct_visite`.`vst_is_apte`, `ct_carte_grise`.`cg_prenom` ORDER BY `ct_carte_grise`.`cg_immatriculation`";
            $res = $db->query($sql);
            return $res;
        }

        /**
         * Fonction permettant de compter les VT par type d'inaptitude
         * @param $annee    : Annee a traiter pour le statistique
         */
        function ListeVTbyIsApteLimit($annee, $offset){
            $db = jDb::getConnection();
            $sql= " SELECT DISTINCT `ct_centre`.`ctr_nom`, `ct_carte_grise`.`cg_immatriculation`, `ct_visite`.`vst_date_expiration`, `ct_carte_grise`.`cg_nom`,
                    `ct_visite`.`vst_is_apte`, `ct_carte_grise`.`cg_prenom`, `ct_carte_grise`.`cg_nom_cooperative`, Max(`ct_visite`.`vst_created`) AS vst_created FROM `ct_visite`
                    LEFT JOIN `ct_carte_grise` ON `ct_visite`.`ct_carte_grise_id` = `ct_carte_grise`.`id` LEFT JOIN `ct_centre` ON `ct_centre`.`id` = `ct_visite`.`ct_centre_id`
                    WHERE `ct_visite`.`vst_created` LIKE '".$annee."%' AND `ct_visite`.`ct_utilisation_id` = 1 AND ISNULL(`ct_carte_grise`.`cg_immatriculation`) = FALSE
                    GROUP BY `ct_centre`.`ctr_nom`, `ct_carte_grise`.`cg_immatriculation`, `ct_visite`.`vst_date_expiration`, `ct_carte_grise`.`cg_nom_cooperative`,
                    `ct_carte_grise`.`cg_nom`, `ct_visite`.`vst_is_apte`, `ct_carte_grise`.`cg_prenom` ORDER BY `ct_carte_grise`.`cg_immatriculation`";
            $res = $db->LimitQuery($sql, $offset, 100);
            return $res;
        }

        /**
         * Fonction permettant de compter les VT par type d'inaptitude
         * @param $annee    : Annee a traiter pour le statistique
         * @param $IsApte   : Type d'aptitude de visite
         */
        public function CompteVTbyIsApte($annee, $IsApte) {
            $nbr = null;
            $db = jDb::getConnection();
            $sql= " SELECT DISTINCT `ct_carte_grise`.`cg_immatriculation`, `ct_visite`.`vst_date_expiration`, `ct_carte_grise`.`cg_nom`, `ct_visite`.`vst_is_apte`,
                    `ct_carte_grise`.`cg_prenom`, `ct_carte_grise`.`cg_nom_cooperative`, Max(`ct_visite`.`vst_created`) AS vst_created FROM `ct_visite`
                    LEFT JOIN `ct_carte_grise` ON `ct_visite`.`ct_carte_grise_id` = `ct_carte_grise`.`id` WHERE `ct_visite`.`vst_created`
                    LIKE '".$annee."%' AND `ct_visite`.`ct_utilisation_id` = 1 AND ISNULL(`ct_carte_grise`.`cg_immatriculation`) = FALSE
                    GROUP BY  `ct_carte_grise`.`cg_immatriculation`, `ct_visite`.`vst_date_expiration`, `ct_carte_grise`.`cg_nom`, `ct_carte_grise`.`cg_nom_cooperative`,
                    `ct_visite`.`vst_is_apte`, `ct_carte_grise`.`cg_prenom` ORDER BY `ct_carte_grise`.`cg_immatriculation`";
                    
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
            $nbr = null;
            $db = jDb::getConnection();
            $sql= " SELECT DISTINCT `ct_carte_grise`.`cg_immatriculation`, `ct_visite`.`vst_date_expiration`, `ct_carte_grise`.`cg_nom`, `ct_visite`.`vst_is_apte`,
                    `ct_carte_grise`.`cg_prenom`, `ct_carte_grise`.`cg_nom_cooperative`, Max(`ct_visite`.`vst_created`) AS vst_created FROM `ct_visite`
                    LEFT JOIN `ct_carte_grise` ON `ct_visite`.`ct_carte_grise_id` = `ct_carte_grise`.`id` WHERE `ct_visite`.`vst_created`
                    LIKE '".$annee."%' AND `ct_visite`.`ct_utilisation_id` = 1 AND ISNULL(`ct_carte_grise`.`cg_immatriculation`) = FALSE
                    GROUP BY `ct_carte_grise`.`cg_immatriculation`, `ct_visite`.`vst_date_expiration`, `ct_carte_grise`.`cg_nom`, `ct_carte_grise`.`cg_nom_cooperative`,
                    `ct_visite`.`vst_is_apte`, `ct_carte_grise`.`cg_prenom` ORDER BY `ct_carte_grise`.`cg_immatriculation`";
                    
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
            $nbr = null;
            $db = jDb::getConnection();
            $sql= " SELECT DISTINCT `ct_carte_grise`.`cg_immatriculation`, `ct_visite`.`vst_date_expiration`, `ct_carte_grise`.`cg_nom`, `ct_visite`.`vst_is_apte`,
                    `ct_carte_grise`.`cg_prenom`, `ct_carte_grise`.`cg_nom_cooperative`, Max(`ct_visite`.`vst_created`) AS vst_created FROM `ct_visite`
                    LEFT JOIN `ct_carte_grise` ON `ct_visite`.`ct_carte_grise_id` = `ct_carte_grise`.`id` WHERE `ct_visite`.`vst_created`
                    LIKE '".$annee."%' AND `ct_visite`.`ct_utilisation_id` = 1 AND ISNULL(`ct_carte_grise`.`cg_immatriculation`) = FALSE
                    GROUP BY `ct_carte_grise`.`cg_immatriculation`, `ct_visite`.`vst_date_expiration`, `ct_carte_grise`.`cg_nom`, `ct_carte_grise`.`cg_nom_cooperative`,
                    `ct_visite`.`vst_is_apte`, `ct_carte_grise`.`cg_prenom` ORDER BY `ct_carte_grise`.`cg_immatriculation`";
                    
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
            $motdepasse = ($res)?$res->password:null;
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
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND `ct_visite`.`ct_type_visite_id` = ".$issursite."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
                    }
                }else{
                    if(empty($iscontre)){
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.vst_is_apte =".$isapte."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.vst_is_apte =".$isapte." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
                    }
                }
            }else{
                if(empty($isapte)){
                    if(empty($iscontre)){
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_contre_visite = ".$iscontre."";

                    }
                }else{
                    if(empty($iscontre)){
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_apte =".$isapte."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." AND `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_apte =".$isapte." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
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
            $script = null;
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
            // return $array = (array) $res;
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
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." `ct_visite`.`ct_type_visite_id` = ".$issursite."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
                    }
                }else{
                    if(is_null($iscontre)){
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.vst_is_apte =".$isapte."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.vst_is_apte =".$isapte." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
                    }
                }
            }else{
                if(is_null($isapte)){
                    if(is_null($iscontre)){
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_contre_visite = ".$iscontre."";

                    }
                }else{
                    if(is_null($iscontre)){
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_apte =".$isapte."";
                    }else{
                        $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$center." `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_apte =".$isapte." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
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
         * @return $integer : nombre des VHL inapte pour fumée excessive
         */
        function newCompteVTIFE($code, $date, $issursite){
            $db = jDb::getConnection();
            $centers = $this->convertCodeCentreToScript($code);
            if(!empty($centers)){
                $sql = "SELECT * FROM ct_visite INNER JOIN ct_visite_anomalie ON ct_visite.id = ct_visite_anomalie.ct_anomalie_id INNER JOIN ct_anomalie ON ct_visite_anomalie.ct_visite_id = ct_anomalie.id WHERE ct_visite.vst_created LIKE '".$date."%' ".$center." AND ct_visite.ct_type_visite_id = ".$issursite." AND ct_anomalie.anml_code IN ('MOT1', 'MOT2', 'EM20')";
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
         * @param $center   : nom du centre de visite
         * @param $date     : date de visite
         * @param $issursite: type de visite effectué
         */
        function compteinapteife($center, $date, $issursite){
            $db = jDb::getConnection();
            // $condition = ($this->tousitinerante($center)!=="")?"(".$this->tousitinerante($center).") AND ":"";
            // if(!$condition !== ""){
            if(!is_null($center)){
                // $sql = "SELECT * FROM ct_visite INNER JOIN ct_visite_anomalie ON ct_visite.id = ct_visite_anomalie.ct_anomalie_id INNER JOIN ct_anomalie ON ct_visite_anomalie.ct_visite_id = ct_anomalie.id WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." ct_visite.ct_type_visite_id = ".$issursite." AND ct_anomalie.anml_code IN ('MOT1', 'MOT2', 'EM20')";
                $sql = "SELECT * FROM ct_visite INNER JOIN ct_visite_anomalie ON ct_visite.id = ct_visite_anomalie.ct_anomalie_id INNER JOIN ct_anomalie ON ct_visite_anomalie.ct_visite_id = ct_anomalie.id WHERE ct_visite.vst_created LIKE '".$date."%' AND ct_visite.ct_centre_id = ".$center." AND ct_visite.ct_type_visite_id = ".$issursite." AND ct_anomalie.anml_code IN ('MOT1', 'MOT2', 'EM20')";
                $res = $db->query($sql);
                $nbr = $res->rowCount();
            }else{
                $nbr = 0;
            }
            return $nbr;
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
                // case preg_match("/RECEPTION/", $center)   : $centre = 'CENTRE RT'; break;
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
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." `ct_visite`.`ct_type_visite_id` = ".$issursite."";
                        }else{
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
                        }
                    }else{
                        if(empty($iscontre)){
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.vst_is_apte =".$isapte."";
                        }else{
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.vst_is_apte =".$isapte." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
                        }
                    }
                }else{
                    if(empty($isapte)){
                        if(empty($iscontre)){
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm."";
                        }else{
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_contre_visite = ".$iscontre."";

                        }
                    }else{
                        if(empty($iscontre)){
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_apte =".$isapte."";
                        }else{
                            $sql = "SELECT * FROM ct_visite WHERE ct_visite.vst_created LIKE '".$date."%' AND ".$condition." `ct_visite`.`ct_type_visite_id` = ".$issursite." AND ct_visite.ct_utilisation_id = ".$isadm." AND ct_visite.vst_is_apte =".$isapte." AND ct_visite.vst_is_contre_visite = ".$iscontre."";
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
    }
?>