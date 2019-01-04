<?php

class C2dController extends AppController {

        var $name = 'C2d';
	var $helpers = array('Html','Ajax','Javascript','Minify','Paginator','GChart');
	var $components = array('RequestHandler','Shop');
	var $uses = array('User','C2d','Slaves');

	function beforeFilter(){
	    parent::beforeFilter();
	    $this->Auth->allow('*');
	}

        public function clickToCallListing($page = 1, $recs = 100) {

                $this->layout = "plain";

                $limit          = ($page - 1) * $recs . ',' . $recs;

                $count_records  = $this->C2d->query("SELECT count(1) count FROM (SELECT * FROM (SELECT c2d_click_to_call.*, cash_payment_client.company_name, "
                        . "c2d_posts.title, c2d_posts.description, c2d_post_order_tag.tag_id FROM c2d_click_to_call "
                        . "LEFT JOIN cash_payment_client ON (c2d_click_to_call.wholesaler_id = cash_payment_client.id) "
                        . "LEFT JOIN c2d_post_order_tag ON (c2d_click_to_call.post_id = c2d_post_order_tag.post_id) "
                        . "LEFT JOIN c2d_posts ON (c2d_posts.id = c2d_click_to_call.post_id) ORDER BY c2d_post_order_tag.created_at DESC) as c2d "
                        . "GROUP BY post_id, retailer_id, call_date ORDER BY 1 DESC) a");

                $listing        = $this->C2d->query("SELECT * FROM (SELECT c2d_click_to_call.*, cash_payment_client.company_name, "
                        . "c2d_posts.title, c2d_posts.description, c2d_post_order_tag.tag_id FROM c2d_click_to_call "
                        . "LEFT JOIN cash_payment_client ON (c2d_click_to_call.wholesaler_id = cash_payment_client.id) "
                        . "LEFT JOIN c2d_post_order_tag ON (c2d_click_to_call.post_id = c2d_post_order_tag.post_id) "
                        . "LEFT JOIN c2d_posts ON (c2d_posts.id = c2d_click_to_call.post_id) ORDER BY c2d_post_order_tag.created_at DESC) as c2d "
                        . "GROUP BY post_id, retailer_id, call_date ORDER BY 1 DESC LIMIT $limit");

                $i = 0;
                foreach($listing as $list) {
                    $listing_data[$i]['c2d_click_to_call']      = array('id'=>$list['c2d']['id'],'post_id'=>$list['c2d']['post_id'],'wholesaler_id'=>$list['c2d']['wholesaler_id'],'retailer_id'=>$list['c2d']['retailer_id'],'retailermobile'=>$list['c2d']['retailermobile'],'wsmobile'=>$list['c2d']['wsmobile'],'call_timestamp'=>$list['c2d']['call_timestamp'],'call_date'=>$list['c2d']['call_date']);
                    $listing_data[$i]['cash_payment_client']    = array('company_name'=>$list['c2d']['company_name']);
                    $listing_data[$i]['c2d_posts']              = array('title'=>$list['c2d']['title'],'description'=>$list['c2d']['description']);
                    $listing_data[$i]['c2d_post_order_tag']     = array('tag_id'=>$list['c2d']['tag_id']);
                    $i++;
                }

                $retailers = array(0);
                foreach($listing_data as $ld) {
                    if(!in_array($ld['c2d_click_to_call']['retailer_id'], $retailers)) {
                        $retailers[] = $ld['c2d_click_to_call']['retailer_id'];
                    }
                }

                // $retailers = $this->Slaves->query("SELECT id, name, shopname FROM retailers WHERE id IN (" . implode(',', $retailers) . ")");
                $imp_data = $this->Shop->getUserLabelData(implode(',', $retailers),2,2);
                $retailer_temp = array();
                // foreach($retailers as $retailer) {
                //     $retailer_temp[$retailer['retailers']['id']]['name']        = $retailer['retailers']['name'];
                //     $retailer_temp[$retailer['retailers']['id']]['shopname']    = $retailer['retailers']['shopname'];
                // }
                foreach($imp_data as $retailer) {
                    $retailer_temp[$retailer['ret']['id']]['name']        = $retailer['imp']['name'];
                    $retailer_temp[$retailer['ret']['id']]['shopname']    = $retailer['imp']['shop_est_name'];
                }

                $order_tags = $this->C2d->query("SELECT id, tags FROM c2d_order_tags");

                $this->set('listing_data', $listing_data);
                $this->set('retailers', $retailer_temp);
                $this->set('order_tags', $order_tags);
                $this->set('totalrecords', $count_records[0][0]['count']);
                $this->set('page', $page);
                $this->set('recs', $recs);
        }

