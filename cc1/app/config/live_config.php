<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//cyberplate live Credentials
define('CYBER_PASSWORD','pay1cyber2018');
define('CYBER_PASSWORD1','pay1cyber2018');
define('CYBER_SD','104996');
define('CYBER_AP','105117');
define('CYBER_OP','105120');
define('CYBER_PUBKEY','bank_prod.key');
define('CYBER_SECKEY','secret_cp.key');
define('CYBERP_URL','https://in.cyberplat.com/cgi-bin/');
define('CYBERP_BAL_URL','https://in.cyberplat.com/cgi-bin/mts_espp/mtspay_rest.cgi');

//paytronics live Credentials
define('PAYT_USER_CODE','1250612001');
define('PAYT_PASSWORD','P@YPASSP@YON1');
define('PAYT_URL','http://123.108.34.245/Integration/RechargeService');

// mypay API credential
define('MYPAYURL',"http://mps1.in/_api/_apiprocs.aspx");
define('MYPAYUSER','9833770118');
define('MYPAYPASSWD','EEY832');


//PG Payu Settings
define('PAYU_SERVICE_URL','https://info.payu.in/merchant/postservice');
define('PAYU_KEY','2gZB8s');
define('PAYU_SALT','75iFRIB4');
define('PAYU_URL','https://secure.payu.in/_payment');
define('PAYU_SUCCESS_URL','https://panel.pay1.in/apis/payu_status/success');
define('PAYU_FAILURE_URL','https://panel.pay1.in/apis/payu_status/failure');
define('PAYU_ASSETS_URL', 'https://panel.pay1.in/');
define('PAYU_WEB_SERVICE', 'https://secure.payu.in/merchant/postservice.php?form=2');
define('PANEL_PAYU_SEAMLESS_URL','https://panel.pay1.in/apis/pgPayUSeamless');


/* Money transfer */
define('GI_URL_MNYTFR','http://202.54.157.77/wsNPCI/IMPSMethods.asmx');
define('GI_TERMINALID_MNYTFR','100145');
define('GI_LOGINKEY_MNYTFR','8055675478');
define('GI_MERCHANTID_MNYTFR',145);

/* ANAND API Parameters */
define('ANAND_MOB','7666888676'); //retailer id of b2c
define('ANAND_PIN','5263');

/* APNA API Parameters */
define('APNA_USERNAME','MB10381228'); //retailer id of b2c
define('APNA_PASSWD','p1@pay1');

/* MAGIC PAY API Parameters */
define('MAGIC_USERNAME','9867213953'); //retailer id of b2c
define('MAGIC_PASSWD','pay12627');

/* RIO API Parameters */
define('RIO_USERNAME','9833770118'); //retailer id of b2c
define('RIO_PASSWD','payone321');

/* RIO2 API Parameters */
define('RIO2_USERNAME','7710976244');
define('RIO2_PASSWD','payone765');

/* PayIntegra API Parameters */
define('GEM_USERNAME','10218'); //retailer id of b2c
define('GEM_PASSWORD','HWIFLZNYQORSLTCXSRUK');

define('GI_URL','http://api.hermes-it.in/airlines/hermesmobile.asmx');
define('GI_LOGINID','Pay');
define('GI_PASSWORD','Pay123');

/* Recharge Kit API Parameters */
define('RKIT_AGTCODE','919833770118'); //retailer id of b2c
define('RKIT_AUTH','1@569854@');
define('RKIT_USER','1177');

/* Quicknpay recharge API parameter */
define('QUICKNPAY_ID','1527');
define('QUICKNPAY_PWD','payqck1188');

/* SMSdaak recharge API token */
define('SMSDAAK_TOKEN','8e0b23c41797b9c806f7496c5a7a8db2');
define('SMSDAAK_TOKEN1','8e0b23c41797b9c806f7496c5a7a8db2');
/* Recharge A2Z API Parameters */
define('A2Z_AGTCODE','1003');
define('A2Z_AUTH','12345');

/*Ambikaroam Credentials*/
define('AMBIKAROAM_UN','8879137555');
define('AMBIKAROAM_PWD','5821');

/*Bigshoprecharge credentials*/
define('BIGSHOPREC_UN','9619802428');
define('BIGSHOPREC_PWD','pay@123');
define('BIGSHOPREC_VENDOR_ID','156');
define('BIGSHOPREC_RECHARGE_PIN','1234');
define('BIGSHOPREC_RECHARGE_URL','Recharge');
define('BIGSHOPREC_BAL_URL','GetBalance');
define('BIGSHOPREC_STATUS_URL','RechargeStatus');

