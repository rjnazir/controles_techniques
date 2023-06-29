<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class rt_stat_motif_centre_periode_xlsCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->title = "STATISTIQUE MENSUELLE PAR USAGE PAR CENTRE DES VISITES";

        // Initialisation des variable et classes utiles
        $myclass= jClasses::getService("controles_techniques~myclass");
        $erreur = false;
        $result = null;
        $_i = 0;
        $fichier = "";

        // Recupération des variables
        $OK = $this->param("OK");
        $annee = 1000;
        $centre = $this->param("centre");
        $trimestre = $this->param("trimestre");
        $nom_centre = $myclass->transformcenter($myclass->getNomCentreById($centre));

        $trim = str_replace('-','_',$trimestre);

        if(empty($annee) AND empty($centre) AND empty($trimestre)){
            jMessage::add("Veuillez entrer les paramètres, svp!");
            $erreur = true;
        }else{
            $_motifs = $myclass->getAllCtMotif();
            $periode = $myclass->convertToMonth($trimestre);

            foreach($_motifs as $_motifs){
                $_idmtf = $_motifs->id;
                $_mtf   = $myclass->getCtMotifById($_idmtf);
                $_result[$_i]['motif'] = utf8_encode($_motifs->mtf_libelle);
                in_array($centre, array(3,4,6,12)) ? $_c = 26 : $_c = $centre;
                $code = $myclass->getCentreById($_c);
                $_result[$_i]['rtpartvhlimmmga'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 2, 0, '');
                $_result[$_i]['rtadmnvhlimmmga'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1, 0, '');
                $_result[$_i]['rtttalvhlimmmga'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1000, 0, '');
                $_result[$_i]['rtpevimpinf3500'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 2, 1, '< 3500');
                $_result[$_i]['rtadvimpinf3500'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1, 1, '< 3500');
                $_result[$_i]['rtpevimpinf7000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 2, 1, '3.5T ≤ PTAC < 7T');
                $_result[$_i]['rtadvimpinf7000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1, 1, '3.5T ≤ PTAC < 7T');
                $_result[$_i]['rtpevimpinf10000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 2, 1, '7T ≤ PTAC < 10T');
                $_result[$_i]['rtadvimpinf10000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1, 1, '7T ≤ PTAC < 10T');
                $_result[$_i]['rtpevimpinf19000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 2, 1, '10T ≤ PTAC < 19T');
                $_result[$_i]['rtadvimpinf19000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1, 1, '10T ≤ PTAC < 19T');
                $_result[$_i]['rtpevimpinf26000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 2, 1, '19T ≤ PTAC < 26T');
                $_result[$_i]['rtadvimpinf26000'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1, 1, '19T ≤ PTAC < 26T');
                $_result[$_i]['rtttalvhlimport'] = $myclass->getCompteRtByMotifByCentre($code, $_idmtf, $annee, $periode, 1000, 1, '');

                $_i++;
            }
            $rep->body->assign('result', $_result);
            $rep->body->assign('motifs', $_motifs);
        }

        $fichier .= (
            "<table align='center' border='1 red 0.1em'>
                <thead class='titre2' style='font-size: xx-small;'>
                    <tr>
                        <th rowspan='3'>MOTIFS</th>
                        <th colspan='3'>VHL IMM A MSCR</th>
                        <th colspan='11'>VHL IMPORT ET AUTRES</th>
                    </tr>
                    <tr>
                        <th rowspan='2'>PARTICULIER</th>
                        <th rowspan='2'>ADM</th>
                        <th rowspan='2'>TOTAL</th>
                        <th colspan='2'>PTAC < 3.5T</th>
                        <th colspan='2'>3.5T ≤ PTAC < 7T</th>
                        <th colspan='2'>7T ≤ PTAC < 10T</th>
                        <th colspan='2'>10T ≤ PTAC < 19T</th>
                        <th colspan='2'>19T ≤ PTAC < 26T</th>
                        <th rowspan='2'>TOTAL</th>
                    </tr>
                    <tr>
                        <th>PARTICULIER</th>
                        <th>ADM</th>
                        <th>PARTICULIER</th>
                        <th>ADM</th>
                        <th>PARTICULIER</th>
                        <th>ADM</th>
                        <th>PARTICULIER</th>
                        <th>ADM</th>
                        <th>PARTICULIER</th>
                        <th>ADM</th>
                    </tr>
                </thead>
                <tbody>");
                    foreach ($result as $result){
                        $fichier .= (
                        "<tr align='right' class='corps' style='background:{cycle array('#CCCCCC','#FFFFFF')}' style='font-size: xx-small;'>
                            <td align='left'>".$result['motif']."</td>
                            <td>".$result['rtpartvhlimmmga']."</td>
                            <td>".$result['rtadmnvhlimmmga']."</td>
                            <td>".$result['rtttalvhlimmmga']."</td>
                            <td>".$result['rtpevimpinf3500']."</td>
                            <td>".$result['rtadvimpinf3500']."</td>
                            <td>".$result['rtpevimpinf7000']."</td>
                            <td>".$result['rtadvimpinf7000']."</td>
                            <td>".$result['rtpevimpinf10000']."</td>
                            <td>".$result['rtadvimpinf10000']."</td>
                            <td>".$result['rtpevimpinf19000']."</td>
                            <td>".$result['rtadvimpinf19000']."</td>
                            <td>".$result['rtpevimpinf26000']."</td>
                            <td>".$result['rtadvimpinf26000']."</td>
                            <td>".$result['rtttalvhlimport']."</td>
                        </tr>");
                    }
                $fichier .= ("
                </tbody>
            </table>
        ");


        // Declaration du type de contenu
        $file_mane = 'STATISTIQUE RT ' . $nom_centre .' '. $trim;
        $file_mane = strtolower(str_replace(" ","_",$file_mane)).".xls";
        header("Content-type: application/vnd.ms-excel");
        header("Content-disposition: attachment; filename=".$file_mane.""); /* Remplacer .csv par .xls pour exporter en .XLS */

        echo "<html>";
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
        echo "<body>";
        echo utf8_decode($fichier);
        echo "</body>";
        echo "</html>";

        $rep->bodyTpl = "controles_techniques~statbycentrebyusage";
        
        $rep->addCSSLink('https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
        $rep->addJSLink('https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js');        
        $rep->body->assignZone('MENU', 'controles_techniques~menu');
        
        return $rep;
    }
}

