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
<h6 align="center" class="titre2">STATISTIQUE MENSUELLE PAR MOTIF PAR CENTRE DES CAD</h6>
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
                        <option value="99999" {if "99999" == $centre}selected{/if} >TOUS CENTRES</option>

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
        	<a href="{jurl 'controles_techniques~cad_stat_motif_centre_periode_xls:index', array('centre'=>$centre, 'trimestre'=>$trimestre, 'annee'=>$annee)}" target="_blank" alt="Exporter en MS Excel" >{image 'msexcel.jpg', array('width'=>40)}</a>
        </p> *}
        <p></p>
        <table align="center" class="table table-sm table-responsive-sm table-striped table-bordered border-primary">
            <thead class="titre2" style="font-size:xx-small;" >
                <tr>
                    <th>CATEGORIE DE VEHICULES</th>
                    <th>3.5T ≤ PTAC < 7T</th>
                    <th>7T ≤ PTAC < 10T</th>
                    <th>10T ≤ PTAC < 19T</th>
                    <th>19T ≤ PTAC < 26T</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                {foreach $result as $result}
                <tr align="center" class="corps" style="background:{cycle array('#CCCCCC','#FFFFFF')}" style="font-size: xx-small;">
                    <td align="left">{$result['GENREVHL']}</td>
                    <td>{$result['VHL07000']}</td>
                    <td>{$result['VHL10000']}</td>
                    <td>{$result['VHL19000']}</td>
                    <td>{$result['VHL26000']}</td>
                    <td>{$result['TOTALGAL']}</td>
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