/* Recharge Join recharge API Parameters */
define('JOINREC_ID','18');
define('JOINREC_PWD','78611');


// cbz credentials
define('CBZ_RECHARGE_URL','http://103.8.217.61/php/apirecharge.php');
define('CBZ_REC_USERNAME','RT59524449');
define('CBZ_REC_PASSWORD','123456');
define('CBZ_REC_KEY','cell1pay1825money');
define('CBZ_BAL_URL','http://103.8.217.61/php/api_report.php');
define('CBZ_TRANS_URL','http://103.8.217.61/php/api_report.php');

//bimco 
define('BIMCO_APIKEY', 'F37Z0*Z*DNI0YE*P');
define('BIMCO_USERNAME', 60170);

//rajan
define('RAJAN_USERNAME', 'MINDS1');
define('RAJAN_PASSWORD', 573168);

//rdu
define('RDU_RECHARGE_URL','http://server.rduniya.com:8080/HttpServletClient/HttpClientServlet');
define('RDU_REC_USERNAME','MATPL');
define('RDU_REC_PASSWORD','nu@k06');
define('RDU_TRANS_URL','http://server.rduniya.com:8080/HttpServletClient/HttpRechargeStatusServlet');

//uva
define('UVA_REC_UNIQID','496091');
define('UVA_REC_USERID','8879647666');
define('UVA_RECHARGE_URL','http://wtopup.uvapoint.com/mobile.aspx');
define('UVA_DTHRECHARGE_URL','http://wtopup.uvapoint.com/dth.aspx');
define('UVA_BAL_URL','http://wtopup.uvapoint.com/credit.aspx');
define('UVA_TRANS_URL','http://wtopup.uvapoint.com/uvacls.aspx');

//uni
define('UNI_RECHARGE_URL','http://182.19.0.60/');
define('UNI_BAL_URL','http://182.19.0.60/gbs.php');
define('UNI_CNAME','ashish');
define('UNI_MNUMBER','9004387418');
define('UNI_TRANS_URL','http://182.19.0.60/status.php');

//anand
define('ANAND_RECHARGE_URL','http://24onlinerecharge.com/ReCharge/APIs.aspx');
define('ANAND_TRANS_URL','www.24onlinerecharge.com/ReCharge/APIs.aspx');

//apna
define('APNA_RECHARGE_URL','http://apnaeasy.net.in/recharge/api');
define('APNA_BAL_URL','http://apnaeasy.net.in/recharge/balance');
define('APNA_TRANS_URL','http://apnaeasy.net.in/recharge/status');

//magic
define('MAGIC_RECHARGE_URL','http://www.magicpay.in/recharge.ashx');
define('MAGIC_BAL_URL','http://www.magicpay.in/getBalance.ashx');
define('MAGIC_TRANS_URL','http://www.magicpay.in/getStatus.ashx');

//rio
define('RIO_RECHARGE_URL','http://117.218.64.210/rechargeapi/recharge.ashx');
define('RIO_BAL_URL','http://117.218.64.210/rechargeapi/getBalance.ashx');
define('RIO_TRANS_URL','http://117.218.64.210/rechargeapi/getStatus.ashx');

//ro2
define('RIO2_RECHARGE_URL','http://117.232.99.162/rechargeapi/recharge.ashx');
define('RIO2_BAL_URL','http://117.232.99.162/rechargeapi/getBalance.ashx');
define('RIO2_TRANS_URL','http://117.232.99.162/rechargeapi/getStatus.ashx');

//gem
define('GEM_RECHARGE_URL','http://180.179.153.59/RechargeService');
define('GEM_BAL_URL','http://180.179.153.59/PartnerBalance');
define('GEM_TRANS_URL','http://180.179.153.59/RealStatus');

//durga
define('DURGA_RECHARGE_URL','http://117.218.206.47/');
define('DURGA_BAL_URL','http://117.218.206.47/getbalance.php');
define('DURGA_TRANS_URL','http://117.218.206.47/status.php');
define('DURGA_UID','10');
define('DURGA_MNUMBER','7666888676');

//rkit
define('RKIT_RECHARGE_URL','http://qubedns.co.in/apinew/recharge.asp');
define('RKIT_BAL_URL','http://qubedns.co.in/apinew/bal.asp');
define('RKIT_TRANS_URL','http://qubedns.co.in/apinew/tranreq.asp');

//a2z
define('A2Z_RECHARGE_URL','http://realrechargeit.net/apinew/recharge.asp');
define('A2Z_BAL_URL','http://realrechargeit.net/api/bal.asp');
define('A2Z_TRANS_URL','http://realrechargeit.net/apinew/tranreq.asp');

