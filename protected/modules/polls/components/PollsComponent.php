<?php
/**
* PollsComponent
*
* PUBLIC: (statis)              PRIVATE
* -----------                   ------------------
* init							_getActualPoll
* prepareTab					_getMonthDay
* drawShortcode
* drawPollsBlock
* checkVotionPermission
* hasUserVoteAlready
* markPollAsVoted
* getResultPoll
* 
*
*/

namespace Modules\Polls\Components;

// Models
use \Modules\Polls\Models\Polls,
	\Modules\Polls\Models\Votes;

// Global
use \A,
	\CAuth,
	\CWidget,
	\CComponent,
	\CConfig,
	\CHtml,
	\CLoader,
	\CTime;

// Directy
use \Website,
	\Bootstrap,
	\LocalTime,
	\ModulesSettings,
	\SocialLogin;


class PollsComponent extends CComponent
{
    const NL = "\n";

    /**
     * Session var to block polls that user has already vote.
     */
    const SESSION_VAR = 'voted_polls';

    private static $countBlock = 0;
    private static $polls = null;

    /**
     * Initializes the class
     */
    public static function init()
    {
        return parent::init(__CLASS__);
    }

    /**
     * Prepares Polls module tabs
     * @param string $activeTab
     */
    public static function prepareTab($activeTab = 'info')
    {
        return CWidget::create('CTabs', array(
            'tabsWrapper'       => array('tag'=>'div', 'class'=>'title'),
            'tabsWrapperInner'  => array('tag'=>'div', 'class'=>'tabs'),
            'contentWrapper'    => array(),
            'contentMessage'    => '',
            'tabs'              => array(
                A::t('polls', 'Settings')   => array('href'=>Website::getBackendPath().'modules/settings/code/polls', 'id'=>'tabSettings', 'content'=>'', 'active'=>false, 'htmlOptions'=>array('class'=>'modules-settings-tab')),
                A::t('polls', 'Polls')      => array('href'=>'polls/manage', 'id'=>'tabInfo', 'content'=>'', 'active'=>($activeTab == 'polls' ? true : false)),
            ),
            'events'            => array(
                //'click' => array('field'=>$errorField)
            ),
            'return'            => true,
        ));
    }

