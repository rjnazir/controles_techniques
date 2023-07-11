<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class crbilanquotidien_xlsCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
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
        $codes  = array();

        // Recuperation des conditions
        $annee = $this->param("annee");
        $offset = $this->param("offset");
        $k = 1;

        if(empty($annee)){
            jMessage::add("Veuillez entrer la date de l'activité, svp!");
            $erreur = true;
            $res = null;
        }else{
            $centres = $myclass->ListCenter($annee);
            $i = 0;
            foreach($centres as $center){
                $res[$i]    = new \stdClass();
                if(!in_array($center->ctr_code, $codes)){
                    /* Rgts CENSERO concerné */
                    $res[$i]->ctr_code  = $center->ctr_code;
                    $res[$i]->ctr_nom   = $myclass->transformcenter($center->ctr_nom);
                    $res[$i]->ctr_lib   = $center->ctr_nom;

                    /***************** SUR SITE *****************/
                    $nbrtbycenter       = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 1, NULL, NULL, NULL, NULL);
                    $res[$i]->total_vt  = $nbrtbycenter;
                    $nbrcontrebycenter  = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 1, NULL, NULL, 1, NULL);
                    $res[$i]->total_contre  = $nbrcontrebycenter;
                    $nbradmbycenter     = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 1, 1, NULL, NULL, NULL);
                    $res[$i]->total_adm = $nbradmbycenter;
                    $nbraptebycenter    = $myclass->newCompteVT($center->ctr_code, $annee, 1, NULL, 1, NULL);
                    $nbrinaptebycenter  = $nbrtbycenter - $nbraptebycenter;
                    $res[$i]->total_inapte  = $nbrinaptebycenter;
                    $nbrifebycenter     = $myclass->newCompteVTIFE($center->ctr_code, $annee, 1, NULL);
                    $res[$i]->total_ife = $nbrifebycenter;
                    $total  += $nbrtbycenter;
                    $contre += $nbrcontrebycenter;
                    $adm    += $nbradmbycenter;
                    $inapte += $nbrinaptebycenter;
                    $ife    += $nbrifebycenter;
                    /***************** FIN SUR SITE *****************/

                    /****************** ITINERANTE ******************/
                    $nbr_itine      = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 1, NULL, NULL, NULL, 'ITINERANTE');
                    $res[$i]->itine = $nbr_itine;
                    $nbr_cvitine    = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 1, NULL, NULL, 1, 'ITINERANTE');
                    $res[$i]->cvitine = $nbr_cvitine;
                    $nbr_admitine   = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 1, 1, NULL, NULL, 'ITINERANTE');
                    $res[$i]->admitine  = $nbr_admitine;
                    $nbr_aptitine   = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 1, NULL, 1, NULL, 'ITINERANTE');
                    $nbr_inptitiner = $nbr_itine - $nbr_aptitine;
                    $res[$i]->inptitine = $nbr_inptitiner;
                    $nbr_ifeitine   = $myclass->newCompteVTIFE($center->ctr_code, $annee, 1, 'ITINERANTE');
                    $res[$i]->ifeitine  = $nbr_ifeitine;
                    $nbr_rtitine    = $myclass->newCompteRT($center->ctr_code, $annee, NULL, NULL, 'ITINERANTE');
                    $res[$i]->rtitine   = $nbr_rtitine;
                    $itiner     += $nbr_itine;
                    $cvitiner   += $nbr_cvitine;
                    $admitiner  += $nbr_admitine;
                    $inptitiner += $nbr_inptitiner;
                    $ifeitiner  += $nbr_ifeitine;
                    $rtitiner   += $nbr_rtitine;
                    /**************** FIN ITINERANTE ****************/

                    /****************** A DOMICILE ******************/
                    $nbrvtdomicile  = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 2, NULL, NULL, NULL, NULL);
                    $res[$i]->total_dom = $nbrvtdomicile;
                    $nbrcontredom   = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 2, NULL, NULL, 1, NULL);
                    $res[$i]->total_cvdom   = $nbrcontredom;
                    $nbradmdom      = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 2, 1, NULL, NULL, NULL);
                    $res[$i]->total_admdom  = $nbradmdom;
                    $nbrinaptedom   = $myclass->getNombreVisiteBy($center->ctr_code, $annee, 2, NULL, 1, NULL, NULL);
                    $nbrinaptedom   = $nbrvtdomicile - $nbrinaptedom;
                    $res[$i]->total_inaptedom   = $nbrinaptedom;
                    $nbrifedom      = $myclass->newCompteVTIFE($center->ctr_code, $annee, 2, NULL);
                    $res[$i]->total_ifedom  = $nbrifedom;
                    $domicile   += $nbrvtdomicile;
                    $contredom  += $nbrcontredom;
                    $admdom     += $nbradmdom;
                    $inaptedom  += $nbrinaptedom;
                    $ifedom     += $nbrifedom;
                    /**************** FIN A DOMICILE ****************/

                    /****************** RECEPTION TECHNIQUE ******************/
                    $nbrrt      = $myclass->newCompteRT($center->ctr_code, $annee, NULL, NULL, NULL);
                    $res[$i]->total_rt = $nbrrt;
                    $nbrrtadm   = $myclass->newCompteRT($center->ctr_code, $annee, 1, NULL, NULL);
                    $res[$i]->total_rtadm = $nbrrtadm;
                    $nbrrttecg  = $myclass->newCompteRT($center->ctr_code, $annee, NULL, 9, NULL);
                    $res[$i]->total_rttecg = $nbrrttecg;
                    $rt     += $nbrrt;
                    $rtadm  += $nbrrtadm;
                    $rttecg += $nbrrttecg;
                    /**************** FIN RECEPTION TECHNIQUE ****************/

                    /****************** COSTATATION AVANT DEDOUANEMENT ******************/
                    $nbrcad = $myclass->newCompteCAD($center->ctr_code, $annee, NULL);
                    $res[$i]->total_cad  = $nbrcad;
                    $cad += $nbrcad;
                    /**************** FIN COSTATATION AVANT DEDOUANEMENT ****************/

                    /* Ajout du code centre dans le tableau des code centre */
                    array_push($codes, $center->ctr_code);
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

        $fichier    .= ("
        <table align='center'>
            <tr>
                <th colspan='21'><u>ETAT FAISANT BILAN D'ACTIVITE JOURNALIERE DU ".date("d/m/Y", strtotime($annee))."</u></th>
            </tr>
        </table>
        <br/>
        <table align='center' border='1'>
            <tr align='center' class='titre2'>
                <th rowspan='3' scope='col'>N&deg;</th>
                <th rowspan='3' scope='col'>CENTRES</th>
                <th colspan='5' scope='col'>VISITE SUR SITE</th>
                <th colspan='6' scope='col'>VISITE ITINERANTE</th>
                <th colspan='5' scope='col'>VISITE A DOMICILE</th>
                <th colspan='3' scope='col'>RT</th>
                <th rowspan='3' scope='col'>CAD</th>
            </tr>
            <tr align='center' class='titre2'>
                <th colspan='3' scope='col'>VISITES</th>
                <th colspan='2' scope='col'>INAPTES</th>
                <th colspan='3' scope='col'>VISITES</th>
                <th colspan='2' scope='col'>INAPTES</th>
                <th rowspan='2' scope='col'>RTI</th>
                <th colspan='3' scope='col'>VISITES</th>
                <th colspan='2' scope='col'>INAPTES</th>
                <th rowspan='2' scope='col'>TTL</th>
                <th rowspan='2' scope='col'>ADM</th>
                <th rowspan='2' scope='col'>TECG</th>
            </tr>
            <tr align='center' class='titre2'>
                <th scope='col'>TTL</th>
                <th scope='col'>CVT</th>
                <th scope='col'>ADM</th>
                <th>TTL</th>
                <th>IFE</th>
                <th scope='col'>TTL</th>
                <th scope='col'>CVT</th>
                <th scope='col'>ADM</th>
                <th scope='col'>TTL</th>
                <th scope='col'>IFE</th>
                <th scope='col'>TTL</th>
                <th scope='col'>CVT</th>
                <th scope='col'>ADM</th>
                <th scope='col'>TTL</th>
                <th scope='col'>IFE</th>
            </tr>
        ");
        $l = 1;
        foreach($res as $res){
            $fichier .= "<tr>";
            $fichier .= "
                            <td>".$l++."</td>
                            <td>".$res->ctr_nom." (".$res->ctr_lib.")</td>
                            <td>".$res->total_vt."</td>
                            <td>".$res->total_contre."</td>
                            <td>".$res->total_adm."</td>
                            <td>".$res->total_inapte."</td>
                            <td>".$res->total_ife."</td>
                            <td>".$res->itine."</td>
                            <td>".$res->cvitine."</td>
                            <td>".$res->admitine."</td>
                            <td>".$res->inptitine."</td>
                            <td>".$res->ifeitine."</td>
                            <td>".$res->rtitine."</td>
                            <td>".$res->total_dom."</td>
                            <td>".$res->total_cvdom."</td>
                            <td>".$res->total_admdom."</td>
                            <td>".$res->total_inaptedom."</td>
                            <td>".$res->total_ifedom."</td>
                            <td>".$res->total_rt."</td>
                            <td>".$res->total_rtadm."</td>
                            <td>".$res->total_rttecg."</td>
                            <td>".$res->total_cad."</td>
                        ";       
            $fichier .= "</tr>";
        }
        $fichier .="
                    <tr class='corps' style='color:#F00; font-weight:bold;'>
                        <th colspan='2' align='right' >TOTAL</th>
                        <th align='right'>".$total."</th>
                        <th align='right'>".$contre."</th>
                        <th align='right'>".$adm."</th>
                        <th align='right'>".$inapte."</th>
                        <th align='right'>".$ife."</th>
                        <th align='right'>".$itiner."</th>
                        <th align='right'>".$cvitiner."</th>
                        <th align='right'>".$admitiner."</th>
                        <th align='right'>".$inptitiner."</th>
                        <th align='right'>".$ifeitiner."</th>
                        <th align='right'>".$rtitiner."</th>
                        <th align='right'>".$domicile."</th>
                        <th align='right'>".$contredom."</th>
                        <th align='right'>".$admdom."</th>
                        <th align='right'>".$inaptedom."</th>
                        <th align='right'>".$ifedom."</th>
                        <th align='right'>".$rt."</th>
                        <th align='right'>".$rtadm."</th>
                        <th align='right'>".$rttecg."</th>
                        <th align='right'>".$cad."</th>
                    </tr>
                    ";
        $fichier .= "</table>";
        $fichier .= "<table>
                        <tr align='left'><th colspan='3'><u>LEGENDES</u> :</th></tr>
                        <tr align='left'><th>TTL</th><td>:</td><td>Total</td></tr>
                        <tr align='left'><th>CVT</th><td>:</td><td>Contre visite</td></tr>
                        <tr align='left'><th>ADM</th><td>:</td><td>Véhicule administratif</td></tr>
                        <tr align='left'><th>IFE</th><td>:</td><td>Inapte pour fumées excessives</td></tr>
                        <tr align='left'><th>RT</th><td>:</td><td>Recéption technique</td></tr>
                        <tr align='left'><th>RTI</th><td>:</td><td>Recéption technique itinérante</td></tr>
                        <tr align='left'><th>TECG</th><td>:</td><td>Transformation entrant changement de genre</td></tr>
                        <tr align='left'><th>CAD</th><td>:</td><td>Constatation avant dédouanement</td></tr>
                    </table>";

        // Declaration du type de contenu
        $file_mane = strtoupper("".str_replace("-","",$annee)).".xls";
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=".$file_mane.""); /* Remplacer .csv par .xls pour exporter en .XLS */

        echo "<html>";
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
        echo "<body>";
        echo utf8_decode($fichier);
        echo "</body>";
        echo "</html>";

        $rep->bodyTpl = "controles_techniques~crbilanquotidien";
        
        $rep->addCSSLink('https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
        $rep->addJSLink('https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js');        
        $rep->addJSLink('https://kit.fontawesome.com/13957d2282.js');
        $rep->body->assignZone('MENU', 'controles_techniques~menu');
        
        $rep->body->assign('erreur', $erreur);
        
        return $rep;
    }
}

