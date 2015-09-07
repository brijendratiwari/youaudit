<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
//routes for youaudit
$route['youaudit/dashboard'] = "youaudit/youaudit_admins/dashboard";
$route['youaudit/login'] = "youaudit/youaudit_admins/login";
$route['youaudit/pincodeAuthentication'] = "youaudit/youaudit_admins/pincodeAuthentication";
$route['youaudit/logout'] = "youaudit/youaudit_admins/logout";
$route['youaudit/masterAccount'] = "youaudit/youaudit_admins/masterAccount";
$route['youaudit/franchisesAccount'] = "youaudit/youaudit_admins/franchisesAccount";
$route['youaudit/index'] = "youaudit/youaudit_admins/index";
$route['youaudit/addmasterAccount'] = "youaudit/youaudit_admins/addmasterAccount";
$route['youaudit/addfranchiseAccount'] = "youaudit/youaudit_admins/addfranchiseAccount";
$route['youaudit/adminlist'] = "youaudit/youaudit_admins/adminlist";
$route['youaudit/setNews'] = "youaudit/youaudit_admins/setNews";
$route['youaudit/addSystemAccount'] = "youaudit/youaudit_admins/addSystemAccount";
$route['youaudit/disableMasterAccount/(:any)'] = "youaudit/youaudit_admins/disableMasterAccount/$1/$2";
$route['youaudit/enableMasterAccount/(:any)'] = "youaudit/youaudit_admins/enableMasterAccount/$1/$2";
$route['youaudit/editMasterAccount'] = "youaudit/youaudit_admins/editMasterAccount";
$route['youaudit/changeMasterUserPassword'] = "youaudit/youaudit_admins/changeMasterUserPassword";
$route['youaudit/disableFranchiseAccount/(:any)'] = "youaudit/youaudit_admins/disableFranchiseAccount/$1";
$route['youaudit/enableFranchiseAccount/(:any)'] = "youaudit/youaudit_admins/enableFranchiseAccount/$1";
$route['youaudit/editFranchiseAccount'] = "youaudit/youaudit_admins/editFranchiseAccount";
$route['youaudit/changeFranchisePassword'] = "youaudit/youaudit_admins/changeFranchisePassword";
$route['youaudit/enableSystemAccount/(:any)'] = "youaudit/youaudit_admins/enableSystemAccount/$1";
$route['youaudit/disableSystemAccount/(:any)'] = "youaudit/youaudit_admins/disableSystemAccount/$1";
$route['youaudit/editSystemAccount'] = "youaudit/youaudit_admins/editSystemAccount";
$route['youaudit/changeSystemAdminPassword'] = "youaudit/youaudit_admins/changeSystemAdminPassword";
$route['youaudit/archive'] = "youaudit/youaudit_admins/archive";
$route['youaudit/restoreMaster/(:any)'] = "youaudit/youaudit_admins/restoreMaster/$1";
$route['youaudit/restoreFranchise/(:any)'] = "youaudit/youaudit_admins/restoreFranchise/$1";
$route['youaudit/restoreSystemAccount/(:any)'] = "youaudit/youaudit_admins/restoreSystemAccount/$1";

// route for packagelist
$route['youaudit/packagelist'] = "youaudit/youaudit_admins/packagelist";
$route['youaudit/add_package'] = "youaudit/youaudit_admins/add_package";
$route['youaudit/edit_package'] = "youaudit/youaudit_admins/edit_package";

// route for archive admin
$route['youaudit/archiveAdmin/(:any)'] = "youaudit/youaudit_admins/archiveAdmin/$1";
// route for archive master
$route['youaudit/archiveMaster/(:any)'] = "youaudit/youaudit_admins/archiveMaster/$1";
// route for archive Franchise
$route['youaudit/archiveFranchise/(:any)'] = "youaudit/youaudit_admins/archiveFranchise/$1";

// routes for master

$route['youaudit/Adminuser/(:any)'] = "youaudit/master_admins/adminuser/$1";
$route['youaudit/customerlist/(:any)'] = "youaudit/master_admins/customerlist/$1";
$route['youaudit/addAdminUser'] = "youaudit/master_admins/addAdminUser";
$route['youaudit/editAdminUser'] = "youaudit/master_admins/editAdminUser";
$route['youaudit/changeAdminUserPassword'] = "youaudit/master_admins/changeAdminUserPassword";
$route['youaudit/disableadminuser/(:any)'] = "youaudit/master_admins/disableadminuser/$1/$2";
$route['youaudit/enableadminuser/(:any)'] = "youaudit/master_admins/enableadminuser/$1/$2";
$route['youaudit/addCustomerAc'] = "youaudit/master_admins/addCustomerAc";
$route['youaudit/disableCustomer/(:any)'] = "youaudit/master_admins/disableCustomer/$1/$2";
$route['youaudit/enableCustomer/(:any)'] = "youaudit/master_admins/enableCustomer/$1/$2";
$route['youaudit/editCustomerAc'] = "youaudit/master_admins/editCustomerAc";
$route['youaudit/profiles/(:any)'] = "youaudit/master_admins/profiles/$1";
$route['youaudit/add_profile'] = "youaudit/master_admins/add_profile";
$route['youaudit/editProfile'] = "youaudit/master_admins/editProfile";



// routes for franchise
$route['youaudit/franchise_customerlist/(:any)'] = "youaudit/franchise_admins/franchise_customerlist/$1";
$route['youaudit/addFranchiseCustomerAc'] = "youaudit/franchise_admins/addFranchiseCustomerAc";
$route['youaudit/disableFranchiseCustomer/(:any)'] = "youaudit/franchise_admins/disableFranchiseCustomer/$1/$2";
$route['youaudit/enableFranchiseCustomer/(:any)'] = "youaudit/franchise_admins/enableFranchiseCustomer/$1/$2";
$route['youaudit/franchiseAdminUser/(:any)'] = "youaudit/franchise_admins/franchiseAdminUser/$1";
$route['youaudit/addFranchiesAdminUser'] = "youaudit/franchise_admins/addFranchiesAdminUser";
$route['youaudit/changeFranchiseAdminUserPassword'] = "youaudit/franchise_admins/changeFranchiseAdminUserPassword";
$route['youaudit/editFranchiseAdminUser'] = "youaudit/franchise_admins/editFranchiseAdminUser";
$route['youaudit/disableFranchiseAdminUser/(:any)'] = "youaudit/franchise_admins/disableFranchiseAdminUser/$1/$2";
$route['youaudit/enableFranchiseAdminUser/(:any)'] = "youaudit/franchise_admins/enableFranchiseAdminUser/$1/$2";
$route['youaudit/editFranchiseCustomerAc'] = "youaudit/franchise_admins/editFranchiseCustomerAc";
$route['youaudit/franchise_profiles/(:any)'] = "youaudit/franchise_admins/profiles/$1";
$route['youaudit/franchise_add_profile'] = "youaudit/franchise_admins/add_profile";
$route['youaudit/franchise_editProfile'] = "youaudit/franchise_admins/editProfile";

$route['users/(:any)'] = 'users/$1';
$route['users'] = 'users';
$route['default_controller'] = "welcome";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */