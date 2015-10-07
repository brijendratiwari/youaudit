<link href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/youaudit/includes/css/sub_style.css" rel="stylesheet" type="text/css" />
<script>
    $(document).ready(function()
    {
        $("#add_profile_form").validate({
            rules: {
                profile_name: "required"
            },
            messages: {
                profile_name: "Please Enter Profile Name"

            }
        });
        $("#edit_profile_form").validate({
            rules: {
                edit_profile_name: "required"
            },
            messages: {
                edit_profile_name: "Please Enter Profile Name"

            }
        });
        // script for edit user
//        $("body").on("click", ".edit", function() {
//
//            $("#edit_form input").attr('disabled', false);
//            $("#edit_form select").attr('disabled', false);
//            $("#edit_form textarea").attr('disabled', false);
//            var profilename = $(this).attr("data_profilename");
//            var adminuser_id = $(this).attr("data_adminuser_id");
//            $("#adminuser_id_1").attr("value", adminuser_id);
//        });
        var max_fields = 100; //maximum input boxes allowed
        var wrapper = $("#profilebody"); //Fields wrapper
        var add_button = $(".add_field_button"); //Add button ID

        var x = 0; //initlal text box count

        $(add_button).click(function(e) { //on add input button click
            e.preventDefault();
            if (x < max_fields) { //max input box allowed
                x++; //text box increment

                $(wrapper).append('<tr id="' + x + '"><input type="hidden" name="profile" value="' + x + '"><td><input id="owners" name="owner_name' + x + '" class="form-control" size="6"></td><td><input id="categories" name="category_name' + x + '" class="form-control" size="6"></td><td><input id="manus" name="manu_name' + x + '" class="form-control" size="6"></td><td><input id="manufactures" name="manufacturer_name' + x + '" class="form-control" size="6"></td><td><input name="field_name' + x + '" class="form-control" size="12"></td><td><select id="field_type' + x + '" name="field_type' + x + '" class="form-control" onchange="getFields(' + x + ')"><option value="text_type">Text</option><option value="pick_list_type">Pick List</option><option value="value_type">$ Value</option><option value="date_type">Date</option><option value="num">Number</option></select></td></tr>'); //add input box
            }
            $("#remove").css("display", "block");
        });
        $(".remove_field").on("click", function(e) { //user click on remove text 

            e.preventDefault();
            var removeid = $('#profilebody tr:last').attr('id');
            if ($('#profilebody tr:first').attr('id') == removeid)
            {
                $("#remove").css("display", "none");
            }
            $('#profilebody tr[id="' + removeid + '"]').remove();
            x--;
        });
        // get profile
        $(document).ajaxStart(function() {
            $("#loader").css("display", "block");
        });
        $(document).ajaxComplete(function() {
            $("#loader").css("display", "none");
        });

        $("body").on("click", ".edit", function() {

            $("#edit_form input").attr('disabled', false);
            $("#edit_form select").attr('disabled', false);
            $("#edit_form textarea").attr('disabled', false);
            var profilename = $(this).attr("data_profilename");
            var adminuser_id = $(this).attr("data_adminuser_id");
            $("#adminuser_id_1").attr("value", adminuser_id);
            $("#owner_list").empty();
            $("#categories_list").empty();
            $("#item_list").empty();
            $("#manufacturer_list").empty();
            $("#name_list").empty();
            $("#type_list").empty();
            $('#profile_limit').attr('disabled', false);
            $("#save_button").css("display", "none");
            var base_url_str = $("#base_url").val();

            $("#edit_profile_name").attr("value", profilename);
            $("#profileid").val(adminuser_id);
            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/master_admins/viewProfile/" + adminuser_id,
                success: function(data) {

                    var profiledata = $.parseJSON(data);
                    var ownerstr = (profiledata.owner);
                    var category = (profiledata.category);
                    var manu = (profiledata.manu);
                    var manufacturer = profiledata.manufacturer;
                    var field_name = profiledata.cus_name;
                    var field_type = profiledata.cus_type;
//                    alert(field_name);
//                    alert(field_type);
                    // To Show Owner

                    if (ownerstr) {
                        var owner_obj = new Array();
                        var ownerArray = ownerstr.toString().split(",");
                        $.each(ownerArray, function(index, value) {
                            if (value != 'N/A' && value != '0') {
                                owner_obj.push(value);
                                owner_obj.sort();
                            }
                        });
                        var owner_arr = owner_obj.join('\n');
                        if (owner_arr) {
                            owner_str = '<textarea name="owner[]" class="form-control item" rows="30" cols="10">' + owner_arr + '</textarea>';
                            $("#owner_list").append(owner_str);
                        }
                    }
                    else
                    {
                        owner_str = '<textarea name="owner[]" class="form-control item" rows="30" cols="10"></textarea>';
                        $("#owner_list").append(owner_str);
                    }

                    // To Show Category
                    if (category) {
                        var category_obj = new Array();
                        var categoryArray = category.toString().split(",");

                        $.each(categoryArray, function(index, value) {
                            if (value != 'N/A' && value != '0') {
                                category_obj.push(value);
                                category_obj.sort();
                            }
                        });
                        var category_arr = category_obj.join('\n');
                        if (category_arr) {
                            category_str = '<textarea name="category[]" class="form-control item" rows="30" cols="10">' + category_arr + '</textarea>';
                            $("#categories_list").append(category_str);
                        }
                    }
                    else
                    {
                        category_str = '<textarea name="category[]" class="form-control item" rows="30" cols="10"></textarea>';
                        $("#categories_list").append(category_str);
                    }

                    // To Show Item List
                    if (manu) {
                        var item_obj = new Array();
                        var itemArray = manu.toString().split(",");

                        $.each(itemArray, function(index, value) {
                            if (value != 'N/A' && value != '0') {
                                item_obj.push(value);
                                item_obj.sort();
                            }
                        });
                        var item_arr = item_obj.join('\n');
                        if (item_arr) {
                            item_str = '<textarea name="item[]" class="form-control item" rows="30" cols="10">' + item_arr + '</textarea>';
                            $("#item_list").append(item_str);
                        }
                    }
                    else
                    {
                        item_str = '<textarea name="item[]" class="form-control item" rows="30" cols="10"></textarea>';
                        $("#item_list").append(item_str);
                    }

                    // To Show Manufacturer List
                    if (manufacturer) {
                        var manufacturer_obj = new Array();
                        var manufacturerArray = manufacturer.toString().split(",");

                        $.each(manufacturerArray, function(index, value) {
                            if (value != 'N/A' && value != '0') {
                                manufacturer_obj.push(value);
                                manufacturer_obj.sort();
                            }
                        });
                        var manufacturer_arr = manufacturer_obj.join('\n');
                        if (manufacturer_arr) {
                            manufacturer_str = '<textarea name="manufacturer[]" class="form-control item" rows="30" cols="10">' + manufacturer_arr + '</textarea>';
                            $("#manufacturer_list").append(manufacturer_str);
                        }
                    }
                    else
                    {
                        manufacturer_str = '<textarea name="manufacturer[]" class="form-control item" rows="30" cols="10"></textarea>';
                        $("#manufacturer_list").append(manufacturer_str);
                    }

                    // Show Custom Field Name 
                    if (field_name) {
                        var fieldname_obj = new Array();
                        var fieldnameobj = new Array();
                        var fieldnameArray = field_name.toString().split(",");

                        $.each(fieldnameArray, function(index, value) {
                            if (value != 'N/A' && value != '0') {
                                fieldname_obj.push(value);
                            }
                        });
                        var fieldnames = fieldname_obj.join(',');
                        var fieldnamesArray = fieldnames.toString().split(",");
                        $.each(fieldnamesArray, function(index, value) {
                            fieldnameobj.push(value);
                        });

                        // Show Custom Field Value
                        var fieldtype_obj = new Array();
                        var fieldtypeArray = field_type.toString().split(",");

                        $.each(fieldtypeArray, function(index, value) {
                            fieldtype_obj.push(value);
                        });

                        if (fieldnameobj.length > 0) {
                            for (var j = 0; j < fieldnameobj.length; j++) {

                                if (fieldnameobj[j] != null && fieldnameobj[j] != '0' && fieldnameobj[j] != '') {
                                    str_name = '<div class="list-group-item"><input value="' + fieldnameobj[j] + '"  name="names[]" class="form-control item" size="10"></div>'
                                    $("#name_list").append(str_name);
                                }
                                else
                                {
                                    $("#name_list input").parent('div').remove();
                                }
                                str_types = '<div class="list-group-item"><select class="form-control" name="types[]" id="profileedit' + j + '" onchange="custom_txt(' + j + ')"><option value="">Select Type</option><option value="text_type">Text</option>\n\
                    <option value="pick_list_type">Pick List</option><option value="value_type">$ value</option><option value="date_type">Date</option><option value="num">Number</option></select></div>'

                                $("#type_list").append(str_types);
                                if (fieldtype_obj[j] != null && fieldtype_obj[j] != '0' && fieldtype_obj[j] != '') {
                                    $('#type_list #profileedit' + j + ' option[value="' + fieldtype_obj[j] + '"]').attr('selected', 'selected');
                                }
                                else
                                {
//                            $('#type_list #profileedit' + j + ' option[value=""]').attr('selected', 'selected');
                                    $('#type_list #profileedit' + j).parent('div').remove();
                                }
                            }
                        }
                    }
                }

            });
        });

        $("body").on("change", "#profilelimit", function() {

            var limit = $('#profilelimit option:selected').val();
            var max_limit = 100;
            if (limit <= max_limit)
            {
                limit = limit;
            }
            else
            {
                limit = max_limit;
            }

            $("#owner_list").empty();
            $("#categories_list").empty();
            $("#item_list").empty();
            $("#manufacturer_list").empty();
            $("#name_list").empty();
            $("#type_list").empty();
            $('#profile_limit').attr('disabled', false);
            var base_url_str = $("#base_url").val();

            var profile_id = '<?php echo $this->uri->segment('3'); ?>';
            var profile_name = $(this).attr("profile_name");

            $("#edit_profile_name").attr("value", profile_name);
            $("#profileid").val(profile_id);
            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/master_admins/viewProfile/" + profile_id,
                success: function(data) {

                    var profiledata = $.parseJSON(data);
                    var ownerstr = (profiledata.owner);
                    var category = (profiledata.category);
                    var manu = (profiledata.manu);
                    var manufacturer = profiledata.manufacturer;
                    var field_name = profiledata.cus_name;
                    var field_type = profiledata.cus_type;
//                    alert(field_name);
//                    alert(field_type);
                    // To Show Owner

                    if (ownerstr) {
                        var owner_obj = new Array();
                        var ownerArray = ownerstr.toString().split(",");
                        if (ownerArray.length < limit)
                        {
                            ownerArray.length = ownerArray.length;
                        }
                        else
                        {
                            ownerArray.length = limit;
                        }
                        $.each(ownerArray, function(index, value) {
                            if (value != 'N/A' && value != '0') {
                                owner_obj.push(value);
                            }
                        });
                        var owner_arr = owner_obj.join('\n');
                        if (owner_arr) {
                            owner_str = '<textarea disabled name="owner[]" class="form-control item" rows="30" cols="10">' + owner_arr + '</textarea>';
                            $("#owner_list").append(owner_str);
                        }
                    }
                    else
                    {
                        owner_str = '<textarea disabled name="owner[]" class="form-control item" rows="30" cols="10"></textarea>';
                        $("#owner_list").append(owner_str);
                    }

                    // To Show Category
                    if (category) {
                        var category_obj = new Array();
                        var categoryArray = category.toString().split(",");
                        if (categoryArray.length < limit)
                        {
                            categoryArray.length = categoryArray.length;
                        }
                        else
                        {
                            categoryArray.length = limit;
                        }
                        $.each(categoryArray, function(index, value) {
                            if (value != 'N/A' && value != '0') {
                                category_obj.push(value);
                            }
                        });
                        var category_arr = category_obj.join('\n');
                        if (category_arr) {
                            category_str = '<textarea disabled name="category[]" class="form-control item" rows="30" cols="10">' + category_arr + '</textarea>';
                            $("#categories_list").append(category_str);
                        }
                    }
                    else
                    {
                        category_str = '<textarea disabled name="category[]" class="form-control item" rows="30" cols="10"></textarea>';
                        $("#categories_list").append(category_str);
                    }

                    // To Show Item List
                    if (manu) {
                        var item_obj = new Array();
                        var itemArray = manu.toString().split(",");
                        if (itemArray.length < limit)
                        {
                            itemArray.length = itemArray.length;
                        }
                        else
                        {
                            itemArray.length = limit;
                        }
                        $.each(itemArray, function(index, value) {
                            if (value != 'N/A' && value != '0') {
                                item_obj.push(value);
                            }
                        });
                        var item_arr = item_obj.join('\n');
                        if (item_arr) {
                            item_str = '<textarea disabled name="item[]" class="form-control item" rows="30" cols="10">' + item_arr + '</textarea>';
                            $("#item_list").append(item_str);
                        }
                    }
                    else
                    {
                        item_str = '<textarea disabled name="item[]" class="form-control item" rows="30" cols="10"></textarea>';
                        $("#item_list").append(item_str);
                    }

                    // To Show Manufacturer List
                    if (manufacturer) {
                        var manufacturer_obj = new Array();
                        var manufacturerArray = manufacturer.toString().split(",");
                        if (manufacturerArray.length < limit)
                        {
                            manufacturerArray.length = manufacturerArray.length;
                        }
                        else
                        {
                            manufacturerArray.length = limit;
                        }
                        $.each(manufacturerArray, function(index, value) {
                            if (value != 'N/A' && value != '0') {
                                manufacturer_obj.push(value);
                            }
                        });
                        var manufacturer_arr = manufacturer_obj.join('\n');
                        if (manufacturer_arr) {
                            manufacturer_str = '<textarea disabled name="manufacturer[]" class="form-control item" rows="30" cols="10">' + manufacturer_arr + '</textarea>';
                            $("#manufacturer_list").append(manufacturer_str);
                        }
                    }
                    else
                    {
                        manufacturer_str = '<textarea disabled name="manufacturer[]" class="form-control item" rows="30" cols="10"></textarea>';
                        $("#manufacturer_list").append(manufacturer_str);
                    }

                    // Show Custom Field Name 
                    if (field_name) {
                        var fieldname_obj = new Array();
                        var fieldnameobj = new Array();
                        var fieldnameArray = field_name.toString().split(",");

                        $.each(fieldnameArray, function(index, value) {
                            if (value != 'N/A' && value != '0') {
                                fieldname_obj.push(value);
                            }
                        });
                        var fieldnames = fieldname_obj.join(',');
                        var fieldnamesArray = fieldnames.toString().split(",");
                        if (fieldnamesArray.length < limit)
                        {
                            fieldnamesArray.length = fieldnamesArray.length;
                        }
                        else
                        {
                            fieldnamesArray.length = limit;
                        }

                        $.each(fieldnamesArray, function(index, value) {
                            fieldnameobj.push(value);
                        });

                        // Show Custom Field Value
                        var fieldtype_obj = new Array();
                        var fieldtypeArray = field_type.toString().split(",");

                        $.each(fieldtypeArray, function(index, value) {
                            fieldtype_obj.push(value);
                        });

                        if (fieldnameobj.length > 0) {
                            for (var j = 0; j < fieldnameobj.length; j++) {

                                if (fieldnameobj[j] != null && fieldnameobj[j] != '0' && fieldnameobj[j] != '') {
                                    str_name = '<div class="list-group-item"><input disabled value="' + fieldnameobj[j] + '"  name="names[]" class="form-control item" rows="30" size="10"></div>'
                                    $("#name_list").append(str_name);
                                }
                                else
                                {
                                    $("#name_list input").parent('div').remove();
                                }
                                str_types = '<div class="list-group-item"><select class="form-control" name="types[]" id="profileedit' + j + '" onchange="custom_txt(' + j + ')" disabled><option value="">Select Type</option><option value="text_type">Text</option>\n\
                    <option value="pick_list_type">Pick List</option><option value="value_type">$ value</option><option value="date_type">Date</option><option value="num">Number</option></select></div>'

                                $("#type_list").append(str_types);
                                if (fieldtype_obj[j] != null && fieldtype_obj[j] != '0' && fieldtype_obj[j] != '') {
                                    $('#type_list #profileedit' + j + ' option[value="' + fieldtype_obj[j] + '"]').attr('selected', 'selected');
                                }
                                else
                                {
                                    $('#type_list #profileedit' + j).parent('div').remove();
                                }
                            }
                        }
                    }
                }

            });
        });

        $("body").on("click", ".view_profile", function() {

            $("#owner_list").empty();
            $("#categories_list").empty();
            $("#item_list").empty();
            $("#manufacturer_list").empty();
            $("#name_list").empty();
            $("#type_list").empty();
            $('#profile_limit').attr('disabled', false);
            $("#save_button").css("display", "none");
            var base_url_str = $("#base_url").val();

            var profile_id = $(this).attr("profile_id");
            var profile_name = $(this).attr("profile_name");

            $("#edit_profile_name").attr("value", profile_name);
            $("#profileid").val(profile_id);
            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/master_admins/viewProfile/" + profile_id,
                success: function(data) {

                    var profiledata = $.parseJSON(data);
                    var ownerstr = (profiledata.owner);
                    var category = (profiledata.category);
                    var manu = (profiledata.manu);
                    var manufacturer = profiledata.manufacturer;
                    var field_name = profiledata.cus_name;
                    var field_type = profiledata.cus_type;
//                    alert(field_name);
//                    alert(field_type);
                    // To Show Owner

                    if (ownerstr) {
                        var owner_obj = new Array();
                        var ownerArray = ownerstr.toString().split(",");
                        $.each(ownerArray, function(index, value) {
                            if (value != 'N/A' && value != '0') {
                                owner_obj.push(value);
                                owner_obj.sort();
                            }
                        });
                        var owner_arr = owner_obj.join('\n');
                        if (owner_arr) {
                            owner_str = '<textarea disabled name="owner[]" class="form-control item" rows="30" cols="10">' + owner_arr + '</textarea>';
                            $("#owner_list").append(owner_str);
                        }
                    }
                    else
                    {
                        owner_str = '<textarea disabled name="owner[]" class="form-control item" rows="30" cols="10"></textarea>';
                        $("#owner_list").append(owner_str);
                    }

                    // To Show Category
                    if (category) {
                        var category_obj = new Array();
                        var categoryArray = category.toString().split(",");

                        $.each(categoryArray, function(index, value) {
                            if (value != 'N/A' && value != '0') {
                                category_obj.push(value);
                                category_obj.sort();
                            }
                        });
                        var category_arr = category_obj.join('\n');
                        if (category_arr) {
                            category_str = '<textarea disabled name="category[]" class="form-control item" rows="30" cols="10">' + category_arr + '</textarea>';
                            $("#categories_list").append(category_str);
                        }
                    }
                    else
                    {
                        category_str = '<textarea disabled name="category[]" class="form-control item" rows="30" cols="10"></textarea>';
                        $("#categories_list").append(category_str);
                    }

                    // To Show Item List
                    if (manu) {
                        var item_obj = new Array();
                        var itemArray = manu.toString().split(",");

                        $.each(itemArray, function(index, value) {
                            if (value != 'N/A' && value != '0') {
                                item_obj.push(value);
                                item_obj.sort();
                            }
                        });
                        var item_arr = item_obj.join('\n');
                        if (item_arr) {
                            item_str = '<textarea disabled name="item[]" class="form-control item" rows="30" cols="10">' + item_arr + '</textarea>';
                            $("#item_list").append(item_str);
                        }
                    }
                    else
                    {
                        item_str = '<textarea disabled name="item[]" class="form-control item" rows="30" cols="10"></textarea>';
                        $("#item_list").append(item_str);
                    }

                    // To Show Manufacturer List
                    if (manufacturer) {
                        var manufacturer_obj = new Array();
                        var manufacturerArray = manufacturer.toString().split(",");

                        $.each(manufacturerArray, function(index, value) {
                            if (value != 'N/A' && value != '0') {
                                manufacturer_obj.push(value);
                                manufacturer_obj.sort();
                            }
                        });
                        var manufacturer_arr = manufacturer_obj.join('\n');
                        if (manufacturer_arr) {
                            manufacturer_str = '<textarea disabled name="manufacturer[]" class="form-control item" rows="30" cols="10">' + manufacturer_arr + '</textarea>';
                            $("#manufacturer_list").append(manufacturer_str);
                        }
                    }
                    else
                    {
                        manufacturer_str = '<textarea disabled name="manufacturer[]" class="form-control item" rows="30" cols="10"></textarea>';
                        $("#manufacturer_list").append(manufacturer_str);
                    }

                    // Show Custom Field Name 
                    if (field_name) {
                        var fieldname_obj = new Array();
                        var fieldnameobj = new Array();
                        var fieldnameArray = field_name.toString().split(",");

                        $.each(fieldnameArray, function(index, value) {
                            if (value != 'N/A' && value != '0') {
                                fieldname_obj.push(value);
                            }
                        });
                        var fieldnames = fieldname_obj.join(',');
                        var fieldnamesArray = fieldnames.toString().split(",");
                        $.each(fieldnamesArray, function(index, value) {
                            fieldnameobj.push(value);
                        });

                        // Show Custom Field Value
                        var fieldtype_obj = new Array();
                        var fieldtypeArray = field_type.toString().split(",");

                        $.each(fieldtypeArray, function(index, value) {
                            fieldtype_obj.push(value);
                        });

                        if (fieldnameobj.length > 0) {
                            for (var j = 0; j < fieldnameobj.length; j++) {

                                if (fieldnameobj[j] != null && fieldnameobj[j] != '0' && fieldnameobj[j] != '') {
                                    str_name = '<div class="list-group-item"><input disabled value="' + fieldnameobj[j] + '"  name="names[]" class="form-control item" size="10"></div>'
                                    $("#name_list").append(str_name);
                                }
                                else
                                {
                                    $("#name_list input").parent('div').remove();
                                }
                                str_types = '<div class="list-group-item"><select class="form-control" name="types[]" id="profileedit' + j + '" onchange="custom_txt(' + j + ')" disabled><option value="">Select Type</option><option value="text_type">Text</option>\n\
                    <option value="pick_list_type">Pick List</option><option value="value_type">$ value</option><option value="date_type">Date</option><option value="num">Number</option></select></div>'

                                $("#type_list").append(str_types);
                                if (fieldtype_obj[j] != null && fieldtype_obj[j] != '0' && fieldtype_obj[j] != '') {
                                    $('#type_list #profileedit' + j + ' option[value="' + fieldtype_obj[j] + '"]').attr('selected', 'selected');
                                }
                                else
                                {
//                            $('#type_list #profileedit' + j + ' option[value=""]').attr('selected', 'selected');
                                    $('#type_list #profileedit' + j).parent('div').remove();
                                }
                            }
                        }
                    }
                }

            });
        });

        $("#profile_name").on("blur", function() {

            var profile_name = $("#profile_name").val();
            var account_id = $("#acc_id").val();
            var base_url_str = $("#base_url").val();
            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/master_admins/checkProfile/" + account_id,
                data: {
                    'profile_name': profile_name,
                },
                success: function(msg) {

                    // we need to check if the value is the same
                    if (msg == '') {
                        //Receiving the result of search here
                        $("#save_button").addClass('hide');
                        $("#username_error").removeClass("hide");
                    } else {
                        $("#save_button").removeClass("hide");
                        $("#username_error").addClass("hide");
                    }
                }

            });
        });

        $("#edit_profile_name").on("blur", function() {

            var profile_name = $("#edit_profile_name").val();
            var account_id = $("#acc_id").val();
            var base_url_str = $("#base_url").val();
            $.ajax({
                type: "POST",
                url: base_url_str + "youaudit/master_admins/checkProfile/" + account_id,
                data: {
                    'profile_name': profile_name,
                },
                success: function(msg) {
                    // we need to check if the value is the same
                    if (msg == '') {
                        //Receiving the result of search here
                        $("#update_btn").attr("disabled", true);
                        $("#profilename_error").removeClass("hide");
                    } else {
                        $("#update_btn").removeAttr("disabled");
                        $("#profilename_error").addClass("hide");
                    }
                }

            });
        });

        var y = 1;
        var z = 1;
        var m = 1;
        var n = 1;
        var r = 1;
        var c = 1;
        var s = -1;
        $('.add-owner').click(function() {
            if (y < max_fields) { //max box allowed
                y++;
                var owner = '<div class="multi-field"><input type="hidden" name="owner" value="' + y + '"><input id="owners" name="owner_name' + y + '" class="form-control prof pull-left" size="9"><button class="add-owner btn-xs btn-primary addp pull-right removeowner" type="button"><i class="fa fa-minus"></i></button></div>';
                $('#profile_owner').append(owner);
            }
        });

        $('.add-category').click(function(e) {
            if (z < max_fields) { //max box allowed
                z++;
                var category = '<div class="multi-field"><input type="hidden" name="category" value="' + z + '"><input id="categories" name="category_name' + z + '" class="form-control prof pull-left" size="9"><button class="add-owner btn-xs btn-primary addp pull-right removecat" type="button"><i class="fa fa-minus"></i></button></div>';
                $('#profile_category').append(category);
            }
        });

        $('.add-manu').click(function(e) {
            if (m < max_fields) { //max box allowed
                m++;
                var manu = '<div class="multi-field"><input type="hidden" name="manu" value="' + m + '"><input id="manus" name="manu_name' + m + '" class="form-control prof pull-left" size="9"><button class="add-owner btn-xs btn-primary addp pull-right removemanu" type="button"><i class="fa fa-minus"></i></button></div>';
                $('#profile_item').append(manu);
            }
        });

        $('.add-manufacturer').click(function(e) {
            if (n < max_fields) { //max box allowed
                n++;
                var manufacturer = '<div class="multi-field"><input type="hidden" name="manufacturer" value="' + n + '"><input id="manufactures" name="manufacturer_name' + n + '" class="form-control prof pull-left" size="9"><button class="add-owner btn-xs btn-primary addp pull-right removemanufact" type="button"><i class="fa fa-minus"></i></button></div>';
                $('#profile_manufacturer').append(manufacturer);
            }
        });

        $('.add-fieldname').click(function(e) {
            if (r < max_fields) { //max box allowed
                r++;
                var fieldname = '<div class="multi-field"><input type="hidden" name="fieldname" value="' + r + '"><input id="fname' + r + '" name="field_name' + r + '" class="form-control prof pull-left" size="12"><button class="add-owner btn-xs btn-primary addp pull-right removepfield" type="button"><i class="fa fa-minus"></i></button></div>';
                var fieldtype = '<div class="multi-fields"><select id="field_type' + r + '" name="field_type' + r + '" class="form-control pull-left"  onchange="custom(' + r + ')"><option value="text_type">Text</option><option value="pick_list_type">Pick List</option><option value="value_type">$ Value</option><option value="date_type">Date</option><option value="num">Number</option></select></div>';
                $('#profile_field').append(fieldname);
                $('#profile_type').append(fieldtype);
            }
        });
        $('#add_custom').click(function(e) {
            if (s < max_fields) { //max box allowed
                s++;
                var fieldname = '<div class="multi-field"><input type="hidden" name="fieldname" value="' + r + '"><input id="fname' + r + '" name="names[]" class="form-control prof pull-left" size="12"><button class="add-owner btn-xs btn-primary addp pull-right removepfield" type="button"><i class="fa fa-minus"></i></button></div>';
                var fieldtype = '<div class="multi-fields"><select id="field_type' + s + '" name="types[]" class="form-control pull-left"  onchange="custom(' + s + ')"><option value="text_type">Text</option><option value="pick_list_type">Pick List</option><option value="value_type">$ Value</option><option value="date_type">Date</option><option value="num">Number</option></select></div>';
                $('#name_list').append(fieldname);
                $('#type_list').append(fieldtype);
            }
        });

        $("body").on("click", ".removepfield", function() {
            $('#profile_field .multi-field:last').remove();
            $('#profile_type .multi-fields:last').remove();
            r--;
        });
        $("body").on("click", ".removeowner", function() {
            $('#profile_owner .multi-field:last').remove();
            y--;
        });
        $("body").on("click", ".removepfield", function() {
            $('#name_list .multi-field:last').remove();
            $('#type_list .multi-fields:last').remove();
            s--;
        });
        $("body").on("click", ".removecat", function() {
            $('#profile_category .multi-field:last').remove();
            z--;
        });
        $("body").on("click", ".removemanu", function() {
            $('#profile_item .multi-field:last').remove();
            m--;
        });
        $("body").on("click", ".removemanufact", function() {
            $('#profile_manufacturer .multi-field:last').remove();
            n--;
        });

        $('#addprofile').on('click', function()
        {
            $('#new_profile').css('display', 'block');
            $('#edit_form').css('display', 'none');
            $('#save_button').css('display', 'block');
        });

        $('.add-fieldname').on('click', function()
        {
            $('.fieldval').css('display', 'none');
        });
        $('.view_profile').on('click', function()
        {
            $('#new_profile').css('display', 'none');
            $('#edit_form').css('display', 'block');
        });
    });
    function custom(field)
    {
        var type = $('#field_type' + field + ' option:selected').val();
        if (type == 'pick_list_type') {
            var textarea = '<textarea placeholder="Each Line Creates a New Value in PickList" name="field_values' + field + '" class="form-control fieldval pull-left" cols="6"></textarea>';
            $('#field_type' + field).parent('.multi-fields').append(textarea);
        }
        else
        {
            $('#field_type' + field).next().remove(textarea);
        }
    }
    function custom_txt(field)
    {
        var type = $('#profileedit' + field + ' option:selected').val();
        if (type == 'pick_list_type') {
            var textarea = '<textarea placeholder="Each Line Creates a New Value in PickList" name="field_values' + field + '" class="form-control fieldval pull-left" cols="6"></textarea>';
            $('#profileedit' + field).parent('.list-group-item').append(textarea);
            $('#profileedit' + field).parent('.list-group-item').css({'height':'145px','width':'162px'});
        }
        else
        {
            $('#profileedit' + field).next().remove(textarea);
            $('#profileedit' + field).parent('.list-group-item').css('height', '55px');
        }
    }
