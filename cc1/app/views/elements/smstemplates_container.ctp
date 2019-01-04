<table cellpadding="0" cellspacing="0" class="table table-striped table-bordered">
            <tr>
                <th style="color:#fff;background-color: #428bca;">ID</th>
                <th style="color:#fff;background-color: #428bca;">Operator</th>
                <th style="color:#fff;background-color: #428bca;">SMS</th>
                <th style="color:#fff;background-color: #428bca;">Template</th>
                <th style="color:#fff;background-color: #428bca;">Type</th>
                <th style="color:#fff;background-color: #428bca;">Type Flag</th>
                <th style="color:#fff;background-color: #428bca;" class="actions"><?php __('Actions');?></th>
            </tr>
	<?php
	$i = 0;
	foreach ($templates as $template):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo '#'.$template['sms_templates']['id']; ?>&nbsp;</td>
		<td>
                    <?php echo (array_key_exists($template['sms_templates']['opr_id'], $providers)) ? $providers[$template['sms_templates']['opr_id']] : '--'; ?>&nbsp;
		</td>
		<td>
                    <?php echo $template['sms_templates']['template']; ?>&nbsp;
		</td>
		<td>
                    <?php echo $template['sms_templates']['template1']; ?>&nbsp;
		</td>
		<td>
                    <?php echo $template['sms_templates']['type']; ?>&nbsp;
                    <?php // echo (array_key_exists($template['sms_templates']['type'], $types)) ? $types[$template['sms_templates']['type']] : '--'; ?>&nbsp;
		</td>
		<td>
                    <?php echo (array_key_exists($template['sms_templates']['type_flag'], $type_flags[$template['sms_templates']['type']])) ? $type_flags[$template['sms_templates']['type']][$template['sms_templates']['type_flag']] : '--'; ?>&nbsp;
		</td>
		<td class="actions">
                    
                    <?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $template['sms_templates']['id'].'?page='.$this->params['url']['page']. (($this->params['url']['q'] != '') ? '&q='.$this->params['url']['q']:''))); ?> |
                    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $template['sms_templates']['id'].'?page='.$this->params['url']['page']. (($this->params['url']['q'] != '') ? '&q='.$this->params['url']['q']:'')), null, sprintf(__('Are you sure you want to delete # %s?', true),$template['sms_templates']['id'])); ?> | 
                    <?php echo $this->Html->link(__('Verify', true), array('action' => 'verify', $template['sms_templates']['id'].'?page='.$this->params['url']['page']. (($this->params['url']['q'] != '') ? '&q='.$this->params['url']['q']:''))); ?> |
                    <?php echo $this->Html->link(__('Verify with others', true), array('action' => 'verifyWithOthers', $template['sms_templates']['id'].'?page='.$this->params['url']['page']. (($this->params['url']['q'] != '') ? '&q='.$this->params['url']['q']:''))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>