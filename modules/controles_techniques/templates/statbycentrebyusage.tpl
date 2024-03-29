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
<h6 align="center" class="titre2">STATISTIQUE TRIMESTRIELLE VISITE</h6>
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
                        <option value="1000" {if "1000"== $centre}selected{/if}>TOUS CENTRES</option>
                    </select>
                </td>
                <td>
                    <select id="trimestre" class="form-select form-select-sm" name="trimestre" aria-placeholder="Choisir le trimestre">
                        <option value="0">Choisir la période ici</option>
                        <option value="1" {if $trimestre == 1}selected{/if}>Premier trimestre</option>
                        <option value="2" {if $trimestre == 2}selected{/if}>Deuxième trimestre</option>
                        <option value="3" {if $trimestre == 3}selected{/if}>Troisième trimestre</option>
                        <option value="4" {if $trimestre == 4}selected{/if}>Quatrième trimestre</option>
                    </select>
                </td>
                <td><input type="text" maxlength="4" class="form-control form-control-sm" width="4" name="annee" id="annee" placeholder="Ex: 2023" value="{if($annee)}{$annee}{/if}" ></td>
                <td>
                    <input name="OK" class="btn btn-sm btn-primary" type="submit" value="Afficher" />
    	            {* {if !empty($result)}
						<a href="{jurl 'controles_techniques~statbycentrebyusage_xls:index', array('centre'=>$centre, 'trimestre'=>$trimestre, 'annee'=>$annee)}" class="btn btn-sm btn-success btn-icon-split ml-2 mr-2">
							<span class="icon text-white-50">
								<i class="fa fa-file-excel-o"></i>
							</span>
							<span class="text">Exporter en MS Excel</span>
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
        <table align="center" class="table table-sm table-responsive-sm table-striped table-bordered border-primary">
            <thead class="titre2" style="font-size: xx-small;">
                <tr>
                    <th rowspan="3">USAGES EFFECTIFS</th>
                    <th colspan="4">SUR SITE</th>
                    <th rowspan="3">TOTAL</th>
                    <th colspan="4">A DOMICILE</th>
                    <th rowspan="3">TOTAL</th>
                    <th rowspan="3">TOTAL GENERAL</th>
                </tr>
                <tr>
                    <th colspan="2">PARTICULIER</th>
                    <th colspan="2">ADMISTRATIF</th>
                    <th colspan="2">PARTICULIER</th>
                    <th colspan="2">ADMISTRATIF</th>
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
            <tbody>
                {foreach $result as $result}
                    <tr align="right" class="corps" style="background:{cycle array('#CCCCCC','#FFFFFF')}" style="font-size: xx-small;">
                        <td align="left">{$result['usage']}</td>
                        <td>{$result['sspartprem']}</td>
                        <td>{$result['sspartcntr']}</td>
                        <td>{$result['ssadmiprem']}</td>
                        <td>{$result['ssadmicntr']}</td>
                        <td>{$result['ssitetotal']}</td>
                        <td>{$result['adpartprem']}</td>
                        <td>{$result['adpartcntr']}</td>
                        <td>{$result['adadmiprem']}</td>
                        <td>{$result['adadmicntr']}</td>
                        <td>{$result['aditetotal']}</td>
                        <td>{$result['totalgener']}</td>
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