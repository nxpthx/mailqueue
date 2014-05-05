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
 * Class SpoolTransport
 */
class SpoolTransport extends \Swift_Transport_SpoolTransport {

	/**
	 *
	 */
	public function __construct() {
		parent::__construct(
			$this->getEventDispatcher(),
			$this->getDatabaseSpool()
		);
	}

	/**
	 * @return \Swift_Events_EventDispatcher
	 */
	protected function getEventDispatcher() {
		return \Swift_DependencyContainer::getInstance()->lookup('transport.eventdispatcher');
	}

	/**
	 * @return DatabaseSpooler
	 */
	protected function getDatabaseSpool() {
		return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Rswebsystems\Mailqueue\DatabaseSpooler');
	}

}