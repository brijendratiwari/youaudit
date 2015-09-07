//$(function () {
//
//    $('#side-menu').metisMenu();
//
//});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;
        height = height - topOffset;
        if (height < 1)
            height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });
});

$(document).ready(function()
{
    // Calculate Sum() to Evalute this value it is necessory    
    jQuery.fn.dataTable.Api.register('sum()', function() {
        return this.flatten().reduce(function(a, b) {
            return (a * 1) + (b * 1); // cast values in-case they are strings
        }, 0);
    });
    /*
     assigning keyup event to password field
     so everytime user type code will execute
     */

    $('#contact_password').keyup(function()
    {
        $('.result').html(checkStrength($('#contact_password').val()))

    });
    $('#contact_password_franchise').keyup(function()
    {
        $('.result').html(checkStrength($('#contact_password_franchise').val()))
    })

    $('#contact_password_franchise').keyup(function()
    {
        $('.result').html(checkStrength($('#contact_password_franchise').val()))
    });

    $('#user_password').keyup(function()
    {
        $('.result').html(checkStrength($('#user_password').val()))
    });

    $('#new_password').keyup(function()
    {
        $('.result').html(checkStrength($('#new_password').val()))
    });

    $('#contact_password').keyup(function()
    {
        $('.result').html(checkStrength($('#contact_password').val()))
    });

    $('#edit_contact_password').keyup(function()
    {
        $('.result').html(checkStrength($('#edit_contact_password').val()))
    });



    /*
     checkStrength is function which will do the 
     main password strength checking for us
     */

    function checkStrength(password)
    {
        //initial strength
        var strength = 0;

        //if the password length is less than 6, return message.
        if (password.length < 6) {
            $('#result').removeClass();
            $('#result').addClass('short');
            $('.result').css("color", "#FF3300");
            return 'Too short';
        }

        //length is ok, lets continue.

        //if length is 8 characters or more, increase strength value
        if (password.length > 7)
            strength += 1;

        //if password contains both lower and uppercase characters, increase strength value
        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))
            strength += 1;

        //if it has numbers and characters, increase strength value
        if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))
            strength += 1;

        //if it has one special character, increase strength value
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))
            strength += 1;

        //if it has two special characters, increase strength value
        if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/))
            strength += 1;

        //now we have calculated strength value, we can return messages

        //if value is less than 2
        if (strength < 2)
        {
            $('#result').removeClass();
            $('#result').addClass('weak');
            $('.result').css("color", "#CC2900");
            return 'Weak';
        }
        else if (strength == 2)
        {
            $('#result').removeClass();
            $('#result').addClass('good');
            $('.result').css("color", "#337AB7");
            return 'Good';
        }
        else
        {
            $('#result').removeClass();
            $('#result').addClass('strong');
            $('.result').css("color", "#1A4C80");
            return 'Strong';
        }
    }

// script for master table

    var base_url_str = $("#base_url").val();

    var master_table = $("#master_datatable").DataTable({
        "oLanguage": {
            "sProcessing": "<div align='center'><img src='" + base_url_str + "img/ajax-loader.gif'></div>"},
        "ordering": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url_str + "youaudit/youaudit_admins/getMasterAccountData",
        "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
        "iDisplayLength": 10,
        "bDestroy": true, //!!!--- for remove data table warning.
      
        "fnRowCallback": function(nRow, aData) {

            var $nRow = $(nRow); // cache the row wrapped up in jQuery
            tdhtm = $nRow.children()[9].innerHTML;

            if (tdhtm.search("enable") != -1) {
                $nRow.css("background-color", "#f2b4b4");
            }

            return nRow;
        },
        "aoColumnDefs": [
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [0]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [1]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [3]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [4]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [5]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [6]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [7]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [8]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [9]}
        ]}
    


    );

    // script for franchise table


    var franchise_table = $("#franchise_datatable").DataTable({
        "oLanguage": {
            "sProcessing": "<div align='center'><img src='" + base_url_str + "img/ajax-loader.gif'></div>"},
        "ordering": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url_str + "youaudit/youaudit_admins/getFranchiseAccountData", "bDeferRender": true,
        "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
        "iDisplayLength": 10,
        "bDestroy": true, //!!!--- for remove data table warning.
        "fnRowCallback": function(nRow, aData) {

            var $nRow = $(nRow); // cache the row wrapped up in jQuery
            tdhtm = $nRow.children()[9].innerHTML;

            if (tdhtm.search("enable") != -1) {
                $nRow.css("background-color", "#f2b4b4");
            }

            return nRow;
        },
        "aoColumnDefs": [
            {"bSortable": true, "aTargets": [0]},
            {"sClass": " aligncenter", "bSortable": true, "aTargets": [1]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [3]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [4]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [5]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [6]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [7]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [8]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [9]},
        ]}
    );


    // script for System Admin table     



    var franchise_table = $("#systemAdmin_datatable").DataTable({
        "oLanguage": {
            "sProcessing": "<div align='center'><img src='" + base_url_str + "img/ajax-loader.gif'></div>"},
        "ordering": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url_str + "youaudit/youaudit_admins/getSystemAccountData", "bDeferRender": true,
        "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
        "iDisplayLength": 10,
        "bDestroy": true, //!!!--- for remove data table warning.
        "fnRowCallback": function(nRow, aData) {

            var $nRow = $(nRow); // cache the row wrapped up in jQuery
            tdhtm = $nRow.children()[3].innerHTML;

            if (tdhtm.search("enable") != -1) {
                $nRow.css("background-color", "#f2b4b4");
            }

            return nRow;
        },
        "aoColumnDefs": [
            {"bSortable": true, "aTargets": [0]},
            {"sClass": " aligncenter", "bSortable": true, "aTargets": [1]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [3]}
        ]}
    );

