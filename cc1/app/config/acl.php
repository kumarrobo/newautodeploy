<?php

		$config['acl']['modules'] = array(
	"Retailer_Retention" => array(
		"list" => array(
			"0" => array(
				"controller" => "alerts",
				"action" => array(
					"0" => array(
						'index',
						'downloadDumpData',
						'retrieveCommentDetails'
					) ,
					"1" => array(
						'insertCommentData'
					)
				)
			)
		) ,
		"url" => array(
			"alerts/index"
		)
	) ,

	"C2D" => array(
		"list" => array(
			"0" => array(
				"controller" => "cashpayment",
				"action" => array(
					"0" => array(
						'index',
						'loadTransactionData',
						'loadTransactionData',
						'loadSettlementData'
					) ,
					"1" => array(
						'insertSettlementData',
						'create_cashpayment_client'
					)
				)
			)
		) ,
		"url" => array(
			"cashpayment/index"
		)
	) ,
"Calls_Dropped" => array(
		"list" => array(
			"0" => array(
				"controller" => "cc",
				"action" => array(
					"0" => array(
						'panel',
					) ,
					"1" => array(
						'callDone',
						'callNotPicked'
					)
				)
			)
		) ,
		"url" => array(
			"cc/panel"
		)
	) ,
	"Distributor_Calls" => array(
		"list" => array(
			"0" => array(
				"controller" => "cc",
				"action" => array(
					"0" => array(
						'panel',
					) ,
					"1" => array(
						'callDone'
					)
				)
			)
		) ,
		"url" => array(
			"cc/panel/Distributor"
		)
	) ,
"Chat_Report" => array(
		"list" => array(
			"0" => array(
				"controller" => "chats",
				"action" => array(
					"0" => array(
						'generateReport',
						'report',
						'conversation'
					) ,
					"1" => array(
						)
				)
			)
		) ,
		"url" => array(
			"chats/report"
		)
	) ,
	"Plans" => array(
		"list" => array(
			"0" => array(
				"controller" => "circles",
				"action" => array(
					"0" => array(
						'index',
						'searchCircles',
						'searchPlans'
					) ,
					"1" => array(
						'deletePlan',
                                                'deletePlans',
						'newPlanEntry',
						'editPlanForm',
						'editPlanEntry'
						)
				)
			)
		) ,
		"url" => array(
			"circles/index"
		)
	) ,
	"Monitor" => array(
		"list" => array(
			"0" => array(
				"controller" => "monitor",
				"action" => array(
					"0" => array(
						'index',
						'smsIncomingMonitoring',
						'smsOutgoingMonitoring',
						'USSDMonitoring'
					) ,
					"1" => array(
						'switch_ussd',
						'setType',
						'setQueue',
						'unsetQueue'
						)
				)
			)
		) ,
		"url" => array(
			"monitor/smsIncomingMonitoring"
		)
	) ,
	"Distributor_Module" => array(
		"list" => array(
			"0" => array(
				"controller" => "salesmen",
				"action" => array(
					"0" => array(
					) ,
					"1" => array(
						'blockSalesman',
                          'mapSalesman',
							'blockRetailer'
					)
				)
			),
			"1" => array(
				"controller" => "shops",
				"action" => array(
					"0" => array(
						'autoCompleteSubarea',
						'backDistEdit',
						'allRetailer',
						'retFilter',
						'deletedRetailer',
						'salesmanListing',
						'editSalesman',
						'editRetailer',
						'showDetails',
						'formRetailer',
						'formSalesman',
						'formSetUpFee',
						'backRetailer',
						'backSalesman',
						'backSetup',
						'salesmanTran',
						'topup',
					        'distIncentive',
						'calculateCommission',
						'transfer',
						'backTransfer',
						'mainReport',
						'overallReport',
						'distTopUpRequest',
						'allRetailerTrans',
						'lastTransferred',
						'graphMainReport',
						'getInvoiceHistory',
					        'getInvoiceHistoryNew',
					        'getNewInvoice',
					        'checkIfEmailIdExists',
					        'downloadZip',
						'distEarningReport',
						'kitReport',
						'getOverallGstReport',
						'getTDSReport',
                                                'getAllInvoices',
                                                // 'debitCreditReport',
                                                'uploadTdsCertificate',
                                                'downloadTdsCertificate',
                                                'newsletter',
                                                'distTargetReport'


					) ,
					"1" => array(
						'deleteSubarea',
						'saveEditSm',
						'deleteRetailer',
						'editRetValidation',
						'addSetUpFee',
						'createSalesman',
						'addSalesmanCollection',
						'getInvoiceData',
						'convertDistToNewSystem'
					)
				)
			),
		    "2" => array("controller" => "panels",
                         "action" => array(
								"0" => array(
								) ,
								"1" => array(
									'addNewNumber'

								)
				             )

		                    ) ,
		"url" => array(
			""
		)
	)) ,
	"SD_Module" => array(
		"list" => array(
			"0" => array(
				"controller" => "shops",
				"action" => array(
					"0" => array(
						'formDistributor',
						'backDistributor',
						'backDistEdit',
						'allDistributor',
						'allRetailer',
						'retFilter',
						'rmOverAll',
						'rmTargetReport',
                                                                                                                                    'rmComapreTool',
                                                'deletedRetailer',
                                                'editRetailer',
                                                'showDetails',
                                                'formRm',
                                                'topup',
                                                'topupDist',
                                                'calculateCommission',
                                                'transfer',
                                                'backTransfer',
                                                'kitsTransfer',
                                                'debitSystem',
                                                'backKitTransfer',
                                                'mainReport',
                                                'overallReport',
					        'targetReport',
                                                'sReport',
                                                'lastTransferred',
                                                'graphMainReport',
                                                'distributorsMonthReport'
					) ,
					"1" => array(
						'autoCompleteSubarea',
						'createDistributor',
							'formDistributor',
'deleteRetailer',
'editDistValidation',
'createRm',
'transferKits','convertDistToNewSystem','updateDistProfile'
					)
				)
			)

		) ,
		"url" => array(
			""
		)
	) ,
	"RM_Module" => array(
		"list" => array(
			"0" => array(
				"controller" => "shops",
				"action" => array(
					"0" => array(
                                                                                                                                    'allDistributor',
                                                                                                                                    'showDetails',
                                                                                                                                    'topupDist',
                                                                                                                                    'mainReport',
                                                                                                                                    'overallReport',
                                                                                                                                    'rmComapreTool',
                                                                                                                                    'rmGraph',
                                                                                                                                    'targetReport',
                                                                                                                                    'rmTargetReport',
                                                                                                                                    'rmOverAll',
                                                                                                                                    'newLead',
                                                                                                                                    'distProfile',
                                                                                                                                    'distSales',
                                                                                                                                    'targetReport',
                                                                                                                                    'rmOverAll',
                                                                                                                                    'rmTargetReport',
                                                                                                                                    'sReport',
                                                                                                                                    'graphMainReport',
                                                                                                                                    'allRetailer',
                                                                                                                                    'createRM',
                                                                                                                                    'scheme'

					) ,
					"1" => array(
                        'updateDistProfile'
					)
				)
			)

		) ,
		"url" => array(
			""
		)
	) ,
"Admin_Module" => array(
		"list" => array(
			"0" => array(
				"controller" => "shops",
				"action" => array(
					"0" => array(
						'allDistributor',
						'topupDist',
						'transfer',
						'backTransfer',
						'mainReport',
						'overallReport',
						'sReport',
						'lastTransferred',
						'recheckTrans',
						'incentivePullback',
						'graphMainReport'

					) ,
					"1" => array(
						'pullBackApproval',
						'pullbackRefund'
					)
				)
			),
                        "1" => array(
				"controller" => "users",
				"action" => array(
					"0" => array(

					) ,
					"1" => array(
                                            'add',
                                            'edit',
                                            'delete'
					)
				)
			)

		) ,
		"url" => array(
			""
		)
	) ,
	'Limit_Tfr_File' => array(
		"list" => array(
			"0" => array(
				"controller" => "shops",
				"action" => array(
					"0" => array(
						'limitTransfer'
					) ,
					"1" => array(

					)
				)
			)

		) ,
		"url" => array(
			"shops/limitTransfer"
		)
	),
        'Vendor_Panel' => array(
                "list" => array(
                        "0" => array(
                                "controller" => "panels",
                                "action" => array(
                                        "0" => array(
                                                'vendors',
                                                'addEditVendor',
                                                'checkMobileExist'
                                        ),
                                        "1" => array(
                                                'addEditBackVendor',
                                                'changeFlag',
                                                'changeSVNFlag'
                                        )
                                )
                        )
                ),
                "url" => array(
                        "panels/vendors"
                )
        ),
        'Distributor_Incentives' => array(
                "list" => array(
                        "0" => array(
                                "controller" => "shops",
                                "action" => array(
                                        "0" => array(
                                                'distributorIncentives',
                                                'incentiveDistributor'
                                        ),
                                        "1" => array(
                                                'distributorIncentives',
                                                'incentiveDistributor'
                                        )
                                )
                        )
                ),
                "url" => array(
                        "shops/distributorIncentives"
                )
        ),
	"Sim_Panel" => array(
		"list" => array(
			"0" => array(
				"controller" => "sims",
				"action" => array(
					"0" => array(
						'getOperatorsViewJSON',
						'index',
						'getModemsimsDetails',
						'getOperatorWiseSuccessFailureReports',
						'getyellowsimsbymodemid',
						'getHighlights',
						'lastModemTransactions',
						'lastModemSMSes',
						'allBalance',
						'checkSimStatus',
                                                                                                                                                'checkBalance',
                                                                                                                                                'removeSim',
                                                                                                                                                'rechargeType',
                                                                                                                                                'addBlockSimsData',
                                                                                                                                                'checkBlocksimStatus',
                                                                                                                                                'resetSimStatus',
                                                                                                                                                'addNewBlockSimsData',
                                                                                                                                                'getSimComments'
					) ,
					"1" => array(
						'updateSimData',
						'checkPassword',
						'shiftSims',
						'updateIncomingManually',
						'adjustPendings',
						'storeIncomingRequestinRedis',
						'updateClosing',
						'updateBalance',
						'storeBalanceRequestinRedis',
						'storeClosingRequestinRedis',
						'resetModemDevice',
						'stopModemDevice',
						'checkNegDiff',
						'sendBlockSms',
                                                                                                                                                'saveComments'

					)
				)
			),
				"1" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array('modemRequest'

					) ,
					"1" => array(
						'modemRequest',
						'shiftbalance'
					)
				)
			)
		) ,
		"url" => array(
			"sims/index"
		)
	) ,
	"Search_Module" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'search',
						'showComments',
						'userInfo',
						'retInfo',
						'ussdLogs',
						'appNotificationLogs',
						'transaction',
						'openTransaction',
						'tranDate',
						'showTransaction'
					) ,
					"1" => array(
						'createTag',
						'manualRequest',
						'updateCommentsForReversalNew',
						'reverseTransaction',
						'reversalDeclined',
						'regReversal',
						'editRetailer',
						'addComment',
						'pullback',
						'pullbackNew',
                                                'addNewNumber',
                                                'updateCallComplain',
                                                'bbpsComplainRegistration'
					)
				)
			),
			"1" => array(
				"controller" => "cc",
				"action" => array(
					"0" => array(
						'checkPendingCalls'
					) ,
					"1" => array(
						)
				)
			)

		) ,
		"url" => array(
			"panels/search"
		)
	) ,
	"inprocess_txns" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'inProcessTransactions'
					) ,
					"1" => array(
						'manualSuccess',
						'manualFailure',
						'reverseTransaction'
					)
				)
			)
		) ,
		"url" => array(
			"panels/inProcessTransactions"
		)
	) ,
		        "dishtv_txns" => array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "panels",
		                                "action" => array(
		                                        "0" => array(
		                                                'dishtvTxns',
		                                                'processDishTvTransaction'
		                                        ) ,
		                                        "1" => array(
		                                                'manualSuccess',
		                                                'manualFailure',
		                                                'reverseTransaction',
		                                                'processDishTvTransaction'
		                                        )
		                                )
		                        )
		                ) ,
		                "url" => array(
		                        "panels/dishtvTxns"
		                )
		        ) ,
	"notifications" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'retMsg'
					) ,
					"1" => array(
					)
				)
			)
		) ,
		"url" => array(
			"panels/retMsg"
		)
	) ,
	"allRetailers" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'retColl'
					) ,
					"1" => array(
						'changeDistributor',
                                                'changeDistributorForMultipleRetailers'
					)
				)
			),
			"1" => array(
				"controller" => "salesmen",
				"action" => array(
					"0" => array(
					) ,
					"1" => array(
						'blockRetailer',
						'mapSalesman',
						'rentalRetailer'
						)
				)
			),
			"2" => array(
				"controller" => "shops",
				"action" => array(
					"0" => array(
					) ,
					"1" => array(
						'deleteRetailer'
					)
				)
			)
		) ,
		"url" => array(
			"panels/retColl"
		)
	),
	"sale_report" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'retailerSale'
					) ,
					"1" => array(

					)
				)
			)
		) ,
		"url" => array(
			"panels/retailerSale"
		)
	),
	"complaints" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'tranReversal',
						'closedComplaints'
					) ,
					"1" => array(

					)
				)
			)
		) ,
		"url" => array(
			"panels/tranReversal"
		)
	),
	"provider_switching" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'prodVendor',
					) ,
					"1" => array(
                                            'disableVendor',
                                            'blockSlab',
                                            'refreshCache',
                                            'deactivateVendor',
                                            'oprEnable',
                                            'show',
                                            'hide',
                                            'updateOperatorFlag'
					)
				)
			)
		) ,
		"url" => array(
			"panels/prodVendor"
		)
	),
	"from_to_txn" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'tranRange'
					) ,
					"1" => array(
					)
				)
			)
		) ,
		"url" => array(
			"panels/tranRange"
		)
	),