//join2rec
define('JOINREC_RECHARGE_URL','http://joinrecharge.com/reseller/FlexiRechargeAPI.php');
define('JOINREC_BAL_URL','http://joinrecharge.com/reseller/ResellerBalanceAPI.php');
define('JOINREC_TRANS_URL','http://joinrecharge.com/reseller/RechargeStatusAPI.php');

//smstadka
define('SMSDAAK_TRANS_URL','https://www.instantpay.in/ws/api/getMIS');
define('SMSDAAK_RECHARGE_URL','https://www.instantpay.in/ws/api/transaction');
define('SMSDAAK_BAL_URL','https://www.instantpay.in/ws/api/checkwallet');

//hitech
define('HITECH_BAL_URL','http://hitechrecharge.com/APIs/ApiReqDisplay.aspx');
define('HITECH_TRANSL_URL','http://hitechrecharge.com/APIs/ApiReqdisplay.aspx');
//define('HITECH_RECHARGE_URL','hitechrecharge.com/apis/RecrgRequest.aspx');
define('HITECH_RECHARGE_URL','hitechrecharge.com/APIs/KEyMaster.aspx');
define('HITECH_USERID','1169');
define('HITECH_PASSWORD','12345');
define('HITECH_MNO','919833770118');

//aporec
define('APOREC_BAL_URL','http://apiws.apionlinerecharge.com/APIGetbalance.aspx');
define('APOREC_USERID','16');
define('APOREC_USER_NAME','APIPay1');
define('APOREC_PASSWORD','J3dxrHy8cYED5S5mVmPXeQ==');
define('APOREC_RECHARGE_URL','http://apiws.apionlinerecharge.com/APIOnlineRecharge.aspx');
define('APOREC_TRANS_URL', 'http://apiws.apionlinerecharge.com/APICheckStatus.aspx');


//ppi
define('PPI_RECHARGE_URL','http://107.22.174.199/recharges/ppiApi');
define('PPI_NAMESPACE_URL','http://paypointindia.com/');


//b2c
define('B2C_TRANS_URL','https://b2c.pay1.in/index.php/api_new/action/actiontype/check_transaction/api/true');
define('B2C_REVERSAL_DECLINE_URL','https://b2c.pay1.in/index.php/api_new/action/actiontype/resolve_complain/api/true');
define('B2C_REFILL_URL','https://b2c.pay1.in/index.php/api_new/action/actiontype/refillwallet/api/true');
define('B2C_PULLBACK_URL','http://b2c.pay1.in/index.php/api_new/action/actiontype/pullback/api/true');

//practic
define('PRACTIC_RECHARGE_URL','http://rr.apistore.in/RechargeRequest.aspx');
define('PRACTIC_TRANS_URL','http://client.apistore.in/rstatus.aspx');
define('PRACTIC_BAL_URL','http://rr.apistore.in/AccountBalance.aspx');
define('PRACTIC_USERID','39');
define('PRACTIC_PASSWORD','mi66@4vd7k9u');
define('PRACTIC_KEY','RP11');



//simple
define('SIMPLE_RECHARGE_URL','http://rechargeserver.biz/api_users/recharge');
define('SIMPLE_BAL_URL','http://www.rechargeserver.biz/api_users/balance');
define('SIMPLE_TRANS_URL','http://www.rechargeserver.biz/api_users/status/restatus');
define('SIMPLE_USERID','70032');
define('SIMPLE_PASSWORD','779762');

//manglam
define('MANGALAM_RECHARGE_URL','http://www.mangalamservice.com/api_users/recharge');
define('MANGALAM_BAL_URL','http://www.mangalamservice.com/api_users/balance');
define('MANGALAM_TRANS_URL','http://www.mangalamservice.com/api_users/status');
define('MANGALAM_USERID','7034');
define('MANGALAM_PASSWORD','560271');

//bulk
define('BULK_RECHARGE_URL','http://148.251.44.130/api/recharge');
define('BULK_BAL_URL','http://148.251.44.130/api/balance');
define('BULK_TRANS_URL','http://148.251.44.130/api/check');
define('BULK_USERID','500007');
define('BULK_PASSWORD','4od6o2s9');

//bimco
define('BIMCO_RECHARGE_URL','http://member.apiworld.in/Api_World_Transaction.aspx');
define('BIMCO_BAL_URL','http://member.apiworld.in/Wallet_Balance.aspx');
define('BIMCO_TRANS_URL','http://member.apiworld.in/TransactionStatus.aspx');

