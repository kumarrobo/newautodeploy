<?php
// //RohitP(rohit3nov@gmail.com)
class ActiveretailersController extends AppController {
    var $name = 'Activeretailers';
    var $helpers = array('Html','Ajax','Javascript','Minify','Paginator','GChart','Csv');
    var $uses = array('User','Slaves');
    var $components = array('RequestHandler','Servicemanagement','Serviceintegration');

    function beforeFilter()
    {
        parent::beforeFilter();
    }
    function index(){
        $service_temp = $this->Serviceintegration->getServiceDetails();
        $services_temp = json_decode($service_temp,true);

        $services = array();
        foreach ($services_temp as $service_id => $service) {
            $services[$service_id] = $service['name'];
        }
        $selected_service = null;
        $active_retailers = array();
        $activated_from = $this->params['url']['activationfrom'];
        $activated_to = $this->params['url']['activationto'];
        $filter_distributors = $this->params['url']['dist_name'];
        $status_type = $this->params['url']['status_type'];
        $active_export = $this->params['url']['activeret_download'];



        if (array_key_exists('service', $this->params['url'])) {
            if (array_key_exists('service', $this->params['url']) && in_array(trim(strtolower($this->params['url']['service'])), array_map('strtolower', $services))) {
                $selected_service = array_search(trim(strtolower($this->params['url']['service'])), array_map('strtolower', $services));
            }
            if ($selected_service) {
                $distributor_list = $this->Servicemanagement->getDistributorData($selected_service);
                $active_retailers = $this->Servicemanagement->getActiveRetailersByService($selected_service, $activated_from, $activated_to,$filter_distributors,$status_type);
            } else {
                $this->set('validation_error','Please select proper service !!');
            }
        } else {
            $this->set('validation_error','Please select service !!');
        }

        if($active_export){
              $this->downloadServicemanagentDetails($active_retailers,$selected_service,$activated_from,$activated_to);
        }
        $this->set('page',$active_export);
        $this->set('selected_service', $selected_service);
        $this->set('activated_from', $activated_from);
        $this->set('activated_to', $activated_to);
        $this->set('status_type',$status_type);
        $this->set('filter_distributors',$filter_distributors);
        $this->set('services', $services);
        // $this->set('plans', Configure::read('plans'));
        $service_plans = $this->Serviceintegration->getServicePlans();
        $service_plans = json_decode($service_plans,true);
        $plan_names = array();
        foreach($service_plans as $service_id => $plans_temp){
            foreach($plans_temp as $plan_key => $plan){
                $plan_names[$service_id][$plan_key] = $plan['plan_name'];
            }
        }

        $this->set('plans', $plan_names);
        $this->set('active_retailers', $active_retailers);
        $this->set('distributor_list',$distributor_list);
        $this->layout = 'plain';
        $this->render('/servicemanagement/retailerlist');
    }
    function downloadServicemanagentDetails($active_retailers,$selected_service,$activated_from,$activated_to){
        $services = $this->Serviceintegration->getServiceDetails();
        $services = json_decode($services,true);

        App::import('Helper','csv');
        $this->layout = null;
        $this->autoLayout = false;
        $csv = new CsvHelper();
        if($selected_service == 12){
        $line=array('User Id','Mobile','Ret ID','Retailer Name','Shop Name','Dist Id','Distributor Name','Margin','Fields','Status','Created at');
        }else {
            $line=array('User Id','Mobile','Ret ID','Retailer Name','Shop Name','Dist Id','Distributor Name','Fields','Status','Created at');
        }
        $csv->addRow($line);
        $i=1;
        foreach ($active_retailers as $actret):
            $status = (($actret['service_flag'] == 1) && ($actret['kit_flag'] == 1))?'Active':'Deactive';
            $margin = (($actret['param1']) == '')?0.4:$actret['param1'];
        if($selected_service == 12){
            $line=array($actret['retailer_user_id'],$actret['retailer_mobile'],$actret['retailer_id'],$actret['retailer_name'],$actret['retailer_shopname'],$actret['distributor_id'],$actret['distributor_name'],$margin,$actret['excelparams'],$status,$actret['service_created_on']);
        }
        else {
            $line=array($actret['retailer_user_id'],$actret['retailer_mobile'],$actret['retailer_id'],$actret['retailer_name'],$actret['retailer_shopname'],$actret['distributor_id'],$actret['distributor_name'],$actret['excelparams'],$status,$actret['service_created_on']);
        }
            $csv->addRow($line);
            $i++;
        endforeach;
        ob_clean();
        echo $csv->render("Servicemanagement"."_".$services[$selected_service]."_".$activated_from."_".$activated_to);
    }
}