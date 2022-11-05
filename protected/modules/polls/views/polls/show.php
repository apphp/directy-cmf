<?php
Website::setMetaTags(array('title'=>A::t('polls', 'Polls')));

// Register polls main css file
A::app()->getClientScript()->registerCssFile('assets/modules/polls/css/polls.css');

$voteAlready = \Modules\Polls\Components\PollsComponent::hasUserVoteAlready($poll->id);
$votionPermission = \Modules\Polls\Components\PollsComponent::checkVotionPermission($poll->id);
$isClosed = $poll->isClosed();
$isActive = $poll->is_active;

// Define breadcrumbs title
$this->_breadcrumbsTitle = A::t('polls', 'Poll Question');

// Define breadcrumbs for this page
$this->_breadCrumbs = array(
	array('label'=>A::t('polls', 'Home'), 'url'=>Website::getDefaultPage()),
	array('label'=>A::t('polls', 'All Polls'), 'url'=>'polls/showAll'),
);
?>

<h3 class="title"><?= CHtml::encode($poll->poll_question); ?></h3>

<div class="block-body">
    <div class="poll-answers">

		<?php if ($successMessage): ?>
            <span class="message success"><?= $successMessage; ?></span>
		<?php elseif ($errorMessage): ?>
            <span class="message error"><?= $errorMessage; ?></span>
		<?php elseif (!$votionPermission): ?>
            <span class="message error"><?= A::t('polls', 'You must be logged in to vote!') ?></span>
		<?php elseif ($isClosed || !$isActive): ?>
            <span class="message error"><?= A::t('polls', 'Poll is closed') ?></span>
		<?php elseif ($voteAlready): ?>
            <span class="message error"><?= A::t('polls', 'You have already voted on this poll!') ?></span>
		<?php endif; ?>

		<?php
		if ($votionPermission && $isActive && !$isClosed && !$voteAlready):
			echo CHtml::openForm('polls/vote/id/'.$poll->id, 'post', array('name'=>'form-vote'));
		endif;
		?>

        <div class="progress-bars">
			<?php for($i = 1; $i < 6; $i++): ?>
				<?php
				$votesAttr = 'poll_answer_'.$i.'_votes';
				$pollAnswer = 'poll_answer_'.$i;

				if(!$answerText = CHtml::encode($poll->$pollAnswer)){
					continue;
				}

				if($poll->total_votes > 0){
					$percent = round($poll->$votesAttr/$poll->total_votes * 100, 1);
				}else{
					$percent = 0;
				}

				// progress-bar-primary
				if ($percent < 10){
					$bg = '#cc0000';
					$bg_class = 'progress-bar-danger';
				}elseif ($percent >= 10 && $percent < 50){
					$bg = '#cccc00';
					$bg_class = 'progress-bar-warning';
				}else{
					$bg = '#00cc00';
					$bg_class = 'progress-bar-success';
				}

				if($percent > 0){
					$percentText = $percent.'% - '.$poll->$votesAttr.' '.($poll->$votesAttr == 1 ? A::t('polls', 'vote') : A::t('polls', 'votes'));
					$toolTipOffset = (A::app()->getLanguage('direction') == 'rtl') ? 'left:-60px;' : 'right:-60px;';
				}else{
					$percentText = $percent.'%';
					$toolTipOffset = '';
				}
				?>

				<?php if ($votionPermission && $isActive && !$isClosed && !$voteAlready): ?>
                    <div class="progress-label">
                        <input type="radio" name="answer" id="answer_<?= $i; ?>" value="<?= $i; ?>">&nbsp;
                        <span><label for="answer_<?= $i; ?>"><?= $i.'. '.$answerText; ?></label></span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar <?= $bg_class; ?>" data-appear-progress-animation="<?= $percent; ?>%"  data-appear-animation-delay="<?= (($i - 1) * 100) ; ?>">
                            <span class="progress-bar-tooltip" style="<?= $toolTipOffset; ?>"><?= $percentText; ?></span>
                        </div>
                    </div>
				<?php else: ?>
                    <div class="progress-label">
                        <span><?= $i.'. '.$answerText; ?></span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar <?= $bg_class; ?>" data-appear-progress-animation="<?= $percent; ?>%"  data-appear-animation-delay="<?= (($i - 1) * 100) ; ?>">
                            <span class="progress-bar-tooltip" style="<?= $toolTipOffset; ?>"><?= $percentText; ?></span>
                        </div>
                    </div>
				<?php endif; ?>

                <!--<div class="slider" style="display:block;height:10px;width:<?//= $percent ?>%;background:<?//= $bg; ?>"></div>-->
                <!--<div class="percent"><?//= $percent ?> %</div>-->
                <!--<div style="clear:left"></div>-->
			<?php endfor; ?>
        </div>

		<?php if ($votionPermission && $isActive && !$isClosed && !$voteAlready): ?>
            <button type="submit" class="btn v-btn v-btn-default v-small-button">
                <i class="fa fa-bullhorn"></i><?= A::t('polls', 'Vote'); ?>
            </button>
			<?= CHtml::closeForm(); ?>
		<?php endif; ?>
    </div>
    <p><?= A::t('polls', 'Total Voted'); ?>: <?= $poll->total_votes; ?></p>
    <br>

	<?php if ($previousPolls): ?>
        <div class="polls-previous">
            <b><?= A::t('polls', 'Previous Polls'); ?>:</b>
            <ul>
				<?php foreach ($previousPolls as $poll): ?>
                    <li><a href="polls/show/id/<?= $poll['id'] ?>"><?= $poll['poll_question']; ?></a></li>
				<?php endforeach; ?>
            </ul>
        </div>
	<?php endif; ?>
</div>