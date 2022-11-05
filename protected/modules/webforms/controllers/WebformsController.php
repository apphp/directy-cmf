<?php
/**
 * Webforms controller
 *
 * PUBLIC:                  PRIVATE:
 * -----------              ------------------
 * __construct				
 * submitAction
 *
 */

namespace Modules\Webforms\Controllers;

// Modules
use \Modules\Webforms\Components\WebformsComponent;

// Framework
use \A,
	\CAuth,
	\CConfig,
	\CController,
	\CDatabase,
	\CHash,
	\CValidator,
	\CMailer,
	\CWidget;

// Directy
use \Bootstrap,
	\Modules,
	\ModulesSettings,
	\Website;

class WebformsController extends CController
{

	private $_backendPath = '';

	/**
	 * Class default constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Get BackEnd path
		$this->_backendPath = Website::getBackendPath();

		// Block access if the module is not installed
		if (!Modules::model()->isInstalled('webforms')) {
			if (CAuth::isLoggedInAsAdmin()) {
				$this->redirect($this->_backendPath . 'modules/index');
			} else {
				$this->redirect(Website::getDefaultPage());
			}
		}

		// Fetch site settings info
		$this->_settings = Bootstrap::init()->getSettings();
		$this->_view->backendPath = $this->_backendPath;

		///$this->_view->tabs = WebformsComponent::prepareTab('messages');
	}

	/**
	 * Controller submit action handler
	 */
	public function submitAction()
	{
		$cRequest = A::app()->getRequest();
		$name = $cRequest->getPost('contact_name');
		$email = $cRequest->getPost('contact_email');
		$phone = $cRequest->getPost('contact_phone');
		$company = $cRequest->getPost('contact_company');
		$message = $cRequest->getPost('contact_message');
		$captcha = $cRequest->getPost('contact_captcha');
		$arr = array();
		$errors = array();

		$fieldName = ModulesSettings::model()->param('webforms', 'field_name');
		$fieldEmail = ModulesSettings::model()->param('webforms', 'field_email');
		$fieldPhone = ModulesSettings::model()->param('webforms', 'field_phone');
		$fieldCompany = ModulesSettings::model()->param('webforms', 'field_company');
		$fieldMessage = ModulesSettings::model()->param('webforms', 'field_message');
		$fieldCaptcha = ModulesSettings::model()->param('webforms', 'field_captcha');

		if (!$cRequest->isPostRequest()) {
			$this->redirect(CConfig::get('defaultController') . '/');
		} elseif (APPHP_MODE == 'demo') {
			$arr[] = '"status": "0"';
			$arr[] = '"error": "' . A::t('core', 'This operation is blocked in Demo Mode!') . '"';
		} elseif ($fieldName == 'show-required' && empty($name)) {
			$arr[] = '"status": "0"';
			$arr[] = '"error": "' . A::t('webforms', 'The field name cannot be empty!') . '"';
		} elseif ($fieldEmail == 'show-required' && empty($email)) {
			$arr[] = '"status": "0"';
			$arr[] = '"error": "' . A::t('webforms', 'The field email cannot be empty!') . '"';
		} elseif (!empty($email) && !CValidator::isEmail($email)) {
			$arr[] = '"status": "0"';
			$arr[] = '"error": "' . A::t('webforms', 'You must provide a valid email address!') . '"';
		} elseif ($fieldPhone == 'show-required' && empty($phone)) {
			$arr[] = '"status": "0"';
			$arr[] = '"error": "' . A::t('webforms', 'The field phone number cannot be empty!') . '"';
		} elseif ($fieldCompany == 'show-required' && empty($company)) {
			$arr[] = '"status": "0"';
			$arr[] = '"error": "' . A::t('webforms', 'The field company name cannot be empty!') . '"';
		} elseif ($fieldMessage == 'show-required' && empty($message)) {
			$arr[] = '"status": "0"';
			$arr[] = '"error": "' . A::t('webforms', 'The field message cannot be empty!') . '"';
		} elseif (!CValidator::validateMinLength($message, 10)) {
			$arr[] = '"status": "0"';
			$arr[] = '"error": "' . A::t('webforms', 'Message must be at least 10 characters in length!') . '"';
		} elseif ($fieldCaptcha == 'show' && $captcha === '') {
			$arr[] = '"status": "0"';
			$arr[] = '"error": "' . A::t('webforms', 'The field captcha cannot be empty!') . '"';
		} elseif ($fieldCaptcha == 'show' && $captcha != A::app()->getSession()->get('captchaWebformResult')) {
			$arr[] = '"status": "0"';
			$arr[] = '"error": "' . A::t('webforms', 'Sorry, the code you have entered is invalid! Please try again.') . '"';
		} else {
			$isBanned = false;
			// Check if access is blocked to this IP address
			if (Website::checkBan('ip_address', $cRequest->getUserHostAddress(), $errors)) {
				$isBanned = true;
			} else {
				// Check if access is blocked to this email
				if (Website::checkBan('email_address', $email, $errors)) {
					$isBanned = true;
				} else {
					// Check if access is blocked to this email domain
					if (Website::checkBan('email_domain', $email, $errors)) {
						$isBanned = true;
					}
				}
			}

			if ($isBanned) {
				$this->_view->actionMessage = CWidget::create('CMessage', array($errors['alertType'], $errors['alert']));
			} else {
				$contactEmail = ModulesSettings::model()->param('webforms', 'contact_email');

				// Send email
				$body = '<b>' . A::t('webforms', 'Name') . '</b>: ' . strip_tags($name) . "\n";
				$body .= '<b>' . A::t('webforms', 'Email') . '</b>: ' . strip_tags($email) . "\n";
				if ($phone) $body .= '<b>' . A::t('webforms', 'Phone') . '</b>: ' . strip_tags($phone) . "\n";
				if ($company) $body .= '<b>' . A::t('webforms', 'Company') . '</b>: ' . strip_tags($company) . "\n";
				$body .= '<b>' . A::t('webforms', 'Message') . '</b>: <br>' . strip_tags($message) . "\n";

				CMailer::config(array(
					'mailer' => $this->_settings->mailer,
					'smtp_auth' => $this->_settings->smtp_auth,
					'smtp_secure' => $this->_settings->smtp_secure,
					'smtp_host' => $this->_settings->smtp_host,
					'smtp_port' => $this->_settings->smtp_port,
					'smtp_username' => $this->_settings->smtp_username,
					'smtp_password' => CHash::decrypt($this->_settings->smtp_password, CConfig::get('password.hashKey')),
				));

				$result = CMailer::send($contactEmail, A::t('webforms', 'Request from visitor (' . CConfig::get('name') . ')'), $body, array('from' => $this->_settings->general_email));
				if ($result) {
					$arr[] = '"status": "1"';
				} else {
					$arr[] = '"status": "0"';
					$arr[] = '"error": "' . A::t('webforms', 'An error occurred while sending email! Please try again later.') . '"';
				}
			}
		}

		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');   // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // Always modified
		header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
		header('Pragma: no-cache'); // HTTP/1.0
		header('Content-Type: application/json');

		echo '{';
		echo implode(',', $arr);
		echo '}';

		exit;
	}
}

