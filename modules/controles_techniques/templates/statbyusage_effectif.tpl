<link href="../../../www/jelix/design/jelix.css" rel="stylesheet" type="text/css" />
<link href="../../../jelix/design/jelix.css" rel="stylesheet" type="text/css" />
<h1 align="center" class="apptitle">STATISTIQUE DE VISITES PAR USAGES EFFECTIFS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{jurl 'controles_techniques~default:index'}"><input name="retour" type="button" value="&lt;&lt; Retour" /></a></h1>
<form action="" name="formulaire">
<table align="center">
  <tr>
    <td width="2%"></td>
    <td>
		<table align="center">
			<tr>
			  <th class="corps">Choisir le mois :</th>
			  <td></td>
			  <td class="warning"><select name="mois">
				  <option value="01">Janvier</option>
				  <option value="02">Février</option>
				  <option value="03">Mars</option>
				  <option value="04">Avril</option>
				  <option value="05">Mai</option>
				  <option value="06">Juin</option>
				  <option value="07">Juillet</option>
				  <option value="08">Août</option>
				  <option value="09">Septembre</option>
				  <option value="10">Octobre</option>
				  <option value="11">Novembre</option>
				  <option value="12">Décembre</option>
				</select>
			  </td>
			  <td></td>
			  <th class="corps">Annee :</th>
			  <td></td>
			  <td>
				<select class="corps" name="annee">
					{for($j=($i-25); $j<=($i+25); $j++)}
					<option value="{$j}" {if($j==$i)} selected="selected" {/if}>{$j}</option>
					{/for}
				</select>
			  </td>
			  <td></td>
			  <th class="corps">Centre :</th>
			  <td></td>
			  <td class="">
				<select name="ct_centre_id">
					<option value="">Choisir...</option>
					{foreach $ct_centre as $ct_centre}
					<option value="{$ct_centre->id}">{$ct_centre->ctr_nom}</option>
					{/foreach}
				</select>
			  </td>
			  <td></td>
			  <td><input name="ok" type="submit" value="Afficher" /></td>
			</tr>
      	</table>
	</td>
    <td width="2%"></td>
  </tr>
</table>
{if $ok == true}
<table width="100%">
	<tr>
		<!--foreach $ct_usage as $ct_usage
		<th class="titre2">$ct_usage-usg_libelle</th>
		foreach-->
		{for($j=0;(count($ct_usage)/2);$j++)}
		<th class="titre2">{$ct_usage->usg_libelle}</th>
		{/for}
	</tr>
</table>
{/if}
</form>