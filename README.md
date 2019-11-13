
## MailDomainRestriction

MailDomainRestriction is a mediawiki extension restrict email's domain at account creation.


## Installation

Extract extension ant place it in the 'extensions' directory of your installation. (the directory namme must be 'MailDomainRestriction')

Load extension in file LocalSetting.php : 

```
wfLoadExtension( 'MailDomainRestriction' );
```

and in this file, add either a domains whitelist or a blacklist : 


```
$wgMailDomainRestrictionWhitelist = ['alloweddomain.com'];
```
or 
```
$wgMailDomainRestrictionBlacklist = ['forbiddendomain.com'];
```

