<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class statbycentrebyusagebymonth_xlsCtrl extends jController {
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
                $code = $myclass->getCentreById($centre);
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
                        <th rowspan='3'>USAGES EFFECTIFS</th>
                        <th colspan='4'>SUR SITE</th>
                        <th rowspan='3'>TOTAL</th>
                        <th colspan='4'>A DOMICILE</th>
                        <th rowspan='3'>TOTAL</th>
                        <th rowspan='3'>TOTAL GENERAL</th>
                    </tr>
                    <tr>
                        <th colspan='2'>PARTICULIER</th>
                        <th colspan='2'>ADMISTRATIF</th>
                        <th colspan='2'>PARTICULIER</th>
                        <th colspan='2'>ADMISTRATIF</th>
                    </tr>
                    <tr>
                        <th>PREMIER</th>
                        <th>CONTRE</th>
                        <th>PREMIER</th>
                        <th>CONTRE</th>
                        <th>PREMIER</th>
                        <th>CONTRE</th>
                        <th>PREMIER</th>
                        <th>CONTRE</th>
                    </tr>
                </thead>
                <tbody>");
                    foreach ($result as $result){
                        $fichier .= (
                        "<tr align='right' class='corps' style='background:{cycle array('#CCCCCC','#FFFFFF')}' style='font-size: xx-small;'>
                            <td align='left'>".$result['usage']."</td>
                            <td>".$result['sspartprem']."</td>
                            <td>".$result['sspartcntr']."</td>
                            <td>".$result['ssadmiprem']."</td>
                            <td>".$result['ssadmicntr']."</td>
                            <td>".$result['ssitetotal']."</td>
                            <td>".$result['adpartprem']."</td>
                            <td>".$result['adpartcntr']."</td>
                            <td>".$result['adadmiprem']."</td>
                            <td>".$result['adadmicntr']."</td>
                            <td>".$result['aditetotal']."</td>
                            <td>".$result['totalgener']."</td>
                        </tr>");
                    }
                $fichier .= ("
                </tbody>
            </table>
        ");

        // Declaration du type de contenu
        $file_mane = 'STATISTIQUE ' . $nom_centre .' '. $trim;
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

        return $rep;
    }
}