//rajan
define('RAJAN_RECHARGE_URL','http://api.rajaneservicesapi.com:88/');
define('RAJAN_BAL_URL','http://api.rajaneservicesapi.com:88/balance');
define('RAJAN_TRANS_URL','http://api.rajaneservicesapi.com:88/Status');

//ditto
define('DITTO_RECHARGE_URL','http://crm.dittotv.com/pay1request.aspx');
define('DITTO_SOAP_URL','http://crm.dittotv.com/service/ditto.asmx');

//ussd credentials 247
define('USSD_VENDOR_247_URL','http://www.24x7sms.com/');
define('V247_USSD_URL','http://smsapi.24x7sms.com/api_2.0/SendUSSD.aspx');
define('V247_USSD_APIKEY','sC44obBySX5');
define('V247_USSD_UID','286');
define('V247_USSD_SERVICENAME','USSD');
define('v247_MOBILE_DETAIL_URL','http://www.24x7sms.com/checknumber.aspx');

//indicore
define('INDICORE_RECHARGE_URL','http://api.indicore.net/'); //indicore URL
define('INDICORE_KEY','8dc266faabd318a024dd67b52ef29c108f7aa9ff3b3e76bf38856da59f04d942'); //indicore Key

/*Swamiraj Credentials*/
define('SWAMIRAJ_RECHARGE_URL','http://www.swamiraj.in/api_request.php');
define('SWAMIRAJ_BALANCE_URL','http://www.swamiraj.in/rpanel/check_balance.php');
define('SWAMIRAJ_STATUS_URL','http://www.swamiraj.in/recharge_status.php');
define('SWAMIRAJ_UN','9892378644');
define('SWAMIRAJ_PWD','KOTZ');
define('SWAMIRAJ_VENDOR_ID',135);


/*Ambika Credentials*/
define('AMBIKA_RECHARGE_URL','http://ambikamultiservices.com/API/NewAPIService.aspx');
define('AMBIKA_BALANCE_URL','http://ambikamultiservices.com/API/NewAPIService.aspx');
define('AMBIKA_STATUS_URL','http://ambikamultiservices.com/API/NewAPIService.aspx');
define('AMBIKA_UN','9867213953');
define('AMBIKA_PWD','4172');
define('AMBIKA_VENDOR_ID',149); // id depends on vendor table id

//gitech
define('GITECH_NAMESPACE','http://tempuri.org/HERMESAPI/HermesMobile/');
define('GITECH_NAMESPACE1','http://tempuri.org/WsHermes/Service1');
/*GITECH credentials*/
//GITECH

define('GITECH_LOGINID','Pay');
define('GITECH_PASSWORD','apiPay');
define('GITECH_RECHARGE_URL','http://api.hermes-it.in/mobile/hermesmobile.svc/JSONService/GetRechargeDone');
define('GITECH_BILLPAYMENT_URL','http://api.hermes-it.in/mobile/hermesmobile.svc/JSONService/GetBillPaymentDone');
define('GITECH_BAL_URL','http://api.hermes-it.in/mobile/hermesmobile.svc/JSONService/getAgentCreditBalance');
define('GITECH_TRANS_URL','http://api.hermes-it.in/mobile/hermesmobile.svc/JSONService/GetTransactionStatus');

/*speedrecharge credentials*/
define('SPEEDREC_RECHARGE_URL','DoRecharge');
define('SPEEDREC_BALANCE_URL','MyBalance');
define('SPEEDREC_STATUS_URL','CheckStatus');
define('SPEEDREC_UN','9867213953');
define('SPEEDREC_PWD','C5C009');
define('SPEEDREC_VENDOR_ID',152);
//payrecharge
define('PAY_RECHARGE_URL','http://103.231.32.126:86/');
define('PAY_RECHARGE_ID',4);

//shivaidea
define('SHIVA_IDEA_RECHARGE_URL','http://www.simpleapi.co.in/recharge.ashx');
define('SHIVA_IDEA_STATUS_URL','http://www.simpleapi.co.in/getStatus.ashx');
define('SHIVA_IDEA_BALANCE_URL','http://www.simpleapi.co.in/getBalance.ashx');
define('SHIVA_IDEA_USERID','8879647661');
define('SHIVA_IDEA_PASSWORD','8879647661');

// indiarecharge
define('INDIARECHARGE_USERNAME','Pay1');
define('INDIARECHARGE_PASSWORD','2627');
define('INDIARECHARGE_PIN','1234');
define('INDIARECHARGE_MOB','9867213953');
define('INDIARECHARGE_URL','http://www.indiarechargestore.com/ReCharge/APIs.aspx');
define('INDIARECHARGE_VENDOR_ID','145');

