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
                <td class="corps">Choisir le trimestre <span class="obligatoire">(*)</span> :</td>
                <td></td>
                <td>
                    <select id="trimestre" class="form-select form-select-sm" name="trimestre" aria-placeholder="Choisir le trimestre">
                        <option value="0"></option>
                        <option value="1" {if $trimestre == 1}selected{/if}>Premier trimestre</option>
                        <option value="2" {if $trimestre == 2}selected{/if}>Deuxième trimestre</option>
                        <option value="3" {if $trimestre == 3}selected{/if}>Troisième trimestre</option>
                        <option value="4" {if $trimestre == 4}selected{/if}>Quatrième trimestre</option>
                    </select>
                </td>
                <td><input type="text" class="form-control form-control-sm" maxlength="4" width="4" name="annee" id="annee" placeholder="Ex : 2001" value="{if($annee)}{$annee}{/if}" ></td>
                <td>
                    <input name="ok" class="btn btn-sm btn-primary" type="submit" value="Afficher" />
                    {if !empty($res)}
						<a href="{jurl 'controles_techniques~bilantrimestriel_xls:index', array('annee'=>$annee, 'trimestre'=>$trimestre)}" class="btn btn-sm btn-success btn-icon-split ml-2 mr-2">
							<span class="icon text-white-50">
								<i class="fa fa-file-excel-o"></i>
							</span>
							<span class="text">Exporter en MS Excel</span>
						</a>
                    {/if}
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
    	{if !empty($res)}
            {$resultbilantrimestriel}
    	{/if}
    	</td>
   		<td width="2%"></td>
    </tr>
</table>
</form>