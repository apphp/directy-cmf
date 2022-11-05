<?php
Website::setMetaTags(array('title'=>A::t('tickets', 'Answers to Ticket')));

A::app()->getClientScript()->registerCssFile('assets/modules/tickets/css/tickets.css');

// Define active menu
$this->_activeMenu = 'tickets/userManageTickets';

$this->_breadCrumbs = array(
    array('label'=>A::t('app', 'Home'), 'url'=>Website::getDefaultPage()),
    array('label'=>A::t('tickets', 'Support'), 'url'=>'tickets/userManageTickets'),
    array('label'=>A::t('tickets', 'Replies to Ticket')),
);
?>

<div class="bloc">
    <div class="content">
        <?=$actionMessage;?>

        <div class="message">
        <?php
		foreach($arrDate as $arrDates):
			echo '<div class="date"><p>'.CHtml::encode($arrDates).'</p></div>';
			foreach($arrReplies as $arrAnswer):
				$timestamp = strtotime($arrAnswer['date_created']);
				if(date($dateFormat, $timestamp) == $arrDates):
					if($arrAnswer['account_role'] == 'admin'): $class = "tickets";
                    elseif($arrAnswer['account_role'] == 'user'): $class = "replies";
                    endif;?>

                    <div class="<?=$class?>">
                        <span><?=date($timeFormat, $timestamp).' '.CHtml::encode($arrAnswer['fullname'])?></span>
                        <p><?=nl2br(CHtml::encode($arrAnswer['message']))?></p>
						<?php if($arrAnswer['file']): ?>
							<p>
								----------<br>
								<?= A::t('tickets', 'Attachment'); ?>:  <a href="ticketReplies/attachment/tid/<?=$id?>/rid/<?=$arrAnswer['id']?>"><?=CHtml::encode($arrAnswer['file'])?></a>
							</p>
						<?php endif;?>
                    </div>

				<?php endif;?>
			<?php endforeach;
		endforeach;
		?>
        </div>
        <div class="block-body">
            <div id="reply-message">
            <?php
                echo CWidget::create('CDataForm', array(
                    'model'             => 'Modules\Tickets\Models\TicketReplies',
                    'operationType'     => 'add',
                    'action'            => 'ticketReplies/userReplies'.($id ? '/id/'.$id : ''),
                    'successUrl'        => 'ticketReplies/userReplies'.($id ? '/id/'.$id : ''),
                    'cancelUrl'         => 'tickets/userManageTickets',
                    'passParameters'=>false,
                    'method'=>'post',
                    'htmlOptions'=>array(
                        'name'=>'frmTicketsAnswerUserAdd',
                        'enctype'=>'multipart/form-data',
                        'autoGenerateId'=>true
                    ),
                    'requiredFieldsAlert'=>true,
                    'fields'=>array(
                        'message'   => array('type'=>'textarea', 'title'=>A::t('tickets', 'Message'), 'tooltip'=>'', 'default'=>'', 'validation' => array('required'=>true, 'type'=>'link', 'maxLength'=>5000), 'htmlOptions'=>array('maxLength'=>'5000', 'class'=>'large')),
                        'file'=>array(
                            'type'			 	=> 'fileUpload',
                            'title'			 	=> A::t('tickets', 'Attachment'),
                            'tooltip'		 	=> '',
                            'default'		 	=> '',
                            'download'          => false,
                            'validation'	 	=> array('required'=>false, 'type'=>'file', 'targetPath'=>'assets/modules/tickets/uploaded/'.$createPath.'/', 'maxSize'=>'1M', 'mimeType'=>'application/zip, application/pdf, image/jpg, image/jpeg, image/png, image/gif', 'fileName'=>CHash::getRandomString(10), 'filePrefix'=>'', 'filePostfix'=>'', 'htmlOptions'=>array()),
                            'fileOptions'	 	=> array('showAlways'=>false, 'class'=>'file', 'size'=>'25', 'filePath'=>'assets/modules/tickets/uploaded/'.$createPath),
                            'appendCode' 		=> '<br>('.A::t('tickets', 'allowed types').': zip, pdf, jpeg, jpg, png, gif)',
                        ),
                        'ticket_id'   		=> array('type'=>'data', 'default'=> $id),
                        'date_created'   	=> array('type'=>'data', 'default'=> date('Y-m-d H:i:s')),
                        'account_role'   	=> array('type'=>'data', 'default'=> 'user'),
                        'account_id'   		=> array('type'=>'data', 'default'=> CAuth::getLoggedId()),
                    ),
                    'buttons'=>array(
                        'submit'=>array('type'=>'submit', 'value'=>A::t('tickets', 'Submit Response'), 'htmlOptions'=>array('name'=>'', 'class'=>'btn btn-primary')),
                        'cancel'=>array('type'=>'button', 'value'=>A::t('tickets', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'btn btn-default')),
                    ),
                    'messagesSource'	=> 'core',
                    'alerts'			=> array('type'=>'flash', 'itemName'=>A::t('tickets', 'Reply')),
                    'return'            => true,
                ));
            ?>
            </div>
        </div>
    </div>
</div>