</script>
<style>
    .modal-body{
        min-height: 100px;
        max-height: 595px; 
        overflow-y: scroll;
    } 
    #masterprofile
    {
        float: left;
        margin-top: 10px;
    }
    #profilebody
    {
        min-height: 50px;
        max-height: 280px;
        overflow-y: scroll;
    }
    #dataTables-customerlist { overflow: auto;}
    .franchise-i
    {
        border: 1px solid #00aeef;
        border-radius: 3px;
        color: #00aeef !important;
        display: block;
        float: none;
        font-size: 14px;
        margin: auto;
        padding-bottom: 2px;
        padding-top: 2px;
        text-align: center;
        width: 25px;
    }
    .prof
    {
        width: 80%;
    }
    .addp
    {
        width: 20%;
        height: 32px;
    }
    .profilehead
    {
        background: #00aeef;  
        color: white;
        height: 30px;
        padding: 6px;
        text-align: center;
    }
    #new_profile
    {
        display: none;
    }
    #profileadd .col-md-2
    {
        padding-left: 0px;
        padding-right: 0px;
        border-right: 1px solid #ddd;
    }
    .profilename_error
    {
        color: red;
        font-weight: bold;  
    }
    #profile_options .panel-body
    {
        padding: 0px;
    }
    #profile_options th
    {
        text-align: center;
    }
    .prof
    {
        width: 80%!important;
    }
    #profile_options .multi-fields{
        width: 160px;
    }
    #owner_list .form-control,#categories_list .form-control,#item_list .form-control,#manufacturer_list .form-control
    {
        width: 100%;
    }
    #type_list .multi-fields select
    {
        width: 165px;
    }
    #type_list .multi-fields textarea
    {
        width: 165px;
    }
    #type_list .list-group-item textarea
    {
       width: 125px; 
    }
