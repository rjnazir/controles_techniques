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
<h6 align="center" class="titre2">STATISTIQUE JOURNALIERE VISITE PAR USAGES EFFECTIFS</h6>
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
                    <input type="date" class="form-control form-control-sm" name="trimestre" id="trimestre" {if $trimestre}value={$trimestre}{/if}>
                </td>
                <td>
                    <input name="OK" class="btn btn-sm btn-primary" type="submit" value="Afficher" />
                    {* {if !empty($result)}
                    <a href="{jurl 'controles_techniques~statbycentrebyusagebyday_xls:index', array('centre'=>$centre, 'trimestre'=>$trimestre, 'annee'=>$annee)}" class="btn btn-sm btn-success btn-icon-split ml-2 mr-2">
                        <span class="icon text-white-50">
                            <i class="fa fa-file-excel-o"></i>
                        </span>
                        <span class="text">Generer Excel</span>
                    </a>
                    {/if} *}
                </td>
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
            {$res_vt_stat}
    	{/if}
    	</td>
   		<td width="2%"></td>
    </tr>
</table>
</form>