    /**
     * Draws polls side block
     * @param string $title
     */
    public static function drawPollsBlock($title = '', $urlPage = '', $isShortcode = false, $id = 0, $onlyResult = false)
    {
        if(!ModulesSettings::model()->param('polls', 'allow_polls')){
            return '';
        }

        $sendAjax = false;
        $output = '';

        $configModule = CLoader::config('polls', 'main');
        $memberInfo = $configModule['members'];
        $memberRole = $configModule['memberRole'];
        $userTypes = array_keys($memberInfo);
        $loginLinkContent = '';
        $socialLoginButtons = array();
        $buttons = '';

        foreach($memberInfo as $type => $info){
            if(empty($info['loginPage']) || empty($info['loginType'])){
                continue;
            }

            // We need only defined member
            if($memberRole != $type){
				continue;
			}
			
            $loginPage = rtrim($info['loginPage'], '/');
            if(in_array($info['loginType'], array('all', 'social'))){
                $socialLoginButtons = array(
                    'facebook'=>$loginPage.'/type/facebook',
                    'twitter'=>$loginPage.'/type/twitter',
                    'linkedin'=>$loginPage.'/type/linkedin',
                );
            }

            if(!empty($info['name']) && in_array($info['loginType'], array('all', 'normal'))){
                if(!empty($loginLinkContent)){
                    $loginLinkContent .= ' '.A::t('polls', 'or').' ';
                }
                $loginLinkContent .= '<a href="'.$loginPage.'">'.$info['name'].'</a>';
            }
        }

        if(ModulesSettings::model()->param('polls', 'ajax_polls')){
            $sendAjax = true;
            // Register FancyBox files
            A::app()->getClientScript()->registerScriptFile('assets/vendors/toastr/toastr.min.js', 2);
            A::app()->getClientScript()->registerScriptFile('assets/modules/polls/js/polls.js', 2);
            A::app()->getClientScript()->registerCssFile('assets/vendors/toastr/toastr.min.css');
        }
        // Register polls main css file
        A::app()->getClientScript()->registerCssFile('assets/modules/polls/css/polls.css');

        if((int)$id != 0){
            $tablePolls = CConfig::get('db.prefix').Polls::model()->getTableName();
            $poll = Polls::model()->findByPk($id, array(
                'condition'=>'('.$tablePolls.'.expires_at IS NULL OR '.$tablePolls.'.expires_at > \''.LocalTime::currentDate().'\') AND '.$tablePolls.'.is_active = 1',
            ));
            $poll = !empty($poll) ? $poll->getFieldsAsArray() : array();
			
        }else{
            $poll = self::_getActualPoll(self::$countBlock++);
        }

        // if poll is not exists or show polls action do not draw block
        if(empty($poll) || substr(A::app()->getUri()->uriString(), 0, 10) == 'polls/show'){
            return $output;
        }

        $arrExpiry = self::_getMonthDay($poll['expires_at']);

        if($isShortcode != true){
            $output .= CHtml::openTag('div', array('id'=>'poll-'.($id == 0 ? self::$countBlock : 'id-'.$id), 'class'=>'side_block', 'style'=>(!empty($poll['background_color']) ? 'background:'.$poll['background_color'].';' : '').(!empty($poll['color_text']) ? 'color:'.$poll['color_text'].';' : '')));
            $output .= CHtml::tag('h3', array('class'=>'title','style'=>(!empty($poll['color_text']) ? 'color:'.$poll['color_text'].';' : '')), A::t('polls', 'Poll'));
            $output .= CHtml::tag('div', array('style'=>'border-top: 0.1em solid lightgrey;'),'');
        }else{
            $output .= CHtml::openTag('div', array('id'=>'poll-shortcode-'.($id == 0 ? self::$countBlock : 'id-'.$id), 'style'=>(!empty($poll['background_color']) ? 'background:'.$poll['background_color'].';' : '').(!empty($poll['color_text']) ? 'color:'.$poll['color_text'].';' : '')));
        }
        $output .= CHtml::tag('p', array('class'=>'block-title', 'style'=>(!empty($poll['color_text']) ? 'color:'.$poll['color_text'].';' : '')), CHtml::encode($poll['poll_question']));

        $showResult = $onlyResult || self::hasUserVoteAlready($poll['id']) || !self::checkVotionPermission($poll['id']);
        if($showResult){
            $output .= self::getResultPoll($poll);
            // Poll closed
            if($arrExpiry['years'] == 0 && ($arrExpiry['months'] > 0 || $arrExpiry['days'] > 0)){
                if($arrExpiry['months'] > 0 && $arrExpiry['days'] > 0){
                    $params = array();
                    $params['{months}'] = $arrExpiry['months'].' '.A::t('polls', $arrExpiry['months'] == 1 ? 'month' : 'months');
                    $params['{days}'] = $arrExpiry['days'].' '.A::t('polls', $arrExpiry['days'] == 1 ? 'day' : 'days');
                    $output .= A::t('polls', 'The poll will be closed in {months} and {days}', $params);
                    $output .= CHtml::tag('br');
                }else{
                    if($arrExpiry['months'] != 0){
                        $params = array('{time}'=>$arrExpiry['months'].' '.A::te('polls', $arrExpiry['months'] == 1 ? 'month' : 'months'));
                    }else{
                        $params = array('{time}'=>$arrExpiry['days'].' '.A::te('polls', $arrExpiry['days'] == 1 ? 'day' : 'days'));
                    }

                    $output .= A::t('polls', 'The poll will be closed in {time}', $params);
                    $output .= CHtml::tag('br');
                }
            }
            // Login as
            if(!$onlyResult && !in_array(CAuth::getLoggedRole(), $userTypes)){
                if(!empty($socialLoginButtons)){
                    $buttons = SocialLogin::drawButtons(
                        $socialLoginButtons,
                        false
                    );
                }

                if(!empty($loginLinkContent)){
					$output .= CHtml::tag('div', array('style'=>'border-top: 0.1em solid lightgrey;margin:5px 0;'),'');
                    $output .= A::t('polls', 'To be able to cast your vote you need to login as {login_link}', array('{login_link}'=>$loginLinkContent));
                    if(!empty($buttons)){
                        $output .= CHtml::tag('br');
                        $output .= CHtml::openTag('div', array('class'=>'polls-social-login'));
                        $output .= CHtml::tag('div', array('class'=>'title', 'style'=>(!empty($poll['color_text']) ? 'color:'.$poll['color_text'].';' : '')), A::t('polls', 'or via').':');
                        $output .= $buttons;
                        $output .= CHtml::closeTag('div');
                        $output .= CHtml::tag('br');
                    }
                }else{
                    if(!empty($buttons)){
                        $output .= CHtml::openTag('div', array('class'=>'polls-social-login'));
                        $output .= $buttons;
                        $output .= CHtml::closeTag('div');
                        $output .= CHtml::tag('br');
                    }
                }
            }
            $output .= CHtml::link(A::t('polls', 'Polls Archive'), 'polls/showAll', array('class'=>'polls-archive'));
        }else{
            $output .= CHtml::openForm('polls/vote/id/'.$poll['id'], 'post', array('id'=>'form-vote-id-'.$poll['id'],'name'=>'form-vote', 'class'=>'form-polls-block', 'data-id'=>$poll['id'])).self::NL;
            if($sendAjax){
                $output .= CHtml::hiddenField('id', $poll['id']);
            }

            for($i = 1; $i <= 5; $i ++){
                if($answerText = $poll['poll_answer_'.$i]){
                    $output .= CHtml::openTag('label');
                    $output .= CHtml::radioButton('answer', false, array('id'=>'form_'.$poll['id'].'_answer_'.$i, 'value'=>$i)).$answerText;
                    $output .= CHtml::closeTag('label');
                }
            }
	
            if($arrExpiry['years'] || $arrExpiry['months'] > 0 || $arrExpiry['days'] > 0){
                $arrExpiry['months'] += $arrExpiry['years'] * 12;
                if($arrExpiry['months'] > 0 && $arrExpiry['days'] > 0){
					$params = array();
                    $params['{months}'] = $arrExpiry['months'].' '.A::t('polls', $arrExpiry['months'] == 1 ? 'month' : 'months');
                    $params['{days}'] = $arrExpiry['days'].' '.A::t('polls', $arrExpiry['days'] == 1 ? 'day' : 'days');
                    $output .= A::t('polls', 'The poll will be closed in {months} and {days}', $params);
                    $output .= CHtml::tag('br');
                }else{
                    if($arrExpiry['months'] != 0){
                        $params = array('{time}'=>$arrExpiry['months'].' '.A::te('polls', $arrExpiry['months'] == 1 ? 'month' : 'months'));
                    }else{
                        $params = array('{time}'=>$arrExpiry['days'].' '.A::te('polls', $arrExpiry['days'] == 1 ? 'day' : 'days'));
                    }

                    $output .= A::t('polls', 'The poll will be closed in {time}', $params);
                    $output .= CHtml::tag('br');
                }
            }
            $output .= CHtml::tag('br');
            $output .= CHtml::link(A::t('polls', 'Vote'), 'javascript:void(0);', array('class'=>'link-form-submit', 'onclick'=>$sendAjax == false ? 'this.parentNode.submit(); return false;' : ''));
            $output .= ' | ';
            $output .= CHtml::link(A::t('polls', 'Results'), 'polls/show/id/'.$poll['id'], array('class'=>'bold', 'data-type'=>'result'));
            $output .= CHtml::tag('br');
            $output .= CHtml::link(A::t('polls', 'Polls Archive'), 'polls/showAll', array('class'=>'polls-archive'));
            $output .= CHtml::closeForm().self::NL;

            $jsPollsBlog = self::getJSPollsBlock();
            A::app()->getClientScript()->registerScript(
                'formPollsBlock',
                $jsPollsBlog,
                2
            );

        }

        // Close tag id="poll-..."
        $output .= '</div>';

        return $output;
    }

