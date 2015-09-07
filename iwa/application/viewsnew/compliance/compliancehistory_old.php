<?php // print "<pre>"; print_r($dueTests); print "</pre>"; ?>
<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<div class="heading">
    <h1>Compliance History</h1>
</div>
<div class="box_content">
    <div class="ver_tabs">
      <a class="" href="<?php  echo base_url('index.php/compliance');  ?>" class="active">Compliance Checks Due</a>
      <a class="active" href="#">Compliance History</a>
      <a class="" href="<?php  echo base_url('index.php/compliance/complianceslist');  ?>">List of Compliance Checks</a>
      <a class="" href="<?php  echo base_url('index.php/compliance/compliancesadmin');  ?>">Compliance Admin</a>
    </div>
    <div class="content_main">
        <div class="compliance_box_top">
            <div class="due_filter">
                <h3>HISTORY FOR</h3>
                <input class="next_due_check" name="" type="checkbox">&nbsp;<span>Month DEFAULT</span><br>
                <input class="next_due_check" name="" type="checkbox">&nbsp;<span>Quarter</span><br>
                <input class="next_due_check" name="" type="checkbox">&nbsp;<span>Six Month</span><br>
                <input class="next_due_check" name="" type="checkbox">&nbsp;<span>Year</span><br>
                <input class="next_due_check" name="" type="checkbox">&nbsp;<span>All</span>
            </div>
            <div class="due_table_contents">
                <!--<input id="goto_page" style="float: right;" type="number">-->
            </div>
            
<!--            <div id="compliance_snapshot">
                    <h1 style="width: 500px; margin: auto;">COMPLIANCE CHECKS DUE</h1>
                    <table class="list_table" style="width: 500px; margin: auto;">
                        <thead>
                            <th colspan="4"><h2>Compliance Summary</h2></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Compliance Checks Due in Next 7 Days</strong></td>
                                <td><?php print count($dueTests['dueMandatory']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Compliance Checks Due in Next 30 Days</strong></td>
                                <td><?php print count($dueTests['dueMandatory']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Compliance Checks Overdue</strong></td>
                                <td><?php print count($dueTests['dueMandatory']); ?></td>
                            </tr>
                        </tbody>
                    </table>
            </div>-->
            <div class="button_holder">
                <div id="export_csv">
                    <a class="button" href="<?php echo base_url('items/exportCSV/'.$this->uri->uri_string())?>">Export Results as CSV</a>
                    <a class="button" href="#">Export Results as PDF</a>
                </div>
                <div class="buttons">
                        <a href="#" class="button">Choose Columns</a>
                        <a href="#" class="button">Save View</a>
                </div>
            </div>
        </div>

        <div id="history" class="form_block">
            
            <table id="history_table" class="list_table">
                <thead>
                    <tr>
                        <th>+</th>
                        <th>QR Code</th>
                        <th>Manufacturer</th>
                        <th>Model</th>
                        <th>Category</th>
                        <th>Owner</th>
                        <th>Location</th>
                        <th>Site</th>
                        <th>Compliance Name</th>
                        <th>Logged By</th>
                        <th>Due Date</th>
                        <th>Complete Date</th>
                        <th>Result</th>
                        <th>No Of Task</th>
                        <th>Check Failed</th>
                        <th>Notes</th>
                        <th>Doc</th>
                    </tr>
                   
                </thead>
                <tfoot>
                    <th>+</th>
                    <th>QR Code</th>
                    <th>Manufacturer</th>
                    <th>Model</th>
                    <th>Category</th>
                    <th>Owner</th>
                    <th>Location</th>
                    <th>Site</th>
                    <th>Compliance Name</th>
                    <th>Logged By</th>
                </tfoot>
                <tbody>
                
                    <?php foreach($dueTests as $key => $value) {
//                            var_dump($value);

//                              $day_remain = '';
//                            if (is_int($test['due_ts'])) {
//
//                                $to = date('Y/m/d', $test['due_ts']);
//                                $from = date('Y/m/d', time());
//                                $d1 = (date_create($to));
//                                $d2 = (date_create($from));
//                                $diff = date_diff($d2, $d1);
//                                $day_remain = $diff->format('%R%a ') . '<br>';
//                            }
                     ?>
                    <tr>
                       <td>+</td>
                       <td><?php print $value['barcode']; ?> </td>
                       <td><?php print $value['manufacturer']; ?></td>
                       <td><?php print $value['model']; ?></td>
                       <td><?php print $value['name']; ?> </td>
                       <td><?php print $value['owner_name']; ?></td>
                       <td><?php print $value['location_name']; ?></td>
                       <td><?php print $value['site_name']; ?></td>
                       <td><?php print $value['compliance_name'] ?></td>
                       <td><?php print $value['test_person'] ?></td>
                       <td><?php (isset($value['test_date'])) ? print date('d/m/Y', strtotime($value['test_date'])) : print "Never Tested"; ?></td>
                       <td></td>
                       <td><?php ($value['result']) ? print 'Pass': print 'Fail'; ?></td>
                       <td><?php print $value['total_tasks']; ?></td>
                       <td></td>
                       <td></td>
                       <td><a class="getPdf_link" href="#"><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/includes/img/pdf.png" title="View Item" alt="View Item" /></a></td>
                    </tr>


                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<form id="genPdf_form" action="<?php echo site_url('compliance/generateHistoryPdf'); ?>" method="post">
    <input hidden="" name="allData">
</form>
      <script>
        $(document).ready(function() {
        var table = $('#history_table').DataTable( {
            "pagingType": "full_numbers"
          });
        $('.dataTables_length').appendTo('.due_table_contents');
//        $('.dataTables_paginate').appendTo('.due_table_contents');
        $('.dataTables_filter').remove();
        
//        $('#goto_page').on( 'change', function () {
//            table.page($(this).val()).draw( false );
//        } );
        $("#history_table tfoot th").each( function ( i ) {
          if(i>1){
            var select = $('<select><option value=""></option></select>')
                .appendTo( $(this).empty() )
                .on( 'change', function () {
                    table.column( i )
                        .search( '^'+$(this).val()+'$', true, false )
                        .draw();
                } );

            table.column( i ).data().unique().sort().each( function ( d, j ) {
                select.append( '<option value="'+d+'">'+d+'</option>' );
            } );
        }
        else
            $(this).html("");
        } );
        
        setTimeout(function(){$('#history_table').wrap('<div style="width:100%;height:100%;overflow-x:auto;"/>');},1000);
        
        $('body').on("click",'.getPdf_link',function(){
            var row = $(this).parent('td').parent('tr');
            var rowData = table.row( row ).data();
            $('#genPdf_form input').val();
            $('#genPdf_form input').val(rowData);
            $('#genPdf_form').submit();
        });
    });
    
    </script>