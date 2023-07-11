<table align="center" class="table table-sm table-responsive-sm table-striped table-bordered border-primary">
    <thead class="titre2" style="font-size: xx-small;">
        <tr>
            <th rowspan="3">MOTIFS</th>
            <th colspan="3">VHL IMM A MSCR</th>
            <th colspan="11">VHL IMPORT ET AUTRES</th>
        </tr>
        <tr>
            <th rowspan="2">PARTICULIER</th>
            <th rowspan="2">ADM</th>
            <th rowspan="2">TOTAL</th>
            <th colspan="2">PTAC < 3.5T</th>
            <th colspan="2">3.5T ≤ PTAC < 7T</th>
            <th colspan="2">7T ≤ PTAC < 10T</th>
            <th colspan="2">10T ≤ PTAC < 19T</th>
            <th colspan="2">19T ≤ PTAC < 26T</th>
            <th rowspan="2">TOTAL</th>
        </tr>
        <tr>
            <th>PARTICULIER</th>
            <th>ADM</th>
            <th>PARTICULIER</th>
            <th>ADM</th>
            <th>PARTICULIER</th>
            <th>ADM</th>
            <th>PARTICULIER</th>
            <th>ADM</th>
            <th>PARTICULIER</th>
            <th>ADM</th>
        </tr>
    </thead>
    <tbody>
        {foreach $result as $result}
        <tr align="right" class="corps" style="background:{cycle array('#CCCCCC','#FFFFFF')}" style="font-size: xx-small;">
            <td align="left">{$result['motif']}</td>
            <td>{$result['rtpartvhlimmmga']}</td>
            <td>{$result['rtadmnvhlimmmga']}</td>
            <td>{$result['rtttalvhlimmmga']}</td>
            <td>{$result['rtpevimpinf3500']}</td>
            <td>{$result['rtadvimpinf3500']}</td>
            <td>{$result['rtpevimpinf7000']}</td>
            <td>{$result['rtadvimpinf7000']}</td>
            <td>{$result['rtpevimpinf10000']}</td>
            <td>{$result['rtadvimpinf10000']}</td>
            <td>{$result['rtpevimpinf19000']}</td>
            <td>{$result['rtadvimpinf19000']}</td>
            <td>{$result['rtpevimpinf26000']}</td>
            <td>{$result['rtadvimpinf26000']}</td>
            <td>{$result['rtttalvhlimport']}</td>
        </tr>
        {/foreach}
    </tbody>
</table>