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
 * Class Mailer
 */
class Mailer extends \TYPO3\CMS\Core\Mail\Mailer {

	/**
	 * @param \Swift_Transport $transport
	 * @param boolean $useNonQueuedTransport
	 */
	public function __construct(\Swift_Transport $transport = NULL, $useNonQueuedTransport = FALSE) {
		if ($useNonQueuedTransport === FALSE) {
			$transport = $this->getQueueTransport();
		}
		parent::__construct($transport);
	}

	/**
	 * @return \Swift_Transport
	 */
	protected function getQueueTransport() {
		return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Rswebsystems\Mailqueue\SpoolTransport');
	}

} 