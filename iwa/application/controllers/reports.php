<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller 
{

    public function index()
    {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
                $this->session->set_userdata('strReferral', '/reports/');
                redirect('users/login/', 'refresh');
        }


        $this->load->model('locations_model');
        // housekeeping
        $arrPageData = array();
        $arrPageData['arrLocations'] = $this->locations_model->getAll($this->session->userdata('objSystemUser')->accountid);
        $arrPageData['arrLocations'] = $arrPageData['arrLocations']['results'];
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Select";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();

        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");

        if ($booPermission)
        {
            // helpers
            $this->load->helper('form');
            $this->load->library('form_validation');
            
            $arrReports                     = array(
                                                    array('id' => '1', 'name' => 'PAT Failures')
                                                    , array('id' => '2', 'name' => 'PAT Due')
                                                    , array('id' => '3', 'name' => 'Items Total Value')
                                                    , array('id' => '4', 'name' => 'Items Total Value by User')
                                                    , array('id' => '5', 'name' => 'Items Total Value by Site')
                                                    , array('id' => '7', 'name' => 'Items Total Value by Location')
                                                    , array('id' => '6', 'name' => 'Removed Items')
                                                    , array('id' => '8', 'name' => 'Fleet Compliance')
                                                    , array('id' => '9', 'name' => 'Compliance checks due')
                                                    , array('id' => '10', 'name' => 'Compliance Completed')
                                                    , array('id' => '11', 'name' => 'Items Location Report')
                                                    , array('id' => '12', 'name' => 'Missing Items Report')
                                                );
            if($this->session->userdata('objSystemUser')->fleet == 0 || $this->session->userdata('objSystemUser')->compliance == 0) {
                if($this->session->userdata('objSystemUser')->fleet == 0) {               
                    unset($arrReports[7]);
                }
                if($this->session->userdata('objSystemUser')->compliance == 0) {

                    unset($arrReports[8]);
                    unset($arrReports[9]);
                }
            }
            $arrPageData['arrReports']      = $arrReports;
            $arrPageData['strStartDate']    = "";
            $arrPageData['strEndDate']      = "";
        }
        else
        {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }


        // load views
        $this->load->view('common/header',              $arrPageData);
        if ($booPermission)
        {
            //$this->load->view('common/system_message',  $arrPageData);
                //load the correct view
            $this->load->view('reports/index',          $arrPageData);
            $this->load->view('reports/forms/index',    $arrPageData);
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }
        $this->load->view('common/footer',              $arrPageData);

    }
	
    public function createPdf()
    {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
                $this->session->set_userdata('strReferral', '/reports/');
                redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Results PDF";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
     
        
        
        $this->load->model('users_model');
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");
        $booSuccess = false;
        $booOutputHtml = false;
        
        $arrUriSegments = $this->uri->segment_array();
        //$strName = $arrUriSegments[count($arrUriSegments)];
        $strReportType = strtolower($arrUriSegments[3]);
        $arrParameters = array_slice($arrUriSegments, 3);

        $mixStartDate   = $arrParameters[0] ? $arrParameters[0] : false;
        $mixEndDate     = $arrParameters[1] ? $arrParameters[1] : false;

        if ($booPermission)
        {
            $this->load->model('reports_model');
            
            $arrResults = array();
            switch ($strReportType)
            {
                case 'patfailures':

                    $arrResults = $this->reports_model->getPatFailures(
                                                        $mixStartDate
                                                        , $mixEndDate
                                                        , $this->session->userdata('objSystemUser')->accountid
                                                                        );
                    $arrFields = array(
                                        array('strName' => 'Barcode', 'strFieldReference' => 'barcode')
                                        , array('strName' => 'Serial Number','strFieldReference' => 'serial_number')
                                        , array('strName' => 'Manufacturer and Model','strFieldReference' => 'itemname')
                                        , array('strName' => 'Failure Date','strFieldReference' => 'pattest_date', 'strConversion'=>'date')
                                        );
                    
                    $strReportName = "PAT Failures";
                    if ($mixStartDate && $mixEndDate)
                    {
                        $strReportName .= " between ".$this->doFormatDateBack($mixStartDate)." and ".$this->doFormatDateBack($mixEndDate);
                    }
                    break;
                case 'missing':

                    $arrResults['results'] = $this->reports_model->getMissingItems($mixStartDate,$mixEndDate, TRUE);

                    $arrFields = array(
                        array('strName' => 'Manufacturer', 'strFieldReference' => 'manufacturer', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>1))
                    , array('strName' => 'Model','strFieldReference' => 'model', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                    , array('strName' => 'Location','strFieldReference' => 'name', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                    , array('strName' => 'Value','strFieldReference' => 'value', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                    , array('strName' => 'Audit Date','strFieldReference' => 'completed', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                    );

                    $strReportName = "Audit Missing Items";
                    if ($mixStartDate && $mixEndDate)
                    {
                        $strReportName .= " between ".$this->doFormatDateBack($mixStartDate)." and ".$this->doFormatDateBack($mixEndDate);
                    }

                    break;
                case 'locationreport':

                    $this->load->model('locations_model');
                    $this->load->model('itemstatus_model');
                    $this->load->model('users_model');

                    $arrResults['results'] = $this->locations_model->getAllItemsForLocation($this->uri->segment(4), $this->session->userdata('objSystemUser')->accountid, (int)$this->uri->segment(4)==0);
                    $arrFields = array(
                        array('strName' => 'Manufacturer', 'strFieldReference' => 'manufacturer', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>1))
                    , array('strName' => 'Model','strFieldReference' => 'model', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                    , array('strName' => 'Location','strFieldReference' => 'location', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                    , array('strName' => 'Value','strFieldReference' => 'value', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                    , array('strName' => 'Status','strFieldReference' => 'status', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                    , array('strName' => 'Owner','strFieldReference' => 'owner', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                    , array('strName' => 'Barcode','strFieldReference' => 'itembarcode', 'strConversion'=>'price', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                    );

                    foreach ($arrResults['results'] as $key => $value ){
                        $arrResults['results'][$key]->status = $this->itemstatus_model->getStatus($value->status);
                    }


                    foreach ($arrResults['results'] as $key => $value ){
                        $owner = $this->users_model->getOne($value->owner, $this->session->userdata('objSystemUser')->accountid);
                        $owner =  isset($owner['result'][0]) ? $owner['result'][0]->firstname . ' ' . $owner['result'][0]->lastname : '';
                        $arrResults['results'][$key]->owner = $owner;
                    }

                    $loc = $this->locations_model->getOne($this->uri->segment(4), $this->session->userdata('objSystemUser')->accountid);
                    $loc = isset($loc['results'][0])  ? $loc['results'][0]->locationname : 'All';

                    $strReportName = "All items in location: ".$loc;
                    break;
                case 'patdue':
                    $arrResults = $this->reports_model->getPatDue(
                                                                $mixStartDate
                                                                , $mixEndDate
                                                                , $this->session->userdata('objSystemUser')->accountid
                                                                                );
                    $arrFields = array(
                                                array('strName' => 'Barcode', 'strFieldReference' => 'barcode')
                                                , array('strName' => 'Serial Number','strFieldReference' => 'serial_number')
                                                , array('strName' => 'Manufacturer and Model','strFieldReference' => 'itemname')
                                                , array('strName' => 'PAT Due','strFieldReference' => 'pattestdue_date', 'strConversion'=>'date')
                                                , array('strName' => 'PAT Date','strFieldReference' => 'pattest_date', 'strConversion'=>'date')
                                                , array('strName' => 'PAT Result','strFieldReference' => 'pattest_status', 'strConversion'=>'pat_result')
                                                );
                            
                    $strReportName = "PAT Due";
                    if ($mixStartDate && $mixEndDate)
                    {
                        $strReportName .= " between ".$this->doFormatDateBack($mixStartDate)." and ".$this->doFormatDateBack($mixEndDate);
                    }
                    break;
                case 'totalvalue':
                    $arrResults = $this->reports_model->getTotalValue(
                                                                            $this->session->userdata('objSystemUser')->accountid
                                                                                );
                                                                                
                    $arrFields = array(
                                                array('strName' => 'Category Name', 'strFieldReference' => 'categoryname', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' => true, 'intColSpan'=>0))
                                                , array('strName' => 'Number of Items','strFieldReference' => 'categorytotalitems','arrFooter'=> array('booTotal'=>true, 'booTotalLabel' => false, 'intColSpan'=>0))
                                                , array('strName' => 'Total Purchase Value','strFieldReference' => 'categorytotalvalue', 'strConversion'=>'price','arrFooter'=> array('booTotal'=>true, 'booTotalLabel' => false, 'intColSpan'=>0)
                                                )
                                                , array('strName' => 'Total Current Value','strFieldReference' => 'categorytotalcurrentvalue', 'strConversion'=>'price','arrFooter'=> array('booTotal'=>true, 'booTotalLabel' => false, 'intColSpan'=>0)
                                                )
                                                , array('strName' => 'Total Depreciation','strFieldReference' => 'categorytotaldepreciation', 'strConversion'=>'price','arrFooter'=> array('booTotal'=>true, 'booTotalLabel' => false, 'intColSpan'=>0)
                                                )
                                                );
                    
                    $strReportName = "Total Value of Items";
                    break;
                case 'usertotalvalue':
                    $arrResults = $this->reports_model->getUserTotalValue(
                                                                            $this->session->userdata('objSystemUser')->accountid
                                                                                );
                                                                                
                    $arrFields = array(
                                                array('strName' => 'Name', 'strFieldReference' => 'userfullname', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' => true, 'intColSpan'=>2))
                                                , array('strName' => 'Username','strFieldReference' => 'username')
                                                , array('strName' => 'Number of Items','strFieldReference' => 'usertotalitems', 'arrFooter'=> array('booTotal'=>true, 'booTotalLabel' =>false, 'intColSpan'=>0))
                                                , array('strName' => 'Total Value','strFieldReference' => 'usertotalvalue', 'strConversion'=>'price', 'arrFooter'=> array('booTotal'=>true, 'booTotalLabel' =>false, 'intColSpan'=>0))
                                                );
                    
                    $strReportName = "Total Value of Items by User";
                    break;
                case 'sitetotalvalue':
                    $arrResults = $this->reports_model->getSiteTotalValue(
                                                                            $this->session->userdata('objSystemUser')->accountid
                                                                                );
                                                                                
                    $arrFields = array(
                                        array('strName' => 'Name', 'strFieldReference' => 'sitename')
                                        , array('strName' => 'Number of Items','strFieldReference' => 'sitetotalitems')
                                        , array('strName' => 'Total Value','strFieldReference' => 'sitetotalvalue', 'strConversion'=>'price')
                                        );
                  
                    $strReportName = "Total Value of Items by Site";
                    break;
                case 'useractivity':
                    $arrResults['results'] = $this->getUserActivityData($arrParameters[0]);
                    if (count($arrResults['results']) > 0)
                    {
                        $arrUserData    = $this->users_model->getBasicCredentialsFor($arrParameters[0]);
                        $objUser        = $arrUserData['result'][0];
                    }
                    
                    $arrFields = array(
                                        array('strName' => 'Time', 'strFieldReference' => 'when')
                                        , array('strName' => 'User','strFieldReference' => 'who_did_it')
                                        , array('strName' => 'Action','strFieldReference' => 'action')
                                        , array('strName' => 'Reference','strFieldReference' => 'target')
                                        );
                    $strReportName = "User Activity for ".$objUser->firstname." ".$objUser->lastname." (".$objUser->username.")";
                    break;
                case 'removeditems':

                    $arrResults = $this->reports_model->getRemovedItems( 
                                                        $mixStartDate
                                                        , $mixEndDate
                                                        , $this->session->userdata('objSystemUser')->accountid
                                                        );
                    $arrFields = array(
                                        array('strName' => 'Deleted Date', 'strFieldReference' => 'deleted_date', 'strConversion'=>'datetime','arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>true, 'intColSpan'=>6))
                                        , array('strName' => 'Barcode','strFieldReference' => 'barcode')
                                        , array('strName' => 'Item','strFieldReference' => 'itemname')
                                        , array('strName' => 'Admin','strFieldReference' => 'adminusername')
                                        , array('strName' => 'Super Admin','strFieldReference' => 'superadminusername')
                                        , array('strName' => 'Reason','strFieldReference' => 'statusname')
                                        , array('strName' => 'Value', 'strFieldReference' => 'value', 'strConversion'=>'price', 'arrFooter'=> array('booTotal'=>true, 'booTotalLabel' =>false, 'intColSpan'=>0))
                                        );
                    
                    $strReportName = "Items Removed from System";

                    if ($mixStartDate && $mixEndDate)
                    {
                        $strReportName .= " between ".$this->doFormatDateBack($mixStartDate)." and ".$this->doFormatDateBack($mixEndDate);
                    }

                    break;
                case 'locationtotalvalue':
                    $arrResults = $this->reports_model->getLocationTotalValue(
                                                                            $this->session->userdata('objSystemUser')->accountid
                                                                                );
                                                                                
                    $arrFields = array(
                                                array('strName' => 'Name', 'strFieldReference' => 'locationname', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' => true, 'intColSpan'=>1))
                                                , array('strName' => 'Number of Items','strFieldReference' => 'locationtotalitems', 'arrFooter'=> array('booTotal'=>true, 'booTotalLabel' =>false, 'intColSpan'=>0))
                                                , array('strName' => 'Total Value','strFieldReference' => 'locationtotalvalue', 'strConversion'=>'price', 'arrFooter'=> array('booTotal'=>true, 'booTotalLabel' =>false, 'intColSpan'=>0))
                                                );
                    
                    $strReportName = "Total Value of Items by Location";
                    break;
                case 'fleetcompliance':

                    $arrResults = $this->reports_model->getFleetCompliance($mixStartDate, $mixEndDate);

                    $strReportName = "Fleet Compliance";
                    if ($mixStartDate && $mixEndDate)
                    {
                        $strReportName .= " between ".$this->doFormatDateBack($mixStartDate)." and ".$this->doFormatDateBack($mixEndDate);
                    }
                    $outputfleet = TRUE;

                    break;
                case 'compliancedue':

                    $arrResults = $this->reports_model->getComplianceDue($mixStartDate, $mixEndDate);

                    $strReportName = "Compliance Checks Due";

                    if ($mixStartDate && $mixEndDate)
                    {
                        $strReportName .= " between ".$this->doFormatDateBack($mixStartDate)." and ".$this->doFormatDateBack($mixEndDate);
                    }
                    $outputcompliancedue = TRUE;
                    break;
                case 'compliancecomplete':

                    $arrResults = $this->reports_model->getComplianceComplete($mixStartDate, $mixEndDate);
                    
                    $strReportName = "Compliance Checks Completed";
                    if ($mixStartDate && $mixEndDate)
                    {
                        $strReportName .= " between ".$this->doFormatDateBack($mixStartDate)." and ".$this->doFormatDateBack($mixEndDate);
                    }

                    $outputcompliancecomplete = TRUE;
                    break;
            }
            $booSuccess = true;
            //print_r($arrResults);
            //die();
            
        }
        else
        {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        // load views
        
        $intParameterCount = count($arrParameters);
        if ($arrParameters[$intParameterCount - 1] == 'true')
        {
            $booOutputHtml = true;
        }
        
        

        if ($booPermission && $booSuccess)
        {
            if(!empty($outputfleet)) {
                $this->outputPdfFileFleet($strReportName, $arrResults, $booOutputHtml);
            } elseif(!empty($outputcompliancedue)) {
                $this->outputPdfFileComplianceDue($strReportName, $arrResults, $booOutputHtml);
            } elseif(!empty($outputcompliancecomplete)) {
                $this->outputPdfFileComplianceComplete($strReportName, $arrResults, $booOutputHtml);
            } else {

                $this->outputPdfFile($strReportName, $arrFields, $arrResults['results'], $booOutputHtml);
            }
        }
        else
        {
            $this->load->view('common/header',              $arrPageData);
            $this->load->view('common/system_message',  $arrPageData);
            $this->load->view('common/footer',              $arrPageData);
        }
        
    }

     public function outputPdfFile($strReportName, $arrFields, $arrResults, $booOutputHtml = false)
    {

        error_reporting(0);

        $this->load->model('accounts_model');
        $currency = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);
        $strHtml = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"><html><head><link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"includes/css/report.css\" /></head>";

        $strHtml .= "<body><div>";
        $strHtml .= "<table><tr><td>";
        $strHtml .= "<h1>Youaudit Report</h1>";
        $strHtml .= "<h2>".$strReportName."</h2>";
        $strHtml .= "</td><td class=\"right\">";
//        $strHtml .= "<img alt=\"iworkaudit\" src=\"https://www.iworkaudit.com/includes/img/logo.png\">";
          $logo = 'logo.png';
       
        $strHtml .= "<img alt=\"Youaudit\" src='brochure/logo/" . $logo . "'>";
     
   
        $strHtml .= "</td></tr></table>";



        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead>";
            $strHtml .= "<tr>";

            foreach ($arrFields as $arrReportField)
            {
                $strHtml .= "<th>".$arrReportField['strName']."</th>";
            }

            $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";
        $arrTotals = array();
            foreach ($arrResults as $objItem)
            {

            $strHtml .= "<tr>";

                foreach ($arrFields as $arrReportField)
                {
                    $strHtml .=  "<td>";
                    if (array_key_exists('strConversion', $arrReportField))
                    {
                        switch ($arrReportField['strConversion'])
                        {
                            case 'date':
                                $arrDate = explode('-', $objItem->{$arrReportField['strFieldReference']});
                                if (count($arrDate) >1) {
                                    $strHtml .= $arrDate[2]."/".$arrDate[1]."/".$arrDate[0];
                                }
                                else
                                {
                                    $strHtml .= "Unknown";
                                }
                                break;
                            case 'datetime':
                                $arrDateTime = explode(' ', $objItem->{$arrReportField['strFieldReference']});
                                $strTime = $arrDateTime[1];
                                $arrDate = explode('-', $arrDateTime[0]);
                                $strHtml .= $arrDate[2]."/".$arrDate[1]."/".$arrDate[0]." ".$strTime;
                                break;
                            case 'pat_result':
                                if ($objItem->{$arrReportField['strFieldReference']} === null)
                                {
                                    $strHtml.="-";
                                }
                                else
                                {
                                    if ($objItem->{$arrReportField['strFieldReference']} == 1)
                                    {
                                        $strHtml.="Pass";
                                    }
                                    else
                                    {
                                        $strHtml.="Fail";
                                    }
                                }
                                break;
                            case 'price':
                                $strHtml .= $currency.$objItem->{$arrReportField['strFieldReference']};
                                break;
                        }
                    }
                    else
                    {
                        $strHtml .=  $objItem->{$arrReportField['strFieldReference']};
                    }
                    if (array_key_exists('arrFooter',$arrReportField)
                            && array_key_exists('booTotal',$arrReportField['arrFooter']))
                    {
                        if (array_key_exists($arrReportField['strFieldReference'], $arrTotals))
                        {
                            $arrTotals[$arrReportField['strFieldReference']] += $objItem->{$arrReportField['strFieldReference']};
                        }
                        else
                        {
                            $arrTotals[$arrReportField['strFieldReference']] = $objItem->{$arrReportField['strFieldReference']};
                        }

                    }

                    $strHtml .=  "</td>";
                }

            $strHtml .= "</tr>";
            }
        $strHtml .= "</tbody>";

        $strHtml .= "<tfoot><tr>";

        foreach ($arrFields as $arrReportField)
        {
            if (array_key_exists('arrFooter',$arrReportField))
            {
                if (array_key_exists('booTotal',$arrReportField['arrFooter'])
                        && $arrReportField['arrFooter']['booTotal'])
                {
                    $strHtml .= "<td>";
                    if (array_key_exists('strConversion', $arrReportField)
                            && ($arrReportField['strConversion'] == "price"))
                    {
                        $strHtml .= "&pound;";
                    }
                    $strHtml .= $arrTotals[$arrReportField['strFieldReference']];
                    $strHtml .= "</td>";
                }
                else
                {
                    if (array_key_exists('booTotalLabel',$arrReportField['arrFooter'])
                        && $arrReportField['arrFooter']['booTotalLabel'])
                    {
                        $strHtml .= "<td";
                        if (array_key_exists('intColSpan',$arrReportField['arrFooter'])
                            && ($arrReportField['arrFooter']['intColSpan']>0))
                        {
                            $strHtml .= " colspan=\"".$arrReportField['arrFooter']['intColSpan']."\"";
                        }
                        $strHtml .= " class=\"right\">";
                        $strHtml .= "Totals</td>";
                    }
                }
            }
        }
        $strHtml .= "</tr></tfoot>";


        $strHtml .= "</table>";


        $strHtml .= "<p>Produced by ".$this->session->userdata('objSystemUser')->firstname." ".$this->session->userdata('objSystemUser')->lastname." (".$this->session->userdata('objSystemUser')->username.") on ".date('d/m/Y')."</p>";
        $strHtml .= "</div></body></html>";

        if (!$booOutputHtml)
        {
            $this->load->library('Mpdf');
            $mpdf = new Pdf('en-GB','A4');
            $mpdf->setFooter('{PAGENO} of {nb}');
            $mpdf->WriteHTML($strHtml);
            $mpdf->Output("ictreport_".date('Ymd_His').".pdf","D");
        }
        else
        {
            echo $strHtml;
            //die();
        }
    }
 
     public function outputPdfFileFleet($strReportName, $arrResults, $booOutputHtml = false)
    {
         
        $strHtml = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"><html><head><link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"https://www.iworkaudit.com/includes/css/report.css\" /></head>";
        
        $strHtml .= "<body><div>";
        $strHtml .= "<table><tr><td>";
        $strHtml .= "<h1>Youaudit Report</h1>";
        $strHtml .= "<h2>".$strReportName."</h2>";
        $strHtml .= "</td><td class=\"right\">";
        $logo = 'logo.png';
       
        $strHtml .= "<img alt=\"Youaudit\" src='brochure/logo/" . $logo . "'>";
        $strHtml .= "</td></tr></table>";
       
        
        
        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead>";
            $strHtml .= "<tr>";

                $strHtml .= "<th>Registration No</th>";
                $strHtml .= "<th>Make & Model</th>";
                $strHtml .= "<th>MOT Due Date</th>";
                $strHtml .= "<th>Tax Due Date</th>";
                $strHtml .= "<th>Service Due Date</th>";
                $strHtml .= "<th>Insurance Expiry Date</th>";        
            $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";
        $arrTotals = array();
            foreach ($arrResults as $vehicle)
            {
            $strHtml .= "<tr>";
                $strHtml .= "<td>" . $vehicle['reg_no'] . "</td>";
                $strHtml .= "<td>" . $vehicle['make'] . " " . $vehicle['model'] . "</td>";  
                $strHtml .= "<td>" . $vehicle['mot_due_date'] . "</td>";
                $strHtml .= "<td>" . $vehicle['tax_due_date'] . "</td>";
                $strHtml .= "<td>" . $vehicle['service_due_date'] . "</td>";
                $strHtml .= "<td>" . date('d/m/Y', strtotime($vehicle['insurance_expiration'])) . "</td>";
            $strHtml .= "</tr>";               
            }
        $strHtml .= "</tbody>";
        
        $strHtml .= "<tfoot><tr>";    
        $strHtml .= "</table>";
        
        
        $strHtml .= "<p>Produced by ".$this->session->userdata('objSystemUser')->firstname." ".$this->session->userdata('objSystemUser')->lastname." (".$this->session->userdata('objSystemUser')->username.") on ".date('d/m/Y')."</p>";
        $strHtml .= "</div></body></html>";
        
        if (!$booOutputHtml)
        {
            $this->load->library('Mpdf');
            $mpdf = new Pdf('en-GB','A4');
            $mpdf->setFooter('{PAGENO} of {nb}');
            $mpdf->WriteHTML($strHtml);
            $mpdf->Output("iwareport_".date('Ymd_His').".pdf","D");
        } 
        else
        {
            echo $strHtml;
            die();
        }
    }
    
     public function outputPdfFileComplianceDue($strReportName, $arrResults, $booOutputHtml = false)
    {
         
        $strHtml = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"><html><head><link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"https://www.iworkaudit.com/includes/css/report.css\" /></head>";
        
        $strHtml .= "<body><div>";
        $strHtml .= "<table><tr><td>";
        $strHtml .= "<h1>Youaudit Report</h1>";
        $strHtml .= "<h2>".$strReportName."</h2>";
        $strHtml .= "</td><td class=\"right\">";
       $logo = 'logo.png';
       
        $strHtml .= "<img alt=\"Youaudit\" src='brochure/logo/" . $logo . "'>";
        $strHtml .= "</td></tr></table>";
       
        
        
        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead>";
            $strHtml .= "<tr>";

                $strHtml .= "<th>Barcode</th>";
                $strHtml .= "<th>Make & Model</th>";
                $strHtml .= "<th>Category</th>";
                $strHtml .= "<th>Compliance Check</th>";
                $strHtml .= "<th>Check Due</th>";      
            $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";
        foreach ($arrResults['dueMandatory'] as $key => $container) {
            foreach ($container['tests'] as $test) {
                $strHtml .= "<tr>";
                    $strHtml .= "<td><a href=\"/iwa/items/view/" . $container['item']->itemid . "\">" . $container['item']->barcode . "</a></td>";
                    $strHtml .= "<td>" . $container['item']->manufacturer . " " . $container['item']->model . "</td>";
                    $strHtml .= "<td>" . $container['item']->categoryname . "</td>";
                    $strHtml .= "<td>" . $test['test_type_name'] . "</td>";
                    if($test['due_ts'] != 'Now') {
                        $strHtml .= "<td>" . date('d/m/y', $test['due_ts']) . "</td>";
                    } else {
                        $strHtml .= "<td>Now</td>";
                    }
                $strHtml .= "</tr>";
               }
        }  ?>
                
       <?php foreach ($arrResults['dueOptional'] as $key => $container) {
            foreach ($container['tests'] as $test) {
                $strHtml .= "<tr>";
                    $strHtml .= "<td><a href=\"/iwa/items/view/" . $container['item']->itemid . "\">" . $container['item']->barcode . "</a></td>";
                    $strHtml .= "<td>" . $container['item']->manufacturer . " " . $container['item']->model . "</td>";
                    $strHtml .= "<td>" . $container['item']->categoryname . "</td>";
                    $strHtml .= "<td>" . $test['test_type_name'] . "</td>";
                    if($test['due_ts'] != 'Now') {
                        $strHtml .= "<td>" . date('d/m/y', $test['due_ts']) . "</td>";
                    } else {
                        $strHtml .= "<td>Now</td>";
                    }
                $strHtml .= "</tr>";
               }
        }  
        $strHtml .= "</tbody>";
        
        $strHtml .= "<tfoot><tr>";    
        $strHtml .= "</table>";
        
        
        $strHtml .= "<p>Produced by ".$this->session->userdata('objSystemUser')->firstname." ".$this->session->userdata('objSystemUser')->lastname." (".$this->session->userdata('objSystemUser')->username.") on ".date('d/m/Y')."</p>";
        $strHtml .= "</div></body></html>";
        
        if (!$booOutputHtml)
        {
            $this->load->library('Mpdf');
            $mpdf = new Pdf('en-GB','A4');
            $mpdf->setFooter('{PAGENO} of {nb}');
            $mpdf->WriteHTML($strHtml);
            $mpdf->Output("iwareport_".date('Ymd_His').".pdf","D");
        } 
        else
        {
            echo $strHtml;
            die();
        }
    }
    
     public function outputPdfFileComplianceComplete($strReportName, $arrResults, $booOutputHtml = false)
    {
        $strHtml = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"><html><head><link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"https://www.iworkaudit.com/includes/css/report.css\" /></head>";
        
        $strHtml .= "<body><div>";
        $strHtml .= "<table><tr><td>";
        $strHtml .= "<h1>Youaudit Report</h1>";
        $strHtml .= "<h2>".$strReportName."</h2>";
        $strHtml .= "</td><td class=\"right\">";
       $logo = 'logo.png';
       
        $strHtml .= "<img alt=\"Youaudit\" src='brochure/logo/" . $logo . "'>";
        $strHtml .= "</td></tr></table>";
       
        
        
        $strHtml .= "<table class=\"report\">";
        $strHtml .= "<thead>";
            $strHtml .= "<tr>";

                $strHtml .= "<th>Date</th>";
                $strHtml .= "<th>QR Code</th>";
                $strHtml .= "<th>Serial No</th>";
                $strHtml .= "<th>Manufacturer & Model</th>";
                $strHtml .= "<th>Category</th>"; 
                $strHtml .= "<th>Check Name</th>";
                $strHtml .= "<th>Mandatory</th>";
            $strHtml .= "</tr>";
        $strHtml .= "</thead><tbody>";
        foreach ($arrResults as $test) {
         
                $strHtml .= "<tr>";
                    $date_ex = explode('-', $test['test_date']); $test['test_date'] = $date_ex[2] . "-" . $date_ex[1] . "-" . $date_ex[0];
                 
                    $strHtml .= "<td>" . $test['test_date'] . "</td>";
                    $strHtml .=  "<td><a href=\"/iwa/items/view/" . $test['test_item_id'] . "\">" . $test['barcode'] . "</a></td>";
                    $strHtml .= "<td>" . $test['serial_number'] . "</td>";
                    $strHtml .= "<td>" . $test['manufacturer'] . " " . $test['model'] . "</td>";
                    $strHtml .= "<td>" . $test['name'] . "</td>";
                    $strHtml .= "<td>" . $test['test_type_name'] . "</td>";
                    if($test['test_type_mandatory'] == 1) {
                        $strHtml .= "<td>Yes</td>";
                    } else {
                        $strHtml .= "<td>No</td>";
                    }

                $strHtml .= "</tr>";
               
        } 
               
        $strHtml .= "</tbody>";
        
        $strHtml .= "<tfoot><tr>";    
        $strHtml .= "</table>";
        
        
        $strHtml .= "<p>Produced by ".$this->session->userdata('objSystemUser')->firstname." ".$this->session->userdata('objSystemUser')->lastname." (".$this->session->userdata('objSystemUser')->username.") on ".date('d/m/Y')."</p>";
        $strHtml .= "</div></body></html>";
        
        if (!$booOutputHtml)
        {
            $this->load->library('Mpdf');
            $mpdf = new Pdf('en-GB','A4');
            $mpdf->setFooter('{PAGENO} of {nb}');
            $mpdf->WriteHTML($strHtml);
            $mpdf->Output("iwareport_".date('Ymd_His').".pdf","D");
        } 
        else
        {
            echo $strHtml;
            die();
        }
    }
       
    public function userActivity($intUser)
    {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
                $this->session->set_userdata('strReferral', '/reports/useractivity/'.$intUser.'/');
                redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "User Activity";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
     
        
        $this->load->model('users_model');
        $this->load->model('actions_model');
        
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");
        $booSuccess = false;
        if ($booPermission)
        {
            if ($intUser>0)
            {
                $arrResults = $this->getUserActivityData($intUser);
                
                    $arrUserData    = $this->users_model->getBasicCredentialsFor($intUser);
                    
                               $objUser        = $arrUserData['result'][0];
                              
                $arrFields = array(
                                    array('strName' => 'Time', 'strFieldReference' => 'when')
                                    , array('strName' => 'User','strFieldReference' => 'who_did_it')
                                    , array('strName' => 'Action','strFieldReference' => 'action')
                                    , array('strName' => 'Reference','strFieldReference' => 'target')
                                    );
                $strPdfReference = "userActivity/".$intUser."/";
                $strReportName = "User Activity for ".$objUser->firstname." ".$objUser->lastname." (".$objUser->username.")";
                            
             
                $arrPageData['arrResults']      = $arrResults;
                $arrPageData['arrReportFields'] = $arrFields;
                $arrPageData['strPdfUrl']       = $strPdfReference;
                $arrPageData['strReportName']   = $strReportName;
                    
                $booSuccess = true;
//                    print_r($arrResults);
                    //die();
                
            }
            else
            {
                $arrPageData['arrErrorMessages'][] = "There was a problem generating the report.";
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We're sorry, but the report didn't generate correctly.";
            }
        }
        else
        {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }
  
        // load views
        $this->load->view('common/header',   $arrPageData);
        if ($booPermission && $booSuccess)
        {
            //$this->load->view('common/system_message',  $arrPageData);
                //load the correct view
            $this->load->view('reports/results',          $arrPageData);
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }
        $this->load->view('common/footer',              $arrPageData);
        
        
    }
    
    private function getUserActivityData($intUser)
    {
    
        $this->load->model('actions_model');
        
        $arrResults = array();
        $arrActions = $this->actions_model->getAllForUser(
                                                    $intUser
                                                    , $this->session->userdata('objSystemUser')->accountid
                                                    );
                                                                       
        if (count($arrActions['results']) > 0)
        {
            foreach ($arrActions['results'] as $objAction)
            {
              
                $strObjectName  = $this->actions_model->getObjectName($objAction->table, $objAction->to_what);
                $arrUser        = $this->users_model->getBasicCredentialsFor($objAction->who_did_it);
                $objWhoDidIt    = $arrUser['result'][0];

                $objTempObject = new stdClass();
                $objTempObject->target = $strObjectName;
                $objTempObject->action = $objAction->action;

                $objTempObject->who_did_it = $objWhoDidIt->firstname." ".$objWhoDidIt->lastname." (".$objWhoDidIt->username.")";
                $objTempObject->when = date("d/m/Y H:i:s", strtotime($objAction->when));

                $arrResults[] = $objTempObject;
            }
        }
        
        return $arrResults;
    }
    
    public function generate()
    {
        if (!$this->session->userdata('booUserLogin') && !$this->session->userdata('booInheritedUser'))
        {
                $this->session->set_userdata('strReferral', '/reports/');
                redirect('users/login/', 'refresh');
        }

        // housekeeping
        $arrPageData = array();
        $arrPageData['arrPageParameters']['strSection'] = get_class();
        $arrPageData['arrPageParameters']['strPage'] = "Results";
        $arrPageData['arrSessionData'] = $this->session->userdata;
        $this->session->set_userdata('booCourier', false);
        $this->session->set_userdata('arrCourier', array());
        $arrPageData['arrErrorMessages'] = array();
        $arrPageData['arrUserMessages'] = array();
     
        
        $this->load->model('users_model');
        $this->load->model('locations_model');
        $this->load->model('accounts_model');
        $arrPageData['currency'] = $this->accounts_model->getCurrencySym($this->session->userdata('objSystemUser')->currency);
        $booPermission = $this->users_model->hasPermission($this->session->userdata('objSystemUser')->userid, "Reports.index");
        $booSuccess = false;
        if ($booPermission)
        {
            if ($this->input->post())
            {
                if ($this->input->post('report_type')>0)
                {
                    $this->load->model('reports_model');
                    $arrResults = array();

                    $mixStartDate = $this->input->post('report_startdate') ? $this->doFormatDate($this->input->post('report_startdate')) : false;
                    $mixEndDate = $this->input->post('report_enddate') ? $this->doFormatDate($this->input->post('report_enddate')) : false;

                    switch ((int)$this->input->post('report_type'))
                    {
                        case 1:

                            $arrResults = $this->reports_model->getPatFailures(
                                                                $mixStartDate
                                                                , $mixEndDate
                                                                , $this->session->userdata('objSystemUser')->accountid
                                                                                );
                            $arrFields = array(
                                                array('strName' => 'Barcode', 'strFieldReference' => 'barcode')
                                                , array('strName' => 'Serial Number','strFieldReference' => 'serial_number')
                                                , array('strName' => 'Manufacturer and Model','strFieldReference' => 'itemname')
                                                , array('strName' => 'Failure Date','strFieldReference' => 'pattest_date', 'strConversion'=>'date')
                                                );
                            $strPdfReference = "PATFailures/";
                            $strReportName = "PAT Failures";
                            if ($mixStartDate && $mixEndDate)
                            {
                                $strReportName .= " between ".$this->doFormatDateBack($mixStartDate)." and ".$this->doFormatDateBack($mixEndDate);
                                $strPdfReference = "PATFailures/".$mixStartDate."/".$mixEndDate."/";
                            }
                            //.$this->input->post('report_startdate')." and ".$this->input->post('report_enddate');
                            break;
                        case 2:
                            $arrResults = $this->reports_model->getPatDue(
                                                                $mixStartDate
                                                                , $mixEndDate
                                                                , $this->session->userdata('objSystemUser')->accountid
                                                                                );
                            $arrFields = array(
                                                array('strName' => 'Barcode', 'strFieldReference' => 'barcode')
                                                , array('strName' => 'Serial Number','strFieldReference' => 'serial_number')
                                                , array('strName' => 'Manufacturer and Model','strFieldReference' => 'itemname')
                                                , array('strName' => 'Location','strFieldReference' => 'locationname')
                                                , array('strName' => 'PAT Due','strFieldReference' => 'pattestdue_date', 'strConversion'=>'date')
                                                , array('strName' => 'PAT Date','strFieldReference' => 'pattest_date', 'strConversion'=>'date')
                                                , array('strName' => 'PAT Result','strFieldReference' => 'pattest_status', 'strConversion'=>'pat_result')
                                                );
                            $strPdfReference = "PATDue/";
                            $strReportName = "PAT Due";
                            if ($mixStartDate && $mixEndDate)
                            {
                                $strReportName .= " between ".$this->doFormatDateBack($mixStartDate)." and ".$this->doFormatDateBack($mixEndDate);
                                $strPdfReference = "PATDue/".$mixStartDate."/".$mixEndDate."/";
                            }
                            break;
                        case 3:
                            
                            $arrResults = $this->reports_model->getTotalValue($this->session->userdata('objSystemUser')->accountid);
                            
                            $arrFields = array(
                                                array('strName' => 'Category Name', 'strFieldReference' => 'categoryname', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>true, 'intColSpan'=>0))
                                                , array('strName' => 'Number of Items','strFieldReference' => 'categorytotalitems', 'arrFooter'=> array('booTotal'=>true, 'booTotalLabel' =>false, 'intColSpan'=>0))
                                                , array('strName' => 'Total Purchase Value','strFieldReference' => 'categorytotalvalue', 'strConversion'=>'price','arrFooter'=> array('booTotal'=>true, 'booTotalLabel' => false, 'intColSpan'=>0)
                                                )
                                                , array('strName' => 'Total Current Value','strFieldReference' => 'categorytotalcurrentvalue', 'strConversion'=>'price','arrFooter'=> array('booTotal'=>true, 'booTotalLabel' => false, 'intColSpan'=>0)
                                                )
                                                , array('strName' => 'Total Depreciation','strFieldReference' => 'categorytotaldepreciation', 'strConversion'=>'price','arrFooter'=> array('booTotal'=>true, 'booTotalLabel' => false, 'intColSpan'=>0)
                                                )
                                                );
                            $strPdfReference = "TotalValue/";
                            $strReportName = "Total Value of Items";
                            break;
                        case 4:
                            $arrResults = $this->reports_model->getUserTotalValue($this->session->userdata('objSystemUser')->accountid);
                                                                                
                            $arrFields = array(
                                                array('strName' => 'Name', 'strFieldReference' => 'userfullname', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>true, 'intColSpan'=>2))
                                                , array('strName' => 'Username','strFieldReference' => 'username')
                                                , array('strName' => 'Number of Items','strFieldReference' => 'usertotalitems', 'arrFooter'=> array('booTotal'=>true, 'booTotalLabel' =>false, 'intColSpan'=>0))
                                                , array('strName' => 'Total Value','strFieldReference' => 'usertotalvalue', 'strConversion'=>'price', 'arrFooter'=> array('booTotal'=>true, 'booTotalLabel' =>false, 'intColSpan'=>0))
                                                );
                            $strPdfReference = "UserTotalValue/";
                            $strReportName = "Total Value of Items by User";
                            break;
                        case 5:
                            $arrResults = $this->reports_model->getSiteTotalValue($this->session->userdata('objSystemUser')->accountid);
                                                                                
                            $arrFields = array(
                                                array('strName' => 'Name', 'strFieldReference' => 'sitename', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>true, 'intColSpan'=>0))
                                                , array('strName' => 'Number of Items','strFieldReference' => 'sitetotalitems', 'arrFooter'=> array('booTotal'=>true, 'booTotalLabel' =>false, 'intColSpan'=>0))
                                                , array('strName' => 'Total Value','strFieldReference' => 'sitetotalvalue', 'strConversion'=>'price', 'arrFooter'=> array('booTotal'=>true, 'booTotalLabel' =>false, 'intColSpan'=>0))
                                                );
                            $strPdfReference = "SiteTotalValue/";
                            $strReportName = "Total Value of Items by Site";
                            break;
                        case 6:
                            $arrResults = $this->reports_model->getRemovedItems(
                                                                $mixStartDate
                                                                , $mixEndDate
                                                                , $this->session->userdata('objSystemUser')->accountid
                                                                );
                            $arrFields = array(
                                                array('strName' => 'Deleted Date', 'strFieldReference' => 'deleted_date', 'strConversion'=>'datetime', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>true, 'intColSpan'=>6))
                                                , array('strName' => 'Barcode','strFieldReference' => 'barcode')
                                                , array('strName' => 'Item','strFieldReference' => 'itemname')
                                                , array('strName' => 'Admin','strFieldReference' => 'adminusername')
                                                , array('strName' => 'Super Admin','strFieldReference' => 'superadminusername')
                                                , array('strName' => 'Reason','strFieldReference' => 'statusname')
                                                , array('strName' => 'Value', 'strFieldReference'=> 'value', 'strConversion'=>'price', 'arrFooter'=> array('booTotal'=>true, 'booTotalLabel' =>false, 'intColSpan'=>0))
                                                );
                            $strPdfReference = "RemovedItems/";
                            $strReportName = "Items Removed from System";
                            
                            if ($mixStartDate && $mixEndDate)
                            {
                                $strReportName .= " between ".$this->doFormatDateBack($mixStartDate)." and ".$this->doFormatDateBack($mixEndDate);
                                $strPdfReference = "RemovedItems/".$mixStartDate."/".$mixEndDate."/";
                            }
                            
                            break;
                         case 7:
                            $arrResults = $this->reports_model->getLocationTotalValue($this->session->userdata('objSystemUser')->accountid);
                                                                               
                            $arrFields = array(
                                                array('strName' => 'Name', 'strFieldReference' => 'locationname', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>true, 'intColSpan'=>1))
                                                , array('strName' => 'Number of Items','strFieldReference' => 'locationtotalitems', 'arrFooter'=> array('booTotal'=>true, 'booTotalLabel' =>false, 'intColSpan'=>0))
                                                , array('strName' => 'Total Value','strFieldReference' => 'locationtotalvalue', 'strConversion'=>'price', 'arrFooter'=> array('booTotal'=>true, 'booTotalLabel' =>false, 'intColSpan'=>0))
                                                );
                            $strPdfReference = "LocationTotalValue/";
                            $strReportName = "Total Value of Items by Location";
                            break;
                            

                         case 8:
                            
                            $arrPageData['start_date'] = $mixStartDate;
                            $arrPageData['end_date'] = $mixEndDate;
                            $arrResults = $this->reports_model->getFleetCompliance($mixStartDate, $mixEndDate);

                            $strPdfReference = "FleetCompliance/";
                            $strReportName = "Fleet Compliance";
                             if ($mixStartDate && $mixEndDate)
                             {
                                 $strReportName .= " between ".$this->doFormatDateBack($mixStartDate)." and ".$this->doFormatDateBack($mixEndDate);
                                 $strPdfReference = "FleetCompliance/".$mixStartDate."/".$mixEndDate."/";
                             }
                            $change_view = 'fleetcompliance';
                            break;
                            
                         case 9:
                            
                            $arrPageData['start_date'] = $mixStartDate;
                            $arrPageData['end_date'] = $mixEndDate;
                            $arrResults = $this->reports_model->getComplianceDue($mixStartDate, $mixEndDate);

                            $strPdfReference = "compliancedue/";
                            $strReportName = "Item Compliance Checks Due";
                             if ($mixStartDate && $mixEndDate)
                             {
                                 $strReportName .= " between ".$this->doFormatDateBack($mixStartDate)." and ".$this->doFormatDateBack($mixEndDate);
                                 $strPdfReference = "compliancedue/".$mixStartDate."/".$mixEndDate."/";
                             }

                            $change_view = 'compliancedue';
                            break;
                            
                         case 10:
                            
                            $arrPageData['start_date'] = $mixStartDate;
                            $arrPageData['end_date'] = $mixEndDate;
                            $arrResults = $this->reports_model->getComplianceComplete($mixStartDate, $mixEndDate);
                      
                            $strPdfReference = "compliancecomplete/";
                            $strReportName = "Items with compliance checks completed";
                             if ($mixStartDate && $mixEndDate)
                             {
                                 $strReportName .= " between ".$this->doFormatDateBack($mixStartDate)." and ".$this->doFormatDateBack($mixEndDate);
                                 $strPdfReference = "compliancecomplete/".$mixStartDate."/".$mixEndDate."/";
                             }
                            $change_view = 'compliancecomplete';
                            break;
                        case 11:

                            $arrPageData['location'] = $this->input->post('report_location');
                            $arrResults['results'] = $this->locations_model->getAllItemsForLocation($this->input->post('report_location'), $this->session->userdata('objSystemUser')->accountid, (int)$this->input->post('report_location')==0);
                            $arrFields = array(
                                array('strName' => 'Manufacturer', 'strFieldReference' => 'manufacturer', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>1))
                            , array('strName' => 'Model','strFieldReference' => 'model', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                            , array('strName' => 'Location','strFieldReference' => 'location', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                            , array('strName' => 'Value','strFieldReference' => 'value', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                            , array('strName' => 'Status','strFieldReference' => 'status', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                            , array('strName' => 'Owner','strFieldReference' => 'owner', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                            , array('strName' => 'Barcode','strFieldReference' => 'itembarcode', 'strConversion'=>'price', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                            );

                            $loc = $this->locations_model->getOne($this->input->post('report_location'), $this->session->userdata('objSystemUser')->accountid);

                            $this->load->model('itemstatus_model');
                            if(is_array($arrResults['results'])){
                                foreach ($arrResults['results'] as $key => $value ){
                                    $arrResults['results'][$key]->status = $this->itemstatus_model->getStatus($value->status);
                                }
                            }

                            $this->load->model('users_model');
                            if(is_array($arrResults['results'])){
                                foreach ($arrResults['results'] as $key => $value ){
                                    $owner = $this->users_model->getOne($value->owner, $this->session->userdata('objSystemUser')->accountid);
                                    $owner =  isset($owner['result'][0]) ? $owner['result'][0]->firstname . ' ' . $owner['result'][0]->lastname : '';
                                    $arrResults['results'][$key]->owner = $owner;
                                }
                            }

                            $strPdfReference = "LocationReport/";
                            $location = isset($loc['results'][0])  ? $loc['results'][0]->locationname : 'All';
                            $strReportName = "All items in location: ".$location;
                            break;

                        case 12:
                            $arrPageData['location'] = $this->input->post('report_location');
                            $arrResults['results'] = $this->reports_model->getMissingItems($this->input->post('report_startdate'),$this->input->post('report_enddate'));
                            $arrFields = array(
                                array('strName' => 'Manufacturer', 'strFieldReference' => 'manufacturer', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>1))
                            , array('strName' => 'Model','strFieldReference' => 'model', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                            , array('strName' => 'Location','strFieldReference' => 'name', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                            , array('strName' => 'Value','strFieldReference' => 'value', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                            , array('strName' => 'Audit Date','strFieldReference' => 'completed', 'arrFooter'=> array('booTotal'=>false, 'booTotalLabel' =>false, 'intColSpan'=>0))
                            );

                            $strPdfReference = "missing/";

                            $strReportName = "Audit Missing Items";

                            if ($mixStartDate && $mixEndDate)
                            {
                                $strReportName .= " between ".$this->doFormatDateBack($mixStartDate)." and ".$this->doFormatDateBack($mixEndDate);
                                $strPdfReference = "missing/".$mixStartDate."/".$mixEndDate."/";
                            }

                            break;

                            
                    }
                    if(isset($change_view)) {
                        $arrPageData['arrResults']      = $arrResults;
                    } else {
                        $arrPageData['arrResults']      = $arrResults['results'];
                    }
                    $arrPageData['arrReportFields'] = $arrFields;
                    $arrPageData['strPdfUrl']       = $strPdfReference;
                    $arrPageData['strReportName']   = $strReportName;
                    $start_date = explode('/',$this->input->post('report_startdate'));
                    $arrPageData['startDate'] = $start_date[2].'-'.$start_date[1].'-'.$start_date[0];
                    $end_date = explode('/',$this->input->post('report_enddate'));
                    $arrPageData['endDate'] = $end_date[2].'-'.$end_date[1].'-'.$end_date[0];
                    $booSuccess = true;
                    //print_r($arrResults);
                    //die();
                }
                else
                {
                    $arrPageData['arrErrorMessages'][] = "You didn't pick a report type to generate.";
                    $arrPageData['strPageTitle'] = "System Error";
                    $arrPageData['strPageText'] = "You didn't pick a report type to generate.";
                }
            }
            else
            {
                $arrPageData['arrErrorMessages'][] = "There was a problem generating the report.";
                $arrPageData['strPageTitle'] = "System Error";
                $arrPageData['strPageText'] = "We're sorry, but the report didn't generate correctly.";
            }
        }
        else
        {
            $arrPageData['arrErrorMessages'][] = "You do not have permission to do this.";
            $arrPageData['strPageTitle'] = "Security Check Point";
            $arrPageData['strPageText'] = "Your current user permissions do not allow this action on your account.";
        }

        // load views
        $this->load->view('common/header',              $arrPageData);
        if ($booPermission && $booSuccess)
        {
            //$this->load->view('common/system_message',  $arrPageData);
                //load the correct view
            if(isset($change_view)) {
                switch ($change_view) {
                    case 'fleetcompliance':
                        $this->load->view('reports/fleet',          $arrPageData);
                    break;
                
                    case 'compliancedue':
                        $this->load->view('reports/compliancedue',          $arrPageData);
                    break;
                
                    case 'compliancecomplete' :
                        $this->load->view('reports/compliancecomplete',          $arrPageData);
                    break;  
                }
            } else {
                $this->load->view('reports/results',          $arrPageData);
            }
        }
        else
        {
            $this->load->view('common/system_message',  $arrPageData);
        }
        $this->load->view('common/footer',              $arrPageData);
        
        
    }
    
    public function doFormatDate($strDate)
        {
            if ($strDate != "")
            {
                $arrDate = explode('/', $strDate);
                return $arrDate[2]."-".$arrDate[1]."-".$arrDate[0];
            }
            return NULL;
        }
        
    public function doFormatDateBack($strDate)
        {
            if ($strDate != "")
            {
                $arrDate = explode('-', $strDate);
                return $arrDate[2]."/".$arrDate[1]."/".$arrDate[0];
            }
            return "";
        }    
         
}