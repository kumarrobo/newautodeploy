<?php


// this array contains all the B2B Application message  parameters that send using sendMessage() function of General components

return array(

'Relationship_managerVerify_OTP_MSG' => "Use <OTP> as one-time password (OTP) to verify your number at Pay1 - Dukandaron ka network! Do not share it with anyone.",

//alert controller 
'GaneshUtsav_Offer_MSG' => "PAY1 Ganesh Utsav offer, Your total targeted Sale Rs <TARGETSALE> and you have achieved Rs <ACHIEVEDAMOUNT>. Achieve your target  Till 30th Sept and win exciting prizes",

//alert controller    
'IndependenceDay_Contest_MSG' => "Have you claimed your Independence Day Contest prize? Like our Facebook page and check the list of winners Today. www.facebook.com/pay1Store \nPAY1",

//apis controller  
'LastRecharge_MSG' => "Last Transaction\nTrans Id: <VENDORS_ACTIVATIONS_REF_CODE> <VENDORS_ACTIVATIONS_PARAM>\nMobile: <MOBILE_NUMBER> \nOperator: <OPERATOR_NAME>\nAmount: <AMOUNT>\nStatus: <SUCEESS_TEXT>\nSale this month: Rs <SALE>\nYour bal: Rs.<RETAILER_BALANCE>\n<TOP_UP>",   

//apis controller      
'LastRecharge_NoLastTrans_MSG' => "There is no last transaction found in last 7 days\nSale this month: Rs <SALE>\nYour bal: Rs.<RETAILER_BALANCE>",

//apis controller
'GetApps_MSG' => "Apps: ".DISTPANEL_URL."users/app\nWebsite: ".RETPANEL_URL."\nMisscall recharges: Dial 02267242234",

//apis controller
'Dropped_DueToLate_MSG' => "Dropped: Your request <MSG> is dropped due to late sms delivery. Please try again", 
 
//apis controller    
'Dropped_Duplicate_MSG' => "Duplicate: Your request <MSG> already received",    

//apis controller    
'Dropped_DuplicateStatus_MSG' => "Duplicate: Your request <MSG> already received \nTo know your transaction status give a misscall on 02267242287",   
    
//apis controller      
'Retailer_Reversal_MSG' => "Your complaint for transaction id <VENDORS_ACTIVATIONS_REF_CODE> has been taken successfully. Please note the complaint reference id <COMPLAINTS_ID>. The complaint should resolve in <TURNAROUND_DURATION> hours",

'Retailer_Reversal_MSG_MINS' => "Your complaint for transaction id <VENDORS_ACTIVATIONS_REF_CODE> has been taken successfully. Please note the complaint reference id <COMPLAINTS_ID>. The complaint should resolve in <TURNAROUND_DURATION> minutes",

//apis controller    
'UserRequest_Of_MobBill_Payment_MSG' => "Dear User\nYour request of bill payment of Rs <VENDORS_ACTIVATIONS_AMOUNT> accepted successfully. Wait for some time for operator's confirmation.\nYour pay1 txnid: <TRANSID>",

//apis controller        
'UserRequest_Of_UtilBill_Payment_MSG' => "Dear User\nYour request of bill payment of Rs <AMOUNT> accepted successfully. Give us 24-48 hours to complete this payment.\nYour pay1 txnid: <TRANSID>", 
    
//apis controller        
'App_PinUpdated_MSG' => "Your Pay1 App Pin Updated successfully. If you have not updated your pin, send SMS: PAY1 HELP to 09004350350 ",
        
//apis controller        
'Forget_Password_MSG' => "Dear User, Your One Time Password(OTP) to reset your password is <OTP>",
    
//apis controller        
'Missed_CallsLeads_MSG' => "Dear sir/madam,\nThank you for showing your intrest in our business. We shall reach you out within 72 hours. PAY1( website:www.pay1.in)",
        
//apis controller        
'Retailer_Registered_MSG'  => "You have registered as a Retailer with Pay1. Use OTP <OTP> to verify your mobile number. Do not share it with anyone",    

//apis controller        
//'Retailer_Distributor_Registered_MSG'  => "You have registered as a <INTRESTED_LEAD_NAME> with Pay1. Use OTP <OTP> to verify your mobile number. Do not share it with anyone.",    
'Retailer_Distributor_Registered_MSG'  => "Use <OTP> as one-time password (OTP) to verify your number at Pay1 - Dukandaron ka network! Do not share it with anyone.",    
    
//apis controller        
'Retailer_DeviceVerify_OTP_MSG'  => "You are trying to login through a new device or browser. To login type OTP (One Time Password) <OTP> to verify your mobile number.",    

//apis controller        
'Retailer_LocationVerify_OTP_MSG'  => "You are trying to login through a new location or place. To login type OTP (One Time Password) <OTP> to verify your mobile number.",    
    
//apis controller        
'Create_RetDist_Leads_MSG'  => "Thank you for choosing PAY1 - India's Fastest Growing Retail Network! For Info call on 022-67242288 Check out more on Youtube: https://www.youtube.com/c/Pay1Inapp",

//apis controller  
'Create_Ret_Leads_MSG'  => "Thank you for choosing Pay1 - Dukandaron ka network! For Info call on 02242932288 Website: ".RET_LEAD_WEB_URL." App: ".RET_LEAD_APP_URL , 

//apis controller     
'Lead_Application_Form_MSG'  => "Thank you for showing interest to become Pay1 distributor. To know more about the proposal, click here. <URL>",

//apis controller        
'Distributor_Lead_Registered_MSG'  => "Thank you for applying to become Pay1 distributor. We will get back to you within 24 hrs. Check out more on Youtube: https://goo.gl/Cw5SGo",

//apis controller        
'Duplicate_Lead_Request'  => "Your application is under review. We have sent detailed email / sms on the proposal. We will get back to you within 24 hrs or you can call us on 02242932202",

//apis controller        
'Retailer_Create_By_Distributor_MSG'  => "\nOne Time Password(OTP) to create a new Retailer is <OTP>.This is valid for next 30 mins. Do not share it with anyone.",

//apis controller        
'Retailer_New_Mobile_Change_By_Distributor_MSG'  => "\nOne Time Password(OTP) to change Retailer mobile number is <OTP>.This is valid for next 30 mins. Do not share it with anyone.",

//apis controller        
'Dist_New_Mobile_Change_By_SuperDist_MSG'  => "\nOne Time Password(OTP) to change Distributor mobile number is <OTP>.This is valid for next 30 mins. Do not share it with anyone.",

//apis controller        
'Salesman_Create_By_Distributor_MSG'  => "\nOne Time Password(OTP) to create a new Salesman is <OTP>.This is valid for next 30 mins. Do not share it with anyone.",

//apis controller        
'Distributor_Create_By_SuperDistributor_MSG'  => "\nOne Time Password(OTP) to create a new Distributor is <OTP>. This is valid for next 30 mins. Do not share it with anyone.",

//apis controller        
'SuperDistributor_Create_By_MasterDistributor_MSG'  => "\nOne Time Password(OTP) to create a new Super Distributor is <OTP>. This is valid for next 30 mins. Do not share it with anyone.",

//cc controller     
'Retailer_Misscall_MSG' => "Dear Sir, No customer care is available now. You can use this facility only between 8AM & 11PM",  
    
//cc controller    
'Retailer_CallNotPicked_MSG'  => "Dear Retailer\nWe tried calling you. But you have not picked your call",

//crons controller
'Distributor_Commission_MSG' =>  "Dear Sir, Your earning of Rs <AMOUNT> added to your account.<MID_MSG>",
        
//crons controller    
'Retailers_MoneyBack_MSG' =>  "Dear Retailer, Aapka last month ka application/internet ka <TARGET> ka target complete ho gaya hai\nAapka last month ka sale hai: Rs <SALE_AMOUNT>\nCompany is giving you a bonus of Rs <AMOUNT>!!. Aap har mahine ye bonus kama sakte ho\nYour current balance is: Rs.<BALANCE>",  
    

//crons controller        
'Retailers_MoneyBack_Special_MSG' => "\nAapka last month ka sale hai: Rs  <SALE_AMOUNT> \nCompany is giving you a diwali bonus of Rs <AMOUNT> !! \nYour current balance is: Rs. <BALANCE> ",    
    
//crons controller 
'Retailer_RentalCut_Monthly_MSG' => "RENTAL MESSAGE\nTotal sale <FROM_MONTH> <TO_MONTH>: Rs <SALE>
                                     \nTotal Monthly rental: Rs<RENTAL> \nTotal Waiver: Rs0\nAmount Charged: Rs<RENTAL>
                                     \nPlease do sale of Rs <TARGET_AMOUNT> every month to avoid monthly rental of Rs<RENTAL_AMOUNT>",

//crons controller    
'Retailer_RentalCut_Deducted_MSG' => "RENTAL MESSAGE\nDear Retailer, Your rental from <FROM_RET> to <TO> of Rs<RENTAL> is deducted from your account.",    

//crons controller        
'Retailer_IncentiveReminder_forDay1_MSG' => "Dear Retailer, Application/Web se 25000 sale par kamaiye 50Rs. bonus aur 50000 par 100Rs. bonus !! Aaj hi application download kare, misscall on 02267242289",
    
//crons controller        
'Retailer_IncentiveReminder_forSale25K_MSG' => "Dear Retailer, Is month ka application sale hai Rs <SALE>. 25000 poore karne par milega 50Rs. aur 50000 par 100Rs. bonus !!",    

//crons controller        
'Retailer_IncentiveReminder_forSale50K_MSG' => "Dear Retailer, Is month ka application sale hai Rs <SALE>. Aap already 25000 ka target complete kar chuke ho, 50000 complete karne par 100Rs. bonus paiye !!",    
    
//crons controller    
'Retailer_RentalReminder_MSG'  => "Dear Retailer, Apki monthly sale <SALE> Rs. ho chuki hai. !! Agar aap 25000 tak sale puri krte hai to aap apka 50 Rs. rental 0 kar skate hai !!",     

//crons controller    
'Retailer_MarIncentive_MSG'  => "Congrats !! \nAapka last month ka sale hai: Rs <ACHIEVED> \nPay1 is giving you a Bonus for the Month (march)  Rs <INCENTIVE>!! \nYour current balance is: Rs. <BALANCE> ",   
 
//crons controller 
'Retailer_Bonus_MSG' => "Thanks A Lot! It was a pleasure doing Business with you on BIG DAY SALE. Your Pay1 a/c has been credited with CASH BONUS <AMOUNT> \n - Pay1",    
 
//crons controller
'Birthday Wishes' => '<div style="text-align:center">'."\n".'<img src="http://shopscdn.s3.amazonaws.com/mailers/birthday-wishes.png" alt="Happy Birthday from Pay1">'."\n".'</div>'."\n".'<div style="color:#231f20;font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;font-size:16px;text-align:center">'."\n".'<h2>'."Dear <NAME>".'</h2>'."\n".'</div>'."\n".'<div style="text-align:center">'."\n".'<img src="http://shopscdn.s3.amazonaws.com/mailers/birthday-feature.png" class="img-responsive" alt="Happy Birthday from Pay1">'."\n".'</div>'."\n".'<div style="text-align:center;padding-top:20px">'."\n".'<table align="center">'."\n".'<td style="width:33.33%;text-align:center">'."\n".'<a href="https://www.facebook.com/pay1store" target="blank">'."\n".'<img src="http://shopscdn.s3.amazonaws.com/mailers/facebook.png" alt="Like us our Facebook page">'."\n".'</a>'."\n".'<p>Like us</p>'."\n".'</td>'."\n".'<td style="width:33.33%;text-align:center">'."\n".'<a href="https://www.youtube.com/c/Pay1Inapp" target="blank">'."\n".'<img src="http://shopscdn.s3.amazonaws.com/mailers/youtube.png" alt="Subscribe to our YouTube channel">'."\n".'</a>'."\n".'<p>Subscribe us</p>'."\n".'</td>'."\n".'<td style="width:33.33%;text-align:center">'."\n".'<a href="http://pay1.in/partners-blog/" target="blank">'."\n".'<img src="http://shopscdn.s3.amazonaws.com/mailers/wordpress.png" alt="Read our Blog">'."\n".'</a>'."\n".'<p>Read our Blog</p>'."\n".'</td>'."\n".'</table>'."\n".'</div>'."\n".'<div style="text-align:center">'."\n".'<img src="http://shopscdn.s3.amazonaws.com/mailers/pay1_logo.png" class="img-responsive" alt="Pay1 Logo">'."\n".'</div>',

//crons controller
'Anniversary Wishes'  =>'<div style="color:#231f20;font-family:\'Helvetica Neue\',Helvetica,Arial,sans-serif;font-size:16px;text-align:center">'."\n".'<h2>'."Dear <NAME>".'</h2>'."\n".'</div>'."\n".'<div style="text-align:center">'."\n".'<img src="http://shopscdn.s3.amazonaws.com/mailers/anniversary_feature.png" alt="Happy Anniversary from Pay1">'."\n".'</div>'."\n".'<div style="text-align:center;padding-top:20px">'."\n".'<table align="center">'."\n".'<td style="width:33.33%;text-align:center">'."\n".'<a href="https://www.facebook.com/pay1store" target="blank">'."\n".'<img src="http://shopscdn.s3.amazonaws.com/mailers/facebook.png" alt="Like us our Facebook page">'."\n".'</a>'."\n".'<p>Like us</p>'."\n".'</td>'."\n".'<td style="width:33.33%;text-align:center">'."\n".'<a href="https://www.youtube.com/c/Pay1Inapp" target="blank">'."\n".'<img src="http://shopscdn.s3.amazonaws.com/mailers/youtube.png" alt="Subscribe to our YouTube channel">'."\n".'</a>'."\n".'<p>Subscribe us</p>'."\n".'</td>'."\n".'<td style="width:33.33%;text-align:center">'."\n".'<a href="http://pay1.in/partners-blog/" target="blank">'."\n".'<img src="http://shopscdn.s3.amazonaws.com/mailers/wordpress.png" alt="Read our Blog">'."\n".'</a>'."\n".'<p>Read our Blog</p>'."\n".'</td>'."\n".'</table>'."\n".'</div>'."\n".'<div style="text-align:center">'."\n".'<img src="http://shopscdn.s3.amazonaws.com/mailers/pay1_logo.png" class="img-responsive" alt="Pay1 Logo">'."\n".'</div>',

//crons controller
'Birthday_Wish_sms' =>"On every birthday, ‘You are not years old, You are experiences grown’ And on this special day we at Pay1 would like to wish you a very Happy Birthday and hope you enjoy many more ahead!",
    
//crons controller
'Anniversary_Wish_sms' => "A very Happy Anniversary with Pay1! ‘Some relations get better with time and experiences’ Thanks for being a member of our Pay1 family and hope you continue to have many more years with us!",
    
//crons controller  
'RENTAL_DEDUCT_TEMPLATE' => 'Dear Retailer, Rs.<AMOUNT> has been deducted from your Pay1 wallet against <SERVICE_NAME> rental for <MONTH> <REACTIVATE>',
    
//crons controller  
'RENTAL_DEDUCT_NOTIFY_TEMPLATE' => 'Dear Retailer, <SERVICE_NAME> rental amount of Rs. <AMOUNT> for <MONTH> is overdue. Service will be deactivated on <DATE>. Please maintain sufficient balance in Pay1 wallet.',
    
//crons controller 
'RENTAL_DEDUCT_SERVICE_DEACT_TEMPLATE' => 'Dear Retailer, Due to insufficient balance, your <SERVICE_NAME> service has been deactivated. Please contact support team on 022 67242299.',

//crons controller 
'PARTIAL_SETTLEMENT_TEMPLATE' => 'Dear Retailer, Due to insufficient balance in your Pay1 wallet, amount of Rs. <AMOUNT> from txn <TXN_ID> of <TXN_DATE> has been settled partially in your wallet for rental consideration.',
    
//crons controller
'DAILY_RENTAL_DEDUCTION_MSG' => 'Dear Sir, Aapke account se Rs <RENTAL_AMOUNT>/- ka rental deduct kiya hai. <SALE_AMT>/- ki upar sale karne pe koi rental nahi hoga.',     

//distributors controller 
'Distributors_OTP_MSG'  => "Use OTP <OTP> to reset pin. Do not share it with anyone",    

//distributors controller     
'Distributors_Pin_ResetOrChange_MSG'  => "Your PIN was reset from Pay1 Channel Partner app. Your new PIN is <PIN> ",    
    
//distributors controller 
'Salesman_Pin_ChangeAndSend_MSG' => "You can login to Pay1 Channel Partner Android App with pin: <PASSWORD>. Kindly, change your pin from the app.",    
  
//groups controller
'Recharge_Demo_MSG' => "Welcome to Signal7 demo! Download links for the sample applications: Android: http://bit.ly/yiClx0   Symbian: http://bit.ly/wgzEbQ",
 
//panels controller     
'Panels_Pullback_MSG'=> "Pulled back amount of Rs. <PULLED_AMOUNT> from your account.\nTrans Id: <TRANSID>\n<PULLBACKTO> Amount: <AMOUNT>\nYour current balance is Rs.<BALANCE>",
    
//panels controller    
'Retailer_DeleteKYCDocs_MSG'  => "Your KYC (<RETAILERS_TYPE> photo) was unverified. Reason: <REASON> Kindly, upload appropriate documents.",    

//panels controller
'smsToRetailerOnDistributorChange'  => "Dear Retailer, Distributor of your area has been changed to <DIST_NAME> (<DIST_MOBILE>). Kindly connect with your new distributor for limit top-up.",        
        
//promotions controller    
'Promotions_Campaign_MSG' => "Now cut your mobile expenses by 30% just give a miss call 02267242267\nConvert to Idea Post Paid & Enjoy: Plan 199, 600mins/400SMS Free, 30p Local, 50p STD",

//promotions controller    
'Old_Retailers_MSG' => "Hi, More fast n friendly, the new pay1 mobile app. Accessible via SMS n GPRS. Activate ur a/c today to enjoy Pay1 benefits. Call today 022-67242288\n-Pay1",   

//recharges controller     
'ReversalDeclined_MSG' => "Complaint for Trans Id: <VENDORS_ACTIVATIONS_REF_CODE> is resolved\nTransaction is successfull\nDate: <DATED>\n<VENDORS_ACTIVATIONS_PARAM>\nMobile: <MOBILE_NUMBER>\nOperator: <OPERATOR_NAME>\nAmount: <AMOUNT>",    
    
//recharges controller    
'UpdateCommentsForReversal_MSG' => "Complaint for transactin id <TRANSID> has been declined.",    
    
//salesmen controller  
'Retailer_CollectPayment_MSG' => "Dear Retailer, We have successfully collected your setup fee of Rs <AMOUNT>",    

//salesmen controller      
'SMS_To_SALESMEN_MSG' => "Dear <SALESMEN_NAME>\nYour Top up limit is: <SALESMEN_TRANSLIMIT>\nLast day pending: <LAST_DAY_PENDING>\nBalance: <BALANCE>\nTotal yesterday's top-ups: <TOP_UPS>\nTotal yesterday's top-up collection: <SALESMEN_COLLECTION>",
    
    
//salesmen controller    
'Retailer_Block_MSG' =>  "Dear Retailer, Thank you for trying Pay1 services. Aapka Pay1 trial khatm ho gaya hai." ,    

//salesmen controller    
'Retailer_UnBlock_MSG' =>  "Dear Retailer, Thank you for choosing Pay1 services. You can now do transactions with us" ,     
    
//shops controller
'CreateDistributor_MSG' => "Congrats!!\nYou have become Distributor of Pay1. Your login details are below\nOnline Url: ".DISTPANEL_URL."\nUserName: <DISTRIBUTOR_MOBILE_NUMBER>\nPassword: <USER_SYSPASS>\nCheck out the Pay1 app for our Channel Partners at https://goo.gl/yuTaeB", 

//shops controller
'CreateSuperDistributor_MSG' => "Congrats!!\nYou have become Super Distributor of Pay1. Your login details are below\nOnline Url: ".DISTPANEL_URL."\nUserName: <SUPER_DISTRIBUTOR_MOBILE_NUMBER>\nPassword: <USER_SYSPASS>",    
    
 //shops controller   
'CreateSalesman_MSG' => "Congratulations!\nYou have become a Salesman at Pay1\nKindly, note your pin for login\nPin: <PASSWORD>",     
 
//shops controller    
'Salesman_Collection_MSG' => "Dear Sir, Amount of Rs. <DIFF_TOP> collected from <SALESMAN_NAME> and now salesman topup limit is Rs. <REMAINING_BALANCE>",    

//shops controller
'CreateRetailer_App_MSG' => "Welcome to Pay1!\nDownload Apps: ".DISTPANEL_URL."users/app\nWebsite: ".RETPANEL_URL."\nMisscall recharges: Dial 02267242234",   


//shops controller    
'Pullback_FromSalesman_OfRetailer_MSG' => "Dear Retailer, Rs <AMOUNT> is pulled back from your account by your salesman. Your balance is now Rs <BALANCE>",
 
//shops controller    
'Pullback_FromDistributor_OfSalesman_MSG' => "Dear Salesman, Rs <AMOUNT> is pulled back from your account by your distributor. Your balance is now Rs <BALANCE>",
 
//shops controller    
'Pullback_Retailer_MSG' => "Dear Retailer, Rs <AMOUNT> is pulled back from your account by your distributor. Your balance is now Rs <BALANCE>",
 
//shops controller    
'Pullback_Salesmen_MSG' => "Dear Salesman, Rs <AMOUNT> is pulled back from retailer <SHOP_NAME> (<MOBILE_NUMBER>)",    

//shops controller    
'Pullback_Distributor_MSG' => "Dear <USER>, Rs <AMOUNT> is pulled back from your account. Your balance is now Rs <BALANCE>",    
    
//shops controller    
'Reatiler_Approve_MSG' =>  "Dear Retailer,\nYour account is successfully credited with Rs. <TOPUP_AMOUNT>\nYour current balance is Rs.<BALANCE>",

//shops controller
'Retailer_Refund_MSG' => "Dear Retailer,\nYou have got refund of Rs <AMOUNT> from Pay1 company\nYour current balance is now: Rs. <BALANCE>",    

//shops controller    
'Distributor_Incentive_MSG' => "Dear Distributor,\nYou have got incentive of Rs <AMOUNT> from Pay1 company\nYour current balance is now: Rs. <BALANCE>",    

//shops controller    
'Pullback_Refund_MSG'    => "Dear User,\nYour incentive of Rs <AMOUNT> is Pulled back by Pay1\nYour current balance is now: Rs. <BALANCE>",   

//shops controller    
'AmountTransfer_DistributorToSalesman_MSG' => "Distributor: ( <DISTRIBUTOR_NAME> ) transferred Rs. <AMOUNT> to Salesman: <SALESMAN_NAME>",    

//shops controller    
'AmountTransfer_SalesmanToRetailer_MSG' => "Salesman: ( <SALESMAN_NAME> ) transferred Rs. <AMOUNT> to Retailer: <SHOP_NAME>",    
    
//shops controller   
'AmountTransfer_DistributorToRetailer_MSG' => "Distributor: ( <DISTRIBUTOR_NAME> ) transferred Rs. <AMOUNT> to Retailer: <RETAILER_NAME>",    

//shops controller   
'AmountTransfer_AccountCreated_MSG' => "Dear Retailer,\nYour account is successfully credited with Rs. <AMOUNT>\nYour current balance is Rs. <BALANCE>",    
 
//shops controller     
'AmountTransfer_TransactionComplete_MSG' => "Transaction is Completed Successfully And Transaction Id is <RECID>",

//shops controller 
'AmountTransfer_TransferComplete_MSG' => "Transfer to <SHOP_NAME> Completed Successfully!!!",
    
//shops controller     
'AmountTransfer_ToRetailer_MSG' => "Amount Rs <AMOUNT> transferred to retailer <SHOP_NAME> successfully.\nYour balance now: <BALANCE>\nYour today's topups: <TOPUPS>",    
    
//shops controller     
'AmountTransfer_ToSalesman_MSG' => "Amount Rs <AMOUNT> transferred to salesman <SALESMAN_NAME> successfully.\nYour balance now: <BALANCE>\nYour today's topups: <TOPUPS>",    

//shops controller 
'TransferKits_MSG' => "Dear Distributor,\nYour account is successfully credited with <KIT_DATA> kits",

//shops controller    
'Transfer_TotalKits_MSG' => "Dear Distributor,\nYour account is successfully credited with <KIT_DATA> kits\nYou have total <TOTAL_KITS> now",    
    
//users controller     
'Retailer_addNewNumber_MSG' => "Dear Retailer,\nYour number has been shifted from <OLD_NUMBER> to your new number <NEW_NUMBER>",    

//users controller     
'Distributor_addNewNumber_MSG' => "Dear Distributor,\nDemo number of retailer (<RETAILERS_SHOPNAME>) changed from <OLD_NUMBER> to new number <NEW_NUMBER>",    

//users controller    
'Send_OTP_MSG' => "Dear User, Your One Time Password (OTP) to Change Number is <OTP>",      
 
//b2cextender components
'B2C_User_Request_MSG' => "Thank you for recharging at a PAY1 store\n <RETAILER_SHOP_NAME>. Keep recharging from here to get\nexciting gifts. Just give a missed call to <MISSED_CALL_NUMBER> to start now!",    
 
//shop components     
'Reverse_Transaction_Declined_MSG' => "Dear User\nYour request of bill payment of Rs <AMOUNT> declined from your operator. Please take your money back if already paid to your retailer\nYour pay1 txnid: <TRANSID>",        

//shop components     
'Reverse_Transaction_MSG' => "<REASON> Reversal of Rs.<RET_AMOUNT> is done\nTrans Id: <TRANSID>\nOperator: <OPERATOR_NAME>\n<VENDORS_ACTIVATIONS_DATA>\nAmount: <VENDORS_ACTIVATIONS_AMOUNT>\nCurrent balance: Rs.<BALANCE>",

//shop components    
'Process_Retailer_Transfer_After_PG_MSG' => "Dear Retailer,\nYour account is successfully credited with Rs <TXN_AMOUNT> Via Online Payment Gateway. Reference id is <TRANSID>\nCurrent balance is Rs<BALANCE>",
		
'Change_Distributor_Number' => "Dear Distributor, Your account number has been shifted from <OLD_NUMBER> to your new number <NEW_NUMBER>. Please contact 022-42932297 in
case of any assistance.",
    
    
    //distributor schemes
    'Distributor_Incentive'=>'Congratulations!! You have achieved incentive of Rs <INCENTIVE> against scheme <SCHEME> and Your closing balance is <CLOSING>',
    'Distributor_Scheme_New'=>"Dear Partner,\n%<TITLE>%\n(From <SCHEME_PERIOD>)\n<TARGETS>",
    'Distributor_Scheme_Ongoing'=>"Dear Partner,\n%<TITLE>%\n(From <SCHEME_PERIOD>)\n<TARGETS>\nYour current <SERVICES> sale is Rs <ACHIEVED>",
        
);

