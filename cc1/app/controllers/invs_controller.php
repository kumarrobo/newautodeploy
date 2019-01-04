<?php

class InvsController extends AppController
{
        var $components = array('RequestHandler', 'Shop', 'Busvendors', 'General');
        var $uses = array('User','InvSupplier');
        var $name = 'Invs';
        
        
        function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('*');
        }
        
        function test(){
        	echo "1";exit;
        }
        
        
        public function syncSuppliers($modem_id=null)
        {
             $this->autoRender=false;
             
             $modemList=  $this->getModemList($modem_id);
             
             $failed=0;
             
             $tempSupplier=array();
             
 /*            echo "<pre>";
             print_r($modemList);
             echo "</pre>";
*/
             foreach($modemList as $modem):
                 
                   $query="query=getDistinctVendorTag";
                 
                  $result=  $this->Shop->modemRequest($query,$modem['vendors']['id']);
                  
                   if($result['status']=="success"):
                       
                        $data=  json_decode($result['data']);
                   
                        if(!empty($data->suppliers)):
                            
                             $suppliers=array_map('trim',$data->suppliers);
                        
//                             echo "<pre>";
//                             print_r($suppliers);
//                             echo "</pre>";


                             foreach($suppliers as $supplier):
                                 
                                                    if(!empty($supplier)):

                                                                       // Insert Supplier 
                                                                         $date=date("Y-m-d H:i:s");

                                                                        $temp=array('name'=>ucwords($supplier),'created_at'=>$date);

                                                                        $this->InvSupplier->create();

                                                                        $r1=$this->InvSupplier->save($temp);

                                                                         $supplier_id=0;

                                                                        if($r1):

                                                                                //$tempSupplier[$this->InvSupplier->id][]=$modem['vendors']['id'] ;
                                                                                $tmpSupplier[ucwords($supplier)]=$this->InvSupplier->id ;

                                                                      /*  else:

                                                                                 $supplier_id=$this->getSupplierIdByName($supplier);

                                                                                 if($supplier_id>0):

                                                                                 //$tempSupplier[$modem['vendors']['id']][]=$supplier_id;    
                                                                                 $tempSupplier[$supplier_id][]=$modem['vendors']['id'];

                                                                                 endif;*/

                                                                        endif;
                                                                        $k = $tmpSupplier[ucwords($supplier)];
                                                                        $sql="Insert into inv_supplier_vendor_mapping(supplier_id,vendor_id) values('{$k}','{$modem['vendors']['id'] }')";
                                                                        $result2=$this->User->query($sql);
                                                                        
                                                                        echo "<pre>";
                                                                        print_r($tempSupplier);
                                                                        echo "</pre>";

                                                                    endif;
                                            
                                            endforeach;
                               
                             else:
                            
                              echo json_encode(array('msg'=>'No Supplier fetched','modem'=>$modem['vendors']['company'],'link'=>"http://{$modem['vendors']['ip']}:{$modem['vendors']['port']}/start.php?query=getDistinctVendorTag"));

                              echo "<br/>";
                                               
                                               
                        endif;
                   
                   else:
                       
                                    $failed++;
                                    echo "No Success Status received for modem : ".$modem['vendors']['company'];
                                    echo "<br/>";
                       
                   endif;
                 
             endforeach;
             
             
             // Insert Supplier vendor Mapping //
            /* echo  "---TEMP ---";
             echo "<pre>";
             print_r($tempSupplier);
             echo "</pre>";
             echo "-------------";
           //  die;

             $mappingarray=array();

             if(!empty($tempSupplier)):
                    
                    foreach($tempSupplier as $key=>$tempS):

                    $mappingarray[$key]=  array_unique($tempS);

                    endforeach;
                    
                    echo "-------<pre>";
                    print_r($mappingarray);
                    echo "</pre>";
                  //  die;

                    foreach($mappingarray as $k =>$v):
                    
                        foreach($v as $mid):
                    
                             echo  $sql="Insert into inv_supplier_vendor_mapping(supplier_id,vendor_id) values('{$k}','{$mid}')";
                            echo "<br/>";
                              $result2=$this->User->query($sql);
                             
                              $sql="";
                             
                              if(!$result2):
                                  echo "Error";
                                  echo "<br/>";
                              endif;
                              
                        endforeach;
             
                    endforeach;
                    
                endif;
                
                */
                   echo json_encode(array('status'=>'success','message'=>'Vendor Synchronization Complete','totalVendors'=>count($modemList),'failure'=>$failed));     
                                 
        }
        
        
        
        
