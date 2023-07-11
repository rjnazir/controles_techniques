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