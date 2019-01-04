<?php
$config['group_app_mapping'] = array(
                                'rm_app' => array(RELATIONSHIP_MANAGER)
                            );

$config['acl'] = array(
    'logout'=>array(RELATIONSHIP_MANAGER),
    'showCheckinOrCheckout'=>array(RELATIONSHIP_MANAGER),
    'markCheckIn'=>array(RELATIONSHIP_MANAGER),
    'markCheckOut'=>array(RELATIONSHIP_MANAGER),
    'addCommentsBeforeCheckout'=>array(RELATIONSHIP_MANAGER),
    'createRetDistNewLeads'=>array(RELATIONSHIP_MANAGER),
    'masterDistributor'=>array(RELATIONSHIP_MANAGER),
    'masterRetailer'=>array(RELATIONSHIP_MANAGER),
    'masterStatus'=>array(RELATIONSHIP_MANAGER),
    'masterServices'=>array(RELATIONSHIP_MANAGER),
    'myAllLead'=>array(RELATIONSHIP_MANAGER),
    'updateFollowUpLead'=>array(RELATIONSHIP_MANAGER),
    'retailerDistributorVisit'=>array(RELATIONSHIP_MANAGER),
    'dailyReport'=>array(RELATIONSHIP_MANAGER),
    'feedback'=>array(RELATIONSHIP_MANAGER),
    'getChildRM'=>array(RELATIONSHIP_MANAGER),
    'updateRMVisit'=>array(RELATIONSHIP_MANAGER),
    'dashboard'=>array(RELATIONSHIP_MANAGER),
    'dashboardDistributor'=>array(RELATIONSHIP_MANAGER),
    'dashboardRetailer'=>array(RELATIONSHIP_MANAGER)
);


$config['app_names'] = array('rm_app');
$config['app_versions_force_upgrade'] = array('rm_app'=>'1');

//example :- array('2018-06-20');
$config['holiday_date'] = array();

$config['whitelist_apis']= array('authenticate','verifyOTPAuthenticate','resendOTPAuthenticate','getOTPforTest','insertTrackingLog');


$config['api_param_counts']= array('authenticateValidation'=>9,'verifyOTPAuthenticateValidation'=>13,'resendOTPAuthenticateValidation'=>5,'logoutValidation'=>8,'markCheckInValidation'=>10,'markCheckOutValidation'=>13,'addCommentsBeforeCheckoutValidation'=>6,'createRetDistNewLeadsValidation'=>21,'masterDistributorValidation'=>5,'masterRetailerValidation'=>6,'masterStatusValidation'=>5,'masterServicesValidation'=>5,'myAllLeadValidation'=>5,'updateFollowUpLeadValidation'=>10,'retailerDistributorVisitValidation'=>14,'dailyReportValidation'=>5,'feedbackValidation'=>6,'getChildRMValidation'=>5,'updateRMVisitValidation'=>8,'dashboardValidation'=>8,'dashboardDistributorValidation'=>9,'dashboardRetailerValidation'=>9);


$config['attendance']= array('checkin_time'=>'06:00:00','checkout_time'=>'22:00:00','halt_distance'=>'10');