//          public function syncSuppliers($modem_id=null)
//          {
//             
//              $this->autoRender=false;
//              
//              $modemList=  $this->getModemList($modem_id);
//          
//              $failed=0;
//              
//               if(!empty($modemList)):
//                   
//                   foreach($modemList as $value):
//                   
//                       $query="query=getDistinctVendorTag";
// 
//                            $result=  $this->Shop->modemRequest($query,$value['vendors']['id']);
//                            
//                            if($result['status']=="success"):
//                                
//                                $data=  json_decode($result['data']);
//                            
//                                            if(!empty($data->suppliers)):
//
//                                                   $suppliers=array_map('trim',$data->suppliers);
//
//                                                   $this->SyncVendorsFromShopsToInventory($suppliers,$value['vendors']['id']);
//
//                                           else:
//
//                                               echo json_encode(array('msg'=>'No Supplier fetched','modem'=>$value['vendors']['company'],'link'=>"http://{$value['vendors']['ip']}:{$value['vendors']['port']}/start.php?query=getDistinctVendorTag"));
//
//                                               echo "<br/>";
//
//                                           endif;
//                                
//                            else:
//                                
//                                    $failed++;
//                                    echo "No Success Status received for modem : ".$value['vendors']['company'];
//                                    echo "<br/>";
//                            
//                            
//                            endif;
//
//
//
//                        endforeach;
//                        
//                           echo json_encode(array('status'=>'success','message'=>'Vendor Synchronization Complete','totalVendors'=>count($modemList),'failure'=>$failed));   
//                   
//               else:
//                   
//                     echo json_encode(array('status'=>'true','message'=>'No Modems Found'));
//               
//               endif;
//         }
          
          public function getModemList($modem_id=null)
          {
               $sql="SELECT id,company,shortForm,ip,bridge_ip,port "
                          . " FROM vendors "
                          . " WHERE update_flag='1'  "; 
              
              if(!is_null($modem_id)):
                  
                  $sql.=" AND id='{$modem_id}' ";
               endif;
             
              $result=  $this->User->query($sql);
              
              return $result;
          }
          
          public function SyncVendorsFromShopsToInventory($vendors,$modem_id)
          {
            foreach ($vendors as $value):
                
                            if(!$this->checkIfSupplierAlreadyExists($value)):
                                
                                         $date=date("Y-m-d H:i:s");
                               
                                        $value=  ucwords(trim($value));
                                        
                                        if($value!=""):
                                        
                                                $temp=array('name'=>$value,'created_at'=>$date);

                                                $this->InvSupplier->create();

                                                $result=$this->InvSupplier->save($temp);

                                                if($result):

                                                    $insertmapping="Insert into inv_supplier_vendor_mapping(supplier_id,vendor_id) values('{$this->InvSupplier->id}','{$modem_id}')";

                                                     $this->User->query($insertmapping);

                                                endif;
                                        
                                        endif;
                                            
                            
                            else:
                                
                                   if($value!=""):
                                       
//                                                if(!$this->checkifSupplierVendorMappingAlreadyExisits(array('supplier_name'=>$value,'vendor_id'=>$modem_id))):
//
//                                                    $insertmapping="Insert into inv_supplier_vendor_mapping(supplier_id,vendor_id) values('{$this->getSupplierIdByName($value)}','{$modem_id}')";
//
//                                                    $this->User->query($insertmapping);
//
//                                                endif;        
                                       
                                                    $supplier_id=$this->getSupplierIdByName($value);
                                   
                                                            if($supplier_id):

                                                                $check="Select id from inv_supplier_vendor_mapping where supplier_id='{$supplier_id}' AND vendor_id='{$modem_id}' ";

                                                                $result2=  $this->User->query($check);

                                                                    if(empty($result2)):

                                                                        $insertmapping="Insert into inv_supplier_vendor_mapping(supplier_id,vendor_id) values('{$supplier_id}','{$modem_id}')";

                                                                        $this->User->query($insertmapping);

                                                                    endif;

                                                            endif;
                                                
                                      endif;
                                
                            endif;
                            
            
            endforeach;
            
            
            
            return true;
           
          }
          
          public function checkIfSupplierAlreadyExists($name)
          {
              $name=strtolower($name);
              
              $sql="Select id from inv_suppliers where LOWER(name)='{$name}' ";
              
              $result=$this->User->query($sql);
              
              if(!empty($result)):
                  
                   return true;
              
              endif;
              
              return false;
              
              $this->autoRender=false;
            
           }
           
           public function getSupplierIdByName($name)
           {
               $name=  strtolower($name);
               
               $result=  $this->User->query("Select id from inv_suppliers where LOWER(name)='{$name}' limit 1");
               
               if(!empty($result)):
                   
                   return $result[0]['inv_suppliers']['id'];
               
               endif;
               
               return false;
           }
           
           public function checkifSupplierVendorMappingAlreadyExisits($data)
           {
              
               if($supplier_id=$this->getSupplierIdByName($data['supplier_name'])):
                   
                   $check="Select id from inv_supplier_vendor_mapping where supplier_id='{$supplier_id}' AND vendor_id='{$data['vendor_id']}' ";
                   
                   $result2=  $this->User->query($check);
                   
                   if(!empty($result2)):
                       
                       return true;
                   
                   else:
                       
                       return false;
                   
                   endif;
                   
               endif;
               
               return true;
           }
           
       
          public function UpdateSupplierId($modem_id=null)
          {
                $this->autoRender=false;
                
                $modemList=  $this->getModemList($modem_id);
                
                if(!empty($modemList)):
                    
                            foreach($modemList as $modem):
                    
                              $query="query=getAllVendorTags";
                
                                $result=  $this->Shop->modemRequest($query,$modem['vendors']['id']);
                            
                                if($result['status']=="success"):
                                    
                                             $data=  json_decode($result['data']);
                                    
                                              $updatelist=array();
                                              
                                            if(!empty($data->suppliers)):

                                                 foreach($data->suppliers as $value):

                                                               $updatelist[trim($value->vendor_tag)]['id'][]=$value->id;
                                        
                                                  endforeach;
                                                  
                                                    if(!empty($updatelist)):
                                                        
                                                         foreach($updatelist as $key=>$supp):
                                                        
                                                            $Inv_supplier_id=  $this->getSupplierIdByName($key);
                                                    
                                                             $updatelist[$key]['inv_supplier_id']=$Inv_supplier_id>0?$Inv_supplier_id:0;
                                                             
                                                                if(count($supp['id'])>1):
                                                                $InStatement=  implode(',', $supp['id']);
                                                                else:
                                                                $InStatement= $supp['id'][0];
                                                                endif;
                                                                
                                                                echo $query2="query=UpdateInvSupplierId&vendor_tag=$key&ids=$InStatement&inv_supplier_id=$Inv_supplier_id";
                                                                
                                                             
                                                                $UpdateSupplierIdCurl=$this->Shop->modemRequest($query2,$modem['vendors']['id']);
                                                                
                                                                echo "<pre>";
                                                                print_r($UpdateSupplierIdCurl);
                                                                echo "</pre>";   
                                                                echo "<br/>";
                                                            
                                                         endforeach;
                                                        
                                                    endif;

                                              else:

                                                echo "No Supplier fetched";
                                                 echo "<br/>";
                                            endif;

                                else:
                                    
                                    echo "No Success status received";
                                    echo "<br/>";
                                    
                                endif;

                        
                            endforeach;
                    
                else:
                    
                    echo "No Modem Fetched";
                     echo "<br/>";
                     
                endif;
          }
          
          
          public function syncSimdata($modem_id=null)
          {
            $failed=0;
            
                  $this->autoRender=false;
                
                $modemList=  $this->getModemList($modem_id);
                
             
                if(!empty($modemList)):
                    
                            foreach($modemList as $modem):
                            
                            $date=date('Y-m-d',strtotime('-1 days'));
                
                            $query="query=balance&date=$date";
                            
                            $result= $this->Shop->modemRequest($query,$modem['vendors']['id']);
                            
                            if($result['status']=="success"):
                                
                                  $data= json_decode($result['data']);
                            
                                  $this->saveSimdata($data,$modem['vendors']['id']);

                                  $this->setSyncStatus($modem['vendors']['id'],'1');

                              else:
                                
                                   $this->setSyncStatus($modem['vendors']['id'],'0');
                              
                                       $failed++;
                                    echo "No Success Status received";
                                     echo "<br/>";
                                     
                            endif;
                    
                            endforeach;
                            
                               echo json_encode(array('status'=>true,'msg'=>'Sync Complete','Total modems'=>count($modemList),'failure'=>$failed));
                
                 else:
            
                          echo json_encode(array('status'=>true,'msg'=>'No Modems To sync'));
        
                 endif;
          }
          
          
          public function saveSimdata($data,$modem_id)
          {
               foreach($data as $sim):

                     $inv_supplier_id=($sim->inv_supplier_id>0)?$sim->inv_supplier_id:0;
                     $supplier_operator_id= $this->getsupplier_operator_id($sim->inv_supplier_id,$sim->opr_id);
                     $tfr=!empty($sim->tfr)?$sim->tfr:0.00;
                     $opening=!empty($sim->opening)?$sim->opening:0.00;
                     $closing=isset($sim->closing)?(!empty($sim->closing)?$sim->closing:0.00):0.00;
                     $sale=!empty($sim->sale)?$sim->sale:0.00;
                     $inc=!empty($sim->inc)?$sim->inc:0.00;
                     $balance=!empty($sim->balance)?$sim->balance:0.00;
                     $sync_timestamp=date("Y-m-d",strtotime("-1 day"));
                             
                     
                     if( ($tfr > 0 || $balance>0 || $opening>0 || $closing>0)  && ($supplier_operator_id>0) )
                     {
                     $query="Insert into inv_simdata(sim_no,sc_id,supplier_id,operator_id,supplier_operator_id,vendor_id,incoming,opening,closing,sale,blocked,active,incentive,balance,sync_timestamp)  "
                                    . " values('{$sim->mobile}','{$sim->scid}','{$inv_supplier_id}','{$sim->opr_id}','{$supplier_operator_id}','{$modem_id}','{$tfr}','{$opening}','{$closing}','{$sale}','{$sim->block}','{$sim->active_flag}','{$inc}','{$balance}','{$sync_timestamp}')"; 
                      
                     }
                    $this->User->query($query);
					echo $query . "<br/>";
            endforeach;
        
        return true;
          }
          
          
          public function getsupplier_operator_id($supplier_id,$operator_id)
          {
            
               $result=  $this->User->query("Select id from inv_supplier_operator where supplier_id='{$supplier_id}' And operator_id='{$operator_id}' ");
               
               if(!empty($result)):
                   
                   return $result[0]['inv_supplier_operator']['id'];
               
               endif;
               
               return '0';
          }
          
          public function setSyncStatus($modem_id,$is_updated)
          {
              
               // Delete Previous Entries of  modems  who were not updated to  avoid repetation when updated
                //Start
                $this->User->query("Delete from inv_sync_status Where modem_id='{$modem_id}' AND  DATE(last_sync_timestamp)=CURDATE() ");
                //End

                $date=date("Y-m-d H:i:s");
          
                $sql="Insert into inv_sync_status(modem_id,is_updated,last_sync_timestamp)  values('{$modem_id}','{$is_updated}','{$date}')";
                
                $this->User->query($sql);
          }
          
          public function syncRemainingModems()
        {
            if($modems=$this->getNonUpdatedModems()):

                    $this->syncSimdata($modems);

            endif;
        }
        
        public function getNonUpdatedModems()
        {
            $query="Select v.id,company,shortForm,ip,bridge_ip,port "
                            . " from  inv_sync_status i "
                            . " JOIN vendors v "
                            . " ON i.modem_id=v.id  "
                            . " where is_updated'=>'0' "
                            . " AND  DATE(last_sync_timestamp)=CURDATE() "; 
            
            $result=$this->User->query($query);
            
            if(!empty($result)):
            
                      return $result;
            
            else:
                
                    return ;
            
            endif;
      
            
            
        }
        
        public function syncSOID($modem_id=null)
        {
        
             $this->autoRender=false;
             
             $modemList=  $this->getModemList($modem_id);
             

             if(!empty($modemList)):
                   
                   foreach($modemList as $modem):
                   

                       $query="query=balance";
           
                        $date=date('Y-m-d');
                
                        $query="query=balance&date=$date";

                        $result= $this->Shop->modemRequest($query,$modem['vendors']['id']);
                        
                   
                        if($result['status']=="success"):
                            
                               $data= json_decode($result['data']);
                
                             
                               foreach($data as $sim):

                                     
                                            $supplier_id=trim($sim->inv_supplier_id);
                                            $operator_id=trim($sim->opr_id);
                                            
                                            if($supplier_id >0 && $operator_id > 0):
                                                
                                                   $this->User->query("Insert into inv_supplier_operator(id,supplier_id,operator_id)  values(NULL,'$supplier_id','$operator_id') ");
                                            
                                            endif;   
                                          
                                endforeach;

                           else:
                               
                                    echo "No Success Status received for modem : ".$modem['vendors']['company'];
                                    echo "<br/>";
                               
                           endif;
                    
                    endforeach;    
                    
                    
                    echo "Process Completed";
                     echo "<br/>";
                    
                    
           endif;         
                          
        }
        
        
        public function ifsoidexists($supplier_id,$operator_id)
        {
             $supplier_id=trim($supplier_id);
             $operator_id=trim($operator_id);
             
             
          $result=  $this->User->query("Select id from inv_supplier_operator where supplier_id=$supplier_id  AND operator_id=$operator_id ");
            
            if(!empty($result)){
                
              return true;
            
            }
            
           return false;
           
        }
}