"modem_reconcilation" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'reconsile'
					) ,
					"1" => array(
						'update_reconsile'
					)
				)
			)
		) ,
		"url" => array(
			"panels/reconsile"
		)
	),
"pullback_report" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'pullbackReport',
					) ,
					"1" => array(
						'pullback'
					)
				)
			)
		) ,
		"url" => array(
			"panels/pullbackReport"
		)
	),
"manualReversalReport_report" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'manualReversalReport'
					) ,
					"1" => array(

					)
				)
			)
		) ,
		"url" => array(
			"panels/manualReversalReport"
		)
	),
"complain_report" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'complainReport',
						'reOpenDetails',
						'exceedComplainDetails'
					) ,
					"1" => array(

					)
				)
			)
		) ,
		"url" => array(
			"panels/complainReport"
		)
	),
"salesman_report" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'salesmanReport'
					) ,
					"1" => array(
					)
				)
			)
		) ,
		"url" => array(
			"panels/salesmanReport"
		)
	),/*
"retatiler_kyc" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'retailers',
						'verifySection',
						'rejectSection',
					) ,
					"1" => array(
						'setVerifyFlag',
						'toggleTrained',
						'retailerVerification',
						'verifyDocuments',
						'rejectDocument',
						'deleteDocument',
						'activateMPOS',
						'updateDSN'
					)
				)
			)
		) ,
		"url" => array(
			"panels/retailers"
		)
	),*/
