<div class="pages index">
	<?php
	
	function addNode($node, $it) {
		?><li class="pageEntry">
		    <table>
					<tr>
						<td>
							<?php echo $it->Html->link(__($node['Page']['title'], true), array('action'=>'view',$node['Page']['id'])) ?>
						</td><td class="pageActions">
						  <span>
								<?php echo $it->Html->link(__('View', true), array('controller' => 'pages', 'action' => 'view', $node['Page']['id']), array('class'=>'btnView')); ?>
								<?php echo $it->Html->link(__('Edit', true), array('controller' => 'pages', 'action' => 'edit', $node['Page']['id']), array('class'=>'btnEdit')); ?>
								<?php echo $it->Html->link(__('Delete', true), array('controller' => 'pages', 'action' => 'delete', $node['Page']['id']), array('class'=>'btnDelete'), sprintf(__('Are you sure you want to delete "%s"?', true), $node['Page']['title'])); ?>
							</span>
					  </td>
					</tr>
				</table>
		
		
		<ul class="index"><?php
		  foreach ($node['ChildPages'] as $cnode) {
				addNode($cnode, $it);
			}
		?></ul>
		  </li><?php 
	} // addNode();
	
	?>
	
	<ul class="index">
		
		<?php addNode($root, $this); ?>
	</ul>
	
</div>


<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Create New Page', true), array('action' => 'add',$rootID)); ?> </li>
	</ul>
</div>