// Script for Master Admin USer Table

    var master_id = $('#masterac_id').val();

    var franchise_table = $("#Adminuser_datatable").DataTable({
        "oLanguage": {
            "sProcessing": "<div align='center'><img src='" + base_url_str + "img/ajax-loader.gif'></div>"},
        "ordering": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url_str + "youaudit/master_admins/getAdminUserData/" + master_id, "bDeferRender": true,
        "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
        "iDisplayLength": 10,
        "bDestroy": true, //!!!--- for remove data table warning.
        "aoColumnDefs": [
            {"bSortable": true, "aTargets": [0]},
            {"sClass": " aligncenter", "bSortable": true, "aTargets": [1]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [3]},
        ]}
    );


    // Script for Master Customer List Table


    var master_account_id = $('#master_account_id').val();

    var mcustomertable = $("#Master_Customer_Datatable").DataTable({
        "oLanguage": {
            "sProcessing": "<div align='center'><img src='" + base_url_str + "img/ajax-loader.gif'></div>"},
        "ordering": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url_str + "youaudit/master_admins/getCustomerAc/" + master_account_id, "bDeferRender": true,
        "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
        "iDisplayLength": 10,
        "bDestroy": true, //!!!--- for remove data table warning.
        "fnDrawCallback": function() {
            var api = this.api();
            $(api.column(7).footer()).html(
                    api.column(7, {page: 'current'}).data().sum()
                    );
            $(api.column(8).footer()).html(
                    api.column(8, {page: 'current'}).data().sum()
                    );
            $(api.column(15).footer()).html(
                    api.column(15, {page: 'current'}).data().sum()
                    );


        },
        "fnRowCallback": function(nRow, aData) {

            var $nRow = $(nRow); // cache the row wrapped up in jQuery
            tdhtm = $nRow.children()[16].innerHTML;

            if (tdhtm.search("enable") != -1) {
                $nRow.css("background-color", "#f2b4b4");
            }

            return nRow;
        },
        "aoColumnDefs": [
            {"bSortable": true, "aTargets": [0]},
            {"sClass": " aligncenter", "bSortable": false, "aTargets": [1]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [2]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [3]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [4]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [5]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [6]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [7]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [8]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [9]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [10]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [11]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [12]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [13]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [14]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [15]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [16]}
        ]}
    );
    $("body").on("change", "#states", function() {
        mcustomertable.column(3)
                .search(this.value)
                .draw();
    });
    $("body").on("change", "#acc_package", function() {
        mcustomertable.column(6)
                .search(this.value)
                .draw();
    });


    // Script for FraNCHISE Customer List Table

    var master_account_id = $('#master_account_id').val();

    var customertable = $("#Franchise_Customer_Datatable").DataTable({
        "oLanguage": {
            "sProcessing": "<div align='center'><img src='" + base_url_str + "img/ajax-loader.gif'></div>"},
        "ordering": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url_str + "youaudit/franchise_admins/getFranchiseCustomerAc/" + master_account_id, "bDeferRender": true,
        "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
        "iDisplayLength": 10,
        "bDestroy": true, //!!!--- for remove data table warning.
        "fnDrawCallback": function() {
            var api = this.api();
            $(api.column(7).footer()).html(
                    api.column(7, {page: 'current'}).data().sum()
                    );
            $(api.column(8).footer()).html(
                    api.column(8, {page: 'current'}).data().sum()
                    );
            $(api.column(15).footer()).html(
                    api.column(15, {page: 'current'}).data().sum()
                    );


        },
        "fnRowCallback": function(nRow, aData) {

            var $nRow = $(nRow); // cache the row wrapped up in jQuery
            tdhtm = $nRow.children()[16].innerHTML;

            if (tdhtm.search("enable") != -1) {
                $nRow.css("background-color", "#f2b4b4");
            }

            return nRow;
        },
        "aoColumnDefs": [
            {"bSortable": true, "aTargets": [0]},
            {"sClass": " aligncenter", "bSortable": false, "aTargets": [1]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [2]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [3]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [4]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [5]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [6]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [7]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [8]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [9]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [10]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [11]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [12]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [13]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [14]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [15]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [16]}
        ]}


    );
    $("body").on("change", "#states", function() {
        customertable.column(3)
                .search(this.value)
                .draw();
    });
    $("body").on("change", "#acc_package", function() {
        customertable.column(6)
                .search(this.value)
                .draw();
    });


    // get franchise admin user
    var master_id = $('#masterac_id').val();

    var franchise_table = $("#franchiseadminuser_datatable").DataTable({
        "oLanguage": {
            "sProcessing": "<div align='center'><img src='" + base_url_str + "img/ajax-loader.gif'></div>"},
        "ordering": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url_str + "youaudit/franchise_admins/getFranchiseAdminUserData/" + master_id, "bDeferRender": true,
        "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
        "iDisplayLength": 10,
        "bDestroy": true, //!!!--- for remove data table warning.
        "aoColumnDefs": [
            {"bSortable": true, "aTargets": [0]},
            {"sClass": " aligncenter", "bSortable": true, "aTargets": [1]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [3]},
        ]}
    );
    
    // Get Archive Cutomer data for master
      var master_account_id = $('#master_account_id').val();
        
    var m_customertable = $("#Master_ArchiveCustomer_Datatable").DataTable({
        "oLanguage": {
            "sProcessing": "<div align='center'><img src='" + base_url_str + "img/ajax-loader.gif'></div>"},
        "ordering": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url_str + "youaudit/master_admins/getArchiveCustomerAc/" + master_account_id, 
        "bDeferRender": true,
        "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
        "iDisplayLength": 10,
        "bDestroy": true, //!!!--- for remove data table warning.
        "fnDrawCallback": function() {
            var api = this.api();
            $(api.column(6).footer()).html(
                    api.column(6, {page: 'current'}).data().sum()
                    );
            $(api.column(8).footer()).html(
                    api.column(8, {page: 'current'}).data().sum()
                    );
            


        },
     
        "aoColumnDefs": [
            {"bSortable": true, "aTargets": [0]},
            {"sClass": " aligncenter", "bSortable": false, "aTargets": [1]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [2]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [3]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [4]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [5]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [6]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [7]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [8]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [9]},
          
           
          
        ]}
    );
    $("body").on("change", "#states", function() {
        m_customertable.column(3)
                .search(this.value)
                .draw();
    });
    $("body").on("change", "#acc_package", function() {
        m_customertable.column(6)
                .search(this.value)
                .draw();
    });

    
    // Script for FraNCHISE Archive Customer List Table

    var franchise_account_id = $('#master_account_id').val();

    var franchise_customertable = $("#Franchise_ArchiveCustomer_Datatable").DataTable({
        "oLanguage": {
            "sProcessing": "<div align='center'><img src='" + base_url_str + "img/ajax-loader.gif'></div>"},
        "ordering": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url_str + "youaudit/franchise_admins/getFranchiseArchiveCustomerAc/" + franchise_account_id, "bDeferRender": true,
        "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
        "iDisplayLength": 10,
        "bDestroy": true, //!!!--- for remove data table warning.
        "fnDrawCallback": function() {
            var api = this.api();
            $(api.column(6).footer()).html(
                    api.column(6, {page: 'current'}).data().sum()
                    );
            $(api.column(8).footer()).html(
                    api.column(8, {page: 'current'}).data().sum()
                    );
         


        },
     
        "aoColumnDefs": [
            {"bSortable": true, "aTargets": [0]},
            {"sClass": " aligncenter", "bSortable": false, "aTargets": [1]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [2]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [3]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [4]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [5]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [6]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [7]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [8]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [9]},
            
           
        ]}


    );
    $("body").on("change", "#states", function() {
        customertable.column(3)
                .search(this.value)
                .draw();
    });
    $("body").on("change", "#acc_package", function() {
        customertable.column(6)
                .search(this.value)
                .draw();
    });

    
       // Script for Master Admin USer Table

    var master_id = $('#masterac_id').val();

    var master_admintable = $("#archiveAdminuser_datatable").DataTable({
        "oLanguage": {
            "sProcessing": "<div align='center'><img src='" + base_url_str + "img/ajax-loader.gif'></div>"},
        "ordering": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url_str + "youaudit/master_admins/getArchiveAdminUserData/" + master_id, "bDeferRender": true,
        "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
        "iDisplayLength": 10,
        "bDestroy": true, //!!!--- for remove data table warning.
        "aoColumnDefs": [
            {"bSortable": true, "aTargets": [0]},
            {"sClass": " aligncenter", "bSortable": true, "aTargets": [1]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [3]},
        ]}
    );
    
    
    //
    
      // get franchise archive admin user
    var franchiseid = $('#franchiseid').val();

    var franchise_admintable = $("#franchisearchiveadminuser_datatable").DataTable({
        "oLanguage": {
            "sProcessing": "<div align='center'><img src='" + base_url_str + "img/ajax-loader.gif'></div>"},
        "ordering": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url_str + "youaudit/franchise_admins/getArchiveAdminUser/" + franchiseid, "bDeferRender": true,
        "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
        "iDisplayLength": 10,
        "bDestroy": true, //!!!--- for remove data table warning.
        "aoColumnDefs": [
            {"bSortable": true, "aTargets": [0]},
            {"sClass": " aligncenter", "bSortable": true, "aTargets": [1]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [3]},
        ]}
    );
    
