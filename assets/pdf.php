<?php
    require "../app//Model/Caisse.php";
    require '../../../main.inc.php';
    require "../../../includes/tecnickcom/tcpdf/tcpdf.php";
    $caisse = new Caisse($db);

    $pdf = new TCPDF('A4');

    $pdf->setPrintHeader(false);

    $pdf->SetCreator('caisse@dolibarr');
    $pdf->SetTitle('Historique de transaction');

    $pdf->AddPage();

    $header='
    <img src="../img/af.png" width="40px"><br>
    <strong>solde: '. number_format($caisse->getEarning(),0,","," ") .'  Ar</strong><br>
    <strong>generé le : '.date("d-m-Y à H::i").'</strong>
    
    <h1>Historique de transactions</h1>
    ';
    
    $body = '
    <table class="table">
        <tr>
            <th style="width:50px;text-align:right;">Ref.</th>
            <th style="width:180px;">Motif</th>
            <th>Valeur</th>
            <th style="width:50px;">Type</th>
            <th style="width:80px;">Etat</th>
            <th>Date</th>
        </tr>';
    foreach($caisse->pdf() as $row){
        $body .= 
        '<tr>
            <td style="text-align:right;">'.$row->id.'</td>'.
            '<td style="text-align:center;">'.$row->motif.'</td>'.
            '<td style="text-align:right;">'.number_format($row->valeur,0,","," ").' Ar</td>'.
            '<td style="text-align:center;">'.$row->entrant .'</td>'.
            '<td style="text-align:center;">'.$row->existe.'</td>'.
            '<td style="text-align:center;">'.$row->date.'</td>
        </tr>';   
    }  
    $body.='</table>';

    $style=
    '<style>
        h1{text-align: center;font-weight:bold;text-decoration: underline 5px;}
        .table,td{border:1px solid #aaaaaa;padding:5px 2.5px;}
        th{background-color: #6c757d; color:rgb(248,249,250); text-align:center; border:1px solid #aa; font-weight: bold;}
        strong{text-align: rigt;font-weight:bold;}
    </style>';

    $html = $header.$body.$style;
    $pdf->writeHTML($html);

    $pdf->Output('Caisse@historique-'.time().'.pdf', 'I');
    ?>