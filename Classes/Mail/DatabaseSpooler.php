<?php
namespace Rswebsystems\Mailqueue\Mail;

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

/**
 * Class DatabaseSpooler
 */
class DatabaseSpooler extends \Swift_ConfigurableSpool {

	/**
	 * Starts this Spool mechanism.
	 */
	public function start() {}

	/**
	 * Stops this Spool mechanism.
	 */
	public function stop() {}

	/**
	 * Tests if this Spool mechanism has started.
	 *
	 * @return boolean
	 */
	public function isStarted() {
		return TRUE;
	}

	/**
	 * @param \Swift_Mime_Message $message
	 *
	 * @return bool
	 */
	public function queueMessage(\Swift_Mime_Message $message) {

		return TRUE;
	}

	/**
	 * Sends messages using the given transport instance.
	 *
	 * @param \Swift_Transport $transport A transport instance
	 * @param \string[] $failedRecipients An array of failures by-reference
	 *
	 * @return integer The number of sent emails
	 */
	public function flushQueue(\Swift_Transport $transport, &$failedRecipients = NULL) {
		if (!$transport->isStarted()) {
			$transport->start();
		}

		$count = 0;
		while ($message = array_pop($this->messages)) {
			$count += $transport->send($message, $failedRecipients);
		}

		return $count;
	}


} 