<?php
class ApiRechargeComponent extends Object{
    var $components = array('General', 'Shop', 'Recharge', 'Jio');
    var $mapping = array(
            'mobRecharge'=>array(
                    '1'=>array('operator'=>'Aircel', 'opr_code'=>'AC',
                            'flexi'=>array('id'=>'1', 'oss'=>'AIC', 'pp'=>'85', 'payt'=>'7', 'cbz'=>'RC', 'rdu'=>'ARCL', 'uva'=>'AIRC', 'anand'=>'aircel', 'apna'=>'AIR', 'magic'=>'RC', 'rio'=>'RC', 'gem'=>'AS', 'rkit'=>1, 'joinrec'=>4, 'mypay'=>2, 'smsdaak'=>'ACP',
                            'hitecrec'=>'RC', 'practic'=>'Aircel', "simple"=>"AIR", "manglam"=>"RC", "bulk"=>'RC', 'aporec'=>20 ,'speedrec'=>2,'ctswallet'=>'AC','bigshoprec'=>1029,'indiaone'=>'RC','emoney'=>'AS','speedpay'=>'Aircel','swamiraj'=>'AC','swamirapi'=>2,'a1rec'=>11,'unrec'=>'AC','ambika'=>'AC','thinkwal'=>'4','champrec'=>'RC','yashicaent'=>1,'zplus'=>'RC','ka2zrec'=>'Aircel','urec'=>'RC','pay1all'=>'As','prec' => 'PAI','ashw1'=>1,'pay1click'=>'RAC','stelcom'=>'7','manimaster'=>'RC','wellborn' => '7','nishi' => '1','supersaas' => '1','balajisaas' => '1','pratisaas' => '1','osssaas' => '1','rajsaas' => '1','kumarsaas' => '1','techmate' => 'AC'), 'voucher'=>array('id'=>'', 'oss'=>'AIC', 'pp'=>'85')),
                    '2'=>array('operator'=>'Airtel', 'opr_code'=>'AT',
                            'flexi'=>array('id'=>'2', 'oss'=>'AR', 'pp'=>'51', 'payt'=>'1302', 'cbz'=>'RA', 'rdu'=>'ARTL', 'uva'=>'AIRT', 'uni'=>'211', 'anand'=>'airtel', 'apna'=>'A', 'magic'=>'RA', 'rio'=>'RC', 'gem'=>'AT', 'durga'=>'AirTel', 'rkit'=>2, 'a2z'=>2, 'joinrec'=>42,
                                    'mypay'=>1, 'smsdaak'=>'ATP', 'aporec'=>2, 'hitecrec'=>'RA', 'practic'=>'Airtel', "simple"=>"1", "krac"=>"RA", "manglam"=>"RA", "bulk"=>'RA', "bimco"=>"AT", "rajan"=>'AT', 'simpleapi'=>'RA', 'indicore'=>'AirTel','swamiraj'=>'AT','swamirapi'=>'1','speedrec'=>1,'ctswallet'=>'AR','bigshoprec'=>2,'indiaone'=>'RA','emoney'=>'AT','speedpay'=>'Airtel','a1rec'=>1,'unrec'=>'AT','ambika'=>'AT','thinkwal'=>'2','champrec'=>'RA','yashicaent'=>2,'zplus'=>'RA','ka2zrec'=>'Airtel','roundpay'=>3,'maxxrec'=>1,'erecpoint'=> 1,'urec'=>'RA','pay1all'=>'At','prec' => 'PA','ashw1'=>2,'pay1click'=>'RAR','kracrec'=>'Airtel','stelcom'=>'8','manimaster'=>'RA','wellborn' => '8','nishi' => '2','supersaas' => '2','myetopup'=> '1','balajisaas' => '2','pratisaas' => '2','osssaas' => '2','rajsaas' => '2','kumarsaas' => '2','techmate' => 'AT','qubarobo' => 'AT','varsharobo' => 'AT',
                                'aventidea' => 'AT','threeplus' => 'AT','pintoosls' => 'AT','aventerp' => 'AT','jasscomm' => 'AT','nkagency' => 'AT','anilkir' => 'AT','jeevnrkh' => 'AT','payclick' => 'RA','starcom' => 'AT','moderntrad' => 'AT','vjyotrader' => 'AT','aftrader' => 'AT','starmbrobo' => 'AT'), 'voucher'=>array('id'=>'', 'oss'=>'AR', 'pp'=>'51','swamiraj'=>'AT')),
                    '3'=>array('operator'=>'BSNL', 'opr_code'=>'CG',
                            'flexi'=>array('id'=>'3', 'oss'=>'BSN', 'pp'=>'53', 'payt'=>'1', 'cbz'=>'BR', 'rdu'=>'BSNL', 'uva'=>'BSNL', 'magic'=>'TB', 'rio'=>'TB', 'gem'=>'BS', 'gitech'=>'HBST', 'rkit'=>5, 'joinrec'=>10, 'mypay'=>4, 'smsdaak'=>'BGP', 'hitecrec'=>'TB', 'practic'=>'BSNL',
                             "simple"=>"BT", "manglam"=>"RB",'speedrec'=>4,'ctswallet'=>'BT', 'ambika'=>'BS','bigshoprec'=>9,'indiaone'=>'RB','emoney'=>'BS','speedpay'=>'BSNL','swamiraj'=>'BT','swamirapi'=>4,'a1rec'=>2,'unrec'=>'BT','thinkwal'=>'5','champrec'=>'RB','yashicaent'=>3,'zplus'=>'TB','ka2zrec'=>'BSNL','roundpay'=>4,'urec'=>'RB','pay1all'=>'Bs','prec' =>'PB','ashw1'=>3,'pay1click'=>'RBT','stelcom'=>'10','manimaster'=>'RB','wellborn' => '10','nishi' => '3','supersaas' => '3','myetopup'=> '2','balajisaas' => '3','pratisaas' => '3','osssaas' => '3','rajsaas' => '3','kumarsaas' => '3','techmate' => 'BT'),
                            'voucher'=>array('id'=>'34', 'oss'=>'BSN', 'pp'=>'53', 'payt'=>'1', 'cbz'=>'BV', 'rdu'=>'BR', 'uva'=>'BSNL', 'magic'=>'RB', 'rio'=>'RB', 'gem'=>'BR', 'gitech'=>'HBSV', 'rkit'=>5, 'joinrec'=>10, 'smsdaak'=>'BVP', 'mypay'=>4, 'hitecrec'=>'TB', "simple"=>"BR",
                                    "manglam"=>"TB", 'ambika'=>'BR','bigshoprec'=>1044,'emoney'=>'BR','speedpay'=>'BSNL','a1rec'=>32,'thinkwal'=>'25','champrec'=>'TB','yashicaent'=>34,'zplus'=>'RB','urec'=>'TB','pay1all'=>'Br','prec' =>'PBS','ashw1'=>34,'pay1click'=>'SBT','stelcom'=>'9','manimaster'=>'RB','wellborn' => '9','ka2zrec'=>'BSNL','nishi' => '34','supersaas' => '34','myetopup'=> '32','balajisaas' => '34','pratisaas' => '34','osssaas' => '34','rajsaas' => '34','kumarsaas' => '34','techmate' => 'BV')),
                    '4'=>array('operator'=>'Idea', 'opr_code'=>'ID',
                            'flexi'=>array('id'=>'4', 'oss'=>'IDE', 'pp'=>'107', 'payt'=>'5', 'cbz'=>'RI', 'rdu'=>'IDEA', 'uva'=>'IDEA', 'uni'=>'233', 'anand'=>'idea', 'apna'=>'I', 'magic'=>'RI', 'rio'=>'RI', 'gem'=>'ID', 'durga'=>'Idea', 'rkit'=>6, 'joinrec'=>48, 'mypay'=>11,
                                    'smsdaak'=>'IDP', 'rio2'=>'RI', 'aporec'=>11, 'hitecrec'=>'RI', 'practic'=>'Idea', "simple"=>"I", "manglam"=>"RI", "bulk"=>"RI", "bimco"=>"IC", "rajan"=>"ID", "payrecharge"=>"Idea", "shivaidea"=>'RI', 'indicore'=>'Idea', 'swamiraj'=>'ID','swamirapi'=>11, 'maxrecharge'=>'I','ambika'=>'ID','ambikaroam'=>'ID','a1rec'=>'4','IndiaRec'=>'Idea','unrec'=>'ID' ,'speedrec'=>11,'ctswallet'=>'ID','bigshoprec'=>7,'indiaone'=>'RI','emoney'=>'ID','speedpay'=>'Idea','thinkwal'=>'3','champrec'=>'RI','yashicaent'=>4,'zplus'=>'RI','ka2zrec'=>'Idea','roundpay'=>12,'maxxrec'=>4,'urec'=>'RI','pay1all'=>'Id','prec' =>'PI','ashw1'=>4,'pay1click'=>'RID','kracrec'=>'Idea','stelcom'=>'11','manimaster'=>'RI','wellborn' => '11','nishi' => '4','supersaas' => '4','myetopup'=> '4','balajisaas' => '4','pratisaas' => '4','osssaas' => '4','rajsaas' => '4','kumarsaas' => '4','techmate' => 'ID','qubarobo' => 'ID','varsharobo' => 'ID',
                                'aventidea' => 'ID','threeplus' => 'ID','pintoosls' => 'ID','aventerp' => 'ID','jasscomm' => 'ID','nkagency' => 'ID','anilkir' => 'ID','jeevnrkh' => 'ID','payclick' => 'RI'),
                            'voucher'=>array('id'=>'', 'oss'=>'IDE', 'pp'=>'107','ambika'=>'ID','a1rec'=>'4','IndiaRec'=>'Idea','unrec'=>'ID')),
                    '5'=>array('operator'=>'Loop/BPL', 'opr_code'=>'LM', 'flexi'=>array('id'=>'5', 'oss'=>'LOP', 'pp'=>'1', 'payt'=>'14', 'cbz'=>'BP', 'rdu'=>'LOOP', 'uva'=>'LOOP', 'apna'=>'LM', 'gem'=>'LP','ctswallet'=>'LO','bigshoprec'=>1032,'indiaone'=>'RL','yashicaent'=> 5,'zplus'=>'RR','urec'=>'RL','pay1all'=>'Rl','ashw1'=>5,'pay1click'=>'RAC','nishi' => '5','supersaas' => '5','balajisaas' => '5','osssaas' => '5','rajsaas' => '5','kumarsaas' => '5','techmate' => 'LM'), 'voucher'=>array('id'=>'', 'oss'=>'LOP', 'pp'=>'1')),
                    '6'=>array('operator'=>'MTS', 'opr_code'=>'MT',
                            'flexi'=>array('id'=>'6', 'oss'=>'MTS', 'pp'=>'133', 'payt'=>'278', 'cbz'=>'DM', 'rdu'=>'MTS', 'rio'=>'RM', 'uva'=>'MTS', 'gem'=>'MT', 'gitech'=>'HMTS', 'joinrec'=>13, 'rkit'=>'13', 'smsdaak'=>'MTP', 'practic'=>"MTS", "simple"=>"M", "manglam"=>"RM" ,'speedrec'=>20,'ctswallet'=>'MT','bigshoprec'=>1012,'indiaone'=>'MTS','emoney'=>'MT','speedpay'=>'MTS','a1rec'=>16,'unrec'=>'MTS','thinkwal'=>'7','champrec'=>'RM','yashicaent'=> 6,'zplus'=>'RM','urec'=>'MTS','pay1all'=>'Mts','prec' =>'PM','ashw1'=>6,'stelcom'=>'14','manimaster'=>'MTS','wellborn' => '14','nishi' => '6','supersaas' => '6','balajisaas' => '6','pratisaas' => '6','osssaas' => '6','rajsaas' => '6','kumarsaas' => '6','techmate' => 'MT'),
                            'voucher'=>array('id'=>'', 'oss'=>'MTS', 'pp'=>'133')),
                    '7'=>array('operator'=>'Reliance CDMA', 'opr_code'=>'RC',
                            'flexi'=>array('id'=>'7', 'oss'=>'RC', 'pp'=>'23', 'payt'=>'1303', 'cbz'=>'RR', 'rdu'=>'RIMC', 'uva'=>'RELC', 'uni'=>'244', 'apna'=>'RC', 'magic'=>'RL', 'rio'=>'RR', 'gem'=>'RC', 'gitech'=>'HREC', 'rkit'=>14, 'a2z'=>1, 'joinrec'=>20, 'mypay'=>6, 'practic'=>"RelianceCDMA",
                                    "simple"=>"RC", "manglam"=>"RG" ,'speedrec'=>6,'ambika'=>'RC','indiaone'=>'RR','smsdaak'=>'RGP','speedpay'=>'Reliance','swamiraj'=>'RC','a1rec'=>3,'unrec'=>'RC','thinkwal'=>'9','champrec'=>'RR','yashicaent'=> 7,'zplus'=>'RR','urec'=>'RR','pay1all'=>'Rg','prec' =>'PRC','ashw1'=>7,'stelcom'=>'16','manimaster'=>'RR','wellborn' => '16','ka2zrec'=>'Reliance','nishi' => '7','supersaas' => '7','balajisaas' => '7','pratisaas' => '7','osssaas' => '7','rajsaas' => '7','kumarsaas' => '7','techmate' => 'RC'), 'voucher'=>array('id'=>'', 'oss'=>'RC', 'pp'=>'23')),
                    '8'=>array('operator'=>'Reliance GSM', 'opr_code'=>'RG',
                            'flexi'=>array('id'=>'8', 'oss'=>'RC', 'pp'=>'84', 'payt'=>'6', 'cbz'=>'RR', 'rdu'=>'RIMG', 'uva'=>'RELG', 'uni'=>'244', 'apna'=>'RG', 'magic'=>'RR', 'rio'=>'RR', 'gem'=>'RG', 'ecom'=>'R', 'gitech'=>'HREG', 'rkit'=>16, 'a2z'=>2, 'joinrec'=>7, 'mypay'=>7, 'smsdaak'=>'RGP',
                                    'practic'=>"RelianceCDMA", "simple"=>"RG", "manglam"=>"RG", 'indicore'=>'Reliance','mypay'=>'7' ,'speedrec'=>7,'ctswallet'=>'RG','ambika'=>'RG','bigshoprec'=>1022,'indiaone'=>'RG','emoney'=>'RG','aporec'=>62,'speedpay'=>'Reliance','swamiraj'=>'RG','swamirapi'=>'RC','swamirapi'=>'RG','a1rec'=>29,'unrec'=>'RG','thinkwal'=>'8','champrec'=>'RG','yashicaent'=>8,'zplus'=>'RG','pay1all'=>'Rg','prec' =>'PR','ashw1'=>8,'stelcom'=>'16','manimaster'=>'RG','wellborn' => '16','ka2zrec'=>'Reliance','nishi' => '8','supersaas' => '8','balajisaas' => '8','pratisaas' => '8','osssaas' => '8','rajsaas' => '8','kumarsaas' => '8','techmate' => 'RG'), 'voucher'=>array('id'=>'', 'oss'=>'RC', 'pp'=>'84','mypay'=>'7','urec'=>'RG','ka2zrec'=>'Reliance')),
                    '9'=>array('operator'=>'Tata Docomo', 'opr_code'=>'TD',
                            'flexi'=>array('id'=>'9', 'oss'=>'DOC', 'pp'=>'108', 'payt'=>'18', 'cbz'=>'RD', 'rdu'=>'DOCO', 'uva'=>'DOCO', 'apna'=>'D', 'magic'=>'TD', 'rio'=>'TD', 'gem'=>'TD', 'gitech'=>'HTAD', 'rkit'=>35, 'a2z'=>8, 'joinrec'=>1, 'mypay'=>8, 'smsdaak'=>'TCP', 'hitecrec'=>'TD',
                                    "simple"=>"D", 'practic'=>"Docomo", "manglam"=>"RD", "bulk"=>"RD" ,'speedrec'=>8,'ctswallet'=>'TD','bigshoprec'=>1,'indiaone'=>'RD','emoney'=>'TD','speedpay'=>'DOCOMO','swamiraj'=>'TN','swamirapi'=>8,'a1rec'=>13,'unrec'=>'TD','thinkwal'=>'6','champrec'=>'RD','yashicaent'=> 9,'zplus'=>'TD','ka2zrec'=>'Docomo','urec'=>'RD','pay1all'=>'Tl','prec' =>'PD','ashw1'=>9,'pay1click'=>'RTD','stelcom'=>'19','manimaster'=>'RD','wellborn' => '19','nishi' => '9','supersaas' => '9','myetopup'=> '13','balajisaas' => '9','pratisaas' => '9','osssaas' => '9','rajsaas' => '9','kumarsaas' => '9','techmate' => 'TD'),
                            'voucher'=>array('id'=>'27', 'oss'=>'DOC', 'pp'=>'108', 'payt'=>'18', 'cbz'=>'RDR', 'rdu'=>'DOCOS', 'uva'=>'DOCO', 'apna'=>'DS', 'magic'=>'RD', 'rio'=>'RD', 'gem'=>'TL', 'gitech'=>'HTDS', 'rkit'=>35, 'a2z'=>7, 'joinrec'=>1, 'smsdaak'=>'TCP', 'hitecrec'=>'RD',
                                    'practic'=>"Docomo", "simple"=>"DS", "manglam"=>"TD",'bigshoprec'=>1046,'indiaone'=>'TD','emoney'=>'TL','speedpay'=>'DOCOMO','swamiraj'=>'TS','swamirapi'=>8,'a1rec'=>31,'thinkwal'=>'20','champrec'=>'TD','yashicaent'=> 27,'zplus'=>'TB','urec'=>'TD','pay1all'=>'Tl','prec' =>'PDS','ashw1'=>'27','pay1click'=>'STD','stelcom'=>'20','manimaster'=>'TD','wellborn' => '20','ka2zrec'=>'Docomo','nishi' => '27','supersaas' => '27','myetopup'=> '31','balajisaas' => '27','pratisaas' => '27','osssaas' => '27','rajsaas' => '27','kumarsaas' => '27','techmate' => 'DS')),
                    '10'=>array('operator'=>'Tata Indicom', 'opr_code'=>'TI',
                            'flexi'=>array('id'=>'10', 'oss'=>'TTS', 'pp'=>'26', 'payt'=>'3', 'cbz'=>'DI', 'rdu'=>'INDI', 'uva'=>'INDI', 'apna'=>'T', 'gem'=>'TA', 'gitech'=>'HTAI', 'rkit'=>19, 'a2z'=>8, 'joinrec'=>1, 'mypay'=>10, 'smsdaak'=>'TCP', 'hitecrec'=>'TD', "practic"=>"Tataindicom",
                                    "simple"=>"T", "manglam"=>"TI" ,'speedrec'=>10,'ctswallet'=>'TI','bigshoprec'=>1023,'indiaone'=>'RT','swamiraj'=>'TI','swamirapi'=>'10','a1rec'=>63,'unrec'=>'TI','champrec'=>'TI','stelcom'=>'TI'), 'voucher'=>array('id'=>'27', 'oss'=>'TTS', 'pp'=>'26', 'payt'=>'3', 'cbz'=>'RDR', 'rdu'=>'DOCOS', 'uva'=>'INDI', 'gitech'=>'HTAI', 'rkit'=>19, 'a2z'=>7, 'joinrec'=>1,'yashicaent'=> 10,'zplus'=>'TD','ka2zrec'=>'TataIndicom','urec'=>'RT','prec' =>'PTI','ashw1'=>10,'stelcom'=>'21','manimaster'=>'RT','wellborn' => '21','nishi' => '10','supersaas' => '10','balajisaas' => '10','pratisaas' => '10','osssaas' => '10','rajsaas' => '10','kumarsaas' => '10','techmate' => 'TT')),
                    '11'=>array('opr_code'=>'UN',
                            'flexi'=>array('id'=>'11', 'oss'=>'UNI', 'pp'=>'129', 'payt'=>'790', 'cbz'=>'UN', 'rdu'=>'UNR', 'uva'=>'UNIN', 'anand'=>'uninor', 'apna'=>'U', 'rio'=>'RU', 'gem'=>'UN', 'gitech'=>'HUNI', 'rkit'=>20, 'joinrec'=>26, 'mypay'=>9, 'smsdaak'=>'UGP', 'hitecrec'=>'TU',
                                    "practic"=>"Uninor", "simple"=>"U", "manglam"=>'RU' ,'speedrec'=>9,'ctswallet'=>'UN','ambika'=>'UN','speedpay'=>'Uninor','swamiraj'=>'UN','swamirapi'=>9,'a1rec'=>12,'unrec'=>'UN','thinkwal'=>'12','champrec'=>'RU','yashicaent'=> 11,'zplus'=>'RU','ka2zrec'=>'Uninor','urec'=>'RU','pay1all'=>'Un','prec' =>'PU','ashw1'=>11,'pay1click'=>'RUN','stelcom'=>'22','manimaster'=>'RU','wellborn' => '22','nishi' => '11','supersaas' => '11','balajisaas' => '11','pratisaas' => '11','osssaas' => '11','rajsaas' => '11','kumarsaas' => '11','techmate' => 'UN'), 'operator'=>'Uninor',
                            'voucher'=>array('id'=>'29', 'oss'=>'UNI', 'pp'=>'129', 'payt'=>'790', 'cbz'=>'UNR', 'rdu'=>'UNRS', 'uva'=>'UNIN', 'anand'=>'uninor', 'apna'=>'US', 'gem'=>'US', 'gitech'=>'HUNS', 'rkit'=>21, 'joinrec'=>31, 'smsdaak'=>'USP', 'hitecrec'=>'RU', "simple"=>"US",
                                    "manglam"=>'TU','ambika'=>'US','speedpay'=>'Uninor','swamiraj'=>'US','swamirapi'=>9,"practic"=>"Uninor",'a1rec'=>46,'thinkwal'=>'21','champrec'=>'TU','yashicaent'=> 29,'zplus'=>'TU','urec'=>'TU','pay1all'=>'Us','prec' =>'PUS','ashw1'=>29,'stelcom'=>'23','manimaster'=>'TU','wellborn' => '23','ka2zrec'=>'Uninor','nishi' => '29','supersaas' => '29','balajisaas' => '29','pratisaas' => '29','osssaas' => '29','rajsaas' => '29','kumarsaas' => '29','techmate' => 'US')),
                    '12'=>array('operator'=>'Videocon', 'opr_code'=>'DC',
                            'flexi'=>array('id'=>'12', 'oss'=>'VID', 'pp'=>'134', 'cbz'=>'VR', 'rdu'=>'VCON', 'uva'=>'vidgsm', 'apna'=>'VD', 'magic'=>'TE', 'rio'=>'TE', 'gem'=>'VT', 'gitech'=>'HVID', 'rkit'=>22, 'mypay'=>5, 'smsdaak'=>'VGP', "simple"=>"VD", "manglam"=>"RN" ,'speedrec'=>5,'ctswallet'=>'VD','bigshoprec'=>1030,'indiaone'=>'NOV','emoney'=>'UT','unrec'=>'VD','champrec'=>'RN','yashicaent'=> 12,'zplus'=>'TN','urec'=>'NOV','pay1all'=>'Vt','ashw1'=>12,'stelcom'=>'24','manimaster'=>'NOV','wellborn' => '24','ka2zrec'=>'Videocon','nishi' => '12','supersaas' => '12','balajisaas' => '12','pratisaas' => '12','osssaas' => '12','kumarsaas' => '12','techmate' => 'VT'),
                            'voucher'=>array('id'=>'28', 'oss'=>'VID', 'pp'=>'134', 'cbz'=>'VS', 'rdu'=>'VCONS', 'uva'=>'vidgsm', 'apna'=>'VS', 'magic'=>'RE', 'rio'=>'RE', 'gem'=>'VS', 'gitech'=>'HVIS', 'rkit'=>23, 'smsdaak'=>'VSP', "simple"=>"VS", "manglam"=>"TN",'ctswallet'=>'VS','indiaone'=>'NOS','emoney'=>'VS','champrec'=>'TN','yashicaent'=> 28,'zplus'=>'RC','urec'=>'NOS','pay1all'=>'Us','ashw1'=>28,'stelcom'=>'25','manimaster'=>'NOS','wellborn' => '25','ka2zrec'=>'Videocon','nishi' => '28','supersaas' => '28','balajisaas' => '28','pratisaas' => '28','osssaas' => '28','kumarsaas' => '12','techmate' => 'VS')),
                    '13'=>array('operator'=>'Virgin CDMA', 'opr_code'=>'VC', 'flexi'=>array('id'=>'13', 'oss'=>'VR', 'pp'=>'52', 'mypay'=>21, "simple"=>"VC", "manglam"=>"RD",'gitech'=>'HVIC','ctswallet'=>'VC','indiaone'=>'NVG','unrec'=>'VGC','champrec'=>'RD','yashicaent'=> 13,'ashw1'=>13,'stelcom'=>'26','manimaster'=>'NVG','wellborn' => '26','nishi' => '13','supersaas' => '13','balajisaas' => '13','pratisaas' => '13','osssaas' => '13','rajsaas' => '13','kumarsaas' => '13'), 'voucher'=>array('id'=>'', 'oss'=>'VR', 'pp'=>'52', "simple"=>"VC")),
                    '14'=>array('operator'=>'Virgin GSM', 'opr_code'=>'VG', 'flexi'=>array('id'=>'14', 'oss'=>'VG', 'pp'=>'52', 'mypay'=>20, "simple"=>"VG", "manglam"=>"TD",'gitech'=>'HVIG','ctswallet'=>'VG','indiaone'=>'NVS','unrec'=>'VGG','champrec'=>'TD','yashicaent'=> 14,'ashw1'=>14,'stelcom'=>'27','manimaster'=>'NVS','nishi' => '14','supersaas' => '14','balajisaas' => '14','pratisaas' => '14','osssaas' => '14','rajsaas' => '14','kumarsaas' => '14'), 'voucher'=>array('id'=>'', 'oss'=>'VG', 'pp'=>'52', "simple"=>"VG",'wellborn' => '27')),
                    '15'=>array('operator'=>'Vodafone', 'opr_code'=>'VF',
                            'flexi'=>array('id'=>'15', 'oss'=>'VF', 'pp'=>'50', 'payt'=>'8', 'cbz'=>'RV', 'rdu'=>'VODA', 'uva'=>'VODA', 'uni'=>'292', 'anand'=>'vodafone', 'apna'=>'V', 'magic'=>'RV', 'rio'=>'RV', 'gem'=>'VF', 'durga'=>'Vodafone', 'gitech'=>'HVOD', 'rkit'=>27, 'a2z'=>5, 'joinrec'=>9,'swamirapi'=>3,
                                    'mypay'=>3, 'smsdaak'=>'VFP', 'aporec'=>6, 'hitecrec'=>'RV', "practic"=>"Vodafone", "simple"=>"V", "manglam"=>"RV", "bulk"=>"RV", "bimco"=>"VF", 'simpleapi'=>'RV', 'indicore'=>'Vodafone', "rajan"=>"VD" ,'speedrec'=>3,'ctswallet'=>'VO','bigshoprec'=>1028,'indiaone'=>'RV','emoney'=>'VF','swamiraj'=>'RV','ambika'=>'VF','a1rec'=>5,'speedpay'=>'Vodafone','unrec'=>'VF','thinkwal'=>'1','champrec'=>'RV','yashicaent'=> 15,'zplus'=>'RV','ka2zrec'=>'Vodafone','roundpay'=>37,'maxxrec'=>5,'erecpoint'=>5,'urec'=>'RV','pay1all'=>'Vf','prec' =>'PV','ashw1'=>15,'pay1click'=>'RVO','kracrec'=>'Vodafone','stelcom'=>'1','manimaster'=>'RV','wellborn' => '1','nishi' => '15','supersaas' => '15','myetopup'=> '5','balajisaas' => '15','pratisaas' => '15','osssaas' => '15','rajsaas' => '15','manglamvod'=>'RV','kumarsaas' => '15','techmate' => 'VF','varsharobo'=>'VF','qubarobo' => 'VF',
                                'aventidea' => 'VF','threeplus' => 'VF','pintoosls' => 'VF','aventerp' => 'VF','jasscomm' => 'VF','nkagency' => 'VF','anilkir' => 'VF','jeevnrkh' => 'VF','payclick' => 'RV'), 'voucher'=>array('id'=>'', 'oss'=>'VF', 'pp'=>'50','a1rec'=>160)),
                    '30'=>array('operator'=>'MTNL', 'opr_code'=>'MT', 'flexi'=>array('id'=>'30', 'payt'=>'13', 'rdu'=>'MTNL', 'uva'=>'MTNL', 'apna'=>'MTT', 'gem'=>'ML', 'gitech'=>'HMTN', 'rkit'=>'12', 'smsdaak'=>'MMP', "manglam"=>"MT", 'aporec'=>'43','ctswallet'=>'ML','bigshoprec'=>1019,'indiaone'=>'RN','emoney'=>'ML','unrec'=>'MT','thinkwal'=>'13','champrec'=>'MT','yashicaent'=> 30,'zplus'=>'RR','urec'=>'RV','pay1all'=>'Ml','ashw1'=>30,'stelcom'=>'12','manimaster'=>'RN','wellborn' => '12','nishi' => '30','supersaas' => '30','balajisaas' => '30','pratisaas' => '30','osssaas' => '30','rajsaas'=>'30','kumarsaas' => '30','techmate' => 'MTT'),
                            'voucher'=>array('id'=>'31', 'payt'=>'13', 'rdu'=>'MTNLS', 'uva'=>'MTNL', 'apna'=>'MTR', 'gem'=>'MR', 'rkit'=>'12', 'smsdaak'=>'MSP', 'aporec'=>'57','indiaone'=>'TM','emoney'=>'MR','yashicaent'=> 31,'prec' =>'PMM','ashw1'=>31,'stelcom'=>'13','manimaster'=>'TM','wellborn' => '13','nishi' => '31','supersaas' => '31','balajisaas' => '31','pratisaas' => '31','osssaas' => '31','rajsaas' => '31','kumarsaas' => '31','techmate' => 'MS')),
                    '83'=>array('operator'=>'Reliance Jio', 'opr_code'=>'JO', 'flexi'=>array('id'=>'83', 'swamiraj'=>'JO','swamirapi'=>'21', 'cp'=>'rjio', 'smsdaak'=>'RJP', 'joinrec'=>'51', 'IndiaRec'=>'reliancejio', 'manglam'=>'JO','a1rec'=>167 ,'speedrec'=>21,'mypay'=>208,'bigshoprec'=>1018,'ambika'=>'JIOS','practic'=>'RelianceJio','emoney'=>'RJ','indiaone'=>'RJ','unrec'=>'JO','speedpay'=>'RJ','thinkwal'=>'10','champrec'=>'JO','yashicaent'=> 83,'zplus'=>'RJ','ka2zrec'=>'RelianceJio','pay1all'=>'Rg','prec' =>'PRJ','ashw1'=>83,'pay1click'=>'RJR','stelcom'=>'38','manimaster'=>'RJ','wellborn' => '38','nishi' => '83','supersaas' => '83','myetopup'=> '167','balajisaas' => '83','magic'=>'RJ','pratisaas' => '83','osssaas' => '83','rajsaas' => '83','kumarsaas' => '83','manglamvod'=>'JO','techmate' => 'RJ','payclick' => 'JIO'),
                     'voucher'=>array('id'=>'83', 'swamiraj'=>'JO','swamirapi'=>21, 'cp'=>'rjio', 'smsdaak'=>'RJP', 'joinrec'=>'51', 'IndiaRec'=>'reliancejio', 'manglam'=>'JO','manglamvod'=>'JO','a1rec'=>167,'pay1all'=>'Rg','prec' =>'PRJ','wellborn' => '38','manimaster'=>'RJ','ka2zrec'=>'RelianceJio','myetopup'=> '167','magic'=>'RJ','myetopup'=> '167','kumarsaas' => '167','techmate' => 'RS','payclick' => 'JIO'))),
            'dthRecharge'=>array(
                    '1'=>array('operator'=>'Airtel DTH',
                            'flexi'=>array('id'=>'16', 'oss'=>'ADT', 'pp'=>'152', 'payt'=>'15', 'cbz'=>'RH', 'rdu'=>'DA', 'uva'=>'AIRTELTV', 'uni'=>'255', 'magic'=>'DA', 'rio'=>'DA', 'rkit'=>11, 'joinrec'=>14, 'mypay'=>15, 'smsdaak'=>'ATV', 'hitecrec'=>'DA', "practic"=>"AirtelDTH", "simple"=>"ATV",'swamirapi'=>15,
                       "manglam"=>"DA", 'rajan'=>'AD','bulk'=>'DA' ,'speedrec'=>15,'ctswallet'=>'AT','bigshoprec'=>4,'indiaone'=>'RH','emoney'=>'AD','ambika'=>'AD','speedpay'=>'AirtelDTH','a1rec'=>22,'unrec'=>'ADDTH','thinkwal'=>'14','champrec'=>'DA','yashicaent'=> 16,'zplus'=>'DA','ka2zrec'=>'AirtelDTH','roundpay'=>51,'urec'=>'RH','pay1all'=>'Ad','prec' =>'PAT','ashw1'=>16,'pay1click'=>'DAR','stelcom'=>'2','manimaster'=>'RH','wellborn' => '2','nishi' => '16','supersaas' => '16','myetopup'=> '22','balajisaas' => '16','pratisaas' => '16','osssaas' => '16','rajsaas' => '16','kumarsaas' => '16','techmate' => 'AD','payclick' => 'DA')),
                    '2'=>array('operator'=>'Big TV DTH',
                            'flexi'=>array('id'=>'17', 'oss'=>'BTV', 'pp'=>'131', 'payt'=>'279', 'cbz'=>'DB', 'rdu'=>'DB', 'uva'=>'BIGTV', 'uni'=>'277', 'magic'=>'DB', 'rio'=>'DB', 'gitech'=>'HBTV', 'rkit'=>10, 'joinrec'=>21, 'mypay'=>19, 'smsdaak'=>'RTV', "practic"=>"BigTV", "simple"=>"BTV",
                                    "manglam"=>"DB", 'rajan'=>'RB','bulk'=>'DB' ,'speedrec'=>19,'ctswallet'=>'BI','bigshoprec'=>1036,'indiaone'=>'DB','ambika'=>'BT','speedpay'=>'RelianceBigTv','a1rec'=>18,'unrec'=>'BIGDTH','swamirapi'=>19,'thinkwal'=>'15','champrec'=>'DB','yashicaent'=> 17,'zplus'=>'DB','ka2zrec'=>'RelianceDigitalTV','urec'=>'DB','pay1all'=>'Bt','prec' =>'PRT','ashw1'=>17,'stelcom'=>'4','manimaster'=>'DB','wellborn' => '4','nishi' => '17','supersaas' => '17','myetopup'=> '18','balajisaas' => '17','pratisaas' => '17','osssaas' => '17','rajsaas' => '17','kumarsaas' => '17','techmate' => 'BG')),
                    '3'=>array('operator'=>'Dish TV DTH',
                            'flexi'=>array('id'=>'18', 'oss'=>'DIS', 'pp'=>'128', 'payt'=>'12', 'cbz'=>'DD', 'rdu'=>'DD', 'uva'=>'DISHTV', 'magic'=>'DD', 'rio'=>'DD', 'rio2'=>'DD', 'gitech'=>'HDIS', 'rkit'=>24, 'joinrec'=>29, 'mypay'=>16, 'smsdaak'=>'DTV', 'aporec'=>17, 'hitecrec'=>'DD',
                                    "practic"=>"DishTV", "simple"=>"DTV", "manglam"=>"DD", "bulk"=>"DD", 'rajan'=>'DT','indicore'=>'Dish TV' ,'speedrec'=>16,'ctswallet'=>'DI','bigshoprec'=>1035,'indiaone'=>'DD','emoney'=>'DS','ambika'=>'DS','speedpay'=>'DishTV','swamiraj'=>'DD','swamirapi'=>16,'a1rec'=>17,'unrec'=>'DISH','thinkwal'=>'16','champrec'=>'DD','yashicaent'=> 18,'zplus'=>'DD','ka2zrec'=>'DishTV','roundpay'=>53,'urec'=>'DD','pay1all'=>'Ds','prec' =>'PDT','ashw1'=>18,'pay1click'=>'DDT','stelcom'=>'3','manimaster'=>'DD','wellborn' => '3','nishi' => '18','supersaas' => '18','myetopup'=> '17','balajisaas' => '18','pratisaas' => '18','osssaas' => '18','rajsaas' => '18','kumarsaas' => '18','techmate' => 'DT')),
                    '4'=>array('operator'=>'Sun TV DTH',
                            'flexi'=>array('id'=>'19', 'oss'=>'SUN', 'pp'=>'74', 'payt'=>'11', 'cbz'=>'DS', 'rdu'=>'DS', 'uva'=>'SUNTV', 'magic'=>'DS', 'rio'=>'DS', 'gitech'=>'HSUN', 'rkit'=>9, 'joinrec'=>19, 'mypay'=>18, 'smsdaak'=>'STV', 'hitecrec'=>'DS', "practic"=>"SunDirect", "simple"=>"STV",
                                    "manglam"=>"DS", 'rajan'=>'SD' ,'speedrec'=>18,'ctswallet'=>'SU','bigshoprec'=>1037,'indiaone'=>'DS','emoney'=>'SD','ambika'=>'SD','speedpay'=>'SunDirect','swamiraj'=>'DS','swamirapi'=>18,'a1rec'=>20,'unrec'=>'SUNDTH','thinkwal'=>'17','champrec'=>'DS','yashicaent'=> 19,'zplus'=>'DS','ka2zrec'=>'SunDirect','urec'=>'DS','pay1all'=>'Sd','prec' =>'PI','ashw1'=>19,'stelcom'=>'5','manimaster'=>'DS','wellborn' => '5','nishi' => '19','supersaas' => '19','balajisaas' => '19','pratisaas' => '19','osssaas' => '19','rajsaas' => '19','kumarsaas' => '19','techmate' => 'ST','varsharobo'=>'SD','qubarobo' => 'SD',
                                'aventidea' => 'SD','threeplus' => 'SD','pintoosls' => 'SD','aventerp' => 'SD','jasscomm' => 'SD','nkagency' => 'SD','anilkir' => 'SD','jeevnrkh' => 'SD')),
                    '5'=>array('operator'=>'Tata Sky DTH',
                            'flexi'=>array('id'=>'20', 'oss'=>'TAS', 'pp'=>'44', 'payt'=>'10', 'cbz'=>'DT', 'rdu'=>'DT', 'uva'=>'TATASKY', 'magic'=>'DT', 'rio'=>'DT', 'rio2'=>'DT', 'rkit'=>28, 'joinrec'=>22, 'mypay'=>14, 'smsdaak'=>'TTV', 'hitecrec'=>'DT', "practic"=>"Tatasky",
                                    "simple"=>"TTV", "manglam"=>"DT", "bulk"=>'DT', 'rajan'=>'TS' ,'speedrec'=>14,'ambika'=>'TS','ctswallet'=>'TS','bigshoprec'=>5,'indiaone'=>'DT','emoney'=>'TS','a1rec'=>19,'speedpay'=>'TataSky','swamiraj'=>'DT','swamirapi'=>14,'unrec'=>'TATASKY','thinkwal'=>'19','champrec'=>'DT','yashicaent'=> 20,'zplus'=>'DT','ka2zrec'=>'TataSky','roundpay'=>55,'erecpoint'=>19,'urec'=>'DT','pay1all'=>'Ts','prec' =>'PTT','ashw1'=>20,'pay1click'=>'DTS','stelcom'=>'37','manimaster'=>'DT','wellborn' => '37','nishi' => '20','supersaas' => '20','myetopup'=> '19','balajisaas' => '20','pratisaas' => '20','osssaas' => '20','rajsaas' => '20','kumarsaas' => '20','techmate' => 'TS','payclick' => 'DT')),
                    '6'=>array('operator'=>'Videocon DTH',
                            'flexi'=>array('id'=>'21', 'oss'=>'D2H', 'pp'=>'132', 'payt'=>'20', 'cbz'=>'VDOC', 'rdu'=>'DV', 'uva'=>'videocond2h', 'magic'=>'DV', 'rio'=>'DV', 'gitech'=>'HVIH', 'rkit'=>4, 'joinrec'=>23, 'mypay'=>17, 'smsdaak'=>'VTV', 'hitecrec'=>'DV', "practic"=>"VideoconDTH",
                                    "simple"=>"VTV", "manglam"=>"DV", "bulk"=>"DV", 'rajan'=>'VH' ,'speedrec'=>17,'ctswallet'=>'VT','bigshoprec'=>1007,'indiaone'=>'DV','emoney'=>'VD','ambika'=>'VD','speedpay'=>'VideoconD2H','swamiraj'=>'DV','swamirapi'=>17,'a1rec'=>21,'unrec'=>'VDDTH','thinkwal'=>'18','champrec'=>'DV','yashicaent'=> 21,'zplus'=>'DV','ka2zrec'=>'VideoconD2h','roundpay'=>56,'urec'=>'DV','pay1all'=>'Vd','prec' =>'PVT','ashw1'=>21,'pay1click'=>'DVD','stelcom'=>'6','manimaster'=>'DV','wellborn' => '6','nishi' => '21','supersaas' => '21','myetopup'=> '21','balajisaas' => '21','pratisaas' => '21','osssaas' => '21','rajsaas' => '21','kumarsaas' => '21','techmate' => 'VV'))), 'busBooking'=>array('1'=>array('operator'=>'Red Bus', 'flexi'=>array('id'=>'42'))),
            'billPayment'=>array('1'=>array('operator'=>'Docomo Postpaid', 'flexi'=>array('id'=>'36', 'apna'=>'PD', 'gitech'=>'HTDP', 'smsdaak'=>'TDC','bigshoprec'=>1038,'indiaone'=>'PD','emoney'=>'85','champrec'=>'DP')), '2'=>array('operator'=>'Loop Mobile PostPaid', 'flexi'=>array('id'=>'37', 'apna'=>'PLM')),
                    '3'=>array('operator'=>'Cellone PostPaid', 'flexi'=>array('id'=>'38', 'apna'=>'PCL', 'gitech'=>'HBSP', 'smsdaak'=>'BGC' ,'speedrec'=>111)), '4'=>array('operator'=>'IDEA Postpaid', 'flexi'=>array('id'=>'39', 'apna'=>'IP', 'gitech'=>'HIDP', 'rkit'=>37, 'smsdaak'=>'IDC' ,'speedrec'=>12,'bigshoprec'=>1039,'indiaone'=>'PI','emoney'=>'IDC','champrec'=>'PI')),
                    '5'=>array('operator'=>'Tata TeleServices PostPaid', 'flexi'=>array('id'=>'40', 'apna'=>'PTT', 'joinrec'=>37, 'rkit'=>42 ,'speedrec'=>112)), '6'=>array('operator'=>'Vodafone Postpaid', 'flexi'=>array('id'=>'41', 'uni'=>'266', 'apna'=>'VP', 'gitech'=>'HVOP', 'rkit'=>38, 'smsdaak'=>'VFC' ,'speedrec'=>31,'bigshoprec'=>1034,'indiaone'=>'PV','emoney'=>'VFC','champrec'=>'VP')),
                    '7'=>array('operator'=>'Airtel Postpaid', 'flexi'=>array('id'=>'42', 'apna'=>'AP', 'joinrec'=>30, 'rkit'=>36, 'smsdaak'=>'ATC' ,'speedrec'=>34,'bigshoprec'=>1041,'indiaone'=>'PA','emoney'=>'ATC','champrec'=>'AP')),
                    '8'=>array('operator'=>'Reliance GSM Postpaid', 'flexi'=>array('id'=>'43', 'apna'=>'RGP', 'joinrec'=>32, 'gitech'=>'HREP', 'rkit'=>39, 'smsdaak'=>'RGC' ,'speedrec'=>33,'bigshoprec'=>1042,'indiaone'=>'PR','emoney'=>'83','champrec'=>'PR'))),

            'utilityBillPayment'=>array('45'=>array('operator'=>'Adani Electricity Mumbai Limited', 'flexi'=>array('id'=>'45', 'smsdaak'=>'REE','cca'=>'RELI00000MUM01')), '46'=>array('operator'=>'BSES Rajdhani', 'flexi'=>array('id'=>'46', 'smsdaak'=>'BRE','cca'=>'BSESRAJPLDEL01')),
                    '47'=>array('operator'=>'BSES Yamuna', 'flexi'=>array('id'=>'47', 'smsdaak'=>'BYE','cca'=>'BSESYAMPLDEL01')), '48'=>array('operator'=>'North Delhi Power Limited', 'flexi'=>array('id'=>'48', 'smsdaak'=>'NDE')), '49'=>array('operator'=>'Airtel Landline', 'flexi'=>array('id'=>'49', 'smsdaak'=>'ATL','indiaone'=>'LA')),
                    '50'=>array('operator'=>'MTNL Delhi Landline', 'flexi'=>array('id'=>'50', 'smsdaak'=>'MDL', 'cca'=>'MTNL00000DEL01')), '51'=>array('operator'=>'Mahanagar Gas Limited', 'flexi'=>array('id'=>'51', 'smsdaak'=>'MMG', 'cca'=>'MAHA00000MUM01')), '85'=>array('operator'=>'Indraprastha Gas', 'flexi'=>array('id'=>'85', 'smsdaak'=>'IPG', 'cca'=>'INDRAPGASDEL02')),
                    '86'=>array('operator'=>'Gujarat Gas', 'flexi'=>array('id'=>'86', 'smsdaak'=>'GJG', 'cca'=>'GUJGAS000GUJ01')),'87'=>array('operator'=>'Adani Gas', 'flexi'=>array('id'=>'87', 'smsdaak'=>'ADG','cca_Gujarat'=>'ADAN00000GUJ01','cca_Haryana'=>'ADAN00000HAR02')),'88'=>array('operator'=>'BSNL', 'flexi'=>array('id'=>'88', 'smsdaak'=>'BGL','indiaone'=>'LB','cca_LLC'=>'BSNLLLCORNAT01','cca_LLI'=>'BSNLLLINDNAT01')),'89'=>array('operator'=>'BEST', 'flexi'=>array('id'=>'89', 'smsdaak'=>'BME', 'cca'=>'BEST00000MUM01')),
                    '90'=>array('operator'=>'MSEDC Limited', 'flexi'=>array('id'=>'90', 'smsdaak'=>'MDE','cca'=>'MAHA00000MAH01')),'91'=>array('operator'=>'Rajasthan Vidyut Vitran Nigam Limited', 'flexi'=>array('id'=>'91', 'smsdaak'=>'')),'92'=>array('operator'=>'Torrent Power', 'flexi'=>array('id'=>'92', 'smsdaak'=>'TPE','cca_Bhiwandi'=>'TORR00000BHW03','cca_Agra'=>'TORR00000AGR01','cca_Ahmedabad'=>'TORR00000AHM02','cca_Surat'=>'TORR00000SUR04')),'93'=>array('operator'=>'Bangalore Electricity Supply Company', 'flexi'=>array('id'=>'93', 'smsdaak'=>'BBE','cca'=>'BESCOM000KAR01')),
                    '94'=>array('operator'=>'MP Madhya Kshetra Vidyut Vitaran - URBAN', 'flexi'=>array('id'=>'94', 'smsdaak'=>'MME','cca'=>'MPCZ00000MAP01')),'95'=>array('operator'=>'Noida Power', 'flexi'=>array('id'=>'95', 'smsdaak'=>'NUE','cca'=>'NPCL00000NOI01')),'96'=>array('operator'=>'MP Paschim Kshetra Vidyut Vitaran', 'flexi'=>array('id'=>'96', 'smsdaak'=>'MPE','cca'=>'MPPK00000MAP01')),'97'=>array('operator'=>'Calcutta Electricity Supply Ltd', 'flexi'=>array('id'=>'97', 'smsdaak'=>'CWE','cca'=>'CESC00000KOL01')),
                    '98'=>array('operator'=>'Chhattisgarh State Electricity Board', 'flexi'=>array('id'=>'98', 'smsdaak'=>'CCE', 'cca'=>'CSPDCL000CHH01')),'99'=>array('operator'=>'India Power Corporation Limited', 'flexi'=>array('id'=>'99', 'smsdaak'=>'IPE','cca_Bihar'=>'IPCL00000BIH01','cca_WestBengal'=>'IPCL00000WBL02')),'100'=>array('operator'=>'Jamshedpur Utilities and Services', 'flexi'=>array('id'=>'100', 'smsdaak'=>'JUE','cca'=>'JUSC00000JAM01')),'101'=>array('operator'=>'Tripura State Electricity', 'flexi'=>array('id'=>'101', 'smsdaak'=>'TTE','cca'=>'TSEC00000TRI01')),
                    '102'=>array('operator'=>'Assam Power Urban', 'flexi'=>array('id'=>'102', 'smsdaak'=>'AAE', 'cca'=>'APDCL0000ASM01')),'103'=>array('operator'=>'Jaipur Vidyut Vitran Nigam Limited', 'flexi'=>array('id'=>'103', 'smsdaak'=>'JRE','cca'=>'JVVNL0000RAJ01')),'104'=>array('operator'=>'Jodhpur Vidyut Vitran Nigam Limited', 'flexi'=>array('id'=>'104', 'smsdaak'=>'DRE', 'cca'=>'JDVVNL000RAJ01')),'105'=>array('operator'=>'Ajmer Vidyut Vitran Nigam Limited', 'flexi'=>array('id'=>'105', 'smsdaak'=>'ARE', 'cca'=>'AVVNL0000RAJ01')),
                    '107'=>array('operator'=>'Sabarmati Gas Limited', 'flexi'=>array('id'=>'107','cca'=>'SGL000000GUJ01')),'108'=>array('operator'=>'Siti Energy', 'flexi'=>array('id'=>'108','cca'=>'SITI00000UTP03')),'109'=>array('operator'=>'Tripura Natural Gas', 'flexi'=>array('id'=>'109','cca'=>'TNGCLOB00TRI01')),
                    '110'=>array('operator'=>'MTNL - Mumbai', 'flexi'=>array('id'=>'110','cca'=>'MTNL00000MUM01')),'111'=>array('operator'=>'Delhi Jal Board', 'flexi'=>array('id'=>'111','cca'=>'DLJB00000DEL01')),'112'=>array('operator'=>'Municipal Corporation of Gurugram', 'flexi'=>array('id'=>'112','cca'=>'MCG000000GUR01')),'113'=>array('operator'=>'Urban Improvement Trust (UIT) - Bhiwadi', 'flexi'=>array('id'=>'113','cca'=>'UITWOB000BHW01')),
                    '114'=>array('operator'=>'Uttarakhand Jal Sansthan', 'flexi'=>array('id'=>'114','cca'=>'UJS000000UTT01')),'115'=>array('operator'=>'Bharatpur Electricity Services Ltd', 'flexi'=>array('id'=>'115','cca'=>'BESLOB000BRT01')),'116'=>array('operator'=>'Bikaner Electricity Supply Limited', 'flexi'=>array('id'=>'116','cca'=>'BKESL0000BKR01')),'117'=>array('operator'=>'Daman and Diu Electricity', 'flexi'=>array('id'=>'117','cca'=>'DDED00000DAD01')),
                    '118'=>array('operator'=>'Eastern Power Distribution Co Ltd', 'flexi'=>array('id'=>'118','cca'=>'EPDCLOB00ANP01')),'119'=>array('operator'=>'Kota Electricity Distribution Limited', 'flexi'=>array('id'=>'119','cca'=>'KEDLOB000KTA01')),'120'=>array('operator'=>'Meghalaya Power Dist Corp Ltd', 'flexi'=>array('id'=>'120','cca'=>'MPDC00000MEG01')),'121'=>array('operator'=>'Muzaffarpur Vidyut Vitran Limited', 'flexi'=>array('id'=>'121','cca'=>'MVVL00000MUZ01')),
                    '122'=>array('operator'=>'North Bihar Power Distribution Company Ltd', 'flexi'=>array('id'=>'122','cca'=>'NBPDCL000BHI01')),'123'=>array('operator'=>'NESCO, Odisha', 'flexi'=>array('id'=>'123','cca'=>'NESCO0000ODI01')),'124'=>array('operator'=>'South Bihar Power Distribution Company Ltd', 'flexi'=>array('id'=>'124','cca'=>'SBPDCL000BHI01')),'125'=>array('operator'=>'SNDL Nagpur', 'flexi'=>array('id'=>'125','cca'=>'SNDL00000NAG01')),
                    '126'=>array('operator'=>'SOUTHCO- Odisha', 'flexi'=>array('id'=>'126','cca'=>'SOUTHCO00ODI01')),'127'=>array('operator'=>'Southern Power Distribution Co Ltd', 'flexi'=>array('id'=>'127','cca'=>'SPDCLOB00ANP01')),'128'=>array('operator'=>'TP Ajmer Distribution Ltd', 'flexi'=>array('id'=>'128','cca'=>'TPADL0000AJM01')),'129'=>array('operator'=>'Uttarakhand Power Corporation Limited', 'flexi'=>array('id'=>'129','cca'=>'UPCL00000UTT01')),
                    '130'=>array('operator'=>'Uttar Pradesh Power Corp Ltd', 'flexi'=>array('id'=>'130','cca'=>'UPPCL0000UTP01')),'131'=>array('operator'=>'Tata Power - Delhi', 'flexi'=>array('id'=>'131','cca'=>'TATAPWR00DEL01')),'132'=>array('operator'=>'Tata power - Mumbai', 'flexi'=>array('id'=>'132','cca'=>'TATAPWR00MUM01')),'133'=>array('operator'=>'Haryana City Gas', 'flexi'=>array('id'=>'133','cca'=>'HCG000000HAR01')),'134'=>array('operator'=>'WESCO Utility', 'flexi'=>array('id'=>'134','cca'=>'WESCO0000ODI01')),'135'=>array('operator'=>'BSNL landline', 'flexi'=>array('id'=>'135')),
                    '136'=>array('operator'=>'Dakshin Gujarat Vij Company Limited', 'flexi'=>array('id'=>'136', 'cca'=>'DGVCL0000GUJ01')),'137'=>array('operator'=>'DNH Power Distribution Company Limited', 'flexi'=>array('id'=>'137', 'cca'=>'DNHPDCL0DNH001')),'138'=>array('operator'=>'Madhya Gujarat Vij Company Limited', 'flexi'=>array('id'=>'138', 'cca'=>'MGVCL0000GUJ01')),'139'=>array('operator'=>'Paschim Gujarat Vij Company Limited', 'flexi'=>array('id'=>'139', 'cca'=>'PGVCL0000GUJ01')),'140'=>array('operator'=>'Uttar Gujarat Vij Company Limited', 'flexi'=>array('id'=>'140', 'cca'=>'UGVCL0000GUJ01')),'141'=>array('operator'=>'Connect Broadband', 'flexi'=>array('id'=>'141')),
                    '142'=>array('operator'=>'Madhya Pradesh Poorv Kshetra Vidyut Vitaran Company Limited(MPPKVVCL)-Jabalpur Urban', 'flexi'=>array('id'=>'142','cca'=>'MPEZ00000MAP01')), '143'=>array('operator'=>'Tamil Nadu Electricity Board (TNEB)', 'flexi'=>array('id'=>'143','cca'=>'TNEB00000TND01')), '144'=>array('operator'=>'Uttar Pradesh Power Corp Ltd (UPPCL) - RURAL', 'flexi'=>array('id'=>'144','cca'=>'UPPCL0000UTP02')), '145'=>array('operator'=>'Vadodara Gas Limited', 'flexi'=>array('id'=>'145','cca'=>'VGL000000GUJ01')), '146'=>array('operator'=>'Unique Central Piped Gases Pvt Ltd', 'flexi'=>array('id'=>'146','cca'=>'UCPGPL000MAH01')),
                    '147'=>array('operator'=>'Uttar Haryana Bijli Vitran Nigam (UHBVN)', 'flexi'=>array('id'=>'147','cca'=>'UHBVN0000HAR01')),'148'=>array('operator'=>'Dakshin Haryana Bijli Vitran Nigam (DHBVN)', 'flexi'=>array('id'=>'148','cca'=>'DHBVN0000HAR01')),'149'=>array('operator'=>'Punjab State Power Corporation Limited (PSPCL)', 'flexi'=>array('id'=>'149','cca'=>'PSPCL0000PUN01')),'150'=>array('operator'=>'Jharkhand Bijli Vitran Nigam Limited (JBVNL)', 'flexi'=>array('id'=>'150','cca'=>'JBVNL0000JHA01')),'151'=>array('operator'=>'Assam Power Distribution Company Ltd (NON-RAPDR)(RURAL)', 'flexi'=>array('id'=>'151','cca'=>'APDCL0000ASM02')),
                    '152'=>array('operator'=>'Chamundeshwari Electricity Supply Corp Ltd (CESCOM)', 'flexi'=>array('id'=>'152','cca'=>'CESCOM000KAR01')),'153'=>array('operator'=>'Hubli Electricity Supply Company Ltd (HESCOM)', 'flexi'=>array('id'=>'153','cca'=>'HESCOM000KAR01')),'193'=>array('operator'=>'Hyderabad Metropolitan Water Supply and Sewerage Board', 'flexi'=>array('id'=>'193','cca'=>'HMWSS0000HYD01')),'194'=>array('operator'=>'Himachal Pradesh State Electricity Board', 'flexi'=>array('id'=>'194','cca'=>'HPSEB0000HIP01')),'195'=>array('operator'=>'Charotar Gas Sahakari Mandali Ltd', 'flexi'=>array('id'=>'195','cca'=>'CGSM00000GUJ01')),
                    '196'=>array('operator'=>'Aavantika Gas Ltd.', 'flexi'=>array('id'=>'196','cca'=>'AGL000000MAP01')),'197'=>array('operator'=>'Bhopal Municipal Corporation - Water', 'flexi'=>array('id'=>'197','cca'=>'BMC000000MAP01')),'198'=>array('operator'=>'Gwalior Municipal Corporation - Water', 'flexi'=>array('id'=>'198','cca'=>'GMC000000MAP01')),'199'=>array('operator'=>'Indore Municipal Corporation - Water', 'flexi'=>array('id'=>'199','cca'=>'IMC000000MAP01')),'200'=>array('operator'=>'Indian Oil-Adani Gas Private Limited', 'flexi'=>array('id'=>'200','cca'=>'IOAG00000DEL01')),'201'=>array('operator'=>'Jabalpur Municipal Corporation - Water', 'flexi'=>array('id'=>'201','cca'=>'JMC000000MAP01')),
                    '202'=>array('operator'=>'Municipal Corporation Jalandhar', 'flexi'=>array('id'=>'202','cca'=>'MCJ000000PUN01')),'203'=>array('operator'=>'Municipal Corporation Ludhiana - Water', 'flexi'=>array('id'=>'203','cca'=>'MCL000000PUN01')),'204'=>array('operator'=>'Maharashtra Natural Gas Limited (MNGL)', 'flexi'=>array('id'=>'204','cca'=>'MNGL00000MAH01')),'205'=>array('operator'=>'M.P. Madhya Kshetra Vidyut Vitaran - RURAL', 'flexi'=>array('id'=>'205','cca'=>'MPCZ00000MAP02')),'206'=>array('operator'=>'M.P. Poorv Kshetra Vidyut Vitaran - RURAL', 'flexi'=>array('id'=>'206','cca'=>'MPEZ00000MAP02')),'207'=>array('operator'=>'Sikkim Power - RURAL', 'flexi'=>array('id'=>'207','cca'=>'SKPR00000SIK01')),
                    '208'=>array('operator'=>'Surat Municipal Corporation - Water', 'flexi'=>array('id'=>'208','cca'=>'SMC000000GUJ01')),'209'=>array('operator'=>'Tata Docomo CDMA Landline', 'flexi'=>array('id'=>'209','cca'=>'TATADLLI0NAT01')),'210'=>array('operator'=>'West Bengal State Electricity Distribution Co. Ltd (WBSEDCL)', 'flexi'=>array('id'=>'210','cca'=>'WBSEDCL00WBL01')),'211'=>array('operator'=>'New Delhi Municipal Council (NDMC) - Water', 'flexi'=>array('id'=>'211','cca'=>'NDMC00000DEL01')),'212'=>array('operator'=>'New Delhi Municipal Council (NDMC) - Electricity', 'flexi'=>array('id'=>'212','cca'=>'NDMC00000DEL02')),
            ));
    var $cp_errs = array('7'=>'Invalid amount', '11'=>'Transaction does not exist', '20'=>'The payment is being completed.', '21'=>'Not enough funds for effecting the payment', '22'=>'The payment has not been accepted. Funds transfer error.', '23'=>'Invalid phone number/subid',
            '223'=>'Not appropriate subscriber contract for top-up', '24'=>'Error of connection with the provider’s server', '25'=>'Effecting of this type of payments is suspended.', '26'=>'Payments of this Dealer are temporarily blocked', '27'=>'Operations with this account are suspended',
            '30'=>'General system failure.', '31'=>'Exceeded number of simultaneously processed requests', '32'=>'Repeated payment within 60 minutes', '34'=>'Transaction does not exist', '43'=>'TransactionId expired',
            '45'=>'No license is available for accepting payments to the benefit of this operator.', '52'=>'Dealer blocked', '54'=>'Operator blocked', '81'=>'Max limit of amount exceeded', '82'=>'Daily debit amount limit exceeded');
    var $cp_opr_map = array('1'=>array('op_short_code'=>'ac', 'op_code'=>'1'), '2'=>array('op_short_code'=>'at', 'op_code'=>'0'), '3'=>array('op_short_code'=>'mm', 'op_code'=>'205'), '34'=>array('op_short_code'=>'mm', 'op_code'=>'219'), '4'=>array('op_short_code'=>'id', 'op_code'=>'0'),
            '5'=>array('op_short_code'=>'lm', 'op_code'=>'0'), '6'=>array('op_short_code'=>'mt', 'op_code'=>'0'), '7'=>array('op_short_code'=>'rl', 'op_code'=>'0'), '8'=>array('op_short_code'=>'rl', 'op_code'=>'0'), '9'=>array('op_short_code'=>'dc', 'op_code'=>'0'),
            '27'=>array('op_short_code'=>'dc', 'op_code'=>'0'), '10'=>array('op_short_code'=>'tt', 'op_code'=>'0'), '11'=>array('op_short_code'=>'un', 'op_code'=>'0'), '29'=>array('op_short_code'=>'un', 'op_code'=>'0'), '12'=>array('op_short_code'=>'vm', 'op_code'=>'0'),
            '28'=>array('op_short_code'=>'vm', 'op_code'=>'0'), '15'=>array('op_short_code'=>'vd', 'op_code'=>'0'), '30'=>array('op_short_code'=>'mm', 'op_code'=>'212'), '31'=>array('op_short_code'=>'mm', 'op_code'=>'215'), '16'=>array('op_short_code'=>'ad', 'op_code'=>'0'),
            '17'=>array('op_short_code'=>'bt', 'op_code'=>'0'), '18'=>array('op_short_code'=>'dt', 'op_code'=>'0'), '19'=>array('op_short_code'=>'mm', 'op_code'=>'213'), '20'=>array('op_short_code'=>'ts', 'op_code'=>'0'), '21'=>array('op_short_code'=>'vc', 'op_code'=>'0'),
            '36'=>array('op_short_code'=>'bu', 'op_code'=>'233'), '37'=>array('op_short_code'=>'bu', 'op_code'=>'230'), '38'=>array('op_short_code'=>'bu', 'op_code'=>'231'), '39'=>array('op_short_code'=>'bu', 'op_code'=>'232'), '40'=>array('op_short_code'=>'bu', 'op_code'=>'233'),
            '41'=>array('op_short_code'=>'bu', 'op_code'=>'234'), '42'=>array('op_short_code'=>'ad', 'op_code'=>'225'), '43'=>array('op_short_code'=>'rl', 'op_code'=>'251'), '45'=>array('op_short_code'=>'bu', 'op_code'=>'235', 'bbps_flag'=>true), '46'=>array('op_short_code'=>'bu', 'op_code'=>'236', 'bbps_flag'=>true),
            '47'=>array('op_short_code'=>'bu', 'op_code'=>'237', 'bbps_flag'=>true), '131'=>array('op_short_code'=>'bu', 'op_code'=>'238', 'bbps_flag'=>true), '49'=>array('op_short_code'=>'bu', 'op_code'=>'239'), '50'=>array('op_short_code'=>'bu', 'op_code'=>'240', 'bbps_flag'=>true), '51'=>array('op_short_code'=>'bu', 'op_code'=>'241', 'bbps_flag'=>true),
            '83'=>array('op_short_code'=>'rjio', 'op_code'=>'0'),'85'=>array('op_short_code'=>'bu', 'op_code'=>'310', 'bbps_flag'=>true),'86'=>array('op_short_code'=>'bu', 'op_code'=>'321', 'bbps_flag'=>true),'87'=>array('op_short_code'=>'bu', 'op_code'=>'338', 'bbps_flag'=>true),'88'=>array('op_short_code'=>'bu', 'op_code'=>'344','bbps_flag'=>true),
            '89'=>array('op_short_code'=>'bu', 'op_code'=>'340', 'bbps_flag'=>true),'90'=>array('op_short_code'=>'bu', 'op_code'=>'342','bbps_flag'=>true),'91'=>array('op_short_code'=>'bu', 'op_code'=>'330', 'bbps_flag'=>true),'92'=>array('op_short_code'=>'bu', 'op_code'=>'332', 'bbps_flag'=>true),'93'=>array('op_short_code'=>'bu', 'op_code'=>'315', 'bbps_flag'=>true),'94'=>array('op_short_code'=>'bu', 'op_code'=>'326'),'95'=>array('op_short_code'=>'bu', 'op_code'=>'335', 'bbps_flag'=>true),
            '96'=>array('op_short_code'=>'bu', 'op_code'=>'326', 'bbps_flag'=>true),'97'=>array('op_short_code'=>'bu', 'op_code'=>'317', 'bbps_flag'=>true),'98'=>array('op_short_code'=>'bu', 'op_code'=>'318'),'99'=>array('op_short_code'=>'bu', 'op_code'=>'495', 'bbps_flag'=>true),'100'=>array('op_short_code'=>'bu', 'op_code'=>'325', 'bbps_flag'=>true),
            '101'=>array('op_short_code'=>'bu', 'op_code'=>'333', 'bbps_flag'=>true),'102'=>array('op_short_code'=>'bu', 'op_code'=>'313'),'115'=>array('op_short_code'=>'bu', 'op_code'=>'476', 'bbps_flag'=>true),'116'=>array('op_short_code'=>'bu', 'op_code'=>'477', 'bbps_flag'=>true),'135'=>array('op_short_code'=>'bu', 'op_code'=>'344', 'bbps_flag'=>true),'127'=>array('op_short_code'=>'bu', 'op_code'=>'331', 'bbps_flag'=>true),'117'=>array('op_short_code'=>'bu', 'op_code'=>'480', 'bbps_flag'=>true),
            '136'=>array('op_short_code'=>'bu', 'op_code'=>'481', 'bbps_flag'=>true),'137'=>array('op_short_code'=>'bu', 'op_code'=>'482', 'bbps_flag'=>true),'118'=>array('op_short_code'=>'bu', 'op_code'=>'483', 'bbps_flag'=>true),'119'=>array('op_short_code'=>'bu', 'op_code'=>'485', 'bbps_flag'=>true),'120'=>array('op_short_code'=>'bu', 'op_code'=>'486', 'bbps_flag'=>true),'138'=>array('op_short_code'=>'bu', 'op_code'=>'487', 'bbps_flag'=>true),'139'=>array('op_short_code'=>'bu', 'op_code'=>'488', 'bbps_flag'=>true),
            '132'=>array('op_short_code'=>'bu', 'op_code'=>'491', 'bbps_flag'=>true),'140'=>array('op_short_code'=>'bu', 'op_code'=>'492', 'bbps_flag'=>true),'129'=>array('op_short_code'=>'bu', 'op_code'=>'496', 'bbps_flag'=>true),'121'=>array('op_short_code'=>'bu', 'op_code'=>'497', 'bbps_flag'=>true),'130'=>array('op_short_code'=>'bu', 'op_code'=>'499', 'bbps_flag'=>true),'122'=>array('op_short_code'=>'bu', 'op_code'=>'501', 'bbps_flag'=>true),'124'=>array('op_short_code'=>'bu', 'op_code'=>'502', 'bbps_flag'=>true),
            '133'=>array('op_short_code'=>'bu', 'op_code'=>'484', 'bbps_flag'=>true),'108'=>array('op_short_code'=>'bu', 'op_code'=>'489', 'bbps_flag'=>true),'109'=>array('op_short_code'=>'bu', 'op_code'=>'490', 'bbps_flag'=>true),'107'=>array('op_short_code'=>'bu', 'op_code'=>'500', 'bbps_flag'=>true),'113'=>array('op_short_code'=>'bu', 'op_code'=>'493', 'bbps_flag'=>true),'114'=>array('op_short_code'=>'bu', 'op_code'=>'', 'bbps_flag'=>true),'111'=>array('op_short_code'=>'bu', 'op_code'=>'494', 'bbps_flag'=>true),
            '112'=>array('op_short_code'=>'bu', 'op_code'=>'498', 'bbps_flag'=>true),'141'=>array('op_short_code'=>'bu', 'op_code'=>'479', 'bbps_flag'=>true),'142'=>array('op_short_code'=>'bu', 'op_code'=>'339', 'bbps_flag'=>true),'143'=>array('op_short_code'=>'bu', 'op_code'=>'536', 'bbps_flag'=>true),'144'=>array('op_short_code'=>'bu', 'op_code'=>'537', 'bbps_flag'=>true),'145'=>array('op_short_code'=>'bu', 'op_code'=>'540', 'bbps_flag'=>true),'146'=>array('op_short_code'=>'bu', 'op_code'=>'545', 'bbps_flag'=>true),
            '147'=>array('op_short_code'=>'bu', 'op_code'=>'548', 'bbps_flag'=>true),'148'=>array('op_short_code'=>'bu', 'op_code'=>'319', 'bbps_flag'=>true),'149'=>array('op_short_code'=>'bu', 'op_code'=>'541', 'bbps_flag'=>true),'150'=>array('op_short_code'=>'bu', 'op_code'=>'542', 'bbps_flag'=>true),'151'=>array('op_short_code'=>'bu', 'op_code'=>'517', 'bbps_flag'=>true),'152'=>array('op_short_code'=>'bu', 'op_code'=>'543', 'bbps_flag'=>true),'153'=>array('op_short_code'=>'bu', 'op_code'=>'544', 'bbps_flag'=>true),'154'=>array('op_short_code'=>'bu', 'op_code'=>'543', 'bbps_flag'=>true));
    var $cpurls = array();

    var $cca_exceptions = array('45'=>array('param'),'51'=>array('param'),'87'=>array('param'),'88'=>array('accountNumber','param1'),'90'=>array('param1'),'99'=>array('param'));
    var $cca_extra_params = array('bill_number','bill_date','due_date','bill_amount','bill_period','customer_name');
    var $param_exceptions = array('87','99');

    function createCpUrl($prodId, $type){
        $cp_url = CYBERP_URL;
        $cpurls = array();

        $value = $this->cp_opr_map[$prodId];
        $url = '';
        if($type == 'cpv'){
            $url = ($value['op_code'] > 0) ? $cp_url . $value['op_short_code'] . '/' . $value['op_short_code'] . '_pay_check.cgi/' . $value['op_code'] : $cp_url . $value['op_short_code'] . '/' . $value['op_short_code'] . "_pay_check.cgi";
        }
        else if($type == 'cpl'){
            $url = ($value['op_code'] > 0) ? $cp_url . $value['op_short_code'] . '/' . $value['op_short_code'] . '_pay.cgi/' . $value['op_code'] : $cp_url . $value['op_short_code'] . '/' . $value['op_short_code'] . "_pay.cgi";
        }
        else if($type == 'cps'){
            $url = $cp_url . $value['op_short_code'] . '/' . $value['op_short_code'] . '_pay_status.cgi';
        }

        return $url;
    }

    function apiAutoStatus($vendor_id, $status){
        if($vendor_id == 23){ // apnaeasy
            $vendor_refid = $status['Txid'];
            $operator_id = $status['Opt_id'];
            $stat = strtolower(trim($status['Status']));
            $message = $status['Message'];
        }
        else if($vendor_id == 24 || $vendor_id == 36 || $vendor_id == 62){ // magic pay | rio | rio2
            $operator_id = $status['optransid'];
            $stat = strtolower(trim($status['status']));
            $message = $status['opmsg'];
            $vendor_refid = "";
        }
        else if($vendor_id == 30){ // durgawati pay
            $operator_id = $status['opid'];
            if($operator_id == "N") $operator_id = "";

            if(trim($status['status']) == '1') $stat = 'success';
            else if(trim($status['status']) == '0') $stat = 'failure';

            $vendor_refid = "";
            $message = "";
        }
        else if($vendor_id == 34){ // RKIT
            $operator_id = $status['ORIGINALTRANNO'];
            if($operator_id == "NA") $operator_id = "";

            if(trim($status['STATUS']) == 'SUCCESS') $stat = 'success';
            else if(trim($status['STATUS']) == 'NOT SUCCESS') $stat = 'failure';

            $vendor_refid = "";
            $message = "";
        }

        else if($vendor_id == 47){ // A2Z
            $operator_id = $status['ORIGINALTRANNO'];
            if($operator_id == "NA") $operator_id = "";

            if(trim($status['STATUS']) == 'SUCCESS') $stat = 'success';
            else if(trim($status['STATUS']) == 'NOT SUCCESS') $stat = 'failure';

            $vendor_refid = isset($status['RECNO']) ? $status['RECNO'] : "";
            $message = "";
        }
        else if($vendor_id == 48){ // Joinrecharge
            $operator_id = $status['RechargeStatus']['OperatorTxnId'];
            if($operator_id == "#" || $operator_id == "Array") $operator_id = "";

            if(trim($status['RechargeStatus']['Status']) == 'SUCCESS') $stat = 'success';
            else if(trim($status['RechargeStatus']['Status']) == 'FAILED') $stat = 'failure';

            $vendor_refid = $status['RechargeStatus']['OrderId'];
            $message = $status['RechargeStatus']['Description'];
        }
        else if($vendor_id == 58){ //
            $operator_id = $status['opr_id'];
            if($operator_id == "#" || $operator_id == "Array") $operator_id = "";

            if(trim($status['status']) == 'SUCCESS') $stat = 'success';
            else if(trim($status['status']) == 'REFUND') $stat = 'failure';

            $vendor_refid = $status['ipay_id'];
            $message = $status['res_msg'];
        }
        else if($vendor_id == 57){ // mypay
            $operator_id = $status['txid'];
            $message = "";
            if($operator_id == "#" || $operator_id == "Array") $operator_id = "";

            if(strtoupper(trim($status['Transtype'])) == 'S') {$stat = 'success';}
            else if(strtoupper(trim($status['Transtype'])) == 'F') {$stat = 'failure';$message = $status['txid'];}

            $vendor_refid = $status['accountId'];

        }
        else if($vendor_id == 63){ //
            $operator_id = $status['OPID'];
            if($operator_id == "#" || $operator_id == "Array") $operator_id = "";

            if(strtoupper(trim($status['status'])) == 'SUCCESS') $stat = 'success';
            else if(strtoupper(trim($status['status'])) == 'FAILED') $stat = 'failure';
            else if(strtoupper(trim($status['status'])) == 'REFUNDED') $stat = 'failure';

            $vendor_refid = $status['trans_id'];
            $message = "";
        }
        else if($vendor_id == 65){ //
            $operator_id = $status['txid'];
            $message = "";
            if($operator_id == "#" || $operator_id == "Array") $operator_id = "";

            if(strtoupper(trim($status['transtype'])) == 'SUCCESS') {$stat = 'success';}
            else if(strtoupper(trim($status['transtype'])) == 'FAILED') {$stat = 'failure';$message = $status['txid'];}
            else if(strtoupper(trim($status['sts'])) == 'REFUNDED') {$stat = 'failure';$message = $status['txid'];}
        }
        // practic modem
        else if($vendor_id == 68){ //
            $operator_id = $status['ORef'];
            if(in_array(strtolower(trim($operator_id)), array('nil', 'null', 'array', '#'))) $operator_id = "";

            if(trim($status['RecS']) == '1') $stat = 'success';
            else if(trim($status['RecS']) == '7' || trim($status['RecS']) == '4') $stat = 'failure';

            $message = "";
        }
        // simple recharge modem
        else if($vendor_id == 69){ //
            $operator_id = $status['operator_id'];
            if($operator_id == "#" || $operator_id == "Array") $operator_id = "";

            if(trim($status['status']) == 'Success') $stat = 'success';
            else if(trim($status['status']) == 'Failure') $stat = 'failure';

            $message = "";
        }

        else if($vendor_id == 87){ //
            $operator_id = $status['operator_id'];
            if($operator_id == "#" || $operator_id == "Array") $operator_id = "";

            if(trim($status['status']) == 'Success') $stat = 'success';
            else if(trim($status['status']) == 'Failure') $stat = 'failure';

            $vendor_refid = isset($status['transaction_id']) ? $status['transaction_id'] : "";

            $message = "";
        }

        else if($vendor_id == 105){ // bulk recharge
            $operator_id = $status['operatorid'];
            if($operator_id == "#" || $operator_id == "Array") $operator_id = "";

            if(trim($status['status']) == 'Success') $stat = 'success';
            else if(trim($status['status']) == 'Failure') $stat = 'failure';

            $vendor_refid = isset($status['transaction_id']) ? $status['transaction_id'] : "";

            $message = "";
        }

        else if($vendor_id == 123){ // bimco recharge
            $operator_id = $status['TransactionID'];
            if($operator_id == "#" || $operator_id == "Array") $operator_id = "";

            if(trim($status['Status']) == 'SUCCESS') $stat = 'success';
            else if(trim($status['Status']) == 'FAILURE') $stat = 'failure';

            $vendor_refid = isset($status['OrderId']) ? $status['OrderId'] : "";

            $message = "";
        }

        else if($vendor_id == 125){ // rajan recharge
            $operator_id = $status['transid'];
            if($operator_id == "#" || $operator_id == "Array") $operator_id = "";

            if(trim($status['status']) == 'SUCCESS') $stat = 'success';
            else if(trim($status['status']) == 'FAILURE') $stat = 'failure';

            $vendor_refid = isset($status['ref']) ? $status['ref'] : "";

            $message = "";
        }

        else if($vendor_id == 129){ // pay recharge
            $operator_id = $status['opid'];
            if($operator_id == "#" || $operator_id == "Array") $operator_id = "";

            if(trim($status['status']) == '0') $stat = 'success';
            else if(trim($status['status']) == '1') $stat = 'failure';

            $vendor_refid = isset($status['ref']) ? $status['ref'] : "";

            $message = "";
        }

        else if($vendor_id == 132){ // shivaidea recharge
            $operator_id = $status['optransid'];
            if($operator_id == "#" || $operator_id == "Array") $operator_id = "";

            if(trim($status['status']) == 'SUCCESS') $stat = 'success';
            else if(trim($status['status']) == 'FAILURE') $stat = 'failure';

            $vendor_refid = isset($status['ref']) ? $status['ref'] : "";

            $message = "";
        }
        else if($vendor_id == '134'){ // indicore recharge
            $operator_id = $status['opid'];
            if($operator_id == "#" || $operator_id == "Array") $operator_id = "";

            if(trim($status['status']) == '1') $stat = 'success';
            else if(trim($status['status']) == '0') $stat = 'failure';

            $vendor_refid = isset($status['ref']) ? $status['ref'] : "";

            $message = "";
        }
        else if($vendor_id == SWAMIRAJ_VENDOR_ID){ // swamiraj recharge API
            $operator_id = $status['transid'];
            $message = "";
            if(trim($status['status']) == 'SUCCESS') {$stat = 'success';}
            else if('FAILED' == trim($status['status']) || 'ERROR' == trim($status['status']) || 'REVERT' == trim($status['status'])) {$stat = 'failure';$message = $status['transid'];}

            $vendor_refid = isset($status['refid']) ? $status['refid'] : "";

        }
        else if($vendor_id == MAXRECHARGE_VENDOR_ID){ // maxrecharge recharge API
            $operator_id = $status['OPRTID'];

            if($status['ST'] == 1) $stat = 'success';
            elseif($status['ST'] != 4) $stat = 'failure';

            $vendor_refid = isset($status['TID']) ? $status['TID'] : "";

            $message = $status['STMSG'];
        }
        else if($vendor_id == INDIARECHARGE_VENDOR_ID){
            $operator_id = $status['txid'];
            if($operator_id == "#" || $operator_id == "Array") $operator_id = "";

            if(trim($status['transtype']) == 's') $stat = 'success';
            else if(trim($status['transtype']) == 'f') $stat = 'failure';

            $vendor_refid = isset($status['ref']) ? $status['ref'] : "";

            $message = "";
        }
        else if($vendor_id == AMBIKA_VENDOR_ID){
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ambika.txt",date('Y-m-d H:i:s') . " *status update from ambika *: " . json_encode($status));
            if(array_key_exists('status',$status) && strtolower($status['status'])=='success'){
                $operator_id = $status['opid'];
                $stat = 'success';
                $vendor_refid = $status['rpid'];
                $message = $status['msg'];
            }else if(array_key_exists('status',$status) && (strtolower($status['status'])=='failed')){
                $stat = 'failure';
                $operator_id ='';
                $vendor_refid ='';
                $message = $status['opid'];
            }

        }
        else if($vendor_id == 155){ //ambika roam
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ambika.txt",date('Y-m-d H:i:s') . " *status update from ambikaroam *: " . json_encode($status));
            if(array_key_exists('status',$status) && strtolower($status['status'])=='success'){
                $operator_id = $status['opid'];
                $stat = 'success';
                $vendor_refid = $status['rpid'];
                $message = $status['msg'];
            }else if(array_key_exists('status',$status) && (strtolower($status['status'])=='failed')){
                $stat = 'failure';
                $operator_id ='';
                $vendor_refid ='';
                $message = $status['opid'];
            }

        }
        else if($vendor_id == A1REC_VENDOR_ID){
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/a1rec.txt",date('Y-m-d H:i:s') . " *status update from a1rec *: " . json_encode($status));
            if(array_key_exists('status',$status) && strtolower($status['status'])=='success'){
                $operator_id = $status['field1'];
                $stat = 'success';
                $vendor_refid = '';
                $message = $status['remark'];
            }else if(array_key_exists('status',$status) && (strtolower($status['status'])=='failed' || strtolower($status['status'])=='refund')){
                $stat = 'failure';
                $operator_id ='';
                $vendor_refid ='';
                $message = $status['remark'];
            }

        }
        else if($vendor_id == UNREC_VENDOR_ID){
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/unrec.txt",date('Y-m-d H:i:s') . " *status update from unrec *: " . json_encode($status));
            if(array_key_exists('Status',$status) && strtolower($status['Status'])=='success'){
                $operator_id = $status['SPTransactionId'];
                $stat = 'success';
                $vendor_refid = $status['TransactionId'];
            }else if(array_key_exists('Status',$status) && (strtolower($status['Status'])=='failure' || strtolower($status['Status'])=='refunded')){
                $stat = 'failure';
                $operator_id ='';
                $vendor_refid = $status['TransactionId'];

            }

        }
        else if($vendor_id == CTSWALLET_VENDOR_ID){
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ctswallet.txt",date('Y-m-d H:i:s') . " *status update from ctswallet *: " . json_encode($status));
            if(array_key_exists('Status',$status) && strtolower($status['Status'])=='success'){
                $operator_id = $status['SPTransactionId'];
                $stat = 'success';
                $vendor_refid = $status['TransactionId'];
            }else if(in_array('Status',$status) && (strtolower($status['Status'])=='failure' || strtolower($status['Status'])=='refunded')){
                $stat = 'failure';
                $operator_id ='';
                $vendor_refid = $status['TransactionId'];

            }

        }
        else if($vendor_id == SPEEDREC_VENDOR_ID){
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/speedrec.txt",date('Y-m-d H:i:s') . " *status update from speedrec *: " . json_encode($status));
            if(array_key_exists('TRANSTYPE',$status) && strtolower($status['TRANSTYPE'])=='s'){
                $operator_id = $status['TXNID'];
                $stat = 'success';
                $vendor_refid = $status['ACCOUNTID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/speedrec.txt",date('Y-m-d H:i:s') . " *status update from speedrec *: " . json_encode($status)."opr_id :".$operator_id." status:".$stat);
            }else if(array_key_exists('TRANSTYPE',$status) && (strtolower($status['TRANSTYPE'])=='f')){
                $stat = 'failure';
                $operator_id = $status['TXNID'];
                $vendor_refid = $status['ACCOUNTID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/speedrec.txt",date('Y-m-d H:i:s') . " *status update from speedrec *: " . json_encode($status)."opr_id :".$operator_id." status:".$stat);
            }
            else
            {
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/speedrec.txt",date('Y-m-d H:i:s') . "speedrec status update: " . json_encode($status));
            }

        }
        else if($vendor_id == BIGSHOPREC_VENDOR_ID){
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/bigshoprec.txt",date('Y-m-d H:i:s') . " *status update from bigshoprec *: " . json_encode($status));
            if(array_key_exists('Status',$status) && strtolower($status['Status'])=='success'){
                $stat = 'success';
                $operator_id = $status['TransID'];
                $vendor_refid = $status['TransID'];
            }
            else if(array_key_exists('Status',$status) && strtolower($status['Status'])=='success')
            {
                $stat = 'failure';
                $operator_id = $status['TransID'];
                $vendor_refid = $status['TransID'];
            }

        }
        else if($vendor_id == 158){ //EMONEY
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/emoney.txt",date('Y-m-d H:i:s') . " *status update from emoney *: " . json_encode($status));
            if(array_key_exists('status',$status) && strtolower($status['status'])=='success'){
                $stat = 'success';
                $operator_id = $status['opid'];
                $vendor_refid = $status['rpid'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/emoney.txt",date('Y-m-d H:i:s') . " *status update from emoney *: " . json_encode($status) .'Status : '.$stat.' Opid : '.$operator_id. ' Vendorid : '.$vendor_refid);
            }
            else if(array_key_exists('status',$status) && (strtolower($status['status'])=='failed'))
            {
                $stat = 'failure';
                $operator_id = $status['opid'];
                $vendor_refid = $status['rpid'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/emoney.txt",date('Y-m-d H:i:s') . " *status update from emoney *: " . json_encode($status) .'Status : '.$stat.' Opid : '.$operator_id. ' Vendorid : '.$vendor_refid);
            }

        }
        else if($vendor_id == 157){ //INDIAONE
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/indiaone.txt",date('Y-m-d H:i:s') . " *status update from indiaone *: " . json_encode($status));
            if(array_key_exists('Status',$status) && strtolower($status['Status'])=='success'){
                $stat = 'success';
                $operator_id = $status['OpId'];
                $vendor_refid = $status['Transno'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/indiaone.txt",date('Y-m-d H:i:s') . " *status update from indiaone *: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
            else if(array_key_exists('Status',$status) && in_array(strtolower($status['Status']),array('failure','reversal')))
            {
                $stat = 'failure';
                $operator_id = $status['OpId'];
                $vendor_refid = $status['Transno'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/indiaone.txt",date('Y-m-d H:i:s') . " *status update from indiaone *: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }

        }
        else if($vendor_id == 160){ //SPEEDPAY RECHARGE
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/speedpay.txt",date('Y-m-d H:i:s') . " *status update from speedpay*: " . json_encode($status));
            if(array_key_exists('transtype',$status) && strtolower($status['transtype'])=='s'){
                $stat = 'success';
                $operator_id = $status['txid'];
                $vendor_refid = '';
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/speedpay.txt",date('Y-m-d H:i:s') . " *status update from speedpay*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
            else if(array_key_exists('transtype',$status) && strtolower($status['transtype'])=='f')
            {
                $stat = 'failure';
                $operator_id = $status['txid'];
                $vendor_refid = '';
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/speedpay.txt",date('Y-m-d H:i:s') . " *status update from speedpay*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }

        }
        else if($vendor_id == 162){ //Think Walnut
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/thinkwal.txt",date('Y-m-d H:i:s') . " *status update from thinkwal*: " . json_encode($status));
            if(array_key_exists('errCode',$status) && ($status['errCode'] == '0')){
                $stat = 'success';
                $operator_id = $status['optId'];
                $vendor_refid = $status['txnId'];
            }
            else if(array_key_exists('errCode',$status) && ($status['errCode'] == '12'))
            {
                $stat = 'failure';
                $operator_id = $status['optId'];
                $vendor_refid = $status['txnId'];
            }

        }
        else if($vendor_id == 163){ //Champrecharge
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/champrec.txt",date('Y-m-d H:i:s') . " *status update from champrec*: " . json_encode($status));
            if(array_key_exists('status',$status) && strtolower($status['status'])=='success'){
                $stat = 'success';
                $operator_id = $status['operator_id'];
                $vendor_refid = $status['transaction_id'];
            }
            else if(array_key_exists('status',$status) && strtolower($status['status'])=='failure')
            {
                $stat = 'failure';
                $operator_id = $status['operator_id'];
                $vendor_refid = $status['transaction_id'];
            }

        }
        else if($vendor_id == 164){ //YASHICAENT
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/yashicaent.txt",date('Y-m-d H:i:s') . " *status update from yashicaent*: " . json_encode($status));

            if(array_key_exists('status',$status) && strtolower($status['status'])=='1'){
                $stat = 'success';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }
            else if(array_key_exists('status',$status) && strtolower($status['status'])=='0')
            {
                $stat = 'failure';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }

        }
        else if($vendor_id == 165){ //kumar a2z recharge
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ka2zrec.txt",date('Y-m-d H:i:s') . " *status update from ka2zrec*: " . json_encode($status));
            if(array_key_exists('transtype',$status) && strtolower($status['transtype'])=='s'){
                $stat = 'success';
                $operator_id = $status['Optxid'];
                $vendor_refid = '';
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ka2zrec.txt",date('Y-m-d H:i:s') . " *status update from ka2zrec*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
            else if(array_key_exists('transtype',$status) && strtolower($status['transtype'])=='f')
            {
                $stat = 'failure';
                $operator_id = $status['Optxid'];
                $vendor_refid = '';
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ka2zrec.txt",date('Y-m-d H:i:s') . " *status update from ka2zrec*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }

        }
        else if($vendor_id == 166){ //Round pay
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/roundpay.txt",date('Y-m-d H:i:s') . " *status update from roundpay*: " . json_encode($status));
            if(array_key_exists('status',$status) && strtolower($status['status'])=='success'){
                $stat = 'success';
                $operator_id = $status['opid'];
                $vendor_refid = $status['rpid'];
                $message = $status['msg'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/roundpay.txt",date('Y-m-d H:i:s') . " *status update from roundpay*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
            else if(array_key_exists('status',$status) && strtolower($status['status'])=='failed')
            {
                $stat = 'failure';
                $operator_id = '';
                $vendor_refid = $status['rpid'];
                $message = $status['msg'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/roundpay.txt",date('Y-m-d H:i:s') . " *status update from roundpay*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }

        }
        else if($vendor_id == 167){ //maxxrec pay
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/maxxrec.txt",date('Y-m-d H:i:s') . " *status update from maxxrec*: " . json_encode($status));
            if(array_key_exists('status',$status) && strtolower($status['status'])=='success'){
                $stat = 'success';
                $operator_id = $status['field1'];
                $vendor_refid = '';
                $message = $status['remark'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/maxxrec.txt",date('Y-m-d H:i:s') . " *status update from maxxrec*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
            else if(array_key_exists('status',$status) && (strtolower($status['status'])=='failed' || strtolower($status['status'])=='refund'))
            {
                $stat = 'failure';
                $operator_id = $status['field1'];
                $vendor_refid = '';
                $message = $status['remark'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/maxxrec.txt",date('Y-m-d H:i:s') . " *status update from maxxrec*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
        }
        else if($vendor_id == 168){ //erecpoint
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/erecpoint.txt",date('Y-m-d H:i:s') . " *status update from erecpoint*: " . json_encode($status));
            if(array_key_exists('status',$status) && strtolower($status['status'])=='success'){
                $stat = 'success';
                $operator_id = $status['field1'];
                $vendor_refid = '';
                $message = $status['remark'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/erecpoint.txt",date('Y-m-d H:i:s') . " *status update from erecpoint*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
            else if(array_key_exists('status',$status) && (strtolower($status['status'])=='failed' || strtolower($status['status'])=='refund'))
            {
                $stat = 'failure';
                $operator_id = $status['field1'];
                $vendor_refid = '';
                $message = $status['remark'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/erecpoint.txt",date('Y-m-d H:i:s') . " *status update from erecpoint*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
        }
        else if($vendor_id == 169){ //urec
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/urec.txt",date('Y-m-d H:i:s') . " *status update from urec*: " . json_encode($status));
            if(array_key_exists('Status',$status) && strtolower($status['Status'])=='success'){
                $stat = 'success';
                $operator_id = $status['OpId'];
                $vendor_refid = $status['Transno'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/urec.txt",date('Y-m-d H:i:s') . " *status update from urec*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
            else if(array_key_exists('Status',$status) && in_array(strtolower($status['Status']),array('failure','reversal')))
            {
                $stat = 'failure';
                $operator_id = $status['OpId'];
                $vendor_refid = $status['Transno'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/urec.txt",date('Y-m-d H:i:s') . " *status update from urec*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
        }
        else if($vendor_id == 170){ //pay1all
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pay1all.txt",date('Y-m-d H:i:s') . " *status update from pay1all*: " . json_encode($status));
            if(array_key_exists('status',$status) && strtolower($status['status'])=='success'){
                $stat = 'success';
                $operator_id = $status['opid'];
                $vendor_refid = $status['rpid'];
                $message = $status['msg'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pay1all.txt",date('Y-m-d H:i:s') . " *status update from pay1all*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
            else if(array_key_exists('status',$status) && strtolower($status['status'])=='failed')
            {
                $stat = 'failure';
                $operator_id = $status['opid'];
                $vendor_refid = $status['rpid'];
                $message = $status['msg'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pay1all.txt",date('Y-m-d H:i:s') . " *status update from pay1all*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
        }
        else if($vendor_id == 171){ //prec
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/prec.txt",date('Y-m-d H:i:s') . " *status update from prec*: " . json_encode($status));
            if(array_key_exists('Status',$status) && strtolower($status['Status'])=='success'){
                $stat = 'success';
                $operator_id = $status['OpId'];
                $vendor_refid = $status['Transno'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/prec.txt",date('Y-m-d H:i:s') . " *status update from prec*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
            else if(array_key_exists('Status',$status) && in_array(strtolower($status['Status']),array('failure','reversal')))
            {
                $stat = 'failure';
                $operator_id = $status['OpId'];
                $vendor_refid = $status['Transno'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/prec.txt",date('Y-m-d H:i:s') . " *status update from prec*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
        }
        else if($vendor_id == 172){ //ASHWIN1
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ashw1.txt",date('Y-m-d H:i:s') . " *status update from ashw1*: " . json_encode($status));

            if(array_key_exists('status',$status) && strtolower($status['status'])=='1'){
                $stat = 'success';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }
            else if(array_key_exists('status',$status) && strtolower($status['status'])=='0')
            {
                $stat = 'failure';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }

        }
        else if ($vendor_id == 173) { //pay1click
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pay1click.txt", date('Y-m-d H:i:s') . " *status update from pay1click*: " . json_encode($status));
            if (array_key_exists('Status', $status) && strtolower($status['Status']) == 'success') {
                $stat = 'success';
                $operator_id = $status['TransID'];
                $vendor_refid = $status['OrderID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pay1click.txt", date('Y-m-d H:i:s') . " *status update from pay1click*: " . json_encode($status) . "Opr id: " . $operator_id . " Status: " . $stat);
            } else if (array_key_exists('Status', $status) && strtolower($status['Status']) == 'failure') {
                $stat = 'failure';
                $operator_id = $status['TransID'];
                $vendor_refid = $status['OrderID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pay1click.txt", date('Y-m-d H:i:s') . " *status update from pay1click*: " . json_encode($status) . "Opr id: " . $operator_id . " Status: " . $stat);
            }
        }

        else if($vendor_id == 174){ //kracrecharge
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/kracrec.txt",date('Y-m-d H:i:s') . " *status update from kracrec*: " . json_encode($status));
            if(array_key_exists('transtype',$status) && strtolower($status['transtype'])=='s'){
                $stat = 'success';
                $operator_id = $status['txid'];
                $vendor_refid = '';
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/kracrec.txt",date('Y-m-d H:i:s') . " *status update from kracrec*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
        else if(array_key_exists('transtype',$status) && strtolower($status['transtype'])=='f')
            {
                $stat = 'failure';
                $operator_id = $status['txid'];
                $vendor_refid = '';
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/kracrec.txt",date('Y-m-d H:i:s') . " *status update from kracrec*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }

        }
        else if($vendor_id == 175){ //stelcom
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/stelcom.txt",date('Y-m-d H:i:s') . " *status update from stelcom*: " . json_encode($status));
            if(array_key_exists('status',$status) && strtolower($status['status'])=='success'){
                $stat = 'success';
                $operator_id = $status['success_id'];
                $vendor_refid = $status['trans_no'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/stelcom.txt",date('Y-m-d H:i:s') . " *status update from stelcom*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
            else if(array_key_exists('status',$status) && strtolower($status['status'])=='failure')
            {
                $stat = 'failure';
                $operator_id = $status['success_id'];
                $vendor_refid = $status['trans_no'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/stelcom.txt",date('Y-m-d H:i:s') . " *status update from stelcom*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }

        }
            else if($vendor_id == 176){ //manimaster
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manimaster.txt",date('Y-m-d H:i:s') . " *status update from manimaster*: " . json_encode($status));
            if(array_key_exists('Status',$status) && strtolower($status['Status'])=='success'){
                $stat = 'success';
                $operator_id = $status['OpId'];
                $vendor_refid = $status['Transno'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manimaster.txt",date('Y-m-d H:i:s') . " *status update from manimaster*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
            else if(array_key_exists('Status',$status) && in_array(strtolower($status['Status']),array('failure','reversal')))
            {
                $stat = 'failure';
                $operator_id = $status['OpId'];
                $vendor_refid = $status['Transno'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manimaster.txt",date('Y-m-d H:i:s') . " *status update from manimaster*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }

        }
        else if($vendor_id == 177){ //wellborn
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/wellborn.txt",date('Y-m-d H:i:s') . " *status update from wellborn*: " . json_encode($status));
            if(array_key_exists('status',$status) && strtolower($status['status'])=='success'){
                $stat = 'success';
                $operator_id = $status['success_id'];
                $vendor_refid = $status['trans_no'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/wellborn.txt",date('Y-m-d H:i:s') . " *status update from wellborn*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
            else if(array_key_exists('status',$status) && strtolower($status['status'])=='failure')
            {
                $stat = 'failure';
                $operator_id = $status['success_id'];
                $vendor_refid = $status['trans_no'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/wellborn.txt",date('Y-m-d H:i:s') . " *status update from wellborn*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }

        }
        else if($vendor_id == 178){ //NISHI
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/nishi.txt",date('Y-m-d H:i:s') . " *status update from nishi*: " . json_encode($status));

            if(array_key_exists('status',$status) && strtolower($status['status'])=='1'){
                $stat = 'success';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }
            else if(array_key_exists('status',$status) && strtolower($status['status'])=='0')
            {
                $stat = 'failure';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }

        }
        else if($vendor_id == 179){ //super_Saas
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/supersaas.txt",date('Y-m-d H:i:s') . " *status update from supersaas*: " . json_encode($status));

            if(array_key_exists('status',$status) && strtolower($status['status'])=='1'){
                $stat = 'success';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }
            else if(array_key_exists('status',$status) && strtolower($status['status'])=='0')
            {
                $stat = 'failure';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }

        }
        else if($vendor_id == 180){ //myetopup 
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/myetopup.txt",date('Y-m-d H:i:s') . " *status update from myetopup*: " . json_encode($status));
            if(array_key_exists('status',$status) && strtolower($status['status'])=='success'){
                $stat = 'success';
                $operator_id = $status['field1'];
                $vendor_refid = '';
                $message = $status['remark'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/myetopup.txt",date('Y-m-d H:i:s') . " *status update from myetopup*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
            else if(array_key_exists('status',$status) && (strtolower($status['status'])=='failed' || strtolower($status['status'])=='refund'))
            {
                $stat = 'failure';
                $operator_id = $status['field1'];
                $vendor_refid = '';
                $message = $status['remark'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/myetopup.txt",date('Y-m-d H:i:s') . " *status update from myetopup*: " . json_encode($status)."Opr id: ".$operator_id." Status: ".$stat);
            }
       }
           else if($vendor_id == 181){ //Balaji_Saas
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/balajisaas.txt",date('Y-m-d H:i:s') . " *status update from balajisaas*: " . json_encode($status));

            if(array_key_exists('status',$status) && strtolower($status['status'])=='1'){
                $stat = 'success';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }
            else if(array_key_exists('status',$status) && strtolower($status['status'])=='0')
            {
                $stat = 'failure';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }

        }
        else if($vendor_id == 182){ //Pratistha_Saas
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pratisaas.txt",date('Y-m-d H:i:s') . " *status update from pratisaas*: " . json_encode($status));

            if(array_key_exists('status',$status) && strtolower($status['status'])=='1'){
                $stat = 'success';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }
            else if(array_key_exists('status',$status) && strtolower($status['status'])=='0')
            {
                $stat = 'failure';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }

        }
        else if($vendor_id == 183){ //OSS_Saas
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/osssaas.txt",date('Y-m-d H:i:s') . " *status update from osssaas*: " . json_encode($status));

            if(array_key_exists('status',$status) && strtolower($status['status'])=='1'){
                $stat = 'success';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }
            else if(array_key_exists('status',$status) && strtolower($status['status'])=='0')
            {
                $stat = 'failure';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }

        }
        else if ($vendor_id == 184) { //MANGLAMVODAFONEAPI
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manglamvod.txt",date('Y-m-d H:i:s') . " *status update from manglamvod*: " . json_encode($status));
            $operator_id = $status['operator_id'];
            if ($operator_id == "#" || $operator_id == "Array")
                $operator_id = "";

            if (trim($status['status']) == 'Success')
                $stat = 'success';
            else if (trim($status['status']) == 'Failure')
                $stat = 'failure';

            $vendor_refid = isset($status['transaction_id']) ? $status['transaction_id'] : "";

            $message = "";
        }
        else if($vendor_id == SWAMIRAJAPI_VENDOR_ID){  // SwamiRapi
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/swamirapi.txt",date('Y-m-d H:i:s') . " *status update from swamirapi *: " . json_encode($status));
            if(array_key_exists('TRANSTYPE',$status) && strtolower($status['TRANSTYPE'])=='s'){
                $operator_id = $status['TXNID'];
                $stat = 'success';
                $vendor_refid = $status['ACCOUNTID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/swamirapi.txt",date('Y-m-d H:i:s') . " *status update from swamirapi *: " . json_encode($status)."opr_id :".$operator_id." status:".$stat);
            }else if(array_key_exists('TRANSTYPE',$status) && (strtolower($status['TRANSTYPE'])=='f')){
                $stat = 'failure';
                $operator_id = $status['TXNID'];
                $vendor_refid = $status['ACCOUNTID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/swamirapi.txt",date('Y-m-d H:i:s') . " *status update from swamirapi *: " . json_encode($status)."opr_id :".$operator_id." status:".$stat);
            }
            else
            {
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/swamirapi.txt",date('Y-m-d H:i:s') . "swamirapi status update: " . json_encode($status));
            }

        }
          else if($vendor_id == 186){ //RAJ_Saas
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rajsaas.txt",date('Y-m-d H:i:s') . " *status update from rajsaas*: " . json_encode($status));

            if(array_key_exists('status',$status) && strtolower($status['status'])=='1'){
                $stat = 'success';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }
            else if(array_key_exists('status',$status) && strtolower($status['status'])=='0')
            {
                $stat = 'failure';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }

        }
          else if($vendor_id == 187){ //KUMARSaas
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/kumarsaas.txt",date('Y-m-d H:i:s') . " *status update from kumarsaas*: " . json_encode($status));

            if(array_key_exists('status',$status) && strtolower($status['status'])=='1'){
                $stat = 'success';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }
            else if(array_key_exists('status',$status) && strtolower($status['status'])=='0')
            {
                $stat = 'failure';
                $operator_id = $status['sys_opr_id'];
                $vendor_refid = $status['id'];
            }

        }else if($vendor_id == 188){ //techmate solution
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/techmate.txt", date('Y-m-d H:i:s') . " *status update from techmate *: " . json_encode($status));
            if (array_key_exists('status', $status) && strtolower($status['status']) == 'success') {
                $stat = 'success';
                $operator_id = $status['opid'];
                $vendor_refid = $status['rpid'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/techmate.txt", date('Y-m-d H:i:s') . " *status update from techmate *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('status', $status) && (strtolower($status['status']) == 'failed')) {
                $stat = 'failure';
                $operator_id = $status['opid'];
                $vendor_refid = $status['rpid'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/techmate.txt", date('Y-m-d H:i:s') . " *status update from techmate *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }
        }else if($vendor_id == 189){ //Varsh Robo
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/varsharobo.txt", date('Y-m-d H:i:s') . " *status update from varsharobo *: " . json_encode($status));
            if (array_key_exists('STATUS', $status) && strtolower($status['STATUS']) == '1') {
                $stat = 'success';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/varsharobo.txt", date('Y-m-d H:i:s') . " *status update from varsharobo *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('STATUS', $status) && (strtolower($status['STATUS']) == '3')) {
                $stat = 'failure';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/varsharobo.txt", date('Y-m-d H:i:s') . " *status update from varsharobo *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }
        }else if($vendor_id == 190){ //Quba Robo
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/qubarobo.txt", date('Y-m-d H:i:s') . " *status update from qubarobo *: " . json_encode($status));
            if (array_key_exists('STATUS', $status) && strtolower($status['STATUS']) == '1') {
                $stat = 'success';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/qubarobo.txt", date('Y-m-d H:i:s') . " *status update from qubarobo *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('STATUS', $status) && (strtolower($status['STATUS']) == '3')) {
                $stat = 'failure';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/qubarobo.txt", date('Y-m-d H:i:s') . " *status update from qubarobo *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }
        }else if($vendor_id == 191){ //AV Enterprise Idea Robo
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aventidea.txt", date('Y-m-d H:i:s') . " *status update from aventidea *: " . json_encode($status));
            if (array_key_exists('STATUS', $status) && strtolower($status['STATUS']) == '1') {
                $stat = 'success';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aventidea.txt", date('Y-m-d H:i:s') . " *status update from aventidea *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('STATUS', $status) && (strtolower($status['STATUS']) == '3')) {
                $stat = 'failure';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aventidea.txt", date('Y-m-d H:i:s') . " *status update from aventidea *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }
        }else if($vendor_id == 192){ //threeplus Robo
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/threeplus.txt", date('Y-m-d H:i:s') . " *status update from threeplus *: " . json_encode($status));
            if (array_key_exists('STATUS', $status) && strtolower($status['STATUS']) == '1') {
                $stat = 'success';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/threeplus.txt", date('Y-m-d H:i:s') . " *status update from threeplus *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('STATUS', $status) && (strtolower($status['STATUS']) == '3')) {
                $stat = 'failure';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/threeplus.txt", date('Y-m-d H:i:s') . " *status update from threeplus *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }
        }else if($vendor_id == 193){ //pintoosls Robo
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pintoosls.txt", date('Y-m-d H:i:s') . " *status update from pintoosls *: " . json_encode($status));
            if (array_key_exists('STATUS', $status) && strtolower($status['STATUS']) == '1') {
                $stat = 'success';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pintoosls.txt", date('Y-m-d H:i:s') . " *status update from pintoosls *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('STATUS', $status) && (strtolower($status['STATUS']) == '3')) {
                $stat = 'failure';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pintoosls.txt", date('Y-m-d H:i:s') . " *status update from pintoosls *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }
        }else if($vendor_id == 194){ //aventerp Robo
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aventerp.txt", date('Y-m-d H:i:s') . " *status update from aventerp *: " . json_encode($status));
            if (array_key_exists('STATUS', $status) && strtolower($status['STATUS']) == '1') {
                $stat = 'success';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aventerp.txt", date('Y-m-d H:i:s') . " *status update from aventerp *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('STATUS', $status) && (strtolower($status['STATUS']) == '3')) {
                $stat = 'failure';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aventerp.txt", date('Y-m-d H:i:s') . " *status update from aventerp *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }
        }else if($vendor_id == 195){ //jasscomm Robo
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/jasscomm.txt", date('Y-m-d H:i:s') . " *status update from jasscomm *: " . json_encode($status));
            if (array_key_exists('STATUS', $status) && strtolower($status['STATUS']) == '1') {
                $stat = 'success';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/jasscomm.txt", date('Y-m-d H:i:s') . " *status update from jasscomm *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('STATUS', $status) && (strtolower($status['STATUS']) == '3')) {
                $stat = 'failure';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/jasscomm.txt", date('Y-m-d H:i:s') . " *status update from jasscomm *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }
        }else if($vendor_id == 196){ //nkagency Robo
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/nkagency.txt", date('Y-m-d H:i:s') . " *status update from nkagency *: " . json_encode($status));
            if (array_key_exists('STATUS', $status) && strtolower($status['STATUS']) == '1') {
                $stat = 'success';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/nkagency.txt", date('Y-m-d H:i:s') . " *status update from nkagency *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('STATUS', $status) && (strtolower($status['STATUS']) == '3')) {
                $stat = 'failure';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/nkagency.txt", date('Y-m-d H:i:s') . " *status update from nkagency *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }
        }else if($vendor_id == 197){ //anilkir Robo
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/anilkir.txt", date('Y-m-d H:i:s') . " *status update from anilkir *: " . json_encode($status));
            if (array_key_exists('STATUS', $status) && strtolower($status['STATUS']) == '1') {
                $stat = 'success';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/anilkir.txt", date('Y-m-d H:i:s') . " *status update from anilkir *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('STATUS', $status) && (strtolower($status['STATUS']) == '3')) {
                $stat = 'failure';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/anilkir.txt", date('Y-m-d H:i:s') . " *status update from anilkir *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }
        }else if($vendor_id == 198){ //jeevnrkh Robo
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/jeevnrkh.txt", date('Y-m-d H:i:s') . " *status update from jeevnrkh *: " . json_encode($status));
            if (array_key_exists('STATUS', $status) && strtolower($status['STATUS']) == '1') {
                $stat = 'success';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/jeevnrkh.txt", date('Y-m-d H:i:s') . " *status update from jeevnrkh *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('STATUS', $status) && (strtolower($status['STATUS']) == '3')) {
                $stat = 'failure';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/jeevnrkh.txt", date('Y-m-d H:i:s') . " *status update from jeevnrkh *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }            
        }
        else if($vendor_id == 199){ //  payclick 
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/payclick.txt", date('Y-m-d H:i:s') . " *status update from payclick *: " . json_encode($status));            
            if (array_key_exists('status', $status) && strtolower($status['status']) == 'success') {
                $stat = 'success';
                $operator_id = $status['operator_id'];
                //$vendor_refid = $status['rpid'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/payclick.txt", date('Y-m-d H:i:s') . " *status update from payclick *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('status', $status) && (strtolower($status['status']) == 'failed')) {
                $stat = 'failure';
                $operator_id = $status['operator_id'];
                //$vendor_refid = $status['rpid'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/payclick.txt", date('Y-m-d H:i:s') . " *status update from payclick *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }
        }else if($vendor_id == 200){ //starcom Robo
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/starcom.txt", date('Y-m-d H:i:s') . " *status update from starcom *: " . json_encode($status));
            if (array_key_exists('STATUS', $status) && strtolower($status['STATUS']) == '1') {
                $stat = 'success';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/starcom.txt", date('Y-m-d H:i:s') . " *status update from starcom *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('STATUS', $status) && (strtolower($status['STATUS']) == '3')) {
                $stat = 'failure';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/starcom.txt", date('Y-m-d H:i:s') . " *status update from starcom *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }            
        }else if($vendor_id == 201){ //moderntrad Robo
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/moderntrad.txt", date('Y-m-d H:i:s') . " *status update from moderntrad *: " . json_encode($status));
            if (array_key_exists('STATUS', $status) && strtolower($status['STATUS']) == '1') {
                $stat = 'success';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/moderntrad.txt", date('Y-m-d H:i:s') . " *status update from moderntrad *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('STATUS', $status) && (strtolower($status['STATUS']) == '3')) {
                $stat = 'failure';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/moderntrad.txt", date('Y-m-d H:i:s') . " *status update from moderntrad *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }            
        }else if($vendor_id == 202){ //vjyotrader Robo
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/vjyotrader.txt", date('Y-m-d H:i:s') . " *status update from vjyotrader *: " . json_encode($status));
            if (array_key_exists('STATUS', $status) && strtolower($status['STATUS']) == '1') {
                $stat = 'success';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/vjyotrader.txt", date('Y-m-d H:i:s') . " *status update from vjyotrader *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('STATUS', $status) && (strtolower($status['STATUS']) == '3')) {
                $stat = 'failure';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/vjyotrader.txt", date('Y-m-d H:i:s') . " *status update from vjyotrader *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }            
        }else if($vendor_id == 203){ //aftrader Robo
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aftrader.txt", date('Y-m-d H:i:s') . " *status update from aftrader *: " . json_encode($status));
            if (array_key_exists('STATUS', $status) && strtolower($status['STATUS']) == '1') {
                $stat = 'success';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aftrader.txt", date('Y-m-d H:i:s') . " *status update from aftrader *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('STATUS', $status) && (strtolower($status['STATUS']) == '3')) {
                $stat = 'failure';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aftrader.txt", date('Y-m-d H:i:s') . " *status update from aftrader *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }            
        }else if($vendor_id == 204){ //starmbrobo Robo
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/starmbrobo.txt", date('Y-m-d H:i:s') . " *status update from starmbrobo *: " . json_encode($status));
            if (array_key_exists('STATUS', $status) && strtolower($status['STATUS']) == '1') {
                $stat = 'success';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/starmbrobo.txt", date('Y-m-d H:i:s') . " *status update from starmbrobo *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            } else if (array_key_exists('STATUS', $status) && (strtolower($status['STATUS']) == '3')) {
                $stat = 'failure';
                $operator_id = $status['OPTRANSID'];
                $vendor_refid = $status['ORDERID'];
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/starmbrobo.txt", date('Y-m-d H:i:s') . " *status update from starmbrobo *: " . json_encode($status) . 'Status : ' . $stat . ' Opid : ' . $operator_id . ' Vendorid : ' . $vendor_refid);
            }            
        }
        return array('status'=>$stat, 'operator_id'=>$operator_id, 'vendor_id'=>$vendor_refid, 'description'=>$message);
    }

    function getAgentId($retailer_id,$vendor_id)
    {
        $slaveObj = ClassRegistry::init('Slaves');

        $res = $slaveObj->query("SELECT agent_id FROM bbps_agents WHERE retailer_id = '$retailer_id' AND vendor_id = '$vendor_id'");
        !$res && $res = $slaveObj->query("SELECT agent_id FROM bbps_agents WHERE vendor_id = '$vendor_id' ORDER BY RAND() LIMIT 1");
        $agent_id = $res[0]['bbps_agents']['agent_id'];

        return $agent_id;
    }

    function modemMobRecharge($transId, $params, $prodId, $vendor = 4, $shortForm = 'modem'){
        $mobileNo = $params['mobileNumber'];

        $adm = "query=recharge&oprId=$prodId&mobile=$mobileNo&amount=" . $params['amount'] . "&type=1&transId=$transId&circle=" . $params['area'];
        try{
            $Rec_Data = $this->Shop->modemRequest($adm, $vendor);
            if($Rec_Data['status'] == 'failure'){
                return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'36', 'vendor_response'=>$Rec_Data['error']);
            }
            else{
                $Rec_Data = trim($Rec_Data['data']);
            }
        }
        catch(Exception $e){
            return array('status'=>'pending', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>'', 'pinRefNo'=>'', 'internal_error_code'=>'36', 'vendor_response'=>'Exception found hence keeping request in pending - ' . $e->getMessage());
        }

        $product = $this->mapping['mobRecharge'][$params['operator']]['operator'];

        if(strpos($Rec_Data, 'Error') !== false){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'36', 'vendor_response'=>'');
        }
        else if($Rec_Data == "1"){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'30', 'vendor_response'=>'');
        }
        else if($Rec_Data == "2"){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'40', 'vendor_response'=>'');
        }
        else if($Rec_Data == "3"){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'41', 'vendor_response'=>'');
        }
        else{
            return array('status'=>'pending', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>$Rec_Data, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>'Request assigned to sim');
        }
    }

    function modemBillPayment($transId, $params, $prodId, $vendor = 4, $shortForm = 'modem'){
        $mobileNo = $params['mobileNumber'];

        $adm = "query=recharge&oprId=$prodId&mobile=$mobileNo&amount=" . $params['amount'] . "&type=1&transId=$transId&circle=" . $params['area'];
        try{
            $Rec_Data = $this->Shop->modemRequest($adm, $vendor);
            if($Rec_Data['status'] == 'failure'){
                return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>36, 'vendor_response'=>'');
            }
            else{
                $Rec_Data = $Rec_Data['data'];
            }
        }
        catch(Exception $e){
            return array('status'=>'pending', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>'', 'pinRefNo'=>'', 'internal_error_code'=>36, 'vendor_response'=>'Exception found hence keeping request in pending');
        }

        $product = $this->mapping['billPayment'][$params['operator']]['operator'];

        if(strpos($Rec_Data, 'Error') !== false){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>36, 'vendor_response'=>'');
        }
        else if($Rec_Data == "1"){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>30, 'vendor_response'=>'');
        }
        else if($Rec_Data == "2"){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>40, 'vendor_response'=>'');
        }
        else if($Rec_Data == "3"){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>41, 'vendor_response'=>'');
        }
        else{
            return array('status'=>'pending', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>$Rec_Data, 'pinRefNo'=>'', 'internal_error_code'=>15, 'vendor_response'=>'Request assigned to sim');
        }
    }

    function paytConnection($content){
        $domain = PAYT_URL;

        $data = $this->General->curl_post($domain, $content);
        if( ! $data['success']){
            if($data['timeout']){
                $this->Shop->unHealthyVendor(5);
            }
        }
        else{
            $this->Shop->healthyVendor(5);
        }
        return $data;
    }

    function paytMobRecharge($transId, $params, $prodId = null){
        $mobileNo = $params['mobileNumber'];
        $type = 1;
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
            $type = 3;
        }
        $operator = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['payt'];

        $message = "$operator|0|$mobileNo|" . $params['amount'] . "|$type";
        $date = date('YmdHis');
        $terminal = PAYT_USER_CODE;
        $sha = strtoupper(sha1($terminal . $transId . $message . $date . PAYT_PASSWORD));
        $message = urlencode($message);
        $sha = urlencode($sha);
        $content = "OperationType=1&TerminalId=$terminal&TransactionId=$transId&DateTimeStamp=$date&Message=$message&Hash=$sha";

        $Rec_Data = $this->paytConnection($content);
        if( ! $Rec_Data['success']){
            if($Rec_Data['timeout']){
                return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Connectivity timeout from payt');
            }
        }

        $Rec_Data = $Rec_Data['output'];
        $response = explode("|", $Rec_Data);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/payt.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($response));

        if(count($response) == 6 && $transId == trim($response[1])){
            $ref_id = trim($response[2]);
            $status = trim($response[0]);
            $opr_id = trim($response[4]);
            $err_code = trim($response[3]);
            if($err_code == 'NA') $opr_id = "";
            if($opr_id == 'NA') $opr_id = "";
            $desc = trim($response[5]);

            if($status == '0' && $err_code != '8'){ // success
                return array('status'=>'success', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>$ref_id, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$desc);
            }
            else if($status == '1' || $err_code == '8'){ // under process
                return array('status'=>'pending', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>$ref_id, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$desc);
            }
            else if($status == '2'){ // failure
                $code = ($err_code == 11) ? 5 : (($err_code == 13) ? 6 : 30);
                $this->Recharge->changeTataId($code, $prodId, $transId);
                return array('status'=>'failure', 'code'=>30, 'description'=>$this->Shop->errors($code), 'tranId'=>$ref_id, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'14', 'vendor_response'=>"Error code: $err_code, " . $desc);
            }
        }
        else{
            $data = $this->paytTranStatus($transId);

            if($data['status'] == 'success'){
                return array('status'=>'success', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>$data['ref_id'], 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'13', 'vendor_response'=>$data['description']);
            }
            else if($data['status'] == 'pending'){
                return array('status'=>'pending', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>$data['ref_id'], 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$data['description']);
            }
            else if($data['status'] == 'failure'){
                return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$data['ref_id'], 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>$data['description']);
            }
        }
    }

    function cpConnect($url, $param){
        $out = $this->General->curl_post($url, $param,'POST',30,10,true,true,false);
        return $out;
    }

    function cpMobRecharge($transId, $params, $prodId, $recheck = false, $attempt = 0){
        $sec = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pub_keys/' . CYBER_SECKEY);
        $pub = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pub_keys/' . CYBER_PUBKEY);

        //$sec = defined('CYBER_PUBKEY_STR') ? CYBER_SECKEY_STR : $sec;
        //$pub = defined('CYBER_PUBKEY_STR') ? CYBER_PUBKEY_STR : $pub;
        // $prodId = ($prodId == '27' && $recheck === false) ? 9 : $prodId;

        $extra = "ACCOUNT=\r\n";
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $extra = "ACCOUNT=2\r\n";
        }
        else if(in_array($prodId, array('12'))){
            $extra = "ACCOUNT=1\r\n";
        }
        else if(in_array($prodId, array('83'))){
            $plan = $this->General->getJioPlan();
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cp.txt", "Plan Offer *: \n" . json_encode($plan));
            if(in_array($params['amount'], array_keys($plan))){
                $extra .= "PlanOffer=" . $plan[$params['amount']]['offId'] . "\r\n";
            }
            else{
                return array('status'=>'failure', 'code'=>'6', 'description'=>$this->Shop->errors(6), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'');
            }
        }

        if($prodId == '2'){
            $dbObj = ClassRegistry::init('Slaves');
            $store_data = $dbObj->query("SELECT store_code FROM cp_retailers WHERE service_id='Prepaid' AND active_flag = 1 ORDER BY RAND() LIMIT 1");
            if( ! empty($store_data)){
                $extra .= "TERM_ID=" . trim($store_data[0]['cp_retailers']['store_code']) . "\r\n";
            }
            else{
                return array('status'=>'failure', 'code'=>'25', 'description'=>$this->Shop->errors(25), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'');
            }
        }

        $mobileNo = $params['mobileNumber'];
        $amount = $params['amount'];

        $verify_url = $this->createCpUrl($prodId, 'cpv');
        $data = "SD=" . CYBER_SD . "\r\nAP=" . CYBER_AP . "\r\nOP=" . CYBER_OP . "\r\nSESSION=$transId\r\nNUMBER=$mobileNo\r\n" . $extra . "AMOUNT=" . floatval($amount) . "\r\nAMOUNT_ALL=$amount\r\nCOMMENT=\r\n";

        $res = ipriv_sign($data, $sec, CYBER_PASSWORD);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cp.txt", $transId . "  ipriv_sign response *: \n" . json_encode($res));
        $out = $this->cpConnect($verify_url, array('inputmessage'=>$res[1]));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cp.txt", $transId . "  out response *: \n" . json_encode($out));

        $cp_encode_input_data = $res;
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = $out['output'];

        $res = ipriv_verify($out, $pub);
        $result = $this->cpArray($res);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cp.txt", $transId . "  result response *: \n" . json_encode($result) . "res response " . json_encode($res));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cp.txt", "*Mob Recharge - Step1*: $verify_url, Input=> " . $data . "\n" . json_encode($result) . " | out : " . json_encode($out) . " | cp_encode_input_data:" . json_encode($cp_encode_input_data));
        if(isset($cp_encode_input_data[0]) && $cp_encode_input_data[0] == "-9"){
            $this->General->logData("cp_null_log.txt", " |<$transId>|" . $sec . "||");
        }
        if(empty($result) && intval($attempt) < 1){
            $attempt = intval($attempt) + 1;
            return $this->cpMobRecharge($transId, $params, $prodId, $recheck, $attempt);
        }

        if(isset($result['RESULT']) && $result['RESULT'] == 0 && empty($result['ERROR'])){
            if(isset($result['AUTHCODE'])) $operator_id = $result['AUTHCODE'];

            $live_url = $this->createCpUrl($prodId, 'cpl');
            $data = "SD=" . CYBER_SD . "\r\nAP=" . CYBER_AP . "\r\nOP=" . CYBER_OP . "\r\nSESSION=$transId\r\nNUMBER=$mobileNo\r\n" . $extra . "AMOUNT=" . floatval($amount) . "\r\nPAY_TOOL=0\r\n";
            $res = ipriv_sign($data, $sec, CYBER_PASSWORD);

            $out = $this->cpConnect($live_url, array('inputmessage'=>$res[1]));

            if( ! $out['success']){
                if($out['timeout']){
                    return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
                }
                else{
                    return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>'Request timed out while recharging');
                }
            }
            else{
                $out = $out['output'];
            }

            $res = ipriv_verify($out, $pub);
            $result = $this->cpArray($res);
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cp.txt", "*Mob Recharge - Step2*: $live_url, Input=> " . $data . "\n" . json_encode($result));

            $vendorTransId = (isset($result['TRANSID'])) ? $result['TRANSID'] : 0;
            $opr_id = (isset($result['AUTHCODE'])) ? $result['AUTHCODE'] : 0;
            $trans_status = (isset($result['TRNXSTATUS'])) ? $result['TRNXSTATUS'] : 3;

            if($trans_status != 7){
                $status = $this->cpTranStatus($transId, null, null, $prodId);
            }
            else{
                $status['status'] = 'success';
                $status['vendor_id'] = $vendorTransId;
            }

            $error =  ! empty($status['status-code']) ? $status['status-code'] : $result['ERROR'];
            $errCode = $this->Shop->errorCodeMapping(8, $error);

            if($status['status'] == 'error' || $status['status'] == 'inprocess' || $status['status'] == 'incomplete'){ // in process
                return array('status'=>'pending', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>$status['vendor_id'], 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15',
                        'vendor_response'=>$status['status'] . "::" . $status['description'] . "(" . $status['status-code'] . ")");
            }
            else if($status['status'] == 'failure'){
                return array('status'=>'failure', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>$status['vendor_id'], 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'14', 'vendor_response'=>$status['description'] . "(" . $status['status-code'] . ")");
            }
            else if($status['status'] == 'success'){
                return array('status'=>'success', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>$status['vendor_id'], 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>'success');
            }
        }
        else{
            $errCode = $this->Shop->errorCodeMapping(8, $result['ERROR']);
            $tatachangeID = $this->Recharge->changeTataId($errCode, $prodId, $transId);
            $err = "Error code: $error," . $this->cp_errs[$result['ERROR']];
            if(array_key_exists('ERRMSG', $result))
            {
               $err.=" ".$result['ERRMSG'];
            }

            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cp.txt", "*Mob Recharge - Authorization error *: txnid = $transId |code =  " . $result['ERROR']);
            return array('status'=>'failure', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>'', 'internal_error_code'=>'42', 'vendor_response'=>$err);
        }
    }

    function magicMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['magic'];
        $vendor = 24;

            $url = MAGIC_RECHARGE_URL;
        $out = $this->General->magicApi($url, array('uid'=>MAGIC_USERNAME, 'pwd'=>MAGIC_PASSWD, 'rcode'=>$provider, 'mobileno'=>$mobileNo, 'amt'=>$amount, 'transid'=>$transId));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_repsonse'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/magic.txt", "*Mob Recharge*: Input=> $transId<br/>$out");

        // 297499#Pending#ACCEPT
        $status = trim($out);
        $txnId = time();
        $txnId .= rand(100, 999);

        $status_array = array('1200'=>'Request Accepted', '1201'=>'Invalid Login', '1202'=>'Invalid Mobile Number', '1203'=>'Invalid Amount', '1204'=>'Transaction ID missing', '1205'=>'Operator not found', '1206'=>'Permission Required', '1207'=>'Balance Limit', '1208'=>'Low Balance',
                '1209'=>'Duplicate Request', '1210'=>'Request not accepted', '1211'=>'Recharge server not connected', '1212'=>'Authentication Failed');
        $out = $status_array[$status];

        if(empty($status) || $status == '1200'){
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
        else{
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
    }

    function rioMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['rio'];
        $vendor = 36;

        $url = RIO_RECHARGE_URL;
        $out = $this->General->rioApi($url, array('uid'=>RIO_USERNAME, 'pwd'=>RIO_PASSWD, 'rcode'=>$provider, 'mobileno'=>$mobileNo, 'amt'=>$amount, 'transid'=>$transId));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_reponse'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rio.txt", "*Mob Recharge*: Input=> $transId<br/>$out");

        // 297499#Pending#ACCEPT
        $status = trim($out);
        $txnId = time();
        $txnId .= rand(100, 999);

        $status_array = array('1200'=>'Request Accepted', '1201'=>'Invalid Login', '1202'=>'Invalid Mobile Number', '1203'=>'Invalid Amount', '1204'=>'Transaction ID missing', '1205'=>'Operator not found', '1206'=>'Permission Required', '1207'=>'Balance Limit', '1208'=>'Low Balance',
                '1209'=>'Duplicate Request', '1210'=>'Request not accepted', '1211'=>'Recharge server not connected', '1212'=>'Authentication Failed');
        $out = $status_array[$status];

        if(empty($status) || $status == '1200'){
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
        else{
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
    }

    function rio2MobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['rio2'];
        $vendor = 62;

        $url = RIO2_RECHARGE_URL;
        $out = $this->General->rioApi($url, array('uid'=>RIO2_USERNAME, 'pwd'=>RIO2_PASSWD, 'rcode'=>$provider, 'mobileno'=>$mobileNo, 'amt'=>$amount, 'transid'=>$transId));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rio2.txt", "*Mob Recharge*: Input=> $transId<br/>$out");

        // 297499#Pending#ACCEPT
        $status = trim($out);
        $txnId = time();
        $txnId .= rand(100, 999);

        $status_array = array('1200'=>'Request Accepted', '1201'=>'Invalid Login', '1202'=>'Invalid Mobile Number', '1203'=>'Invalid Amount', '1204'=>'Transaction ID missing', '1205'=>'Operator not found', '1206'=>'Permission Required', '1207'=>'Balance Limit', '1208'=>'Low Balance',
                '1209'=>'Duplicate Request', '1210'=>'Request not accepted', '1211'=>'Recharge server not connected', '1212'=>'Authentication Failed');
        $out = $status_array[$status];

        if(empty($status) || $status == '1200'){
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
        else{
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
    }

    function infogemMobRecharge($transId, $params, $prodId = null){
        $mobileNo = $params['mobileNumber'];
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }
        $operator = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['gem'];

        $message = "$operator|$mobileNo|" . $params['amount'];
        $vendor = 27;
        $terminal = GEM_USERNAME;
        $sha = strtoupper(sha1($terminal . $transId . $message . GEM_PASSWORD));

        $url = GEM_RECHARGE_URL;
        $out = $this->General->gemApi($url, array('PartnerId'=>$terminal, 'TransId'=>$transId, 'Message'=>$message, 'Hash'=>$sha));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/gem.txt", "*Mob Recharge*: Input=> $transId<br/>$out");

        $Rec_Data = $out['output'];
        $response = explode("|", $Rec_Data);
        $status = trim($response[0]);
        $txnId = trim($response[2]);
        $opr_id = trim($response[3]);
        $desc = trim($response[4]);

        if($opr_id == 'NA') $opr_id = "";

        $status_array = array('100'=>'Transaction Successful', '99'=>'Recharge Failed', '101'=>'Invalid Login', '102'=>'Insufficient Balance', '103'=>'Invalid Amount', '104'=>'Invalid Trans ID', '105'=>'Trans ID already exists', '106'=>'Service Unavailable for user', '107'=>'Invalid phone Number',
                '110'=>'Invalid Transaction amount', '111'=>'Daily Limit reached', '121'=>'Account Blocked', '123'=>'Technical Failure', '165'=>'Response waiting', '170'=>'Wrong Requested Ip', '171'=>'Repeated Request', '173'=>'Operator temporarly not available', '172'=>'Invalid request',
                '174'=>'Hash Value MisMatch');
        if(empty($desc)) $desc = $status_array[$status];
        if(empty($desc)) $desc = $Rec_Data;

        if(empty($status) || $status == '165'){ // pending
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$desc);
        }
        else if($status == '100'){ // success
            return array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$txnId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$desc);
        }
        else{ // failure
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$desc);
        }
    }

    function rkitMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['rkit'];
        $vendor = 34;
        $rcType = 'NORMAL';
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $rcType = 'SPECIAL';
        }

        $url = RKIT_RECHARGE_URL;
        $out = $this->General->rkitApi($url, array('USERID'=>RKIT_USER, 'PASSWORD'=>RKIT_AUTH, 'SUBSCRIBER'=>$mobileNo, 'AMOUNT'=>$amount, 'TRANNO'=>$transId, 'RECTYPE'=>$rcType, 'OPERATOR'=>$provider));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rkit.txt", "*Mob Recharge*: Input=> $transId<br/>$out");

        // 1. SUCCESS# RECHARGE POSTED # TRANREFNO #98888
        // 3. ERROR# INVALID MOBILE ENTRY #
        // TRANNO:9836-STATUS:SUCCESS
        // ERRCODE:104-OPERATOR IS NOT AVAILABLE
        // $out1 = explode(":",$out);
        $txnId = "";

        if($out['NODE']['STATUS'] == 'FAILED'){
            $description = trim($out1['NODE']['STATUS']);
            $txnId = $out['NODE']['TXNID'];
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
            $txnId = $out['NODE']['TXNID'];
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
    }

    function a2zMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['a2z'];
        $vendor = 47;
        $rcType = 'NORMAL';
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $rcType = 'SPECIAL';
        }

        // $transId = 321456378262;
        //
        // $provider = 2;
        //
        // $amount = 10;
        //
        // $mobileNo = 9975629244;

        $url = A2Z_RECHARGE_URL;

        $params = array('USERID'=>A2Z_AGTCODE, 'PASSWORD'=>A2Z_AUTH, 'OPERATOR'=>$provider, 'AMOUNT'=>$amount, 'SUBSCRIBER'=>$mobileNo, 'TRANNO'=>$transId, 'RECTYPE'=>$rcType);

        $out = $this->General->a2zApi($url, $params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $this->General->xml2array("<NODE>" . $out['output'] . "</NODE>");

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/a2z.txt", "*Mob Recharge*: Input=> " . json_encode($params) . "<br/>" . json_encode($out));

        // 1. SUCCESS# RECHARGE POSTED # TRANREFNO #98888
        // 3. ERROR# INVALID MOBILE ENTRY #

        if($out['NODE']['STATUS'] == 'FAILED'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$out['NODE']['TXNID'], 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$out['NODE']['TXNID'], 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
    }

    function joinrecMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['joinrec'];
        $vendor = 48;
        $rcType = '';
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $rcType = 'special';
        }

        /* ---only related to joinrecharge airtel */
        if($prodId == 2 && $amount < 50){
            return array('status'=>'failure', 'code'=>'29', 'description'=>$this->Shop->errors(29), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'29', 'vendor_response'=>'Not sending airtel recharge of less than rs50');
        }

        $url = JOINREC_RECHARGE_URL;
        $out = $this->General->joinrecApi($url, array('reseller_id'=>JOINREC_ID, 'reseller_pass'=>JOINREC_PWD, 'mobilenumber'=>$mobileNo, 'denomination'=>$amount, 'meroid'=>$transId, 'voucher'=>$rcType, 'operatorid'=>$provider, 'circleid'=>'*'));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/joinrec.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out));

        if(isset($out['Data']['Error'])){
            $status = 'FAILED';
            $description = trim($out['Data']['Error']);
        }
        else{
            $status = trim($out['Data']['Status']);
            $description = trim($out['Data']['Description']);
            if($description == 'NA') $description = "Request accepted";
            $txnId = trim($out['Data']['OrderId']);
        }

        if($status == 'FAILED'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$description);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$description);
        }
    }

    function mypayMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['mypay'];
        $vendor = 57;
        $rcType = '0';
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $rcType = '1';
        }

        $url = MYPAYURL;
        $pwd = MYPAYPASSWD;
        $dt_time = date('m.d.Y H:i:s');

        // pwd|mob|amt|opr|type|datetime
        $req_str = $pwd . "|" . $mobileNo . "|" . $amount . "|" . $provider . "|" . $rcType . "|" . $dt_time . "|" . $transId;

        $out = $this->General->mypayApi($url, array('_prcsr'=>MYPAYUSER, '_urlenc'=>$req_str, '_encuse'=>0));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/mypay.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out));

        if(isset($out['_ApiResponse']['statusCode']) && strtolower($out['_ApiResponse']['statusCode']) != "10008"){
            $status = 'FAILED';
            $description = trim($out['_ApiResponse']['statusDescription']);
        }
        else{
            $status = trim($out['_ApiResponse']['statusCode']);
            $description = isset($out['_ApiResponse']['statusDescription']) ? trim($out['_ApiResponse']['statusDescription']) : "NA";
            if($description == 'NA') $description = "Request accepted";
            $txnId = trim($out['_ApiResponse']['requestID']);
        }

        if($status == 'FAILED'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$description);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$description);
        }
    }

    /*
     * smsdaak trans status check
     */
    function smsdaakTranStatus($transId, $date = null, $refId = null){
        $url = SMSDAAK_TRANS_URL;

        $request_param = array('token'=>SMSDAAK_TOKEN, 'agentid'=>$transId, 'format'=>'xml');

        $out = $this->General->smsdaakApi($url, $request_param);

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/smsdaak.txt", date('Y-m-d H:i:s') . ":smsdaakTranStatus: | " . $transId . " | " . json_encode($out));

        $status = isset($out['xml']['status']) ? trim($out['xml']['status']) : "";
        $operator_id = isset($out['xml']['opr_id']) ? trim($out['xml']['opr_id']) : "";
        $vendor_id = isset($out['xml']['ipay_id']) ? trim($out['xml']['ipay_id']) : "";

        if(isset($out['xml']['ipay_errorcode'])){
            $description = trim($out['xml']['ipay_errordesc']);
            if( ! in_array($out['xml']['ipay_errorcode'], array("TXN", "TUP", "RPI"))){
                $status = "FAILED";
            }
        }
        else
            $description = trim($out['xml']['res_msg']);

        if($status == 'SUCCESS'){
            $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$description, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else if(in_array($status, array('FAILED', 'REFUND'))){
            $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$description, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$description, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }

        return $ret;
    }

    /*
     * smsdaak prepaid mobile recharges
     */
    function smsdaakMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['smsdaak'];
        $vendor = 58;

        $url = SMSDAAK_RECHARGE_URL;
        // --- request parameter
        $request_param = $validation_param = array('token'=>SMSDAAK_TOKEN, 'spkey'=>$provider, 'agentid'=>$transId, 'account'=>$mobileNo, 'amount'=>$amount, 'format'=>'xml');

        // ---validating request
        $validation_param['mode'] = 'VALIDATE';
        $out = $this->General->smsdaakApi($url, $validation_param);
        $service_id = '1';

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/smsdaak.txt", "*Mob Recharge validation request *: Input=> $transId<br/>" . json_encode($out) . " : input : " . json_encode($validation_param));

        // -----------handle success / failure of validation hit
        if(strtoupper($out['xml']['ipay_errorcode']) == 'TXN'){
            $out = "";
            $out = $this->General->smsdaakApi($url, $request_param);
            if( ! $out['success']){
                if($out['timeout']){
                    return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
                }
            }
            $out = $out['output'];
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/smsdaak.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . " : input : " . json_encode($request_param));

            $txnId = isset($out['xml']['ipay_id']) ? $out['xml']['ipay_id'] : "";
            $opr_id = isset($out['xml']['status']) ? $out['xml']['opr_id'] : "";
            if(isset($out['xml']['status'])){
                $error =  ! empty($out['xml']['res_code']) ? $out['xml']['res_code'] : "";
                $errCode = $this->Shop->errorCodeMapping($vendor, $error);
                if(strtoupper(trim($out['xml']['status'])) == "SUCCESS"){
                    $description = trim($out['xml']['res_msg']);

                    if(empty($description)) $description = 'success';
                    return array('status'=>'success', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>$txnId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$description);
                }
                else{
                    return array('status'=>'pending', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$description);
                }
            }
        }

        // ----------- handle failure of validation and payment hit
        if(isset($out['xml']['ipay_errorcode']) && strtoupper($out['xml']['ipay_errorcode']) != 'TXN'){
            $description = trim($out['xml']['ipay_errordesc']);
            $error =  ! empty($out['xml']['ipay_errorcode']) ? $out['xml']['ipay_errorcode'] : "";
            $errCode = $this->Shop->errorCodeMapping($vendor, $error);
            $txnId = "";
            return array('status'=>'failure', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$description);
        }
    }

    /*
     * smsdaak dth recharge
     */
    function smsdaakDthRecharge($transId, $params, $prodId){
        $mobileNo = $params['subId'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['smsdaak'];
        $vendor = 58;

        $url = SMSDAAK_RECHARGE_URL;
        // --- request parameter
        $request_param = $validation_param = array('token'=>SMSDAAK_TOKEN, 'spkey'=>$provider, 'agentid'=>$transId, 'account'=>$mobileNo, 'amount'=>$amount, 'format'=>'xml');

        // ---validating request
        $validation_param['mode'] = 'VALIDATE';
        $out = $this->General->smsdaakApi($url, $validation_param);
        $service_id = '2';

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/smsdaak.txt", "*Dth Recharge validation request *: Input=> $transId<br/>" . json_encode($out));

        // -----------handle success / failure of validation hit
        if(strtoupper($out['xml']['ipay_errorcode']) == 'TXN'){
            $out = "";
            $out = $this->General->smsdaakApi($url, $request_param);
            if( ! $out['success']){
                if($out['timeout']){
                    return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
                }
            }
            $out = $out['output'];
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/smsdaak.txt", "*Dth Recharge*: Input=> $transId<br/>" . json_encode($out));
            $opr_id = isset($out['xml']['status']) ? $out['xml']['opr_id'] : "";
            $txnId = isset($out['xml']['ipay_id']) ? $out['xml']['ipay_id'] : "";
            if(isset($out['xml']['status'])){
                $error =  ! empty($out['xml']['res_code']) ? $out['xml']['res_code'] : "";
                $errCode = $this->Shop->errorCodeMapping($vendor, $error);
                if(strtoupper(trim($out['xml']['status'])) == "SUCCESS"){
                    $description = trim($out['xml']['res_msg']);
                    if(empty($description)) $description = 'success';
                    return array('status'=>'success', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>$txnId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$description);
                }
                else{
                    return array('status'=>'pending', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
                }
            }
        }

        // ----------- handle failure of validation and payment hit
        if(isset($out['xml']['ipay_errorcode']) && strtoupper($out['xml']['ipay_errorcode']) != 'TXN'){
            $description = trim($out['xml']['ipay_errordesc']);
            $txnId = "";
            $error =  ! empty($out['xml']['ipay_errorcode']) ? $out['xml']['ipay_errorcode'] : "";
            $errCode = $this->Shop->errorCodeMapping($vendor, $error);
            return array('status'=>'failure', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$description);
        }
    }

    /*
     * smsdaak Bill Payment
     */
    function smsdaakBillPayment($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['billPayment'][$params['operator']][$params['type']]['smsdaak'];
        $vendor = 58;

        $url = SMSDAAK_RECHARGE_URL;
        // --- request parameter
        $request_param = $validation_param = array('token'=>SMSDAAK_TOKEN, 'spkey'=>$provider, 'agentid'=>$transId, 'account'=>$mobileNo, 'amount'=>$amount, 'format'=>'xml');

        // ---validating request
        $validation_param['mode'] = 'VALIDATE';
        $out = $this->General->smsdaakApi($url, $validation_param);
        $service_id = '4';

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/smsdaak.txt", "*BillPayment validation request *: Input=> $transId<br/>" . json_encode($out));

        // -----------handle success / failure of validation hit
        if(strtoupper($out['xml']['ipay_errorcode']) == 'TXN'){
            $out = "";
            $out = $this->General->smsdaakApi($url, $request_param);
            if( ! $out['success']){
                if($out['timeout']){
                    return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>14, 'vendor_response'=>'Not able to connect to server');
                }
            }
            $out = $out['output'];
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/smsdaak.txt", "* BillPayment *: Input=> $transId<br/>" . json_encode($out));

            $txnId = isset($out['xml']['ipay_id']) ? $out['xml']['ipay_id'] : "";
            $opr_id = isset($out['xml']['status']) ? $out['xml']['opr_id'] : "";

            if(isset($out['xml']['status'])){
                $error =  ! empty($out['xml']['res_code']) ? $out['xml']['res_code'] : "";
                $errCode = $this->Shop->errorCodeMapping($vendor, $error);
                if(strtoupper(trim($out['xml']['status'])) == "SUCCESS"){
                    $description = trim($out['xml']['res_msg']);

                    if(empty($description)) $description = 'success';
                    return array('status'=>'success', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>$txnId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>13, 'vendor_response'=>$description);
                }
                else{
                    return array('status'=>'pending', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>15, 'vendor_response'=>$out);
                }
            }
        }

        // ----------- handle failure of validation and payment hit
        if(isset($out['xml']['ipay_errorcode']) && strtoupper($out['xml']['ipay_errorcode']) != 'TXN'){
            $description = trim($out['xml']['ipay_errordesc']);
            $error =  ! empty($out['xml']['ipay_errorcode']) ? $out['xml']['ipay_errorcode'] : "";
            $errCode = $this->Shop->errorCodeMapping($vendor, $error);
            $txnId = "";
            return array('status'=>'failure', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>$txnId, 'internal_error_code'=>30, 'vendor_response'=>$description);
        }
    }

    /*
     * smsdaak check balance
     */
    function smsdaakBalance(){
        $vendor_id = 58;

        $url = SMSDAAK_BAL_URL;
        $request_param = array('token'=>SMSDAAK_TOKEN, 'format'=>'xml');

        $out = $this->General->smsdaakApi($url, $request_param);
        $out = $out['output'];
        $bal = $out['xml']['Wallet'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/smsdaak.txt", date('Y-m-d H:i:s') . ":Balance Check: " . json_encode($out));
        return array('balance'=>$bal);
    }

    /*
     * hitechrec
     */
    function hitechBalance(){
        $vendor_id = 65;
        $url = HITECH_BAL_URL;
        $request_param = array('uid'=>HITECH_USERID, 'pass'=>HITECH_PASSWORD, 'mno'=>HITECH_MNO, 'msg'=>'cb');

        $out = $this->General->hitechrecApi($url, $request_param);
        $out_arr = $out['output'];
        $messag_arr = explode(" ", $out_arr['NODES']['MESSAGE']);

        $out = explode(':', $messag_arr[1]);
        $out =  ! empty($out[1]) ? $out[1] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/hitech.txt", date('Y-m-d H:i:s') . ":Balance Check: " . json_encode($out));

        return array('balance'=>$out);
    }

    /*
     * hitech
     */
    function hitechMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['hitecrec'];
        $vendor = 65;

        $url = HITECH_RECHARGE_URL;
        // --- request parameter
        $request_param = array('Key'=>'OXCNy4iR7', 'mno'=>$mobileNo, 'op'=>$provider, 'amt'=>$amount, 'Refid'=>$transId);

        $out = $this->General->hitechrecApi($url, $request_param);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/hitech.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out));

        if(isset($out['NODES']) && strtolower($out['NODES']['STATUS']) == 'failed'){
            $status = 'FAILED';
            $description = isset($out['NODES']['REFID']) ? trim($out['NODES']['REFID']) : "";
        }
        else{
            $status = trim($out['NODES']['STATUS']);
            $description = "NA";
            if($description == 'NA') $description = "Request accepted";
            $txnId = trim($out['NODES']['REFID']);
            $oprId = isset($out['NODES']['OPERATORID'])?trim($out['NODES']['OPERATORID']):'';
        }

        if($status == 'FAILED'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }elseif(strtolower($status) == 'success'){
            return array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$txnId, 'pinRefNo'=>'', 'operator_id'=>$oprId, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
    }

    /*
     * Api online recharge "aporec"
     */
    function aporecBalance(){
        $vendor_id = 63;
        $url = APOREC_BAL_URL;
        $request_param = array('apiid'=>APOREC_USERID, 'apiname'=>APOREC_USER_NAME, 'apipass'=>APOREC_PASSWORD);

        $out = $this->General->aporecApi($url, $request_param);

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aporec.txt", date('Y-m-d H:i:s') . ":Balance Check: " . json_encode($out));

        return array('balance'=>$out);
    }

    /*
     * aporec prepaid mobile recharges
     */
    function aporecMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }
//        if($prodId == '2' && $amount < 50){
//            return array('status'=>'failure', 'code'=>'29', 'description'=>$this->Shop->errors(29), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'29', 'vendor_response'=>'Cannot do recharge of less than Rs50');
//        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['aporec'];
        $vendor = 63;

        $url = APOREC_RECHARGE_URL;
        // --- request parameter
        $request_param = array('apiid'=>APOREC_USERID, 'apiname'=>APOREC_USER_NAME, 'apipass'=>APOREC_PASSWORD, 'reqid'=>$transId, 'custno'=>$mobileNo, 'amount'=>$amount, 'opcode'=>$provider, 'storeid'=>'');

        $out = $this->General->aporecApi($url, $request_param);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aporec.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out));

        if(isset($out['ResponseCode']) && $out['ResponseCode'] == 1){
            $status = 'FAILED';
            $description = isset($out['Status']) ? trim($out['Status']) : (isset($out['Reason']) ? trim($out['Reason']) : "");
        }
        else{
            $status = trim($out['ResponseText']);
            $description = trim($out['ResponseText']);
            if($description == 'NA') $description = "Request accepted";
            $txnId = trim($out['TXNID']);
            $opr_id = trim($out['OPID']);
        }

        if($status == 'FAILED'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId ,'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$description);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId,'operator_id'=>$opr_id, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$description);
        }
    }

    /*
     * api online recharges dth
     */
    function aporecDthRecharge($transId, $params, $prodId){
        $mobileNo = $params['subId'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['aporec'];
        $vendor = 63;

        $url = APOREC_RECHARGE_URL;
        // --- request parameter
        $request_param = array('apiid'=>APOREC_USERID, 'apiname'=>APOREC_USER_NAME, 'apipass'=>APOREC_PASSWORD, 'reqid'=>$transId, 'custno'=>$mobileNo, 'amount'=>$amount, 'opcode'=>$provider, 'storeid'=>'');

        $out = $this->General->aporecApi($url, $request_param);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aporec.txt", "*Dth Recharge*: Input=> $transId<br/>" . json_encode($out));

        if(isset($out['ResponseCode']) && $out['ResponseCode'] == 1){
            $status = 'FAILED';
            $description = isset($out['Status']) ? trim($out['Status']) : (isset($out['Reason']) ? trim($out['Reason']) : "");
        }
        else{
            $status = trim($out['ResponseText']);
            $description = trim($out['ResponseText']);
            if($description == 'NA') $description = "Request accepted";
            $txnId = trim($out['TXNID']);
            $opr_id = trim($out['OPID']);
        }

        if($status == 'FAILED'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId,'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$description);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId,'operator_id'=>$opr_id, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$description);
        }
    }

    /*
     * hitech tranStatus
     */
    function hitechTranStatus($transId, $params, $prodId){
        $url = HITECH_TRANSL_URL;
        // --- request parameter
        $request_param = array('uid'=>HITECH_USERID, 'pass'=>HITECH_PASSWORD, 'mno'=>HITECH_MNO, 'msg'=>"cr$transId");

        $out = $this->General->hitechrecApi($url, $request_param);
        $operator_id = $vendor_id = "";
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/hitech.txt", date('Y-m-d H:i:s') . ":hitechTranStatus: " . json_encode($out));

        $description = $out['output']['NODES']['MESSAGE'];

        $status = (explode(" ", $out['output']['NODES']['MESSAGE']));

        if(in_array(strtolower($status[3]), array('success', 'manually sucess'))){
            $ret = array('status'=>'success', 'status-code'=>$status[3], 'description'=>$description, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else if(in_array(strtolower($status[3]), array('failed', 'manually failed'))){
            $ret = array('status'=>'failure', 'status-code'=>$status[3], 'description'=>$description, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>$status[3], 'description'=>$description, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }

        return $ret;
    }

    /*
     * Api online recharge api Trans Status
     */
    function aporecTranStatus($transId, $date = null, $refId = null){
        $url = APOREC_TRANS_URL;

        $request_param = array('apiid'=>APOREC_USERID, 'apiname'=>APOREC_USER_NAME, 'apipass'=>APOREC_PASSWORD, 'clientid'=>$transId);

        $out = $this->General->aporecApi($url, $request_param);

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aporec.txt", date('Y-m-d H:i:s') . ":aporecTranStatus: " . json_encode($out));

        $status = isset($out['Status']) ? strtoupper(trim($out['Status'])) : "";
        $operator_id = isset($out['OPID']) ? trim($out['OPID']) : "";
        $vendor_id = isset($out['trans_id']) ? trim($out['trans_id']) : "";

        $description = "";

        if($status == 'SUCCESS'){
            $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$description, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else if(in_array($status, array('FAILED', 'REFUNDED')) || strpos(strtolower($status), 'client id/data not available') !== false){
            $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$description, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$description, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }

        return $ret;
    }

    function gitechMobRechargeOld($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);
        $_POST['usecurl'] = true;
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['gitech'];
        $item_desc = $this->mapping['mobRecharge'][$params['operator']]['operator'];
        $vendor = 35;

        $newtransId = $transId . $transId . $transId;
        $request = "<MobileBookingRequest>
        <UsertrackId>$newtransId</UsertrackId>
        <Itemid>$provider</Itemid>
        <ItemDesc>$item_desc</ItemDesc>
        <MobileNo>$mobileNo</MobileNo>
        <Amount>$amount</Amount>
        </MobileBookingRequest>";
        $out = $this->General->gitechApi("MOBILEBOOKINGDETAILS", $request);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/gitech.txt", "*Mob Recharge*: Input=> $transId<br/> request $request " . json_encode($out));

        $status = trim($out['MobileBookingResponse']['Status']);

        if($status == '1'){
            $description = json_encode(trim($out['MobileBookingResponse']['Remarks']));
            $txnId = trim($out['MobileBookingResponse']['TransNo']);

            if(empty($description)) $description = 'success';
            return array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$txnId, 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'13', 'vendor_response'=>$description);
        }
        else if($status == '0'){
            $description = json_encode(trim($out['MobileBookingResponse']['Error']['Remarks']));
            $txnId = "";

            if($description == '"Your transaction status is not known to us. Please check with our call center (timeout)"'){
                $status = 'pending';
            }
            else{
                $status = 'failure';
            }

            return array('status'=>$status, 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$description);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
    }

    function gitechMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);
        $url = GITECH_RECHARGE_URL;
        $_POST['usecurl'] = true;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $newtransId = $transId . $transId . $transId;
        $opr_code = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['gitech'];
        $vendor = 35;

        $request = array('Authentication'=>array('LoginId'=>GITECH_LOGINID,'Password'=>GITECH_PASSWORD),'UserTrackId'=>$newtransId,'RechargeInput'=>array('OperatorCode'=>$opr_code,'MobileNumber'=>$mobileNo,'Amount'=>$amount));
        $out = $this->General->gitechApi($url, $request);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/gitech.txt", "*Mob Recharge*: Input=> $transId<br/> request :: ". json_encode($request) ."response :: " . $out);
        $out = json_decode($out,TRUE);
        $status = $out['ResponseStatus'];
        $txnId = "";

        if($status == '1'){
            $description = json_encode($out['RechargeOutput']);
            $txnId = $out['RechargeOutput']['ReferenceNumber'];

            if(empty($description)) $description = 'success';
            return array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$txnId, 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'13', 'vendor_response'=>$description);
        }
        else if($status == '0'){
            $description = $out['FailureRemarks'];
            $txnId = "";

            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$description);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
    }

    /*
     * GI-tech Dth recharge
     *
     */
    function gitechDthRechargeOld($transId, $params, $prodId){
        $mobileNo = $params['subId'];
        $amount = intval($params['amount']);
        $_POST['usecurl'] = true;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['gitech'];
        $item_desc = $this->mapping['dthRecharge'][$params['operator']]['operator'];
        $vendor = 35;

        $newtransId = $transId . $transId . $transId;
        $request = "<MobileBookingRequest>
        <UsertrackId>$newtransId</UsertrackId>
        <Itemid>$provider</Itemid>
        <ItemDesc>$item_desc</ItemDesc>
        <MobileNo>$mobileNo</MobileNo>
        <Amount>$amount</Amount>
        </MobileBookingRequest>";
        $out = $this->General->gitechApi("MOBILEBOOKINGDETAILS", $request);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/gitech.txt", "*Dth Recharge*: Input=> $transId<br/> request $request " . json_encode($out));

        $status = trim($out['MobileBookingResponse']['Status']);

        if($status == '1'){
            $description = json_encode(trim($out['MobileBookingResponse']['Remarks']));
            $txnId = trim($out['MobileBookingResponse']['TransNo']);

            if(empty($description)) $description = 'success';
            return array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$txnId, 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'13', 'vendor_response'=>$description);
        }
        else if($status == '0'){
            $description = json_encode(trim($out['MobileBookingResponse']['Error']['Remarks']));
            $txnId = "";
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$description);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
    }
    function gitechDthRecharge($transId, $params, $prodId){
        $mobileNo = $params['subId'];
        $amount = intval($params['amount']);
        $url = GITECH_RECHARGE_URL;
//        $url = GITECH_RECHARGE_URL.'/GetRechargeDone';
        $_POST['usecurl'] = true;

        $newtransId = $transId . $transId . $transId;
        $opr_code = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['gitech'];

        $request = array('Authentication'=>array('LoginId'=>GITECH_LOGINID,'Password'=>GITECH_PASSWORD),'UserTrackId'=>$newtransId,'RechargeInput'=>array('OperatorCode'=>$opr_code,'MobileNumber'=>$mobileNo,'Amount'=>$amount));
        $out = $this->General->gitechApi($url, $request);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/gitech.txt", "*Mob Recharge*: Input=> $transId<br/> request :: ".json_encode($request). "response :: " . $out);
        $out = json_decode($out,TRUE);
        $status = $out['ResponseStatus'];
        $txnId = "";
        $opr_id = "";

        if($status == '1'){
            $description = json_encode($out['RechargeOutput']);
            $txnId = $out['RechargeOutput']['ReferenceNumber'];
            $opr_id = $out['RechargeOutput']['OperatorTransactionId'];

            if(empty($description)) $description = 'success';
            return array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$txnId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$description);
        }
        else if($status == '0'){
            $description = $out['FailureRemarks'];

            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$description);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
    }

    /*
     *
     * GI TECH BILLING
     */
    function gitechBillPaymentOld($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);
        $_POST['usecurl'] = true;
        $provider = $this->mapping['billPayment'][$params['operator']][$params['type']]['gitech'];
        $item_desc = $this->mapping['billPayment'][$params['operator']]['operator'];
        $vendor = 35;

        $newtransId = $transId . $transId . $transId;
        $request = "<BillBookingRequest>
        <UsertrackId>$newtransId</UsertrackId>
        <Itemid>$provider</Itemid>
        <ItemDesc>$item_desc</ItemDesc>
        <MobileNo>$mobileNo</MobileNo>
        <Amount>$amount</Amount>
        </BillBookingRequest>";
        $out = $this->General->gitechApi("BILLPAYMENTBOOKINGDETAILS", $request);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/gitech.txt", "*Bill Payment*: Input=> $transId<br/> request :: $request " . json_encode($out));

        $status = trim($out['BillBookingResponse']['Status']);

        if($status == '1'){
            $description = json_encode(trim($out['BillBookingResponse']['Remarks']));
            $txnId = trim($out['BillBookingResponse']['TransNo']);

            if(empty($description)) $description = 'success';
            return array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$txnId, 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>13, 'vendor_response'=>$description);
        }
        else if($status == '0'){
            $description = json_encode(trim($out['BillBookingResponse']['Error']['Remarks']));
            $txnId = "";
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>30, 'vendor_response'=>$description);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>15, 'vendor_response'=>$out);
        }
    }

    function gitechBillPayment($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);
        $url = GITECH_BILLPAYMENT_URL;
//        $url = GITECH_RECHARGE_URL.'/GetBillPaymentDone';
        $_POST['usecurl'] = true;

        $newtransId = $transId . $transId . $transId;
        $opr_code = $this->mapping['billPayment'][$params['operator']][$params['type']]['gitech'];
        $vendor = 35;

        $request = array('Authentication'=>array('LoginId'=>GITECH_LOGINID,'Password'=>GITECH_PASSWORD),'UserTrackId'=>$newtransId,'BillPaymentInput'=>array('OperatorCode'=>$opr_code,'MobileNumber'=>$mobileNo,'Amount'=>$amount,'OtherDetails'=>''));
        $out = $this->General->gitechApi($url, $request);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/gitech.txt", "*Bill Payment*: Input=> $transId<br/> request :: ". json_encode($request). "response :: " . $out);
        $out = json_decode($out,TRUE);
        $status = $out['ResponseStatus'];
        $txnId = "";
        $opr_id = "";

        if($status == '1'){
            $description = json_encode($out['BillPaymentOutput']);
            $txnId = $out['BillPaymentOutput']['ReferenceNumber'];
            $opr_id = $out['BillPaymentOutput']['OperatorTransactionId'];

            if(empty($description)) $description = 'success';
            return array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$txnId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>13, 'vendor_response'=>$description);
        }
        else if($status == '0'){
            $description = $out['FailureRemarks'];
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>30, 'vendor_response'=>$description);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>15, 'vendor_response'=>$out);
        }
    }

    /*
     * rkit DTH intergation
     *
     */
    function rkitDthRecharge($transId, $params, $prodId){
        $mobileNo = $params['subId'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['rkit'];
        $vendor = 34;
        $rcType = 'NORMAL';

        $url = RKIT_RECHARGE_URL;
        $out = $this->General->rkitApi($url, array('USERID'=>RKIT_USER, 'PASSWORD'=>RKIT_AUTH, 'SUBSCRIBER'=>$mobileNo, 'AMOUNT'=>$amount, 'TRANNO'=>$transId, 'RECTYPE'=>$rcType, 'OPERATOR'=>$provider));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rkit.txt", "*Dth Recharge*: Input=> $transId<br/>$out");

        // 1. SUCCESS# RECHARGE POSTED # TRANREFNO #98888
        // 3. ERROR# INVALID MOBILE ENTRY #//TRANNO:9836-STATUS:SUCCESS
        // ERRCODE:104-OPERATOR IS NOT AVAILABLE

        $txnId = "";

        if($out['NODE']['STATUS'] == 'FAILED'){
            $description = trim($out['NODE']['STATUS']);
            $txnId = $out['NODE']['TXNID'];
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$description);
        }
        else{
            $txnId = $out['NODE']['TXNID'];
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
    }

    /*
     * joinrec Dth
     */
    function joinrecDthRecharge($transId, $params, $prodId){
        $mobileNo = $params['subId'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['joinrec'];
        $vendor = 48;
        $rcType = '';

        $url = JOINREC_RECHARGE_URL;
        $out = $this->General->joinrecApi($url, array('reseller_id'=>JOINREC_ID, 'reseller_pass'=>JOINREC_PWD, 'mobilenumber'=>$mobileNo, 'denomination'=>$amount, 'meroid'=>$transId, 'voucher'=>$rcType, 'operatorid'=>$provider, 'circleid'=>'*'));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/joinrec.txt", "*Dth Recharge*: Input=> $transId<br/>" . json_encode($out));

        if(isset($out['Data']['Error'])){
            $status = 'FAILED';
            $description = trim($out['Data']['Error']);
        }
        else{
            $status = trim($out['Data']['Status']);
            $description = trim($out['Data']['Description']);
            if($description == 'NA') $description = "Request accepted";
            $txnId = trim($out['Data']['OrderId']);
        }

        if($status == 'FAILED'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
    }

    function mypayDthRecharge($transId, $params, $prodId){
        $mobileNo = $params['subId'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['mypay'];
        $vendor = 57;
        $rcType = 3;

        $url = MYPAYURL;
        $pwd = MYPAYPASSWD;

        $dt_time = date('m.d.Y H:i:s');

        // pwd|mob|amt|opr|type|datetime
        $req_str = $pwd . "|" . $mobileNo . "|" . $amount . "|" . $provider . "|" . $rcType . "|" . $dt_time . "|" . $transId;

        $out = $this->General->mypayApi($url, array('_prcsr'=>MYPAYUSER, '_urlenc'=>$req_str, '_encuse'=>0));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/mypay.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out));

        if(isset($out['_ApiResponse']['statusCode']) && strtolower($out['_ApiResponse']['statusCode']) != "10008"){
            $status = 'FAILED';
            $description = trim($out['_ApiResponse']['statusDescription']);
        }
        else{
            $status = trim($out['_ApiResponse']['statusCode']);
            $description = isset($out['_ApiResponse']['statusDescription']) ? trim($out['_ApiResponse']['statusDescription']) : "NA";
            if($description == 'NA') $description = "Request accepted";
            $txnId = trim($out['_ApiResponse']['requestID']);
        }

        if($status == 'FAILED'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$description);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$description);
        }
    }

    function paytDthRecharge($transId, $params, $prodId = null){
        $mobileNo = $params['subId'];
        $type = 1;
        $operator = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['payt'];

        if($prodId == 18 && $params['amount'] < 250){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Cannot do less than Rs250 recharge');
        }

        $message = "$operator|0|$mobileNo|" . $params['amount'] . "|$type";
        $date = date('YmdHis');
        $terminal = PAYT_USER_CODE;
        $sha = strtoupper(sha1($terminal . $transId . $message . $date . PAYT_PASSWORD));
        $message = urlencode($message);
        $sha = urlencode($sha);
        $content = "OperationType=1&TerminalId=$terminal&TransactionId=$transId&DateTimeStamp=$date&Message=$message&Hash=$sha";

        $Rec_Data = $this->paytConnection($content);
        if( ! $Rec_Data['success']){
            if($Rec_Data['timeout']){
                return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $Rec_Data = $Rec_Data['output'];
        $response = explode("|", $Rec_Data);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/payt.txt", "*Dth Recharge*: Input=> $transId<br/>" . json_encode($response));

        if(count($response) == 6 && $transId == trim($response[1])){
            $ref_id = trim($response[2]);
            $status = trim($response[0]);
            $opr_id = trim($response[4]);
            $err_code = trim($response[3]);
            if($err_code == 'NA') $opr_id = "";
            if($opr_id == 'NA') $opr_id = "";
            $desc = trim($response[5]);

            if($status == '0' && $err_code != '8'){ // success
                return array('status'=>'success', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>$ref_id, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$desc);
            }
            else if($status == '1' || $err_code == '8'){ // under process
                return array('status'=>'pending', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>$ref_id, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$desc);
            }
            else if($status == '2'){ // failure
                return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$ref_id, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'14', 'vendor_response'=>"Error code: $err_code, " . $desc);
            }
        }
        else{
            $data = $this->paytTranStatus($transId);

            if($data['status'] == 'success'){
                return array('status'=>'success', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>$data['ref_id'], 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'13', 'vendor_response'=>$data['description']);
            }
            else if($data['status'] == 'pending'){
                return array('status'=>'pending', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>$data['ref_id'], 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$data['description']);
            }
            else if($data['status'] == 'failure'){
                return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$data['ref_id'], 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>$data['description']);
            }
        }
    }

    function modemDthRecharge($transId, $params, $prodId, $vendor = 4, $shortForm = 'modem'){
        $mobileNo = $params['mobileNumber'];

        $adm = "query=recharge&oprId=$prodId&mobile=$mobileNo&amount=" . $params['amount'] . "&param=" . $params['subId'] . "&type=1&transId=$transId";

        try{
            $Rec_Data = $this->Shop->modemRequest($adm, $vendor);
            if($Rec_Data['status'] == 'failure'){
                return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'36', 'vendor_response'=>$Rec_Data['error']);
            }
            else{
                $Rec_Data = $Rec_Data['data'];
            }
        }
        catch(Exception $e){
            return array('status'=>'pending', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'10', 'vendor_response'=>'Transaction gone in pending due to some exception');
        }

        $product = $this->mapping['dthRecharge'][$params['operator']]['operator'];

        if(strpos($Rec_Data, 'Error') !== false){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'36', 'vendor_response'=>$Rec_Data);
        }
        else if($Rec_Data == "1"){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'30');
        }
        else if($Rec_Data == "2"){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'40');
        }
        else if($Rec_Data == "3"){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'41');
        }
        else{
            return array('status'=>'pending', 'code'=>'31', 'description'=>$this->Shop->errors(31), 'tranId'=>$Rec_Data, 'pinRefNo'=>'', 'internal_error_code'=>'15');
        }
    }

    function cpDthRecharge($transId, $params, $prodId){
        $sec = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pub_keys/' . CYBER_SECKEY);
        $pub = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pub_keys/' . CYBER_PUBKEY);

        //$sec = defined('CYBER_PUBKEY_STR') ? CYBER_SECKEY_STR : $sec;
        //$pub = defined('CYBER_PUBKEY_STR') ? CYBER_PUBKEY_STR : $pub;

        $extra = "ACCOUNT=\r\n";
        if($prodId == '16'){
            $dbObj = ClassRegistry::init('Slaves');
            $store_data = $dbObj->query("SELECT store_code FROM cp_retailers WHERE service_id='DTH' AND active_flag = 1 ORDER BY RAND() LIMIT 1");
            if( ! empty($store_data)){
                $extra .= "TERM_ID=" . trim($store_data[0]['cp_retailers']['store_code']) . "\r\n";
            }
            else{
                return array('status'=>'failure', 'code'=>'27', 'description'=>$this->Shop->errors(27), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'No Retailer mapped in cyberplat');
            }
        }

        $subId = $params['subId'];
        $amount = $params['amount'];

        $verify_url = $this->createCpUrl($prodId, 'cpv');
        $data = "SD=" . CYBER_SD . "\r\nAP=" . CYBER_AP . "\r\nOP=" . CYBER_OP . "\r\nSESSION=$transId\r\nNUMBER=$subId\r\n" . $extra . "AMOUNT=" . floatval($amount) . "\r\nAMOUNT_ALL=$amount\r\nCOMMENT=\r\n";
        $res = ipriv_sign($data, $sec, CYBER_PASSWORD);

        $out = $this->cpConnect($verify_url, array('inputmessage'=>$res[1]));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];

        $res = ipriv_verify($out, $pub);
        $result = $this->cpArray($res);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cp.txt", "*Dth Recharge*: $verify_url, Input=> " . $data . "\n" . json_encode($result));

        if(isset($result['RESULT']) && $result['RESULT'] == 0 && empty($result['ERROR'])){
            if(isset($result['AUTHCODE'])) $operator_id = $result['AUTHCODE'];

            $live_url = $this->createCpUrl($prodId, 'cpl');
            $data = "SD=" . CYBER_SD . "\r\nAP=" . CYBER_AP . "\r\nOP=" . CYBER_OP . "\r\nSESSION=$transId\r\nNUMBER=$subId\r\n" . $extra . "AMOUNT=" . floatval($amount) . "\r\nPAY_TOOL=0\r\n";
            $res = ipriv_sign($data, $sec, CYBER_PASSWORD);

            $out = $this->cpConnect($live_url, array('inputmessage'=>$res[1]));
            if( ! $out['success']){
                if($out['timeout']){
                    return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
                }
                else{
                    return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>'Request timed out while recharging');
                }
            }
            else{
                $out = $out['output'];
            }

            $res = ipriv_verify($out, $pub);
            $result = $this->cpArray($res);

            $vendorTransId = (isset($result['TRANSID'])) ? $result['TRANSID'] : 0;
            $opr_id = (isset($result['AUTHCODE'])) ? $result['AUTHCODE'] : 0;
            $trans_status = (isset($result['TRNXSTATUS'])) ? $result['TRNXSTATUS'] : 3;

            if($trans_status != 7){
                $status = $this->cpTranStatus($transId, null, null, $prodId);
            }
            else{
                $status['status'] = 'success';
                $status['vendor_id'] = $vendorTransId;
            }

            $error = isset($status['status-code']) ? $status['status-code'] : $result['ERROR'];

            $errorCode = $this->Shop->errorCodeMapping(8, $error);

            if($status['status'] == 'error' || $status['status'] == 'inprocess' || $status['status'] == 'incomplete'){ // in process
                return array('status'=>'pending', 'code'=>$errorCode, 'description'=>$this->Shop->errors($errorCode), 'tranId'=>$status['vendor_id'], 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15',
                        'vendor_response'=>$status['status'] . "::" . $status['description'] . "(" . $status['status-code'] . ")");
            }
            else if($status['status'] == 'failure'){
                return array('status'=>'failure', 'code'=>$errorCode, 'description'=>$this->Shop->errors($errorCode), 'tranId'=>$status['vendor_id'], 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'14', 'vendor_response'=>$status['description'] . "(" . $status['status-code'] . ")");
            }
            else if($status['status'] == 'success'){
                return array('status'=>'success', 'code'=>$errorCode, 'description'=>$this->Shop->errors($errorCode), 'tranId'=>$status['vendor_id'], 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>'');
            }
        }
        else{
            $error = $result['ERROR'];
            $errorCode = $this->Shop->errorCodeMapping(8, $error);
            $err = "Error code: " . $result['ERROR'] . "," . $this->cp_errs[$result['ERROR']];
            return array('status'=>'failure', 'code'=>$errorCode, 'description'=>$this->Shop->errors($errorCode), 'tranId'=>'', 'internal_error_code'=>'42', 'vendor_response'=>$err);
        }
    }

    function magicDthRecharge($transId, $params, $prodId){
        $subid = $params['subId'];
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['magic'];
        $vendor = 24;

        $url = MAGIC_RECHARGE_URL;
        $out = $this->General->magicApi($url, array('uid'=>MAGIC_USERNAME, 'pwd'=>MAGIC_PASSWD, 'rcode'=>$provider, 'mobileno'=>$subid, 'amt'=>$amount, 'transid'=>$transId));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/magic.txt", "*DTH Recharge*: Input=> $transId<br/>$out");

        // 297499#Pending#ACCEPT
        $status = trim($out);
        $txnId = time();
        $txnId .= rand(100, 999);

        $status_array = array('1200'=>'Request Accepted', '1201'=>'Invalid Login', '1202'=>'Invalid Mobile Number', '1203'=>'Invalid Amount', '1204'=>'Transaction ID missing', '1205'=>'Operator not found', '1206'=>'Permission Required', '1207'=>'Balance Limit', '1208'=>'Low Balance',
                '1209'=>'Duplicate Request', '1210'=>'Request not accepted', '1211'=>'Recharge server not connected', '1212'=>'Authentication Failed');
        $out = $status_array[$status];

        if(empty($status) || $status == '1200'){
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
        else{
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
    }

    function rioDthRecharge($transId, $params, $prodId){
        $subid = $params['subId'];
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['rio'];
        $vendor = 36;

        $url = RIO_RECHARGE_URL;
        $out = $this->General->rioApi($url, array('uid'=>RIO_USERNAME, 'pwd'=>RIO_PASSWD, 'rcode'=>$provider, 'mobileno'=>$subid, 'amt'=>$amount, 'transid'=>$transId));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rio.txt", "*DTH Recharge*: Input=> $transId<br/>$out");

        // 297499#Pending#ACCEPT
        $status = trim($out);
        $txnId = time();
        $txnId .= rand(100, 999);

        $status_array = array('1200'=>'Request Accepted', '1201'=>'Invalid Login', '1202'=>'Invalid Mobile Number', '1203'=>'Invalid Amount', '1204'=>'Transaction ID missing', '1205'=>'Operator not found', '1206'=>'Permission Required', '1207'=>'Balance Limit', '1208'=>'Low Balance',
                '1209'=>'Duplicate Request', '1210'=>'Request not accepted', '1211'=>'Recharge server not connected', '1212'=>'Authentication Failed');
        $out = $status_array[$status];

        if(empty($status) || $status == '1200'){
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'14', 'vendor_response'=>$out);
        }
        else{
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
    }

    function rio2DthRecharge($transId, $params, $prodId){
        $subid = $params['subId'];
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['rio2'];
        $vendor = 62;

        $url = RIO2_RECHARGE_URL;
        $out = $this->General->rioApi($url, array('uid'=>RIO2_USERNAME, 'pwd'=>RIO2_PASSWD, 'rcode'=>$provider, 'mobileno'=>$subid, 'amt'=>$amount, 'transid'=>$transId));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rio2.txt", "*DTH Recharge*: Input=> $transId<br/>$out");

        // 297499#Pending#ACCEPT
        $status = trim($out);
        $txnId = time();
        $txnId .= rand(100, 999);

        $status_array = array('1200'=>'Request Accepted', '1201'=>'Invalid Login', '1202'=>'Invalid Mobile Number', '1203'=>'Invalid Amount', '1204'=>'Transaction ID missing', '1205'=>'Operator not found', '1206'=>'Permission Required', '1207'=>'Balance Limit', '1208'=>'Low Balance',
                '1209'=>'Duplicate Request', '1210'=>'Request not accepted', '1211'=>'Recharge server not connected', '1212'=>'Authentication Failed');
        $out = $status_array[$status];

        if(empty($status) || $status == '1200'){
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
        else{
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
    }

    function cpBillPayment($transId, $params, $prodId){
        $sec = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pub_keys/' . CYBER_SECKEY);
        $pub = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pub_keys/' . CYBER_PUBKEY);

        //$sec = defined('CYBER_PUBKEY_STR') ? CYBER_SECKEY_STR : $sec;
        //$pub = defined('CYBER_PUBKEY_STR') ? CYBER_PUBKEY_STR : $pub;

        $extra = "ACCOUNT=\r\n";
        if($prodId == '42'){
            $dbObj = ClassRegistry::init('Slaves');
            $store_data = $dbObj->query("SELECT store_code FROM cp_retailers WHERE service_id='Postpaid' AND active_flag = 1 ORDER BY RAND() LIMIT 1");
            if( ! empty($store_data)){
                $extra .= "TERM_ID=" . trim($store_data[0]['cp_retailers']['store_code']) . "\r\n";
            }
            else{
                return array('status'=>'failure', 'code'=>'25', 'description'=>$this->Shop->errors(25), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'25', 'vendor_response'=>'No retailer mapped at cyberplat');
            }
        }

        $mobileNo = $params['mobileNumber'];
        $amount = $params['amount'];
        $service_id = 4;
        $vendor = 8;

        $verify_url = $this->createCpUrl($prodId, 'cpv');
        $data = "SD=" . CYBER_SD . "\r\nAP=" . CYBER_AP . "\r\nOP=" . CYBER_OP . "\r\nSESSION=$transId\r\nNUMBER=$mobileNo\r\n" . $extra . "AMOUNT=" . floatval($amount) . "\r\nAMOUNT_ALL=$amount\r\nCOMMENT=\r\n";
        $res = ipriv_sign($data, $sec, CYBER_PASSWORD);
        $out = $this->cpConnect($verify_url, array('inputmessage'=>$res[1]));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = $out['output'];

        $res = ipriv_verify($out, $pub);
        $result = $this->cpArray($res);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cp.txt", "*Bill Payment*: $verify_url, Input=> " . $data . "\n" . json_encode($result));

        if(isset($result['RESULT']) && $result['RESULT'] == 0 && empty($result['ERROR'])){
            if(isset($result['AUTHCODE'])) $operator_id = $result['AUTHCODE'];

            $live_url = $this->createCpUrl($prodId, 'cpl');
            $data = "SD=" . CYBER_SD . "\r\nAP=" . CYBER_AP . "\r\nOP=" . CYBER_OP . "\r\nSESSION=$transId\r\nNUMBER=$mobileNo\r\n" . $extra . "AMOUNT=" . floatval($amount) . "\r\nPAY_TOOL=0\r\n";
            $res = ipriv_sign($data, $sec, CYBER_PASSWORD);

            $out = $this->cpConnect($live_url, array('inputmessage'=>$res[1]));
            if( ! $out['success']){
                if($out['timeout']){
                    return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
                }
                else{
                    return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>'Request timed out while recharging');
                }
            }
            else{
                $out = $out['output'];
            }

            $res = ipriv_verify($out, $pub);
            $result = $this->cpArray($res);

            $vendorTransId = (isset($result['TRANSID'])) ? $result['TRANSID'] : 0;
            $opr_id = (isset($result['AUTHCODE'])) ? $result['AUTHCODE'] : 0;
            $trans_status = (isset($result['TRNXSTATUS'])) ? $result['TRNXSTATUS'] : 3;

            if($trans_status != 7){
                $status = $this->cpTranStatus($transId, null, null, $prodId);
            }
            else{
                $status['status'] = 'success';
                $status['vendor_id'] = $vendorTransId;
            }

            $error = $result['ERROR'];
            $errorCode = $this->Shop->errorCodeMapping(8, $error);

            if($status['status'] == 'error' || $status['status'] == 'inprocess' || $status['status'] == 'incomplete'){ // in process
                return array('status'=>'pending', 'code'=>$errorCode, 'description'=>$this->Shop->errors($errorCode), 'tranId'=>$status['vendor_id'], 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15',
                        'vendor_response'=>$status['status'] . "::" . $status['description'] . "(" . $status['status-code'] . ")");
            }
            else if($status['status'] == 'failure'){
                return array('status'=>'failure', 'code'=>$errorCode, 'description'=>$this->Shop->errors($errorCode), 'tranId'=>$status['vendor_id'], 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'14', 'vendor_response'=>$status['description'] . "(" . $status['status-code'] . ")");
            }
            else if($status['status'] == 'success'){
                return array('status'=>'success', 'code'=>$errorCode, 'description'=>$this->Shop->errors($errorCode), 'tranId'=>$status['vendor_id'], 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13');
            }
        }
        else{
            $error = $result['ERROR'];
            $errorCode = $this->Shop->errorCodeMapping(8, $error);
            $err = "Error code: " . $result['ERROR'] . "," . $this->cp_errs[$result['ERROR']];

            return array('status'=>'failure', 'code'=>$errorCode, 'description'=>$this->Shop->errors($errorCode), 'tranId'=>'', 'internal_error_code'=>'42', 'vendor_response'=>$err);
        }
    }

    function uniBillPayment($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['billPayment'][$params['operator']][$params['type']]['uni'];
        $vendor = 19;

        $url = UNI_RECHARGE_URL;
        $out = $this->General->uniApi($url, array('rcm'=>$mobileNo, 'rca'=>$amount, 'crqid'=>$transId, 'cro'=>$provider));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/uni.txt", "*Bill Payment*: Input=> " . json_encode(array('rcm'=>$mobileNo, 'rca'=>$amount, 'crqid'=>$transId, 'cro'=>$provider)) . "\n" . json_encode($params) . "\n$out");

        // ///0|Transaction Successful | 12121212121 | 121139144523204238;
        $res = explode("|", $out);

        if(count($res) == 2 || trim($res[0]) == '405'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
            $code = trim($res['0']);
            $msg = trim($res['1']);
            $txnId = trim($res['3']);
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>trim($txnId), 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$msg);
        }
    }

    /*
     * joinrec Bill payment
     */
    function joinrecBillPayment($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['billPayment'][$params['operator']][$params['type']]['joinrec'];
        $vendor = 48;
        $rcType = '';

        $url = JOINREC_RECHARGE_URL;
        $out = $this->General->joinrecApi($url, array('reseller_id'=>JOINREC_ID, 'reseller_pass'=>JOINREC_PWD, 'mobilenumber'=>$mobileNo, 'denomination'=>$amount, 'meroid'=>$transId, 'voucher'=>$rcType, 'operatorid'=>$provider, 'circleid'=>'*'));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/joinrec.txt", "*Bill Payment*: Input=> $transId<br/>" . json_encode($out));

        if(isset($out['Data']['Error'])){
            $status = 'FAILED';
            $description = trim($out['Data']['Error']);
        }
        else{
            $status = trim($out['Data']['Status']);
            $description = trim($out['Data']['Description']);
            if($description == 'NA') $description = "Request accepted";
            $txnId = trim($out['Data']['OrderId']);
        }

        if($status == 'FAILED'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
    }

    function cpBillData($info){
        if(!empty($info)){
            $billData = urldecode($info);
            $billData = str_replace("> <",":::",$billData);
            $billData = str_replace("<","",$billData);
            $billData = str_replace(">","",$billData);
            $data = explode(":::",$billData);
            $cust_name = '';
            if(isset($data[5])){
                $cust_name_arr = explode('$',$data[5]);
                if(isset($cust_name_arr[1])){
                    $cust_name = $cust_name_arr[1];
                }
            }
            $bill_data_insert= array('customer_name'=>$cust_name,'bill_number'=>(($data[0] == 'NA') ? '' : $data[0]),'bill_date'=>(($data[1] == 'NA') ? '' : date('Y-m-d',strtotime($data[1]))),'due_date'=>(($data[2] == 'NA') ? '' : date('Y-m-d',strtotime($data[2]))),'bill_period'=>$bill_data['bill_period'],'bill_amount'=>(($data[3] == 'NA') ? '0' : $data[3]));
        }
        else {
            $bill_data_insert = array();
        }
        
        return $bill_data_insert;
    }
    
    function cpUtilityBillPayment($transId, $params, $prodId){
        $sec = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pub_keys/' . CYBER_SECKEY);
        $pub = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pub_keys/' . CYBER_PUBKEY);


        $extra = "";
        if(in_array($prodId,$this->param_exceptions)){
            $params['param'] = '';
        }
        if(isset($params['param']) && !empty($params['param'])){
            $extra .= "ACCOUNT=".$params['param']."\r\n";
        }
        if(isset($params['param1']) && !empty($params['param1'])){
            $extra .= "Authenticator3=".$params['param1']."\r\n";
        }

        $number = $params['accountNumber'];
        $amount = $params['amount'];
        $service_id = 6;
        $vendor = 8;

        $verify_url = $this->createCpUrl($prodId, 'cpv');

        if(isset($this->cp_opr_map[$prodId]['bbps_flag']))
        {
            $slaveObj = ClassRegistry::init('Slaves');
            $agent_id = $this->getAgentId($params['retailer_id'],8);
            $location = $slaveObj->query("SELECT rl.latitude,rl.longitude,la.pincode FROM retailers_location rl JOIN locator_area la ON (rl.area_id = la.id) WHERE retailer_id = '{$params['retailer_id']}' ");
            $pin = $location[0]['la']['pincode'];
            $geo_code = $location[0]['rl']['longitude'].",".$location[0]['rl']['latitude'];
            $data = "SD=" . CYBER_SD . "\r\nAP=" . CYBER_AP . "\r\nOP=" . CYBER_OP . "\r\nSESSION=$transId\r\nAgentId=$agent_id\r\nChannel=AGT\r\nfName=\r\nlName=\r\nPanCardNo=NA\r\nAadhar=NA\r\nCardType=NA\r\nEmail=\r\nbenMobile=$number\r\nGeoCode=$geo_code\r\nPin=$pin\r\nTERMINAL_ID=\r\nNUMBER=$number\r\n".$extra."AMOUNT=" . floatval($amount) . "\r\nAMOUNT_ALL=$amount\r\nTERM_ID=\r\n";
        }
        else
        {
            $data = "SD=" . CYBER_SD . "\r\nAP=" . CYBER_AP . "\r\nOP=" . CYBER_OP . "\r\nSESSION=$transId\r\nNUMBER=$number\r\n" . $extra . "AMOUNT=" . floatval($amount) . "\r\nAMOUNT_ALL=$amount\r\nCOMMENT=\r\n";
        }

        $res = ipriv_sign($data, $sec, CYBER_PASSWORD);
        $out = $this->cpConnect($verify_url, array('inputmessage'=>$res[1]));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = $out['output'];

        $res = ipriv_verify($out, $pub);
        $result = $this->cpArray($res);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cp.txt", "*Utility Bill Payment Validation*: $verify_url, Input=> " . json_encode($data) . "Output=>" . json_encode($result));

        if(isset($result['RESULT']) && $result['RESULT'] == 0 && empty($result['ERROR'])){
            if(isset($result['AUTHCODE'])) $operator_id = $result['AUTHCODE'];

            $live_url = $this->createCpUrl($prodId, 'cpl');

            if(isset($this->cp_opr_map[$prodId]['bbps_flag']))
            {
                $data = "SD=" . CYBER_SD . "\r\nAP=" . CYBER_AP . "\r\nOP=" . CYBER_OP . "\r\nSESSION=$transId\r\nAgentId=$agent_id\r\nChannel=AGT\r\nfName=\r\nlName=\r\nPanCardNo=NA\r\nAadhar=NA\r\nCardType=NA\r\nEmail=\r\nbenMobile=$number\r\nGeoCode=$geo_code\r\nPin=$pin\r\nTERMINAL_ID=\r\nNUMBER=$number\r\n".$extra."AMOUNT=" . floatval($amount) . "\r\nAMOUNT_ALL=$amount\r\nTERM_ID=\r\n";
            }
            else
            {
                $data = "SD=" . CYBER_SD . "\r\nAP=" . CYBER_AP . "\r\nOP=" . CYBER_OP . "\r\nSESSION=$transId\r\nNUMBER=$number\r\n" . $extra . "AMOUNT=" . floatval($amount) . "\r\nPAY_TOOL=0\r\n";
            }

            $res = ipriv_sign($data, $sec, CYBER_PASSWORD);

            $out = $this->cpConnect($live_url, array('inputmessage'=>$res[1]));
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cp.txt", "*Utility Bill Payment*: $live_url, Input=> " . json_encode($data) . "Output=>" . json_encode($out));
            if( ! $out['success']){
                if($out['timeout']){
                    return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
                }
                else{
                    return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>'Request timed out while recharging');
                }
            }
            else{
                $out = $out['output'];
            }

            $res = ipriv_verify($out, $pub);
            $result = $this->cpArray($res);
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cp.txt", "*Utility Bill Payment Final Response*: $live_url, Input=> " . json_encode($data) . "Output=>" . json_encode($result));
            $vendorTransId = (isset($result['TRANSID'])) ? $result['TRANSID'] : 0;
            $opr_id = (isset($result['AUTHCODE'])) ? $result['AUTHCODE'] : 0;
            $trans_status = (isset($result['TRNXSTATUS'])) ? $result['TRNXSTATUS'] : 3;

            if($trans_status != 7){
                $status = $this->cpTranStatus($transId, null, null, $prodId);
            }
            else{
                $status['status'] = 'success';
                $status['vendor_id'] = $vendorTransId;
            }
            $error = $result['ERROR'];
            $errorCode = $this->Shop->errorCodeMapping(8, $error);
            if(isset($result['ERRMSG']) && !empty($result['ERRMSG']))
            {
                $description = $result['ERRMSG'];
            }
            else
            {
                $description = $this->Shop->errors($errorCode);
            }
            
            if(isset($this->cp_opr_map[$prodId]['bbps_flag'])){
                $bill_data_insert = $this->cpBillData($result['ADDINFO']);
                $bill_data_insert['amount_paid']=$params['amount'];
                
                $userObj = ClassRegistry::init('User');
                $userObj->query("INSERT INTO bbps_txnid_mapping (fetch_txnid,payment_txnid,cca_id,vendor_id,bill_data) VALUES ('".$status['vendor_id']."','$transId','".$status['vendor_id']."',$vendor,'".addslashes(json_encode($bill_data_insert))."')");
            }

            if($status['status'] == 'error' || $status['status'] == 'inprocess' || $status['status'] == 'incomplete'){ // in process
                return array('status'=>'pending', 'code'=>$errorCode, 'description'=>$description, 'tranId'=>$status['vendor_id'], 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15',
                        'vendor_response'=>$status['status'] . "::" . $status['description'] . "(" . $status['status-code'] . ")");
            }
            else if($status['status'] == 'failure'){
                return array('status'=>'failure', 'code'=>$errorCode, 'description'=>$description, 'tranId'=>$status['vendor_id'], 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'14', 'vendor_response'=>$status['description'] . "(" . $status['status-code'] . ")");
            }
            else if($status['status'] == 'success'){
                return array('status'=>'success', 'code'=>$errorCode, 'description'=>$description, 'tranId'=>$status['vendor_id'], 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13');
            }
        }
        else{
            $error = $result['ERROR'];
            if(isset($result['ERRMSG']) && !empty($result['ERRMSG']))
            {
                $errorCode = $error;
                $description = $result['ERRMSG'];
            }
            else
            {
                $errorCode = $this->Shop->errorCodeMapping(8, $error);
                $description = $this->cp_errs[$result['ERROR']];
            }

            $err = "Error code: " . $result['ERROR'] . "," . $description;

            if(isset($result['ADDINFO'])){
                $err .= ":: Info: " . $result['ADDINFO'];
            }
            if(isset($result['DUEDATE'])){
                $err .= ":: DueDate" . $result['DUEDATE'];
            }
            return array('status'=>'failure', 'code'=>$errorCode, 'description'=>$description, 'tranId'=>'', 'internal_error_code'=>$code, 'vendor_response'=>$err);
        }
    }

    function cpUtilityBillFetch($params, $prodId){
        $transId = rand() . time();
        $sec = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pub_keys/' . CYBER_SECKEY);
        $pub = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pub_keys/' . CYBER_PUBKEY);

        $extra = '';
        if(in_array($prodId,$this->param_exceptions)){
            $params['param'] = '';
        }
        if(isset($params['param']) && !empty($params['param'])){
            $extra .= "ACCOUNT=".$params['param']."\r\n";
        }
        if(isset($params['param1']) && !empty($params['param1'])){
            $extra .= "Authenticator3=".$params['param1']."\r\n";
        }
        $number = $params['accountNumber'];
        $amount = 100;


        $verify_url = $this->createCpUrl($prodId, 'cpv');
        if(isset($this->cp_opr_map[$prodId]['bbps_flag']))
        {
            $slaveObj = ClassRegistry::init('Slaves');
            $agent_id = $this->getAgentId($params['retailer_id'],8);
            $location = $slaveObj->query("SELECT rl.latitude,rl.longitude,la.pincode FROM retailers_location rl JOIN locator_area la ON (rl.area_id = la.id) WHERE retailer_id = '{$params['retailer_id']}' ");
            $pin = $location[0]['la']['pincode'];
            $geo_code = $location[0]['rl']['longitude'].",".$location[0]['rl']['latitude'];
            $data = "SD=" . CYBER_SD . "\r\nAP=" . CYBER_AP . "\r\nOP=" . CYBER_OP . "\r\nSESSION=$transId\r\nAgentId=$agent_id\r\nChannel=AGT\r\nfName=\r\nlName=\r\nPanCardNo=NA\r\nAadhar=NA\r\nCardType=NA\r\nEmail=\r\nbenMobile=$number\r\nGeoCode=$geo_code\r\nPin=$pin\r\nTERMINAL_ID=\r\nNUMBER=$number\r\n".$extra."AMOUNT=" . floatval($amount) . "\r\nAMOUNT_ALL=$amount\r\nTERM_ID=\r\n";
        }
        else
        {
            $data = "SD=" . CYBER_SD . "\r\nAP=" . CYBER_AP . "\r\nOP=" . CYBER_OP . "\r\nSESSION=$transId\r\nNUMBER=$number\r\n".$extra."AMOUNT=" . floatval($amount) . "\r\nCOMMENT=test\r\n";
        }

        $res = ipriv_sign($data, $sec, CYBER_PASSWORD1);
        $out = $this->cpConnect($verify_url, array('inputmessage'=>$res[1]));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>$errorCode, 'description'=>'Not able to connect to server');
            }
        }
        $out = $out['output'];
        $res = ipriv_verify($out, $pub);
        $result = $this->cpArray($res);
//        $result['ADDINFO'] = '1234';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cp.txt", "*Utility Bill Fetch*: $verify_url, Input=> " . json_encode($data) . "Output=>" . json_encode($result));

        if(isset($result['ADDINFO'])){
            $billData = urldecode($result['ADDINFO']);
            $billData = str_replace("> <",":::",$billData);
            $billData = str_replace("<","",$billData);
            $billData = str_replace(">","",$billData);
            $data = explode(":::",$billData);
            if(isset($this->cp_opr_map[$prodId]['bbps_flag'])){
                $mem_data = array('bill_number'=>(($data[0] == 'NA') ? '' : $data[0]),'bill_date'=>(($data[1] == 'NA') ? '' : date('Y-m-d',strtotime($data[1]))),'due_date'=>(($data[2] == 'NA') ? '' : date('Y-m-d',strtotime($data[2]))),'bill_amount'=>(($data[3] == 'NA') ? '0' : $data[3]),'bbps'=>'1','vendor_id'=>8);
                $this->Shop->setMemcache("bbps_".$prodId."_".$params['accountNumber']."_".$_SESSION['Auth']['id']."_8", $mem_data, 60 * 60);
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cp.txt", " SET cp memcache key : bbps_".$prodId."_".$params['accountNumber']."_".$_SESSION['Auth']['id']."_8 Data :". json_encode($mem_data));
            }
            $bill_arr = array('bill_no'=>(($data[0] == 'NA') ? '' : $data[0]),'bill_date'=>(($data[1] == 'NA') ? '' : date('Y-m-d',strtotime($data[1]))),'due_date'=>(($data[2] == 'NA') ? '' : date('Y-m-d',strtotime($data[2]))),'bill_amount'=>(($data[3] == 'NA') ? '0' : $data[3]));
            return array('status'=>'success', 'description'=>$bill_arr);
        }
        else {
            return array('status'=>'failure', 'description'=>'Bill info not found');
        }

    }

    /*
     * Smsdaak Bill Fetch
     */
    function smsdaakUtilityBillFetch($params, $prodId){
        $optional1 = "";
        $optional2 = "";
        $optional3 = "";
        $transId = rand() . time();

        if(in_array($prodId,$this->param_exceptions)){
            $params['param'] = '';
        }
        if(isset($params['param']) && !empty($params['param'])){
            $optional1=$params['param'];
        }
        if(isset($params['param1']) && !empty($params['param1'])){
            $optional2=$params['param1'];
        }

        $accountNo = $params['accountNumber'];
        if($prodId == 88){
            $optional3 = $optional2;
            $optional2 = $optional1;
            $optional1 = "0".substr($accountNo,0,2);
            $accountNo= substr($accountNo,2);
        }

        $operator = isset($params['operator']) ? $params['operator'] : "";
        $type = isset($params['type']) ? $params['type'] : "";
        $amount = 100;

        $provider = $this->mapping['utilityBillPayment'][$prodId][$type]['smsdaak'];
        $url = SMSDAAK_RECHARGE_URL;
        $validation_param = array('token'=>SMSDAAK_TOKEN1, 'spkey'=>$provider, 'agentid'=>$transId, 'account'=>$accountNo, 'amount'=>$amount, 'optional1'=>$optional1, 'optional2'=>$optional2, 'optional3'=>$optional3,'format'=>'xml');
        $validation_param['mode'] = 'VALIDATE';


        $out = $this->General->smsdaakApi($url, $validation_param);
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'description'=>'Not able to connect to server');
            }
        }
        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/smsdaak.txt", "*Utility Bill Fetch request *:" . json_encode($validation_param). "::response:".json_encode($out));

        if(isset($out['xml']['particulars']) && !empty($out['xml']['particulars'])){
            $billData = $out['xml']['particulars'];
            $bill_arr = array('bill_no'=>'','bill_date'=>'','due_date'=>'','bill_amount'=>$billData['dueamount']);
            return array('status'=>'success', 'description'=>$bill_arr);
        }
        else {
            return array('status'=>'failure', 'description'=>'Bill info not found');
        }
    }


    /**
     *
     * @param type $transId
     * @param type $params
     * @param type $prodId
     * @return type
     */
    function smsdaakUtilityBillPayment($transId, $params, $prodId){
        $optional1 = "";
        $optional2 = "";
        $optional3 = "";

        if(in_array($prodId,$this->param_exceptions)){
            $params['param'] = '';
        }
        if(isset($params['param']) && !empty($params['param'])){
            $optional1=$params['param'];
        }
        if(isset($params['param1']) && !empty($params['param1'])){
            $optional2=$params['param1'];
        }

        $account_number = isset($params['accountNumber']) ? $params['accountNumber'] : "";
        if($prodId == 88){
            $optional3 = $optional2;
            $optional2 = $optional1;
            $optional1 = "0".substr($account_number,0,2);
            $account_number= substr($account_number,2);
        }
        $amount = intval(isset($params['amount']) ? $params['amount'] : 0);
        $mobileNo = isset($params['mobileNumber']) ? $params['mobileNumber'] : "";
        $operator = isset($params['operator']) ? $params['operator'] : "";
        $type = isset($params['type']) ? $params['type'] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/smsdaak.txt", "*Utility Bill Params request *:" . json_encode($params));

        $provider = $this->mapping['utilityBillPayment'][$prodId][$type]['smsdaak'];
        $vendor = 58;
        $url = SMSDAAK_RECHARGE_URL;
        // --- request parameter
        $request_param = $validation_param = array('token'=>SMSDAAK_TOKEN, 'spkey'=>$provider, 'agentid'=>$transId, 'account'=>$account_number, 'amount'=>$amount, 'optional1'=>$optional1, 'optional2'=>$optional2,'optional3'=>$optional3,'format'=>'xml');
        // ---validating request
        $validation_param['mode'] = 'VALIDATE';
        $out = $this->General->smsdaakApi($url, $validation_param);
        $service_id = '6';
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>14, 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/smsdaak.txt", "*Utility Bill Payment validation request *: Input=> $transId<br/>" . json_encode($out) . " : input : " . json_encode($validation_param));
        // -----------handle success / failure of validation hit
        if(strtoupper($out['xml']['ipay_errorcode']) == 'TXN'){
            $out = "";
            $out = $this->General->smsdaakApi($url, $request_param);
            if( ! $out['success']){
                if($out['timeout']){
                    return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>14, 'vendor_response'=>'Not able to connect to server');
                }
            }
            $out = $out['output'];
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/smsdaak.txt", "*Utility Bill Payment*: Input=> $transId<br/>" . json_encode($out) . " : input : " . json_encode($request_param));
            $txnId = $out['xml']['ipay_id'];
            $opr_id = isset($out['xml']['status']) ? $out['xml']['opr_id'] : "";
            if(isset($out['xml']['status'])){
                $error =  ! empty($out['xml']['res_code']) ? $out['xml']['res_code'] : "";
                $errCode = $this->Shop->errorCodeMapping($vendor, $error);
                if(strtoupper(trim($out['xml']['status'])) == "SUCCESS"){
                    $description = trim($out['xml']['res_msg']);
                    if(empty($description)) $description = 'success';
                    return array('status'=>'success', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>$txnId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>13, 'vendor_response'=>$description);
                }
                else{
                    return array('status'=>'pending', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>15, 'vendor_response'=>$out);
                }
            }
        }
        // ----------- handle failure of validation and payment hit
        if(isset($out['xml']['ipay_errorcode']) && strtoupper($out['xml']['ipay_errorcode']) != 'TXN'){
            $description = trim($out['xml']['ipay_errordesc']);
            $txnId = "";
            $error =  ! empty($out['xml']['ipay_errorcode']) ? $out['xml']['ipay_errorcode'] : "";
            $errCode = $this->Shop->errorCodeMapping($vendor, $error);
            return array('status'=>'failure', 'code'=>$errCode, 'description'=>$this->Shop->errors($errCode), 'tranId'=>$txnId, 'internal_error_code'=>30, 'vendor_response'=>$description);
        }
    }

    function paytBalance(){
        $date = date('YmdHis');
        $terminal = PAYT_USER_CODE;
        $sha = strtoupper(sha1($terminal . $date . PAYT_PASSWORD));
        $content = "OperationType=3&TerminalId=$terminal&DateTimeStamp=$date&Hash=$sha";

        $Rec_Data = $this->paytConnection($content);
        if( ! $Rec_Data['success']){
            return array('balance'=>'');
        }

        $Rec_Data = $Rec_Data['output'];

        $response = explode("|", $Rec_Data);
        if(trim($response[0]) == 0){
            $balance = trim($response[1]);
            $message = trim($response[2]);
            return array('balance'=>$balance);
        }
        else{
            return array('balance'=>'');
        }
    }

    function modemBalance($date = null, $vendor = 4, $modem_src = true){
        if(empty($date)) $date = date('Y-m-d');
        $adm = "query=balance&date=$date";
        $info = $this->Shop->getVendorInfo($vendor);

        $Rec_Data = false;
        if($date == date('Y-m-d')) $Rec_Data = $this->Shop->getMemcache("balance_$vendor");

        if($Rec_Data === false && $info['show_flag'] == 1){
            if($modem_src){
                $Rec_Data = $this->Shop->modemRequest($adm, $vendor, $info);
                $Rec_Data = $Rec_Data['data'];
            }
            else{
                $this->General->logData("modem_test.txt", "i m finding data in devices_data now");
                $dbObj = ClassRegistry::init('Slaves');
                $data = $dbObj->query("SELECT * FROM `devices_data` WHERE sync_date = '$date' AND vendor_id ='$vendor'");
                $Rec_Data = array();
                foreach($data as $dt){
                    $Rec_Data[] = $dt['devices_data'];
                }
                $Rec_Data = json_encode($Rec_Data);
            }
        }

        if( ! empty($Rec_Data)){
            $Rec_Data = json_decode($Rec_Data, true);
            $time = $this->Shop->getMemcache("balance_timestamp_$vendor" . "_last");
            $ports = $this->Shop->getMemcache("balance_ports_$vendor");
            if($time !== false){
                $Rec_Data['lasttime'] = $time;
            }
            if($ports !== false){
                $Rec_Data['ports'] = $ports;
            }

            return $Rec_Data;
        }
        else
            return null;
    }

    function cpBalance(){
        $sec = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pub_keys/' . CYBER_SECKEY);
        $pub = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pub_keys/' . CYBER_PUBKEY);

        //$sec = defined('CYBER_PUBKEY_STR') ? CYBER_SECKEY_STR : $sec;
        //$pub = defined('CYBER_PUBKEY_STR') ? CYBER_PUBKEY_STR : $pub;

        $url = CYBERP_BAL_URL;

        $transId = "12" . time() . "" . rand(0, 10000);
        $data = "SD=" . CYBER_SD . "\r\nAP=" . CYBER_AP . "\r\nOP=" . CYBER_OP . "\r\nSESSION=$transId\r\n";
        $res = ipriv_sign($data, $sec, CYBER_PASSWORD);
        $out = $this->cpConnect($url, array('inputmessage'=>$res[1]));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = $out['output'];
        $res = ipriv_verify($out, $pub);
        $result = $this->cpArray($res);
        if( ! empty($result)) $this->Shop->setMemcache("balance_8", $result, 24 * 60 * 60);

        $status = '';
        return array('balance'=>$result['REST']);
    }

    function cbzBalance(){
        $url = CBZ_BAL_URL;
        $out = $this->General->cbzApi($url, array('username'=>CBZ_REC_USERNAME, 'password'=>CBZ_REC_PASSWORD, 'key'=>CBZ_REC_KEY, 'cmd'=>'balance'));

        if( ! $out['success']){
            return array('balance'=>'');
        }
        else{
            $out = $out['output'];
            $result = $this->General->xml2array($out);

            $status = '';
            return array('balance'=>$result['root']['balance']);
        }
    }

    function rduBalance(){
        $url = RDU_BAL_URL;
        $out = $this->General->rduApi($url, array('userId'=>RDU_REC_USERNAME, 'pwd'=>RDU_REC_PASSWORD));

        if( ! $out['success']){
            return array('balance'=>'');
        }
        else{
            $out = trim($out['output']);
            return array('balance'=>$out);
        }
    }

    function uvaBalance(){
        $url = UVA_BAL_URL;
        $out = $this->General->uvaApi($url, array('username'=>UVA_REC_USERID, 'uniqueid'=>UVA_REC_UNIQID));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = explode(":", trim($out['output']));
        $bal = $out[1];

        return array('balance'=>$bal);
    }

    function uniBalance(){
        $url = UNI_BAL_URL;
        $out = $this->General->uniApi($url, array('cname'=>UNI_CNAME, 'cmob'=>UNI_MNUMBER));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = explode("|", trim($out['output']));
        $bal = trim($out[1]);

        return array('balance'=>$bal);
    }

    function anandBalance(){
        $url = ANAND_RECHARGE_URL;
        $out = $this->General->anandApi($url, array('Mob'=>ANAND_MOB, 'message'=>"bal " . ANAND_PIN, 'source'=>'API'));

        if( ! $out['success']){
            return array('balance'=>'');
        }
        else{
            $out = trim($out['output']);
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/anand.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");

            $info = $this->General->matchTemplate($out, "Your Balance is @__balance__@");
            $vars = $info['vars'];
            $bal = $vars['balance'];

            return array('balance'=>$bal);
        }
    }

    function apnaBalance(){
        $url = APNA_BAL_URL;
        $out = $this->General->apnaApi($url, array('username'=>APNA_USERNAME, 'pwd'=>APNA_PASSWD));
        $vendor_id = 23;

        if( ! $out['success']){
            return array('balance'=>'');
        }
        else{
            $out = trim($out['output']);
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/apna.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");

            return array('balance'=>$out);
        }
    }

    function magicBalance(){
        $vendor_id = 24;
        $url = MAGIC_BAL_URL;
        $out = $this->General->magicApi($url, array('uid'=>MAGIC_USERNAME, 'pwd'=>MAGIC_PASSWD));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = trim($out['output']);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/magic.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");
        return array('balance'=>$out);
    }

    function rioBalance(){
        $vendor_id = 36;
        $url = RIO_BAL_URL;
        $out = $this->General->rioApi($url, array('uid'=>RIO_USERNAME, 'pwd'=>RIO_PASSWD));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = trim($out['output']);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rio.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");
        return array('balance'=>$out);
    }

    function rio2Balance(){
        $vendor_id = 62;
        $url = RIO2_BAL_URL;
        $out = $this->General->rioApi($url, array('uid'=>RIO2_USERNAME, 'pwd'=>RIO2_PASSWD));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = trim($out['output']);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rio2.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");
        return array('balance'=>$out);
    }

    function infogemBalance(){
        $vendor_id = 27;
        $terminal = GEM_USERNAME;
        $sha = strtoupper(sha1($terminal . GEM_PASSWORD));

        $url = GEM_BAL_URL;
        $out = $this->General->gemApi($url, array('PartnerId'=>$terminal, 'Hash'=>$sha));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = trim($out['output']);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/gem.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");
        return array('balance'=>intval($out));
    }

    function durgaBalance(){
        $vendor_id = 30;
        $url = DURGA_BAL_URL;
        $out = $this->General->durgaApi($url, array('cid'=>DURGA_UID, 'mob'=>DURGA_MNUMBER));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = explode("|", trim($out['output']));
        $bal = trim($out[1]);

        return array('balance'=>$bal);
    }

    function rkitBalance(){
        $vendor_id = 34;
        $url = RKIT_BAL_URL;
        $out = $this->General->rkitApi($url, array('USERID'=>RKIT_USER, 'PASSWORD'=>RKIT_AUTH));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        // $out = explode('#',trim($out['output']));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rkit.txt", date('Y-m-d H:i:s') . ":Balance Check: " . $out['output']['NODE']['BALANCE']);

        $bal = (is_array($out)) ? $out['output']['NODE']['BALANCE'] : $out;

        return array('balance'=>$bal);
    }

    function a2zBalance(){
        $vendor_id = 47;
        $url = A2Z_BAL_URL;
        $out = $this->General->a2zApi($url, array('USERID'=>A2Z_AGTCODE, 'PASSWORD'=>A2Z_AUTH));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = explode('#', $out['output']);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/a2z.txt", date('Y-m-d H:i:s') . ":Balance Check: $out[1]");
        $out = $out[1];

        $bal = trim($out);

        return array('balance'=>$bal);
    }

    function joinrecBalance(){
        $vendor_id = 48;
        $url = JOINREC_BAL_URL;
        $out = $this->General->joinrecApi($url, array('reseller_id'=>JOINREC_ID, 'reseller_pass'=>JOINREC_PWD));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/joinrec.txt", date('Y-m-d H:i:s') . ":Balance Check: " . json_encode($out));

        $bal = trim($out['Data']['Balance']);

        return array('balance'=>$bal);
    }

    function gitechBalanceOld(){
        $vendor_id = 35;
        $_POST['usecurl'] = true;
        $out = $this->General->gitechApi("CheckQuota", '', 'gitechBalance', array('0'=>$panel, '1'=> - 1));

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/gitech.txt", date('Y-m-d H:i:s') . ":Balance Check: " . json_encode($out));

        if(isset($out['balance'])) $bal = $out['balance'];
        else $bal = $out['GetBalanceResponse']['REMAININGAMOUNT'];

        return array('balance'=>$bal);
    }

    function gitechBalance(){
        $vendor_id = 35;
        $_POST['usecurl'] = true;
        $url = GITECH_BAL_URL;
        $request = array('Authentication'=>array('LoginId'=>GITECH_LOGINID,'Password'=>GITECH_PASSWORD));
        $out = $this->General->gitechApi($url, $request);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/gitech.txt", date('Y-m-d H:i:s') . ":Balance Check: " . $out);

        $out = json_decode($out,TRUE);

        $bal = isset($out['AgentCreditBalanceOutput']['RemainingAmount'])?$out['AgentCreditBalanceOutput']['RemainingAmount']:"";

        return array('balance'=>$bal);
    }

    function mypayBalance(){
        $vendor_id = '57';
        $url = MYPAYURL;
        $pwd = MYPAYPASSWD;

        $req_str = $pwd . "|bal";

        $out = $this->General->mypayApi($url, array('_prcsr'=>MYPAYUSER, '_urlenc'=>$req_str));

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/mypay.txt", date('Y-m-d H:i:s') . ": Check: " . json_encode($out));

        if( ! $out['success']){
            return array('balance'=>'');
        }
        $out = $out['output'];

        $bal = isset($out['_ApiResponse']['availableBalance']) ? ($out['_ApiResponse']['availableBalance']) : "";

        return array('balance'=>$bal);
    }

    function cpTranStatus($tranId, $date = null, $refId = null, $prodId = null){
        $sec = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pub_keys/' . CYBER_SECKEY);
        $pub = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/pub_keys/' . CYBER_PUBKEY);

        //$sec = defined('CYBER_PUBKEY_STR') ? CYBER_SECKEY_STR : $sec;
        //$pub = defined('CYBER_PUBKEY_STR') ? CYBER_PUBKEY_STR : $pub;
        //$this->create_cp_url_mapping();
        if($prodId == null){
            $dbObj = ClassRegistry::init('Slaves');
            $txn_id = $dbObj->query("SELECT vendors_activations.product_id FROM vendors_activations WHERE vendors_activations.txn_id= '" . $tranId . "'");
            $prodId = $txn_id['0']['vendors_activations']['product_id'];
        }
        $status_url = $this->createCpUrl($prodId, 'cps');

        if(isset($this->cp_opr_map[$prodId]['bbps_flag']))
        {
            $data = "SD=" . CYBER_SD . "\r\nAP=" . CYBER_AP . "\r\nOP=" . CYBER_OP . "\r\nSESSION=$tranId\r\n";
        }
        else
        {
            $data = "SESSION=$tranId\r\n";
        }

        $res = ipriv_sign($data, $sec, CYBER_PASSWORD);

        $out = $this->cpConnect($status_url, array('inputmessage'=>$res[1]));
        if( ! $out['success']){
            if($out['timeout']){
                $ret = array('status'=>'pending', 'status-code'=>'', 'description'=>'Connection timeout from server', 'tranId'=>$tranId, 'vendor_id'=>'', 'operator_id'=>'');
            }
            else{
                $ret = array('status'=>'pending', 'status-code'=>'', 'description'=>'Request timeout from server', 'tranId'=>$tranId, 'vendor_id'=>'', 'operator_id'=>'');
            }
            return $ret;
        }

        $out = $out['output'];
        $res = ipriv_verify($out, $pub);
        $result = $this->cpArray($res);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cp.txt", "*Status Check*: $status_url, Input=> " . json_encode($data) . "Output=>" . json_encode($result));

        $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);

        if(isset($result['RESULT']) &&  ! empty($result['RESULT'])){
            if($result['RESULT'] == 1){
                $ret = array('status'=>'failure', 'status-code'=>$result['ERROR'], 'description'=>'', 'tranId'=>$tranId, 'vendor_id'=>$result['TRANSID'], 'operator_id'=>$result['AUTHCODE']);
            }
            else if(1 < $result['RESULT'] && $result['RESULT'] < 7){
                $ret = array('status'=>'pending', 'status-code'=>$result['ERROR'], 'description'=>'Result is unknown at the moment', 'tranId'=>$tranId, 'vendor_id'=>$result['TRANSID'], 'operator_id'=>$result['AUTHCODE']);
            }
            else if($result['RESULT'] == 7 && $result['ERROR'] == 0){
                $ret = array('status'=>'success', 'status-code'=>$result['ERROR'], 'description'=>'', 'tranId'=>$tranId, 'vendor_id'=>$result['TRANSID'], 'operator_id'=>$result['AUTHCODE']);
            }
            else if(isset($result['ERROR']) &&  ! empty($result['ERROR'])){
                $error = $result['ERROR'];
                $ret = array('status'=>'failure', 'status-code'=>$error, 'description'=>$error . " :: " . $this->cp_errs[$error], 'tranId'=>$tranId, 'vendor_id'=>$result['TRANSID'], 'operator_id'=>$result['AUTHCODE']);
            }
            else{
                $ret = array('status'=>'pending', 'status-code'=>'', 'description'=>json_encode($result), 'tranId'=>$tranId, 'vendor_id'=>'', 'operator_id'=>'');
            }
        }
        else if(isset($result['ERROR']) && $result['ERROR'] != '0' &&  ! empty($result['ERROR'])){
            $error = $result['ERROR'];
            $ret = array('status'=>'failure', 'status-code'=>$error, 'description'=>$error . " :: " . $this->cp_errs[$error], 'tranId'=>$tranId, 'vendor_id'=>$result['TRANSID'], 'operator_id'=>$result['AUTHCODE']);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'', 'description'=>'Error in status check', 'tranId'=>$tranId, 'vendor_id'=>'', 'operator_id'=>'');
        }

        return $ret;
    }

    function modemTranStatus($tranId, $vendor = 4, $date = null){
        $adm = "query=status&transId=$tranId";
        $Rec_Data = $this->Shop->modemRequest($adm, $vendor);

        if($Rec_Data['status'] == 'failure'){
            $status = array('status'=>'pending', 'status-code'=>'', 'description'=>'Recharge modem not responding', 'tranId'=>$tranId, 'vendor_id'=>'', 'operator_id'=>'');
        }
        else{
            $Rec_Data = json_decode($Rec_Data['data'], true);
            $desc = ( ! empty($Rec_Data['cause'])) ? $Rec_Data['cause'] : $Rec_Data['sent'];
            $desc .= (isset($Rec_Data['sms'])) ? " (" . $Rec_Data['sms'] . ")" : "";

            $status = array('status'=>strtolower($Rec_Data['status']), 'status-code'=>$Rec_Data['status'], 'description'=>$desc, 'tranId'=>$tranId, 'vendor_id'=>'', 'operator_id'=>'');
            $status['sent_by'] = $Rec_Data['sent_by'];
            $status['Probable SMS'] = $Rec_Data['Probable SMS'];
            $status['around'] = $Rec_Data['around'];
            $status['trans_history'] = $Rec_Data['trans_history'];
        }
        return $status;
    }

    function paytTranStatus($transId, $date = null, $refid = null){
        $date = date('YmdHis');
        $terminal = PAYT_USER_CODE;
        $sha = strtoupper(sha1($terminal . $transId . $date . PAYT_PASSWORD));
        $content = "OperationType=2&TerminalId=$terminal&TransactionId=$transId&DateTimeStamp=$date&Hash=$sha";

        $Rec_Data = $this->paytConnection($content);
        $Rec_Data = $Rec_Data['output'];
        $response = explode("|", $Rec_Data);
        if(count($response) == 5 && $transId == trim($response[2])){
            if(trim($response[0]) == '0'){
                $status = trim($response[1]);
                $opr_id = trim($response[3]);

                if($opr_id == 'NA') $opr_id = "";
                $desc = trim($response[4]);

                if($status == '0'){ // success
                    $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'ref_id'=>$opr_id);
                }
                else if($status == '1' || $status == '4'){ // under success
                    $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'ref_id'=>$opr_id);
                }
                else if($status == '2' || $status == '3'){ // failure
                    $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'ref_id'=>$opr_id);
                }
                return $ret;
            }
            else if(trim($response[0]) == '1' || trim($response[0]) == '2'){
                return array('status'=>'failure', 'description'=>'Transaction not found');
            }
            else{
                return array('status'=>'pending', 'description'=>'Technical Error');
            }
        }
        else{
            return array('status'=>'pending', 'description'=>'Technical Error - Some issue in connection');
        }
    }

    function cbzTranStatus($transId, $date = null, $refid = null){
        if( ! is_array($transId)) $transId = array($transId);

        $url = CBZ_TRANS_URL;
        $out = $this->General->cbzApi($url, array('username'=>CBZ_REC_USERNAME, 'password'=>CBZ_REC_PASSWORD, 'key'=>CBZ_REC_KEY, 'cmd'=>'tr_status', 'trans_id'=>implode(",", $transId)));
        $out = $out['output'];

        if( ! empty($date) && $date < date('Y-m-d', strtotime('-1 days'))){
            echo "Please check on cellbiz panel";
            // $this->General->sendMails('Paytronics Transaction check: Error in message',$content."<br/>".json_encode($response),array('ashish@pay1.in'));

            return array('status'=>'NA', 'description'=>'Check on cellbiz panel');
        }

        $rec = $this->General->xml2array($out);
        if($date == null && $refid == null){
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/cbz.txt", "*Trans Check*: Input=> " . implode(",", $transId) . "<br/>" . json_encode($rec));
        }

        $status = array();
        if( ! empty($rec)){
            $result = array();
            if( ! isset($rec['root']['rec']['0']) && isset($rec['root']['rec'])) $result[] = $rec['root']['rec'];
            else if(isset($rec['root']['rec'])) $result = $rec['root']['rec'];
            else{
                foreach($transId as $tid){
                    $data = array();
                    $data['status'] = $rec['root']['result'];
                    $data['req_id'] = $tid;
                    $result[] = $data;
                }
            }
            foreach($result as $res){
                $status[$res['req_id']]['tranId'] = $res['req_id'];
                $status[$res['req_id']]['refId'] = $res['tr_id'];
                if(strtoupper($res['status']) == 'FAIL' || strtoupper($res['status']) == 'REVERT'){
                    $status[$res['req_id']]['status'] = 'failure';
                }
                else if(strtoupper($res['status']) == 'SUCCESSFUL'){
                    $status[$res['req_id']]['status'] = 'success';
                }
                else{
                    $status[$res['req_id']]['status'] = 'process';
                }
            }

            foreach($transId as $tid){
                if( ! isset($status[$tid])){
                    $status[$tid]['status'] = 'NA';
                    $status[$tid]['tranId'] = $tid;
                }
            }

            return $status;
        }
        else{
            return array('status'=>'NA', 'description'=>'Technical Error');
        }
    }

    function rduTranStatus($transId, $date = null, $ref_id = null){
        $url = RDU_TRANS_URL;
        $out = $this->General->rduApi($url, array('userId'=>RDU_REC_USERNAME, 'pwd'=>RDU_REC_PASSWORD, 'clientTrnId'=>$transId, 'date'=>$date));
        $out = $out['output'];

        if($date == null && $refid == null){
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rdu.txt", "*Trans Check*: Input=> $transId<br/>$out");
        }

        $response = explode(":", $out);

        if(count($response) >= 6 && $transId == trim($response[3])){
            $vendor_trans_id = trim($response[5]);
            $opr_id = trim($response[6]);
            $status = trim($response[0]);
            $desc = trim($response[2]);
            // if($opr_id == 'NA')$opr_id = "";

            if($status == '0' || $status == '2'){ // success
                $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>$vendor_trans_id, 'opr_ref_id'=>$opr_id);
            }
            else if($status == '528'){ // under success
                $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>$vendor_trans_id, 'opr_ref_id'=>$opr_id);
            }
            else{ // failure
                $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>$vendor_trans_id, 'opr_ref_id'=>$opr_id);
            }
            return $ret;
        }
        else if(trim($response[0]) == '527'){
            return array('status'=>'failure', 'description'=>'Transaction not found');
        }
        else{
            return array('status'=>'pending', 'description'=>'Technical Error - Some issue in request');
        }
    }

    function uvaTranStatus($transId, $date = null, $refId, $power = null){
        $url = UVA_TRANS_URL;
        $out = $this->General->uvaApi($url, array('username'=>UVA_REC_USERID, 'uniqueid'=>UVA_REC_UNIQID, 'clid'=>$transId));
        $out = $out['output'];

        if($date == null){
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/uva.txt", "*Trans Check*: Input=> $transId<br/>$out");
        }

        $response = explode("|", $out);
        $status = trim($response[0]);
        $desc = trim($response[1]);
        $oprId = ($response[2] == 'N' || $response[2] == 'NA') ? "" : trim($response[2]);

        if($status == 0 && ( ! empty($oprId) || $power == 1)){
            $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>$refId, 'operator_id'=>$oprId);
        }
        else if($status == 503){
            $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>$refId, 'operator_id'=>$oprId);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>$refId, 'operator_id'=>$oprId);
        }

        return $ret;
    }

    function uniTranStatus($transId, $date = null, $refId = null){
        $url = UNI_TRANS_URL;
        $out = $this->General->uniApi($url, array('crqid'=>$transId, 'cro'=>'2'));
        $out = $out['output'];

        // 505|Not found | |501|Not Process |
        /*
         * if($date == null){
         * $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/uni.txt","*Trans Check*: Input=> $transId<br/>$out");
         * }
         */

        $response = explode("|", $out);
        $status = trim($response[0]);

        if($status == 0){
            $oprId = (trim($response[1]) == 'NA') ? "" : trim($response[1]);
            $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>'Success', 'tranId'=>$transId, 'vendor_id'=>'', 'operator_id'=>$oprId);
        }
        else if(in_array($status, array(503, 505))){
            $desc = trim($response[1]);
            $oprId = trim($response[3]);
            $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>'', 'operator_id'=>$oprId);
        }
        else{
            $desc = trim($response[1]);
            $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>'', 'operator_id'=>'');
        }

        return $ret;
    }

    function anandTranStatus($transId, $date = null, $refId = null){
        $url = ANAND_TRANS_URL;
        $out = $this->General->anandApi($url, array('Mob'=>ANAND_MOB, 'message'=>"TxId $refId " . ANAND_PIN, 'source'=>'API'));
        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/anand.txt", date('Y-m-d H:i:s') . ":AnandTranStatus: " . json_encode(array('Mob'=>ANAND_MOB, 'message'=>"myTxId $transId " . ANAND_PIN, 'source'=>'API')));

        $response = explode(",", $out);
        $status = trim($response[0]);

        $status = explode(":", $status);

        $stat = strtolower(trim($status[1]));
        if($stat == 'success'){
            $ret = array('status'=>'success', 'status-code'=>'success', 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>'', 'operator_id'=>'');
        }
        else if($stat == 'fail'){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>'', 'operator_id'=>'');
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>'', 'operator_id'=>'');
        }

        return $ret;
    }

    function apnaTranStatus($transId, $date = null, $refId = null){
        $url = APNA_TRANS_URL;
        $out = $this->General->apnaApi($url, array('username'=>APNA_USERNAME, 'pwd'=>APNA_PASSWD, 'client_id'=>$transId));

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/apna.txt", date('Y-m-d H:i:s') . ":ApnaTranStatus: " . $out);

        // Success#MU20061014170010#200708912#297617
        // Failure##200709042#297740
        $response = explode("#", $out);
        $status = strtolower(trim($response[0]));
        $operator_id = trim($response[1]);
        $vendor_id = trim($response[2]);

        if($status == 'success'){
            $ret = array('status'=>'success', 'status-code'=>'success', 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else if($status == 'failure'){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }

        return $ret;
    }

    function magicTranStatus($transId, $date = null, $refId = null){
        $url = MAGIC_TRANS_URL;
        $out = $this->General->magicApi($url, array('uid'=>MAGIC_USERNAME, 'pwd'=>MAGIC_PASSWD, 'transid'=>$transId));

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/magic.txt", date('Y-m-d H:i:s') . ":MagicTranStatus: " . $out);

        // transid=<transaction_id>;status=<status>;optransid=<operator_transaction_id>
        $response = explode(";", $out);

        $status = explode("=", strtolower(trim($response[1])));
        $status = strtolower($status[1]);

        $operator_id = explode("=", strtolower(trim($response[2])));
        $operator_id = strtolower($operator_id[1]);

        $vendor_id = "";

        if($status == 'success' && $operator_id != '' &&  strpos($operator_id,'refund') === false ){
            $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else if($status == 'failure' || $status == 'cancel' || ($operator_id != '' &&  strpos($operator_id,'refund') !== false)){
            $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }

        return $ret;
    }

    function rioTranStatus($transId, $date = null, $refId = null){
        $url = RIO_TRANS_URL;
        $out = $this->General->rioApi($url, array('uid'=>RIO_USERNAME, 'pwd'=>RIO_PASSWD, 'transid'=>$transId));

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rio.txt", date('Y-m-d H:i:s') . ":RIOTranStatus: " . $out);

        // transid=<transaction_id>;status=<status>;optransid=<operator_transaction_id>
        $response = explode(";", $out);

        $status = explode("=", strtolower(trim($response[1])));
        $status = strtolower($status[1]);

        $operator_id = explode("=", strtolower(trim($response[2])));
        $operator_id = strtolower($operator_id[1]);

        $vendor_id = "";

        if($status == 'success'){
            $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else if($status == 'failure' || $status == 'cancel' || trim($out) == 'Not Found'){
            $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }

        return $ret;
    }

    function rio2TranStatus($transId, $date = null, $refId = null){
        $url = RIO2_TRANS_URL;
        $out = $this->General->rioApi($url, array('uid'=>RIO2_USERNAME, 'pwd'=>RIO2_PASSWD, 'transid'=>$transId));

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rio2.txt", date('Y-m-d H:i:s') . ":RIOTranStatus: " . $out);

        // transid=<transaction_id>;status=<status>;optransid=<operator_transaction_id>
        $response = explode(";", $out);

        $status = explode("=", strtolower(trim($response[1])));
        $status = strtolower($status[1]);

        $operator_id = explode("=", strtolower(trim($response[2])));
        $operator_id = strtolower($operator_id[1]);

        $vendor_id = "";

        if($status == 'success'){
            $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else if($status == 'failure' || $status == 'cancel' || trim($out) == 'Not Found'){
            $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }

        return $ret;
    }

    function infogemTranStatus($transId, $date = null, $refId = null){
        $vendor = 27;
        $terminal = GEM_USERNAME;
        $sha = strtoupper(sha1($terminal . $transId . GEM_PASSWORD));

        $url = GEM_TRANS_URL;
        $out = $this->General->gemApi($url, array('PartnerId'=>$terminal, 'TransId'=>$transId, 'Hash'=>$sha));

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/gem.txt", date('Y-m-d H:i:s') . ":GemTranStatus: " . $out);

        // transid=<transaction_id>;status=<status>;optransid=<operator_transaction_id>
        $response = explode("|", $out);
        $status = trim($response[0]);
        $vendor_id = trim($response[2]);
        $operator_id = trim($response[3]);
        $desc = trim($response[4]);
        $pay1_transid = trim($response[1]);

        if($operator_id == 'NA') $operator_id = "";

        $status_array = array('100'=>'Transaction Successful', '99'=>'Recharge Failed', '101'=>'Invalid Login', '102'=>'Insufficient Balance', '103'=>'Invalid Amount', '104'=>'Invalid Trans ID', '105'=>'Trans ID already exists', '106'=>'Service Unavailable for user', '107'=>'Invalid phone Number',
                '110'=>'Invalid Transaction amount', '111'=>'Daily Limit reached', '121'=>'Account Blocked', '123'=>'Technical Failure', '165'=>'Response waiting', '170'=>'Wrong Requested Ip', '171'=>'Repeated Request', '173'=>'Operator temporarly not available', '172'=>'Invalid request',
                '174'=>'Hash Value MisMatch');
        if(empty($desc)) $desc = $status_array[$status];
        if(empty($desc)) $desc = $out;

        if(empty($status) || $status == '165' || strlen($status) > 5 || $pay1_transid != $transId){ // pending
            $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else if($status == '100'){
            $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else{
            $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/gem.txt", date('Y-m-d H:i:s') . ":ReturnGemTranStatus: " . json_encode($ret) . " status:$status");

        return $ret;
    }

    function durgaTranStatus($transId, $date = null, $refId = null){
        $url = DURGA_TRANS_URL;
        $out = $this->General->durgaApi($url, array('ctxnid'=>$transId, 'cid'=>'10'));
        $out = trim($out['output']);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/durga.txt", "*Trans Check*: Input=> $transId<br/>$out");
        // 505|Not found | |501|Not Process |
        /*
         * if($date == null){
         *
         * }
         */

        // 503|Failed | BC2012092485485 | 1592560
        $response = explode("|", $out);
        $status = trim($response[0]);

        if($status == 0 &&  ! empty($out)){
            $oprId = (trim($response[1]) == 'N') ? "" : trim($response[1]);
            $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>'Success', 'tranId'=>$transId, 'vendor_id'=>'', 'operator_id'=>$oprId);
        }
        else if(in_array($status, array(503, 505))){
            $desc = trim($response[1]);
            $oprId = trim($response[3]);
            $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>'', 'operator_id'=>$oprId);
        }
        else{
            $desc = trim($response[1]);
            $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>'', 'operator_id'=>'');
        }

        return $ret;
    }

    function pay1TranStatus($transId, $date = null, $refId = null){
        $url = B2C_URL.'actiontype/check_transaction/api/true';
        $out = $this->General->curl_post($url, array('client_req_id'=>$transId));
        $out = $out['output'];

        $response = json_decode($out, true);
        $status = trim($response['status']);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pay1.txt", date('Y-m-d H:i:s') . ":Request Sent: " . $url . "::" . $transId);

        if($status == 'success'){
            $ret = array('status'=>'success', 'description'=>'success', 'tranId'=>$transId, 'vendor_id'=>$response['description']);
        }
        else if($status == 'failure'){
            $ret = array('status'=>'failure', 'description'=>$response['description'], 'tranId'=>$transId, 'errCode'=>$response['errCode']);
        }

        return $ret;
    }

    function rkitTranStatus($transId, $date = null, $refId = null){
        $url = RKIT_TRANS_URL;

        $out = $this->General->rkitApi($url, array('USERID'=>RKIT_USER, 'PASSWORD'=>RKIT_AUTH, 'TRANNO'=>$transId));
        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rkit.txt", date('Y-m-d H:i:s') . ":RkitTranStatus: " . json_encode($out));

        // transid=<transaction_id>;status=<status>;optransid=<operator_transaction_id>
        // $response = explode("#",trim($out));

        $status = strtolower(trim($out['NODE']['STATUS']));
        $operator_id = strtolower(trim($out['NODE']['OPTTRAN']));

        $vendor_id = "";

        if($status == 'success'){
            $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else if(in_array($status, array('failed', 'transaction not found'))){
            $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }

        return $ret;
    }

    function a2zTranStatus($transId, $date = null, $refId = null){
        $url = A2Z_TRANS_URL;
        $out = $this->General->a2zApi($url, array('USERID'=>A2Z_AGTCODE, 'PASSWORD'=>A2Z_AUTH, 'TRANNO'=>$transId));

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/a2z.txt", date('Y-m-d H:i:s') . ":A2ZTranStatus: " . $out);

        // transid=<transaction_id>;status=<status>;optransid=<operator_transaction_id>

        $out = $this->General->xml2array("<NODE>" . $out . "</NODE>");

        $status = strtolower(trim($out['NODE']['STATUS']));

        $vendor_id = "";

        $operator_id = $out['NODE']['OPTTRAN'];

        if($status == 'success'){
            $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else if(in_array($status, array('failed', 'transaction not found'))){
            $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }

        return $ret;
    }

    function joinrecTranStatus($transId, $date = null, $refId = null){
        $url = JOINREC_TRANS_URL;
        $out = $this->General->joinrecApi($url, array('reseller_id'=>JOINREC_ID, 'reseller_pass'=>JOINREC_PWD, 'meroid'=>$transId));

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/joinrec.txt", date('Y-m-d H:i:s') . ":JoinRecTranStatus: " . json_encode($out));

        $status = trim($out['RechargeStatus']['Status']);
        $operator_id = trim($out['RechargeStatus']['OperatorTxnId']);
        $vendor_id = trim($out['RechargeStatus']['OrderId']);

        if(isset($out['RechargeStatus']['Error'])){
            $description = trim($out['RechargeStatus']['Error']);
        }
        else
            $description = trim($out['RechargeStatus']['Description']);

        if($status == 'SUCCESS'){
            $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$description, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else if($status == 'FAILED'){
            $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$description, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$description, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }

        return $ret;
    }

    /**
     * ***
     */
    function mypayTranStatus($transId, $date = null, $refId = null){
        $url = MYPAYURL;
        $pwd = MYPAYPASSWD;

        $dt_time = date('m.d.Y H:i:s');

        // pwd|status|refId
        // $req_str = $pwd."|status|".$refId;
        $req_str = $pwd . "|status|" . $transId;
        $out = $this->General->mypayApi($url, array('_prcsr'=>MYPAYUSER, '_urlenc'=>$req_str));

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/mypay.txt", date('Y-m-d H:i:s') . ":mypay TranStatus: " . json_encode($out));
        if(strpos($out['_ApiResponse']['statusDescription'], "|")){
            $status_arr = explode("|", $out['_ApiResponse']['statusDescription']);
            $status = isset($status_arr[1]) ? strtoupper($status_arr[1]) : "";
        }
        else{
            $status = $out['_ApiResponse']['statusDescription'];
        }
        $operator_id = "";
        $vendor_id = "";
        $description = "";
        $statusCode = $out['_ApiResponse']['statusCode'];
        if($status == 'SUCCESS'){
            $operator_id = isset($status_arr[3]) ? trim($status_arr[3]) : "";
            $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$description, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else if(($status == 'FAILED' ||  ! (in_array($statusCode, array('10008', '10010', '10019', '10020' ,'10021')))) && ( ! empty($status))){
            $description = isset($status_arr[3]) ? trim($status_arr[3]) : "";
            $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$description, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$description, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }

        return $ret;
    }

    function gitechTranStatusOld($transId, $date = null, $refId = null){
        $newtransId = $transId . $transId . $transId;
        $_POST['usecurl'] = true;
        $request = "<CheckTransReq>
        <UserTrackID>$newtransId</UserTrackID>
        </CheckTransReq>";
        $out = $this->General->gitechApi("CheckTransactionStatus", $request, 'gitechTranStatus', array('0'=>$transId, '1'=> - 1));

        if(isset($out['status'])){
            $ret = $out;
        }
        else{
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/gitech.txt", date('Y-m-d H:i:s') . ":GitechTranStatus: request is $request::" . json_encode($out));

            $status = trim($out['CheckTransRes']['StatusCode']);
            $desc = trim($out['CheckTransRes']['Remarks']);
            $vendor_id = "";
            $operator_id = "";

            if($status == 1){
                $vendor_id = $desc;
                $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
            }
            else if($status == 3){
                $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
            }
            else{
                $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
            }
        }
        if(isset($out['status']) || $date ==  - 1){
            return $ret;
        }
        else
            return $ret;
    }

    function gitechTranStatus($transId, $date = null, $refId = null){
        $newtransId = $transId . $transId . $transId;
        $_POST['usecurl'] = true;
        $url = GITECH_TRANS_URL;
        $request = array('Authentication'=>array('LoginId'=>GITECH_LOGINID,'Password'=>GITECH_PASSWORD),'UserTrackId'=>$newtransId);
        $out = $this->General->gitechApi($url, $request);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/gitech.txt", date('Y-m-d H:i:s') . ":GitechTranStatus: request :: " .json_encode($request). "response :: " . $out);
        $out = json_decode($out,TRUE);

        $status = $out['TransactionStatusOutput']['TransactionStatus'];
        $desc = $out['TransactionStatusOutput']['Remarks'];
        $vendor_id = "";
        $operator_id = "";

        if($status == 1){
            $ret = array('status'=>'success', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>$out['TransactionStatusOutput']['TransactionDetails']['ReferenceNumber'], 'operator_id'=>$out['TransactionStatusOutput']['TransactionDetails']['OperatorTransactionId']);
        }
        else if($status == 3){
            $ret = array('status'=>'failure', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>$status, 'description'=>$desc, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }

        return $ret;
    }

    function cpArray($res){
        $array = array();

        if($res[0] == 0){
            $output = explode("\n", $res[1]);

            foreach($output as $oput){
                $exp = explode("=", $oput);
                if( ! empty($exp[0])) $array[trim($exp[0])] = trim($exp[1]);
            }
        }
        return $array;
    }

    /**
     * * practicsoft api Integration ***
     */
    function practicMobRecharge($transId, $params, $prodId){

        // 9769480014
        $mobileNo = $params['mobileNumber'];

        // $mobileNo = 9769480014;
        //
        $amount = intval($params['amount']);

        // $amount = 20;

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['practic'];

        // $provider = 'Vodafone';

        // $transId = '32145678910';

        $vendor = 68;

        if(in_array($prodId, array('27', '28', '31'))){
            $rechargeType = 'S';
        }
        elseif(in_array($prodId, array('3','29'))){
            $rechargeType = 'T';
        }
        else
        {
           $rechargeType = "R";
        }

        $url = PRACTIC_RECHARGE_URL;
        $params = array('Operator'=>$provider, 'Number'=>$mobileNo, 'Amount'=>$amount, 'RechargeType'=>$rechargeType, 'ReferenceID'=>$transId, 'Result'=>1, 'Repeat'=>0, 'UserID'=>PRACTIC_USERID, "Password"=>PRACTIC_PASSWORD, "Key"=>PRACTIC_KEY);
        $out = $this->General->practicApi($url, $params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/practic.txt", "*Mob Recharge*: Input=> $transId Params=> ".json_encode($params)."<br/>$out");

        $res = explode("^", $out);

        $status = strtolower(trim($res[0]));

        $txnId = trim($res[2]);

        if(empty($status) || $status == 'requestaccepted'){
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
        else{
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
    }

    function practicDthRecharge($transId, $params, $prodId){
        $mobileNo = $params['subId'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['practic'];

        $rechargeType = "R";
        $vendor = 68;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $rechargeType = 'S';
        }

        $params = array('Operator'=>$provider, 'Number'=>$mobileNo, 'Amount'=>$amount, 'RechargeType'=>$rechargeType, 'ReferenceID'=>$transId, 'Result'=>1, 'Repeat'=>0, 'UserID'=>PRACTIC_USERID, "Password"=>PRACTIC_PASSWORD, "Key"=>PRACTIC_KEY);
        $url = PRACTIC_RECHARGE_URL;
        $out = $this->General->practicApi($url, $params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/practic.txt", "*DTH Recharge*: Input=> $transId Params=> ".json_encode($params)." <br/>$out");

        $res = explode("^", $out);

        $status = strtolower(trim($res[0]));

        $txnId = trim($res[2]);

        if(empty($status) || $status == 'requestaccepted'){
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
        else{
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
    }

    function practicTranStatus($transId, $date = null, $refId = null){
        $url = PRACTIC_TRANS_URL;

        $out = $this->General->practicApi($url, array('u'=>PRACTIC_USERID, 'p'=>PRACTIC_PASSWORD, 'c'=>$transId));
        $operator_id = '';
        $vendor_id = '';
        $out = $out['output'];
        $check = simplexml_load_string($out);
        if( ! $check){
            if(trim($out) == 'Record not found.' && intVal((time() - strtotime($date)) / 86400) >= 2){
                $status = 9;
            }
            elseif(trim($out) == 'Record not found.'){
                $status = 7;
            }
            else{
                $status = 0;
            }
        }
        else{
            // 0 – Recharge request in process.
            // 1 – Recharge successful.
            // 2 – Request accepted.
            // 3 – Recharge suspense.
            // 4 – System reserved.
            // 7 – Recharge failure.
            // 9 – status not provided by vendor.
            $out = $this->General->xml2array($out);
            $vendor_id = isset($out['RechargeRequest']['RequestResponse']['APIRef']) ? $out['RechargeRequest']['RequestResponse']['APIRef'] : "";

            $operator_id = isset($out['RechargeRequest']['RequestResponse']['ORef']) ? $out['RechargeRequest']['RequestResponse']['ORef'] : "";

            $operator_id = in_array(strtolower(trim($operator_id)), array("nil", "null")) ? "" : $operator_id;

            $status = isset($out['RechargeRequest']['RequestResponse']['RecS']) ? $out['RechargeRequest']['RequestResponse']['RecS'] : 0;
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/practic.txt", date('Y-m-d H:i:s') . ":PracticTranStatus:$transId: " . json_encode($out));

        if($status == '1'){
            $ret = array('status'=>'success', 'status-code'=>'success', 'description'=>$out['RechargeRequest']['RequestResponse'], 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else if($status == '7' || $status == '4'){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        elseif($status == '5'){
            $ret = array('status'=>'Refund', 'status-code'=>'Refund', 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        elseif($status == '9'){
            $out = 'status not available. Kindly check on vendor\'s panel';
            $ret = array('status'=>'status not available. Kindly check on vendor\'s panel', 'status-code'=>'pending', 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'description'=>$out, 'tranId'=>$transId, 'vendor_id'=>$vendor_id, 'operator_id'=>$operator_id);
        }

        return $ret;
    }

    function practicBalance(){
        $vendor_id = 68;
        $url = PRACTIC_BAL_URL;
        $out = $this->General->practicApi($url, array('a'=>PRACTIC_KEY));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = trim($out['output']);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/practic.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");
        return array('balance'=>$out);
    }

    function simpleMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];

        $amount = intval($params['amount']);

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        // if(isset($params['area']) && $params['area'] == 'MH' && $prodId == 4){
        // $provider = 8;
        // } else {
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['simple'];
        // }

        $vendor = 69;

        $url = SIMPLE_RECHARGE_URL;

        $out = $this->General->simpleApi($url, array('username'=>SIMPLE_USERID, 'pwd'=>SIMPLE_PASSWORD, 'circlecode'=>'*', 'operatorcode'=>$provider, 'number'=>$mobileNo, 'amount'=>$amount, 'orderid'=>$transId));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/simple.txt", "*Mob Recharge*: Input=> $transId<br/>$out<br/>params=>" . json_encode($params));

        $res = explode("#", $out);

        if(count($res) == 1){
            $res = explode("::", $out);
        }
        else{
            $txnId = trim($res[0]);
        }

        if(trim($res[0]) == 'ERROR'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$res[1]);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
    }

    function simpleBalance(){
        $vendor_id = 69;

        $url = SIMPLE_BAL_URL;
        $out = $this->General->simpleApi($url, array('username'=>SIMPLE_USERID, 'pwd'=>SIMPLE_PASSWORD));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = trim($out['output']);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/simple.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");

        return array('balance'=>$out);
    }

    function simpleTranStatus($transId, $date = null, $refId = null){
        $url = SIMPLE_TRANS_URL;

        $out = $this->General->simpleApi($url, array('username'=>SIMPLE_USERID, 'pwd'=>SIMPLE_PASSWORD, 'order_id'=>$transId));
        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/simple.txt", date('Y-m-d H:i:s') . ":SimpleTranStatus: " . $out);

        if($out == 'Success'){
            $ret = array('status'=>'success', 'status-code'=>'success', 'description'=>$out, 'tranId'=>$transId);
        }
        else if($out == 'Failure'){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'description'=>$out, 'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'description'=>$out, 'tranId'=>$transId);
        }

        return $ret;
    }



        function ccaBillerInfo($biller_id) {

            $bill_data = $this->Shop->getMemcache("bbps_billinfo_".$biller_id);

            if(!$bill_data) {

                if($biller_id != '') {

                    $url = CCAVENUE_RECHARGE_URL . 'extMdmCntrl/mdmRequest/xml';

                    $xml = '<?xml version="1.0" encoding="UTF-8"?>
<billerInfoRequest>
 <billerId>'.$biller_id.'</billerId>
</billerInfoRequest>';

                    $out = $this->General->ccavenueApi($url, $xml);

                    if(!$out['success'] && $out['timeout']){
                        return array('status'=>'failure', 'description'=>'Not able to connect to server');
                    }

                    $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", date('Y-m-d H:i:s') . " :: * Utility Biller Info * :: Request : ".$xml);
                    $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", date('Y-m-d H:i:s') . " :: * Utility Biller Info * :: Response : ".json_encode($out));

                    $bill = $out['output']['billerInfoResponse'];
                    if($out['output']['billerInfoResponse']['responseCode'] == '000') {
                        $this->Shop->setMemcache("bbps_billinfo_".$biller_id, $bill['biller'], 24*60*60);
                        return array('status'=>'success', 'description'=>$bill['biller'], 'code'=>200);
                    } else {
                        return array('status'=>'failure', 'code'=>$bill['errorInfo']['error']['errorCode'], 'description'=>$bill['errorInfo']['error']['errorMessage']);
                    }

                }
                else {
                    return array('status'=>'failure', 'code'=>'', 'description'=>'biller id is empty');
                }
            }
            else {
                return array('status'=>'success', 'description'=>$bill_data, 'code'=>200);
            }

        }

        function getccaBillerId($params, $prod_id){
            $var = 'cca';
            if(in_array($prod_id,$this->param_exceptions) || $prod_id == 92){
                $var = 'cca_'.$params['param'];
            } else if($prod_id == 88) {
                $var = 'cca_'.$params['param1'];
            }
            $biller_id = $this->mapping['utilityBillPayment'][$prod_id]['flexi'][$var];
            return $biller_id;
        }

        function ccaUtilityBillFetch($params, $prod_id) {

                $url = CCAVENUE_RECHARGE_URL . 'extBillCntrl/billFetchRequest/xml';

                $slaveObj = ClassRegistry::init('Slaves');
//                $res = $slaveObj->query("SELECT agent_id FROM bbps_agents WHERE retailer_id = '{$_SESSION['Auth']['id']}' AND vendor_id = 161");
//                !$res && $res = $slaveObj->query("SELECT agent_id FROM bbps_agents ORDER BY RAND() LIMIT 1");
//                $agent_id = $res[0]['bbps_agents']['agent_id'];
                $agent_id = $this->getAgentId($_SESSION['Auth']['id'],161);
                $biller_id = $this->getccaBillerId($params, $prod_id);

                Configure::load('billers');
                $billers = Configure::read('billers');
                $dynamic_fields = $billers[$prod_id]['fields'];

                $xml = '<?xml version="1.0" encoding="UTF-8"?>
   <billFetchRequest>
   <agentId>'.$agent_id.'</agentId>
   <agentDeviceInfo>
      <ip>'.$_SERVER['SERVER_ADDR'].'</ip>
      <initChannel>AGT</initChannel>
      <mac>02:42:AC:11:00:03</mac>
   </agentDeviceInfo>
   <customerInfo>
      <customerMobile>'.$params['mobileNumber'].'</customerMobile>
      <customerEmail></customerEmail>
      <customerAdhaar></customerAdhaar>
      <customerPan></customerPan>
   </customerInfo>
   <billerId>'.$biller_id.'</billerId>
   <inputParams>';
if(in_array($prod_id,array(147,148)))
{
    $exceptions = array('amount');
}
else
{
    $exceptions = array('mobileNumber','amount');
}

if(isset($this->cca_exceptions[$prod_id])){
    $exceptions = array_merge($exceptions,$this->cca_exceptions[$prod_id]);
}
foreach($dynamic_fields as $d_f) {
    if(!in_array($d_f['param'], $exceptions)) {
    $xml .= '<input>
                <paramName>'.$d_f['label'].'</paramName>
                <paramValue>'.$params[$d_f['param']].'</paramValue>
             </input>';
    }
}
$xml .= '</inputParams>
</billFetchRequest>';

                $tran_id = time().$_SESSION['Auth']['User']['mobile'].$prod_id;
                $tran_id = substr("pay1".$tran_id.md5(uniqid(rand(), true)),0,35);
                $params['bbps'] && $out = $this->General->ccavenueApi($url, $xml, $tran_id);
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", "TxnId : ".$tran_id." CCA Bill Fetch Response : ".json_encode($out));
                 
                if(!$out['success'] && $out['timeout']){
                        return array('status'=>'failure', 'description'=>'Not able to connect to server');
                }
                $mem_data = array('tran_id'=>$tran_id);
                $this->Shop->setMemcache("bbps_".$prod_id."_".$params['accountNumber']."_".$_SESSION['Auth']['id']."_161", $mem_data, 60 * 60);
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", "TxnId : ".$tran_id." * CCAvenues Utility Bill Fetch * :: Request : ".json_encode($xml));
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", " * CCAvenues Utility Bill Fetch * :: Response : ".json_encode($out));
                
                $resp = $out['output']['billFetchResponse'];
                if($resp['responseCode'] == '000') {
                        $mem_data = array('tran_id'=>$tran_id,'bill_number'=>$resp['billerResponse']['billNumber'],'bill_date'=>$resp['billerResponse']['billDate'],'due_date'=>$resp['billerResponse']['dueDate'],'bill_amount'=>($resp['billerResponse']['billAmount'])/100,'amount'=>($resp['billerResponse']['billAmount'])/100,'bill_period'=>$resp['billerResponse']['billPeriod'],'customer_name'=>$resp['billerResponse']['customerName'],'bbps'=>'1','vendor_id'=>161);
                        if(isset($resp['billerResponse']['amountOptions'])) {
                            foreach($resp['billerResponse']['amountOptions']['option'] as $option){
                                $name = $option['amountName'];
                                $mem_data['amount_options'][$name] = $option['amountValue'];
                            }
                        }
                        if(isset($resp['additionalInfo'])) {
                            foreach($resp['additionalInfo']['info'] as $a_i) {
                                 $mem_data['additional_info'][$a_i['infoName']] = $a_i['infoValue'];
                            }
                        }

                        if(isset($mem_data['additional_info']['Early Payment Date']) && date('Y-m-d') <= date('Y-m-d', strtotime($mem_data['additional_info']['Early Payment Date']))){
                            $mem_data['bill_amount'] = ($mem_data['amount_options']['Early Payment Amount'])/100;
                        }
                        elseif(isset($mem_data['due_date']) && isset($mem_data['amount_options']['Late Payment Amount']) && $mem_data['due_date'] < date('Y-m-d')){
                            $mem_data['bill_amount'] = ($mem_data['amount_options']['Late Payment Amount'])/100;
                        }else{
                            $mem_data['bill_amount'] = ($resp['billerResponse']['billAmount'])/100;
                        }
                        $this->Shop->setMemcache("bbps_".$prod_id."_".$params['accountNumber']."_".$_SESSION['Auth']['id']."_161", $mem_data, 60 * 60);
                        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", " SET cca memcache key : bbps_".$prod_id."_".$params['accountNumber']."_".$_SESSION['Auth']['id']."_161 Data :". json_encode($mem_data));
                        return array('status'=>'success', 'description'=>array('bill_number'=>$resp['billerResponse']['billNumber'],'bill_date'=>$resp['billerResponse']['billDate'],'due_date'=>$resp['billerResponse']['dueDate'],'bill_amount'=>$mem_data['bill_amount'],'bill_period'=>$resp['billerResponse']['billPeriod'],'customer_name'=>$resp['billerResponse']['customerName'],'acc_no_label'=>$dynamic_fields[1]['label']), 'code'=>200, 'bbps'=>1);
                } else {
                        /*if(!in_array($resp['errorInfo']['error']['errorCode'], array('E001','E002','BFR001','BFR004'))) {
                                $resp['errorInfo']['error']['errorMessage'] = 'Something went wrong. Contact Pay1 Customercare';
                        }*/
                        return array('status'=>'failure', 'code'=>$resp['errorInfo']['error']['errorCode'], 'description'=>$resp['errorInfo']['error']['errorMessage']);
                }
        }

        function ccaUtilityBillPayment($tran_id, $params, $prod_id) {
                $bill_data = $this->Shop->getMemcache("bbps_".$prod_id."_".$params['accountNumber']."_".$params['retailer_id']."_161"); 
                $txn_id = $bill_data['tran_id'];
                
                if(!isset($bill_data['bill_amount']) && empty($bill_data['bill_amount'])){
                    $bill_data['tran_id'] = $txn_id;
                    $bill_data = $this->Shop->getMemcache("bbps_".$prod_id."_".$params['accountNumber']."_".$params['retailer_id']."_8");   
                }
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", "GET cca memcache key : bbps_".$prod_id."_".$params['accountNumber']."_".$params['retailer_id']."_161 Data :". json_encode($bill_data));
                if($bill_data['vendor_id'] != 161){
                    $this->ccaUtilityBillFetch($params, $prod_id);
                }
                $url = CCAVENUE_RECHARGE_URL . 'extBillPayCntrl/billPayRequest/xml';

                $slaveObj = ClassRegistry::init('User');
//
//                $res = $slaveObj->query("SELECT agent_id FROM bbps_agents WHERE retailer_id = '{$params['retailer_id']}' AND vendor_id = 161");
//                !$res && $res = $slaveObj->query("SELECT agent_id FROM bbps_agents ORDER BY RAND() LIMIT 1");
//                $agent_id = $res[0]['bbps_agents']['agent_id'];
                $agent_id = $this->getAgentId($params['retailer_id'],161);
                $biller_id = $this->getccaBillerId($params, $prod_id);

                Configure::load('billers');
                $billers = Configure::read('billers');
                $dynamic_fields = $billers[$prod_id]['fields'];
                $quickpay = $billers[$prod_id]['bill_fetch'] === false?'Y':'N';

                $biller_data = $this->ccaBillerInfo($biller_id);
                $xml = '<?xml version="1.0" encoding="UTF-8"?>
<billPaymentRequest>
    <agentId>'.$agent_id.'</agentId>';
                $xml .= ($biller_data['billerAdhoc'] === false || empty($biller_data['billerAdhoc'])) ? "<billerAdhoc>false</billerAdhoc>" :   "<billerAdhoc>true</billerAdhoc>";

                $xml .= '<agentDeviceInfo>
        <ip>'.$_SERVER['SERVER_ADDR'].'</ip>
        <initChannel>AGT</initChannel>
        <mac>02:42:AC:11:00:03</mac>
    </agentDeviceInfo>
    <customerInfo>
        <customerMobile>'.$params['mobileNumber'].'</customerMobile>
        <customerEmail></customerEmail>
        <customerAdhaar></customerAdhaar>
        <customerPan></customerPan>
    </customerInfo>
    <billerId>'.$biller_id.'</billerId>
   <inputParams>';

    if(in_array($prod_id,array(147,148)))
    {
        $exceptions = array('amount');
    }
    else
    {
        $exceptions = array('mobileNumber','amount');
    }
    if(isset($this->cca_exceptions[$prod_id])){
        $exceptions = array_merge($exceptions,$this->cca_exceptions[$prod_id]);
    }

   foreach($dynamic_fields as $d_f) {
       if(!in_array($d_f['param'], $exceptions)) {
    $xml .= '<input>
                <paramName>'.$d_f['label'].'</paramName>
                <paramValue>'.$params[$d_f['param']].'</paramValue>
             </input>';
       }
   }
    $bill_amt = (isset($bill_data['amount']) && !empty($bill_data['amount'])) ? $bill_data['amount'] : $params['amount'];
      $xml .= '</inputParams>
   <billerResponse>
        <billAmount>'.($bill_amt*100).'</billAmount>
        <billDate>'.$bill_data['bill_date'].'</billDate>
        <billNumber>'.$bill_data['bill_number'].'</billNumber>
        <billPeriod>'.$bill_data['bill_period'].'</billPeriod>
        <customerName>'.htmlspecialchars($bill_data['customer_name']).'</customerName>
        <dueDate>'.$bill_data['due_date'].'</dueDate>';

      if(isset($bill_data['amount_options'])) {

          $xml .= '<amountOptions>';
          foreach($bill_data['amount_options'] as $idx=>$a_i) {
              $xml .= '<option>
                                <amountName>'.$idx.'</amountName>
                                <amountValue>'.$a_i.'</amountValue>
                             </option>';
          }
          $xml .= '</amountOptions>';
      }

    $xml .= '</billerResponse>';

    if(isset($bill_data['additional_info'])) {
            $xml .= '<additionalInfo>';
            foreach($bill_data['additional_info'] as $idx=>$a_i) {
                    $xml .= '<info>
                                <infoName>'.$idx.'</infoName>
                                <infoValue>'.$a_i.'</infoValue>
                             </info>';
            }
            $xml .= '</additionalInfo>';
    }
    if(isset($bill_data['additional_info']['Early Payment Date']) && date('Y-m-d') <= date('Y-m-d', strtotime($bill_data['additional_info']['Early Payment Date']))){
        $amount = ($bill_data['amount_options']['Early Payment Amount'])/100;

        $amount_tag = '<amountTag>Early Payment Amount</amountTag><value>'.($amount*100).'</value>';
    }
    elseif(isset($bill_data['amount_options']['Late Payment Amount']) && $bill_data['due_date'] < date('Y-m-d')){
        $amount = ($bill_data['amount_options']['Late Payment Amount'])/100;
        $amount_tag = '<amountTag>Late Payment Amount</amountTag><value>'.($amount*100).'</value>';
    }else{
        $amount = $bill_data['bill_amount'];
        $amount_tag = '';
    }
//    $amount = (isset($amount) && !empty($amount)) ? $amount : $params['amount'];
    $xml .= '<amountInfo>
        <amount>'.($params['amount']*100).'</amount>
        <currency>356</currency>
        <custConvFee>0</custConvFee>
        <amountTags>'.$amount_tag.'</amountTags>
    </amountInfo>
    <paymentMethod>
        <paymentMode>Cash</paymentMode>
        <quickPay>'.$quickpay.'</quickPay>
        <splitPay>N</splitPay>
    </paymentMethod>
    <paymentInfo>
        <info>
            <infoName>Remarks</infoName>
            <infoValue>Received</infoValue>
        </info>
    </paymentInfo>
</billPaymentRequest>';

                $bill_data['bbps'] && $out = $this->General->ccavenueApi($url, $xml, $bill_data['tran_id']);

                if(!$bill_data['bbps']){
                    return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'30', 'vendor_response'=>'Older app request. Switched to diff vendor');
                }
                if(!$out['success'] && $out['timeout']){
                    return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
                }

                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", date('Y-m-d H:i:s') . " :: * CCAvenues Utility Bill Payment * :: Request : ".json_encode($xml));
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", date('Y-m-d H:i:s') . " :: * CCAvenues Utility Bill Payment * :: Response : ".json_encode($out));

                $resp = $out['output']['ExtBillPayResponse'];
                
                $bill_data_insert = array('customer_name'=>$bill_data['customer_name'],'bill_number'=>$bill_data['bill_number'],'bill_date'=>$bill_data['bill_date'],'due_date'=>$bill_data['due_date'],'bill_period'=>$bill_data['bill_period'],'bill_amount'=>$bill_data['bill_amount'],'amount_paid'=>$params['amount']);
                
                $slaveObj->query("INSERT INTO bbps_txnid_mapping (fetch_txnid,payment_txnid,cca_id,vendor_id,bill_data) VALUES ('{$bill_data['tran_id']}','$tran_id','{$resp['txnRefId']}',161,'".addslashes(json_encode($bill_data_insert))."')");

                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", date('Y-m-d H:i:s') . " :: INSERT query : INSERT INTO bbps_txnid_mapping (fetch_txnid,payment_txnid,cca_id) VALUES ('{$bill_data['tran_id']}','$tran_id','{$resp['txnRefId']}')");

                if($resp['responseCode'] == '000') {

                    return array('status'=>'success', 'code'=>13, 'description'=>$this->Shop->errors(13), 'tranId'=>$resp['txnRefId'], 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'13','vendor_response'=>$resp['responseReason']);

                } else if($resp['responseCode'] == '001' || $resp['responseCode'] == '204' || $resp['responseCode'] == '200' || $resp['responseCode'] == 'BPR005') {
                        /*if(!in_array($resp['errorInfo']['error']['errorCode'], array('E019','E020','E021','E049','E050','E024','E075','E076','E030','E031','E069','E039'))) {
                                $resp['errorInfo']['error']['errorMessage'] = 'Something went wrong. Contact Pay1 Customercare';
                        }*/

                        return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$resp['errorInfo']['error']['errorMessage']);

                } else {
                    return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>'', 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$resp['errorInfo']['error']['errorMessage']);
                }
        }

        /*function ccaUtilityBillQuickPayment($trans_id, $params, $prod_id) {

                $url = CCAVENUE_RECHARGE_URL . 'extBillPayCntrl/billPayRequest/xml';

                $slaveObj = ClassRegistry::init('Slaves');
                $res = $slaveObj->query("SELECT agent_id FROM bbps_agents WHERE retailer_id = '{$params['retailer_id']}'");
                !$res && $res = $slaveObj->query("SELECT agent_id FROM bbps_agents ORDER BY RAND() LIMIT 1");
                $agent_id = $res[0]['bbps_agents']['agent_id'];
                $biller_id = $this->getccaBillerId($params, $prod_id);

                Configure::load('billers');
                $billers = Configure::read('billers');
                $dynamic_fields = $billers[$prod_id]['fields'];

                $xml = '<?xml version="1.0" encoding="UTF-8"?>
<billPaymentRequest>
    <agentId>'.$agent_id.'</agentId>
    <billerAdhoc>true</billerAdhoc>
    <agentDeviceInfo>
        <ip>192.168.2.73</ip>
        <initChannel>AGT</initChannel>
        <mac></mac>
    </agentDeviceInfo>
    <customerInfo>
        <customerMobile>'.$params['mobileNumber'].'</customerMobile>
        <customerEmail></customerEmail>
        <customerAdhaar></customerAdhaar>
        <customerPan></customerPan>
    </customerInfo>
    <billerId>'.$biller_id.'</billerId>
   <inputParams>';
    $exceptions = array('mobileNumber','amount');
    if(isset($this->cca_exceptions[$prod_id])){
        $exceptions = array_merge($exceptions, $this->cca_exceptions[$prod_id]);
    }
    foreach($dynamic_fields as $d_f) {
        if(!in_array($d_f['param'], $exceptions)) {
            $xml .= '<input>
                <paramName>'.$d_f['label'].'</paramName>
                <paramValue>'.$params[$d_f['param']].'</paramValue>
             </input>';
        }
    }

   $xml .= '</inputParams>
   <amountInfo>
       <amount>'.($params['amount'] * 100).'</amount>
       <currency>356</currency>
       <custConvFee>0</custConvFee>
       <amountTags></amountTags>
   </amountInfo>
   <paymentMethod>
       <paymentMode>Cash</paymentMode>
       <quickPay>Y</quickPay>
       <splitPay>N</splitPay>
   </paymentMethod>
   <paymentInfo>
       <info>
           <infoName>Remarks</infoName>
           <infoValue>Received</infoValue>
       </info>
   </paymentInfo>
</billPaymentRequest>';

                $out = $this->General->ccavenueApi($url, $xml, $trans_id);

                if(!$out['success'] && $out['timeout']){
                        return array('status'=>'failure', 'description'=>'Not able to connect to server');
                }

                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", "* CCAvenues Utility Bill Payment request * :: Response : ".json_encode($out));

                $resp = $out['output']['ExtBillPayResponse'];
                if($resp['responseCode'] == '000') {
                        return array('status'=>'success','operator_id'=>'','pinRefNo'=>'','tranId'=>$resp['txnRefId'],
                            'code'=>$resp['responseCode'],'description'=>$resp['responseReason'],'internal_error_code'=>'13',
                            'vendor_response'=>$resp);
                } else if($resp['responseCode'] == '001') {
                        return array('status'=>'failure', 'operator_id'=>'', 'pinRefNo'=>'', 'code'=>$resp['errorInfo']['error']['errorCode'],
                            'description'=>$resp['errorInfo']['error']['errorMessage'], 'internal_error_code'=>'14', 'vendor_response'=>$resp);
                } else {
                        return array('status'=>'pending', 'operator_id'=>'', 'pinRefNo'=>'', 'code'=>$resp['errorInfo']['error']['errorCode'],
                            'description'=>$resp['errorInfo']['error']['errorMessage'], 'internal_error_code'=>'15', 'vendor_response'=>$resp);
                }
        }*/

        function ccaTranStatus($trans_id, $date = null, $ref_id = null) {

                $dbObj = ClassRegistry::init('Slaves');
                $cca_txnid = $dbObj->query("SELECT * FROM bbps_txnid_mapping WHERE payment_txnid = '$trans_id'");

                $url = CCAVENUE_RECHARGE_URL . 'transactionStatus/fetchInfo/xml';

                $xml = '<?xml version="1.0" encoding="UTF-8"?>
<transactionStatusReq>
 <trackType>REQUEST_ID</trackType>
 <trackValue>'.$cca_txnid[0]['bbps_txnid_mapping']['fetch_txnid'].'</trackValue>
</transactionStatusReq>';

                $out = $this->General->ccavenueApi($url, $xml);

                if(!$out['success'] && $out['timeout']){
                        return array('status'=>'failure', 'description'=>'Not able to connect to server');
                }

                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", date('Y-m-d H:i:s') . " :: * Utility Bill Txn Status * :: Request : ".$xml);
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", date('Y-m-d H:i:s') . " :: * Utility Bill Txn Status * :: Response : ".json_encode($out));

                $resp = $out['output']['transactionStatusResp'];
                $vendor_ref_id = '';
                $opr_id = '';

                if(isset($resp['txnList'])){
                    $vendor_ref_id = $resp['txnList']['txnReferenceId'];
                }
                if($resp['responseCode'] == '000' && (strtolower($resp['txnList']['txnStatus']) !== 'failure')) {
                    $ret = array('status'=>'success', 'status-code'=>$resp['responseCode'], 'description'=>$resp['responseReason'], 'tranId'=>$cca_txnid[0]['bbps_txnid_mapping']['fetch_txnid'], 'vendor_id'=>$vendor_ref_id, 'operator_id'=>$opr_id);
                } else if(($resp['responseCode'] == '001' && strtolower($resp['responseReason']) != 'no transaction found') || (strtolower($resp['txnList']['txnStatus']) == 'failure') || $resp['errorInfo']['error']['errorCode'] == 'V4008') {
                    $ret = array('status'=>'failure', 'status-code'=>$resp['responseCode'], 'description'=>$resp['responseReason'], 'tranId'=>$cca_txnid[0]['bbps_txnid_mapping']['fetch_txnid'], 'vendor_id'=>$vendor_ref_id, 'operator_id'=>$opr_id);
                } else {
                    $ret = array('status'=>'pending', 'status-code'=>'', 'description'=>'', 'tranId'=>$cca_txnid[0]['bbps_txnid_mapping']['fetch_txnid'], 'vendor_id'=>$vendor_ref_id, 'operator_id'=>$opr_id);
                }

                return $ret;
        }

        function ccaBalance() {

                $url = CCAVENUE_RECHARGE_URL . 'enquireDeposit/fetchDetails/xml';

                $xml = '<?xml version="1.0" encoding="UTF-8"?>
<depositDetailsRequest>
   <fromDate>'.date('Y-m-d', strtotime('-1 day')).'</fromDate>
   <toDate>'.date('Y-m-d').'</toDate>
   <transType>DR</transType>
   <agents />
</depositDetailsRequest>';

                $out = $this->General->ccavenueApi($url, $xml);

                if( ! $out['success']){
                    return array('balance'=>'');
                }

                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", date('Y-m-d H:i:s') . " :: * Utility Balance Check * :: Request : ".$xml);
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", date('Y-m-d H:i:s') . " :: * Utility Balance Check * :: Response : ".json_encode($out));

                $balance = $out['output']['DepositEnquiryResponse']['currentBalance'];
                return array('balance'=>$balance);
        }


        function ccaComplaintRegistration($params) {

                $url = CCAVENUE_RECHARGE_URL . 'extComplaints/register/xml';

                $xml = '<?xml version="1.0" encoding="UTF-8"?>
<complaintRegistrationReq>
   <complaintType>'.$params['complaint_type'].'</complaintType>';
   $xml .= $params['complaint_type'] == 'Service' ? '<participationType>'.$params['participation_type'].'</participationType><servReason>'.$params['serv_reason'].'</servReason>' : '<txnRefId>'.$params['txn_id'].'</txnRefId><complaintDisposition>'.$params['complaint_disposition'].'</complaintDisposition>';
   if($params['complaint_type'] == 'Service') {
           $xml .= $params['participation_type'] == 'AGENT' ? '<agentId>'.$params['agent_id'].'</agentId>' : '<billerId>'.$params['biller_id'].'</billerId>';
   }
   $xml .= '<complaintDesc>'.$params['complaint_description'].'</complaintDesc>
</complaintRegistrationReq>';

                $out = $this->General->ccavenueApi($url, $xml);

                if(!$out['success'] && $out['timeout']) {
                        return array('status'=>'failure', 'description'=>'Not able to connect to server');
                }

                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", date('Y-m-d H:i:s') . " :: * Utility Complain Registration * :: Request : ".$xml);
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", date('Y-m-d H:i:s') . " :: * Utility Complain Registration * :: Response : ".json_encode($out));

                if($out['output']['complaintRegistrationResp']['responseCode'] == '000') {
                        $out['output']['complaintRegistrationResp']['complaint_reason'] = $params['serv_reason'] == '' ? $params['complaint_disposition'] : $params['serv_reason'];
                        return array('status'=>'success', 'description'=>$out['output']['complaintRegistrationResp']);
                } else {
                        return array('status'=>'failure');
                }
        }

        function ccaComplaintTracking($params) {

                $url = CCAVENUE_RECHARGE_URL . 'extComplaints/track/xml';

                $xml = '<?xml version="1.0" encoding="UTF-8"?>
<complaintTrackingReq>
 <complaintType>Transaction</complaintType>
 <complaintId>'.$params['bbps_complaint_id'].'</complaintId>
</complaintTrackingReq>';

                $out = $this->General->ccavenueApi($url, $xml);

                if(!$out['success'] && $out['timeout']){
                        return array('status'=>'failure', 'description'=>'Not able to connect to server');
                }

                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", date('Y-m-d H:i:s') . " :: * Utility Complaint Tracking * :: Request : ".$xml);
                $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ccavenue.txt", date('Y-m-d H:i:s') . " :: * Utility Complaint Tracking * :: Response : ".json_encode($out));

                if($out['output']['complaintTrackingResp']['responseCode'] == '000') {
                        return array('status'=>'success', 'description'=>$out['output']['complaintTrackingResp']);
                } else {
                        return array('status'=>'failure', 'code'=>$out['output']['complaintTrackingResp']['errorInfo']['error']['errorCode'], 'description'=>$out['output']['complaintTrackingResp']['errorInfo']['error']['errorMessage']);
                }
        }

    function simpleDthRecharge($transId, $params, $prodId){
        $mobileNo = $params['subId'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['simple'];

        $vendor = 69;

        $url = SIMPLE_RECHARGE_URL;

        $out = $this->General->simpleApi($url, array('username'=>SIMPLE_USERID, 'pwd'=>SIMPLE_PASSWORD, 'circlecode'=>'*', 'operatorcode'=>$provider, 'number'=>$mobileNo, 'amount'=>$amount, 'orderid'=>$transId));

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/simple.txt", "*dth Recharge*: Input=> $transId<br/>$out");

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/simple.txt", "*dth Recharge*: Input=> $transId<br/>$out");

        $res = explode("#", $out);
        $txnId = "";
        if(count($res) == 1){
            $res = explode("::", $out);
        }
        else{
            $txnId = trim($res[0]);
        }
        $opr_id =  ! empty($res[2]) ? $res[2] : "";

        if((trim($res[1]) == 'Failure')){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$res[2]);
        }
        else if((trim($res[1]) == 'Success')){
            return array('status'=>'success', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
    }

    function manglamMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];

        $amount = intval($params['amount']);

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['manglam'];

        $vendor = 87;

        $url = MANGALAM_RECHARGE_URL;

        $message = $provider . $mobileNo . "A" . $amount . "REF" . $transId;

        $out = $this->General->manglamApi($url, array('login_id'=>MANGALAM_USERID, 'transaction_password'=>MANGALAM_PASSWORD, 'message'=>$message, 'response_type'=>'CSV'));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];

        $res = explode(",", $out);

        $txnId =  ! empty($res[1]) ? $res[1] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manglam.txt", "*Mob Recharge*: Input=> $transId<br/>$out<br/>params=>" . json_encode($params));

        if($res[0] == 'Failure'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$res[5]);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$res[5]);
        }
    }

    function manglamDthRecharge($transId, $params, $prodId){
        $mobileNo = $params['subId'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['manglam'];

        $vendor = 87;

        $url = MANGALAM_RECHARGE_URL;

        $message = $provider . $mobileNo . "A" . $amount . "REF" . $transId;

        $out = $this->General->manglamApi($url, array('login_id'=>MANGALAM_USERID, 'transaction_password'=>MANGALAM_PASSWORD, 'message'=>$message, 'response_type'=>'CSV'));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];

        $res = explode(",", $out);

        $txnId =  ! empty($res[1]) ? $res[1] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manglam.txt", "*dth Recharge*: Input=> $transId<br/>$out<br/>params=>" . json_encode($params));

        if($res[0] == 'Failure'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$res[5]);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$res[5]);
        }
    }

    function manglamBalance(){
        $vendor_id = 87;

        $url = MANGALAM_BAL_URL;

        $out = $this->General->manglamApi($url, array('login_id'=>MANGALAM_USERID, 'transaction_password'=>MANGALAM_PASSWORD));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = trim($out['output']);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manglam.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");

        return array('balance'=>$out);
    }

    function manglamTranStatus($transId, $date = null, $refId = null){
        $url = MANGALAM_TRANS_URL;
        $operator_id = $vendor_id = "";
        $out = $this->General->manglamApi($url, array('login_id'=>MANGALAM_USERID, 'transaction_password'=>MANGALAM_PASSWORD, 'CLIENTID'=>$transId, 'response_type'=>'XML'));

        $out = $this->General->xml2array($out['output']);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manglam.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":manglamTranStatus: " . json_encode($out));

        if($out['RESPONSE']['STATUS'] == 'Success'){
            $ret = array('status'=>'success', 'status-code'=>'success', 'description'=>$out, 'tranId'=>$transId,'vendor_id'=>$out['RESPONSE']['TRID'],'operator_id'=>$out['RESPONSE']['MESSAGE']);
        }
        elseif($out['RESPONSE']['STATUS'] == 'Failure' && $out['RESPONSE']['MESSAGE'] == 'Transaction Not Found' && intVal((time() - strtotime($date)) / 86400) >= 2){
            $ret = array('status'=>'status not available. Kindly check on vendor\'s panel', 'status-code'=>'pending', 'description'=>$out['RESPONSE']['MESSAGE'], 'tranId'=>$transId, 'vendor_id'=>'', 'operator_id'=>'');
        }
        else if($out['RESPONSE']['STATUS'] == 'Failure'){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'description'=>$out['RESPONSE']['MESSAGE'], 'tranId'=>$transId,'vendor_id'=>$out['RESPONSE']['TRID'],'operator_id'=>'');
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'description'=>$out, 'tranId'=>$transId,'vendor_id'=>$out['RESPONSE']['TRID'],'operator_id'=>'');
        }

        return $ret;
    }

    function bulkMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['bulk'];

        $vendor = 105;

        $url = BULK_RECHARGE_URL;

        $out = $this->General->bulkApi($url, array('username'=>BULK_USERID, 'password'=>BULK_PASSWORD, 'number'=>$mobileNo, 'circlecode'=>'12', 'operatorcode'=>$provider, 'amount'=>$amount, 'clientid'=>$transId));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];

        $res = explode(",", $out);

        $clinetId = (isset($res[1]) &&  ! empty($res[1])) ? $res[1] : "";
        $status = (isset($res[0]) &&  ! empty($res[0])) ? $res[0] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/bulk.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($res) . "<br/>params=>" . json_encode($params));

        if($status == 'failure'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clinetId, 'internal_error_code'=>'30', 'vendor_response'=>$res[5]);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clinetId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$res[5]);
        }
    }

    function bulkBalance(){
        $vendor_id = 105;

        $url = BULK_BAL_URL;

        $out = $this->General->bulkApi($url, array('username'=>BULK_USERID, 'password'=>BULK_PASSWORD));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $bal = explode(',', $out['output']);

        $out = (isset($bal[1]) &&  ! empty($bal[1])) ? $bal[1] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/bulk.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");

        $bal = trim($out);

        return array('balance'=>$bal);
    }

    function bulkTranStatus($transId, $date = null, $refId = null){
        $url = BULK_TRANS_URL;

        $out = $this->General->bulkApi($url, array('username'=>BULK_USERID, 'password'=>BULK_PASSWORD, 'rcid'=>$transId));

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/bulk.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":bulkTranStatus: " . json_encode($out['output']));

        $response = explode(",", $out['output']);

        $vendor_id = $response[1];
        if($response[0] == 'success'){
            $operator_id = $response[2];
            $ret = array('status'=>'success', 'status-code'=>'success', 'description'=>$out['output'], 'tranId'=>$transId, 'vendor_id'=>$vendor_id,'operator_id'=>$operator_id);
        }
        else if($response[0] == 'failure'){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'description'=>$out['output'], 'tranId'=>$transId, 'vendor_id'=>$vendor_id,'operator_id'=>'');
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'description'=>$out['output'], 'tranId'=>$transId, 'vendor_id'=>$vendor_id,'operator_id'=>'');
        }

        return $ret;
    }

    function bulkDthRecharge($transId, $params, $prodId){
        $mobileNo = $params['subId'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['bulk'];

        $vendor = 105;

        $url = BULK_RECHARGE_URL;

        $out = $this->General->bulkApi($url, array('username'=>BULK_USERID, 'password'=>BULK_PASSWORD, 'number'=>$mobileNo, 'circlecode'=>'12', 'operatorcode'=>$provider, 'amount'=>$amount, 'clientid'=>$transId));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];

        $res = explode(",", $out);

        $clinetId = (isset($res[1]) &&  ! empty($res[1])) ? $res[1] : "";
        $status = (isset($res[0]) &&  ! empty($res[0])) ? $res[0] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/bulk.txt", "*dth Recharge*: Input=> $clinetId<br/>" . json_encode($res) . "<br/>params=>" . json_encode($params));

        if($status == 'failure'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clinetId, 'internal_error_code'=>'30', 'vendor_response'=>$res[5]);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clinetId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$res[5]);
        }
    }

    function bimcoMobRecharge($transId, $params, $prodId, $res = null){
        if(is_string($params)){

            $string = parse_str($params);

            $params['mobileNumber'] = $mobileNumber;
            $params['operator'] = $operator;
            $params['type'] = $type;
            $params['amount'] = $amount;
        }

        $mobileNo = $params['mobileNumber'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['bimco'];

        $vendor = 123;

        $url = BIMCO_RECHARGE_URL;

        $out = $this->General->bimcoApi($url, array('apikey'=>BIMCO_APIKEY, 'username'=>BIMCO_USERNAME, 'mobile'=>$mobileNo, 'amount'=>$amount, 'opcode'=>$provider, 'Merchantrefno'=>$transId, 'ServiceType'=>'MR'), "bimcoMobRecharge", array("0"=>$transId, "1"=>$params, "2"=>$prodId, "3"=>'-1'));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        if($out['success']){

            $out = $out['output'];

            $res = explode(" ", $out);

            $status = (isset($res[1]) &&  ! empty($res[1])) ? $res[1] : "";

            $clientId = (isset($res[0]) &&  ! empty($res[0])) ? $res[0] : "";

            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/bimco.txt", "*Mob Recharge*: Input=> $clientId<br/>" . json_encode($res) . "<br/>params=>" . json_encode($params));

            if($status == 'FAILURE'){
                $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$res[0]);
            }
            else{
                $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'internal_error_code'=>'15', 'vendor_response'=>$res[0]);
            }

            if($res == '-1'){
                echo json_encode($ret);
            }
            else{

                return $ret;
            }
        }
        else{

            $ret = json_decode($out, True);
            return $ret;
        }
    }

    function bimcoBalance(){
        $vendor_id = 123;

        $url = BIMCO_BAL_URL;

        $out = $this->General->bimcoApi($url, array('apikey'=>BIMCO_APIKEY, 'username'=>BIMCO_USERNAME), "bimcoBalance", array('0'=>$panel, '1'=> - 1));

        if(isset($out['success'])){

            $bal = explode(':', $out['output']);

            $out = (isset($bal[1]) &&  ! empty($bal[1])) ? $bal[1] : "";

            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/bimco.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");

            $out = trim($out);
        }
        else{
            $output = json_decode($out, True);

            $out = $output['balance'];
        }

        return array('balance'=>$out);
    }

    function bimcoTranStatus($transId, $date = null, $refId = null){
        $url = BIMCO_TRANS_URL;

        $out = $this->General->bimcoApi($url, array('apikey'=>BIMCO_APIKEY, 'username'=>BIMCO_USERNAME, 'Merchantrefno'=>$transId, 'ServiceType'=>'MR'), "bimcoTranStatus", array('0'=>$transId, '1'=> - 1));

        if(isset($out['success'])){

            $out = explode("||", $out['output']);

            $status = explode("=", $out[2]);
            $operatorId = explode("=", $out[1]);
            $vendorId = explode("=", $out[3]);

            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/bimco.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":bimcoTranStatus: " . json_encode($out));

            if($status[1] == 'SUCCESS'){
                $ret = array('status'=>'success', 'status-code'=>'success', 'description'=>$out, 'tranId'=>$transId, 'operator_id'=>$operatorId[1], "vendor_id"=>$vendorId[1]);
            }
            else if($status[1] == 'FAILURE'){
                $ret = array('status'=>'failure', 'status-code'=>'failure', 'description'=>$out, 'tranId'=>$transId, 'operator_id'=>$operatorId[1], "vendor_id"=>$vendorId[1]);
            }
            else{
                $ret = array('status'=>'pending', 'status-code'=>'pending', 'description'=>$out, 'tranId'=>$transId, 'operator_id'=>$operatorId[1], "vendor_id"=>$vendorId[1]);
            }
            if($date == '-1'){
                // echo json_encode($ret);
            }
            else{
                return $ret;
            }
        }
        else{
            $ret = json_decode($out, true);
            return $ret;
        }
    }

    function rajanMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['rajan'];

        $vendor = 125;

        $url = RAJAN_RECHARGE_URL;

        $out = $this->General->rajanApi($url, array('uname'=>RAJAN_USERNAME, 'password'=>RAJAN_PASSWORD, 'provider'=>$provider, 'mobno'=>$mobileNo, 'amount'=>$amount, 'uid'=>$transId));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $this->General->xml2array($out['output']);

        $status = isset($out['response']['status']) ? $out['response']['status'] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rajan.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "s<br/>params=>" . json_encode($params));

        if($status == 'FAILURE'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$out['response']['status']);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>'', 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out['response']['status']);
        }
    }

    public function rajanDthRecharge($transId, $params, $prodId){
        $mobileNo = $params['subId'];

        $amount = intval($params['amount']);
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['rajan'];

        $vendor = 125;

        $url = RAJAN_RECHARGE_URL;

        $out = $this->General->rajanApi($url, array('uname'=>RAJAN_USERNAME, 'password'=>RAJAN_PASSWORD, 'provider'=>$provider, 'mobno'=>$mobileNo, 'amount'=>$amount, 'uid'=>$transId));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $this->General->xml2array($out['output']);
        $status = isset($out['response']['status']) ? $out['response']['status'] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rajan.txt", "*Dth Recharge*: Input=> $transId<br/>" . json_encode($out) . "s<br/>params=>" . json_encode($params));

        if($status == 'FAILURE'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'pinRefNo'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$out['response']['status']);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>'', 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out['response']['status']);
        }
        $this->autoRender = false;
    }

    function rajanBalance(){
        $vendor_id = 125;

        $url = RAJAN_BAL_URL;
        $out = $this->General->rajanApi($url, array('uname'=>RAJAN_USERNAME, 'password'=>RAJAN_PASSWORD));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = $this->General->xml2array($out['output']);

        $out = isset($out['response']['balance']) ? $out['response']['balance'] : "";
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rajan.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");

        $bal = trim($out);

        return array('balance'=>$bal);
    }

    function rajanTranStatus($transId, $date = null, $refId = null){
        $vendor_id = 125;

        $url = RAJAN_TRANS_URL;

        $out = $this->General->rajanApi($url, array('uname'=>RAJAN_USERNAME, 'password'=>RAJAN_PASSWORD, 'uid'=>$transId));

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rajan.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":rajanTranStatus: " . json_encode($out['output']));

        $response = $this->General->xml2array($out['output']);

        $status = $response['response']['status'];

        if($status == 'SUCCESS'){
            $ret = array('status'=>'success', 'status-code'=>'success', 'description'=>$response, 'tranId'=>$transId);
        }
        else if($response[0] == 'FAILURE'){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'description'=>$response, 'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'description'=>$response, 'tranId'=>$transId);
        }

        return $ret;
    }

    function payApiMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['payrecharge'];

        $vendor = 129;

        $url = PAY_RECHARGE_URL;

        $out = $this->General->payRechargeApi($url, array('mobile'=>$mobileNo, 'amt'=>$amount, 'ctxnid'=>$transId, 'operator'=>$provider, 'type'=>'Recharge', 'rt'=>1, 'cid'=>PAY_RECHARGE_ID));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = isset($out['output']) ? explode("|", $out['output']) : "";

        $status = isset($out[0]) ? $out[0] : "";

        $txnId = isset($out[3]) ? $out[3] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/payrecharge.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "s<br/>params=>" . json_encode($params));

        if($status == 1){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
    }

    function payApiBalance(){
        $vendor_id = 129;

        $url = PAY_RECHARGE_URL . "getbalance.php";
        $out = $this->General->payRechargeApi($url, array('cname'=>'Pay', 'cmob'=>'3333333333'));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = isset($out['output']) ? $out['output'] : "";

        $out = explode("|", $out);

        $out = $out['1'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/payrecharge.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");

        $bal = trim($out);

        return array('balance'=>$bal);
    }

    function payApiTranStatus($transId, $date = null, $refId = null){
        $vendor_id = 129;

        $url = PAY_RECHARGE_URL . "status.php";

        $out = $this->General->payRechargeApi($url, array('cid'=>PAY_RECHARGE_ID, 'ctxnid'=>$transId));

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/payrecharge.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":payRecahrgeTranStatus: " . json_encode($out['output']));

        $res = explode("|", $out['output']);

        $status = $res[0];

        if($status == '0'){
            $ret = array('status'=>'success', 'status-code'=>'success', 'operator_id'=>$res[1], 'description'=>$out['output'], 'tranId'=>$transId);
        }
        else if($status == '503' || $status == '505'){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'operator_id'=>$res[1], 'description'=>$out['output'], 'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'operator_id'=>$res[1], 'description'=>$out['output'], 'tranId'=>$transId);
        }

        return $ret;
    }

    function ShivaIdeaMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['shivaidea'];
        $vendor = 132;
        $url = SHIVA_IDEA_RECHARGE_URL;

        $out = $this->General->ShivaIdea($url, array('uid'=>SHIVA_IDEA_USERID, 'pwd'=>SHIVA_IDEA_PASSWORD, 'mobileno'=>$mobileNo, 'amt'=>$amount, 'rcode'=>$provider, 'transid'=>$transId));

        $status = isset($out['output']) ? $out['output'] : "";

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ShivaIdea.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "s<br/>params=>" . json_encode($params));

        if(in_array($status, array('1201', '1202', '1203', '1204', '1205', '1206', '1207', '1208', '1209', '1210', '1211', '1212'))){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'operator_id'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>'', 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
    }

    function ShivaIdeaBalance(){
        $vendor_id = 132;
        $url = SHIVA_IDEA_BALANCE_URL;
        $out = $this->General->ShivaIdea($url, array('uid'=>SHIVA_IDEA_USERID, 'pwd'=>SHIVA_IDEA_PASSWORD));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = isset($out['output']) ? $out['output'] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ShivaIdea.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");

        $bal = trim($out);

        return array('balance'=>$bal);
    }

    function ShivaIdeaTranStatus($transId, $date = null, $refId = null){
        $vendor_id = 132;

        $url = SHIVA_IDEA_STATUS_URL;

        $out = $this->General->ShivaIdea($url, array('uid'=>SHIVA_IDEA_USERID, 'pwd'=>SHIVA_IDEA_PASSWORD, 'transid'=>$transId));

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ShivaIdea.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":ShivaIdeaTranStatus: " . json_encode($out['output']));

        $out = isset($out['output']) ? $out['output'] : "";

        $out = explode(';', $out);

        $status = explode("=", $out[1]);

        $operatorId = explode("=", $out[2]);

        if($status[1] == 'SUCCESS'){
            $ret = array('status'=>'success', 'status-code'=>'success', 'operator_id'=>$operatorId[1], 'description'=>$out['output'], 'tranId'=>$transId);
        }
        else if($status['1'] == 'FAILURE' || $status['1'] == 'CANCEL'){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'operator_id'=>$operatorId[1], 'description'=>$out['output'], 'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'operator_id'=>$operatorId[1], 'description'=>$out['output'], 'tranId'=>$transId);
        }

        return $ret;
    }

    function indiCoreMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['indicore'];

        $vendor = 134;

        $url = INDICORE_RECHARGE_URL;

        $out = $this->General->IndicoreRechargeApi($url, array('signature'=>INDICORE_KEY, 'rnum'=>$mobileNo, 'ramt'=>$amount, 'optr'=>$provider, 'type'=>'REC', 'cack'=>$transId));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = array_key_exists('output', $out) ? explode("|", $out['output']) : "";

        $status = array_key_exists(0, $out) ? $out[0] : "";
        $remark = array_key_exists(1, $out) ? $out[1] : "";

        $clientId = '';

        if($status == '200'){
            $clientId = array_key_exists(3, $out) ? $out[3] : "";
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/indicorerecharge.txt", "*Inside recharge*: Input=> $transId<br/>" . json_encode($out) . "s<br/>params=>" . json_encode($params));
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/indicorerecharge.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "s<br/>params=>" . json_encode($params));

        if(in_array($status, array('406', '403', '503', '500'))){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$remark);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$remark);
        }
    }

    function indiCoreDthRecharge($transId, $params, $prodId){
        $params = array('subId'=>'','type'=>'flexi','operator'=>'3','amount'=>10);
        $mobileNo = $params['subId'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['indicore'];

        $vendor = 134;

        $url = INDICORE_RECHARGE_URL;

        $out = $this->General->IndicoreRechargeApi($url, array('signature'=>INDICORE_KEY, 'rnum'=>$mobileNo, 'ramt'=>$amount, 'optr'=>$provider, 'type'=>'DTH', 'cack'=>$transId));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = array_key_exists('output', $out) ? explode("|", $out['output']) : "";

        $status = array_key_exists(0, $out) ? $out[0] : "";
        $remark = array_key_exists(1, $out) ? $out[1] : "";

        $clientId = '';

        if($status == '200'){
            $clientId = array_key_exists(3, $out) ? $out[3] : "";
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/indicorerecharge.txt", "*Inside DTH recharge*: Input=> $transId<br/>" . json_encode($out) . "s<br/>params=>" . json_encode($params));
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/indicorerecharge.txt", "*DTH Recharge*: Input=> $transId<br/>" . json_encode($out) . "s<br/>params=>" . json_encode($params));

        if(in_array($status, array('406', '403', '503', '500'))){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$remark);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$remark);
        }
    }

    function indiCoreBalance(){
        $vendor_id = 134;

        $url = INDICORE_RECHARGE_URL;
        $out = $this->General->IndicoreRechargeApi($url, array('signature'=>INDICORE_KEY));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = array_key_exists('output', $out) ? $out['output'] : "";
        $out = explode("|", $out);
        $out = trim($out['1']);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/indicorerecharge.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");

        return array('balance'=>$out);
    }

    function indiCoreTranStatus($transId, $date = null, $refId = null){
        $vendor_id = 134;

        $url = INDICORE_RECHARGE_URL;

        $out = $this->General->IndicoreRechargeApi($url, array('signature'=>INDICORE_KEY, 'cack'=>$transId));

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/indicorerecharge.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":indicorerechargeTranStatus: " . json_encode($out['output']));

        $res = explode("|", $out['output']);

        $status = $res[0];

        $oprId = '';

        $statusResponse = $res[1];

        if($status == '200' &&  ! in_array($statusResponse, array('FAILED', 'PROCESSED', 'PENDING', 'SUSPENSE', 'NOT FOUND'))){
            $oprId = $statusResponse;
            $ret = array('status'=>'success', 'status-code'=>'success', 'description'=>$out['output'], 'tranId'=>$transId, 'operator_id'=>$oprId);
        }
        else if(($status == '200' || $status == '400' || $status == '404') && in_array($statusResponse, array('FAILED', 'NOT FOUND'))){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'description'=>$out['output'], 'tranId'=>$transId, 'operator_id'=>$oprId);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'description'=>$out['output'], 'tranId'=>$transId, 'operator_id'=>$oprId);
        }
        return $ret;
    }

    /*
     * API integration for Swamiraj by swapnilT 14 NOV 2016
     */
    function swamirajMobRecharge($transId, $params, $prodId = null, $res = null){
        $prodId = empty($prodId) ? 0 : $prodId;
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['swamiraj'];

        $vendor = SWAMIRAJ_VENDOR_ID;
        $url = SWAMIRAJ_RECHARGE_URL;

        $out = $this->General->swamirajApi($url, array('username'=>SWAMIRAJ_UN, 'pwd'=>SWAMIRAJ_PWD, 'operator'=>$provider, 'number'=>$params['mobileNumber'], 'amt'=>$params['amount'], 'clientid'=>$transId));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $result = explode("|", $out);

        $status = (isset($result[0]) &&  ! empty($result[0])) ? $result[0] : "";
        $clientId = (isset($result[1]) && ( ! empty($result[1])) && 'ERROR' != trim($result[0])) ? trim($result[1]) : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/swamiraj.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($result) . "<br/>params=>" . json_encode($params));

        if('ERROR' == trim($status)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
        return $ret;
    }

    function swamirajDthRecharge($transId, $params, $prodId = null, $res = null){
        $prodId = empty($prodId) ? 0 : $prodId;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['swamiraj'];

        $vendor = SWAMIRAJ_VENDOR_ID;
        $url = SWAMIRAJ_RECHARGE_URL;

        $out = $this->General->swamirajApi($url, array('username'=>SWAMIRAJ_UN, 'pwd'=>SWAMIRAJ_PWD, 'operator'=>$provider, 'number'=>$params['subId'], 'amt'=>$params['amount'], 'clientid'=>$transId));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $result = explode("|", $out);

        $status = (isset($result[0]) &&  ! empty($result[0])) ? $result[0] : "";
        $clientId = (isset($result[1]) && ( ! empty($result[1])) && 'ERROR' != trim($result[0])) ? trim($result[1]) : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/swamiraj.txt", "*DTH Recharge*: Input=> $transId<br/>" . json_encode($result) . "<br/>params=>" . json_encode($params));

        if('ERROR' == trim($status)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
        return $ret;
    }

    function swamirajBalance(){
        $vendor_id = SWAMIRAJ_VENDOR_ID;

        $url = SWAMIRAJ_BALANCE_URL;
        $out = $this->General->swamirajApi($url, array('username'=>SWAMIRAJ_UN, 'pwd'=>SWAMIRAJ_PWD));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $arr_val = explode('|', $out['output']);
        $bal = trim($arr_val[1]);
        $bal = strpos($bal, ',') ? str_replace(',', '', $bal) : $bal;
        $bal = floor($bal);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/swamiraj.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");

        return array('balance'=>$bal);
    }

    function swamirajTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;
        $date = empty($date) ? 0 : $date;
        $url = SWAMIRAJ_STATUS_URL;
        $out = $this->General->swamirajApi($url, array('username'=>SWAMIRAJ_UN, 'pwd'=>SWAMIRAJ_PWD, 'clientid'=>$transId));

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/swamiraj.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":swamiraTrjanStatus: " . json_encode($out['output']));

        if(strpos($out['output'], '&')){
            $responce = explode("&", $out['output']);
            $string = parse_str($out['output']);
        }
        else if( ! empty($out['output'])){
            $out = explode("|", $out['output']);
            $status = trim($out[0]);
            $status = ('ERROR' == $status || 'FAILED' == $status || 'REVERT' == $status || 'SUCCESS' == $status || 'SUSPENSE' == $status) ? $status : '';
            $responce = $out;
        }
        else if(empty($out['output'])){
            $responce = 'pending';
            $status = 'PENDING';
        }

        if('SUCCESS' == trim($status)){
            $ret = array('status'=>'success', 'status-code'=>'success','operator_id'=>$transid,'vendor_id'=>$refid, 'description'=>$responce, 'tranId'=>$transId);
        }
        else if('ERROR' == trim($status) || 'FAILED' == trim($status) || 'REVERT' == trim($status) || 'SUSPENSE' == trim($status)){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'description'=>$responce, 'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'description'=>$responce, 'tranId'=>$transId);
        }
        return $ret;
    }

    /*
     * API integration for maxrecharge by swapnilT 24 JAN 2017
     */
    function maxrechargMobRecharge($transId, $params, $prodId = null){
        $prodId = empty($prodId) ? 0 : $prodId;
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['maxrecharge'];

        $vendor = MAXRECHARGE_VENDOR_ID;

        $url = MAXRECHARGE_RECHARGE_URL;

        $out = $this->General->maxrechargeApi($url, array('username'=>MAXRECHARGE_UN, 'pwd'=>MAXRECHARGE_PWD, 'xmlpwd'=>MAXRECHARGE_XMLPWD, 'operator'=>$provider, 'number'=>$params['mobileNumber'], 'amt'=>$params['amount'], 'clientid'=>$transId, 'type'=>'recharge'));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = $out['output'];
        $status = (isset($out['TRNSTATUS']) || array_key_exists('TRNSTATUS', $out)) ? 'SUCCESS' : 'ERROR';

        $clientId = (isset($out['TRNID']) || array_key_exists('TRNID', $out)) ? $out['TRNID'] : 0;

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/maxrecharge.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "<br/>params=>" . json_encode($params));

        if('ERROR' == trim($status)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$out['STATUSTEXT']);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out['STATUSTEXT']);
        }

        return $ret;
    }

    function maxrechargBalance(){
        $vendor_id = MAXRECHARGE_VENDOR_ID;
        $url = MAXRECHARGE_BALANCE_URL;
        $out = $this->General->maxrechargeApi($url, array('username'=>MAXRECHARGE_UN, 'pwd'=>MAXRECHARGE_PWD, 'xmlpwd'=>MAXRECHARGE_XMLPWD, 'type'=>'balance'));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = $out['output'];
        if(array_key_exists('STCODE', $out) && $out['STCODE'] == 0){
            $bal = floor($out['STMSG']);
            $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/maxrecharge.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
            $bal = trim($bal);
        }

        return array('balance'=>$bal);
    }

    function maxrechargTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;
        $date = empty($date) ? 0 : $date;
        $url = MAXRECHARGE_STATUS_URL;
        $out = $this->General->maxrechargeApi($url, array('username'=>MAXRECHARGE_UN, 'pwd'=>MAXRECHARGE_PWD, 'xmlpwd'=>MAXRECHARGE_XMLPWD, 'clientid'=>$transId, 'type'=>'status'));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/maxrecharge.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":maxrechargTrjanStatus: " . json_encode($out['output']));
        $out = $out['output'];
        if(array_key_exists('TRNSTATUS', $out) && $out['TRNSTATUS'] == 1){
            $status = 'SUCCESS';
        }
        else if(array_key_exists('TRNSTATUS', $out) && in_array($out['TRNSTATUS'], array(4, 6))){
            $status = 'PENDING';
        }
        else{
            $status = 'ERROR';
        }
        if(empty($out)) $status = 'PENDING';
        if('SUCCESS' == trim($status)){
            $ret = array('status'=>'success', 'status-code'=>'success', 'description'=>$out['STATUSTEXT'], 'tranId'=>$transId);
        }
        else if('ERROR' == trim($status)){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'description'=>$out['STATUSTEXT'], 'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'description'=>$out['STATUSTEXT'], 'tranId'=>$transId);
        }

        return $ret;
    }

    function pay1JioMobRecharge($transId, $params, $prodId = null){
        $userData = $this->Jio->pay1JioUserInfo($params['mobileNumber']);
        $vendor = PAY1JIO_VENDORID;

        if($userData['status'] == 'fail'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'operator_id'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$userData['description']);
        }
        $retData = $this->Jio->activeJioRetailer($params['amount'], $userData['zone']);
        $retailerId = $retData['userId'];

        if(empty($retailerId)){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'operator_id'=>'', 'internal_error_code'=>'30', 'vendor_response'=>'No active sim with balance - ' . $userData['zone']);
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1jio.txt",date('Y-m-d H:i:s').":mobRecharge: $transId::".json_encode($params));

        $output = $this->Jio->pay1JioRecharge($userData, $retData, $params['mobileNumber'], $params['amount']);
        $this->General->logData($_SERVER['DOCUMENT_ROOT']."/logs/pay1jio.txt",date('Y-m-d H:i:s').":mobRecharge: $transId::".json_encode($params)." output ".json_encode($output));
        if($output['status'] == 'success'){
            $clientId = $output['txnId'];
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'13', 'vendor_response'=>'success');
        }
        else if($output['status'] == 'fail'){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>'', 'operator_id'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$output['description']);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>'', 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>'Request timed out');
        }

        if($output['status'] != 'fail'){
            $this->Jio->updateJioRetailerBalance($retailerId, $params['amount']);
        }
        $this->Jio->unlockJioRetailer($retailerId);
        return $ret;
    }

    function pay1JioBalance(){
        $vendor_id = PAY1JIO_VENDORID;

        $bal = $this->Jio->getJioRetailerBalance();
        $this->General->logData("pay1jio.txt", "Pay1jio balance : " . $bal);
        return array('balance'=>$bal);
    }

    function IndiaRecMobRecharge($transId, $params, $prodId = null){
        $prodId = empty($prodId) ? 0 : $prodId;

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['IndiaRec'];

        $vendor = INDIARECHARGE_VENDOR_ID;
        $url = INDIARECHARGE_URL;

        $mobile = (isset($params['mobileNumber']) && !empty($params['mobileNumber'])) ? $params['mobileNumber'] : "";

        $amount = (isset($params['amount']) && !empty($params['amount'])) ? $params['amount'] : "";


        $message = 'RR' . ' ' . $provider . ' ' . $mobile . ' ' . $amount . ' ' . INDIARECHARGE_PIN;

        $out = $this->General->IndiaRechargeApi($url, array('Mob'=>INDIARECHARGE_MOB, 'message'=>$message, 'myTxId'=>$transId, 'source'=>'API', 'circle'=>'1'));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = $out['output'];
        $out = explode(',', $out);

        $clientId = explode(':', $out[2]);
        $clientId = $clientId[1];

        //$oprId = explode(':', $out[7]);
        //$oprId = $oprId[1];
        $oprId = "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/Indiarecharge.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "<br/>params=>" . json_encode($params));

        if(trim($out[0]) == 'Your Request have been Processed' || trim($out[0]) == 'Your Request has been Success'){
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$oprId, 'internal_error_code'=>'15', 'vendor_response'=>$out[0]);
        }
        else{
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$oprId, 'internal_error_code'=>'30', 'vendor_response'=>$out[0]);
        }

        return $ret;
    }

    function IndiaRecTranStatus($transId, $date = null, $refId = null){
        $vendor_id = INDIARECHARGE_VENDOR_ID;
        $url = INDIARECHARGE_URL;

        $message = 'mytxid' . ' ' . $transId . ' ' . INDIARECHARGE_PIN;

        $out = $this->General->IndiaRechargeApi($url, array('Mob'=>INDIARECHARGE_MOB, 'message'=>$message, 'Source'=>'API'));

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/indiarerecharge.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":indiarerechargeTranStatus: " . json_encode($out['output']));

        $res = $out['output'];
        $status = explode(',', $res);
        $transStatus = explode(':', $status[0]);

        if($transStatus[1] == 'success' || trim($transStatus[1]) == 'Processed'){
            $arr = explode('*',$status[1]);
            $oprId = $arr[3];
            $ret = array('status'=>'success', 'status-code'=>'success', 'description'=>$status[1], 'tranId'=>$transId, 'operator_id'=>$oprId);
        }
        else if($transStatus[1] == 'fail'){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'description'=>$status[1], 'tranId'=>$transId, 'operator_id'=>'');
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'description'=>$status[1], 'tranId'=>$transId, 'operator_id'=>'');
        }

        return $ret;
    }

    function IndiaRecBalance(){
        $vendor_id = INDIARECHARGE_VENDOR_ID;
        $message = 'BAL' . ' ' . INDIARECHARGE_PIN;
        $url = INDIARECHARGE_URL;

        $out = $this->General->IndiaRechargeApi($url, array('Mob'=>INDIARECHARGE_MOB, 'message'=>$message, 'Source'=>'API'));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = isset($out['output']) ? $out['output'] : "";
        $out = explode(' ', $out);
        $bal = trim($out[3]);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/indiarerecharge.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance'=>$bal);
    }

    /*
     * API integration for Ambika by swapnilT 08 MAY 2017
     */
    function ambikaMobRecharge($transId, $params, $prodId = null, $res = null){
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['ambika'];

        $vendor = AMBIKA_VENDOR_ID;
        $url = AMBIKA_RECHARGE_URL;

        $out = $this->General->ambikaApi($url, array('userid'=>AMBIKA_UN, 'pass'=>AMBIKA_PWD, 'opt'=>$provider, 'mob'=>$params['mobileNumber'], 'amt'=>$params['amount'], 'agentid'=>$transId,'fmt'=>'Json'));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ambika.txt",date('Y-m-d H:i:s') . " *Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "<br/>params=>" . json_encode($params));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $out = json_decode($out,true);
        $status = strtolower($out['STATUS']);
        $clientId = $out['RPID'];
        $opr_id = $out['OPID'];
        $msg = $out['MSG'];

        if('success' == trim($status)){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId,'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$msg);
        }
        elseif('failed' == trim($status)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId,'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$msg);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId,'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$msg);
        }
        return $ret;
    }

    function ambikaDthRecharge($transId, $params, $prodId = null, $res = null){

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['ambika'];

        $vendor = AMBIKA_VENDOR_ID;
        $url = AMBIKA_RECHARGE_URL;

        $out = $this->General->ambikaApi($url, array('userid'=>AMBIKA_UN, 'pass'=>AMBIKA_PWD, 'opt'=>$provider, 'mob'=>$params['subId'], 'amt'=>$params['amount'], 'agentid'=>$transId,'fmt'=>'Json'));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ambika.txt",date('Y-m-d H:i:s') . " *Dth Recharge*: Input=> $transId<br/>" . json_encode($out) . "<br/>params=>" . json_encode($params));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $out = json_decode($out,true);
        $status = strtolower($out['STATUS']);
        $clientId = $out['RPID'];
        $opr_id = $out['OPID'];
        $msg = $out['MSG'];

        if('success' == trim($status)){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId,'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$msg);
        }
        elseif('failed' == trim($status)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId,'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$msg);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId,'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$msg);
        }
        return $ret;
    }

    function ambikaBalance(){
        $vendor_id = AMBIKA_VENDOR_ID;

        $url = AMBIKA_BALANCE_URL;
        $out = $this->General->ambikaApi($url, array('userid'=>AMBIKA_UN, 'pass'=>AMBIKA_PWD,'Get'=>'CB','fmt'=>'Json'));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ambika.txt", date('Y-m-d H:i:s') . ":Balance Check:  ".json_encode($out));

        $out = $out['output'];
        $out = json_decode($out,true);
        if(strtolower($out['MSG']) == 'remaining balance'){
            $bal = floor($out['STATUS']);
        }
        else{
            return array('balance'=>'');
        }

        return array('balance'=>$bal);
    }

    function ambikaTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;
        $url = AMBIKA_STATUS_URL;
//        $out = $this->General->ambikaApi($url, array('userid'=>AMBIKA_UN, 'pass'=>AMBIKA_PWD, 'csagentid'=>$transId,'fmt'=>'Json'));
//        $params = array('userid'=>AMBIKA_UN, 'pass'=>AMBIKA_PWD, 'csrpid'=>$refId,'fmt'=>'Json');
        $params = array('userid'=>AMBIKA_UN, 'pass'=>AMBIKA_PWD, 'csagentid'=>$transId,'fmt'=>'Json');
        $out = $this->General->ambikaApi($url, $params);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ambika.txt", date('Y-m-d H:i:s') . "transId=>$transId params=>" .json_encode($params). ":ambikaTranStatus: " . json_encode($out));
        $out = $out['output'];
        $out = json_decode($out,true);
        $operatorId = $out['OPID'];
        $vendor_id = $out['RPID'];
        if('success' == trim(strtolower($out['STATUS']))){
            $ret = array('status'=>'success', 'status-code'=>'success', 'vendor_id'=>$vendor_id,'operator_id'=>$operatorId,'description'=> json_encode($out), 'tranId'=>$transId);
        }
        else if('failed' == trim(strtolower($out['STATUS'])) && (strtolower($out['MSG']) != 'incomplete or invalid parameteres')){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'vendor_id'=>$vendor_id,'operator_id'=>$operatorId, 'description'=>json_encode($out), 'tranId'=>$transId);
        }
        else if('refund' == trim(strtolower($out['STATUS']))){
            $ret = array('status'=>'refund', 'status-code'=>'refund', 'vendor_id'=>$vendor_id,'operator_id'=>$operatorId, 'description'=>json_encode($out), 'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'vendor_id'=>$vendor_id,'operator_id'=>$operatorId, 'description'=>json_encode($out), 'tranId'=>$transId);
        }
        return $ret;
    }

    function ambikaroamMobRecharge($transId, $params, $prodId = null, $res = null){
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['ambikaroam'];

        $vendor = 155;
        $url = AMBIKA_RECHARGE_URL;

        $out = $this->General->ambikaApi($url, array('userid'=>AMBIKAROAM_UN, 'pass'=>AMBIKAROAM_PWD, 'opt'=>$provider, 'mob'=>$params['mobileNumber'], 'amt'=>$params['amount'], 'agentid'=>$transId,'fmt'=>'Json'));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ambika.txt",date('Y-m-d H:i:s') . " *Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "<br/>params=>" . json_encode($params));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $out = json_decode($out,true);
        $status = strtolower($out['STATUS']);
        $clientId = $out['RPID'];
        $opr_id = $out['OPID'];
        $msg = $out['MSG'];

        if('success' == trim($status)){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId,'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$msg);
        }
        elseif('failed' == trim($status)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId,'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$msg);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId,'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$msg);
        }
        return $ret;
    }

    function ambikaroamBalance(){
        $vendor_id = 155;

        $url = AMBIKA_BALANCE_URL;
        $out = $this->General->ambikaApi($url, array('userid'=>AMBIKAROAM_UN, 'pass'=>AMBIKAROAM_PWD,'Get'=>'CB','fmt'=>'Json'));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ambika.txt", date('Y-m-d H:i:s') . ":Balance Check:  ".json_encode($out));

        $out = $out['output'];
        $out = json_decode($out,true);
        if(strtolower($out['MSG']) == 'remaining balance'){
            $bal = floor($out['STATUS']);
        }
        else{
            return array('balance'=>'');
        }

        return array('balance'=>$bal);
    }

    function ambikaroamTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;
        $url = AMBIKA_STATUS_URL;
//        $out = $this->General->ambikaApi($url, array('userid'=>AMBIKAROAM_UN, 'pass'=>AMBIKAROAM_PWD, 'csagentid'=>$transId,'fmt'=>'Json'));
//        $params = array('userid'=>AMBIKAROAM_UN, 'pass'=>AMBIKAROAM_PWD, 'csrpid'=>$refId,'fmt'=>'Json');
        $params = array('userid'=>AMBIKAROAM_UN, 'pass'=>AMBIKAROAM_PWD, 'csagentid'=>$transId,'fmt'=>'Json');
        $out = $this->General->ambikaApi($url, $params);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ambika.txt", date('Y-m-d H:i:s') . "transId=>$transId params=>" .json_encode($params). ":ambikaroamTranStatus: " . json_encode($out));
        $out = $out['output'];
        $out = json_decode($out,true);
        $operatorId = $out['OPID'];
        $vendor_id = $out['RPID'];
        if('success' == trim(strtolower($out['STATUS']))){
            $ret = array('status'=>'success', 'status-code'=>'success', 'vendor_id'=>$vendor_id,'operator_id'=>$operatorId,'description'=> json_encode($out), 'tranId'=>$transId);
        }
        else if('failed' == trim(strtolower($out['STATUS'])) && (strtolower($out['MSG']) != 'incomplete or invalid parameteres')){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'vendor_id'=>$vendor_id,'operator_id'=>$operatorId, 'description'=>json_encode($out), 'tranId'=>$transId);
        }
        else if('refund' == trim(strtolower($out['STATUS']))){
            $ret = array('status'=>'refund', 'status-code'=>'refund', 'vendor_id'=>$vendor_id,'operator_id'=>$operatorId, 'description'=>json_encode($out), 'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'vendor_id'=>$vendor_id,'operator_id'=>$operatorId, 'description'=>json_encode($out), 'tranId'=>$transId);
        }
        return $ret;
    }


    /*
     * API integration for A1 recharge by swapnilT 31 MAY 2017
     */
    function a1recMobRecharge($transId, $params, $prodId = null, $res = null){
        $vendor_id = A1REC_VENDOR_ID;
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['a1rec'];
        $token = A1REC_TOKEN;
        $url = A1REC_RECHARGE_URL;

        $out = $this->General->a1recApi($url, array('apiToken'=>$token, 'op'=>$provider, 'mn'=>$params['mobileNumber'], 'amt'=>$params['amount'], 'reqid'=>$transId,'field1'=>'','field2'=>''));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/a1rec.txt",date('Y-m-d H:i:s') . " *Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "<br/>params=>" . json_encode($params));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = $out['output'];
        $out=  json_encode($this->General->xml2array($out));
        $out = json_decode($out,true);
        $out =  $out['Result'];
        $remark = $out['remark'];

        $status = strtolower($out['status']);
        $clientId = $out['apirefid'];
        $opr_id = '';

        if('success' == trim($status)){
            $opr_id = $out['field1'];
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$remark);
        }
        elseif(in_array(trim($status),array('failed','frequent'))){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$remark);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$remark);
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/a1rec.txt",date('Y-m-d H:i:s') . "recharge response ".json_encode($ret));

        return $ret;
    }

    function a1recDthRecharge($transId, $params, $prodId = null, $res = null){
        $vendor_id = A1REC_VENDOR_ID;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['a1rec'];
        $token = A1REC_TOKEN;
        $url = A1REC_RECHARGE_URL;

        $out = $this->General->a1recApi($url, array('apiToken'=>$token, 'op'=>$provider, 'mn'=>$params['subId'], 'amt'=>$params['amount'], 'reqid'=>$transId,'field1'=>'','field2'=>''));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/a1rec.txt",date('Y-m-d H:i:s') . " *DTH Recharge*: Input=> $transId<br/>" . json_encode($out) . "<br/>params=>" . json_encode($params));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = $out['output'];
        $out=  json_encode($this->General->xml2array($out));
        $out = json_decode($out,true);
        $out =  $out['Result'];
        $remark = $out['remark'];

        $status = strtolower($out['status']);
        $clientId = $out['apirefid'];
        $opr_id = '';

        if('success' == trim($status)){
            $opr_id = $out['field1'];
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$remark);
        }
        elseif(in_array(trim($status),array('failed','frequent'))){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$remark);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$remark);
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/a1rec.txt",date('Y-m-d H:i:s') . "Dth recharge response ".json_encode($ret));

        return $ret;
    }

    function a1recBalance(){
        $vendor_id = A1REC_VENDOR_ID;
        $token = A1REC_TOKEN;
        $url = A1REC_BALANCE_URL;

        $out = $this->General->a1recApi($url, array('apiToken'=>$token));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/a1rec.txt", date('Y-m-d H:i:s') . ":Balance Check:  ".json_encode($out));

        $out = $out['output'];
        $out=  json_encode($this->General->xml2array($out));
        $out = json_decode($out,true);
        if(array_key_exists('string', $out) && !empty($out['string']))
        $bal = floor($out['string']);
        else
            return array('balance'=>'');

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/a1rec.txt",date('Y-m-d H:i:s') . "balance response ".$bal);
        return array('balance'=>$bal);
    }

    function a1recTranStatus($transId, $date = null, $refId = null){
        $vendor_id = A1REC_VENDOR_ID;
        $token = A1REC_TOKEN;
        $url = A1REC_STATUS_URL;
        $out = $this->General->a1recApi($url, array('apiToken'=>$token, 'reqid'=>$transId));

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/a1rec.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":a1recTranStatus: " . json_encode($out));

        $out = $out['output'];
        $out=  json_encode($this->General->xml2array($out));
        $out = json_decode($out,true);
        $out =  $out['Result'];

        $operatorId = $out['field1'];
        $vendor_id = $refId;
        if('success' == trim(strtolower($out['status']))){
            $ret = array('status'=>'success', 'status-code'=>'success', 'vendor_id'=>$vendor_id,'operator_id'=>$operatorId,'description'=> json_encode($out), 'tranId'=>$transId);
        }
        else if('failed' == trim(strtolower($out['status'])) || 'refund'== trim(strtolower($out['status']))){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'vendor_id'=>$vendor_id,'operator_id'=>$operatorId, 'description'=>json_encode($out), 'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'vendor_id'=>$vendor_id,'operator_id'=>$operatorId, 'description'=>json_encode($out), 'tranId'=>$transId);
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/a1rec.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":a1recTranStatus:final " . json_encode($ret));
        return $ret;
    }

    /*
     * API integration for RechargeUnlimited recharge by swapnilT 06 Jun 2017
     */
    function unrecMobRecharge($transId, $params, $prodId = null, $res = null){
        $vendor_id = UNREC_VENDOR_ID;
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['unrec'];

        $url = UNREC_RECHARGE_URL;

        $out = $this->General->unrecApi($url, array('Username'=>UNREC_USERID,'Password'=>UNREC_PASSWORD, 'Opcode'=>$provider, 'Number'=>$params['mobileNumber'], 'Amount'=>$params['amount'], 'ReferenceId'=>$transId,'Circle'=>'GJ'));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/unrec.txt",date('Y-m-d H:i:s') . " *Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "<br/>params=>" . json_encode($params));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = $out['output'];
        $out = json_decode($out,true);

        $status = strtolower($out['Status']);
        $clientId = $out['TransactionId'];
        if('failure' == trim(strtolower($status))){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/unrec.txt",date('Y-m-d H:i:s') . "recharge response ".json_encode($ret));

        return $ret;
    }

    function unrecDthRecharge($transId, $params, $prodId = null, $res = null){
        $vendor_id = UNREC_VENDOR_ID;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['unrec'];

        $url = UNREC_RECHARGE_URL;

        $out = $this->General->unrecApi($url, array('Username'=>UNREC_USERID,'Password'=>UNREC_PASSWORD, 'Opcode'=>$provider, 'Number'=>$params['subId'], 'Amount'=>$params['amount'], 'ReferenceId'=>$transId,'Circle'=>'GJ'));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/unrec.txt",date('Y-m-d H:i:s') . " *Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "<br/>params=>" . json_encode($params));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = $out['output'];
        $out = json_decode($out,true);

        $status = strtolower($out['Status']);
        $clientId = $out['TransactionId'];
        if('failure' == trim(strtolower($status))){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/unrec.txt",date('Y-m-d H:i:s') . "recharge response ".json_encode($ret));

        return $ret;
    }

    function unrecBalance(){
        $vendor_id = UNREC_VENDOR_ID;
        $url = UNREC_BALANCE_URL;

        $out = $this->General->unrecApi($url, array('Username'=>UNREC_USERID,'Password'=>UNREC_PASSWORD));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/unrec.txt", date('Y-m-d H:i:s') . ":Balance Check:  ".json_encode($out));

        $out = $out['output'];
        $out = json_decode($out,true);
        if($out['Status']=='Success')
        $bal = floor($out['Value']);
        else
            return array('balance'=>'');

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/unrec.txt",date('Y-m-d H:i:s') . "balance response ".$bal);
        return array('balance'=>$bal);
    }

    function unrecTranStatus($transId, $date = null, $refId = null){
        $vendor_id = UNREC_VENDOR_ID;
        $url = UNREC_STATUS_URL;

        if(!empty($refId))
        {
            $params = array('Username'=>UNREC_USERID,'Password'=>UNREC_PASSWORD, 'ReferenceId'=>$transId,'TransactionId'=>$refId);
        }
        else
        {
            $params = array('Username'=>UNREC_USERID,'Password'=>UNREC_PASSWORD, 'ReferenceId'=>$transId);
        }

        $out = $this->General->unrecApi($url, $params);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/unrec.txt", date('Y-m-d H:i:s') . "transId=>$transId params=>".json_encode($params).":unrecTranStatus: " . json_encode($out));

        $out = $out['output'];
        $out = json_decode($out,true);

        $operatorId = $out['SPTransactionId'];
        $client_id = $out['TransactionId'];

        if('success' == trim(strtolower($out['Status']))){
            $ret = array('status'=>'success', 'status-code'=>'success', 'vendor_id'=>$client_id,'operator_id'=>$operatorId,'description'=> json_encode($out), 'tranId'=>$transId);
        }
        else if('failure' == trim(strtolower($out['Status'])) || 'refunded'== trim(strtolower($out['Status']))){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'vendor_id'=>$client_id,'operator_id'=>$operatorId, 'description'=>json_encode($out), 'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'vendor_id'=>$client_id,'operator_id'=>$operatorId, 'description'=>json_encode($out), 'tranId'=>$transId);
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/unrec.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":unrecTranStatus:final " . json_encode($ret));
        return $ret;
    }

    function ctswalletMobRecharge($transId, $params, $prodId = null, $res = null){
        $prodId = empty($prodId) ? 0 : $prodId;
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['ctswallet'];

        $vendor = CTSWALLET_VENDOR_ID;
        $url = CTSWALLET_URL;

        $out = $this->General->ctswalletApi($url, array('AccessKey'=>CTSWALLET_ACCESS_KEY,'username'=>CTSWALLET_UN,'action'=>'mod_MobileRecharge_postEnterRechargeDetails', 'options_operators'=>$provider, 'options_mobilenumber'=>$params['mobileNumber'], 'options_amount'=>$params['amount'], 'clientid'=>$transId));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ctswallet.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "<br/>params=>" . json_encode($params));
        $out = $out['output'];
        $status = ($out['response']['errorcode']==0)?'Success':'Error';
        $clientId = $out['response']['TID'];

        if(isset($out['response']) && $status == 'Error'){
//        if(isset($out['response']) && strtolower($out['response']['errortext']) == 'sucess'){
            $description = isset($out['response']['errortext']) ? trim($out['response']['errortext']) : "";
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
//            $description = "NA";
//            if($description == 'NA') $description = "Request accepted";
//            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$description, 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'13', 'vendor_response'=>'success');
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'13', 'vendor_response'=>'success');
        }

        return $ret;
    }

    function ctswalletDthRecharge($transId, $params, $prodId){
        $mobileNo = $params['subId'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['ctswallet'];

        $vendor = '';

        $url = CTSWALLET_URL;

        $out = $this->General->ctswalletApi($url, array('AccessKey'=>CTSWALLET_ACCESS_KEY,'username'=>CTSWALLET_UN, 'action'=>'mod_DTHRecharge_postEnterRechargeDetails','amount'=>$amount, 'DTHnumber'=>$mobileNo, 'operator'=>$provider));

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ctswallet.txt", "*dth Recharge*: Input=> $transId<br/>$out");

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ctswallet.txt", "*dth Recharge*: Input=> $transId<br/>$out");
        $status = ($out['response']['errorcode']==0)?'Success':'Error';
        $clientId = $out['response']['TID'];

        if(isset($out['response']) && $status == 'Error'){
            $description = isset($out['response']['errortext']) ? trim($out['response']['errortext']) : "";
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
            $description = "NA";
            if($description == 'NA') $description = "Request accepted";
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'13', 'vendor_response'=>'success');
        }

        return $ret;
    }

    function ctswalletBalance(){
        $vendor_id = CTSWALLET_VENDOR_ID;

        $url = CTSWALLET_URL;
        $out = $this->General->ctswalletApi($url, array('AccessKey'=>CTSWALLET_ACCESS_KEY, 'username'=>CTSWALLET_UN));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = $out['output'];

        $bal = isset($out['response']['TotalAccountBalance'])?floor($out['response']['TotalAccountBalance']):'';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ctswallet.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");

        return array('balance'=>$bal);
    }

    function ctswalletTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;
        $url = CTSWALLET_URL;
        $out = $this->General->ctswalletApi($url, array('AccessKey'=>CTSWALLET_ACCESS_KEY,'username'=>CTSWALLET_UN, 'action'=>'mod_MobileRecharge_getTransactionStatus','tid'=>$refId));

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ctswallet.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":ctswalletTranStatus: " . json_encode($out['output']));
        $out = $out['output'];
        $status = ($out['response']['TansactionStatusCode']==0)?'Success':'Error';

        if($status == 'Success'){
            $ret = array('status'=>$out['response']['TansactionStatus'], 'status-code'=>$out['response']['TansactionStatusCode'],'operator_id'=>$out['response']['MobileNumber'],'vendor_id'=>$refid, 'description'=>$out, 'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>$out['response']['TansactionStatus'], 'status-code'=>$out['response']['TansactionStatusCode'], 'description'=>$out, 'tranId'=>$transId);
        }
        return $ret;
    }

    function ctswalletBillPayment($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];
        $amount = intval($params['amount']);

        $provider = $this->mapping['billPayment'][$params['operator']][$params['type']]['ctswallet'];
        $vendor = '';

        $url = CTSWALLET_RECHARGE_URL;
        $out = $this->General->ctswalletApi($url, array('AccessKey'=>'knr@knr@29','username'=>CTSWALLET_UN,'action'=>'mod_PostPaidMobileRecharge_postEnterRechargeDetails', 'options_operators'=>$provider, 'options_mobilenumber'=>$params['mobileNumber'], 'options_amount'=>$params['amount'], 'clientid'=>$transId));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ctswallet.txt", "*Bill Payment*: Input=> $transId<br/>" . json_encode($out));

        $status = ($out['response']['errorcode']==0)?'Success':'Error';
        $clientId = $out['response']['TID'];

        if(isset($out['response']) && $status == 'Error'){
            $description = isset($out['response']['errortext']) ? trim($out['response']['errortext']) : "";
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
            $description = "NA";
            if($description == 'NA') $description = "Request accepted";
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'13', 'vendor_response'=>'success');
        }
        return $ret;
    }

    function speedrecMobRecharge($transId, $params, $prodId = null){
        $prodId = empty($prodId) ? 0 : $prodId;
        $rec_type = 0;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
            $rec_type = 1;
        }

        $opr_code = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['speedrec'];
        $vendor = SPEEDREC_VENDOR_ID;
        $url = SPEEDREC_RECHARGE_URL;

        $out = $this->General->speedrecApi($url, array('UserName'=>SPEEDREC_UN, 'Password'=>SPEEDREC_PWD, 'MobileToRecharge'=>$params['mobileNumber'], 'APIAccountRef'=>$transId,'Amount'=>$params['amount'],'RechargeVia'=>4,'TypeOfRecharge'=>$rec_type,'OperatorCode'=>$opr_code,'type'=>'recharge'));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = json_decode($out['output'],TRUE);
        $status = $out['DoRechargeResponse']['Code'];
        $clientId = $out['DoRechargeResponse']['ReferenceID'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/speedrec.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "<br/>params=>" . json_encode($params));

        if(!empty($status) && in_array($status , array('SR101','SR102','SR103','SR104','SR105','SR106','SR107','SR108','SR109','SR110','SR111','SR112'))){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$out['DoRechargeResponse']['Message']);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out['DoRechargeResponse']['Message']);
        }

        return $ret;
    }

    function speedrecDthRecharge($transId, $params, $prodId = null){
        $prodId = empty($prodId) ? 0 : $prodId;
        $rec_type = 0;

        $opr_code = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['speedrec'];
        $vendor = SPEEDREC_VENDOR_ID;
        $url = SPEEDREC_RECHARGE_URL;

        $out = $this->General->speedrecApi($url, array('UserName'=>SPEEDREC_UN, 'Password'=>SPEEDREC_PWD, 'MobileToRecharge'=>$params['subId'], 'APIAccountRef'=>$transId,'Amount'=>$params['amount'],'RechargeVia'=>4,'TypeOfRecharge'=>$rec_type,'OperatorCode'=>$opr_code,'type'=>'recharge'));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = json_decode($out['output'],TRUE);
        $status = $out['DoRechargeResponse']['Code'];
        $clientId = $out['DoRechargeResponse']['ReferenceID'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/speedrec.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "<br/>params=>" . json_encode($params));

        if(!empty($status) && in_array($status , array('SR101','SR102','SR103','SR104','SR105','SR106','SR107','SR108','SR109','SR110','SR111','SR112'))){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$out['DoRechargeResponse']['Message']);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out['DoRechargeResponse']['Message']);
        }

        return $ret;
    }

    function speedrecBalance(){
        $vendor_id = SPEEDREC_VENDOR_ID;
        $url = SPEEDREC_BALANCE_URL;
        $out = $this->General->speedrecApi($url, array('UserName'=>SPEEDREC_UN, 'Password'=>SPEEDREC_PWD,'type'=>'balance'));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = $out['output'];
        $out = json_decode($out,TRUE);

        if(array_key_exists('Code', $out['MyBalanceResponse']) && strtolower($out['MyBalanceResponse']['Code']) == 'sr113'){
            $bal = trim(end(explode(":",$out['MyBalanceResponse']['Message'])));
        }
        else
        {
            $bal = '';
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/speedrec.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");

        return array('balance'=>$bal);
    }

    function speedrecTranStatus($transId, $date = null, $refId = null) {
        $transId = empty($transId) ? 0 : $transId;
        $url = SPEEDREC_STATUS_URL;
        $out = $this->General->speedrecApi($url, array('UserName' => SPEEDREC_UN, 'Password' => SPEEDREC_PWD, 'APIAccountRef' => $transId,'type'=>'status'));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/speedrec.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":speedrecTranStatus: " . $out['output']);

        $out = json_decode($out['output'], TRUE);
        $clientId = !empty($out['CheckStatusResponse']['ReferenceID'])?$out['CheckStatusResponse']['ReferenceID']:'';
        $status = isset($out['CheckStatusResponse']['Code'])?$out['CheckStatusResponse']['Code']:'';
//        $txnId = trim(end(explode(":",$out['CheckStatusResponse']['Message'])));
        $txnId = trim(reset(explode(",",end(explode(":",$out['CheckStatusResponse']['Message'])))));

        if($status == 1) {
            $ret = array('status' => 'success', 'status-code' => 'success', 'vendor_id'=>$clientId,'operator_id'=>$txnId, 'description' => $out['CheckStatusResponse']['Message'], 'tranId' => $clientId);
        }
        elseif($status == 2){
            $ret = array('status' => 'failure', 'status-code' => 'failure', 'vendor_id'=>$clientId,'operator_id'=>$txnId, 'description' => $out['CheckStatusResponse']['Message'], 'tranId' => $clientId);
        }
        else{
            $ret = array('status' => 'pending', 'status-code' => 'pending', 'vendor_id'=>$clientId,'operator_id'=>$txnId, 'description' => $out['CheckStatusResponse']['Message'], 'tranId' => $clientId);
        }

        return $ret;
    }

    function bigshoprecMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['bigshoprec'];

        $vendor = BIGSHOPREC_VENDOR_ID;
        $url = BIGSHOPREC_RECHARGE_URL;
        $input_params = array('Username'=>BIGSHOPREC_UN,'Password'=>BIGSHOPREC_PWD, 'Operator'=>$provider, 'Amount'=>$params['amount'], 'ConsumerNumber'=>$params['mobileNumber'], 'ServiceID'=>'1','RechargePin'=>BIGSHOPREC_RECHARGE_PIN,'RequestID'=>$transId);

        $out = $this->General->bigshoprecApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/bigshoprec.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'],TRUE);
        $status = $out['status'];
        $clientId = $out['TransactionID'];

        if(strtolower($status) == 'failure'){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out['response']);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$clientId, 'internal_error_code'=>'15', 'vendor_response'=>$out['response']);
        }

        return $ret;
    }

    function bigshoprecDthRecharge($transId, $params, $prodId)
    {
        $mobileNo = $params['subId'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['bigshoprec'];

        $vendor = BIGSHOPREC_VENDOR_ID;
        $url = BIGSHOPREC_RECHARGE_URL;

        $input_params = array('Username'=>BIGSHOPREC_UN,'Password'=>BIGSHOPREC_PWD, 'Operator'=>$provider, 'Amount'=>$params['amount'], 'ConsumerNumber'=>$mobileNo, 'ServiceID'=>'2','RechargePin'=>BIGSHOPREC_RECHARGE_PIN,'RequestID'=>$transId);
        $out = $this->General->bigshoprecApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/bigshoprec.txt", "*DTH Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));

        $out = json_decode($out['output'],TRUE);

        $status = $out['status'];
        $clientId = $out['TransactionID'];

        if(strtolower($status) == 'failure'){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out['response']);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$clientId, 'internal_error_code'=>'15', 'vendor_response'=>$out['response']);
        }

        return $ret;
    }

    function bigshoprecBalance(){
        $vendor = BIGSHOPREC_VENDOR_ID;
        $url = BIGSHOPREC_BAL_URL;

        $out = $this->General->bigshoprecApi($url, array('Username'=>BIGSHOPREC_UN,'Password'=>BIGSHOPREC_PWD));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = json_decode($out['output'],TRUE);

        $bal = isset($out['balance'])?floor($out['balance']):'';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/bigshoprec.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");

        return array('balance'=>$bal);
    }

    function bigshoprecTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;
        $vendor = BIGSHOPREC_VENDOR_ID;
        $url = BIGSHOPREC_STATUS_URL;
        $input_params = array('Username'=>BIGSHOPREC_UN,'Password'=>BIGSHOPREC_PWD, 'ApiRequestID'=>$transId);
        $out = $this->General->bigshoprecApi($url, $input_params);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/bigshoprec.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":bigshoprecTranStatus: " . $out['output']);
        $out = json_decode($out['output'],TRUE);
        $status = $out['status'];

        if(strtolower($status) == 'success'){
            $ret = array('status'=>$status, 'status-code'=>'success','operator_id'=>$out['TransactionID'],'vendor_id'=>$out['TransactionID'], 'description'=>$out, 'tranId'=>$transId);
        }
        elseif(strtolower($status) == 'failure'){
            $ret = array('status'=>$status, 'status-code'=>'failure', 'description'=>$out, 'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>$status, 'status-code'=>'pending','operator_id'=>$out['TransactionID'],'vendor_id'=>$out['TransactionID'], 'description'=>$out, 'tranId'=>$transId);
        }
        return $ret;
    }

    function indiaoneMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['indiaone'];

        $vendor = INDIAONE_VENDOR_ID;
        $url = INDIAONE_URL;
        $message = $provider." ".$params['mobileNumber']." ".$params['amount'];
        $input_params = array('mobileno'=>INDIAONE_UN,'message'=>$message,'password'=>INDIAONE_PWD, 'Tranref'=>$transId);

        $out = $this->General->indiaoneApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/indiaone.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'],TRUE);
        $err_code = $out['ErrorCode'];
        $status = isset($out['Data']['Status'])?$out['Data']['Status']:$out['Status'];
        $clientId = $out['Data']['TranId'];
        $opr_id = $out['Data']['OPTId'];
        $remark = $out['Remarks'];
        $err_remarks = array("sequence contains no elements","issue with the wallet deduction. contact your service provider.","same amount hit again ..recharge after 1 hour for same amount & same number","insufficent balance to use service.");

        if($err_code == '1' || (strtolower($status) == 'success')){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>'success');
        }
        elseif($err_code == '2' || (strtolower($status) == 'failure') || in_array(strtolower($remark),$err_remarks)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>json_encode($out));
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out['Remarks']);
        }

        return $ret;
    }

    function indiaoneDthRecharge($transId, $params, $prodId)
    {
        $mobileNo = $params['subId'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['indiaone'];

        $vendor = INDIAONE_VENDOR_ID;
        $url = INDIAONE_URL;
        $message = $provider." ".$params['subId']." ".$params['amount'];
        $input_params = array('mobileno'=>INDIAONE_UN,'message'=>$message,'password'=>INDIAONE_PWD, 'Tranref'=>$transId);
        $out = $this->General->indiaoneApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/indiaone.txt", "*DTH Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));

        $out = json_decode($out['output'],TRUE);

        $err_code = $out['ErrorCode'];
        $status = isset($out['Data']['Status'])?$out['Data']['Status']:$out['Status'];
        $clientId = $out['Data']['TranId'];
        $opr_id = $out['Data']['OPTId'];
        $remark = $out['Remarks'];
        $err_remarks = array("sequence contains no elements","issue with the wallet deduction. contact your service provider.","same amount hit again ..recharge after 1 hour for same amount & same number","insufficent balance to use service.");

        if($err_code == '1' || (strtolower($status) == 'success')){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>'success');
        }
        elseif($err_code == '2' || (strtolower($status) == 'failure') || in_array(strtolower($remark),$err_remarks)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>json_encode($out));
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out['Remarks']);
        }

        return $ret;
    }

    function indiaoneBalance(){
        $vendor = INDIAONE_VENDOR_ID;
        $url = INDIAONE_URL;
        $message = 'BAL';
        $input_params = array('mobileno'=>INDIAONE_UN,'message'=>$message,'password'=>INDIAONE_PWD, 'Tranref'=>'1234');
        $out = $this->General->indiaoneApi($url,$input_params);

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = json_decode($out['output'],TRUE);

        $bal = isset($out['Data']['Balance'])?floor($out['Data']['Balance']):'';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/indiaone.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");

        return array('balance'=>$bal);
    }

    function indiaoneTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;
        $vendor = INDIAONE_VENDOR_ID;
        $url = INDIAONE_URL;
        $message = "MYSTATUS";
        $input_params = array('mobileno'=>INDIAONE_UN,'message'=>$message,'password'=>INDIAONE_PWD, 'Tranref'=>$transId);
        $out = $this->General->indiaoneApi($url,$input_params);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/indiaone.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":indiaoneTranStatus: " . $out['output']);
        $out = json_decode($out['output'],TRUE);
        $status = isset($out['Data']['Status'])?$out['Data']['Status']:$out['Status'];
        $err_code = $out['ErrorCode'];

        if(strtolower($status) == 'success'){
            $ret = array('status'=>'success', 'status-code'=>$status,'operator_id'=>$out['Data']['OPTId'],'vendor_id'=>$out['Data']['TranId'], 'description'=>json_encode($out), 'tranId'=>$transId);
        }
        elseif((in_array(strtolower($status),array('failure','reversal')) || ($err_code == '2')) && (!in_array($err_code,array(4,17)))){
            $ret = array('status'=>'failure', 'status-code'=>$status,'operator_id'=>$out['Data']['OPTId'],'vendor_id'=>$out['Data']['TranId'], 'description'=>json_encode($out), 'tranId'=>$transId);
        }
//        elseif((strtolower($status) == 'reversal')){
//            $ret = array('status'=>'reversal', 'status-code'=>$status,'operator_id'=>$out['Data']['OPTId'],'vendor_id'=>$out['Data']['TranId'], 'description'=>$out, 'tranId'=>$transId);
//        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>$status,'operator_id'=>$out['Data']['OPTId'],'vendor_id'=>$out['Data']['TranId'], 'description'=>json_encode($out), 'tranId'=>$transId);
        }
        return $ret;
    }

    function emoneyMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['emoney'];

        $vendor = EMONEY_VENDOR_ID;
        $url = EMONEY_URL;
        $input_params = array('userid'=>EMONEY_UN,'pass'=>EMONEY_PWD,'mob'=>$params['mobileNumber'],'opt'=>$provider,'amt'=>$params['amount'], 'agentid'=>$transId,'fmt'=>'json');

        $out = $this->General->emoneyApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/emoney.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

        $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['RPID'];
        $opr_id = $out['OPID'];

        if(strtolower($status) == 'failed'){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 'success'){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function emoneyDthRecharge($transId, $params, $prodId)
    {
        $mobileNo = $params['subId'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['emoney'];

        $vendor = EMONEY_VENDOR_ID;
        $url = EMONEY_URL;
        $message = $provider."".$params['subId']."".$params['amount'];
        $input_params = array('userid'=>EMONEY_UN,'pass'=>EMONEY_PWD,'mob'=>$params['subId'],'opt'=>$provider,'amt'=>$params['amount'], 'agentid'=>$transId,'fmt'=>'json');
        $out = $this->General->emoneyApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/emoney.txt", "*DTH Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

        $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['RPID'];
        $opr_id = $out['OPID'];

        if(strtolower($status) == 'failed'){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 'success'){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function emoneyBalance(){
        $vendor = EMONEY_VENDOR_ID;
        $url = EMONEY_URL;
        $input_params = array('userid'=>EMONEY_UN,'pass'=>EMONEY_PWD,'Get'=>'CB');
        $out = $this->General->emoneyApi($url,$input_params);

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out['output'] = $this->General->xml2array($out['output']);

        $out = $out['output'];

        $bal = isset($out['NODES']['STATUS'])?floor($out['NODES']['STATUS']):'';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/emoney.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");

        return array('balance'=>$bal);
    }

    function emoneyTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;
        $vendor = EMONEY_VENDOR_ID;
        $url = EMONEY_URL;
        $input_params = array('userid'=>EMONEY_UN,'pass'=>EMONEY_PWD,'csagentid'=>$transId);
        $out = $this->General->emoneyApi($url,$input_params);

        $out = $this->General->xml2array($out['output']);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/emoney.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":emoneyTranStatus: " . json_encode($out));

        $status = $out['NODES']['STATUS'];
        $vendor_id = $out['NODES']['RPID'];
        $opr_id = $out['NODES']['OPID'];

        if(strtolower($status) == 'success'){
            $ret = array('status'=>$status, 'status-code'=>'success','operator_id'=>$opr_id,'vendor_id'=>$vendor_id, 'description'=>$out, 'tranId'=>$transId);
        }
        elseif(strtolower($status) == 'request accepted'){
            $ret = array('status'=>$status, 'status-code'=>'pending','operator_id'=>$opr_id,'vendor_id'=>$vendor_id, 'description'=>$out, 'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>$status, 'status-code'=>'failure', 'description'=>$out, 'tranId'=>$transId);
        }
        return $ret;
    }

    function speedpayMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;
        $recharge_type = 'RR';

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
            $recharge_type = 'STV';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['speedpay'];

        $pin_no = SPEEDPAY_PIN_NO;
        $message = $recharge_type." ".$provider." ".$params['mobileNumber']." ".$params['amount']." ".$pin_no;
        $url = SPEEDPAY_URL;
        $input_params = array('Mob'=>SPEEDPAY_MN,'message'=>$message,'myTxId'=>$transId,'source'=>'API');

        $out = $this->General->speedpayApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/speedpay.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));
        $out = $out['output'];
        if(strpos($out, ',') !== false)
        {
            $output = explode(',',$out);
            $status = trim(end(explode(':',$output[0])));
            $opr_id = isset($output[7])?trim(end(explode(':',$output[7]))):'';
            $clientId = isset($output[2])?trim(end(explode(':',$output[2]))):'';
        }
        else
        {
            $status = $out;
            $clientId = '';
            $opr_id = '';
        }

        $err_status = array("your request have been fail","mobile number must not be less than 10 digits","sorry...invalid mobilenumber or pinnumber.","invalid recharge amount.","you can't send same recharge request for 10 min.","service temporory down");

        if(strtolower($status) == 'your request have been success'){
            $description = isset($out['response']) ? trim($out['response']) : "";
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'pinRefNo'=>'', 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        elseif(in_array(strtolower($status),$err_status) || strpos(strtolower($status), 'insufficient balance for this recharge') !== false){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
            $description = isset($out['response']) ? trim($out['response']) : "";
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function speedpayDthRecharge($transId, $params, $prodId)
    {
        $prodId = empty($prodId) ? 0 : $prodId;
        $recharge_type = 'DTH';

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['speedpay'];

        $pin_no = SPEEDPAY_PIN_NO;
        $message = $recharge_type." ".$provider." ".$params['subId']." ".$params['amount']." ".$pin_no;
        $url = SPEEDPAY_URL;
        $input_params = array('Mob'=>SPEEDPAY_MN,'message'=>$message,'myTxId'=>$transId,'source'=>'API');

        $out = $this->General->speedpayApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/speedpay.txt", "*DTH Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));
        $out = $out['output'];
        if(strpos($out, ',') !== false)
        {
            $output = explode(',',$out);
            $status = trim(end(explode(':',$output[0])));
            $opr_id = isset($output[7])?trim(end(explode(':',$output[7]))):'';
            $clientId = isset($output[2])?trim(end(explode(':',$output[2]))):'';
        }
        else
        {
            $status = $out;
            $clientId = '';
            $opr_id = '';
        }

        $err_status = array("your request have been fail","mobile number must not be less than 10 digits","sorry...invalid mobilenumber or pinnumber.","invalid recharge amount.","you can't send same recharge request for 10 min.","service temporory down");

        if(strtolower($status) == 'your request have been success'){
            $description = isset($out['response']) ? trim($out['response']) : "";
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'vendor_id'=>$clientId, 'operator_id'=>$opr_id, 'pinRefNo'=>'', 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        elseif(in_array(strtolower($status),$err_status) || strpos(strtolower($status), 'insufficient balance for this recharge') !== false){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'vendor_id'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        else{
            $description = isset($out['response']) ? trim($out['response']) : "";
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'vendor_id'=>$clientId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function speedpayBalance(){
        $url = SPEEDPAY_URL;
        $pin_no = SPEEDPAY_PIN_NO;
        $message = 'BAL'.' '.$pin_no;
        $input_params = array('Mob'=>SPEEDPAY_MN,'message'=>$message,'source'=>'API');
        $out = $this->General->speedpayApi($url, $input_params);

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = explode(' ',$out['output']);

        $bal = isset($out[3])?floor($out[3]):'';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/speedpay.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");

        return array('balance'=>$bal);
    }


    function speedpayTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;
        $url = SPEEDPAY_URL;
        $pin_no = SPEEDPAY_PIN_NO;
        $message = "mytxid"." ".$transId." ".$pin_no;

        $input_params = array('Mob'=>SPEEDPAY_MN,'message'=>$message,'source'=>'API');

        $out = $this->General->speedpayApi($url,$input_params);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/speedpay.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":speedpayTranStatus: " . $out['output']);
        $out = $out['output'];
        $output = explode(',',$out);
        $status = trim(end(explode(':',$output[0])));
        $opr_id = isset($output[1])?trim(end(explode('*',$output[1]))):'';

        if(strtolower($status) == 'success'){
            $ret = array('status'=>'success', 'status-code'=>'success','operator_id'=>$opr_id,'vendor_id'=>'', 'description'=>$out, 'tranId'=>$transId);
        }
        elseif(strtolower($status) == 'fail'){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'description'=>$out, 'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending','operator_id'=>$opr_id,'vendor_id'=>'', 'description'=>$out, 'tranId'=>$transId);
        }
        return $ret;
    }


    function thinkwalMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['thinkwal'];

        $vendor = THINKWAL_VENDOR_ID;
        $url = THINKWAL_MOB_REC_URL;
        $input_params = array('to'=>$params['mobileNumber'],'memberId'=>THINKWAL_MEMBER_ID,'passwd'=>THINKWAL_PASS, 'transId'=>$transId, 'amount'=>$params['amount'], 'operator'=>$provider, 'type'=>'TOPUP', 'circle'=>'51');

        $out = $this->General->thinkwalApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/thinkwal.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));

        $out = json_decode($out['output'],TRUE);

        $err_code = $out['errCode'];
        $msg = $out['msg'];
        $clientId = $out['txnId'];
        $opr_id = '';

        if($err_code == '0'){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$msg);
        }
        elseif(in_array($err_code,array(1,2,3,4,5,6,7,8,10,11,12,14,786))){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$msg);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$msg);
        }

        return $ret;
    }

    function thinkwalDthRecharge($transId, $params, $prodId = null, $res = null)
    {
        $vendor = THINKWAL_VENDOR_ID;
        $url = THINKWAL_DTH_REC_URL;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['thinkwal'];

        $input_params = array('to'=>$params['subId'],'memberId'=>THINKWAL_MEMBER_ID,'passwd'=>THINKWAL_PASS, 'transId'=>$transId, 'amount'=>$params['amount'], 'operator'=>$provider, 'type'=>'Topup', 'circle'=>'51');

        $out = $this->General->thinkwalApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/thinkwal.txt", "*DTH Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'],TRUE);
        $err_code = $out['errCode'];
        $msg = $out['msg'];
        $clientId = $out['txnId'];
        $opr_id = '';

        if($err_code == '0'){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$msg);
        }
        elseif(in_array($err_code,array(1,2,3,4,5,6,7,8,10,11,12,14,15,786))){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$msg);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$msg);
        }

        return $ret;
    }

    function thinkwalBalance(){
        $url = THINKWAL_BAL_URL;
        $input_params = array('memberId' => THINKWAL_MEMBER_ID,'passwd'=> THINKWAL_PASS);

        $out = $this->General->thinkwalApi($url,$input_params);

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = json_decode($out['output'],TRUE);

        $bal = isset($out['balance'])?$out['balance']:'';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/thinkwal.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");

        return array('balance'=>$bal);
    }

    function thinkwalTranStatus($transId, $date = null, $refId = null){
        $vendor = THINKWAL_VENDOR_ID;
        $url = THINKWAL_STATUS_URL;
        $input_params = array('memberId'=>THINKWAL_MEMBER_ID,'passwd'=>THINKWAL_PASS, 'refId'=>$transId);
        $out = $this->General->thinkwalApi($url,$input_params);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/thinkwal.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":thinkwalTranStatus: " . $out['output']);
        $out = json_decode($out['output'],TRUE);
        $err_code = $out['errCode'];
        $msg = $out['msg'];
        $clientId = $out['txnId'];
        $opr_id = $out['optId'];

        if($err_code == '0'){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'vendor_id'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$msg);
        }
        elseif(in_array($err_code,array(12))){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'vendor_id'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$msg);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'vendor_id'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$msg);
        }

        return $ret;
    }

    function champrecMobRecharge($transId,$params,$prodId){
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['champrec'];

        $vendor = CHAMPREC_VENDOR_ID;
        $url = CHAMPREC_REC_URL;
        $message =  $provider.$params['mobileNumber'].'A'.$params['amount'].'REF'.$transId;

        $inputparams = array('login_id'=>CHAMPREC_USERID,'transaction_password'=>CHAMPREC_TXNPASS, 'message'=>$message, 'response_type'=>'XML');

        $out = $this->General->champrecApi($url,$inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/champrec.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = $this->General->xml2array($out['output']);

        $status = $out['RESPONSE']['STATUS'];
        $msg = $out['RESPONSE']['MESSAGE'];
        $clientId = $out['RESPONSE']['TRID'];
        $opr_id = '';

        if(strtolower($status) == 'success'){
            $opr_id = $out['RESPONSE']['MESSAGE'];
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$msg);
        }
        elseif(strtolower($status) == 'failure'){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$msg);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$msg);
        }

        return $ret;
    }

    function champrecDthRecharge($transId,$params,$prodId){
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['champrec'];

        $vendor = CHAMPREC_VENDOR_ID;
        $url = CHAMPREC_REC_URL;
        $message =  $provider.$params['subId'].'A'.$params['amount'].'REF'.$transId;

        $inputparams = array('login_id'=>CHAMPREC_USERID,'transaction_password'=>CHAMPREC_TXNPASS, 'message'=>$message, 'response_type'=>'XML');

        $out = $this->General->champrecApi($url,$inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/champrec.txt", "*DTH Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = $this->General->xml2array($out['output']);

        $status = $out['RESPONSE']['STATUS'];
        $msg = $out['RESPONSE']['MESSAGE'];
        $clientId = $out['RESPONSE']['TRID'];
        $opr_id = '';

        if(strtolower($status) == 'success'){
            $opr_id = $out['RESPONSE']['MESSAGE'];
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$msg);
        }
        elseif(strtolower($status) == 'failure'){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$msg);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$msg);
        }

        return $ret;
    }

    function champrecBalance(){
        $url = CHAMPREC_BAL_URL;
        $input_params = array('login_id' => CHAMPREC_USERID, 'transaction_password' => CHAMPREC_TXNPASS);

        $out = $this->General->champrecApi($url, $input_params);

        if (!$out['success']) {
            return array('balance' => '');
        }
        $out = json_decode($out['output'], TRUE);

        $bal = isset($out) ? floor($out) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/champrec.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }

    function champrecTranStatus($transId, $date = null, $refId = null){
        $url = CHAMPREC_STATUS_URL;
        $input_params = array('login_id' => CHAMPREC_USERID, 'transaction_password' => CHAMPREC_TXNPASS, 'CLIENTID'=>$transId, 'response_type'=>'XML');
        $out = $this->General->champrecApi($url, $input_params);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/champrec.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":champrecTranStatus: " . json_encode($out['output']));

        $out = $this->General->xml2array($out['output']);

        $status = $out['RESPONSE']['STATUS'];
        $clientId = $out['RESPONSE']['TRID'];
        $opr_id = (strtolower($status) == 'success')?$out['RESPONSE']['MESSAGE']:'';

        if(strtolower($status) == 'success'){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'vendor_id'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=> json_encode($out));
        }
        elseif(strtolower($status) == 'failure'){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'vendor_id'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>json_encode($out));
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'vendor_id'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>json_encode($out));
        }

        return $ret;
    }

    function yashicaentMobRecharge($transId, $params, $prodId) {
        $prodId = empty($prodId) ? 0 : $prodId;
        if (in_array($prodId, array('27', '28', '29', '31', '34'))) {
            $params['type'] = 'voucher';
        }
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['yashicaent'];
        $mobileNo = $params['mobileNumber'];

        $url = YASHICAENT_RECURL;
        $param = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => YASHICAENT_USER);
        $token = $this->General->yashicaenttokenGenerator($param, YASHICAENT_SECRET_KEY);
        $data = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => YASHICAENT_USER);
        $out = $this->General->yashicaentApi($url, $data);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/yashicaent.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);

        $err_code = $out['errorCode'];
        $msg  = $out['description'];
        $status = $out['description']['status'];
        $clientId = $out['description']['transactionID'];
        $opr_id = '';

        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function yashicaentDthRecharge($transId, $params, $prodId) {

        $mobileNo = $params['mobileNumber'];
        $sub_Id   = $params['subId'];
        $url = YASHICAENT_RECURL;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['yashicaent'];
        $param =  array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => YASHICAENT_USER);
        $token = $this->General->yashicaenttokenGenerator($param, YASHICAENT_SECRET_KEY);
        $data = array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => YASHICAENT_USER);
        $out = $this->General->yashicaentApi($url, $data);
        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/yashicaent.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $msg = $out['description'];

        $status = $out['description']['status'];
        $clientId = ($out['description']['transactionID']);
        $opr_id = '';
        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function yashicaentBalance() {
        $url = YASHICAENT_BALURL;
        $params = array('user_id' => YASHICAENT_USER);
        $token = $this->General->yashicaenttokenGenerator($params, YASHICAENT_SECRET_KEY);
        $input_params = array('user_id' => YASHICAENT_USER, 'secret' => $token);
        $out = $this->General->yashicaentApi($url, $input_params);

        if (!$out['success']) {
            return array('balance' => '');
        }

        $out = json_decode($out['output'], TRUE);
        $bal = isset($out['description']['data']['current_balance']) ? floor($out['description']['data']['current_balance']) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/yashicaent.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }


    function yashicaentTranStatus($transId, $date = null, $refId = null) {
        $url = YASHICAENT_STATUS_URL;
        $data = array('transaction_id' => $transId, 'user_id' => YASHICAENT_USER);
        $token = $this->General->yashicaenttokenGenerator($data, YASHICAENT_SECRET_KEY);
        $data = array('transaction_id' => $transId, 'user_id' => YASHICAENT_USER, 'secret' => $token);
        $out = $this->General->yashicaentApi($url, $data);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/yashicaent.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($data) . "Output: " . $out['output']);
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $msg = ($out['description']['data']);
        $status = isset($out['description']['data']['status'])?($out['description']['data']['status']):'';
        $clientId = isset($out['description']['data']['id'])?($out['description']['data']['id']):'' ;
        $opr_id =   isset($out['description']['data']['sys_opr_id'])?($out['description']['data']['sys_opr_id']):'';

        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id'=>$clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '0') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id'=>$clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id'=>$clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }

    function ka2zrecMobRecharge($transId, $params, $prodId = null, $res = null) {
        $prodId = empty($prodId) ? 0 : $prodId;
        $recharge_type = 'RR';
        if (in_array($prodId, array('27', '28', '29', '31', '34'))) {
            $params['type'] = 'voucher';
            $recharge_type = 'STV';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['ka2zrec'];
        $url = KA2ZREC_URL;
        $mobile = $params['mobileNumber'];
        $message = $recharge_type . ' ' . $provider . ' ' . $mobile . ' ' . $params['amount'] . ' ' . KA2ZREC_PIN;
        $input_params = array('Mob' => KA2ZREC_PAY1_MOBILE, 'message' => $message,'myTxid' => $transId,'Source' => 'API');
        $out = $this->General->ka2zrecApi($url, $input_params);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ka2zrec.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params) . "<br/>Output=>" . json_encode($out));

        $out = $out['output'];
        if(strpos($out, ',') !== false){
        $outs = explode(',', $out);
        $status = trim(end(explode(':', $outs[0])));
        $clientId = isset($outs[2]) ? trim(end(explode(':', $outs[2]))) : '';
        $opr_Id = isset($outs[7]) ? trim(end(explode(':', $outs[7]))) : '';
        }
        else {
            $status = $out;
            $clientId = '';
            $opr_Id   = '';
        }

        $err_status = array("your request have been fail", "mobile number must not be less than 10 digits.",
            "sorry...invalid mobilenumber or pinnumber.", "invalid recharge amount.", "insufficient balance for this recharge",
            "you can't send same recharge request for 10 min.", "you can not request for recharge because your distributor have no balance.",
            "sorry..!! your service is temporarily unavailable contact your distributor for further assistance.");

        if (strtolower($status) == 'your request have been success') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_Id, 'internal_error_code' => '13', 'vendor_response' => json_encode($out));
        } else if (in_array(strtolower($status), $err_status)) {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => $out);
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'internal_error_code' => '15', 'vendor_response' => $out);
        }
        return $ret;
    }

    function ka2zrecDthRecharge($transId, $params, $prodId = null) {
        $prodId = empty($prodId) ? 0 : $prodId;
        $recharge_type ='DTH';

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['ka2zrec'];

        $url = KA2ZREC_URL;
        $mobile = $params['subId'];
        $message = $recharge_type . ' ' . $provider . ' ' . $mobile . ' ' . $params['amount'] . ' ' . KA2ZREC_PIN;
        $input_params = array('Mob' => KA2ZREC_PAY1_MOBILE, 'message' => $message,'myTxid' => $transId,'Source' => 'API');
        $out = $this->General->ka2zrecApi($url, $input_params);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ka2zrec.txt", "*DTH Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));
        $out = $out['output'];

        if(strpos($out, ',') !== false)
        {
            $outs = explode(',', $out);
            $status = trim(end(explode(':', $outs[0])));
            $clientId = isset($outs[2]) ? trim(end(explode(':', $outs[2]))) : '';
            $opr_Id = isset($outs[7]) ? trim(end(explode(':', $outs[7]))) : '';
        }
        else
        {
            $status = $out;
            $clientId = '';
            $opr_Id = '';
        }

        $err_status = array("your request have been fail", "mobile number must not be less than 10 digits.",
            "sorry...invalid mobilenumber or pinnumber.", "invalid recharge amount.", "insufficient balance for this recharge",
            "you can't send same recharge request for 10 min.", "you can not request for recharge because your distributor have no balance.",
            "sorry..!! your service is temporarily unavailable contact your distributor for further assistance.");

        if (strtolower($status) == 'your request have been success') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_Id, 'internal_error_code' => '13', 'vendor_response' => $out);
        } else if (in_array(strtolower($status), $err_status)) {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => $out);
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'internal_error_code' => '15', 'vendor_response' => $out);
        }

        return $ret;
    }

    function ka2zrecBalance() {
        $url = KA2ZREC_URL;
        $message = 'Bal ' . KA2ZREC_PIN;
        $input_params = array('Mob' => KA2ZREC_PAY1_MOBILE, 'message' => $message, 'Source' => 'API');
        $out = $this->General->ka2zrecApi($url, $input_params);
        if (!$out['success']) {
            return array('balance' => '');
        }

        $amount = explode(' ',$out['output']);

        $bal = isset($amount[3]) ? floor($amount[3]) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ka2zrec.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }

    function ka2zrecTranStatus($transId, $date = null, $refId = null) {
        $transId = empty($transId) ? 0 : $transId;
        $url = KA2ZREC_URL;
        $message =  'mytxid ' . $transId . " " . KA2ZREC_PIN;
        $input_params = array('Mob' => KA2ZREC_PAY1_MOBILE, 'message' => $message, 'Source' => 'API');
        $out = $this->General->ka2zrecApi($url, $input_params);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ka2zrec.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":ka2zrecTranStatus: " . $out['output']);
        $out = $out['output'];
        $outs = explode(',', $out);
        $status = isset($outs[0])?trim(end(explode(':', $outs[0]))):'';
        $opr_id = isset($outs[1])?trim(end(explode('*', $outs[1]))):'';
        if (strtolower($status) == 'success') {
            $ret = array('status' => 'success', 'status-code' => 'success', 'operator_id' => $opr_id, 'vendor_id' => '', 'description' => $out, 'tranId' => $transId);
        } elseif (strtolower($status) == 'fail') {
            $ret = array('status' => 'failure', 'status-code' => 'failure', 'description' => $out, 'tranId' => $transId);
        } else {
            $ret = array('status' => 'pending', 'status-code' => 'pending', 'operator_id' => $opr_id, 'vendor_id' => '', 'description' => $out, 'tranId' => $transId);
        }
        return $ret;
    }

//    function zplusBalance(){
//        $url = ZPLUS_BAL_URL;
//        $input_params = array('userid'=>ZPLUS_USERID,'pin'=>ZPLUS_PASSWORD);
//
//        $out = $this->General->zplusApi($url,$input_params);
//    }


     function roundpayMobRecharge($transId,$params,$prodId){
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['roundpay'];

        $url = ROUNDPAY_URL;
        $inputparams = array('userid' => ROUNDPAY_USERID, 'pass' => ROUNDPAY_PASSWORD,'mob'=>$params['mobileNumber'],'opt'=> $provider,'amt' => $params['amount'],'agentid' => $transId,'fmt' => 'Json' );
        $out = $this->General->roundpayApi($url,$inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/roundpay.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($inputparams). "<br/>Output=>" . json_encode($out));
        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $out = json_decode($out['output'], TRUE);

        $status = strtolower($out['STATUS']);
        $msg  = $out['MSG'];
        $clientId = $out['RPID'];
        $opr_id = '';

        if ($status == 'success') {
            $opr_id = $out['OPID'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> $msg);
        }
        elseif ($status == 'failed') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => $msg);
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => $msg);
        }

        return $ret;
     }

     function roundpayDthRecharge($transId,$params,$prodId){
        $prodId = empty($prodId) ? 0 : $prodId;

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['roundpay'];

        $url = ROUNDPAY_URL;
        $inputparams = array('userid' => ROUNDPAY_USERID, 'pass' => ROUNDPAY_PASSWORD,'mob'=>$params['subId'],'opt'=> $provider,'amt' => $params['amount'],'agentid' => $transId,'fmt' => 'Json' );
        $out = $this->General->roundpayApi($url,$inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/roundpay.txt", "*DTH Recharge*: Input=> $transId<br/>params=>" . json_encode($inputparams). "<br/>Output=>" . json_encode($out));
        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $out = json_decode($out['output'], TRUE);

        $status = strtolower($out['STATUS']);
        $msg  = $out['MSG'];
        $clientId = $out['RPID'];
        $opr_id = '';
        if ($status == 'success') {
            $opr_id = $out['OPID'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> $msg);
        }
        elseif ($status == 'failed') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => $msg);
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => $msg);
        }

        return $ret;
     }


     function roundpayBalance(){
        $url = ROUNDPAY_URL;
        $input_params = array('userid' => ROUNDPAY_USERID, 'pass' => ROUNDPAY_PASSWORD, 'GET' => 'CB', 'fmt' => 'Json');
        $out = $this->General->roundpayApi($url, $input_params);

        if (!$out['success']) {
            return array('balance' => '');
        }
        $balance = json_decode($out['output'], TRUE);
        $bal = isset($balance['STATUS']) ? floor($balance['STATUS']) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/roundpay.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }


    function roundpayTranStatus($transId, $date = null, $refId = null) {
        $transId = empty($transId) ? 0 : $transId;
        $url = ROUNDPAY_URL;
        $input_params = array('userid' => ROUNDPAY_USERID, 'pass' => ROUNDPAY_PASSWORD, 'csagentid' => $transId, 'fmt' => 'Json');
        $out = $this->General->roundpayApi($url, $input_params);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/roundpay.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":roundpayTranStatus: " . $out['output']);
        $outs = json_decode($out['output'],TRUE);
        $status = strtolower($outs['STATUS']);
        $client_id = isset($outs['RPID']) ? $outs['RPID'] : '' ;
        $opr_id = isset($outs['OPID']) ? $outs['OPID']:'';
        $msg    = isset($outs['MSG']) ? $outs['MSG']:' ';

        if ($status == 'success') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' => $client_id, 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => $msg);
        } elseif ($status == 'failed') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $client_id, 'internal_error_code' => '30', 'vendor_response' => $msg);
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $client_id, 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => $msg);
        }
        return $ret;
    }

    function maxxrecMobRecharge($transId,$params,$prodId){
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['maxxrec'];
        $url = MAXXREC_URL;
        $inputparams = array('apiToken' => MAXXREC_M_TOKEN,'mn'=>$params['mobileNumber'],'op'=>$provider,'amt'=>$params['amount'],'reqid'=> $transId,'field1' => null,'field2' => null);

        $out = $this->General->maxxrecApi($url,$inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/maxxrec.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($inputparams). "<br/>Output=>" . json_encode($out));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $out = json_encode($this->General->xml2array($out));
        $out = json_decode($out,true);
        $out =  $out['Result'];
        $remark = $out['remark'];
        $status = strtolower($out['status']);
        $clientId   = $out['apirefid'];
        $opr_id     = "";

        if('success' == trim($status)){
            $opr_id = $out['field1'];
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$remark);
        }
        elseif(in_array(trim($status),array('failed','frequent'))){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$remark);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$remark);
        }

        return $ret;
    }

    function maxxrecDthRecharge($transId,$params,$prodId){
        $prodId = empty($prodId) ? 0 : $prodId;

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['maxxrec'];
        $url = MAXXREC_URL;
        $inputparams = array('apiToken' => MAXXREC_M_TOKEN,'mn'=>$params['subId'],'op'=>$provider,'amt'=>$params['amount'],'reqid'=> $transId,'field1' => null,'field2' => null);

        $out = $this->General->maxxrecApi($url,$inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/maxxrec.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($inputparams). "<br/>Output=>" . json_encode($out));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $out = json_encode($this->General->xml2array($out));
        $out = json_decode($out,true);
        $out = $out['Result'];
        $remark = $out['remark'];
        $status = strtolower($out['status']);
        $clientId   = $out['apirefid'];
        $opr_id     = "";

        if('success' == trim($status)){
            $opr_id = $out['field1'];
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'vendor_id' => $clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$remark);
        }
        elseif(in_array(trim($status),array('failed','frequent'))){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'vendor_id' => $clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$remark);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'vendor_id' => $clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$remark);
        }

        return $ret;
    }

    function maxxrecBalance(){
        $url = MAXXREC_BAL_URL;
        $input_params = array('apiToken' => MAXXREC_M_TOKEN);
        $out = $this->General->maxxrecApi($url, $input_params);

        if (!$out['success']) {
           return array('balance' => '');
        }

        $out = $this->General->xml2array($out['output']);
        $balance = $out['string'];

        $bal = isset($balance) ? floor($balance) : '';
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/maxxrec.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }

    function maxxrecTranStatus($transId, $date = null, $refId = null){
        $url = MAXREC_STATUS_URL;
        $input_params = array('apiToken' => MAXXREC_M_TOKEN,'reqid' => $transId);
        $out = $this->General->maxxrecApi($url, $input_params);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/maxxrec.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":maxxrecTranStatus: " . json_encode($out['output']));
        $out = $out['output'];
        $out = json_encode($this->General->xml2array($out));
        $out = json_decode($out,true);
        $out = $out['Result'];
        $status = $out['status'];
        $clientId = $out['reqid'];
        $opr_id = (strtolower($status) == 'success') ? $out['field1']:'';

        if(strtolower($status) == 'success'){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'vendor_id' => $clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=> json_encode($out));
        }
        elseif(strtolower($status) == 'failed' || strtolower($status) == 'refund'){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code'=>'30', 'vendor_response'=>json_encode($out));
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15),'vendor_id' => $clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>json_encode($out));
        }

        return $ret;
    }

    function erecpointMobRecharge($transId,$params,$prodId){
        $prodId = empty($prodId) ? 0 : $prodId;
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['erecpoint'];
        $url = EREC_URL;
        $inputparams = array('apiToken' => EREC_M_TOKEN,'mn'=>$params['mobileNumber'],'op'=>$provider,'amt'=>$params['amount'],'reqid'=> $transId,'field1' => null,'field2' => null);

        $out = $this->General->erecpointApi($url,$inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/erecpoint.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $out = json_encode($this->General->xml2array($out));
        $out = json_decode($out,true);
        $out =  $out['Result'];
        $remark = $out['remark'];
        $status = strtolower($out['status']);
        $clientId   = $out['apirefid'];
        $opr_id     = "";

        if('success' == trim($status)){
            $opr_id = $out['field1'];
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$remark);
        }
        elseif(in_array(trim($status),array('failed','frequent'))){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$remark);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$remark);
        }

        return $ret;
    }

    function erecpointDthRecharge($transId,$params,$prodId){
        $prodId = empty($prodId) ? 0 : $prodId;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['erecpoint'];
        $url = EREC_URL;
        $inputparams = array('apiToken' => EREC_M_TOKEN,'mn'=>$params['subId'],'op'=>$provider,'amt'=>$params['amount'],'reqid'=> $transId,'field1' => null,'field2' => null);

        $out = $this->General->maxxrecApi($url,$inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/erecpoint.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $out = json_encode($this->General->xml2array($out));
        $out = json_decode($out,true);
        $out =  $out['Result'];
        $remark = $out['remark'];
        $status = strtolower($out['status']);
        $clientId = $out['apirefid'];
        $opr_id = "";

        if('success' == trim($status)){
            $opr_id = $out['field1'];
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$remark);
        }
        elseif(in_array(trim($status),array('failed','frequent'))){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$remark);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$remark);
        }
        return $ret;
    }

    function erecpointBalance(){
        $url = EREC_BAL_URL;
        $input_params = array('apiToken' => EREC_M_TOKEN);
        $out = $this->General->erecpointApi($url, $input_params);

        if (!$out['success']) {
           return array('balance' => '');
        }

        $out = $out['output'];
        $out = json_encode($this->General->xml2array($out));
        $out = json_decode($out,true);
        if(array_key_exists('string', $out) && !empty($out['string']))
        $bal = floor($out['string']);
          else
        return array('balance'=>'');

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/erecpoint.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }

    function erecpointTranStatus($transId, $date = null, $refId = null){
        $url = EREC_URL_STATUS;
        $input_params = array('apiToken' => EREC_M_TOKEN,'reqid' => $transId);
        $out = $this->General->erecpointApi($url, $input_params);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/erecpoint.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":erecpointTranStatus: " . json_encode($out['output']));
        $out = $out['output'];
        $out = json_encode($this->General->xml2array($out));
        $out = json_decode($out,true);
        $out = $out['Result'];
        $status = $out['status'];
        $clientId = $out['reqid'];
        $opr_id = (strtolower($status) == 'success') ? $out['field1']:'';

        if(strtolower($status) == 'success'){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'vendor_id' => $clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=> json_encode($out));
        }
        elseif(strtolower($status) == 'failed' || strtolower($status) == 'refund'){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code'=>'30', 'vendor_response'=>json_encode($out));
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'vendor_id' => $clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>json_encode($out));
        }

        return $ret;
    }

    function urecMobRecharge($transId,$params,$prodId){
        $url = UREC_URL;
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['urec'];
        $message = $provider . ' ' . $params['mobileNumber'].' '.$params['amount'];
        $inputparams = array('mobileno' => UREC_ID,'message' => $message ,'password' => UREC_PASS,'Tranref' => $transId);
        $out = $this->General->urecApi($url,$inputparams);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/urec.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['ErrorCode'];
        $status = isset($out['Data']['Status'])?$out['Data']['Status']:$out['Status'];
        $clientId = $out['Data']['TranId'];
        $opr_id = $out['Data']['OPTId'];
        $remark = $out['Remarks'];

        $err_remarks = array("error occurred. please contact to service provider","exception for invalid ip","invalid ip","exception for invalid password","your account is suspended, contact to service provider","your not authorized. contact your service provider",
                        "operator service is unavailable.","recharge service temporary is unavailable.","same amount hit again ..recharge after 1 hour for same amount & same number","invalid user",
                        "exception for invalid message format","error occured in recharge format","parameter is required","sequence contains no elements","insufficent balance to use service.","configuration is not set properly. contact your service provider.");

        if($err_code == '1' || (strtolower($status) == 'success')){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>'success');
        }
        elseif($err_code == '2' || (strtolower($status) == 'failure') || in_array(strtolower($remark),$err_remarks)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>json_encode($out));
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out['Remarks']);
        }

        return $ret;
    }

    function urecDthRecharge($transId,$params,$prodId){
        $url = UREC_URL;
        $prodId = empty($prodId) ? 0 : $prodId;

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['urec'];
        $message = $provider . ' ' . $params['subId'].' '.$params['amount'];
        $inputparams = array('mobileno' => UREC_ID,'message' => $message ,'password' => UREC_PASS,'Tranref' => $transId);
        $out = $this->General->urecApi($url,$inputparams);
        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/urec.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['ErrorCode'];
        $status = isset($out['Data']['Status'])?$out['Data']['Status']:$out['Status'];
        $clientId = $out['Data']['TranId'];
        $opr_id = $out['Data']['OPTId'];
        $remark = $out['Remarks'];

        $err_remarks = array("error occurred. please contact to service provider","exception for invalid ip","invalid ip","exception for invalid password","your account is suspended, contact to service provider","your not authorized. contact your service provider",
                        "operator service is unavailable.","recharge service temporary is unavailable.","same amount hit again ..recharge after 1 hour for same amount & same number","invalid user",
                        "exception for invalid message format","error occured in recharge format","parameter is required","sequence contains no elements","insufficent balance to use service.","configuration is not set properly. contact your service provider.");

        if($err_code == '1' || (strtolower($status) == 'success')){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>'success');
        }
        elseif($err_code == '2' || (strtolower($status) == 'failure') || in_array(strtolower($remark),$err_remarks)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>json_encode($out));
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out['Remarks']);
        }

        return $ret;
    }

    function urecBalance(){
        $url = UREC_URL;
        $input_params = array('mobileno'=> UREC_ID,'message'=>'BAL','password'=> UREC_PASS,'Tranref'=>'101');
        $out = $this->General->urecApi($url, $input_params);

        if (!$out['success']) {
            return array('balance' => '');
        }

        $outs = json_decode($out['output'],TRUE);
        $balance = $outs['Data']['Balance'];
        $bal = isset($balance)? floor($balance):'';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/urec.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }

    function urecTranStatus($transId, $date = null, $refId = null) {
        $transId = empty($transId) ? 0 : $transId;
        $url = UREC_URL;
        $message = "STATUS";
        $input_params = array('mobileno' => UREC_ID, 'message' => $message, 'password' => UREC_PASS, 'Tranref' => $transId);
        $out = $this->General->urecApi($url, $input_params);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/urec.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":urecTranStatus: " . $out['output']);
        $out = json_decode($out['output'], TRUE);
        $status = isset($out['Data']['Status']) ? $out['Data']['Status'] : $out['Status'];
        $err_code = $out['ErrorCode'];

        if (strtolower($status) == 'success') {
            $ret = array('status' => 'success', 'status-code' => $status, 'operator_id' => $out['Data']['OPTId'], 'vendor_id' => $out['Data']['TranId'], 'description' => json_encode($out), 'tranId' => $transId);
        } elseif((in_array(strtolower($status),array('failure','reversal')) || ($err_code == '2')) && (!in_array($err_code,array(4,17)))){
            $ret = array('status' => 'failure', 'status-code' => $status, 'operator_id' => $out['Data']['OPTId'], 'vendor_id' => $out['Data']['TranId'], 'description' => json_encode($out), 'tranId' => $transId);
        } else {
            $ret = array('status' => 'pending', 'status-code' => $status, 'operator_id' => $out['Data']['OPTId'], 'vendor_id' => $out['Data']['TranId'], 'description' => json_encode($out), 'tranId' => $transId);
        }

        return $ret;
    }

    function pay1allMobRecharge($transId, $params, $prodId) {
        $prodId = empty($prodId) ? 0 : $prodId;

        if (in_array($prodId, array('27', '28', '29', '31', '34'))) {
            $params['type'] = 'voucher';
        }
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['pay1all'];

        $url = PAY1ALL_URL;
        $inputparams = array('userid' => PAY1ALL_USERID, 'pass' => PAY1ALL_PASSWORD, 'mob' => $params['mobileNumber'], 'opt' => $provider, 'amt' => $params['amount'], 'agentid' => $transId,
            'fmt' => 'Json');
        $out = $this->General->pay1allApi($url, $inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pay1all.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params) . "<br/>Output=>" . json_encode($out));
        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }

        $out = json_decode($out['output'], TRUE);
        $status = strtolower($out['STATUS']);
        $msg = $out['MSG'];
        $clientId = $out['RPID'];
        $opr_id = '';

        if ($status == 'success') {
            $opr_id = $out['OPID'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> $msg);
        }
        elseif ($status == 'failed') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => $msg);
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => $msg);
        }

        return $ret;
    }

    function pay1allDthRecharge($transId, $params, $prodId) {
        $prodId = empty($prodId) ? 0 : $prodId;

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['pay1all'];

        $url = PAY1ALL_URL;
        $inputparams = array('userid' => PAY1ALL_USERID, 'pass' => PAY1ALL_PASSWORD, 'mob' => $params['subId'], 'opt' => $provider, 'amt' => $params['amount'], 'agentid' => $transId,
            'fmt' => 'Json');
        $out = $this->General->pay1allApi($url, $inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pay1all.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params) . "<br/>Output=>" . json_encode($out));
        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }

        $out = json_decode($out['output'], TRUE);
        $status = strtolower($out['STATUS']);
        $msg = $out['MSG'];
        $clientId = $out['RPID'];
        $opr_id = '';

        if ($status == 'success') {
            $opr_id = $out['OPID'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> $msg);
        }
        elseif ($status == 'failed') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => $msg);
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => $msg);
        }

        return $ret;
    }

    function pay1allBalance(){
        $url = PAY1ALL_URL;
        $input_params = array('userid' => PAY1ALL_USERID, 'pass' => PAY1ALL_PASSWORD, 'GET' => 'CB', 'fmt' => 'Json');
        $out = $this->General->pay1allApi($url, $input_params);

        if (!$out['success']) {
            return array('balance' => '');
        }

        $balance = json_decode($out['output'], TRUE);
        $bal = isset($balance['STATUS']) ? floor($balance['STATUS']) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pay1all.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }

    function pay1allTranStatus($transId, $date = null, $refId = null) {
        $transId = empty($transId) ? 0 : $transId;
        $url = PAY1ALL_URL;
        $input_params = array('userid' => PAY1ALL_USERID, 'pass' => PAY1ALL_PASSWORD, 'csagentid' => $transId, 'fmt' => 'Json');
        $out = $this->General->pay1allApi($url, $input_params);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pay1all.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":pay1allTranStatus: " . $out['output']);
        $outs = json_decode($out['output'],TRUE);

        $status = strtolower($outs['STATUS']);
        $client_id = isset($outs['RPID']) ? $outs['RPID'] : '' ;
        $opr_id = isset($outs['OPID']) ? $outs['OPID']:'';
        $msg    = isset($outs['MSG']) ? $outs['MSG']:' ';

        if ($status == 'success') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' =>  $client_id, 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => $msg);
        } elseif ($status == 'failed') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' =>  $client_id, 'internal_error_code' => '30', 'vendor_response' => $msg);
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' =>  $client_id, 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => $msg);
        }
        return $ret;
    }

    function precMobRecharge($transId,$params,$prodId){
        $url = PREC_URL;
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['prec'];
        $message = $provider . ' ' . $params['mobileNumber'].' '.$params['amount'];
        $inputparams = array('mobileno' => PREC_ID,'message' => $message ,'password' => PREC_PASS,'Tranref' => $transId);
        $out = $this->General->precApi($url,$inputparams);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/prec.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['ErrorCode'];
        $status = isset($out['Data']['Status'])?$out['Data']['Status']:$out['Status'];
        $clientId = $out['Data']['TranId'];
        $opr_id = $out['Data']['OPTId'];
        $remark = $out['Remarks'];

        $err_remarks = array("error occurred. please contact to service provider","exception for invalid ip","invalid ip","exception for invalid password","your account is suspended, contact to service provider","your not authorized. contact your service provider",
                        "operator service is unavailable.","recharge service temporary is unavailable.","same amount hit again ..recharge after 1 hour for same amount & same number","invalid user",
                        "exception for invalid message format","error occured in recharge format","parameter is required","sequence contains no elements","insufficent balance to use service.","configuration is not set properly. contact your service provider.","operator server down.");

        if($err_code == '1' || (strtolower($status) == 'success')){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>'success');
        }
        elseif($err_code == '2' || (strtolower($status) == 'failure') || in_array(strtolower($remark),$err_remarks)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>json_encode($out));
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out['Remarks']);
        }

        return $ret;
    }

    function precDthRecharge($transId,$params,$prodId){
        $url = PREC_URL;
        $prodId = empty($prodId) ? 0 : $prodId;

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['prec'];
        $message = $provider . ' ' . $params['subId'].' '.$params['amount'];
        $inputparams = array('mobileno' => PREC_ID,'message' => $message ,'password' => PREC_PASS,'Tranref' => $transId);
        $out = $this->General->precApi($url,$inputparams);
        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/prec.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['ErrorCode'];
        $status = isset($out['Data']['Status'])?$out['Data']['Status']:$out['Status'];
        $clientId = $out['Data']['TranId'];
        $opr_id = $out['Data']['OPTId'];
        $remark = $out['Remarks'];

        $err_remarks = array("error occurred. please contact to service provider","exception for invalid ip","invalid ip","exception for invalid password","your account is suspended, contact to service provider","your not authorized. contact your service provider",
                        "operator service is unavailable.","recharge service temporary is unavailable.","same amount hit again ..recharge after 1 hour for same amount & same number","invalid user",
                        "exception for invalid message format","error occured in recharge format","parameter is required","sequence contains no elements","insufficent balance to use service.","configuration is not set properly. contact your service provider.","operator server down.");

        if($err_code == '1' || (strtolower($status) == 'success')){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>'success');
        }
        elseif($err_code == '2' || (strtolower($status) == 'failure') || in_array(strtolower($remark),$err_remarks)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>json_encode($out));
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out['Remarks']);
        }

        return $ret;
    }

    function precBalance(){
        $url = PREC_URL;
        $input_params = array('mobileno'=> PREC_ID,'message'=>'BAL','password'=> PREC_PASS,'Tranref'=>'101');
        $out = $this->General->precApi($url, $input_params);

        if (!$out['success']) {
            return array('balance' => '');
        }

        $outs = json_decode($out['output'],TRUE);
        $balance = $outs['Data']['Balance'];
        $bal = isset($balance)? floor($balance):'';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/prec.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }

    function precTranStatus($transId, $date = null, $refId = null) {
        $transId = empty($transId) ? 0 : $transId;
        $url = PREC_URL;
        $message = "STATUS";
        $input_params = array('mobileno' => PREC_ID, 'message' => $message, 'password' => PREC_PASS, 'Tranref' => $transId);
        $out = $this->General->precApi($url, $input_params);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/prec.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":precTranStatus: " . $out['output']);
        $out = json_decode($out['output'], TRUE);
        $status = isset($out['Data']['Status']) ? $out['Data']['Status'] : $out['Status'];
        $err_code = $out['ErrorCode'];

        if (strtolower($status) == 'success') {
            $ret = array('status' => 'success', 'status-code' => $status, 'operator_id' => $out['Data']['OPTId'], 'vendor_id' => $out['Data']['TranId'], 'description' => json_encode($out), 'tranId' => $transId);
        } elseif((in_array(strtolower($status),array('failure','reversal')) || ($err_code == '2')) && (!in_array($err_code,array(4,17)))){
            $ret = array('status' => 'failure', 'status-code' => $status, 'operator_id' => $out['Data']['OPTId'], 'vendor_id' => $out['Data']['TranId'], 'description' => json_encode($out), 'tranId' => $transId);
        } else {
            $ret = array('status' => 'pending', 'status-code' => $status, 'operator_id' => $out['Data']['OPTId'], 'vendor_id' => $out['Data']['TranId'], 'description' => json_encode($out), 'tranId' => $transId);
        }

        return $ret;
    }

    function ashw1MobRecharge($transId, $params, $prodId) {
        $prodId = empty($prodId) ? 0 : $prodId;
        if (in_array($prodId, array('27', '28', '29', '31', '34'))) {
            $params['type'] = 'voucher';
        }
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['ashw1'];
        $mobileNo = $params['mobileNumber'];

        $url = ASHWIN1_RECURL;
        $param = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => ASHWIN1_USER);
        $token = $this->General->ashw1tokenGenerator($param, ASHWIN1_SECRET_KEY);
        $data = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => ASHWIN1_USER);
        $out = $this->General->ashw1Api($url, $data);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ashw1.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);

        $err_code = $out['errorCode'];
        $msg = $out['description'];
        $status = $out['description']['status'];
        $clientId = $out['description']['transactionID'];
        $opr_id = '';

        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors']) ? $out['description']['errors'] : "";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function ashw1DthRecharge($transId, $params, $prodId) {

        $mobileNo = $params['mobileNumber'];
        $sub_Id = $params['subId'];
        $url = ASHWIN1_RECURL;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['ashw1'];
        $param = array('mobile' => $mobileNo, 'subscriber_id' => $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => ASHWIN1_USER);
        $token = $this->General->ashw1tokengenerator($param, ASHWIN1_SECRET_KEY);
        $data = array('mobile' => $mobileNo, 'subscriber_id' => $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => ASHWIN1_USER);
        $out = $this->General->ashw1Api($url, $data);
        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ashw1.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $msg = $out['description'];

        $status = $out['description']['status'];
        $clientId = ($out['description']['transactionID']);
        $opr_id = '';
        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors']) ? $out['description']['errors'] : "";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

     function ashw1Balance() {
        $url = ASHWIN1_BALURL;
        $params = array('user_id' => ASHWIN1_USER);
        $token = $this->General->ashw1tokenGenerator($params, ASHWIN1_SECRET_KEY);
        $input_params = array('user_id' => ASHWIN1_USER, 'secret' => $token);
        $out = $this->General->ashw1Api($url, $input_params);

        if (!$out['success']) {
            return array('balance' => '');
        }

        $out = json_decode($out['output'], TRUE);
        $bal = isset($out['description']['data']['current_balance']) ? floor($out['description']['data']['current_balance']) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ashw1.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }

    function ashw1TranStatus($transId, $date = null, $refId = null) {
        $url = ASHWIN1_STATUS_URL;
        $data = array('transaction_id' => $transId, 'user_id' => ASHWIN1_USER);
        $token = $this->General->ashw1tokenGenerator($data, ASHWIN1_SECRET_KEY);
        $data = array('transaction_id' => $transId, 'user_id' => ASHWIN1_USER, 'secret' => $token);
        $out = $this->General->ashw1Api($url, $data);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/ashw1.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($data) . "Output: " . $out['output']);
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $msg = ($out['description']['data']);
        $status = isset($out['description']['data']['status']) ? ($out['description']['data']['status']) : '';
        $clientId = isset($out['description']['data']['id']) ? ($out['description']['data']['id']) : '';
        $opr_id = isset($out['description']['data']['sys_opr_id']) ? ($out['description']['data']['sys_opr_id']) : '';

        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' =>  $clientId, 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '0') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' =>  $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' =>  $clientId, 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function pay1clickMobRecharge($transId,$params,$prodId){
        $prodId = empty($prodId) ? 0 : $prodId;
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['pay1click'];
        $url = PAY1CLICK_RECURL;
        $inputparams = array('key' => PAY1CLICK_KEY,'password'=> PAY1CLICK_PASS,'mobile'=>$params['mobileNumber'],'amount'=>$params['amount'],'opcode'=>$provider,'requestid'=> $transId,'response_type'=>'XML');
        $out = $this->General->pay1clickApi($url,$inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pay1click.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($inputparams). "<br/>Output=>" . json_encode($out));

        if(!$out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = $this->General->xml2array($out['output']);

        $remark = $out['Recharge_Request_Response']['MSG'];

        $status = strtolower($out['Recharge_Request_Response']['Status']);
        $clientId   = $out['Recharge_Request_Response']['OrderID'];
        $opr_id     = "";

        if('success' == trim($status)){
            $opr_id = $out['Recharge_Request_Response']['TransID'];
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$remark);
        }
        elseif('failure' == trim($status)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$remark);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$remark);
        }

        return $ret;
    }

    function pay1clickDthRecharge($transId,$params,$prodId){
        $prodId = empty($prodId) ? 0 : $prodId;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['pay1click'];
        $url = PAY1CLICK_RECURL;
        $inputparams = array('key' => PAY1CLICK_KEY,'password'=> PAY1CLICK_PASS,'mobile'=>$params['subId'],'amount'=>$params['amount'],'opcode'=>$provider,'requestid'=> $transId,'response_type'=>'XML');

        $out = $this->General->pay1clickApi($url,$inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pay1click.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($inputparams). "<br/>Output=>" . json_encode($out));

        if(!$out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $out = $this->General->xml2array($out['output']);
        $remark = $out['Recharge_Request_Response']['MSG'];
        $status = strtolower($out['Recharge_Request_Response']['Status']);
        $clientId   = $out['Recharge_Request_Response']['OrderID'];
        $opr_id     = "";

        if('success' == trim($status)){
            $opr_id = $out['Recharge_Request_Response']['TransID'];
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$remark);
        }
        elseif('failure' == trim($status)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$remark);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$remark);
        }

        return $ret;
    }

    function pay1clickBalance(){
        $url = PAY1CLICK_BALURL;
        $input_params = array('key' => PAY1CLICK_KEY,'password' => PAY1CLICK_PASS);
        $out = $this->General->pay1clickApi($url, $input_params);

        if (!$out['output']) {
           return array('balance' => '');
        }

        $out = $this->General->xml2array($out['output']);

        $bal = isset($out['User_Balance']['Balance']) ? floor($out['User_Balance']['Balance']) : '';
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pay1click.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }

    function pay1clickTranStatus($transId, $date = null, $refId = null){
        $url = PAY1CLICK_STATUSURL;
        $input_params = array('key' => PAY1CLICK_KEY, 'password' => PAY1CLICK_PASS, 'requestid'=>$transId);
        $out = $this->General->pay1clickApi($url, $input_params);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pay1click.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":pay1clickTranStatus: " . json_encode($out['output']));

        $out = $this->General->xml2array($out['output']);

        $status = $out['Transaction_Status']['Status'];
        $clientId = $out['Transaction_Status']['OrderID'];
        $opr_id = $out['Transaction_Status']['TransID'];

        if(strtolower($status) == 'success'){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'vendor_id' => $clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=> json_encode($out));
        }
        elseif((strtolower($status) == 'failure') || (strtolower($status) == 'rollback')){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code'=>'30', 'vendor_response'=>json_encode($out));
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>json_encode($out));
        }

        return $ret;
    }

    function kracrecMobRecharge($transId, $params, $prodId = null, $res = null) {
        $prodId = empty($prodId) ? 0 : $prodId;
        $recharge_type = 'RR';
        if (in_array($prodId, array('27', '28', '29', '31', '34'))) {
            $params['type'] = 'voucher';
            $recharge_type = 'STV';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['kracrec'];
        $url = KRACREC_URL;
        $mobile = $params['mobileNumber'];
        $message = $recharge_type . ' ' . $provider . ' ' . $mobile . ' ' . $params['amount'] . ' ' . KRACREC_PIN;
        $input_params = array('Mob' => KRACREC_PAY1_MOBILE, 'message' => $message,'myTxid' => $transId,'Source' => 'API');
        $out = $this->General->kracrecApi($url, $input_params);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/kracrec.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params) . "<br/>Output=>" . json_encode($out));

        $out = $out['output'];
        if(strpos($out, ',') !== false){
        $outs = explode(',', $out);
        $status = trim(end(explode(':', $outs[0])));
        $clientId = isset($outs[2]) ? trim(end(explode(':', $outs[2]))) : '';
        $opr_Id = isset($outs[7]) ? trim(end(explode(':', $outs[7]))) : '';
        }
        else {
            $status = $out;
            $clientId = '';
            $opr_Id   = '';
        }

        $err_status = array("your request have been fail", "mobile number must not be less than 10 digits.",
            "sorry...invalid mobilenumber or pinnumber.", "invalid recharge amount.", "insufficient balance for this recharge",
            "you can't send same recharge request for 60 min.", "you can not request for recharge because your distributor have no balance.",
            "sorry..!! your service is temporarily unavailable contact your distributor for further assistance.",
            "please check your request format.you have entered an api request with invalid format.");

        if (strtolower($status) == 'your request have been success') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_Id, 'internal_error_code' => '13', 'vendor_response' => json_encode($out));
        } else if (in_array(strtolower($status), $err_status)) {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => $out);
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'internal_error_code' => '15', 'vendor_response' => $out);
        }
        return $ret;
    }

    function kracrecDthRecharge($transId, $params, $prodId = null) {
        $prodId = empty($prodId) ? 0 : $prodId;
        $recharge_type ='DTH';

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['ka2zrec'];

        $url = KRACREC_URL;
        $mobile = $params['subId'];
        $message = $recharge_type . ' ' . $provider . ' ' . $mobile . ' ' . $params['amount'] . ' ' . KRACREC_PIN;
        $input_params = array('Mob' => KRACREC_PAY1_MOBILE, 'message' => $message,'myTxid' => $transId,'Source' => 'API');
        $out = $this->General->kracrecApi($url, $input_params);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
         	$this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/kracrec.txt", "*DTH Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));
            $out = $out['output'];

        if(strpos($out, ',') !== false)
        {
            $outs = explode(',', $out);
            $status = trim(end(explode(':', $outs[0])));
            $clientId = isset($outs[2]) ? trim(end(explode(':', $outs[2]))) : '';
            $opr_Id = isset($outs[7]) ? trim(end(explode(':', $outs[7]))) : '';
        }
        else
        {
            $status = $out;
            $clientId = '';
            $opr_Id = '';
        }

        $err_status = array("your request have been fail", "mobile number must not be less than 10 digits.",
        "sorry...invalid mobilenumber or pinnumber.", "invalid recharge amount.", "insufficient balance for this recharge",
        "you can't send same recharge request for 60 min.", "you can not request for recharge because your distributor have no balance.",
        "sorry..!! your service is temporarily unavailable contact your distributor for further assistance.",
        "please check your request format.you have entered an api request with invalid format.");

            if (strtolower($status) == 'your request have been success') {
                $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_Id, 'internal_error_code' => '13', 'vendor_response' => $out);
            } else if (in_array(strtolower($status), $err_status)) {
                $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => $out);
            } else {
                $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'internal_error_code' => '15', 'vendor_response' => $out);
            }

        return $ret;
    }

    function kracrecBalance() {
        $url = KRACREC_URL;
        $message = 'Bal '. KRACREC_PIN;
        $input_params = array('Mob' => KRACREC_PAY1_MOBILE, 'message' => $message, 'source' => 'api');
        $out = $this->General->kracrecApi($url, $input_params);
        if (!$out['success']) {
            return array('balance' => '');
        }

        $amount = explode(' ',$out['output']);

        $bal = isset($amount[3]) ? floor($amount[3]) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/kracrec.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);

    }

    function kracrecTranStatus($transId, $date = null, $refId = null) {
        $transId = empty($transId) ? 0 : $transId;
        $url = KRACREC_URL;
        $message =  'mytxid ' . $transId . " " . KRACREC_PIN;
        $input_params = array('Mob' => KRACREC_PAY1_MOBILE, 'message' => $message, 'Source' => 'API');

        $out = $this->General->kracrecApi($url, $input_params);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/kracrec.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":kracrecTranStatus: " . $out['output']);

        $out = $out['output'];
        $outs = explode(',', $out);
        $status = isset($outs[0])?trim(end(explode(':', $outs[0]))):'';
        $opr_id = isset($outs[1])?trim(end(explode('*', $outs[1]))):'';

        if (strtolower($status) == 'success') {
            $ret = array('status' => 'success', 'status-code' => 'success', 'operator_id' => $opr_id, 'vendor_id' => '', 'description' => $out, 'tranId' => $transId);
        } elseif (strtolower($status) == 'fail') {
            $ret = array('status' => 'failure', 'status-code' => 'failure', 'description' => $out, 'tranId' => $transId);
        } else {
            $ret = array('status' => 'pending', 'status-code' => 'pending', 'operator_id' => $opr_id, 'vendor_id' => '', 'description' => $out, 'tranId' => $transId);
        }
        return $ret;
    }

    function stelcomMobRecharge($transId,$params,$prodId){
        $prodId = empty($prodId) ? 0 : $prodId;
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['stelcom'];
        $url = STELCOM_REC_URL;
        $inputparams = array('acc_no' => STELCOM_ACCNO,'api_key' => STELCOM_KEY,'opr_code'=>$provider,'rech_num'=>$params['mobileNumber'],'amount'=>$params['amount'],'client_key'=> $transId);
        $out = $this->General->stelcomApi($url,$inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/stelcom.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($inputparams). "<br/>Output=>" . json_encode($out));

        if(!$out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $outs = explode(',',$out['output']);

        $remark = $outs[7];
        $status = $outs[0];
        $clientId   = $outs[1];
        $opr_id     = "";

        if('success' == trim($status)){
            $opr_id = $outs[6];
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'vendor_id' => $clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$remark);
        }
        elseif('failure' == trim($status)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'vendor_id' => $clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$remark);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'vendor_id' => $clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$remark);
        }

        return $ret;
    }

    function stelcomDthRecharge($transId,$params,$prodId){
        $prodId = empty($prodId) ? 0 : $prodId;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['stelcom'];
        $url = STELCOM_REC_URL;
        $inputparams = array('acc_no' => STELCOM_ACCNO,'api_key' => STELCOM_KEY,'opr_code'=>$provider,'rech_num'=>$params['subId'],'amount'=>$params['amount'],'client_key'=> $transId);
        $out = $this->General->stelcomApi($url,$inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/stelcom.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($inputparams). "<br/>Output=>" . json_encode($out));

        if(!$out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $outs = explode(',',$out['output']);
        $remark = $outs[7];
        $status = $outs[0];
        $clientId   = $outs[1];
        $opr_id     = "";

        if('success' == trim($status)){
            $opr_id = $outs[6];
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$remark);
        }
        elseif('failure' == trim($status)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$remark);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$remark);
        }
        return $ret;

    }

    function stelcomBalance() {
        $url = STELCOM_BAL_URL;
        $input_params = array('acc_no' => STELCOM_ACCNO,'api_key' => STELCOM_KEY);
        $out = $this->General->stelcomApi($url, $input_params);
        if (!$out['success']) {
            return array('balance' => '');
        }
        $outs = $out['output'];
        $amount = $outs;

        $bal = isset($amount) ? floor($amount) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/stelcom.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }

    function stelcomTranStatus($transId, $date = null, $refId = null) {
        $transId = empty($transId) ? 0 : $transId;
        $url = STELCOM_STATUS_URL;
        $input_params = array('acc_no' => STELCOM_ACCNO, 'api_key' => STELCOM_KEY, 'your_key' => $transId,'trans_no' => $refId);

        $out = $this->General->stelcomApi($url, $input_params);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/stelcom.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":kracrecTranStatus: " . $out['output']);

        $out = $out['output'];
        $outs = explode(',', $out);
        $status = isset($outs[0])?$outs[0]:'';
        $opr_id = isset($outs[6])?$outs[6]:'';
        $vendor_id = isset($outs[1])?$outs[1]:'';

        if (strtolower($status) == 'success') {
            $ret = array('status' => 'success', 'status-code' => 'success', 'operator_id' => $opr_id, 'vendor_id' => $vendor_id, 'description' => $out, 'tranId' => $transId);
        } elseif (strtolower($status) == 'failure') {
            $ret = array('status' => 'failure', 'status-code' => 'failure', 'description' => $out, 'tranId' => $transId);
        } else {
            $ret = array('status' => 'pending', 'status-code' => 'pending', 'operator_id' => $opr_id, 'vendor_id' => $vendor_id, 'description' => $out, 'tranId' => $transId);
        }
        return $ret;
    }

    function manimasterMobRecharge($transId,$params,$prodId){
        $url = MANIMASTER_URL;
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['manimaster'];
        $message = $provider . ' ' . $params['mobileNumber'].' '.$params['amount'];
        $inputparams = array('mobileno' => MANIMASTER_ID,'message' => $message ,'password' => MANIMASTER_PASS,'Tranref' => $transId);
        $out = $this->General->manimasterApi($url,$inputparams);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manimaster.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['ErrorCode'];
        $status = isset($out['Data']['Status'])?$out['Data']['Status']:$out['Status'];
        $clientId = $out['Data']['TranId'];
        $opr_id = $out['Data']['OPTId'];
        $remark = $out['Remarks'];

        $err_remarks = array("error occurred. please contact to service provider","exception for invalid ip","invalid ip","exception for invalid password","your account is suspended, contact to service provider","your not authorized. contact your service provider",
                        "operator service is unavailable.","recharge service temporary is unavailable.","same amount hit again ..recharge after 1 hour for same amount & same number","invalid user",
                        "exception for invalid message format","error occured in recharge format","parameter is required","sequence contains no elements","insufficent balance to use service.","configuration is not set properly. contact your service provider.","operator server down.");

        if($err_code == '1' || (strtolower($status) == 'success')){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>'success');
        }
        elseif($err_code == '2' || (strtolower($status) == 'failure') || in_array(strtolower($remark),$err_remarks)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>json_encode($out));
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out['Remarks']);
        }

        return $ret;
    }

    function manimasterDthRecharge($transId,$params,$prodId){
        $url = MANIMASTER_URL;
        $prodId = empty($prodId) ? 0 : $prodId;

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['manimaster'];
        $message = $provider . ' ' . $params['subId'].' '.$params['amount'];
        $inputparams = array('mobileno' => MANIMASTER_ID,'message' => $message ,'password' => MANIMASTER_PASS,'Tranref' => $transId);
        $out = $this->General->manimasterApi($url,$inputparams);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manimaster.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['ErrorCode'];
        $status = isset($out['Data']['Status'])?$out['Data']['Status']:$out['Status'];
        $clientId = $out['Data']['TranId'];
        $opr_id = $out['Data']['OPTId'];
        $remark = $out['Remarks'];

        $err_remarks = array("error occurred. please contact to service provider","exception for invalid ip","invalid ip","exception for invalid password","your account is suspended, contact to service provider","your not authorized. contact your service provider",
                        "operator service is unavailable.","recharge service temporary is unavailable.","same amount hit again ..recharge after 1 hour for same amount & same number","invalid user",
                        "exception for invalid message format","error occured in recharge format","parameter is required","sequence contains no elements","insufficent balance to use service.","configuration is not set properly. contact your service provider.","operator server down.");

        if($err_code == '1' || (strtolower($status) == 'success')){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>'success');
        }
        elseif($err_code == '2' || (strtolower($status) == 'failure') || in_array(strtolower($remark),$err_remarks)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>json_encode($out));
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out['Remarks']);
        }

        return $ret;
    }

    function manimasterBalance(){
        $url = MANIMASTER_URL;
        $input_params = array('mobileno'=> MANIMASTER_ID,'message'=>'BAL','password'=> MANIMASTER_PASS,'Tranref'=>'101');
        $out = $this->General->manimasterApi($url, $input_params);

        if (!$out['success']) {
            return array('balance' => '');
        }

        $outs = json_decode($out['output'],TRUE);

        $balance = $outs['Data']['Balance'];
        $bal = isset($balance)? floor($balance):'';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manimaster.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }

    function manimasterTranStatus($transId, $date = null, $refId = null) {
        $transId = empty($transId) ? 0 : $transId;
        $url = MANIMASTER_URL;
        $message = "STATUS";
        $input_params = array('mobileno' => MANIMASTER_ID, 'message' => $message, 'password' => MANIMASTER_PASS, 'Tranref' => $transId);
        $out = $this->General->manimasterApi($url, $input_params);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manimaster.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":manimasterTranStatus: " . $out['output']);
        $out = json_decode($out['output'], TRUE);
        $status = isset($out['Data']['Status']) ? $out['Data']['Status'] : $out['Status'];
        $err_code = $out['ErrorCode'];

        if (strtolower($status) == 'success') {
            $ret = array('status' => 'success', 'status-code' => $status, 'operator_id' => $out['Data']['OPTId'], 'vendor_id' => $out['Data']['TranId'], 'description' => json_encode($out), 'tranId' => $transId);
        } elseif((in_array(strtolower($status),array('failure','reversal')) || ($err_code == '2')) && (!in_array($err_code,array(4,17)))){
            $ret = array('status' => 'failure', 'status-code' => $status, 'operator_id' => $out['Data']['OPTId'], 'vendor_id' => $out['Data']['TranId'], 'description' => json_encode($out), 'tranId' => $transId);
        } else {
            $ret = array('status' => 'pending', 'status-code' => $status, 'operator_id' => $out['Data']['OPTId'], 'vendor_id' => $out['Data']['TranId'], 'description' => json_encode($out), 'tranId' => $transId);
        }

        return $ret;
    }

    function wellbornMobRecharge($transId,$params,$prodId){
        $prodId = empty($prodId) ? 0 : $prodId;
        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['wellborn'];
        $url = WELLBORN_REC_URL;
        $inputparams = array('acc_no' => WELLBORN_ACCNO,'api_key' => WELLBORN_KEY,'opr_code'=>$provider,'rech_num'=>$params['mobileNumber'],'amount'=>$params['amount'],'client_key'=> $transId);
        $out = $this->General->wellbornApi($url,$inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/wellborn.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($inputparams). "<br/>Output=>" . json_encode($out));

        if(!$out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        $outs = explode(',',$out['output']);

        $remark = $outs[7];
        $status = $outs[0];
        $clientId   = $outs[1];
        $opr_id     = "";

        if('success' == trim($status)){
            $opr_id = $outs[6];
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$remark);
        }
        elseif('failure' == trim($status)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$remark);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$remark);
        }

        return $ret;
    }

    function wellbornDthRecharge($transId,$params,$prodId){
        $prodId = empty($prodId) ? 0 : $prodId;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['wellborn'];
        $url = WELLBORN_REC_URL;
        // $inputparams = array('acc_no' => WELLBORN_ACCNO,'api_key' => WELLBORN_KEY,'opr_code'=>$provider,'rech_num'=>$params['mobileNumber'],'amount'=>$params['amount'],'client_key'=> $transId);
        // $out = $this->General->stelcomApi($url,$inputparams);

        $inputparams = array('acc_no' => WELLBORN_ACCNO,'api_key' => WELLBORN_KEY,'opr_code'=>$provider,'rech_num'=>$params['subId'],'amount'=>$params['amount'],'client_key'=> $transId);
        $out = $this->General->wellbornApi($url,$inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/wellborn.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($inputparams). "<br/>Output=>" . json_encode($out));

        if(!$out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $outs = explode(',',$out['output']);
        $remark = $outs[7];
        $status = $outs[0];
        $clientId   = $outs[1];
        $opr_id     = "";

        if('success' == trim($status)){
            $opr_id = $outs[6];
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$remark);
        }
        elseif('failure' == trim($status)){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'30', 'vendor_response'=>$remark);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$remark);
        }
        return $ret;

    }

    function wellbornBalance(){
        $url = WELLBORN_BAL_URL;
        $input_params = array('acc_no' => WELLBORN_ACCNO,'api_key' => WELLBORN_KEY);
        $out = $this->General->wellbornApi($url, $input_params);
        if (!$out['success']) {
            return array('balance' => '');
        }
        $outs = $out['output'];
        $amount = $outs;

        $bal = isset($amount) ? floor($amount) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/wellborn.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);

    }

    function wellbornTranStatus($transId, $date = null, $refId = null) {
        $transId = empty($transId) ? 0 : $transId;
        $url = WELLBORN_STATUS_URL;
        $input_params = array('acc_no' => WELLBORN_ACCNO, 'api_key' => WELLBORN_KEY, 'your_key' => $transId);

        $out = $this->General->wellbornApi($url, $input_params);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/wellborn.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":kracrecTranStatus: " . $out['output']);

        $out = $out['output'];
        $outs = explode(',', $out);
        $status = isset($outs[0])?$outs[0]:'';
        $opr_id = isset($outs[3])?$outs[3]:'';
        $vendor_id = '';

        if (strtolower($status) == 'success') {
            $ret = array('status' => 'success', 'status-code' => 'success', 'operator_id' => $opr_id, 'vendor_id' => $vendor_id, 'description' => $out, 'tranId' => $transId);
        } elseif (strtolower($status) == 'failure') {
            $ret = array('status' => 'failure', 'status-code' => 'failure', 'description' => $out, 'tranId' => $transId);
        } else {
            $ret = array('status' => 'pending', 'status-code' => 'pending', 'operator_id' => $opr_id, 'vendor_id' => $vendor_id, 'description' => $out, 'tranId' => $transId);
        }
        return $ret;
    }
    
        function nishiMobRecharge($transId, $params, $prodId) {
        $prodId = empty($prodId) ? 0 : $prodId;
        if (in_array($prodId, array('27', '28', '29', '31', '34'))) {
            $params['type'] = 'voucher';
        }
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['nishi'];
        $mobileNo = $params['mobileNumber'];

        $url = NISHI_RECURL;
        $param = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => NISHI_USER);
        $token = $this->General->nishitokenGenerator($param, NISHI_SECRET_KEY);
        $data = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => NISHI_USER);
        $out = $this->General->nishiApi($url, $data);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/nishi.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);

        $err_code = $out['errorCode'];
        $msg  = $out['description'];
        $status = $out['description']['status'];
        $clientId = $out['description']['transactionID'];
        $opr_id = '';

        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function nishiDthRecharge($transId, $params, $prodId) {

        $mobileNo = $params['mobileNumber'];
        $sub_Id   = $params['subId'];
        $url = NISHI_RECURL;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['nishi'];
        $param =  array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => NISHI_USER);
        $token = $this->General->nishitokenGenerator($param, NISHI_SECRET_KEY);
        $data = array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => NISHI_USER);
        $out = $this->General->nishiApi($url, $data);
        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/nishi.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $msg = $out['description'];

        $status = $out['description']['status'];
        $clientId = ($out['description']['transactionID']);
        $opr_id = '';
        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function nishiBalance() {
        $url = NISHI_BALURL;
        $params = array('user_id' => NISHI_USER);
        $token = $this->General->nishitokenGenerator($params, NISHI_SECRET_KEY);
        $input_params = array('user_id' => NISHI_USER, 'secret' => $token);
        $out = $this->General->nishiApi($url, $input_params);

        if (!$out['success']) {
            return array('balance' => '');
        }

        $out = json_decode($out['output'], TRUE);
        $bal = isset($out['description']['data']['current_balance']) ? floor($out['description']['data']['current_balance']) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/nishi.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }


    function nishiTranStatus($transId, $date = null, $refId = null) {
        $url = NISHI_STATUS_URL;
        $data = array('transaction_id' => $transId, 'user_id' => NISHI_USER);
        $token = $this->General->nishitokenGenerator($data, NISHI_SECRET_KEY);
        $data = array('transaction_id' => $transId, 'user_id' => NISHI_USER, 'secret' => $token);
        $out = $this->General->nishiApi($url, $data);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/nishi.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($data) . "Output: " . $out['output']);
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $msg = ($out['description']['data']);
        $status = isset($out['description']['data']['status'])?($out['description']['data']['status']):'';
        $clientId = isset($out['description']['data']['id'])?($out['description']['data']['id']):'' ;
        $opr_id =   isset($out['description']['data']['sys_opr_id'])?($out['description']['data']['sys_opr_id']):'';

        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' =>  $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '0') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' =>  $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }

    function supersaasMobRecharge($transId, $params, $prodId) {
        $prodId = empty($prodId) ? 0 : $prodId;
        if (in_array($prodId, array('27', '28', '29', '31', '34'))) {
            $params['type'] = 'voucher';
        }
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['supersaas'];
        $mobileNo = $params['mobileNumber'];

        $url = SUPERSAAS_RECURL;
        $param = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => SUPERSAAS_USER);
        $token = $this->General->supersaastokenGenerator($param, SUPERSAAS_SECRET_KEY);
        $data = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => SUPERSAAS_USER);
        $out = $this->General->supersaasApi($url, $data);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/supersaas.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);

        $err_code = $out['errorCode'];
        $msg  = $out['description'];
        $status = $out['description']['status'];
        $clientId = $out['description']['transactionID'];
        $opr_id = '';

        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function supersaasDthRecharge($transId, $params, $prodId) {

        $mobileNo = $params['mobileNumber'];
        $sub_Id   = $params['subId'];
        $url = SUPERSAAS_RECURL;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['supersaas'];
        $param =  array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => SUPERSAAS_USER);
        $token = $this->General->supersaastokenGenerator($param, SUPERSAAS_SECRET_KEY);
        $data = array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => SUPERSAAS_USER);
        $out = $this->General->supersaasApi($url, $data);
        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/supersaas.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $msg = $out['description'];

        $status = $out['description']['status'];
        $clientId = ($out['description']['transactionID']);
        $opr_id = '';
        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function supersaasBalance() {
        $url = SUPERSAAS_BALURL;
        $params = array('user_id' => SUPERSAAS_USER);
        $token = $this->General->supersaastokenGenerator($params, SUPERSAAS_SECRET_KEY);
        $input_params = array('user_id' => SUPERSAAS_USER, 'secret' => $token);
        $out = $this->General->supersaasApi($url, $input_params);

        if (!$out['success']) {
            return array('balance' => '');
        }

        $out = json_decode($out['output'], TRUE);
        $bal = isset($out['description']['data']['current_balance']) ? floor($out['description']['data']['current_balance']) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/supersaas.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }


    function supersaasTranStatus($transId, $date = null, $refId = null) {
        $url = SUPERSAAS_STATUS_URL;
        $data = array('transaction_id' => $transId, 'user_id' => SUPERSAAS_USER);
        $token = $this->General->supersaastokenGenerator($data, SUPERSAAS_SECRET_KEY);
        $data = array('transaction_id' => $transId, 'user_id' => SUPERSAAS_USER, 'secret' => $token);
        $out = $this->General->supersaasApi($url, $data);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/supersaas.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($data) . "Output: " . $out['output']);
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $status = isset($out['description']['data']['status'])?($out['description']['data']['status']):'';
        $msg = ($out['description']['data']);
        $clientId = isset($out['description']['data']['id'])?($out['description']['data']['id']):'' ;
        $opr_id =   isset($out['description']['data']['sys_opr_id'])?($out['description']['data']['sys_opr_id']):'';

        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' =>  $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '0') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' =>  $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }              
    function myetopupMobRecharge($transId, $params, $prodId) {
        $prodId = empty($prodId) ? 0 : $prodId;

        if (in_array($prodId, array('27', '28', '29', '31', '34'))) {
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['myetopup'];
        $url = MYETOPUP_RECURL;
        $inputparams = array('apiToken' => MYETOPUP_APITOKEN, 'mn' => $params['mobileNumber'], 'op' => $provider, 'amt' => $params['amount'], 'reqid' => $transId, 'field1' => null, 'field2' => null);

        $out = $this->General->myetopupApi($url, $inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/myetopup.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($inputparams) . "<br/>Output=>" . json_encode($out));

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $out = json_encode($this->General->xml2array($out));
        $out = json_decode($out, true);
        $out = $out['Result'];
        $remark = $out['remark'];
        $status = strtolower($out['status']);
        $clientId = $out['apirefid'];
        $opr_id = "";

        if ('success' == trim($status)) {
            $opr_id = $out['field1'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => $remark);
        } elseif (in_array(trim($status), array('failed', 'frequent'))) {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'operator_id' => $opr_id, 'internal_error_code' => '30', 'vendor_response' => $remark);
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => $remark);
        }

        return $ret;
    }

    function myetopupDthRecharge($transId, $params, $prodId) {
        $prodId = empty($prodId) ? 0 : $prodId;

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['myetopup'];
        $url = MYETOPUP_RECURL;
        $inputparams = array('apiToken' => MYETOPUP_APITOKEN, 'mn' => $params['subId'], 'op' => $provider, 'amt' => $params['amount'], 'reqid' => $transId, 'field1' => null, 'field2' => null);

        $out = $this->General->myetopupApi($url, $inputparams);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/myetopup.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($inputparams) . "<br/>Output=>" . json_encode($out));

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }

        $out = $out['output'];
        $out = json_encode($this->General->xml2array($out));
        $out = json_decode($out, true);
        $out = $out['Result'];
        $remark = $out['remark'];
        $status = strtolower($out['status']);
        $clientId = $out['apirefid'];
        $opr_id = "";

        if ('success' == trim($status)) {
            $opr_id = $out['field1'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => $remark);
        } elseif (in_array(trim($status), array('failed', 'frequent'))) {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'operator_id' => $opr_id, 'internal_error_code' => '30', 'vendor_response' => $remark);
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => $remark);
        }

        return $ret;
    }

    function myetopupBalance() {
        $url = MYETOPUP_BALURL;
        $input_params = array('apiToken' => MYETOPUP_APITOKEN);
        $out = $this->General->myetopupApi($url, $input_params);

        if (!$out['success']) {
            return array('balance' => '');
        }

        $out = $this->General->xml2array($out['output']);
        $balance = $out['string'];

        $bal = isset($balance) ? floor($balance) : '';
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/myetopup .txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }

    function myetopupTranStatus($transId, $date = null, $refId = null) {
        $url = MYETOPUP_STATUSURL;
        $input_params = array('apiToken' => MYETOPUP_APITOKEN, 'reqid' => $transId);
        $out = $this->General->myetopupApi($url, $input_params);
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/myetopup.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":myetopupTranStatus: " . json_encode($out['output']));
        $out = $out['output'];
        $out = json_encode($this->General->xml2array($out));
        $out = json_decode($out, true);
        $out = $out['Result'];
        $status = $out['status'];
        $clientId = $out['reqid'];
        $opr_id = (strtolower($status) == 'success') ? $out['field1'] : '';

        if (strtolower($status) == 'success') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' =>  $clientId, 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($out));
        } elseif (strtolower($status) == 'failed' || strtolower($status) == 'refund') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' =>  $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($out));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($out));
        }

        return $ret;
    }
    function balajisaasMobRecharge($transId, $params, $prodId) {
        $prodId = empty($prodId) ? 0 : $prodId;
        if (in_array($prodId, array('27', '28', '29', '31', '34'))) {
            $params['type'] = 'voucher';
        }
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['balajisaas'];
        $mobileNo = $params['mobileNumber'];

        $url = BALAJISAAS_RECURL;
        $param = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => BALAJISAAS_USER);
        $token = $this->General->balajisaastokenGenerator($param, BALAJISAAS_SECRET_KEY);
        $data = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => BALAJISAAS_USER);
        $out = $this->General->balajisaasApi($url, $data);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/balajisaas.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);

        $err_code = $out['errorCode'];
        $msg  = $out['description'];
        $status = $out['description']['status'];
        $clientId = $out['description']['transactionID'];
        $opr_id = '';

        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function balajisaasDthRecharge($transId, $params, $prodId) {

        $mobileNo = $params['mobileNumber'];
        $sub_Id   = $params['subId'];
        $url = BALAJISAAS_RECURL;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['balajisaas'];
        $param =  array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => BALAJISAAS_USER);
        $token = $this->General->balajisaastokenGenerator($param, BALAJISAAS_SECRET_KEY);
        $data = array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => BALAJISAAS_USER);
        $out = $this->General->balajisaasApi($url, $data);
        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/balajisaas.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $msg = $out['description'];

        $status = $out['description']['status'];
        $clientId = ($out['description']['transactionID']);
        $opr_id = '';
        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function balajisaasBalance() {
        $url = BALAJISAAS_BALURL;
        $params = array('user_id' => BALAJISAAS_USER);
        $token = $this->General->balajisaastokenGenerator($params, BALAJISAAS_SECRET_KEY);
        $input_params = array('user_id' => BALAJISAAS_USER, 'secret' => $token);
        $out = $this->General->balajisaasApi($url, $input_params);

        if (!$out['success']) {
            return array('balance' => '');
        }

        $out = json_decode($out['output'], TRUE);
        $bal = isset($out['description']['data']['current_balance']) ? floor($out['description']['data']['current_balance']) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/balajisaas.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }


    function balajisaasTranStatus($transId, $date = null, $refId = null) {
        $url = BALAJISAAS_STATUS_URL;
        $data = array('transaction_id' => $transId, 'user_id' => BALAJISAAS_USER);
        $token = $this->General->balajisaastokenGenerator($data, BALAJISAAS_SECRET_KEY);
        $data = array('transaction_id' => $transId, 'user_id' => BALAJISAAS_USER, 'secret' => $token);
        $out = $this->General->balajisaasApi($url, $data);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/balajisaas.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($data) . "Output: " . $out['output']);
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $status = isset($out['description']['data']['status'])?($out['description']['data']['status']):'';
        $msg = ($out['description']['data']);
        $clientId = isset($out['description']['data']['id'])?($out['description']['data']['id']):'' ;
        $opr_id =   isset($out['description']['data']['sys_opr_id'])?($out['description']['data']['sys_opr_id']):'';

        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' =>  $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '0') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' =>  $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' =>  $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }
    
    function pratisaasMobRecharge($transId, $params, $prodId) {
        $prodId = empty($prodId) ? 0 : $prodId;
        if (in_array($prodId, array('27', '28', '29', '31', '34'))) {
            $params['type'] = 'voucher';
        }
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['pratisaas'];
        $mobileNo = $params['mobileNumber'];

        $url = PRATISAAS_RECURL;
        $param = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => PRATISAAS_USER);
        $token = $this->General->pratisaastokenGenerator($param, PRATISAAS_SECRET_KEY);
        $data = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => PRATISAAS_USER);
        $out = $this->General->pratisaasApi($url, $data);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pratisaas.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);

        $err_code = $out['errorCode'];
        $msg  = $out['description'];
        $status = $out['description']['status'];
        $clientId = $out['description']['transactionID'];
        $opr_id = '';

        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function pratisaasDthRecharge($transId, $params, $prodId) {

        $mobileNo = $params['mobileNumber'];
        $sub_Id   = $params['subId'];
        $url = PRATISAAS_RECURL;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['pratisaas'];
        $param =  array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => PRATISAAS_USER);
        $token = $this->General->pratisaastokenGenerator($param, PRATISAAS_SECRET_KEY);
        $data = array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => PRATISAAS_USER);
        $out = $this->General->pratisaasApi($url, $data);
        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pratisaas.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $msg = $out['description'];

        $status = $out['description']['status'];
        $clientId = ($out['description']['transactionID']);
        $opr_id = '';
        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function pratisaasBalance() {
        $url = PRATISAAS_BALURL;
        $params = array('user_id' => PRATISAAS_USER);
        $token = $this->General->pratisaastokenGenerator($params, PRATISAAS_SECRET_KEY);
        $input_params = array('user_id' => PRATISAAS_USER, 'secret' => $token);
        $out = $this->General->pratisaasApi($url, $input_params);

        if (!$out['success']) {
            return array('balance' => '');
        }

        $out = json_decode($out['output'], TRUE);
        $bal = isset($out['description']['data']['current_balance']) ? floor($out['description']['data']['current_balance']) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pratisaas.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }


    function pratisaasTranStatus($transId, $date = null, $refId = null) {
        $url = PRATISAAS_STATUS_URL;
        $data = array('transaction_id' => $transId, 'user_id' => PRATISAAS_USER);
        $token = $this->General->pratisaastokenGenerator($data, PRATISAAS_SECRET_KEY);
        $data = array('transaction_id' => $transId, 'user_id' => PRATISAAS_USER, 'secret' => $token);
        $out = $this->General->pratisaasApi($url, $data);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pratisaas.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($data) . "Output: " . $out['output']);
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $status = isset($out['description']['data']['status'])?($out['description']['data']['status']):'';
        $msg = ($out['description']['data']);
        $clientId = isset($out['description']['data']['id'])?($out['description']['data']['id']):'' ;
        $opr_id =   isset($out['description']['data']['sys_opr_id'])?($out['description']['data']['sys_opr_id']):'';

        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' =>  $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '0') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' =>  $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }    

    function osssaasMobRecharge($transId, $params, $prodId) {
        $prodId = empty($prodId) ? 0 : $prodId;
        if (in_array($prodId, array('27', '28', '29', '31', '34'))) {
            $params['type'] = 'voucher';
        }
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['osssaas'];
        $mobileNo = $params['mobileNumber'];

        $url = OSSSAAS_RECURL;
        $param = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => OSSSAAS_USER);
        $token = $this->General->osssaastokenGenerator($param, OSSSAAS_SECRET_KEY);
        $data = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => OSSSAAS_USER);
        $out = $this->General->osssaasApi($url, $data);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/osssaas.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);

        $err_code = $out['errorCode'];
        $msg  = $out['description'];
        $status = $out['description']['status'];
        $clientId = $out['description']['transactionID'];
        $opr_id = '';

        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function osssaasDthRecharge($transId, $params, $prodId) {

        $mobileNo = $params['mobileNumber'];
        $sub_Id   = $params['subId'];
        $url = OSSSAAS_RECURL;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['osssaas'];
        $param =  array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => OSSSAAS_USER);
        $token = $this->General->osssaastokenGenerator($param, OSSSAAS_SECRET_KEY);
        $data = array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => OSSSAAS_USER);
        $out = $this->General->osssaasApi($url, $data);
        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/osssaas.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $msg = $out['description'];

        $status = $out['description']['status'];
        $clientId = ($out['description']['transactionID']);
        $opr_id = '';
        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function osssaasBalance() {
        $url = OSSSAAS_BALURL;
        $params = array('user_id' => OSSSAAS_USER);
        $token = $this->General->osssaastokenGenerator($params, OSSSAAS_SECRET_KEY);
        $input_params = array('user_id' => OSSSAAS_USER, 'secret' => $token);
        $out = $this->General->osssaasApi($url, $input_params);

        if (!$out['success']) {
            return array('balance' => '');
        }

        $out = json_decode($out['output'], TRUE);
        $bal = isset($out['description']['data']['current_balance']) ? floor($out['description']['data']['current_balance']) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/osssaas.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }


    function osssaasTranStatus($transId, $date = null, $refId = null) {
        $url = OSSSAAS_STATUS_URL;
        $data = array('transaction_id' => $transId, 'user_id' => OSSSAAS_USER);
        $token = $this->General->osssaastokenGenerator($data, OSSSAAS_SECRET_KEY);
        $data = array('transaction_id' => $transId, 'user_id' => OSSSAAS_USER, 'secret' => $token);
        $out = $this->General->osssaasApi($url, $data);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/osssaas.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($data) . "Output: " . $out['output']);
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $status = isset($out['description']['data']['status'])?($out['description']['data']['status']):'';
        $msg = ($out['description']['data']);
        $clientId = isset($out['description']['data']['id'])?($out['description']['data']['id']):'' ;
        $opr_id =   isset($out['description']['data']['sys_opr_id'])?($out['description']['data']['sys_opr_id']):'';

        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' =>  $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '0') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' =>  $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' =>  $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }    
       

    function manglamvodMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];

        $amount = intval($params['amount']);

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['manglamvod'];

        $vendor = 184;

        $url = MANGLAMVOD_RECURL;

        $message = $provider . $mobileNo . "A" . $amount . "REF" . $transId;

        $out = $this->General->manglamvodApi($url, array('login_id'=>MANGLAMVOD_USERID, 'transaction_password'=>MANGLAMVOD_TXNPWD, 'message'=>$message, 'response_type'=>'CSV'));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];

        $res = explode(",", $out);

        $txnId =  ! empty($res[1]) ? $res[1] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manglamvod.txt", "*Mob Recharge*: Input=> $transId<br/>$out<br/>params=>" . json_encode($params));

        if($res[0] == 'Failure'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$res[5]);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$res[5]);
        }
    }

    function manglamvodDthRecharge($transId, $params, $prodId){
        $mobileNo = $params['subId'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['manglamvod'];

        $vendor = 184;

        $url = MANGLAMVOD_RECURL;

        $message = $provider . $mobileNo . "A" . $amount . "REF" . $transId;

        $out = $this->General->manglamvodApi($url, array('login_id'=>MANGLAMVOD_USERID, 'transaction_password'=>MANGLAMVOD_TXNPWD, 'message'=>$message, 'response_type'=>'CSV'));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }
        
        $out = $out['output'];

        $res = explode(",", $out);

        $txnId =  ! empty($res[1]) ? $res[1] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manglamvod.txt", "*dth Recharge*: Input=> $transId<br/>$out<br/>params=>" . json_encode($params));

        if($res[0] == 'Failure'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$txnId, 'internal_error_code'=>'30', 'vendor_response'=>$res[5]);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$txnId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$res[5]);
        }
    }

    function manglamvodBalance(){
        $vendor_id = 184;

        $url = MANGLAMVOD_BALURL;

        $out = $this->General->manglamvodApi($url, array('login_id'=>MANGLAMVOD_USERID, 'transaction_password'=>MANGLAMVOD_TXNPWD));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out = trim($out['output']);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manglamvod.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");

        return array('balance'=>$out);
    }

    function manglamvodTranStatus($transId, $date = null, $refId = null){
        $url = MANGLAMVOD_TRANSURL;
        $operator_id = $vendor_id = "";
        $out = $this->General->manglamvodApi($url, array('login_id'=>MANGLAMVOD_USERID, 'transaction_password'=>MANGLAMVOD_TXNPWD, 'CLIENTID'=>$transId, 'response_type'=>'XML'));

        $out = $this->General->xml2array($out['output']);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/manglamvod.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":manglamvodTranStatus: " . json_encode($out));

        if($out['RESPONSE']['STATUS'] == 'Success'){
            $ret = array('status'=>'success', 'status-code'=>'success', 'description'=>$out, 'vendor_id' => $transId,'vendor_id'=>$out['RESPONSE']['TRID'],'operator_id'=>$out['RESPONSE']['MESSAGE']);
        }
        elseif($out['RESPONSE']['STATUS'] == 'Failure' && $out['RESPONSE']['MESSAGE'] == 'Transaction Not Found' && intVal((time() - strtotime($date)) / 86400) >= 2){
            $ret = array('status'=>'status not available. Kindly check on vendor\'s panel', 'status-code'=>'pending', 'description'=>$out['RESPONSE']['MESSAGE'], 'tranId'=>$transId, 'vendor_id'=>'', 'operator_id'=>'');
        }
        else if($out['RESPONSE']['STATUS'] == 'Failure'){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'description'=>$out['RESPONSE']['MESSAGE'], 'vendor_id' => $transId,'vendor_id'=>$out['RESPONSE']['TRID'],'operator_id'=>'');
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'description'=>$out, 'vendor_id' => $transId,'vendor_id'=>$out['RESPONSE']['TRID'],'operator_id'=>'');

    }
			return $ret;
}

       function swamirapiMobRecharge($transId, $params, $prodId = null){
        $prodId = empty($prodId) ? 0 : $prodId;
        $rec_type = 0;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
            //$rec_type = 2;
        }

        $opr_code = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['swamirapi'];
        $vendor = SWAMIRAJAPI_VENDOR_ID;
        $url = SWAMIRAJAPI_RECHARGE_URL;
        

        $out = $this->General->swamirapiApi($url, array('UserName'=>SWAMIRAJAPI_UN, 'Password'=>SWAMIRAJAPI_PWD, 'MobileToRecharge'=>$params['mobileNumber'], 'APIAccountRef'=>$transId,'Amount'=>$params['amount'],'RechargeVia'=>4,'TypeOfRecharge'=>$rec_type,'OperatorCode'=>$opr_code,'type'=>'recharge'));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }

        }        
        $out = json_decode($out['output'],TRUE);
        $status = $out['DoRechargeResponse']['Code'];
        $clientId = $out['DoRechargeResponse']['ReferenceID'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/swamirajapi.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "<br/>params=>" . json_encode($params));

        if(!empty($status) && in_array($status , array('SR101','SR102','SR103','SR104','SR105','SR106','SR107','SR108','SR109','SR110','SR111','SR112','SR999','SR1000'))){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$out['DoRechargeResponse']['Message']);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out['DoRechargeResponse']['Message']);
        }

        return $ret;
        }

    function swamirapiDthRecharge($transId, $params, $prodId = null){
        $prodId = empty($prodId) ? 0 : $prodId;
        $rec_type = 0;

        $opr_code = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['swamirapi'];
        $vendor = SWAMIRAJAPI_VENDOR_ID;
        $url = SWAMIRAJAPI_RECHARGE_URL;

        $out = $this->General->swamirapiApi($url, array('UserName'=>SWAMIRAJAPI_UN, 'Password'=>SWAMIRAJAPI_PWD, 'MobileToRecharge'=>$params['subId'], 'APIAccountRef'=>$transId,'Amount'=>$params['amount'],'RechargeVia'=>4,'TypeOfRecharge'=>$rec_type,'OperatorCode'=>$opr_code,'type'=>'recharge'));
        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }        
        $out = json_decode($out['output'],TRUE);
        $status = $out['DoRechargeResponse']['Code'];
        $clientId = $out['DoRechargeResponse']['ReferenceID'];

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/swamirajapi.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($out) . "<br/>params=>" . json_encode($params));

        if(!empty($status) && in_array($status , array('SR101','SR102','SR103','SR104','SR105','SR106','SR107','SR108','SR109','SR110','SR111','SR112','SR999','SR1000'))){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'operator_id'=>'', 'internal_error_code'=>'30', 'vendor_response'=>$out['DoRechargeResponse']['Message']);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$out['DoRechargeResponse']['Message']);
        }

        return $ret;
    }
    function swamirapiBalance() {
        $vendor_id = SWAMIRAJAPI_VENDOR_ID;
        $url = SWAMIRAJAPI_BALANCE_URL;

        $out = $this->General->swamirapiApi($url, array('UserName' => SWAMIRAJAPI_UN, 'Password' => SWAMIRAJAPI_PWD, 'type' => 'balance'));

        if (!$out['success']) {
            return array('balance' => '');
        }

        $out = $out['output'];
        $out = json_decode($out, TRUE);

        if (array_key_exists('Code', $out['MyBalanceResponse']) && strtolower($out['MyBalanceResponse']['Code']) == 'sr113') {
            $bal = trim(end(explode(":", $out['MyBalanceResponse']['Message'])));
        } else {
            $bal = '';
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/swamirajapi.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");

        return array('balance' => $bal);
    }

    function swamirapiTranStatus($transId, $date = null, $refId = null) {
        $transId = empty($transId) ? 0 : $transId;
        $url = SWAMIRAJAPI_STATUS_URL;
        $out = $this->General->swamirapiApi($url, array('UserName' => SWAMIRAJAPI_UN, 'Password' => SWAMIRAJAPI_PWD, 'APIAccountRef' => $transId,'type'=>'status'));
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/swamirajapi.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":speedrecTranStatus: " . $out['output']);

        $out = json_decode($out['output'], TRUE);
        $clientId = !empty($out['CheckStatusResponse']['ReferenceID'])?$out['CheckStatusResponse']['ReferenceID']:'';
        $status = isset($out['CheckStatusResponse']['Code'])?$out['CheckStatusResponse']['Code']:'';
//        $txnId = trim(end(explode(":",$out['CheckStatusResponse']['Message'])));
        $txnId = trim(reset(explode(",",end(explode(":",$out['CheckStatusResponse']['Message'])))));
        // $status = trim(reset(explode("\n",end(explode(":",$out['CheckStatusResponse']['Message'])))));
                  
        if($status == 1) {
            $ret = array('status' => 'success', 'status-code' => 'success', 'vendor_id'=>$clientId,'operator_id'=>$txnId, 'description' => $out['CheckStatusResponse']['Message'], 'tranId' => $clientId);
        }
        elseif($status == 2){
            $ret = array('status' => 'failure', 'status-code' => 'failure', 'vendor_id'=>$clientId,'operator_id'=>$txnId, 'description' => $out['CheckStatusResponse']['Message'], 'tranId' => $clientId);
        }
        else{
            $ret = array('status' => 'pending', 'status-code' => 'pending', 'vendor_id'=>$clientId,'operator_id'=>$txnId, 'description' => $out['CheckStatusResponse']['Message'], 'tranId' => $clientId);
        }

        return $ret;
    }
    function rajsaasMobRecharge($transId, $params, $prodId) {
        $prodId = empty($prodId) ? 0 : $prodId;
        if (in_array($prodId, array('27', '28', '29', '31', '34'))) {
            $params['type'] = 'voucher';
        }
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['rajsaas'];
        $mobileNo = $params['mobileNumber'];

        $url = RAJSAAS_RECURL;
        $param = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => RAJSAAS_USER);
        $token = $this->General->saastokenGenerator($param, RAJSAAS_SECRET_KEY);
        $data = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => RAJSAAS_USER);
        $out = $this->General->saasApi($url, $data);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rajsaas.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);

        $err_code = $out['errorCode'];
        $msg  = $out['description'];
        $status = $out['description']['status'];
        $clientId = $out['description']['transactionID'];
        $opr_id = '';

        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function rajsaasDthRecharge($transId, $params, $prodId) {

        $mobileNo = $params['mobileNumber'];
        $sub_Id   = $params['subId'];
        $url = RAJSAAS_RECURL;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['rajsaas'];
        $param =  array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => RAJSAAS_USER);
        $token = $this->General->saastokenGenerator($param, RAJSAAS_SECRET_KEY);
        $data = array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => RAJSAAS_USER);
        $out = $this->General->saasApi($url, $data);
        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rajsaas.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $msg = $out['description'];

        $status = $out['description']['status'];
        $clientId = ($out['description']['transactionID']);
        $opr_id = '';
        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function rajsaasBalance() {
        $url = RAJSAAS_BALURL;
        $params = array('user_id' => RAJSAAS_USER);
        $token = $this->General->saastokenGenerator($params, RAJSAAS_SECRET_KEY);
        $input_params = array('user_id' => RAJSAAS_USER, 'secret' => $token);
        $out = $this->General->saasApi($url, $input_params);

        if (!$out['success']) {
            return array('balance' => '');
        }

        $out = json_decode($out['output'], TRUE);
        $bal = isset($out['description']['data']['current_balance']) ? floor($out['description']['data']['current_balance']) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rajsaas.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }


    function rajsaasTranStatus($transId, $date = null, $refId = null) {
        $url = RAJSAAS_STATUS_URL;
        $data = array('transaction_id' => $transId, 'user_id' => RAJSAAS_USER);
        $token = $this->General->saastokenGenerator($data, RAJSAAS_SECRET_KEY);
        $data = array('transaction_id' => $transId, 'user_id' => RAJSAAS_USER, 'secret' => $token);
        $out = $this->General->saasApi($url, $data);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/rajsaas.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($data) . "Output: " . $out['output']);
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $status = isset($out['description']['data']['status'])?($out['description']['data']['status']):'';
        $msg = ($out['description']['data']);
        $clientId = isset($out['description']['data']['id'])?($out['description']['data']['id']):'' ;
        $opr_id =   isset($out['description']['data']['sys_opr_id'])?($out['description']['data']['sys_opr_id']):'';

        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id'  => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '0') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' =>  $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' =>  $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }    

    function kumarsaasMobRecharge($transId, $params, $prodId) {
        $prodId = empty($prodId) ? 0 : $prodId;
        if (in_array($prodId, array('27', '28', '29', '31', '34'))) {
            $params['type'] = 'voucher';
        }
        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['kumarsaas'];
        $mobileNo = $params['mobileNumber'];

        $url = KUMARSAAS_RECURL;
        $param = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => KUMARSAAS_USER);
        $token = $this->General->saastokenGenerator($param, KUMARSAAS_SECRET_KEY);
        $data = array('mobile' => $mobileNo, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => KUMARSAAS_USER);
        $out = $this->General->saasApi($url, $data);

        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/kumarsaas.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);

        $err_code = $out['errorCode'];
        $msg  = $out['description'];
        $status = $out['description']['status'];
        $clientId = $out['description']['transactionID'];
        $opr_id = '';

        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function kumarsaasDthRecharge($transId, $params, $prodId) {

        $mobileNo = $params['mobileNumber'];
        $sub_Id   = $params['subId'];
        $url = KUMARSAAS_RECURL;
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['kumarsaas'];
        $param =  array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'user_id' => KUMARSAAS_USER);
        $token = $this->General->saastokenGenerator($param, KUMARSAAS_SECRET_KEY);
        $data = array('mobile' => $mobileNo,'subscriber_id'=> $sub_Id, 'operator_id' => $provider, 'amount' => $params['amount'], 'client_ref_id' => $transId, 'secret' => $token, 'user_id' => KUMARSAAS_USER);
        $out = $this->General->saasApi($url, $data);
        if (!$out['success']) {
            if ($out['timeout']) {
                return array('status' => 'failure', 'code' => '14', 'description' => $this->Shop->errors(14), 'tranId' => '', 'pinRefNo' => '', 'operator_id' => '', 'internal_error_code' => '14', 'vendor_response' => 'Not able to connect to server');
            }
        }
        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/kumarsaas.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($data) . "<br/>Output=>" . json_encode($out));
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $msg = $out['description'];

        $status = $out['description']['status'];
        $clientId = ($out['description']['transactionID']);
        $opr_id = '';
        if (($err_code == '0') && ($status == '1')) {
            $opr_id = $out['description']['sys_opr_id'];
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13','vendor_response'=> json_encode($msg));
        }
        elseif (($err_code > 0) || ($status == '0')) {
            $msg = isset($out['description']['errors'])?$out['description']['errors']:"";
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'tranId' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));

        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }
        return $ret;
    }

    function kumarsaasBalance() {
        $url = KUMARSAAS_BALURL;
        $params = array('user_id' => KUMARSAAS_USER);
        $token = $this->General->saastokenGenerator($params, KUMARSAAS_SECRET_KEY);
        $input_params = array('user_id' => KUMARSAAS_USER, 'secret' => $token);
        $out = $this->General->saasApi($url, $input_params);
        if (!$out['success']) {
            return array('balance' => '');
        }

        $out = json_decode($out['output'], TRUE);
        $bal = isset($out['description']['data']['current_balance']) ? floor($out['description']['data']['current_balance']) : '';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/kumarsaas.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");
        return array('balance' => $bal);
    }


    function kumarsaasTranStatus($transId, $date = null, $refId = null) {
        $url = KUMARSAAS_STATUS_URL;
        $data = array('transaction_id' => $transId, 'user_id' => KUMARSAAS_USER);
        $token = $this->General->saastokenGenerator($data, KUMARSAAS_SECRET_KEY);
        $data = array('transaction_id' => $transId, 'user_id' => KUMARSAAS_USER, 'secret' => $token);
        $out = $this->General->saasApi($url, $data);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/kumarsaas.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($data) . "Output: " . $out['output']);
        $out = json_decode($out['output'], TRUE);
        $err_code = $out['errorCode'];
        $status = isset($out['description']['data']['status'])?($out['description']['data']['status']):'';
        $msg = ($out['description']['data']);
        $clientId = isset($out['description']['data']['id'])?($out['description']['data']['id']):'' ;
        $opr_id =   isset($out['description']['data']['sys_opr_id'])?($out['description']['data']['sys_opr_id']):'';

        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' =>  $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '0') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' =>  $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' =>  $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }    

    function techmateMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['techmate'];

        $vendor = TECHMATE_VENDOR_ID;
        $url = TECHMATE_URL;
        $input_params = array('userid'=>TECHMATE_UN,'pass'=>TECHMATE_PWD,'mob'=>$params['mobileNumber'],'opt'=>$provider,'amt'=>$params['amount'], 'agentid'=>$transId,'fmt'=>'json');

        $out = $this->General->techmateApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/techmate.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

        $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['RPID'];
        $opr_id = $out['OPID'];

        if(strtolower($status) == 'failed'){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 'success'){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function techmateDthRecharge($transId, $params, $prodId)
    {
        $mobileNo = $params['subId'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['techmate'];

        $url = TECHMATE_URL;
        $message = $provider."".$params['subId']."".$params['amount'];
        $input_params = array('userid'=>TECHMATE_UN,'pass'=>TECHMATE_PWD,'mob'=>$params['subId'],'opt'=>$provider,'amt'=>$params['amount'], 'agentid'=>$transId,'fmt'=>'json');
        $out = $this->General->techmateApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/techmate.txt", "*DTH Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

        $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['RPID'];
        $opr_id = $out['OPID'];

        if(strtolower($status) == 'failed'){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 'success'){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function techmateBalance(){
       
        $url = TECHMATE_URL;
        $input_params = array('userid'=>TECHMATE_UN,'pass'=>TECHMATE_PWD,'Get'=>'CB');
        $out = $this->General->techmateApi($url,$input_params);

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $out['output'] = $this->General->xml2array($out['output']);

        $out = $out['output'];

        $bal = isset($out['NODES']['STATUS'])?floor($out['NODES']['STATUS']):'';

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/techmate.txt", date('Y-m-d H:i:s') . ":Balance Check: $bal");

        return array('balance'=>$bal);
    }

    function techmateTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = TECHMATE_URL;
        $input_params = array('userid'=>TECHMATE_UN,'pass'=>TECHMATE_PWD,'csagentid'=>$transId);
        $out = $this->General->techmateApi($url,$input_params);

        $out = $this->General->xml2array($out['output']);

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/techmate.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":techmateTranStatus: " . json_encode($out));

        $status = $out['NODES']['STATUS'];
        $vendor_id = $out['NODES']['RPID'];
        $opr_id = $out['NODES']['OPID'];

        if(strtolower($status) == 'success'){
            $ret = array('status'=>$status, 'status-code'=>'success','operator_id'=>$opr_id,'vendor_id'=>$vendor_id, 'description'=>$out, 'tranId'=>$transId);
        }
        elseif(strtolower($status) == 'request accepted'){
            $ret = array('status'=>$status, 'status-code'=>'pending','operator_id'=>$opr_id,'vendor_id'=>$vendor_id, 'description'=>$out, 'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>$status, 'status-code'=>'failure', 'description'=>$out, 'tranId'=>$transId);
        }
        return $ret;
    }

    function varsharoboMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['varsharobo'];

        $id = VARSHA_APIID;
        $url = ROBO_RECURL;
        $pass = VARSHA_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/varsharobo.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }        
        return $ret;
    }  
    
    function varsharoboDthRecharge($transId, $params, $prodId)
    {

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['varsharobo'];


        $id = VARSHA_APIID;
        $url = ROBO_RECURL;
        $pass = VARSHA_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/varsharobo.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }        
        return $ret;
    }    
    function varsharoboTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = ROBO_STATUSURL;
        $input_params = array('Apimember_id'=>VARSHA_APIID,'Api_password'=>VARSHA_PASS,'Member_request_txnid'=>$transId);
        $out = $this->General->RoboApi($url,$input_params);
        

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/varsharobo.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":techmateTranStatus: " . json_encode($out));

        $out = json_decode($out['output'], TRUE);
        $status = $out['STATUS'];
        $msg = $out['MESSAGE'];
        $clientId = $out['ORDERID'];
        $opr_id =   $out['OPTRANSID'];

        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' =>  $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '3') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }

    function qubaroboMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['qubarobo'];

        $id = QUBA_ROBO_APIID;
        $url = ROBO_RECURL;
        $pass = QUBA_ROBO_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/qubarobo.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function qubaroboDthRecharge($transId, $params, $prodId = null, $res = null)
    {
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['qubarobo'];

        $id = QUBA_ROBO_APIID;
        $url = ROBO_RECURL;
        $pass = QUBA_ROBO_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/qubarobo.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }    
    
    function qubaroboTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = ROBO_STATUSURL;
        $input_params = array('Apimember_id'=>QUBA_ROBO_APIID,'Api_password'=>QUBA_ROBO_PASS,'Member_request_txnid'=>$transId);
        $out = $this->General->RoboApi($url,$input_params);
        

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/qubarobo.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":qubaroboTranStatus: " . json_encode($out));

        $out = json_decode($out['output'], TRUE);
        $status   = $out['STATUS'];
        $msg      = $out['MESSAGE'];
        $clientId = $out['ORDERID'];
        $opr_id =   $out['OPTRANSID'];
        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '3') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }
    function aventideaMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['aventidea'];

        $id = AVENTIDEA_APIID;
        $url = ROBO_RECURL;
        $pass = AVENTIDEA_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aventidea.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function aventideaDthRecharge($transId, $params, $prodId = null, $res = null)
    {
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['aventidea'];

        $id = AVENTIDEA_APIID;
        $url = ROBO_RECURL;
        $pass = AVENTIDEA_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aventidea.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }    
    
    function aventideaTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = ROBO_STATUSURL;
        $input_params = array('Apimember_id'=>AVENTIDEA_APIID,'Api_password'=>AVENTIDEA_PASS,'Member_request_txnid'=>$transId);
        $out = $this->General->RoboApi($url,$input_params);
        

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aventidea.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":aventideaTranStatus: " . json_encode($out));

        $out = json_decode($out['output'], TRUE);
        $status   = $out['STATUS'];
        $msg      = $out['MESSAGE'];
        $clientId = $out['ORDERID'];
        $opr_id =   $out['OPTRANSID'];
        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '3') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }

        function threeplusMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['threeplus'];

        $id = threeplus_APIID;
        $url = ROBO_RECURL;
        $pass = threeplus_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/threeplus.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function threeplusDthRecharge($transId, $params, $prodId = null, $res = null)
    {
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['threeplus'];

        $id = threeplus_APIID;
        $url = ROBO_RECURL;
        $pass = threeplus_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/threeplus.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }    
    
    function threeplusTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = ROBO_STATUSURL;
        $input_params = array('Apimember_id'=>threeplus_APIID,'Api_password'=>threeplus_PASS,'Member_request_txnid'=>$transId);
        $out = $this->General->RoboApi($url,$input_params);
        

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/threeplus.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":threeplusTranStatus: " . json_encode($out));

        $out = json_decode($out['output'], TRUE);
        $status   = $out['STATUS'];
        $msg      = $out['MESSAGE'];
        $clientId = $out['ORDERID'];
        $opr_id =   $out['OPTRANSID'];
        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '3') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }

    function pintooslsMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['pintoosls'];

        $id = PINTOOS_APIID;
        $url = ROBO_RECURL;
        $pass = PINTOOS_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pintoosls.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function pintooslsDthRecharge($transId, $params, $prodId = null, $res = null)
    {
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['pintoosls'];

        $id = PINTOOS_APIID;
        $url = ROBO_RECURL;
        $pass = PINTOOS_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pintoosls.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }    
    
    function pintooslsTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = ROBO_STATUSURL;
        $input_params = array('Apimember_id'=>PINTOOS_APIID,'Api_password'=>PINTOOS_PASS,'Member_request_txnid'=>$transId);
        $out = $this->General->RoboApi($url,$input_params);
        

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/pintoosls.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":techmateTranStatus: " . json_encode($out));

        $out = json_decode($out['output'], TRUE);
        $status   = $out['STATUS'];
        $msg      = $out['MESSAGE'];
        $clientId = $out['ORDERID'];
        $opr_id =   $out['OPTRANSID'];
        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '3') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }

    function aventerpMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['aventerp'];

        $id = AVEnterp_APIID;
        $url = ROBO_RECURL;
        $pass = AVEnterp_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aventerp.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function aventerpDthRecharge($transId, $params, $prodId = null, $res = null)
    {
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['aventerp'];

        $id = AVEnterp_APIID;
        $url = ROBO_RECURL;
        $pass = AVEnterp_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aventerp.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }    
    
    function aventerpTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = ROBO_STATUSURL;
        $input_params = array('Apimember_id'=>AVEnterp_APIID,'Api_password'=>AVEnterp_PASS,'Member_request_txnid'=>$transId);
        $out = $this->General->RoboApi($url,$input_params);
        

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aventerp.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":aventerpTranStatus: " . json_encode($out));

        $out = json_decode($out['output'], TRUE);
        $status   = $out['STATUS'];
        $msg      = $out['MESSAGE'];
        $clientId = $out['ORDERID'];
        $opr_id =   $out['OPTRANSID'];
        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '3') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }

    function jasscommMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['jasscomm'];

        $id = JASHComm_APIID;
        $url = ROBO_RECURL;
        $pass = JASHComm_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/jasscomm.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function jasscommDthRecharge($transId, $params, $prodId = null, $res = null)
    {
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['jasscomm'];

        $id = JASHComm_APIID;
        $url = ROBO_RECURL;
        $pass = JASHComm_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/jasscomm.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }    
    
    function jasscommTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = ROBO_STATUSURL;
        $input_params = array('Apimember_id'=>JASHComm_APIID,'Api_password'=>JASHComm_PASS,'Member_request_txnid'=>$transId);
        $out = $this->General->RoboApi($url,$input_params);
        

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/jasscomm.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":jasscommTranStatus: " . json_encode($out));

        $out = json_decode($out['output'], TRUE);
        $status   = $out['STATUS'];
        $msg      = $out['MESSAGE'];
        $clientId = $out['ORDERID'];
        $opr_id =   $out['OPTRANSID'];
        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '3') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }
    
    function nkagencyMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['nkagency'];

        $id = NKAGENCIES_APIID;
        $url = ROBO_RECURL;
        $pass = NKAGENCIES_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/nkagency.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function nkagencyDthRecharge($transId, $params, $prodId = null, $res = null)
    {
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['nkagency'];

        $id = NKAGENCIES_APIID;
        $url = ROBO_RECURL;
        $pass = NKAGENCIES_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/nkagency.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }    
    
    function nkagencyTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = ROBO_STATUSURL;
        $input_params = array('Apimember_id'=>NKAGENCIES_APIID,'Api_password'=>NKAGENCIES_PASS,'Member_request_txnid'=>$transId);
        $out = $this->General->RoboApi($url,$input_params);
        

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/nkagency.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":nkagencyTranStatus: " . json_encode($out));

        $out = json_decode($out['output'], TRUE);
        $status   = $out['STATUS'];
        $msg      = $out['MESSAGE'];
        $clientId = $out['ORDERID'];
        $opr_id =   $out['OPTRANSID'];
        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '3') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }

    function anilkirMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['anilkir'];

        $id = ANILKirana_APIID;
        $url = ROBO_RECURL;
        $pass = ANILKirana_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/anilkir.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function anilkirDthRecharge($transId, $params, $prodId = null, $res = null)
    {
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['anilkir'];

        $id = ANILKirana_APIID;
        $url = ROBO_RECURL;
        $pass = ANILKirana_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/anilkir.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }    
    
    function anilkirTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = ROBO_STATUSURL;
        $input_params = array('Apimember_id'=>ANILKirana_APIID,'Api_password'=>ANILKirana_PASS,'Member_request_txnid'=>$transId);
        $out = $this->General->RoboApi($url,$input_params);
        

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/anilkir.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":jasscommTranStatus: " . json_encode($out));

        $out = json_decode($out['output'], TRUE);
        $status   = $out['STATUS'];
        $msg      = $out['MESSAGE'];
        $clientId = $out['ORDERID'];
        $opr_id =   $out['OPTRANSID'];
        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '3') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }

    function jeevnrkhMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['jeevnrkh'];

        $id = JEEVANRAKSHAE_APIID;
        $url = ROBO_RECURL;
        $pass = JEEVANRAKSHAE_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/jeevnrkh.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function jeevnrkhDthRecharge($transId, $params, $prodId = null, $res = null)
    {
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['jeevnrkh'];

        $id = JEEVANRAKSHAE_APIID;
        $url = ROBO_RECURL;
        $pass = JEEVANRAKSHAE_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/jeevnrkh.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));-

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }    
    
    function jeevnrkhTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = ROBO_STATUSURL;
        $input_params = array('Apimember_id'=>JEEVANRAKSHAE_APIID,'Api_password'=>JEEVANRAKSHAE_PASS,'Member_request_txnid'=>$transId);
        $out = $this->General->RoboApi($url,$input_params);
        

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/jeevnrkh.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":jeevnrkhTranStatus: " . json_encode($out));

        $out = json_decode($out['output'], TRUE);
        $status   = $out['STATUS'];
        $msg      = $out['MESSAGE'];
        $clientId = $out['ORDERID'];
        $opr_id =   $out['OPTRANSID'];
        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '3') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }    
    
    
    function payclickMobRecharge($transId, $params, $prodId){
        $mobileNo = $params['mobileNumber'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['payclick'];
        

        $url = PAYCLICK_REC_URL;

        $out = $this->General->payclickApi($url, array('username'=>PAYCLICK_USERID, 'password'=>PAYCLICK_PASS, 'number'=>$mobileNo, 'circlecode'=>'12', 'operatorcode'=>$provider, 'amount'=>$amount, 'clientid'=>$transId));

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];

        $res = explode(",", $out);
       
        $clientId = (isset($res[1]) &&  ! empty($res[1])) ? $res[1] : "";
        $oprId = (isset($res[2]) &&  ! empty($res[2])) ? $res[2] : "";
        $status = (isset($res[0]) &&  ! empty($res[0])) ? $res[0] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/payclick.txt", "*Mob Recharge*: Input=> $transId<br/>" . json_encode($res) . "<br/>params=>" . json_encode($params));
        
        if ($status == 'success') {
            return array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $oprId, 'internal_error_code' => '13', 'vendor_response' => json_encode($res));
        }
        else if($status == 'failure'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$res[5]);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$res[5]);
        }
    }

    function payclickDthRecharge($transId, $params, $prodId){
        $mobileNo = $params['subId'];

        $amount = intval($params['amount']);

        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['payclick'];
        

        $url = PAYCLICK_REC_URL;

        $out = $this->General->payclickApi($url, array('username'=>PAYCLICK_USERID, 'password'=>PAYCLICK_PASS, 'number'=>$mobileNo, 'circlecode'=>'12', 'operatorcode'=>$provider, 'amount'=>$amount, 'clientid'=>$transId));

        if( ! $out['success']){
            if($out['timeout']){
                    return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $out = $out['output'];

        $res = explode(",", $out);

        $clientId = (isset($res[1]) &&  ! empty($res[1])) ? $res[1] : "";
          $oprId = (isset($res[2]) &&  ! empty($res[2])) ? $res[2] : "";
        $status = (isset($res[0]) &&  ! empty($res[0])) ? $res[0] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/payclick.txt", "*dth Recharge*: Input=> $clientId<br/>" . json_encode($res) . "<br/>params=>" . json_encode($params));

        if ($status == 'success') {
            return array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'tranId' => $clientId, 'pinRefNo' => '', 'operator_id' => $oprId, 'internal_error_code' => '13', 'vendor_response' => $res[5]);
        } 
        else if($status == 'failure'){
            return array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$res[5]);
        }
        else{
            return array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'internal_error_code'=>'15', 'vendor_response'=>$res[5]);
        }
    }
    function payclickBalance(){        

        $url = PAYCLICK_BAL;

        $out = $this->General->payclickApi($url, array('username'=>PAYCLICK_USERID, 'password'=> PAYCLICK_PASS));

        if( ! $out['success']){
            return array('balance'=>'');
        }

        $bal = explode(',', $out['output']);

        $out = (isset($bal[1]) &&  ! empty($bal[1])) ? $bal[1] : "";

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/payclick.txt", date('Y-m-d H:i:s') . ":Balance Check: $out");

        $bal = trim($out);

        return array('balance'=>$bal);
    }

    function payclickTranStatus($transId, $date = null, $refId = null){
        $url = PAYCLICK_TRANS_URL;

        $out = $this->General->payclickApi($url, array('username'=>PAYCLICK_USERID, 'password'=>PAYCLICK_PASS, 'rcid'=>$transId));

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/payclick.txt", date('Y-m-d H:i:s') . "transId=>$transId" . ":payclickTranStatus: " . json_encode($out['output']));

        $response = explode(",", $out['output']);

        $clientId = $response[1];
        if($response[0] == 'success'){
            $operator_id = $response[2];
            $ret = array('status'=>'success', 'status-code'=>'success', 'description'=>$out['output'], 'vendor_id'=>$clientId,'operator_id'=>$operator_id , 'tranId'=>$transId);
        }
        else if($response[0] == 'failure'){
            $ret = array('status'=>'failure', 'status-code'=>'failure', 'description'=>$out['output'], 'vendor_id'=>$clientId,'operator_id'=>'' ,'tranId'=>$transId);
        }
        else{
            $ret = array('status'=>'pending', 'status-code'=>'pending', 'description'=>$out['output'],  'vendor_id'=>$clientId,'operator_id'=>'' ,'tranId'=>$transId);
        }

        return $ret;
    }

    function starcomMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['starcom'];

        $id = STARCOMM_APIID;
        $url = ROBO_RECURL;
        $pass = STARCOMM_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/starcom.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function starcomDthRecharge($transId, $params, $prodId = null, $res = null)
    {
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['starcom'];

        $id = STARCOMM_APIID;
        $url = ROBO_RECURL;
        $pass = STARCOMM_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/starcom.txt", "*DTH Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));-

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }    
    
    function starcomTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = ROBO_STATUSURL;
        $input_params = array('Apimember_id'=>STARCOMM_APIID,'Api_password'=>STARCOMM_PASS,'Member_request_txnid'=>$transId);
        $out = $this->General->RoboApi($url,$input_params);
        

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/starcom.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":starcomTranStatus: " . json_encode($out));

        $out = json_decode($out['output'], TRUE);
        $status   = $out['STATUS'];
        $msg      = $out['MESSAGE'];
        $clientId = $out['ORDERID'];
        $opr_id =   $out['OPTRANSID'];
        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '3') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }    

    function moderntradMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['moderntrad'];

        $id = MODERNTRADE_APIID;
        $url = ROBO_RECURL;
        $pass = MODERNTRADE_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/moderntrad.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function moderntradDthRecharge($transId, $params, $prodId = null, $res = null)
    {
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['moderntrad'];

        $id = MODERNTRADE_APIID;
        $url = ROBO_RECURL;
        $pass = MODERNTRADE_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/moderntrad.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));-

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }    
    
    function moderntradTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = ROBO_STATUSURL;
        $input_params = array('Apimember_id'=>MODERNTRADE_APIID,'Api_password'=>MODERNTRADE_PASS,'Member_request_txnid'=>$transId);
        $out = $this->General->RoboApi($url,$input_params);
        

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/moderntrad.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":moderntradTranStatus: " . json_encode($out));

        $out = json_decode($out['output'], TRUE);
        $status   = $out['STATUS'];
        $msg      = $out['MESSAGE'];
        $clientId = $out['ORDERID'];
        $opr_id =   $out['OPTRANSID'];
        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '3') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }    

    function vjyotraderMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['vjyotrader'];

        $id = VISHWAJYOTI_APIID;
        $url = ROBO_RECURL;
        $pass = VISHWAJYOTI_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/vjyotrader.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function vjyotraderDthRecharge($transId, $params, $prodId = null, $res = null)
    {
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['vjyotrader'];

        $id = VISHWAJYOTI_APIID;
        $url = ROBO_RECURL;
        $pass = VISHWAJYOTI_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/vjyotrader.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));-

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }    
    
    function vjyotraderTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = ROBO_STATUSURL;
        $input_params = array('Apimember_id'=>VISHWAJYOTI_APIID,'Api_password'=>VISHWAJYOTI_PASS,'Member_request_txnid'=>$transId);
        $out = $this->General->RoboApi($url,$input_params);
        

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/vjyotrader.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":vjyotraderTranStatus: " . json_encode($out));

        $out = json_decode($out['output'], TRUE);
        $status   = $out['STATUS'];
        $msg      = $out['MESSAGE'];
        $clientId = $out['ORDERID'];
        $opr_id =   $out['OPTRANSID'];
        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '3') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }    

    function aftraderMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['aftrader'];

        $id = AFTRADER_APIID;
        $url = ROBO_RECURL;
        $pass = AFTRADER_ROBO_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aftrader.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function aftraderDthRecharge($transId, $params, $prodId = null, $res = null)
    {
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['aftrader'];

        $id = AFTRADER_APIID;
        $url = ROBO_RECURL;
        $pass = AFTRADER_ROBO_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aftrader.txt", "*Dth Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));-

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }    
    
    function aftraderTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = ROBO_STATUSURL;
        $input_params = array('Apimember_id'=>AFTRADER_APIID,'Api_password'=>AFTRADER_ROBO_PASS,'Member_request_txnid'=>$transId);
        $out = $this->General->RoboApi($url,$input_params);
        

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/aftrader.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":aftraderTranStatus: " . json_encode($out));

        $out = json_decode($out['output'], TRUE);
        $status   = $out['STATUS'];
        $msg      = $out['MESSAGE'];
        $clientId = $out['ORDERID'];
        $opr_id =   $out['OPTRANSID'];
        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '3') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }    

    function starmbroboMobRecharge($transId, $params, $prodId = null, $res = null)
    {
        $prodId = empty($prodId) ? 0 : $prodId;

        if(in_array($prodId, array('27', '28', '29', '31', '34'))){
            $params['type'] = 'voucher';
        }

        $provider = $this->mapping['mobRecharge'][$params['operator']][$params['type']]['starmbrobo'];

        $id = STARMBROBO_APIID;
        $url = ROBO_RECURL;
        $pass = STARMBROBO_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/starmbrobo.txt", "*Mob Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }

    function starmbroboDthRecharge($transId, $params, $prodId = null, $res = null)
    {
        $provider = $this->mapping['dthRecharge'][$params['operator']][$params['type']]['starmbrobo'];

        $id = STARMBROBO_APIID;
        $url = ROBO_RECURL;
        $pass = STARMBROBO_PASS;
        $input_params = array('Apimember_id'=>$id,'Api_password'=>$pass,'Mobile_no'=>$params['mobileNumber'],'Operator_code'=>$provider,'Amount'=>$params['amount'], 'Member_request_txnid'=>$transId,'Circle'=>12);

        $out = $this->General->RoboApi($url,$input_params);

        if( ! $out['success']){
            if($out['timeout']){
                return array('status'=>'failure', 'code'=>'14', 'description'=>$this->Shop->errors(14), 'tranId'=>'', 'pinRefNo'=>'', 'operator_id'=>'', 'internal_error_code'=>'14', 'vendor_response'=>'Not able to connect to server');
            }
        }

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/starmbrobo.txt", "*DTh Recharge*: Input=> $transId<br/>params=>" . json_encode($input_params). "<br/>Output=>" . json_encode($out['output']));-

            $out = json_decode($out['output'],TRUE);

        $status = $out['STATUS'];
        $clientId = $out['ORDERID'];
        $opr_id = $out['OPTRANSID'];

        if(strtolower($status) == 3){
            $ret = array('status'=>'failure', 'code'=>'30', 'description'=>$this->Shop->errors(30), 'tranId'=>$clientId, 'internal_error_code'=>'30', 'vendor_response'=>$out);
        }
        elseif(strtolower($status) == 1){
            $ret = array('status'=>'success', 'code'=>'13', 'description'=>$this->Shop->errors(13), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'13', 'vendor_response'=>$out);
        }
        else{
            $ret = array('status'=>'pending', 'code'=>'15', 'description'=>$this->Shop->errors(15), 'tranId'=>$clientId, 'pinRefNo'=>'', 'operator_id'=>$opr_id, 'internal_error_code'=>'15', 'vendor_response'=>$out);
        }

        return $ret;
    }    
    
    function starmbroboTranStatus($transId, $date = null, $refId = null){
        $transId = empty($transId) ? 0 : $transId;        
        $url = ROBO_STATUSURL;
        $input_params = array('Apimember_id'=>STARMBROBO_APIID,'Api_password'=>STARMBROBO_PASS,'Member_request_txnid'=>$transId);
        $out = $this->General->RoboApi($url,$input_params);
        

        $this->General->logData($_SERVER['DOCUMENT_ROOT'] . "/logs/starmbrobo.txt", date('Y-m-d H:i:s') . "transId=>$transId <br/>params=>" . json_encode($input_params) . ":starmbroboTranStatus: " . json_encode($out));

        $out = json_decode($out['output'], TRUE);
        $status   = $out['STATUS'];
        $msg      = $out['MESSAGE'];
        $clientId = $out['ORDERID'];
        $opr_id =   $out['OPTRANSID'];
        if ($status == '1') {
            $ret = array('status' => 'success', 'code' => '13', 'description' => $this->Shop->errors(13), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '13', 'vendor_response' => json_encode($msg));
        } elseif ($status == '3') {
            $ret = array('status' => 'failure', 'code' => '30', 'description' => $this->Shop->errors(30), 'vendor_id' => $clientId, 'internal_error_code' => '30', 'vendor_response' => json_encode($msg));
        } else {
            $ret = array('status' => 'pending', 'code' => '15', 'description' => $this->Shop->errors(15), 'vendor_id' => $clientId, 'pinRefNo' => '', 'operator_id' => $opr_id, 'internal_error_code' => '15', 'vendor_response' => json_encode($msg));
        }

        return $ret;
    }    
  

}
?>