</style>
<br>

<input type="hidden" id="acc_id" value="<?php echo $this->uri->segment('3'); ?>">
<div class="panel panel-default">
    <div class="panel-heading">
        <b>  <?php echo strtoupper($account_name); ?> / Profile </b>
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <!-- Nav tabs -->
        <ul class="nav nav-pills">
            <li><a data-toggle="" href="<?php echo base_url("youaudit/customerlist/$masterid"); ?>">Customer List</a>
            </li>
            <li><a data-toggle="" href="<?php echo base_url("youaudit/Adminuser/$masterid"); ?>">Admin Users</a>
            </li>
            <li><a data-toggle="" href="<?php echo base_url("youaudit/master_admins/complianceChecks/$masterid"); ?>">Compliance Templates</a>
            </li>
            <li  class="active"><a data-toggle="" href="<?php echo base_url("youaudit/profiles/$masterid"); ?>">Profiles</a>
            </li>
            <li><a  data-toggle="" href="<?php echo base_url("youaudit/master_admins/arcivelist/$masterid"); ?>">Archive Account</a>
            </li>
        </ul>

        <!-- Tab panes -->

    </div>
    <!-- /.panel-body -->
</div>
<div class="row">
    <div class="col-lg-3">
        <h1 class="page-header">PROFILES</h1>
    </div>
    <div class="col-lg-9" style="margin-top: 35px;">
        <div class="panel panel-default">

            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="col-md-6 pull-left">
                    <a id="addprofile" class="button icon-with-text round pull-left"><i class="fa fa-plus-circle"></i><b>Add Profile</b></a>
                    <a class="button icon-with-text round pull-left" id="save_button" onclick="$('#add_profile_form').submit();"><i class="fa fa-arrow-circle-down"></i><b>Save</b></a></div>
                <div class="col-md-3 pull-right"><a class="button icon-with-text round pull-left" href='<?php echo base_url("youaudit/master_admins/exportProfilePdf/PDF/$masterid"); ?>' type="button" id="b1"><i class="fa  fa-file-pdf-o"></i>
                        <b> Export PDF</b></a>
                    <a  class="button icon-with-text round pull-left" href='<?php echo base_url("youaudit/master_admins/exportProfilePdf/CSV/$masterid"); ?>' type="button"><i class="fa fa-file-word-o"></i>
                        <b>Export CSV</b></a></div> 
            </div>
        </div>
    </div>

    <!-- /.col-lg-12 -->
