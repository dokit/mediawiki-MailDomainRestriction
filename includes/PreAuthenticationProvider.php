<?php
namespace MailDomainRestriction;

/**
 * Class PreAuthenticationProvider to check domain of email before authenticate
 */

use MediaWiki\Auth\AbstractPreAuthenticationProvider;
use Status;

class PreAuthenticationProvider extends AbstractPreAuthenticationProvider {

	/**
	 *
	 * {@inheritDoc}
	 * @see \MediaWiki\Auth\AbstractPreAuthenticationProvider::testForAccountCreation()
	 *
	 * @param \User $user
	 * @param \User $creator
	 * @param array $reqs
	 * @return Status
	 */
	public function testForAccountCreation( $user, $creator, array $reqs ) {
		global $wgMailDomainRestrictionBlacklist, $wgMailDomainRestrictionWhitelist;

		if ( !$wgMailDomainRestrictionBlacklist && !$wgMailDomainRestrictionWhitelist ) {
			return Status::newGood();
		}

		// extract domain from email :
		$email = $user->getEmail();
		if ( strpos( $email, '@' ) === false ) {
			return $this->makeError( wfMessage( 'maildomainrestriction-mailinvalid' ) );
		}
		$domain = substr( $email, strpos( $email, '@' ) + 1 );

		// check against blacklist :
		if ( $wgMailDomainRestrictionBlacklist ) {
			if ( in_array( $domain, $wgMailDomainRestrictionBlacklist ) ) {
				return $this->makeError( wfMessage( 'maildomainrestriction-mailisBlacklisted' ) );
			}
		}

		// check against whitelist :
		if ( $wgMailDomainRestrictionWhitelist ) {
			if ( !in_array( $domain, $wgMailDomainRestrictionWhitelist ) ) {
				return $this->makeError( wfMessage( 'maildomainrestriction-mailisnotallowed' ) );
			}
		}

		return \Status::newGood();
	}

	/**
	 * @param string $message Message
	 * @return Status
	 */
	protected function makeError( $message ) {
		global $wgMailDomainRestrictionMessage, $wgMailDomainRestrictionWhitelist;
		if ( $wgMailDomainRestrictionMessage ) {
			$message = $wgMailDomainRestrictionMessage;
		}
		if ( $wgMailDomainRestrictionWhitelist && count( $wgMailDomainRestrictionWhitelist ) == 1 ) {
			$allowedDomain = array_pop( $wgMailDomainRestrictionWhitelist );
			$message = wfMessage( 'maildomainrestriction-error-allowedmail', $allowedDomain );
		}
		return Status::newFatal( $message );
	}
}