"inproccess_report" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'inprocessReport',
                                                'inProcessTransactionList'
					) ,
					"1" => array(

					)
				)
			)
		) ,
		"url" => array(
			"panels/inprocessReport"
		)
	),
				"inproccess_report_mongo" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'inprocessReportMongo'
					) ,
					"1" => array(

					)
				)
			)
		) ,
		"url" => array(
			"panels/inprocessReportMongo"
		)
	),
"online_lead" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'leads'
					) ,
					"1" => array(
					)
				)
			)
		) ,
		"url" => array(
			"panels/leads"
		)
	),
"vendor_product_mapping" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'vendorsCommissions'
					) ,
					"1" => array(
						'saveVendorCommission'
					)
				)
			)
		) ,
		"url" => array(
			"panels/vendorsCommissions"
		)
	),
"api_recon" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'apiRecon'
					) ,
					"1" => array(
						'saveVendorCommission',
						'check_current_api_txn_status'
					)
				)
			)
		) ,
		"url" => array(
			"panels/apiRecon"
		)
	),
"API Recon" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'getApiReconData',
                                                'uploadReconExcel',
                                                'apiReconData'
					) ,
					"1" => array(
                                                'apiReconSuccessTxn',
                                                'reverseTransaction',
                                                'resolveApiReconTxn'

					)
				)
			)
		) ,
		"url" => array(
			"panels/getApiReconData"
		)
	),
