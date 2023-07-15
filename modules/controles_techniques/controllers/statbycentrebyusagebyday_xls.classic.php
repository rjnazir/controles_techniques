<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class statbycentrebyusagebyday_xlsCtrl extends jController {
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
        // switch($trimestre)
        // {
        //     case 1 : $trim = "1ER TRIMESTRE";break;
        //     case 2 : $trim = "2EME TRIMESTRE";break;
        //     case 3 : $trim = "3EME TRIMESTRE";break;
        //     case 4 : $trim = "4EME TRIMESTRE";break;
        // }
        $trim = str_replace('-','_',$trimestre);

        if(empty($annee) AND empty($centre) AND empty($trimestre)){
            jMessage::add("Veuillez entrer les paramètres, svp!");
            $erreur = true;
        }else{
            $_usage = $myclass->getUsageAll();
            $periode = $myclass->convertToMonth($trimestre);

            foreach($_usage as $_usage){
                $usage = $_usage->id;
                $result[$_i]['usage'] = utf8_encode($_usage->usg_libelle);
                $code = $centre != "1000" ? $myclass->getCentreById($centre) : "";
                $result[$_i]['sspartprem'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 2, 1000, 0);
                $result[$_i]['sspartcntr'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 2, 1000, 1);
                $result[$_i]['ssadmiprem'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 1, 1000, 0);
                $result[$_i]['ssadmicntr'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 1, 1000, 1);
                $result[$_i]['ssitetotal'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1, 1000, 1000, 1000);
                $result[$_i]['adpartprem'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 2, 2, 1000, 0);
                $result[$_i]['adpartcntr'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 2, 2, 1000, 1);
                $result[$_i]['adadmiprem'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 2, 1, 1000, 0);
                $result[$_i]['adadmicntr'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 2, 1, 1000, 1);
                $result[$_i]['aditetotal'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 2, 1000, 1000, 1000);
                $result[$_i]['totalgener'] = $myclass->getCompteVisiteByUsageByCentre($code, $usage, $annee, $periode, 1000, 1000, 1000, 1000);
                $_i++;
            }
            $rep->body->assign('result', $result);
            $rep->body->assign('usage', $_usage);
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
        $file_mane = 'STATISTIQUE RT' . $nom_centre .' '. $trim;
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
        $rep->addJSLink('https://kit.fontawesome.com/13957d2282.js');
        $rep->body->assignZone('MENU', 'controles_techniques~menu');

        $rep->body->assign('erreur', $erreur);

        return $rep;
    }
}