        public function addOrderTag() {

                $this->autoRender = FALSE;

                $post_id    = $this->params['form']['post_id'];
                $tag_id     = $this->params['form']['tag_id'];

                $res        = $this->C2d->query("INSERT INTO c2d_post_order_tag (post_id, tag_id, created_at, created_date) VALUES ($post_id, $tag_id, '". date('Y-m-d H:i:s') ."', '". date('Y-m-d') ."')");
        }

        public function addComment() {

                $this->autoRender = FALSE;

                $post_id    = $this->params['form']['post_id'];
                $comment    = $this->params['form']['comment'];

                if(!empty($comment)) {
                    $res    = $this->C2d->query("INSERT INTO c2d_order_comments (post_id, comment, user_id, created_at, created_date) VALUES ($post_id, '$comment', '". $this->Session->read('Auth.User.id')."', '". date('Y-m-d H:i:s') ."', '". date('Y-m-d') ."')");
                }
        }

        public function viewComment() {

                $this->autoRender = FALSE;

                $post_id    = $this->params['form']['post_id'];

                $res        = $this->C2d->query("SELECT id, comment FROM c2d_order_comments WHERE post_id = '$post_id' ORDER BY 1 DESC");

                echo json_encode($res);
        }

        public function postInterestListing($page = 1, $recs = 100) {

                $this->layout = "plain";

                $limit          = ($page - 1) * $recs . ',' . $recs;

                $count_records  = $this->C2d->query("SELECT count(1) FROM c2d_posts_interests LEFT JOIN c2d_posts ON c2d_posts.id = c2d_posts_interests.post_id ORDER BY 1 DESC");

                $listing_data   = $this->C2d->query("SELECT c2d_posts_interests.*, c2d_posts.title, c2d_posts.description FROM c2d_posts_interests LEFT JOIN c2d_posts ON c2d_posts.id = c2d_posts_interests.post_id ORDER BY 1 DESC LIMIT $limit");

                $retailers = array(0);
                foreach($listing_data as $ld) {
                    $retailers[] = $ld['c2d_posts_interests']['retailer_id'];
                }

                // $retailers = $this->Slaves->query("SELECT id, name, shopname, mobile FROM retailers WHERE id IN (" . implode(',', $retailers) . ")");
                $imp_data = $this->Shop->getUserLabelData(implode(',', $retailers),2,2);
                $retailer_temp = array();
                // foreach($retailers as $retailer) {
                //     $retailer_temp[$retailer['retailers']['id']]['name']        = $retailer['retailers']['name'];
                //     $retailer_temp[$retailer['retailers']['id']]['shopname']    = $retailer['retailers']['shopname'];
                //     $retailer_temp[$retailer['retailers']['id']]['mobile']      = $retailer['retailers']['mobile'];
                // }
                foreach($imp_data as $retailer) {
                    $retailer_temp[$retailer['ret']['id']]['name']        = $retailer['imp']['name'];
                    $retailer_temp[$retailer['ret']['id']]['shopname']    = $retailer['imp']['shop_est_name'];
                    $retailer_temp[$retailer['ret']['id']]['mobile']      = $retailer['ret']['mobile'];
                }

                $this->set('listing_data', $listing_data);
                $this->set('retailers', $retailer_temp);
                $this->set('totalrecords', $count_records[0][0]['count(1)']);
                $this->set('page', $page);
                $this->set('recs', $recs);
        }

        public function c2dPost($page = 1, $recs = 100) {

                $this->layout = "plain";

                $limit = ($page - 1) * $recs . ',' . $recs;

                $count_records = $this->C2d->query("SELECT count(1) FROM c2d_posts LEFT JOIN cash_payment_client ON (c2d_posts.client_id = cash_payment_client.id) LEFT JOIN c2d_categories ON (c2d_posts.category_id = c2d_categories.id) ORDER BY 1 DESC");

                $listing_data = $this->C2d->query("SELECT c2d_posts.*, cash_payment_client.company_name, cash_payment_client.contact_person_name, cash_payment_client.contact_no, c2d_categories.group_name FROM c2d_posts LEFT JOIN cash_payment_client ON (c2d_posts.client_id = cash_payment_client.id) LEFT JOIN c2d_categories ON (c2d_posts.category_id = c2d_categories.id) ORDER BY 1 DESC LIMIT $limit");

                $list_image = array(0);
                foreach($listing_data as $list) {
                    $list_image[] = $list['c2d_posts']['id'];
                }


                $list_image = $this->C2d->query("SELECT post_id, post_filename FROM c2d_posts_images WHERE post_id IN (". implode(',', $list_image) .")");
                $images = array(0);
                foreach($list_image as $list) {
                    $images[$list['c2d_posts_images']['post_id']][] = $list['c2d_posts_images']['post_filename'];
                }

                $this->set('listing_data', $listing_data);
                $this->set('listing_images', $images);
                $this->set('totalrecords', $count_records[0][0]['count(1)']);
                $this->set('page', $page);
                $this->set('recs', $recs);
        }
}