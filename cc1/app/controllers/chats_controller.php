<?php 

class ChatsController extends AppController {

	var $helpers = array('Ajax','Javascript','Paginator');
	var $components = array();
	var $uses = array('Chat','Slaves');
	var $ignoreJID = "adminsupport@dev.pay1.in";
	var $REPORT_INIT_DATE = "10-07-2015";
	
	
	function beforeFilter(){
		$this->layout = 'sims';
		$this->Auth->allow('*');
        parent::beforeFilter();
        $this->Auth->allow('generateReport');
	}
	
	function generateReport(){
		$this->autoRender = false;
		
		$report = $this->Chat->query("select end_time from chat_report where end_time > '".(strtotime($this->REPORT_INIT_DATE) * 1000)."' order by end_time desc limit 1");
		$last_updated_time = $report[0]['chat_report']['end_time'];
		
		if(!$last_updated_time){
			$last_updated_time = strtotime($this->REPORT_INIT_DATE) * 1000;
		}
		$chats = $this->Chat->query("select m.conversationID as cid, from_unixtime(min(m.sentDate)/1000, '%Y-%m-%d') as date, 
				min(m.sentDate) as start_time, max(m.sentDate) as end_time, m.fromJID, m.toJID 
				from ofMessageArchive m
				where m.sentDate > '".$last_updated_time."' group by m.conversationID");
		foreach($chats as $chat){
			if(!in_array($this->ignoreJID, array($chat['m']['fromJID'], $chat['m']['toJID']))){
				$time_array[$chat['m']['cid']] = array();
				$splitFromJID = explode("@", $chat['m']['fromJID']);
				$splitToJID = explode("@", $chat['m']['toJID']);

				$from[$chat['m']['cid']] = $splitFromJID[0];
				$to[$chat['m']['cid']] = $splitToJID[0];
				
				$start_time[$chat['m']['cid']] = $chat['0']['start_time'];
				$end_time[$chat['m']['cid']] = $chat['0']['end_time'];
				$date[$chat['m']['cid']] = $chat['0']['date'];
				
				$report[$chat['m']['cid']] = array(
					'from' 		=> 		$from[$chat['m']['cid']],
					'to'		=>		$to[$chat['m']['cid']],
					'date'		=>		$date[$chat['m']['cid']],
					'start_time'=>		$start_time[$chat['m']['cid']],
					'end_time'	=>		$end_time[$chat['m']['cid']]			
				);
			}	
		}
		echo "<table><tr><th>CID</th><th>from</th><th>to</th><th>date</th><th>start_time</th><th>end_time</th></tr>";
		foreach($report as $kr => $r){
			$cid_exists = $this->Chat->query("select * from chat_report 
					where conversation_id = '".$kr."'");
			echo "<tr>";
			echo "<td>".$kr."</td>";
			echo "<td>".$r['from']."</td>";
			echo "<td>".$r['to']."</td>";
			echo "<td>".$r['date']."</td>";
			echo "<td>".$r['start_time']."</td>";
			echo "<td>".$r['end_time']."</td>";
			echo "</tr>";
			if($cid_exists){
				$this->Chat->query("update chat_report
						set end_time = '".$r['end_time']."'
						where conversation_id = '$kr' and end_time != '".$r['end_time']."'");
			}
			else {
				$this->Chat->query("insert into chat_report 
						(conversation_id, from_jid, to_jid, date, start_time, end_time) 
						values ('$kr', '".$r['from']."', '".$r['to']."', '".$r['date']."', '".$r['start_time']."', '".$r['end_time']."')");
			}
		}
		echo "</table>";
	}
	
	function report($fromDate, $toDate, $toJID){
		if(empty($fromDate) || empty($toDate)){
			$fromDate = $toDate = date("d-m-Y", time());
		}
		$subject = " ( ".$fromDate." - ".$toDate." )";

		$addSupportUser = '';
		if($toJID){
			if(is_numeric($toJID)){
				$addSupportUser = " and c.from_jid = '$toJID' ";
				$fromDate = $this->REPORT_INIT_DATE;
			}	
			else 
				$addSupportUser = " and c.to_jid = '$toJID' ";
			$subject = " - ".$toJID." ".$subject;
		}
		
		$from_date = date("Y-m-d", strtotime($fromDate));
		$to_date = date("Y-m-d", strtotime($toDate));
		
		
		
		$chats = $this->Chat->query("select c.* from chat_report c 
				where c.date between '".$from_date."' and '".$to_date."' ".$addSupportUser."
				order by c.start_time desc");
		$last1Month = date('Y-m-d', time() - 30 * 86400);
		$supportUsers = $this->Chat->query("select distinct c.to_jid from chat_report c
				where c.date > '".$last1Month."'");
		
		$this->set('chats', $chats);
		$this->set('supportJIDs', $supportUsers);
		$this->set('fromDate', $fromDate);
		$this->set('toDate', $toDate);
		$this->set('subject', $subject);
	}
	
	function conversation($c_id){
		$conversation = $this->Chat->query("select * from ofMessageArchive m 
				where m.conversationID = '".$c_id."'
				order by m.sentDate asc");
		$this->set('chats', $conversation);
	}
}	