"cc_report" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'ccReport',
						'tagReport',
						'removeTag'
					) ,
					"1" => array(
					)
				)
			)
		) ,
		"url" => array(
			"panels/ccReport"
		)
	),
"retailerRegistrationReport" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'retailerRegistrationReport',
						'graphReport',
					) ,
					"1" => array(
					)
				)
			)
		) ,
		"url" => array(
			"panels/retailerRegistrationReport"
		)
	),
"process_time_and_failer_report" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(
						'getProcessTime',
						'failureInfo',
						'graphReport',
					) ,
					"1" => array(
					)
				)
			)
		) ,
		"url" => array(
			"panels/getProcessTime"
		)
	),
        "new_lead" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(

					) ,
					"1" => array(
						'newLead'
					)
				)
			)
		) ,
		"url" => array(
			"panels/newLead"
		)
	),
        "txn_diff_report" => array(
		"list" => array(
			"0" => array(
				"controller" => "panels",
				"action" => array(
					"0" => array(

					) ,
					"1" => array(
						'tranDiffReport'
					)
				)
			)
		) ,
		"url" => array(
			"panels/tranDiffReport"
		)
	),
	'investment_Report' => array(
		"list" => array(
			"0" => array(
				"controller" => "shops",
				"action" => array(
					"0" => array(
						'investmentReport'
					) ,
					"1" => array(
						'addInvestmentEntry',
						'addInvestedAmount'
					)
				)
			)
		) ,
		"url" => array(
			"shops/investmentReport"
		)
	),
