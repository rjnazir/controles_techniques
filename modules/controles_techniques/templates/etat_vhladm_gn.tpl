<head>
<link href="../../../www/jelix/design/jelix.css" rel="stylesheet" type="text/css" />
<link href="../../../jelix/design/jelix.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<h1 align="center" class="apptitle">LISTE DE VEHICULES ADMINISTRATIFS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{jurl 'controles_techniques~default:index'}"><input name="retour" type="button" value="&lt;&lt; Retour" /></a></h1>
<form action="" method="post" enctype="application/x-www-form-urlencoded" name="form">
<table width="100%">
    <tr>
        <td width="2%"></td>
        <td align="center">
            <table>
                <tr>
                    <!--td class="corps">Choisir l'annee :</td>
                    <td></td>
                    <td>
                        <!--select class="corps" name="annee">
							{for($j=($i-25); $j<=($i+25); $j++)}
							<option value="{$j}" {if($j==$i)} selected="selected" {/if}>{$j}</option>
							{/for}
                        </select>
                    </td-->
					<td class="corps">Choisir la date :</td>
					<td></td>
					<td><input type="month" name="annee" id="annee" value='{if($annee)}{$annee}{/if}' ></td>
					<td><input name="ok" type="submit" value="Afficher" /></td>
                </tr>
            </table>
			{if $nbr != null}
			<table align="center">
				<tr valign="middle">
					<td>
						<!--table class="titre2" align="center">
							<tr>
								<th colspan="2" scope="row">&nbsp;STATISTIQUE DE VT ADM.</th>
							</tr>
							<tr>
								<th align="left" scope="row">&nbsp;Apte :</th>
								<td align="right">{$nbrApte}&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<th align="left" scope="row">&nbsp;Inapte :</th>
								<td align="right">{$nbrInapte}&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<th align="left" scope="row">&nbsp;Total :</th>
								<td align="right">{$nbr}&nbsp;&nbsp;</td>
							</tr>
						</table-->
					</td>
					<td>
						<!--table class="titre2" align="center">
							<tr>
								<th colspan="2" scope="row">&nbsp;STATISTIQUE DE VT GN</th>
							</tr>
							<tr>
								<th align="left" scope="row">&nbsp;Apte :</th>
								<td align="right">{$nbrgnapte}&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<th align="left" scope="row">&nbsp;Inapte :</th>
								<td align="right">{$nbrgninapte}&nbsp;&nbsp;</td>
							</tr>
							<tr>
								<th align="left" scope="row">&nbsp;Total :</th>
								<td align="right">{$nbrgn}&nbsp;&nbsp;</td>
							</tr>
						</table-->
					</td>
					<td>
						<a href="{jurl 'controles_techniques~etat_vhladm_gn_xls:index', array('annee'=>$annee,'res'=>$res)}" target="_blank"><img src="../../../printxls.jpg" width="80" alt="Impr. MS Excel" /></a>
					</td>
				</tr>
			</table>

			<table align="center">
				<tr align="center" class="titre2">
					<th scope="col">N&deg;</th>
					<th scope="col">Centre</th>
					<th scope="col">Immatriculation</th>
					<th scope="col">Propri&eacute;taire</th>
					<th scope="col">Date de visite</th>
					<th scope="col">Validit&eacute; visite</th>
					<th scope="col">Aptitude</th>
				</tr>
				{foreach($res as $res)}
				<tr style="background:{cycle array('#CCCCCC','#FFFFFF')}">
					<td align="center">{$k++}</td>
					<td>{$res->ctr_nom}</td>
					<td>{$res->cg_immatriculation}</td>
					<td>&nbsp;{$res->cg_nom}&nbsp;{$res->cg_prenom}</td>
					<td align="center">{$res->vst_created|date_format:'%d/%m/%Y'}</td>
					<td align="center">{$res->vst_date_expiration|date_format:'%d/%m/%Y'}</td>
					<td align="center">{if ($res->vst_is_apte)==0}Inapte{else}Apte{/if}</td>
				</tr>
				{/foreach}
				<tr align="center" {if($nbr<=100)}style="display:none"{/if}>
					<td colspan="7">{pagelinks 'controles_techniques~etat_vhladm_gn:index', array('annee'=>$annee, 'ok'=>$ok), $nbr, $offset, 100, "offset", array()}</td>
				</tr>
			</table>
			{/if}
        </td>
        <td width="2%"></td>
    </tr>
</table>
</form>