</div>
<?php
if ($this->session->flashdata('success')) {
    ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('success'); ?>
    </div>
    <?php
}
?>
<?php
if ($this->session->flashdata('error')) {
    ?>
    <div class="alert alert-warning alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
        <?php echo $this->session->flashdata('error'); ?>
    </div>
    <?php
}
?>  

<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-4">
            <div class="panel-body">
                <div class="table-responsive">
                    <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-customerlist">

                        <table id="master_profile" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Profile Name</th>                               
                                    <th>Actions</th>
                                </tr>             
                            </thead>
                            <tbody id="profile_body">
                                <?php foreach ($profilelist as $profile) {
                                    ?>
                                    <tr>
                                        <td><?php echo $profile->profile_name; ?></td>
                                        <td><span class="action-w"><a data-toggle="modal" id="edit_adminuser_id_<?php echo $profile->profile_id; ?>" href="#edit_profile" title="Edit" data_profilename="<?php echo $profile->profile_name; ?>" data_adminuser_id="<?php echo $profile->profile_id; ?>" class="edit"><i class="glyphicon glyphicon-edit franchise-i"></i></a>Edit</span><span class="action-w"><a data-toggle="modal" href="#view_profile" class="view_profile" title="View"  profile_name="<?php echo $profile->profile_name; ?>" profile_id="<?php echo $profile->profile_id; ?>"><i class="glyphicon glyphicon-eye-open franchise-i"></i></a>View</span></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>

            </div>

            <!--<div class="table-responsive">-->
            <!--            <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-customerlist">
                            <form action="<?php echo base_url('youaudit/add_profile'); ?>" method="post" id="add_profile_form">
                                <div class="form-group col-md-12" style="float: left; margin-bottom: 10px;">
                                    <div class="col-md-6">  <label>Profile Name :</label> </div>
                                    <div class="col-md-6">  <input placeholder="Enter Profile Name" class="form-control" name="profile_name" id="profile_name">
                                        <div id="username_error" class="username_error hide">Profile Is Already Exist.</div> 
                                    </div>
                                    <input type="hidden" name="masterid" id="master_account_id" value="<?php echo $masterid; ?>"/>
                                </div>  /.form-group 
                                <div class="col-md-12" ><button class="btn btn-sm btn-info remove_field pull-left" id="remove" style="display: none;"><i class="fa fa-minus"></i>&nbsp;&nbsp;Remove</button><button class="add_field_button btn btn-success btn-sm pull-right" type="button"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button></div>
                                <table id="masterprofile" class="table table-striped table-bordered table-hover" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Owners</th>                               
                                            <th>Categories</th>
                                            <th>Item</th>
                                            <th>Manufacturer</th>
                                            <th>Custom Field Name</th>
                                            <th>Custom Type</th>
                                        </tr>             
                                    </thead> 
                                    <tbody id="profilebody">
                                    </tbody>                           
            
                                </table>
                                <div class="col-md-3 pull-left"><button class="btn btn-primary" type="submit" id="save_button">Submit</button></div>
                            </form>
                        </div>-->
            <!-- /.table-responsive -->
            <!--</div>-->

        </div>    <!-- col-lg-4 -->

        <div class="col-lg-8" id="edit_form">
            <form action="<?php echo base_url('youaudit/master_admins/editProfile'); ?>" method="post" id="edit_profile_form">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group ">
                            <input type="hidden" name="masterid" id="master_account_id" value="<?php echo $masterid; ?>"/>
                            <div class="col-md-4">  <label>Profile Name :</label> </div>
                            <div class="col-md-4">  <input placeholder="Enter Profile Name" disabled="" class="form-control" name="edit_profile_name" id="edit_profile_name"><input type="hidden" id="profileid" value="">
                                <div id="profilename_error" class="username_error hide">Profile Is Already Exist.</div>
                            </div>
                            <div class="col-md-3" ><input style="float:right" type="button" disabled="" id="add_custom" class="btn btn-info" value="Add Custom Field"></div>
                            <div class="col-md-1" ><input style="float:right" id="update_btn" type="submit" disabled="" class="btn btn-info" value="Save"></div>
                        </div> <!-- /.form-group -->
                        <input type="hidden" name="adminuser_id" id="adminuser_id_1" style="visibility:hidden;" readonly/>
                        <div class="col-md-4">
                            <div class="col-md-2">Show</div>
                            <div class="col-md-2"><select id="profilelimit" disabled="">
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                    <option value="100">All</option>
                                </select> 
                            </div>
                            <div class="col-md-1">Limit</div>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-customerlist">
                            <table id="profile_options" class="table table-striped table-bordered table-hover" width="90%" cellspacing="0">
                                <div style="display: none" align='center' id="loader"><img src='<?php echo base_url('./img/ajax-loader.gif'); ?>'/></div>
                                <thead>
                                    <tr>
                                        <th>Owners</th>
                                        <th>Categories</th>
                                        <th>Item</th>
                                        <th>Manufacturer</th>
                                        <th>Custom Field</th>
                                        <th>Custom Field Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="panel-body">
                                                <div class="list-group" id="owner_list">


                                                </div>
                                            </div>
                                        </td>
                                        <td><div class="panel-body">
                                                <div class="list-group" id="categories_list">


                                                </div>
                                            </div></td>
                                        <td><div class="panel-body">
                                                <div class="list-group" id="item_list">

                                                </div>
                                            </div></td>
                                        <td><div class="panel-body">
                                                <div class="list-group" id="manufacturer_list">

                                                </div>
                                            </div></td>
                                        <td><div class="panel-body">
                                                <div class="list-group" id="name_list">

                                                </div>
                                            </div></td>
                                        <td><div class="panel-body">
                                                <div class="list-group" id="type_list">

                                                </div>
                                            </div></td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>

            </form>
        </div>
        <div class="col-lg-8" id="new_profile">

            <form action="<?php echo base_url('youaudit/add_profile'); ?>" method="post" id="add_profile_form">
                <div class="form-group col-md-12" style="float: left; margin-bottom: 10px;">
                    <div class="col-md-4">  
                        <label>Profile Name :</label> </div>
                    <div class="col-md-4">  <input placeholder="Enter Profile Name" class="form-control" name="profile_name" id="profile_name">
                        <div id="username_error" class="username_error hide">Profile Is Already Exist.</div> 
                    </div>
                    <input type="hidden" name="masterid" id="master_account_id" value="<?php echo $masterid; ?>"/>
                </div> <!-- /.form-group -->
                <div class="form-group col-md-12" id="profileadd">
                    <div class="col-md-2" id="profile_owner">
                        <div class="profilehead">Owners</div>
                        <div class="multi-field"><textarea class="form-control pull-left" name="owner_name[]" id="owners" rows="30"></textarea></div>
                    </div>
                    <div class="col-md-2" id="profile_category">
                        <div class="profilehead">Categories</div>
                        <textarea class="form-control pull-left" name="category_name[]" id="categories" rows="30"></textarea>
                    </div>
                    <div class="col-md-2" id="profile_item">
                        <div class="profilehead">Item</div>
                        <textarea class="form-control pull-left" name="manu_name[]" id="manus" rows="30"></textarea>
                    </div>
                    <div class="col-md-2" id="profile_manufacturer">
                        <div class="profilehead">Manufacturer</div> 
                        <textarea class="form-control pull-left" name="manufacturer_name[]" id="manufactures" rows="30"></textarea>
                    </div>
                    <div class="col-md-2" id="profile_field">
                        <div class="profilehead">Custom Field Name</div> 
                        <input type="hidden" name="fieldname" value="1"><input size="12" class="form-control prof pull-left" name="field_name1"><button type="button" class="add-fieldname btn-xs btn-primary addp pull-right"><i class="fa fa-plus"></i></button>
                    </div>
                    <div class="col-md-2" id="profile_type">
                        <div class="profilehead">Custom Type</div>
                        <div class="multi-fields"><select class="form-control pull-left custom_type" name="field_type1" id="field_type1" onchange="custom(1)"><option value="text_type">Text</option><option value="pick_list_type">Pick List</option><option value="value_type">$ Value</option><option value="date_type">Date</option><option value="num">Number</option></select></div>
                    </div>

                </div>
        </div>
        </form>
    </div>
</div>

</div> <!-- col-lg-12 -->
</div>