    public static function getJSPollsBlock()
    {
        $output = 'jQuery(document).ready(function(){
                var $ = jQuery;
                var saveBlockVote = null;
                $("body").on("click", ".link-form-submit", function(){
                    var form = $(this).closest("form");
                    var token = form.find("input[name=APPHP_CSRF_TOKEN]").val();
                    var id = form.find("input[name=id]").val();
                    var answer = form.find("input[name=answer]:checked").val();

                    if(answer == undefined){
                        apAlert("'.A::te('polls', 'To vote in a poll, you must select an answer').'", "error");
                    }else if(id == undefined){
                        apAlert("'.A::te('polls', 'Input parameters error').'", "error");
                    }else{
                        $.ajax({
                            url: "polls/vote/id/" + id,
                            global: false,
                            type: "POST",
                            data: {
                                APPHP_CSRF_TOKEN: token,
                                act: "send",
                                answer: answer,
                            },
                            dataType: "html",
                            async: true,
                            error: function(html){
                                console.error("AJAX: cannot connect to the server or server response error!");
                            },
                            success: function(html){
                                var obj = $.parseJSON(html);
                                if(obj.html !== undefined && obj.html != ""){
                                    $(".form-polls-block[data-id=" + id + "]").replaceWith(obj.html);
                                    apAlert("'.A::te('polls', 'Thanks for your answer').'", "success");
                                }else{
                                    if(obj.error_message != null && obj.error_message != ""){
                                        apAlert(obj.error_message, "error");
                                    }else{
                                        apAlert("'.A::te('polls', 'Unknown error').'", "error");
                                    }
                                }
                            }
                        });
                    }
                    return false;
                });
                $("body").on("click", ".form-polls-block a[data-type=result]", function(){
                    var form = $(this).closest("form");
                    var id = form.find("input[name=id]").val();

                    saveBlockVote = form.parent().html();

                    if(id == undefined){
                        apAlert("'.A::te('polls', 'Input parameters error').'", "error");
                    }else{
                        $.ajax({
                            url: "polls/show/id/" + id,
                            global: false,
                            type: "POST",
                            data: {},
                            dataType: "html",
                            async: true,
                            error: function(html){
                                console.error("AJAX: cannot connect to the server or server response error!");
                            },
                            success: function(html){
                                var obj = $.parseJSON(html);
                                
                                if(obj.html !== undefined && obj.html != ""){
                                    form.replaceWith(obj.html);
                                }else{
                                    if(obj.error_message != null && obj.error_message != ""){
                                        apAlert(obj.error_message, "error");
                                    }else{
                                        apAlert("'.A::te('polls', 'Unknown error').'", "error");
                                    }
                                }
                            }
                        });
                    }
                    return false;
                });
                $("body").on("click", "a[data-type=vote]", function(){
                    var block = $(this).parent();
                    console.log(saveBlockVote);
                    block.html(saveBlockVote);

                    return false;
                });
            });';
        return $output;
    }

    public static function getResultPoll($poll){
        if(!is_array($poll)){
            return false;
        }

        $output = '';
        $answers = array();
        $maxAnswers = 0;
        $sumAnswers = 0;
        for($i = 0; $i <= 5; $i++){
            if(!empty($poll['poll_answer_'.$i])){
                $answers[] = array('votes'=>$poll['poll_answer_'.$i.'_votes'], 'answer'=>$poll['poll_answer_'.$i]);
                $sumAnswers += $poll['poll_answer_'.$i.'_votes'];
                if($maxAnswers < $poll['poll_answer_'.$i.'_votes']){
                    $maxAnswers = $poll['poll_answer_'.$i.'_votes'];
                }
            }
        }

        if(!empty($answers)){
            $totalVotes = 0;
            $output .= '<table class="poll-table" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>';
            foreach($answers as $answer){
                $widthPercent = !empty($maxAnswers) ? round((($answer['votes'] / $maxAnswers) * 100), 1) : 0;
                $percent = !empty($sumAnswers) ? round((($answer['votes'] / $sumAnswers) * 100), 1) : 0;
                $output .= '<tr>
                            <td align="left" class="cell-answer">
                                <span'.(!empty($poll['color_text']) ? ' style="color:'.$poll['color_text'].';"' : '').'>'.$answer['answer'].'</span>
                            </td>
                            <td align="left" class="cell-poll-bar">
                                <div><div class="ap-bar poll-bar" ap-wratio="1" style="width:'.$widthPercent.'%; display: block;">&nbsp;</div></div>
                            </td>
                            <td align="right" class="cell-poll-percent"'.(!empty($poll['color_text']) ? ' style="color:'.$poll['color_text'].';"' : '').'>'.$percent.'%</td>
                            <td align="right" class="cell-votes"'.(!empty($poll['color_text']) ? ' style="color:'.$poll['color_text'].';"' : '').'>'.$answer['votes'].'</td>
                        </tr>';
                $totalVotes += $answer['votes'];
            }
            $output .= '<tr><td colspan="4" style="text-align:right;padding-top:5px;"><b'.(!empty($poll['color_text']) ? ' style="color:'.$poll['color_text'].';"' : '').'>'.A::t('polls', 'Total').': '.$totalVotes.'</b></td></tr>';
            $output .= '</tbody></table>';
        }

        return $output;
    }

    /**
     * Draws shortcode output.
     * @param array $params
     */
    public static function drawShortcode($params = array())
    {
        $id = isset($params[0]) ? (int)$params[0] : 0;
        $onlyResult = isset($params[1]) && $params[1] == 'result' ? true : false;
        return self::drawPollsBlock('', '', true, $id, $onlyResult);
    }

    /**
     * Check votion permissions.
     * @return boolean
     */
    public static function checkVotionPermission($pollId = null)
    {
        $voterType = ModulesSettings::model()->param('polls', 'voter_type');
        switch($voterType){
            case 'registered_only':
                $configModule = CLoader::config('polls', 'main');
                $memberInfo = $configModule['members'];
                $userTypes = array_keys($memberInfo);
                // If the user entered under one of the types of user defined in settings
                $voteAccess = in_array(CAuth::getLoggedRole(), $userTypes) ? true : false;
                break;
            default:
                $voteAccess = true;
                break;
        }

        return $voteAccess;
    }

    /**
     * Whether user has vote on poll
     * @return boolean
     */
    public static function hasUserVoteAlready($pollId)
    {
        $vote = null;
        $voterType = ModulesSettings::model()->param('polls', 'voter_type');
        $configModule = CLoader::config('polls', 'main');
        $memberInfo = $configModule['members'];
        $userTypes = array_keys($memberInfo);
        // If the user entered under one of the types of user defined in settings
        if(in_array(CAuth::getLoggedRole(), $userTypes)){
            $vote = Votes::model()->find('account_id = :account_id AND poll_id = :poll_id', array(':account_id'=>CAuth::getLoggedId(), ':poll_id'=>$pollId));
        }elseif($voterType == 'all' && !in_array(CAuth::getLoggedRole(), $userTypes)){
            return in_array($pollId, A::app()->getSession()->get(self::SESSION_VAR, array()));
        }
        return !empty($vote) ? true : false;
    }

    /**
     * Mark poll as voted via session.
     */
    public static function markPollAsVoted($pollId)
    {
        $pollId = (int)$pollId;
        $voterType = ModulesSettings::model()->param('polls', 'voter_type');
        if(!empty($pollId) && !self::hasUserVoteAlready($pollId)){
            $configModule = CLoader::config('polls', 'main');
            $memberInfo = $configModule['members'];
            $userTypes = array_keys($memberInfo);
            // If the user entered under one of the types of user defined in settings
            if(in_array(CAuth::getLoggedRole(), $userTypes)){
                $vote = new Votes();
                $vote->account_id = CAuth::getLoggedId();
                $vote->poll_id = $pollId;
                $vote->save();
            }elseif($voterType == 'all'){
                $session = A::app()->getSession();
                $votedPolls = $session->get(self::SESSION_VAR, array());
                array_push($votedPolls, $pollId);
                $session->set(self::SESSION_VAR, $votedPolls);
            }
        }
    }

    /**
     * Prepare Polls
     * @param int $number
     * @return array
     */
    private static function _getActualPoll($number = 0)
    {
        if(self::$polls === null){
            $polls = array();

            $tablePolls = CConfig::get('db.prefix').Polls::model()->getTableName();
            $allPolls = Polls::model()->findAll(array(
                'condition'=>'('.$tablePolls.'.expires_at IS NULL OR '.$tablePolls.'.expires_at > \''.LocalTime::currentDate().'\') AND '.$tablePolls.'.is_active = 1',
                'order'=>'sort_order DESC, created_at DESC',
            ));

            if(!empty($allPolls) && is_array($allPolls)){
                $basePath = trim(A::app()->getRequest()->getRequestUri(), '\\/');
                if(empty($basePath)){
                    $basePath = trim(Website::getDefaultPage(), '\\/');
                }
                $basePath .= '/';
                foreach($allPolls as $poll){
                    if(!empty($poll['instruction'])){
                        $pollInstruction = rtrim($poll['instruction'],'\\/').'/';
                        if($poll['type_comparison'] == 0){
                            // Equally
                            if($pollInstruction == $basePath){
                                $polls[] = $poll;
                            }
                        }elseif($poll['type_comparison'] == 1){
                            // Substring
                            if(stristr($basePath, $pollInstruction) !== false){
                                $polls[] = $poll;
                            }
                        }
                    }else{
                        // If you specify a null value, such a poll is for any page
                        $polls[] = $poll;
                    }
                }
            }

            self::$polls = $polls;
        }


        if(isset(self::$polls[$number])){
            return self::$polls[$number];
        }else{
            return null;
        }
    }

    /**
	 * Returns month day
	 * @param string $expiresAt
	 * @retirn array
     */
	private static function _getMonthDay($expiresAt = '')
    {
        $arrOut = array(
            'years'  => 0,
            'months' => 0,
            'days'   => 0
        );

        if(!CTime::isEmptyDate($expiresAt)){
            $expiresAtTime = strtotime($expiresAt);
            $currentTime = LocalTime::currentDate(null);

            $expiresAtYear = date('Y', $expiresAtTime);
            $expiresAtMonth = date('m', $expiresAtTime);
            $expiresAtDay = date('d', $expiresAtTime);

            $currentYear = date('Y', $currentTime);
            $currentMonth = date('m', $currentTime);
            $currentDay = date('d', $currentTime);

            // Transfer days from the previous month, to calculate
            if($expiresAtDay < $currentDay){
                if($expiresAtMonth > 1){
                    $expiresAtDay += cal_days_in_month(CAL_GREGORIAN, $expiresAtMonth - 1, $expiresAtYear);
                }else{
                    $expiresAtDay += cal_days_in_month(CAL_GREGORIAN, 12, $expiresAtYear - 1);
                }
                // Transfer months from the previous year, to calculate
                if($expiresAtMonth == 1){
                    $expiresAtMonth = 12;
                    $expiresAtYear--;
                }else{
                    $expiresAtMonth--;
                }
            }
            // Transfer months from the previous year, to calculate
            if($expiresAtMonth < $currentMonth){
                $expiresAtMonth += 12;
                $expiresAtYear--;
            }
            $years = $expiresAtYear - $currentYear;
            if($years >= 0){
                $arrOut['years'] = $years;
                $arrOut['months'] = $expiresAtMonth - $currentMonth;
                $arrOut['days'] = $expiresAtDay - $currentDay;
            }
        }

        return $arrOut;
    }
}