<html>
    <head></head><body>
    <CENTER><TABLE BORDER="5" BORDERCOLOR="#808080" CELLSPACING="1" WIDTH="405">
            <CAPTION><FONT FACE="ARIAL" SIZE="5">YouAudit</FONT></CAPTION>
            
           <?php 
           foreach ($customer_data as $key => $value) {
           ?>
            <TR ALIGN="CENTER">
                <TD><?php echo strtoupper($key); ?></TD><TD><?php echo $value; ?></TD>
            </TR>
           <?php  } ?>
          
        </TABLE>
    </CENTER>
</body>
</html>