'float_Report' => array(
		"list" => array(
			"0" => array(
				"controller" => "shops",
				"action" => array(
					"0" => array(
						'floatReport'
					) ,
					"1" => array(
								)
				)
			)
		) ,
		"url" => array(
			'shops/floatReport'
		)
) ,
'earning_Report' => array(
		"list" => array(
			"0" => array(
				"controller" => "shops",
				"action" => array(
					"0" => array(
						'earningReport'
					) ,
					"1" => array(
					)
				)
			)
		) ,
		"url" => array(
			'shops/earningReport'
		)
) ,
'float_graph' => array(
		"list" => array(
			"0" => array(
				"controller" => "shops",
				"action" => array(
					"0" => array(
						'floatGraph'
					) ,
					"1" => array(
					)
				)
			)
		) ,
		"url" => array(
			'shops/floatGraph'
		)
),
		'acl_module' => array(
		"list" => array(
			"0" => array(
				"controller" => "acl",
				"action" => array(
					"0" => array(
						'listUser',
						'listGroup',
                                                'outsideAccess',                                                                                                                    'add',
                                                'edit',
                                                'moduleGroup'
					) ,
					"1" => array(
					        'module',
					        'addGroup',
					        'addUser',
					        'giveaccess',
						'removeaccess',
					        'externalaccess',
					        'delete',
                                                'groupUsers',
                                                'moduleAccess'
					)
				)
			)
		) ,
		"url" => array(
			'acl/module'
		)
),
     'list_distributors' => array("list" => array(
						"0" => array(
								"controller" => "shops",
								"action" => array("0" => array("allDistributor"))
						         )
				              ),"url" => array(
								'shops/allDistributor'
							)),

		'incentive_retailer' => array("list" => array(
						"0" => array(
								"controller" => "shops",
								"action" => array("0" => array("refundRetailer"))
						         )
				              ),"url" => array(
								'shops/refundRetailer'
							)),
					'incentive_distributor' => array("list" => array(
						"0" => array(
								"controller" => "shops",
								"action" => array("0" => array("incentiveDistributor"))
						         )
				              ),"url" => array(
								'shops/incentiveDistributor'
							)),
				'changeDistibutor_MobileNo' => array("list" => array(
						"0" => array(
								"controller" => "shops",
								"action" => array("0" => array("changeDistributorMobileNo"))
						         )
				              ),"url" => array(
								'shops/changeDistributorMobileNo'
							)),

                    "Wholesaler registration"=>array(
                                                     "list"=>array(
                                                                    "0"=>array(
                                                                     "controller"=>"wsregister",
                                                                     "action"=>array(
                                                                      "0"=>array(
                                                                                 "index",
                                                                                 "checkUnique",
                                                                                  "listws"
                                                                                ),
                                                                       "1"=>array(
                                                                                  "registerWholesaler"
                                                                                  )) )
                                                                        ),"url"=>array(
                                                                                   'wsregister/index'
                                                                            )
                                                        ),

				 "Products"=>array(
                                "list"=>array(
                                            "0"=>array(
                                                "controller"=>"products",
                                                  "action"=>array(
                                                      "0"=>array(
                                                            "index",
															   "listing_apm",
																"listing_lvm",
															  "a_p_mapping",
															  "local_vendor_mapping",

                                                      ),
                                                      "1"=>array(
                                                          "edit","editFormEntry","l_v_m_entry","a_p_m_entry"
                                                      )
                                                  )
                                            )
                                ),
						       "url" => array("products/index")
                    ),
                     "Ivruploads"=>array(
                                "list"=>array(
                                            "0"=>array(
                                                "controller"=>"products",
                                                  "action"=>array(
                                                      "0"=>array(
                                                                 "uploadivr"
                                                      ),
                                                      "1"=>array(

                                                      )
                                                  )
                                            )
                                ),
						       "url" => array("products/uploadivr")
                                ),
                     "AllTxnReports"=>array(
                                "list"=>array(
                                            "0"=>array(
                                                "controller"=>"products",
                                                  "action"=>array(
                                                      "0"=>array(
                                                                 "allTransaction"
                                                      ),
                                                      "1"=>array(

                                                      )
                                                  )
                                            )
                                ),
						       "url" => array("products/allTransaction")
                                ),
                    "Community Panel"=>array(
                                "list"=>array(
                                            "0"=>array(
                                                "controller"=>"community",
                                                  "action"=>array(
                                                      "0"=>array(
                                                                "uploadPanel"
                                                      ),
                                                      "1"=>array(
                                                           "uploadImages","sendFeed","shortImages","typeImages","sliderImages","bloghtml","updateFeed","getFeedReport"
                                                      )
                                                  )
                                            )
                                ),
						       "url" => array("community/uploadPanel")
                                ),

                                "Heatmap" => array(
                                        "list" => array(
                                                "0" => array(
                                                        "controller" => "heatmap",
                                                                "action" => array(
                                                                    "0" => array(
                                                                            "index", "filterCityArea"
                                                                    ),
                                                                    "1" => array()
                                                        )
                                                )
                                        ),
                                       "url" => array("heatmap/index")
                                ),
                                "Regional Distributors Retailers" => array(
                                        "list" => array(
                                                "0" => array(
                                                        "controller" => "heatmap",
                                                                "action" => array(
                                                                    "0" => array(
                                                                            "distRetMap", "filterCityArea"
                                                                    ),
                                                                    "1" => array()
                                                        )
                                                )
                                        ),
                                       "url" => array("heatmap/distRetMap")
                                ),

                                "C2D Reports" => array(
                                        "list" => array(
                                                "0" => array(
                                                        "controller" => "c2d",
                                                                "action" => array(
                                                                    "0" => array(
                                                                            "clickToCallListing",
                                                                            "postInterestListing",
                                                                            "c2dPost",
                                                                            "viewComment"
                                                                    ),
                                                                    "1" => array(
                                                                            "addOrderTag",
                                                                            "addComment"
                                                                    )
                                                        )
                                                )
                                        ),
                                       "url" => array("c2d/clickToCallListing")
                                ),

                                "Marketing Notification" => array(
                                        "list" => array(
                                                "0" => array(
                                                        "controller" => "events",
                                                                "action" => array(
                                                                    "0" => array(
                                                                            "index",
                                                                            "callEvent",
                                                                            "serviceAlert"
                                                                    ),
                                                                    "1" => array(
                                                                            "addEvent",
                                                                            "uploadImage",
                                                                            "generateServiceAlert"
                                                                    )
                                                        )
                                                )
                                        ),
                                       "url" => array("events/index")

                                ),

                               'Incentive_Distributor' => array(
                                    "list" => array(
                                        "0" => array(
                                        "controller" => "shops",
                                        "action" => array(
                                        "0" => array(
                                        'incentiveDistributor'
                                        ) ,
                                        "1" => array(

                                        )
                                        )
                                        )

                                    ) ,
                                    "url" => array(
                                            "shops/incentiveDistributor"
                                        )
                                    ),

                                'Vendor_Panel' => array(
                                    "list" => array(
                                        "0" => array(
                                            "controller" => "panels",
                                            "action" => array(
                                                "0" => array(
                                                'vendors',
                                                'addEditVendor',
                                                'checkMobileExist'
                                                ) ,
                                                "1" => array(
                                                'addEditBackVendor',
                                                'changeFlag'
                                                )
                                            )
                                        )
                                    ) ,
                                    "url" => array(
                                        "panels/vendors"
                                    )
                                ),

                            'SMS Templates' =>  array(
                                        "list" => array(
                                                "0" => array(
                                                        "controller" => "smstemplates",
                                                                "action" => array(
                                                                    "0" => array(
                                                                            "index",
                                                                            "edit",
                                                                            "verify",
                                                                            "verifyWithOthers"
                                                                    ),
                                                                    "1" => array(
                                                                            "add",
                                                                            "update",
                                                                            "delete"
                                                                    )
                                                        )
                                                )
                                        ),
                                       "url" => array("smstemplates/index")
                                ),
                            'Recharge Flow Rules' =>  array(
                                        "list" => array(
                                                "0" => array(
                                                        "controller" => "rechargeflowrules",
                                                                "action" => array(
                                                                    "0" => array(
                                                                            "index",
                                                                            "getDistributors",
                                                                            "getRetailers",
                                                                    ),
                                                                    "1" => array(
                                                                            "saveRule",
                                                                            "toggleRuleForRetailers",
                                                                    )
                                                        )
                                                )
                                        ),
                                       "url" => array("rechargeflowrules/index")
                                ),
		        'Smartpay' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "smartpay",
		                                "action" => array(
		                                        "0" => array(
		                                                "checkUserDocs",
		                                                "updateUserDocs",
		                                                "saveDeviceComments",
		                                                "saveCspComments",
		                                                "saveTIDComments",
		                                                "getRetailerList",
		                                                "getSettlementDetails",
		                                                "saveSettlementComments",
		                                                "downloadSettlementDetails",
		                                                "formatExcelDataForIcici",
		                                                "formatExcelDataForAxisIMPS",
		                                                "uploadExcel",
		                                        ),
		                                        "1" => array()
		                                )
		                        )
		                ),
		                "url" => array("smartpay/getSettlementDetails")
		        ),
				'Microfinance' =>  array(
						"list" => array(
								"0" => array(
										"controller" => "microfinance",
										"action" => array(
												"0" => array("setEmiDetails","setLoanDetails"),
												"1" => array("getLoanDetails", "verifyDocs", "rejectDocs", "submitToNBFC", "rejectApplication", "rejectLead", "approveLead", "disburseLoan"),
										)
								)
						),
						"url" => array("microfinance/getLoanDetails")
				),
		        'Information Management' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "docmanagement",
		                                "action" => array(
		                                        "0" => array(
														"getUserDocuments",
														"getActiveUsers",
		                                                "userProfile",
		                                                "getUserInformation",
                                                                "getUserSectionReport",
                                                                "sectionStatusSummaryReport"
		                                        ),
		                                        "1" => array(
                                                                "uploadDocs",
		                                                "verifyUserDocs",
                                                        )
		                                )
		                        )
		                ),
		                "url" => array("docmanagement/getUserInformation")
		        ),
		        'Service Management' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "servicemanagement",
		                                "action" => array(
		                                        "0" => array(
														"getServices",
		                                                "index"
		                                        ),
		                                        "1" => array(
		                                                "addUpdateServices",
														"reactivateService",
                                                        "pullbackKit",
                                                        "requestService"
		                                        )
		                                )
		                        )
		                ),
		                "url" => array("servicemanagement/")
		        ),
		        'Active Retailers' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "activeretailers",
		                                "action" => array(
		                                        "0" => array(
		                                                "index"
		                                        ),
		                                        "1" => array()
		                                )
		                        )
		                ),
		                "url" => array("activeretailers/")
		        ),
		        'Kit Report' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "kitreport",
		                                "action" => array(
		                                        "0" => array(
		                                                "index",
                                                        "getKitEntries"
		                                        ),
		                                        "1" => array()
		                                )
		                        )
		                ),
		                "url" => array("kitreport/")
		        ),
		        'CC Avenue Complaints' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "panels",
		                                "action" => array(
		                                        "0" => array(
		                                                "ccaComplaints"
		                                        ),
		                                        "1" => array(
		                                        )
		                                )
		                        )
		                ),
		                "url" => array("panels/ccaComplaints")
		        ),
		        'Pay1 Remit Panel' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "dmt",
		                                "action" => array(
		                                        "0" => array(
		                                                "index",
		                                                "retailersReport",
		                                                "sendersReport",
		                                                 "transactionReport",
		                                                "dmtFromto",
                                                                                     "dmtCheckRefund",
		                                                "beneficiaryData",
                                                                                     "accvalidationreport","dmtCommentSystem"
		                                        ),
		                                        "1" => array("dmtAdminPanel","dmtUpdateNotification","serviceToggle","refundPanel")
		                                )
		                        )
		                ),
		                "url" => array("dmt/index")
		        ),
		        'Accounting Module' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "accounting",
		                                "action" => array(
		                                        "0" => array(
		                                                "txnUpload",
                                                                "accountSpecificTxn",
                                                                "bankTxnListing"
		                                        ),
		                                        "1" => array(
                                                                "autoUpload",
                                                                "clearNonclearedTxn"
		                                        )
		                                )
		                        )
		                ),
                             "url" => array("accounting/txnUpload")
		        ),
		        'RM Module' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "rm",
		                                "action" => array(
		                                        "0" => array(
		                                                "rmAttendance",
                                                                "rmAttendanceDetail",
                                                                "rmMapRoutine",
                                                                "rmTask",
                                                                "rmTaskDetail",
                                                                "rmRoutine",
		                                        )
		                                )
		                        )
		                ),
                             "url" => array("rm/rmAttendance")
		        ),
		        'Retailer Incentive Upload' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "shops",
		                                "action" => array(
		                                        "0" => array(
		                                                "retailerIncentives"
		                                        )
		                                )
		                        )
		                ),
                             "url" => array("shops/retailerIncentives")
		        ),
		        'Finance Module' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "finance",
		                                "action" => array(
		                                        "0" => array(
		                                                "overview",
                                                                "pnl",
                                                                "balanceSheet",
                                                                "verifyOtp"
		                                        )
		                                )
		                        )
		                ),
                        "url" => array("finance/pnl")
		        ),
		        'Vendor Recon' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "accounting",
		                                "action" => array(
		                                        "0" => array(
		                                                "vendorSearch"
		                                        ),
		                                        "1" => array(
                                                                "vendorDashboard"
		                                        )
		                                )
		                        )
		                ),
                             "url" => array("accounting/vendorSearch")
		        ),
		        'Float Report' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "accounting",
		                                "action" => array(
		                                        "0" => array(
		                                                "floatReport"
		                                        ),
		                                )
		                        )
		                ),
                             "url" => array("accounting/floatReport")
		        ),
		        'PayU Txns' =>  array(
                        "list" => array(
                                "0" => array(
                                        "controller" => "accounting",
                                        "action" => array(
                                                "0" => array(
                                                        "payuSalesReport"
                                                )
                                        )
                                )
                        ),
                "url" => array("accounting/payuSalesReport")
                ),
		        'Txn Recon' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "accounting",
		                                "action" => array(
		                                        "0" => array(
		                                                "txnRecon",
                                                                "txnSearch",
                                                                "dashboard"
		                                        ),
		                                        "1" => array(
                                                                "displayTxnRecon"
		                                        )
		                                )
		                        )
		                ),
                             "url" => array("accounting/txnSearch")
		        ),
		        'Leads Management' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "leads",
		                                "action" => array(
		                                        "0" => array(
		                                                "index",
                                                                "salesList",
                                                                "leadList",
                                                                "customerDetail",
                                                                "report",
                                                                "assign_lead",
                                                                "format",
                                                                "employeeDetails"
		                                        ),
		                                        "1" => array(
                                                                "assign_lead_data",
                                                                "displayData",
                                                                "leadDetail"
		                                        )
		                                )
		                        )
		                ),
                             "url" => array("leads/leadList")
		        ),
		        'Retailer_scheme_panel' =>  array(
		                "list" => array(
		                        "0" => array(
		                                "controller" => "incentives",
		                                "action" => array(
		                                        "0" => array(
		                                                "schemePanel",
		                                                "getRetailerSchemes"
		                                        ),
		                                        "1" => array(
		                                                "uploadRetailerSchemesDetails",
                                                                "deleteScheme"
		                                        )
		                                )
		                        )
		                ),
		                "url" => array("incentives/getRetailerSchemes")
		        ),
                        'txn_report' =>  array(
                            "list" => array(
                                    "0" => array(
                                            "controller" => "panels",
                                            "action" => array(
                                                    "0" => array(
                                                            "downloadTxnReport"
                                                        )
                                                )
                                        )
                                ),
                            "url" => array("panels/downloadTxnReport")
                            ),
                    'distributor_incentive' =>  array(
                            "list" => array(
                                    "0" => array(
                                            "controller" => "scheme",
                                            "action" => array(
                                                    "0" => array(
                                                            "getscheme",
                                                            "viewscheme",
                                                            "getSchemeDistributor",
                                                            "schemeReport",
                                                            "sendSchemeNotification"
                                                        ),
                                                "1"=>array(
                                                        "adddistToScheme",
                                                        "updatescheme",
                                                        "deleteDistributorScheme",
                                                        "addscheme",
                                                        "editSchemeDistributor",
                                                        "giveDistributorScheme"
                                                    )
                                                )
                                        )
                                ),
                            "url" => array("scheme/viewscheme")
                            ),

                        'slab_report' =>  array(
                            "list" => array(
                                    "0" => array(
                                            "controller" => "panels",
                                            "action" => array(
                                                    "0" => array(
                                                            "slabReport",
                                                            "slabInfo"
                                                        ),
                                                    "1" => array(
                                                            "slabDataUpdate",
                                                        "slabchangeFlag",
                                                        "slabCreation"
                                                        )
                                                )
                                        )
                                ),
                            "url" => array("panels/slabReport")
                            ),
                            'fas_report' =>  array(
                            "list" => array(
                                    "0" => array(
                                            "controller" => "panels",
                                            "action" => array(
                                                    "0" => array(
                                                            "failureAfterSuccess"
                                                        ),
                                                    "1" => array(
                                                            "fasManualFailure"
                                                        )
                                                )
                                        )
                                ),
                            "url" => array("panels/failureAfterSuccess")
                            ),
                            'recovery_panel' =>  array(
                            "list" => array(
                                    "0" => array(
                                            "controller" => "panels",
                                            "action" => array(
                                                    "0" => array(
                                                            "getRecoveryData",
                                                            "getComments",
                                                            "getUserProfile"
                                                        ),
                                                    "1" => array(
                                                            "recoverAmount",
                                                            "reactivateService",
                                                            "refundAmount"
                                                        )
                                                )
                                        )
                                ),
                            "url" => array("panels/getRecoveryData")
                            ),
                            'insurance_panel' =>  array(
                            "list" => array(
                                    "0" => array(
                                            "controller" => "insurance",
                                            "action" => array(
                                                    "0" => array("index","mutualFundPanel"),
                                                    "1" => array()
                                                )
                                        )
                                ),
                                "url" => array("insurance/index")
                            ),
                            'distributor_limit' =>  array(
                                "list" => array(
                                        "0" => array(
                                                "controller" => "shops",
                                                "action" => array(
                                                        "0" => array(),
                                                        "1" => array(
                                                            "distributorLimit"
                                                            )
                                                    )
                                            )
                                    ),
                                    "url" => array("shops/distributorLimit")
                            ),
                            'distributor_bankcard_mapping' =>  array(
                                "list" => array(
                                        "0" => array(
                                                "controller" => "accounting",
                                                "action" => array(
                                                        "0" => array(
                                                            "distributorList",
                                                            "banklist"
                                                        ),
                                                        "1" => array(
                                                            "distBankMapping"
                                                            )
                                                    )
                                            )
                                    ),
                                    "url" => array("accounting/distBankMapping")
                            ),
                            'debit_credit_report' =>  array(
                                "list" => array(
                                        "0" => array(
                                                "controller" => "shops",
                                                "action" => array(
                                                        "0" => array("debitCreditReport"),
                                                        "1" => array()
                                                    )
                                            )
                                    ),
                                "url" => array("shops/debitCreditReport")
                            ),
                            'service_integration_panel' =>  array(
                            "list" => array(
                                    "0" => array(
                                            "controller" => "serviceintegration",
                                            "action" => array(
                                                    "0" => array(
                                                            "servicesForm",
                                                            "getFieldData",
                                                            "getKYCData",
                                                            "servicesPlans",
                                                            "setPlansDetails",
                                                            "servicesProducts",
                                                            "ListServicePartner",
                                                            "serviceVendor",
                                                            "prodListing",
                                                            "getProductPlanDetails",
                                                            "setVendorDetails",
                                                    ),
                                                    "1" => array("servicesInsert",
                                                                 "serviceFields",
                                                                 "updserviceFields",
                                                                 "setKYCData",
                                                                 "insKYCData",
                                                                 "setProductData",
                                                                 "updPlanDetails",
                                                                 "updProductsDetails",
                                                                 "InsProductDetails",
                                                                 "InsPlanDetails",
                                                                 "updPlansDetails",
                                                                 "InsServicePartner",
                                                                 "updServicePartner",
                                                                 "InsVendor",
                                                                 "updVendors",

                            )
                                                )
                                        )
                                ),
                            "url" => array("serviceintegration/servicesForm")
                            ),
                            'irctc_panel' =>  array(
                                "list" => array(
                                        "0" => array(
                                                "controller" => "irctc",
                                                "action" => array(
                                                        "0" => array(),
                                                        "1" => array("index","getUserids","refundtxn","refundtxnTwo","getUsersDetails",)
                                                    )
                                            )
                                    ),
                                "url" => array("irctc/index")
                            ),
                           'pan_service_panel' =>  array(
                                "list" => array(
                                        "0" => array(
                                                "controller" => "pan_services",
                                                "action" => array(
                                                        "0" => array("panServicePanel"),
                                                        "1" => array("panCouponProccess","panDetailUpdate")
                                                    )
                                            )
                                    ),

                             "url" => array("pan_services/panServicePanel")
                            ),
                            'travel_panel' =>  array(
                                "list" => array(
                                        "0" => array(
                                                "controller" => "travel",
                                                "action" => array(
                                                        "0" => array("index","travelRetailersReport","travelTransactionReport","travelFromTo"),
                                                        "1" => array("travelTicket","travelpkcs7_pad","travelencrypt",)
                                                    )
                                            )
                                    ),
                                "url" => array("travel/index")
                            ),
                                'kits_delivery_panel' =>  array(
                                "list" => array(
                                        "0" => array(
                                                "controller" => "kit_delivery_system",
                                                "action" => array(
                                                        "0" => array("kitDeliveryPanel"),
                                                        "1" => array("kitDeliveryUpdate")
                                                    )
                                            )
                                    ),
                                "url" => array("kit_delivery_system/kitDeliveryPanel")
                            ),
                               'service_registration_panel' =>  array(
                                "list" => array(
                                        "0" => array(
                                                "controller" => "kit_delivery_system",
                                                "action" => array(
                                                        "0" => array("serviceRegistration"),
                                                        "1" => array("UpdserviceRegistration","getkitsData")
                                                    )
                                            )
                                    ),
                                "url" => array("kit_delivery_system/serviceRegistration")
                            ),
                           'leads_service_panel' =>  array(
                                "list" => array(
                                        "0" => array(
                                                "controller" => "pan_services",
                                                "action" => array(
                                                        "0" => array("leadReport"),
                                                        "1" => array("leadProccess","leadDetailUpdate")
                                                    )
                                            )
                                    ),
                             "url" => array("pan_services/leadReport")),
                 );



				$config['acl']['bypass'] =  array(
											'alerts' => array(
												'salesDownDistributor',
												'salesDownRetailer',
												'salesDownState',
												'salesForecast',
												'retailersGraduallyDropped',
												'retailersDroppedOut',
												'distributorRetailersCount',
												'insertRetailersGradualDropData',
												'insertRetailersDroppedOutData',
												'insertDataPostCallDate',
												'helperInsertDataPostCallDate',
												'systemAlerts',
										        'retailerSales'
	                                       ) ,
											'apis' => array(
													'*'
												) ,
											'b2cextender' => array(
													'*'
												),
											'cc' => array(
											'retMisscall',
											'test',
											'checkPendingCalls'
										),
						                'cashpayment' => array(
												'collection_req',
								                 'check_status',
												 'get_transaction_list',
												 'cancel_transaction',
												 'update_callback_api',
												 'get_client_detail_by_ref_id',
												 'transaction_detail_by_request_id',
												 'cashpayment_api_manager',
												 'get_pending_request_by_mobile',

																	) ,
																	'crons' => array(
																		'*'
																	) ,
																	'distributors' => array(
																		'*'
																	) ,

													'invs' => array(
														'*'
													) ,
													'ivr' => array(
														'*'
													) ,
													'promotions' => array(
														'*'
													) ,
													'retailers'=> array(
														'*'
													),
									'salesmen'=> array(
										'updateRetailerLogs',
										'updateDistributorLogs',
										'correctOldEntries',
										'updateDistributorsLogsQuarter',
									) ,
								'ussdapis'=> array(
										'*'
									) ,
								'recharges'=> array(
										'*'
									) ,
				                                'accounting'=> array(
				                                                '*'
				                                ) ,
				                                'leads'=> array(
				                                                '*'
				                                ) ,
								'users'=> array(
										'*'
									) ,
				        'platform'=> array(
				                '*'
				        ) ,
				        'relationship_manager'=> array(
				                '*'
				        ) ,
				        'script'=> array(
				                '*'
				        ) ,
						'modemalerts'=> array(
								'*'
						) ,
				        'bridgeapis'=> array(
				                '*'
				        ) ,
				        'invoices'=> array(
				                '*'
				        ),
                                                                'panels'=> array(
                                                                        'view',
                                                                        'errorMsg',
                                                                        'request'
                                                                ) ,
                                                                'chats'=> array(
                                                                        'generateReport'
                                                                ) ,
                                                                'shops'=> array(
                                                                        '*'
                       						),
                                                        //   'servicemanagement'=>array('getDistributorkits'),

						       'acl'=>array('setUserAccess','insertModule','insertExistingUser'),

                                 );




