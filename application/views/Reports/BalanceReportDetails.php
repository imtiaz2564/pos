<div class="modal-body" style="max-height:400px; overflow-y:scroll;">
    <h1 id="header">Debitors/Creditors List</h1>
    <table id = "printTable" class="table table-report">
        <thead>
            <tr>
               <td>No</td>
               <td>ID(Supplier/Customer)</td> 
               <td>Business Name(Supplier/Customer)</td>
               <td>Outstanding Balance</td>
               <td>Thana</td>
            </tr>
        </thead>
        <? $i=1; $totalOutstanding = 0; $thana = [''=>'','0'=>'Kanaighat','1'=>'Companiganj','2'=>'Gowainghat','3'=>'Golabganj','4'=>'Zakiganj','5'=>'Jaintiapur','6'=>'Dakshin Surma','7'=>'Fenchuganj','8'=>'Balaganj','9'=>'Beanibazar','10'=>'Bishwanath','11'=>'Sylhet Sadar',
        '12'=>'Barlekja','13'=>'Kamalganj','14'=>'Kulaura','15'=>'Moulvibazar Sadar','16'=>'Rajnagar','17'=>'Sreemangal','18'=>'Juri','19'=>'Bishwamvarpur',
        '20'=>'Chhatak','21'=>'Derai','22'=>'Dakshin Sunamganj','23'=>'Dharampasha','24'=>'Dowarabazar','25'=>'Jagannathpur','26'=>'Jamalganj','27'=>'Sullah',
        '28'=>'Sunamganj Sadar','29'=>'Tahirpur','30'=>'Ajmiriganj','31'=>'Bahubal','32'=>'Baniyachong','33'=>'Chunarughat','34'=>'Habiganj Sadar','35'=>'Lakhai','36'=>'Madhabpur','37'=>'Nabiganj']; 
        foreach( $balancedata as $balancedata) { $totalOutstanding +=$balancedata['balance']; ?>
        <tr>
            <td><?=$i++?></td>
            <td><?=$balancedata['id']?></td>
            <td><?=$balancedata['name']?></td>
            <td><?=$balancedata['balance']?></td>
            <td><?=$thana[$balancedata['thana']]?></td>
        </tr>
        <? } ?>   
        <tr>
            <td></td>
            <td></td>
            <td>Total Outstanding Balance</td>
            <td><?=number_format($totalOutstanding,2)?></td>
            <td></td>
        </tr>   
    </table>    
    </div>
    <div class="row">
        <div class="col-xs-12 text-right">
        <button class="btn btn-primary" onclick="printDiv()">Print</button>
    </div>
    <script>
        $('#submitButton').hide();
        function printDiv() {
            var divToPrint=document.getElementById("printTable");
            var divToPrintHead=document.getElementById("header");
            var htmlToPrint = '' +
            '<style type="text/css">' +
                'table th, table td {' +
                'border: 1px solid black;' +
            '}' +
            'table {' +
                'border-collapse: collapse;' +
                'width: 100%;' +
            '}'+
            '</style>';
            htmlToPrint += divToPrint.outerHTML;
            newWin= window.open("");
            newWin.document.write(divToPrintHead.outerHTML);
            newWin.document.write(htmlToPrint);
            newWin.print();
            newWin.close();
        }
    </script>