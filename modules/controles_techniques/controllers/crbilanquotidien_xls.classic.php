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
                if(!preg_match("/ITINERANTE/", $center->ctr_nom) AND !preg_match("/BARIKADIMY/", $center->ctr_nom)){
                    $res[$i]    = new \stdClass();
                    // $res[$i]->ctr_nom   = $myclass->transformcenter($center->ctr_nom)." (".$center->ctr_nom.")";
                    $res[$i]->ctr_nom   = $myclass->transformcenter($center->ctr_nom);

                    // VISITE SUR SITE
                    $nbrtbycenter   = $myclass->compteVT($center->id, $annee, $issursite=1, NULL, NULL, NULL);
                    $res[$i]->total_vt  = $nbrtbycenter;
                    $nbradmbycenter = $myclass->compteVT($center->id, $annee, $issursite=1, $isadm=1, NULL, NULL);
                    $res[$i]->total_adm = $nbradmbycenter;
                    $nbraptebycenter= $myclass->compteVT($center->id, $annee, $issursite=1, NULL, $isapte=1, NULL);
                    $nbrinaptebycenter  = $nbrtbycenter - $nbraptebycenter;
                    $res[$i]->total_inapte  = $nbrinaptebycenter;
                    $nbrifebycenter = $myclass->compteinapteife($center->id, $annee, $issursite=1);
                    $res[$i]->total_ife = $nbrifebycenter;
                    $nbrcontrebycenter  = $myclass->compteVT($center->id, $annee, $issursite=1, NULL, NULL, $iscontre=1);
                    $res[$i]->total_contre  = $nbrcontrebycenter;

                    $total  += $nbrtbycenter;
                    $adm    += $nbradmbycenter;
                    $inapte += $nbrinaptebycenter;
                    $ife    += $nbrifebycenter;
                    $contre += $nbrcontrebycenter;

                    // VISITE ITINERANTE
                    $nbr_itine  = $myclass->comptevtitinerante($center->id, $annee, $issursite=1, NULL, NULL, NULL);
                    $res[$i]->itine = $nbr_itine;
                    $nbr_cvitine    = $myclass->comptevtitinerante($center->id, $annee, $issursite=1, NULL, NULL, $iscontre=1);
                    $res[$i]->cvitine   = $nbr_cvitine;
                    $nbr_admitine   = $myclass->comptevtitinerante($center->id, $annee, $issursite=1, $isadm=1, NULL, NULL);
                    $res[$i]->admitine  = $nbr_admitine;
                    $nbr_inptitine  = $myclass->comptevtitinerante($center->id, $annee, $issursite=1, $isadm=1, NULL, NULL);
                    $res[$i]->inptitine = $nbr_inptitine;
                    $nbr_ifeitine    = $myclass->compterti($center->id, $annee, $isadm=1);
                    $res[$i]->ifeitine   = $nbr_ifeitine;
                    $nbr_rtitine    = $myclass->compterti($center->id, $annee, $isadm=1);
                    $res[$i]->rtitine   = $nbr_rtitine;

                    $itiner     += $nbr_itine;
                    $cvitiner   += $nbr_cvitine;
                    $admitiner  += $nbr_admitine;
                    $inptitiner += $nbr_admitine;
                    $ifeitiner  += $nbr_ifeitine;
                    $rtitiner   += $nbr_rtitine;

                    // VISITE A DOMICILE
                    $nbrvtdomicile  = $myclass->compteVT($center->id, $annee, $issursite=2, NULL, NULL, NULL);
                    $res[$i]->total_dom = $nbrvtdomicile;
                    $nbrcontredom   = $myclass->compteVT($center->id, $annee, $issursite=2, NULL, NULL, $iscontre=1);
                    $res[$i]->total_cvdom   = $nbrcontredom;
                    $nbradmdom  = $myclass->compteVT($center->id, $annee, $issursite=2, $isadm=1, NULL, NULL);
                    $res[$i]->total_admdom  = $nbradmdom;
                    $nbrinaptedom   = $myclass->compteVT($center->id, $annee, $issursite=2, NULL, $isapte=1, NULL);
                    $nbrinaptedom   = $nbrvtdomicile - $nbrinaptedom;
                    $res[$i]->total_inaptedom   = $nbrinaptedom;
                    $nbrifedom  = $myclass->compteinapteife($center->id, $annee, $issursite=2);
                    $res[$i]->total_ifedom  = $nbrifedom;

                    $domicile   += $nbrvtdomicile;
                    $contredom  += $nbrcontredom;
                    $admdom     += $nbradmdom;
                    $inaptedom  += $nbrinaptedom;
                    $ifedom     += $nbrifedom;

                    // RECEPTION TECHNIQUE
                    $nbrrt  = $myclass->comptert($center->id, $annee, NULL, NULL);
                    $res[$i]->total_rt  = $nbrrt;
                    $nbrrtadm   = $myclass->comptert($center->id, $annee, $isadm=1, NULL);
                    $res[$i]->total_rtadm   = $nbrrtadm;
                    $nbrrttecg   = $myclass->comptert($center->id, $annee, NULL, $motif=9);
                    $res[$i]->total_rttecg   = $nbrrttecg;

                    $rt += $nbrrt;
                    $rtadm  += $nbrrtadm;
                    $rttecg  += $nbrrttecg;

                    // COSTATATION AVANT DEDOUANEMENT
                    $nbrcad  = $myclass->comptecad($center->id, $annee);
                    $res[$i]->total_cad  = $nbrcad;

                    $cad    += $nbrcad;

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
                            <td>".$res->ctr_nom."</td>
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

        return $rep;
    }
}

