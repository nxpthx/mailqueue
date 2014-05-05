<?php
namespace Rswebsystems\Mailqueue\Domain\Model;

/***************************************************************
 * Copyright notice
 *
 * (c) 2014 Steffen Ritter <steffen.ritter@typo3.org>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the textfile GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

class QueueItem {

	/**
	 * @var integer
	 */
	protected $uid;

	/**
	 * @var integer
	 */
	protected $queued;

	/**
	 * @var integer
	 */
	protected $send = NULL;

	/**
	 * @var string
	 */
	protected $subject = '';

	/**
	 * @var string
	 */
	protected $recipients = '';

	/**
	 * @var string
	 */
	protected $failedRecipients = '';

	/**
	 * @var \Swift_Message
	 */
	protected $mail;

	/**
	 * Protect constructor
	 */
	protected function __construct() {}

	/**
	 * @return string
	 */
	public function getFailedRecipients() {
		return $this->failedRecipients;
	}

	/**
	 * @return \Swift_Message
	 */
	public function getMail() {
		return $this->mail;
	}

	/**
	 * @return int
	 */
	public function getQueued() {
		return $this->queued;
	}

	/**
	 * @return string
	 */
	public function getRecipients() {
		return $this->recipients;
	}

	/**
	 * @return int
	 */
	public function getSend() {
		return $this->send;
	}

	/**
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * @return int
	 */
	public function getUid() {
		return $this->uid;
	}

	/**
	 * @param array $failedRecipients
	 */
	public function updateAfterSend(array $failedRecipients) {
		$this->queued = time();
		$this->failedRecipients = print_r($failedRecipients, TRUE);
	}

	/**
	 * @param array $row
	 *
	 * @return QueueItem
	 */
	public static function createFromDatabaseRecord(array $row) {
		$queueItem = new self();

		$queueItem->uid = $row['uid'];
		$queueItem->subject = $row['subject'];
		$queueItem->queued = $row['queued'];
		$queueItem->send = $row['send'];
		$queueItem->mail = unserialize($row['mail']);
		$queueItem->recipients = $row['recipients'];
		$queueItem->failedRecipients = $row['failed_recipients'];

		return $queueItem;
	}

	/**
	 * @param \Swift_Message $mail
	 *
	 * @return QueueItem
	 */
	public static function createFromMail(\Swift_Message $mail) {
		$queueItem = new self();

		$queueItem->mail = $mail;
		$queueItem->subject = $mail->getSubject();

		$recipientArray = $mail->getTo();
		$recipientArray += $mail->getCc();
		$recipientArray += $mail->getBcc();
		$queueItem->recipients = print_r($recipientArray, TRUE);

		$queueItem->subject = $mail->getSubject();
		$queueItem->queued = $GLOBALS['EXEC_TIME'];

		return $queueItem;
	}

	/**
	 * @return array
	 */
	public function toDatabaseRecordArray() {
		if ($this->mail instanceof \TYPO3\CMS\Core\Mail\MailMessage) {
			$class = new \ReflectionClass('TYPO3\CMS\Core\Mail\MailMessage');
			$property = $class->getProperty('mailer');
			$property->setAccessible(true);
			$property->setValue($this->mail, NULL);
		}
		return array(
			'uid' => (int)$this->uid,
			'mail' => serialize($this->mail),
			'subject' => $this->subject,
			'queued' => $this->queued,
			'send' => $this->send,
			'recipients' => $this->recipients,
			'failed_recipients' => $this->failedRecipients
		);
	}
}