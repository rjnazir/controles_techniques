<head>
<link href="../../../www/jelix/design/jelix.css" rel="stylesheet" type="text/css" />
<link href="../../../jelix/design/jelix.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<h1 align="center" class="apptitle">BILAN D'ACTIVITE JOURNALIERE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{jurl 'controles_techniques~default:index'}"><input name="retour" type="button" value="&lt;&lt; Retour" /></a></h1>
<form action="" method="post" enctype="application/x-www-form-urlencoded" name="form">
<table width="100%">
    <tr>
        <td width="2%"></td>
      <td align="center">
    	<table>
    		<tr>
                <td class="corps">Choisir la date :</td>
                <td></td>
                <td><input type="date" name="annee" id="annee" value="{if($annee)}{$annee}{/if}" ></td>
                <td><input name="ok" type="submit" value="Afficher" /></td>
            </tr>
            {if $erreur == true}
            <tr align="center">
            	<td colspan="4">
                    <table class="sms">
                        <tr>
                            <td>{jmessage}</td>
                        </tr>
                    </table>
				</td>
            </tr>
            {/if}
    	</table>
    	{if $res}
    	<p align="center">
        	<a href="{jurl 'controles_techniques~crbilanquotidien_xls:index', array('annee'=>$annee)}" target="_blank" alt="Exporter en MS Excel" ><img src="../../../msexcel.png" width="3%" alt="Exporter en MS Excel" /></a>
        </p>
        <table align="center" border="1 red 0.1em">
          <tr align="center" class="titre2">
                <th rowspan="3" scope="col">N&deg;</th>
                <th rowspan="3" scope="col">CENTRES</th>
                <th colspan="5" scope="col">VISITE SUR SITE</th>
                <th colspan="6" scope="col">VISITE ITINERANTE</th>
                <th colspan="5" scope="col">VISITE A DOMICILE</th>
                <th colspan="3" scope="col">RT</th>
                <th rowspan="3" scope="col">CAD</th>
            </tr>
            <tr align="center" class="titre2">
                <th colspan="3" scope="col">VISITES</th>
                <th colspan="2" scope="col">INAPTES</th>
                <th colspan="3" scope="col">VISITES</th>
                <th colspan="2" scope="col">INAPTES</th>
                <th rowspan="2" scope="col">RTI</th>
                <th colspan="3" scope="col">VISITES</th>
                <th colspan="2" scope="col">INAPTES</th>
                <th rowspan="2" scope="col">TTL</th>
                <th rowspan="2" scope="col">ADM</th>
                <th rowspan="2" scope="col">TECG</th>
            </tr>
            <tr align="center" class="titre2">
              <th scope="col">TTL</th>
              <th scope="col">CVT</th>
              <th scope="col">ADM</th>
                <th>TTL</th>
                <th>IFE</th>
                <th scope="col">TTL</th>
                <th scope="col">CVT</th>
                <th scope="col">ADM</th>
                <th scope="col">TTL</th>
                <th scope="col">IFE</th>
                <th scope="col">TTL</th>
                <th scope="col">CVT</th>
                <th scope="col">ADM</th>
                <th scope="col">TTL</th>
                <th scope="col">IFE</th>
            </tr>
            {foreach($res as $res)}
            <tr class="corps" style="background:{cycle array('#CCCCCC','#FFFFFF')}">
                <td align="center">{$k++}</td>
                <td>{$res->ctr_nom}</td>
                <td align="right">{$res->total_vt}</td>
                <td align="right">{$res->total_contre}</td>
                <td align="right">{$res->total_adm}</td>
                <td align="right">{$res->total_inapte}</td>
                <td align="right">{$res->total_ife}</td>
                <td align="right">{$res->itine}</td>
                <td align="right">{$res->cvitine}</td>
                <td align="right">{$res->admitine}</td>
                <td align="right">{$res->inptitine}</td>
                <td align="right">{$res->ifeitine}</td>
                <td align="right">{$res->rtitine}</td>
                <td align="right">{$res->total_dom}</td>
                <td align="right">{$res->total_cvdom}</td>
                <td align="right">{$res->total_admdom}</td>
                <td align="right">{$res->total_inaptedom}</td>
                <td align="right">{$res->total_ifedom}</td>
                <td align="right">{$res->total_rt}</td>
                <td align="right">{$res->total_rtadm}</td>
                <td align="right">{$res->total_rttecg}</td>
                <td align="right">{$res->total_cad}</td>
            </tr>
            {/foreach}
            <tr class="corps" style="color:#F00; font-weight:bold;">
            	<th colspan="2" align="right" >TOTAL</th>
             	<th align="right">{$total}</th>
             	<th align="right">{$contre}</th>
            	<th align="right">{$adm}</th>
            	<th align="right">{$inapte}</th>
            	<th align="right">{$ife}</th>
            	<th align="right">{$itiner}</th>
            	<th align="right">{$cvitiner}</th>
            	<th align="right">{$admitiner}</th>
            	<th align="right">{$inptitiner}</th>
            	<th align="right">{$ifeitiner}</th>
            	<th align="right">{$rtitiner}</th>
            	<th align="right">{$domicile}</th>
            	<th align="right">{$contredom}</th>
            	<th align="right">{$admdom}</th>
            	<th align="right">{$inaptedom}</th>
            	<th align="right">{$ifedom}</th>
            	<th align="right">{$rt}</th>
            	<th align="right">{$rtadm}</th>
            	<th align="right">{$rttecg}</th>
            	<th align="right">{$cad}</th>
       	   </tr>
        </table>
    	{/if}
    	</td>
   		<td width="2%"></td>
    </tr>
    <tr>
    	<td></td>
    	<td align="center">
        	<table border="0">
            	<tr align="left">
                	<th colspan="3"><u>LEGENDES</u> :</th>
                </tr>
            	<tr align="left">
                	<th>TTL</th>
                    <td>:</td>
                    <td>Total</td>
                </tr>
            	<tr align="left">
                	<th>CVT</th>
                    <td>:</td>
                    <td>Contre visite</td>
                </tr>
            	<tr align="left">
                	<th>ADM</th>
                    <td>:</td>
                    <td>Véhicule administratif</td>
                </tr>
            	<tr align="left">
                	<th>IFE</th>
                    <td>:</td>
                    <td>Inapte pour fumées excessives</td>
                </tr>
            	<tr align="left">
                	<th>RT</th>
                    <td>:</td>
                    <td>Recéption technique</td>
                </tr>
            	<tr align="left">
                	<th>RTI</th>
                    <td>:</td>
                    <td>Recéption technique itinérante</td>
                </tr>
            	<tr align="left">
                	<th>TECG</th>
                    <td>:</td>
                    <td>Transformation entrant changement de genre</td>
                </tr>
            	<tr align="left">
                	<th>CAD</th>
                    <td>:</td>
                    <td>Constatation avant dédouanement</td>
                </tr>
            </table>
        </td>
    	<td></td>
    </tr>
</table>
</form>