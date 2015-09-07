<div class="box">  

    <div class="heading">
        <h1>Export Excel File</h1>
        <div class="buttons">
            <a class="button" onclick="$('#user_file').submit();">Upload</a>
        </div>
    </div> 
    <div class="box_content">
        <div class="tabs">
            <a href="#upload_file">Upload File</a>

        </div>
        <div id="upload_file">
            <?php echo form_open_multipart('users/upload_userfile/', array('id' => 'user_file')); ?>  

            <div class="form_row">
                <label for="upload_file">Example Excel File:</label>
                <a href="<?php echo site_url('/users/downloadExcelFomatFile'); ?>">Demo Excel File&nbsp;<?php echo "<img src=\"" . base_url('/brochure/img/excel.jpg') . "\"  />"; ?></a>
            </div>

            <div class="form_row"> 
                <div class="form_field">
                    <label for="upload_file">Upload File</label>
                    <input type="file" name="file" size="20" class="upload" />
                </div>
            </div> 

            </form>

        </div>
    </div></div>