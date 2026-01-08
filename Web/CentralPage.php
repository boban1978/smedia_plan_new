<?php
ob_start();
session_start();
require_once __DIR__.'/../init.php';
define('SR',50);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Smedia</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="shortcut icon" href="<?php echo HOME_ADDRESS;?>favicon.ico?v=1" />
        <link rel="stylesheet" type="text/css" href="ExtJS/resources/css/ext-all.css?v=<?php echo SR;?>" />
        <link rel="stylesheet" type="text/css" href="ExtJS/ux/calendar/resources/css/extensible-all.css?v=<?php echo SR;?>" />
        <link rel="stylesheet" type="text/css" href="ExtJS/ux/scheduler/resources/css/sch-all.css?v=<?php echo SR;?>" />
        <link rel="stylesheet" type="text/css" href="ExtJS/ux/grid/css/RowActions.css?v=<?php echo SR;?>" />
        <link rel="stylesheet" type="text/css" media="screen" href="CSS/general.css?v=<?php echo SR;?>" />
        <link rel="stylesheet" type="text/css" media="screen" href="CSS/icons.css?v=<?php echo SR;?>" />
        <link rel="stylesheet" type="text/css" media="screen" href="CSS/calendar.css?v=<?php echo SR;?>" />
        <script type="text/javascript" src="ExtJS/ext-all.js?v=<?php echo SR;?>"></script>
        <script type="text/javascript" src="ExtJS/ux/calendar/lib/extensible-all.js?v=<?php echo SR;?>"></script>
        <script type="text/javascript" src="ExtJS/ux/calendar/data/Events.js?v=<?php echo SR;?>"></script>
        <script type="text/javascript" src="ExtJS/ux/calendar/data/Calendars.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/MediaPlan/CalendarOverrides.js?v=<?php echo SR;?>"></script>
        <!--<script type="text/javascript" src="ExtJS/ux/calendar/pkgs/calendar-debug.js"></script>-->
        <script type="text/javascript" src="ExtJS/locale/ext-lang-sr.js?v=<?php echo SR;?>"></script>
        <script type="text/javascript" src="ExtJS/ux/calendar/src/locale/extensible-lang-sr.js?v=<?php echo SR;?>"></script>
        <script type="text/javascript" src="ExtJS/ux/grid/RowActions.js?v=<?php echo SR;?>"></script>
        <script type="text/javascript" src="ExtJS/ux/grid/CheckBoxList.js?v=<?php echo SR;?>"></script>
        <script type="text/javascript" src="ExtJS/ux/scheduler/sch-all-debug.js?v=<?php echo SR;?>"></script>
        
        <script language="javascript" type="text/javascript" src="../Web/Lang/lang-default.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="../Web/Common/Common.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="../Web/Common/Format.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="../Web/Common/Icons.js?v=<?php echo SR;?>"></script>

        
        <script language="javascript" type="text/javascript" src="CentralPageManager.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/AdministrationManager.js?v=<?php echo SR;?>"></script>
        
        <script language="javascript" type="text/javascript" src="MediaPlan/Scheduler/SchedulerView.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Scheduler/Util.js?v=<?php echo SR;?>"></script>
		<script language="javascript" type="text/javascript" src="MediaPlan/Scheduler/SchedulerBlockDetails.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Scheduler/SchedulerBlockAddMoveDialog.js?v=<?php echo SR;?>"></script>
        
        <script language="javascript" type="text/javascript" src="MediaPlan/MediaPlan/MediaPlanBlockDetailsGrid.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/MediaPlan/MediaPlanBlockDetailsDialog.js?v=<?php echo SR;?>"></script>
        
        <script language="javascript" type="text/javascript" src="MediaPlan/MediaPlanToolBar.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientsPanel.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientsFilter.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientsGrid.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientDetails/ClientDetailsWindow.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientDetails/ClientDetailsForm.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientDetails/ClientDetailsCampaignesGrid.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientDetails/ClientDetailsCampaignesDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientDetails/ClientDetailsImportantDatesGrid.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientDetails/ClientDetailsImportantDatesDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientDetails/ClientDetailsContactsGrid.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientDetails/ClientDetailsContactsDialog.js?v=<?php echo SR;?>"></script>
		        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientDetails/ClientDetailsBrendGrid.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientDetails/ClientDetailsBrendDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientDetails/ClientDetailsCommunicationsGrid.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientDetails/ClientDetailsCommunicationsDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientDetails/ClientDetailsOffersGrid.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Clients/ClientDetails/ClientDetailsOffersDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Agencies/AgenciesPanel.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Agencies/AgenciesFilter.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Agencies/AgenciesGrid.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Campaignes/CampaignesPanel.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Campaignes/CampaignesFilter.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Campaignes/CampaignesGrid.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Reports/ReportsPanel.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Reports/ReportsFinancialReportPanel.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Reports/ReportsOffersReportPanel.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Reports/ReportsCommunicationReportPanel.js?v=<?php echo SR;?>"></script>

        <script language="javascript" type="text/javascript" src="MediaPlan/Sporno/SpornoPanel.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Sporno/SpornoSporneReklamePanel.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Sporno/SpornoVisakReklamePanel.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="MediaPlan/Sporno/SpornoCronsPanel.js?v=<?php echo SR;?>"></script>


        
        <script language="javascript" type="text/javascript" src="UserDetails/UserDetailsDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="UserDetails/UserPassChangeDialog.js?v=<?php echo SR;?>"></script>
        
        <script language="javascript" type="text/javascript" src="Administration/UserAdministration/UserAdministrationDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/UserAdministration/UserAdministrationFilter.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/UserAdministration/UserAdministrationGrid.js?v=<?php echo SR;?>"></script>
        
        <script language="javascript" type="text/javascript" src="Administration/RoleAdministration/RoleAdministrationDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/RoleAdministration/RoleAdministrationFilter.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/RoleAdministration/RoleAdministrationGrid.js?v=<?php echo SR;?>"></script>
        
        <script language="javascript" type="text/javascript" src="Administration/ClientAdministration/ClientAdministrationDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/ClientAdministration/ClientAdministrationFilter.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/ClientAdministration/ClientAdministrationGrid.js?v=<?php echo SR;?>"></script>
        
        <script language="javascript" type="text/javascript" src="Administration/AgencyAdministration/AgencyAdministrationDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/AgencyAdministration/AgencyAdministrationFilter.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/AgencyAdministration/AgencyAdministrationGrid.js?v=<?php echo SR;?>"></script>
        
        <script language="javascript" type="text/javascript" src="Administration/ContractTypeAdministration/ContractTypeAdministrationDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/ContractTypeAdministration/ContractTypeAdministrationFilter.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/ContractTypeAdministration/ContractTypeAdministrationGrid.js?v=<?php echo SR;?>"></script>
        
        <script language="javascript" type="text/javascript" src="Administration/ActivityTypeAdministration/ActivityTypeAdministrationDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/ActivityTypeAdministration/ActivityTypeAdministrationFilter.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/ActivityTypeAdministration/ActivityTypeAdministrationGrid.js?v=<?php echo SR;?>"></script>
        
        <script language="javascript" type="text/javascript" src="Administration/BlockAdministration/BlockAdministrationDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/BlockAdministration/BlockAdministrationGrid.js?v=<?php echo SR;?>"></script>
        
        <script language="javascript" type="text/javascript" src="Administration/PriceListAdministration/PriceListAdministrationDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/PriceListAdministration/PriceListAdministrationGrid.js?v=<?php echo SR;?>"></script>
		
		<script language="javascript" type="text/javascript" src="Administration/ServicePriceAdministration/ServicePriceAdministrationDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/ServicePriceAdministration/ServicePriceAdministrationFilter.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/ServicePriceAdministration/ServicePriceAdministrationGrid.js?v=<?php echo SR;?>"></script>
		
		<script language="javascript" type="text/javascript" src="Administration/VoiceAdministration/VoiceAdministrationDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/VoiceAdministration/VoiceAdministrationFilter.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/VoiceAdministration/VoiceAdministrationGrid.js?v=<?php echo SR;?>"></script>
		
		<script language="javascript" type="text/javascript" src="Administration/StationAdministration/StationAdministrationDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/StationAdministration/StationAdministrationFilter.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/StationAdministration/StationAdministrationGrid.js?v=<?php echo SR;?>"></script>
		
		<script language="javascript" type="text/javascript" src="Administration/StationProgram/StationProgramDialog.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/StationProgram/StationProgramFilter.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/StationProgram/StationProgramGrid.js?v=<?php echo SR;?>"></script>
		
        <script language="javascript" type="text/javascript" src="Administration/CampaigneTemplates/CampaigneTemplatesFilter.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/CampaigneTemplates/CampaigneTemplatesGrid.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/CampaigneTemplates/CampaigneTemplatesDialog.js?v=<?php echo SR;?>"></script>
        
        <script language="javascript" type="text/javascript" src="Administration/WeekTemplates/WeekTemplatesFilter.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/WeekTemplates/WeekTemplatesGrid.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="Administration/WeekTemplates/WeekTemplatesDialog.js?v=<?php echo SR;?>"></script>
        
        <script language="javascript" type="text/javascript" src="Administration/CampaigneManual/CampaigneManualDialog.js?v=<?php echo SR;?>"></script>


        <script language="javascript" type="text/javascript" src="JS/actb/actb.js?v=<?php echo SR;?>"></script>
        <script language="javascript" type="text/javascript" src="JS/actb/common.js?v=<?php echo SR;?>"></script>

        <script>
            var home_address='<?php echo HOME_ADDRESS;?>';
        </script>

     </head>
    <body>     
    </body>
</html>
<? ob_flush(); ?>