<head>
<link href="../../../www/jelix/design/jelix.css" rel="stylesheet" type="text/css" />
<link href="../../../jelix/design/jelix.css" rel="stylesheet" type="text/css" />
<link href="/jelix/design/jelix.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
{* <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" /> *}
</head>
<h1 align="center" class="apptitle">
	STATISTIQUES ET BILANS DES CONTROLES TECHNIQUES<br/>
	<span class="welcome">OUTILS DE STATISTIQUE CT - CAD - RT</span>
	{$MENU}
</h1>
<form action="" method="post" enctype="application/x-www-form-urlencoded" name="form">
<h6 align="center" class="titre2">STATISTIQUE MENSUELLE PAR MOTIF PAR CENTRE DES RECEPTIONS</h6>
<table width="100%">
    <tr>
        <td width="2%"></td>
      <td align="center">
    	<table>
    		<tr>
                <td class="corps">Veuillez remplir les champs <span class="obligatoire">(*)</span> :</td>
                <td></td>
                <td>
                    <select id="centre" class="form-select form-select-sm" name="centre" aria-placeholder="Choisir le centre">
                        <option value="">Choisir le centre ici</option>
                        {foreach $centres as $centres}
                        <option value="{$centres->id}" {if $centres->id == $centre}selected{/if}>{$centres->ctr_nom}</option>
                        {/foreach}
                        <option value="1000" {if "1000" == $centre}selected{/if}>TOUS CENTRES</option>
                    </select>
                </td>
                <td>
                    <input type="month" class="form-control form-control-sm" name="trimestre" id="trimestre" {if $trimestre}value={$trimestre}{/if}>
                </td>
                <td><input name="OK" class="btn btn-sm btn-primary" type="submit" value="Afficher" /></td>
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
    	{* <p align="center">
        	<a href="{jurl 'controles_techniques~rt_stat_motif_centre_periode_xls:index', array('centre'=>$centre, 'trimestre'=>$trimestre, 'annee'=>$annee)}" target="_blank" alt="Exporter en MS Excel" >{image 'msexcel.jpg', array('width'=>40)}</a>
        </p> *}
        <table align="center" class="table table-sm table-responsive-sm table-striped table-bordered border-primary">
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