<div class="box">
    <div class="heading">
        <h1>Supplier - <?=$supplier->supplier_title?></h1>
    </div>

    <div class="box_content">
        <div class="content_main">
            <table class="list_table">
                <thead>
                <tbody>
                <tr>
                    <td><strong>Name</strong></td>
                    <td><?=$supplier->supplier_title?></td>
                </tr>
                <tr>
                    <td><strong>Website</strong></td>
                    <td><a target="_blank" href="<?=$supplier->supplier_website?>"><?=$supplier->supplier_website?></a></td>
                </tr>
                <tr>
                    <td><strong>Email</strong></td>
                    <td><a href="mailto:<?=$supplier->supplier_title?>"><?=$supplier->supplier_title?></a></td>
                </tr>
                <tr>
                    <td><strong>Telephone</strong></td>
                    <td><?=$supplier->supplier_telephone?></td>
                </tr>

                <tr>
                    <td><strong>Address line 1</strong></td>
                    <td><?=$supplier->supplier_address1?></td>
                </tr>
                <tr>
                    <td><strong>Address line 2</strong></td>
                    <td><?=$supplier->supplier_address2?></td>
                </tr>
                <tr>
                    <td><strong>Address line 3</strong></td>
                    <td><?=$supplier->supplier_address3?></td>
                </tr>
                <tr>
                    <td><strong>Town/City</strong></td>
                    <td><?=$supplier->supplier_town?></td>
                </tr>
                <tr>
                    <td><strong>County/State</strong></td>
                    <td><?=$supplier->supplier_county?></td>
                </tr>
                <tr>
                    <td><strong>Postcode</strong></td>
                    <td><?=$supplier->supplier_postcode?></td>
                </tr>

                <tr>
                    <td><strong>Contact Name</strong></td>
                    <td><?=$supplier->supplier_contact_name?></td>
                </tr>
                <tr>
                    <td><strong>Contact Job Title</strong></td>
                    <td><?=$supplier->supplier_contact_job_title?></td>
                </tr>
                <tr>
                    <td><strong>Our Account Number</strong></td>
                    <td><?=$supplier->our_account_number?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>