/*maxrecharge Credentials*/
define('MAXRECHARGE_RECHARGE_URL','MobileRecharge');
define('MAXRECHARGE_BALANCE_URL','GetBalance');
define('MAXRECHARGE_STATUS_URL','GetTransactionStatus');
define('MAXRECHARGE_UN','9867213953');
define('MAXRECHARGE_PWD','74027071');
define('MAXRECHARGE_XMLPWD','7113');
define('MAXRECHARGE_VENDOR_ID',142);

//pay1Jio Retailer
define('PAY1JIO_VENDORID','144');
define('PAY1JIO_MINBAL','10');
define('PAY1JIO_APIKEY','l7xx44140427a59d4297abee5e0d1e91d473');

/*A1REC Credentials */
define('A1REC_RECHARGE_URL','http://www.a1rechargesolutions.com/apiservice.asmx/Recharge');
define('A1REC_TOKEN','f02c1498a26340f88b77ab5746f2d01b');
define('A1REC_BALANCE_URL','http://www.a1rechargesolutions.com/apiservice.asmx/GetBalance');
define('A1REC_STATUS_URL','http://www.a1rechargesolutions.com/apiservice.asmx/GetRechargeStatus');
define('A1REC_VENDOR_ID',150);

/* UNREC RECHARGE */
define('UNREC_RECHARGE_URL','http://api.rechargeunlimited.in/odata/RechargeByAPI');
define('UNREC_BALANCE_URL','http://api.rechargeunlimited.in/odata/GetUserBalance');
define('UNREC_STATUS_URL','http://api.rechargeunlimited.in/odata/RechargeStatusCheck');
define('UNREC_VENDOR_ID',151);
define('UNREC_USERID','A15');
define('UNREC_PASSWORD','70021'); 


/*Indiaoneonline credentials*/
define('INDIAONE_UN','pay1');
define('INDIAONE_PWD','ae0422');
define('INDIAONE_VENDOR_ID','157');
define('INDIAONE_URL','http://indiaoneonline.com/api/ApiServices/GetService');

/*Emoneygroup credentials*/
define('EMONEY_UN','9867213953');
define('EMONEY_PWD','496086');
define('EMONEY_VENDOR_ID','158');
define('EMONEY_URL','http://emoneygroup.online/API/APIService.aspx');


/*Speedpay recharge credentials*/

define('SPEEDPAY_URL','http://www.speedpayrecharge.com/ReCharge/APIs.aspx');
define('SPEEDPAY_VENDOR_ID','160');
define('SPEEDPAY_MN','9867213953');
define('SPEEDPAY_UN','Payone');
define('SPEEDPAY_PWD','pay@123');
define('SPEEDPAY_PIN_NO','2627');


/* CC Avenue Credentials */

define('CCAVENUE_RECHARGE_URL','https://api.billavenue.com/billpay/');
define('CCAVENUE_VERSION','1.0');
define('CCAVENUE_INSTITUTION_ID','CC07');
define('CCAVENUE_ACCESS_CODE','AVWU35TS71CQ49GXET');
define('CCAVENUE_KEY','17FF9F73CAF5468FD7648EAB2AF58B41');

//Dipali's changes

/*Think walnut api credentials*/
define('THINKWAL_VENDOR_ID','162');
define('THINKWAL_MEMBER_ID','TW1011');
define('THINKWAL_PASS','Toh5Ood1');
define('THINKWAL_MOB_REC_URL','http://api.twd.bz/wallet/api/mobile.php');
define('THINKWAL_DTH_REC_URL','http://api.twd.bz/wallet/api/dth.php');
define('THINKWAL_BAL_URL','http://api.twd.bz/wallet/api/getBalance.php');
define('THINKWAL_STATUS_URL','http://api.twd.bz/wallet/api/checkStatus.php');

/*Champ recharge api credentials*/
define('CHAMPREC_VENDOR_ID','163');
define('CHAMPREC_USERID','7097');
define('CHAMPREC_PASS','6509962');
define('CHAMPREC_TXNPASS','757993');
define('CHAMPREC_REC_URL','http://www.champrecharges.com/api_users/recharge');
define('CHAMPREC_BAL_URL','http://www.champrecharges.com/api_users/balance');
define('CHAMPREC_STATUS_URL','http://www.champrecharges.com/api_users/status');


//SAAS API CREDENTIAL

