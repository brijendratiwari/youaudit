<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
    <head>
        <link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"".site_url()."/includes/css/report.css\" />
        <style>
            
.table_bg tbody tr:nth-child(2n) {
    background: none repeat scroll 0 0 rgb(239, 239, 239);
}
.table_bg thead tr th{
    background:  url("../../includes/img/th-bg.png") repeat-x scroll center bottom rgb(247, 247, 247);
}
        </style>
    </head>
    <body>
        <p align="left"><img width="200px" src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/includes/img/logo.png"></p>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100.0%;border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:0in 0in 0in 0in" class="MsoNormalTable">
            <tbody><tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes;height:.2in">
                    <td width="32%" valign="top" style="width:32.0%;background:rgb(102, 51, 102);mso-background-themecolor:accent4;padding:0in 0in 0in 0in;height:.2in">
                        <p class="MsoNormal">&nbsp;</p>
                    </td>
            <p class="MsoNormal">&nbsp;</p>
        </td>
        <td width="2%" valign="top" style="width:2.0%;padding:0in 0in 0in 0in; height:.2in">
            <p class="MsoNormal">&nbsp;</p>
        </td>
        <td width="32%" valign="top" style="width:32.0%;background:#999966;mso-background-themecolor: accent4;padding:0in 0in 0in 0in;height:.2in">
            <p class="MsoNormal">&nbsp;</p>
        </td>
        <td width="2%" valign="top" style="width:2.0%;padding:0in 0in 0in 0in;height:.2in">
            <p class="MsoNormal">&nbsp;</p>
        </td>
        <td width="32%" valign="top" style="width:32.0%;background:#666699;mso-background-themecolor:accent3;padding:0in 0in 0in 0in;height:.2in">
            <p class="MsoNormal">&nbsp;</p>
        </td>
    </tr>
</tbody></table><br>

<table cellpadding="5" cellspacing="0" border="1" width="100%" class="table_bg" >
    <?php echo $allData;?>
</table>
