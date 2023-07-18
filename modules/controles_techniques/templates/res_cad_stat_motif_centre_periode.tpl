<div class="col-4 table-responsive-xxl">
    <table align="center" class="table table-sm table-responsive-sm table-striped table-bordered">
        <thead class="titre2" style="font-size: xx-small;">
            <tr>
                <th>MOTIF OU TONNAGE</th>
                <th>PAYANTES</th>
    {*             <th>GRATUITES</th>
                <th>TOTAL</th> *}
            </tr>
        </thead>
        <tbody class="titre2" style="font-size: xx-small;">
            {* <tr style="font-size: xx-small;">
                <td class="bg-success" colspan="4">RECEPTION EN VUE IMMATRICULATION A MADAGASCAR</td>
            </tr> *}
            <tr style="font-size: xx-small;">
                <td class="bg-success" colspan="4">VEHICULE A MOTEUR (ISOLE)</td>
            </tr>
            {foreach $vhlamoteurs as $vhlamoteurs}
            <tr align="right" class="corps" style="background:{cycle array('#CCCCCC','#FFFFFF')}" style="font-size: xx-small;">
                <td align="left">{$vhlamoteurs['motif']}</td>
                <td>{$vhlamoteurs['parti']}</td>
                {* <td>{$vhlamoteurs['admin']}</td>
                <td>{$vhlamoteurs['total']}</td> *}
            </tr>
            {/foreach}
            <tr style="font-size: xx-small;">
                <td class="bg-info" colspan="4">REMORQUE ET SEMI-REMORQUE</td>
            </tr>
            {foreach $semiremorqs as $semiremorqs}
            <tr align="right" class="corps" style="background:{cycle array('#CCCCCC','#FFFFFF')}" style="font-size: xx-small;">
                <td align="left">{$semiremorqs['motif']}</td>
                <td>{$semiremorqs['parti']}</td>
                {* <td>{$semiremorqs['admin']}</td>
                <td>{$semiremorqs['total']}</td> *}
            </tr>
            {/foreach}
            {* {foreach $cyclom as $cyclom}
            <tr align="right" class="corps bg-info" style="background:{cycle array('#CCCCCC','#FFFFFF')}" style="font-size: xx-small;">
                <td align="left">{$cyclom['motif']}</td>
                <td>{$cyclom['parti']}</td>
                <td>{$cyclom['admin']}</td>
                <td>{$cyclom['total']}</td>
            </tr>
            {/foreach}
            {foreach $agrico as $agrico}
            <tr align="right" class="corps bg-info" style="background:{cycle array('#CCCCCC','#FFFFFF')}" style="font-size: xx-small;">
                <td align="left">{$agrico['motif']}</td>
                <td>{$agrico['parti']}</td>
                <td>{$agrico['admin']}</td>
                <td>{$agrico['total']}</td>
            </tr>
            {/foreach}
            <tr style="font-size: xx-small;">
                <td class="bg-success" colspan="4">VEHICULES DÉJÀ IMMATRICULES A MADAGASCAR</td>
            </tr>
            {foreach $rcoque as $rcoque}
            <tr align="right" class="corps" style="background:{cycle array('#CCCCCC','#FFFFFF')}" style="font-size: xx-small;">
                <td align="left">{$rcoque['motif']}</td>
                <td>{$rcoque['parti']}</td>
                <td>{$rcoque['admin']}</td>
                <td>{$rcoque['total']}</td>
            </tr>
            {/foreach} *}
        </tbody>
    </table>
</div>