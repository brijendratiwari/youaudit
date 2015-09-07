
<div class="heading">
    <h1>Fleet Management</h1>
    <div class="buttons">
    </div>           
</div>
<div id="content">
    <!--<ul id="breadcrumb">
        <li><a href="https://www.iworkaudit.com/iwa/">iSchool Audit</a></li>
        <li><a href="https://www.iworkaudit.com/iwa/items">Items</a></li><li>View item</li>
    </ul>-->
    
    <div class="box">
        <div class="heading">
            <h1>Vehicle Check History - <?php echo ($arrCompleteCheck['results'][0]['reg_no'] && $arrCompleteCheck['results'][0]['makemodel'] ? $arrCompleteCheck['results'][0]['makemodel'] . ' (' . $arrCompleteCheck['results'][0]['reg_no'] . ')' : ''); ?></h1>
        </div>
          
            <div class="box_content">
                <p style="margin-bottom: 5px;">This check was completed on <?=$arrCompleteCheck['results'][0]['date_time']?> <?php echo ($arrCompleteCheck['results'][0]['user_name'] ? 'by ' . $arrCompleteCheck['results'][0]['user_name'] : ''); ?></p>

                <div id="fourth_table" class="content_main">

                    <table class="list_table">
                        <thead>
                        <tr>
                            <th class="left">Check Name</th>
                            <th class="left">Result</th>
                            <th class="left">Note</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php


                        foreach ($arrCompleteCheck['results'] as $arrRecord)
                        {
                            ?>
                            <tr>
                                <td><?=$arrRecord['check_name']?></td>
                                <td><?=$arrRecord['result']?></td>
                                <td><?=$arrRecord['check_note']?></td>

                            </tr>
                        <?php
                        }
                        ?>

                        </tbody>

                    </table>
                </div>
                
            </div>
    </div>
</div>