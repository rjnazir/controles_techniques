<head>
<link href="../../../www/jelix/design/jelix.css" rel="stylesheet" type="text/css" />
<link href="../../../jelix/design/jelix.css" rel="stylesheet" type="text/css" />
<link href="/jelix/design/jelix.css" rel="stylesheet" type="text/css" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<h1 align="center" class="apptitle">BILAN D'ACTIVITE TRIMESTRIEL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="{jurl 'controles_techniques~default:index'}"><input name="retour" type="button" value="&lt;&lt; Retour" /></a></h1>
<form action="" method="post" enctype="application/x-www-form-urlencoded" name="form">
<table width="100%">
    <tr>
        <td width="2%"></td>
      <td align="center">
    	<table>
    		<tr>
                <td class="corps">Choisir le trimestre <span class="obligatoire">(*)</span> :</td>
                <td></td>
                <td>
                    <select id="trimestre" name="trimestre" aria-placeholder="Choisir le trimestre">
                        <option value="0"></option>
                        <option value="1" {if $trimestre == 1}selected{/if}>Premier trimestre</option>
                        <option value="2" {if $trimestre == 2}selected{/if}>Deuxième trimestre</option>
                        <option value="3" {if $trimestre == 3}selected{/if}>Troisième trimestre</option>
                        <option value="4" {if $trimestre == 4}selected{/if}>Quatrième trimestre</option>
                    </select>
                </td>
                <td><input type="text" maxlength="4" width="4" name="annee" id="annee" placeholder="2001" value="{if($annee)}{$annee}{/if}" ></td>
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
    	{if !empty($res)}
    	{* <p align="center">
        	<a href="{jurl 'controles_techniques~crbilanquotidien_xls:index', array('annee'=>$annee)}" target="_blank" alt="Exporter en MS Excel" ><img src="../../../msexcel.png" width="3%" alt="Exporter en MS Excel" /></a>
        </p> *}
        <br/>
        <table align="center" border="1 red 0.1em">
            <thead class="titre2" style="font-size: xx-small;">
                <tr>
                    <th>ANA</th>
                    <th>SVA</th>
                    <th>NSB</th>
                    <th>FNR</th>
                    <th>ATR<th>
                    <th>MRA</th>
                    <th>FNA</th>
                    <th>IHO</th>
                    <th>TNA</th>
                    <th>AKA</th>
                    <th>FVE</th>
                    <th>MOG</th>
                    <th>BGL</th>
                    <th>DOR</th>
                    <th>ALS</th>
                    <th>IVT</th>
                    <th>TDD</th>
                    <th>ABE</th>
                    <th>TLR</th>
                    <th>MVA</th>
                    <th>TRO</th>
                    <th>ABA</th>
                    <th>MGA</th>
                    <th>ATH</th>
                </tr>
            </thead>
            <tbody>
                {foreach $res as $res}
                    <tr class="corps" style="background:{cycle array('#CCCCCC','#FFFFFF')}" style="font-size: xx-small;">
                        <td>{$res['usg_libelle']}</td>
                        <td>{$res['ana']}</td>
                        <td>{* {$res['SVA']} *}</td>
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