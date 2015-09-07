 <script>
     $(document).ready(function(){
         
     
       $(document).find('#location_id').change(function(){
      
           
           var site_id = this.value;
              $.getJSON("<?php echo base_url('items/getsitebylocation'); ?>"+'/'+ site_id, function(data) {
               
               if(data.results.length!=0){
            $('#site_id option[value="'+data.results[0].site_id+'"]').attr('selected', 'selected');
                }
                else{
                   $('#site_id option[value="0"]').attr('selected', 'selected');
        }
                });
    });
    });
 </script>   
<div class="box">
    	<div class="heading">
            <h1>Change links</h1>
            <div class="buttons">
                <a class="button" onclick="$('#change_links').submit();">Save</a>
            </div>
        </div>
        <div class="box_content">
            <div class="content_main">

        
    <p>Use this form to change an item's owner and/or location.</p>
    <p>The item, <?php
        echo "<strong>".$objItem->manufacturer." ".$objItem->model."</strong> (".$objItem->barcode.")"; ?>, is presently
        recorded as being owned by <?php echo $objItem->userfirstname." ".$objItem->userlastname; ?> and stored in
        <?php echo $objItem->locationname; ?></p>
    <?php echo form_open('items/changelinks/'.$objItem->itemid.'/', array('id' => 'change_links')); ?>

   