define('DCP_URL','http://54.227.235.179/api/');
define('DCP_BALURL','http://54.227.235.179/api/checkbalance');
define('DCP_RECURL','http://54.227.235.179/api/recharge');
define('DCP_STATUS_URL','http://54.227.235.179/api/checkstatus');
define('DCP_SECRET_KEY','8e7078438c157483fb95b73807c099f1');
define('DCP_USER',3);

//added on live 7.4.14

define('YASHICAENT_URL','http://54.227.235.179/api/');
define('YASHICAENT_BALURL','http://54.227.235.179/api/checkbalance');
define('YASHICAENT_RECURL','http://54.227.235.179/api/recharge');
define('YASHICAENT_STATUS_URL','http://54.227.235.179/api/checkstatus');
define('YASHICAENT_SECRET_KEY','8e7078438c157483fb95b73807c099f1');
define('YASHICAENT_USER',3);

define('KA2ZREC_USERID','Pay1');
define('KA2ZREC_PASSWORD',262780);
define('KA2ZREC_PIN',2627);
define('KA2ZREC_URL','http://www.kumara2zrecharge.in/Recharge/APIs.aspx');
define('KA2ZREC_PAY1_MOBILE',9867213953);

//For ROUNDPAY API

define('ROUNDPAY_USERID',9867213953);
define('ROUNDPAY_PASSWORD',988154);
define('ROUNDPAY_PIN',9881);
define('ROUNDPAY_URL','http://roundpayapi.in/Api/ApiService.aspx');

//For MAXXRECHARGE

define('MAXXREC_URL','https://www.maxxrecharge.com/apiservice.asmx/Recharge');
define('MAXXREC_BAL_URL','https://www.maxxrecharge.com/apiservice.asmx/GetBalance');
define('MAXREC_STATUS_URL','https://www.maxxrecharge.com/apiservice.asmx/GetRechargeStatus');
define('MAXXREC_TOKEN','3f500a1939994a39b2ab687dab2c783f');
define('MAXXREC_M_TOKEN','c7799c3cec3e4a71883d1950d8884717');

//For ERECHARGEPOINT

define('EREC_URL','http://www.erechargepoint.in/apiservice.asmx/Recharge');
define('EREC_BAL_URL','http://www.erechargepoint.in/apiservice.asmx/GetBalance');
define('EREC_URL_STATUS','http://www.erechargepoint.in/apiservice.asmx/GetRechargeStatus');
define('EREC_TOKEN','3c60028f56a7473798744f5f9aa1a7dd');
//define('EREC_M_TOKEN','150c5bfeaac34681b2c939ac193e7713');
define('EREC_M_TOKEN','6092cc85154e4611865fd5538a1bfca6');

//For UNIQUE RECHARGE

define('UREC_ID','Pay1');
define('UREC_PASS','2627@PAY1');
define('UREC_URL','http://uniquerechargesrs.com/api/ApiServices/GetService');

//For PAYONEALL

define('PAY1ALL_USERID',9867213953);
define('PAY1ALL_PASSWORD','pay1@123');
define('PAY1ALL_URL','http://payoneallrechargesystem.com/API/APIService.aspx');

//For PRECHARGE

define('PREC_ID',9867213953);
define('PREC_PASS','262780');
define('PREC_URL','http://login.prechaarge.in/api/ApiServices/GetService');

//For Ashwin1
define('ASHWIN1_URL','http://182.56.9.57:8081/api/');
define('ASHWIN1_BALURL','http://182.56.9.57:8081/api/checkbalance');
define('ASHWIN1_RECURL','http://182.56.9.57:8081/api/recharge');
define('ASHWIN1_STATUS_URL','http://182.56.9.57:8081/api/checkstatus');
define('ASHWIN1_SECRET_KEY','8e7078438c157483fb95b73807c099f1');
define('ASHWIN1_USER',3);
//For payoneclick
define('PAY1CLICK_BALURL', 'http://payoneclick.in/retailer/api/do_check_balance.aspx');
define('PAY1CLICK_RECURL','http://payoneclick.in/retailer/api/do_recharge_transaction.aspx');
define('PAY1CLICK_STATUSURL','http://www.payoneclick.in/retailer/api/do_check_transaction.aspx');
//define('PAY1CLICK_KEY','Xke%2fJk3%2bAFlZZinHecNdTy5ZDAeY10na');
define('PAY1CLICK_KEY','Xke%2fJk3%2bAFlZZinHecNdT1%2bcvo4%2bCO3g');
define('PAY1CLICK_PASS',262780);

//For KRACREC API
define('KRACREC_USERID','krac205');
define('KRACREC_PASSWORD',26739);
define('KRACREC_PIN',1606);
define('KRACREC_URL','http://recharge.kracrecharge.com/ReCharge/APIs.aspx');
define('KRACREC_PAY1_MOBILE',7738525957);

