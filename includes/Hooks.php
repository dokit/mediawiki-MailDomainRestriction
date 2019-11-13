<?php
namespace MailDomainExtension;

use Article;
use MediaWiki;
use OutputPage;
use PermissionsError;
use RequestContext;
use Title;
use User;
use WebRequest;

/**
 * Holds the hooks for the Lockdown extension.
 */
class Hooks {

	/* Check Domain if once- or trash-mail
	 */
	public static function onAbortNewAccount ( $user, &$abortError ) {
		global $wgMailDomainRestrictionWhitelist, $wgMailDomainRestrictionBlacklist;

var_dump('TOTO');die();

		if (! $wgMailDomainRestrictionWhitelist && ! $wgMailDomainRestrictionBlacklist) {
			return '';
		}

		$email = $user->getEmail();

		if (strpos($email, '@') === false) {
			return true;
		}
		$domain = substr($email, strpos($email, '@'));


		var_dump($domain);

		$abortError = wfMessage('not-implemented-yet');

		return true;

		if( !is_array( $wgCheckEmailAddressDomainSources ) || count( $wgCheckEmailAddressDomainSources ) <= 0 ) {
			return '';
		}

		if( $wgCheckEmailAddressDomainSources[ 'type' ] == CEASRC_FILE ) {
			$srcfile = $wgCheckEmailAddressDomainSources[ 'src' ];

			if( file_exists( $srcfile ) ) {
				$domlines = file( $srcfile );
			} else {
				return true;
			}

			foreach( $domlines as $domline ) {
				$domline = rtrim( $domline, "\r\n");
				$entry = "/@".$domline."/";
				if( preg_match( $entry, $domain ) ) {
					$abortError = wfMessage( 'checkemailaddress-domainerror' )->text();
					unset( $domline );
					return false;
				}
			}
		}
		unset( $domline );
		return true;
	}
}
