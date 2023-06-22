<head>
<link href="../../../www/jelix/design/jelix.css" rel="stylesheet" type="text/css" />
<link href="../../../jelix/design/jelix.css" rel="stylesheet" type="text/css" />
<link href="/jelix/design/jelix.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
{* <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" /> *}
</head>
<h1 align="center" class="apptitle">STATISTIQUE MENSUELLE PAR MOTIF PAR CENTRE DES RECEPTIONS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{jurl 'controles_techniques~default:index'}"><input name="retour" type="button" value="&lt;&lt; Retour" /></a></h1>
<form action="" method="post" enctype="application/x-www-form-urlencoded" name="form">
<table width="100%">
    <tr>
        <td width="2%"></td>
      <td align="center">
    	<table>
    		<tr>
                <td class="corps">Veuillez remplir les champs <span class="obligatoire">(*)</span> :</td>
                <td></td>
                <td>
                    <select id="centre" name="centre" aria-placeholder="Choisir le centre">
                        <option value="">Choisir le centre ici</option>
                        {foreach $centres as $centres}
                        <option value="{$centres->id}" {if $centres->id == $centre}selected{/if}>{$centres->ctr_nom}</option>
                        {/foreach}
                        {* <option value="1000">TOUS CENTRES</option> *}
                    </select>
                </td>
                <td>
                    <input type="month" name="trimestre" id="trimestre" {if $trimestre}value={$trimestre}{/if}>
                    {* <select id="trimestre" name="trimestre" aria-placeholder="Choisir le trimestre">
                        <option value="0">Choisir la période ici</option>
                        <option value="1" {if $trimestre == 1}selected{/if}>Premier trimestre</option>
                        <option value="2" {if $trimestre == 2}selected{/if}>Deuxième trimestre</option>
                        <option value="3" {if $trimestre == 3}selected{/if}>Troisième trimestre</option>
                        <option value="4" {if $trimestre == 4}selected{/if}>Quatrième trimestre</option>
                    </select> *}
                </td>
                {* <td><input type="text" maxlength="4" width="4" name="annee" id="annee" placeholder="Entrer l'année ici (Ex: 2023)" value="{if($annee)}{$annee}{/if}" ></td> *}
                <td><input name="OK" type="submit" value="Afficher" /></td>
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
    	{if !empty($result)}
    	<p align="center">
        	<a href="{jurl 'controles_techniques~rt_stat_motif_centre_periode_xls:index', array('centre'=>$centre, 'trimestre'=>$trimestre, 'annee'=>$annee)}" target="_blank" alt="Exporter en MS Excel" ><img src="../../../msexcel.png" width="3%" alt="Exporter en MS Excel" /></a>
        </p>
        <br/>
        <table align="center" border="1 red 0.1em">
            <thead class="titre2" style="font-size: xx-small;">
                <tr>
                    <th rowspan="3">MOTIFS</th>
                    <th colspan="3">VHL IMM A MSCR</th>
                    <th colspan="11">VHL IMPORT ET AUTRES</th>
                </tr>
                <tr>
                    <th rowspan="2">PARTICULIER</th>
                    <th rowspan="2">ADM</th>
                    <th rowspan="2">TOTAL</th>
                    <th colspan="2">PTAC < 3.5T</th>
                    <th colspan="2">3.5T ≤ PTAC < 7T</th>
                    <th colspan="2">7T ≤ PTAC < 10T</th>
                    <th colspan="2">10T ≤ PTAC < 19T</th>
                    <th colspan="2">19T ≤ PTAC < 26T</th>
                    <th rowspan="2">TOTAL</th>
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
            <tbody>
                {foreach $result as $result}
                <tr align="right" class="corps" style="background:{cycle array('#CCCCCC','#FFFFFF')}" style="font-size: xx-small;">
                    <td align="left">{$result['motif']}</td>
                    <td>{$result['rtpartvhlimmmga']}</td>
                    <td>{$result['rtadmnvhlimmmga']}</td>
                    <td>{$result['rtttalvhlimmmga']}</td>
                    <td>{$result['rtpevimpinf3500']}</td>
                    <td>{$result['rtadvimpinf3500']}</td>
                    <td>{$result['rtpevimpinf7000']}</td>
                    <td>{$result['rtadvimpinf7000']}</td>
                    <td>{$result['rtpevimpinf10000']}</td>
                    <td>{$result['rtadvimpinf10000']}</td>
                    <td>{$result['rtpevimpinf19000']}</td>
                    <td>{$result['rtadvimpinf19000']}</td>
                    <td>{$result['rtpevimpinf26000']}</td>
                    <td>{$result['rtadvimpinf26000']}</td>
                    <td>{$result['rtttalvhlimport']}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    	{/if}
    	</td>
   		<td width="2%"></td>
    </tr>
</table>
</form>