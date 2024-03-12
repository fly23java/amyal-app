<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,-scale=1.0">
    <style>

        table.redTable {
            /* border: 2px solid #000000; */
            width: 98%;
            height: 200%;
            text-align: right;
        }
        /* table.redTable td, table.redTable th {
        border: 1px solid #AAAAAA;
        } */
        table.redTable thead {
            background: #000;
            text-align: right;
        }
        table.redTable thead th {
            font-size: 19px;
            font-weight: bold;
            color: #FFFFFF;
            text-align: right;
        }
        table.redTable tfoot {
            font-weight: bold;
        }

        @media print {
            background: #eee !important; /* <= DISABLES backgrounds */
        }
    </style>
    
    
</head>
<body>
 
    <!-- main information-->
    <table class="redTable">
        <thead>
            <tr>
                <th>head1</th>
                <th>head2</th>
                <th>head3</th>
                <th>head4</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>cell1_1</td>
                <td>cell2_1</td>
                <td>cell3_1</td>
                <td>cell4_1</td>
            </tr>
            <tr>
                <td>cell1_2</td>
                <td>cell2_2</td>
                <td>cell3_2</td>
                <td>cell4_2</td>
            </tr>
            <tr>
                <td>cell1_3</td>
                <td>cell2_3</td>
                <td>cell3_3</td>
                <td>cell4_3</td>
            </tr>
            <tr>
                <td>cell1_4</td>
                <td>cell2_4</td>
                <td>cell3_4</td>
                <td>cell4_4</td>
            </tr>
            <tr>
                <td>cell1_5</td>
                <td>cell2_5</td>
                <td>cell3_5</td>
                <td>cell4_5</td>
            </tr>
        </tbody>
    </table>

    <table class="redTable">
        <thead>
            <tr>
                <th>head1</th>
                <th>head2</th>
                <th>head3</th>
                <th>head4</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>cell1_1</td>
                <td>cell2_1</td>
                <td>cell3_1</td>
                <td>cell4_1</td>
            </tr>
            <tr>
                <td>cell1_2</td>
                <td>cell2_2</td>
                <td>cell3_2</td>
                <td>cell4_2</td>
            </tr>
            <tr>
                <td>cell1_3</td>
                <td>cell2_3</td>
                <td>cell3_3</td>
                <td>cell4_3</td>
            </tr>
        </tbody>
    </table>


    <table class="redTable">
        <thead>
            <tr>
                <th>head1</th>
                <th>head2</th>
                <th>head3</th>
                <th>head4</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td>foot1</td>
                <td>foot2</td>
                <td>foot3</td>
                <td>foot4</td>
            </tr>
        </tfoot>
        <tbody>
            <tr>
                <td>cell1_1</td>
                <td>cell2_1</td>
                <td>cell3_1</td>
                <td>cell4_1</td>
            </tr>
            <tr>
                <td>cell1_2</td>
                <td>cell2_2</td>
                <td>cell3_2</td>
                <td>cell4_2</td>
            </tr>
            <tr>
                <td>cell1_3</td>
                <td>cell2_3</td>
                <td>cell3_3</td>
                <td>cell4_3</td>
            </tr>
            <tr>
                <td>cell1_4</td>
                <td>cell2_4</td>
                <td>cell3_4</td>
                <td>cell4_4</td>
            </tr>
            <tr>
                <td>cell1_5</td>
                <td>cell2_5</td>
                <td>cell3_5</td>
                <td>cell4_5</td>
            </tr>
            <tr>
                <td>cell1_6</td>
                <td>cell2_6</td>
                <td>cell3_6</td>
                <td>cell4_6</td>
            </tr>
        </tbody>
    </table>

    <table class="redTable">
        <thead>
            <tr>
                <th>head1</th>
                <th>head2</th>
                <th>head3</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td>foot1</td>
                <td>foot2</td>
                <td>foot3</td>
            </tr>
        </tfoot>
        <tbody>
            <tr>
                <td>cell1_1</td>
                <td>cell2_1</td>
                <td>cell3_1</td>
            </tr>
            <tr>
                <td>cell1_2</td>
                <td>cell2_2</td>
                <td>cell3_2</td>
            </tr>
        </tbody>
    </table>

    <table class="redTable">
        <thead>
            <tr>
                <th>head1</th>
                <th>head2</th>
                <th>head3</th>
                <th>head4</th>
                <th>head5</th>
                <th>head6</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td>foot1</td>
                <td>foot2</td>
                <td>foot3</td>
                <td>foot4</td>
                <td>foot5</td>
                <td>foot6</td>
            </tr>
        </tfoot>
        <tbody>
            <tr>
                <td>cell1_1</td>
                <td>cell2_1</td>
                <td>cell3_1</td>
                <td>cell4_1</td>
                <td>cell5_1</td>
                <td>cell6_1</td>
            </tr>
            <tr>
                <td>cell1_2</td>
                <td>cell2_2</td>
                <td>cell3_2</td>
                <td>cell4_2</td>
                <td>cell5_2</td>
                <td>cell6_2</td>
            </tr>
        </tbody>
    </table>
</body>
</html>