/*stelecom */
define('STELCOM_ACCNO','123460');
define('STELCOM_PASS','7kwr4x');
define('STELCOM_MOB','919867213953');
define('STELCOM_KEY','7b1df2d6-5422-408b-85ce-cc41b13e5d8b');
define('STELCOM_BAL_URL','http://shreetelecom.in/webservices/httpapi/check-balance');
define('STELCOM_REC_URL','http://shreetelecom.in/webservices/httpapi/recharge-request');
define('STELCOM_STATUS_URL','http://shreetelecom.in/webservices/httpapi/check-status.cshtml');

/*Mani Master */

define('MANIMASTER_ID','9867213953');
define('MANIMASTER_PASS','pay123');
define('MANIMASTER_URL','http://manimaster.com/api/ApiServices/GetService');

/*WellBorn */
define('WELLBORN_ACCNO','123566');
define('WELLBORN_PASS','pay1@2627');
define('WELLBORN_MOB','919867213953');
define('WELLBORN_KEY','22870a84-5fe8-4cfc-89dd-98018d63a898');
define('WELLBORN_BAL_URL','http://wellbornapi.in/webservices/httpapi/check-balance');
//define('WELLBORN_REC_URL','http://wellbornapi.in/webservices/httpapi/recharge-request');
define('WELLBORN_REC_URL','http://wellbornapi.in/webservices/httpapi/recharge-request-new');
define('WELLBORN_STATUS_URL','http://wellbornapi.in/webservices/httpapi/recharge-check');

//Nishi Saas API

define('NISHI_USER',1);
define('NISHI_SECRET_KEY','3d5f9fe19a66f98018100cb27e80a20f');
define('NISHI_BALURL','nishi.saasrecharge.in/api/checkbalance');
define('NISHI_STATUS_URL','nishi.saasrecharge.in/api/checkstatus');
define('NISHI_RECURL','nishi.saasrecharge.in/api/recharge');

//Super Saas Api

define('SUPERSAAS_USER',1);
define('SUPERSAAS_SECRET_KEY','5be0bdd97bff5ff94a523153250672e1');
define('SUPERSAAS_BALURL','super.saasrecharge.in/api/checkbalance');
define('SUPERSAAS_STATUS_URL','http://super.saasrecharge.in/api/checkstatus');
define('SUPERSAAS_RECURL','http://super.saasrecharge.in/api/recharge');

define('MYETOPUP_BALURL','https://www.myetopup.co.in/apiservice.asmx/GetBalance');
//define('MYETOPUP_APITOKEN','f390ba574d504114b956509b310f3afb');
define('MYETOPUP_APITOKEN','fcb64fbd8f944de6b8612bf61dc5e189');
define('MYETOPUP_RECURL','https://www.myetopup.co.in/apiservice.asmx/Recharge');
define('MYETOPUP_STATUSURL','https://www.myetopup.co.in/apiservice.asmx/GetRechargeStatus');


define('BALAJISAAS_USER',1);
define('BALAJISAAS_SECRET_KEY','75df700258ad1f6b22d4ca7ebf0bdb8c');
define('BALAJISAAS_BALURL','http://balaji.saasrecharge.in/api/checkbalance');
define('BALAJISAAS_STATUS_URL','http://balaji.saasrecharge.in/api/checkstatus');
define('BALAJISAAS_RECURL','http://balaji.saasrecharge.in/api/recharge');

/* PRATISTHA SAAS */

define('PRATISAAS_USER',1);
define('PRATISAAS_SECRET_KEY','21309cc3709eec60006febb3af15bd36');
define('PRATISAAS_BALURL','http://pratishtha.saasrecharge.in/api/checkbalance');
define('PRATISAAS_STATUS_URL','http://pratishtha.saasrecharge.in/api/checkstatus');
define('PRATISAAS_RECURL','http://pratishtha.saasrecharge.in/api/recharge');
/*OSS SAAS */
define('OSSSAAS_USER',1);
define('OSSSAAS_SECRET_KEY','4686fd03c73cae688ce62dcd94d432dc');
define('OSSSAAS_BALURL','http://oss.saasrecharge.in/api/checkbalance');
define('OSSSAAS_STATUS_URL','http://oss.saasrecharge.in/api/checkstatus');
define('OSSSAAS_RECURL','http://oss.saasrecharge.in/api/recharge');

/*Manglam Vodafone API */

