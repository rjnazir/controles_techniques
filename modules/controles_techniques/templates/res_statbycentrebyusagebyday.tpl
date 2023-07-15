<table align="center" class="table table-sm table-responsive table-striped table-bordered border-primary">
    <thead class="titre2" style="font-size: xx-small;">
        <tr>
            <th rowspan="2">USAGES EFFECTIFS</th>
            <th colspan="5">SUR SITE</th>
            <th colspan="5">ITINERANTE</th>
            <th colspan="5">A DOMICILE</th>
        </tr>
        <tr>
            <th>APTES</th>
            <th>INAPTES</th>
            <th>PAYANTES</th>
            <th>GRATUITES</th>
            <th>TOTAL</th>
            <th>APTES</th>
            <th>INAPTES</th>
            <th>PAYANTES</th>
            <th>GRATUITES</th>
            <th>TOTAL</th>
            <th>APTES</th>
            <th>INAPTES</th>
            <th>PAYANTES</th>
            <th>GRATUITES</th>
            <th>TOTAL</th>
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
                
                <td>{$result['ssitinapte']}</td>
                <td>{$result['ssitininapte']}</td>
                <td>{$result['ssitintotalp']}</td>
                <td>{$result['ssitinadmin']}</td>
                <td>{$result['ssitintotal']}</td>

                <td>{$result['adpartapte']}</td>
                <td>{$result['adpartinapte']}</td>
                <td>{$result['adtotalpremi']}</td>
                <td>{$result['adtotaladmin']}</td>
                <td>{$result['adtotalgener']}</td>
            </tr>
        {/foreach}
    </tbody>
</table>