// script for Archive master table

    var base_url_str = $("#base_url").val();

    var master_table = $("#master_archivedatatable").DataTable({
        "oLanguage": {
            "sProcessing": "<div align='center'><img src='" + base_url_str + "img/ajax-loader.gif'></div>"},
        "ordering": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url_str + "youaudit/youaudit_admins/getArchiveMasterAccountData",
        "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
        "iDisplayLength": 10,
        "bDestroy": true, //!!!--- for remove data table warning.
      
        
        "aoColumnDefs": [
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [0]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [1]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [3]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [4]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [5]},
            
        ]}
    


    );
    
    
    
    // script for Archive master table

    var base_url_str = $("#base_url").val();

    var franchise_table = $("#franchise_archivedatatable").DataTable({
        "oLanguage": {
            "sProcessing": "<div align='center'><img src='" + base_url_str + "img/ajax-loader.gif'></div>"},
        "ordering": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url_str + "youaudit/youaudit_admins/getArchiveFranchiseAccountData",
        "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
        "iDisplayLength": 10,
        "bDestroy": true, //!!!--- for remove data table warning.
      
        
        "aoColumnDefs": [
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [0]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [1]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [3]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [4]},
            {"sClass": "eamil_conform aligncenter", "bSortable": false, "aTargets": [5]},
            
        ]}
    


    );
    
    
       var system_table = $("#archivesystemAdmin_datatable").DataTable({
        "oLanguage": {
            "sProcessing": "<div align='center'><img src='" + base_url_str + "img/ajax-loader.gif'></div>"},
        "ordering": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": base_url_str + "youaudit/youaudit_admins/getArchibeSystemAccountData", "bDeferRender": true,
        "aLengthMenu": [[10, 20, 40, -1], [10, 20, 40, "All"]],
        "iDisplayLength": 10,
        "bDestroy": true, //!!!--- for remove data table warning.
        "fnRowCallback": function(nRow, aData) {

            var $nRow = $(nRow); // cache the row wrapped up in jQuery
            tdhtm = $nRow.children()[3].innerHTML;

            if (tdhtm.search("enable") != -1) {
                $nRow.css("background-color", "#f2b4b4");
            }

            return nRow;
        },
        "aoColumnDefs": [
            {"bSortable": true, "aTargets": [0]},
            {"sClass": " aligncenter", "bSortable": true, "aTargets": [1]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [2]},
            {"sClass": "eamil_conform aligncenter", "bSortable": true, "aTargets": [3]}
        ]}
    );

});