    <h2><?php echo $arrAccount['result'][0]->accountname; ?></h2>
    <ul>
	<li>Address:
	    <br /><?php echo $arrAccount['result'][0]->accountaddress; ?>
	    <br /><?php echo $arrAccount['result'][0]->accountcity; ?>
	    <?php
	    if ($arrAccount['result'][0]->accountcounty != '')
	    {
		?><br /><?php echo $arrAccount['result'][0]->accountcounty;
	    }
	    ?>
	    <br /><?php echo $arrAccount['result'][0]->accountpostcode; ?>
	    <?php
	    if ($arrAccount['result'][0]->accountcountry != '')
	    {
		?><br /><?php echo $arrAccount['result'][0]->accountcountry;
	    }
	    ?></li>
	<li>Challenge Question: <?php echo $arrAccount['result'][0]->accountsecurityquestion; ?>
	    <br />Answer: <?php echo $arrAccount['result'][0]->accountsecurityanswer; ?></li>
	<li>Contact: <?php echo $arrAccount['result'][0]->accountcontactname; ?>
	    <br />Email: <?php echo $arrAccount['result'][0]->accountcontactemail; ?>
	    <br />Number: <?php echo $arrAccount['result'][0]->accountcontactnumber; ?></li>
	<li>Package: <?php echo $arrAccount['result'][0]->accountpackagename; ?></li>
	<li>Verified: <?php if ($arrAccount['result'][0]->accountverified == 1) { echo "Yes"; } else { echo "No"; }; ?></li>
	<li><a href="<?php echo site_url('/'.strtolower($arrPageParameters['strSection']).'/editaccount/'.$arrAccount['result'][0]->accountid); ?>">Edit this account</a></li>
	<li><a href="<?php echo site_url('/'.strtolower($arrPageParameters['strSection']).'/viewusers/'.$arrAccount['result'][0]->accountid); ?>">View Users on this account</a></li>
    </ul>