define('MANGLAMVOD_BALURL','http://www.mangalamservice.com/api_users/balance');
define('MANGLAMVOD_RECURL','http://www.mangalamservice.com/api_users/recharge');
define('MANGLAMVOD_TRANSURL','http://www.mangalamservice.com/api_users/status');
define('MANGLAMVOD_USERID','7055');
define('MANGLAMVOD_TXNPWD','951452');

/* Axis Integration */
define('AXIS_SALT','U3CFGMYOK9R3FAXS');

// SWAMIRAJAPI
define('SWAMIRAJAPI_RECHARGE_URL','DoRecharge');
define('SWAMIRAJAPI_BALANCE_URL','MyBalance');
define('SWAMIRAJAPI_STATUS_URL','CheckStatus');
define('SWAMIRAJAPI_UN','9867213953');
define('SWAMIRAJAPI_PWD','pay12627');
define('SWAMIRAJAPI_VENDOR_ID',185);
/*  RAJSAAS */
define('RAJSAAS_USER',1);
define('RAJSAAS_SECRET_KEY','53b3f6a084853dac2fc0fee7f9a2bc5d');
define('RAJSAAS_BALURL','http://raj.saasrecharge.in/api/checkbalance');
define('RAJSAAS_STATUS_URL','http://raj.saasrecharge.in/api/checkstatus');
define('RAJSAAS_RECURL','http://raj.saasrecharge.in/api/recharge');
/*  KUMARSAAS */
define('KUMARSAAS_USER',2);
define('KUMARSAAS_SECRET_KEY','33ea9f277db740e0334e41126da302e6');
define('KUMARSAAS_BALURL','http://kumar.saasrecharge.in/api/checkbalance');
define('KUMARSAAS_STATUS_URL','http://kumar.saasrecharge.in/api/checkstatus');
define('KUMARSAAS_RECURL','http://kumar.saasrecharge.in/api/recharge');
//Techmate solution Api
define('TECHMATE_PWD','0805');
define('TECHMATE_UN','7710975362');
define('TECHMATE_URL','http://techmatesolutions.info/API/APIService.aspx');
//ROBO URL's
define('ROBO_RECURL','http://web.ezytm.com/Robotics/webservice/GetMobileRecharge');
define('ROBO_STATUSURL','http://web.ezytm.com/Robotics/webservice/GetStatus');
//VARSHA CREDENTIAL
define('VARSHA_APIID','3852');
define('VARSHA_PASS','Nishi@362');

//QUBA Telecomm
define('QUBA_ROBO_APIID','3850');
define('QUBA_ROBO_PASS','Nishi@461');


//AVENTIDEA
define('AVENTIDEA_APIID','3851');
define('AVENTIDEA_PASS','Nishi@717');

//3PLUS
define('threeplus_APIID','3853');
define('threeplus_PASS','Nishi@967');


//PINTOO Sales
define('PINTOOS_APIID','3854');
define('PINTOOS_PASS','Nishi@106');


//AV Enterprise
define('AVEnterp_APIID','3855');
define('AVEnterp_PASS','Nishi@715');


//JASH Comm
define('JASHComm_APIID','3856');
define('JASHComm_PASS','Nishi@534');


//NK Agencies
define('NKAGENCIES_APIID','3857'); 
define('NKAGENCIES_PASS','Nishi@460');


//Anil Kirana
define('ANILKirana_APIID','3858');
define('ANILKirana_PASS','Nishi@547');


//Jeevanraksha Enterprise
define('JEEVANRAKSHAE_APIID','3859');
define('JEEVANRAKSHAE_PASS','Nishi@645');
//Payclick
define('PAYCLICK_USERID','500002');
define('PAYCLICK_PASS','PjqEW3XZN5sg');
define('PAYCLICK_BAL','http://payoneclick.co.in/api/balance');
define('PAYCLICK_REC_URL','http://payoneclick.co.in/api/recharge');
define('PAYCLICK_TRANS_URL','http://payoneclick.co.in/api/check');

//STARCOMM
define('STARCOMM_APIID','3936');
define('STARCOMM_PASS','Hsui@333');

//MODERNTRADE
define('MODERNTRADE_APIID','3937');
define('MODERNTRADE_PASS','P87@ay01@11');

//VISHWAJYOTI
define('VISHWAJYOTI_APIID','3938');
define('VISHWAJYOTI_PASS','P68@ay30@19');

//AFTRADER
define('AFTRADER_APIID','3939');
define('AFTRADER_ROBO_PASS','P90@ay67@14');

//STARMBROBO
define('STARMBROBO_APIID','3940');
define('STARMBROBO_PASS','P73@ay66@17');

