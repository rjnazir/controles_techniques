<head>

<link href="../../../www/jelix/design/jelix.css" rel="stylesheet" type="text/css" />
<link href="../../../jelix/design/jelix.css" rel="stylesheet" type="text/css" />
<link href="/jelix/design/jelix.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<h1 align="center" class="apptitle">
	STATISTIQUES ET BILANS DES CONTROLES TECHNIQUES<br/>
	<span class="welcome">OUTILS DE STATISTIQUE CT - CAD - RT</span>
	{$MENU}
</h1>
<form action="" method="post" enctype="application/x-www-form-urlencoded" name="form">
<h6 align="center" class="titre2">LISTE DE VEHICULES ADMINISTRATIFS</h6>
<table width="100%">
    <tr>
        <td width="2%"></td>
        <td align="center">
            <table>
                <tr>
					<td class="corps">Choisir la date :</td>
					<td></td>
					<td><input type="month" class="form-control form-control-sm" name="annee" id="annee" value='{if($annee)}{$annee}{/if}' ></td>
					<td>
						<input name="ok" class="btn btn-sm btn-primary" type="submit" value="Afficher" />
						{if $nbr != null}
						<a href="{jurl 'controles_techniques~etat_vhladm_gn_xls:index', array('annee'=>$annee,'res'=>$res)}" class="btn btn-sm btn-success btn-icon-split ml-2 mr-2">
							<span class="icon text-white-50">
								<i class="fa fa-file-excel-o"></i>
							</span>
							<span class="text">Exporter en MS Excel</span>
						</a>
						{/if}
					</td>
                </tr>
            </table>
			{if $nbr != null}

			<table align="center" class="table table-sm table-responsive-sm table-striped table-bordered border-primary">
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