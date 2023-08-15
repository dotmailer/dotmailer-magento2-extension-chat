# Dotdigital Chat for Magento 2 (Magento Open Source and Adobe Commerce)
[![Packagist Version](https://img.shields.io/packagist/v/dotdigital/dotdigital-magento2-extension-chat?color=green&label=stable)](https://github.com/dotmailer/dotmailer-magento2-extension-chat/releases)
[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](LICENSE.md)

## Requirements
- This module requires the `Dotdigitalgroup_Email` module v4.18.0+

## Activation
- This module is included in our core extension. Please refer to [these instructions](https://github.com/dotmailer/dotmailer-magento2-extension#installation) to install via the Magento Marketplace.
- Ensure you have set valid API credentials in **Configuration > Dotdigital > Account Settings**
- Turn on the chat bubble by enabling chat in **Configuration > Dotdigital > Chat Settings**

## Changelog

### 1.7.2

##### Improvements
- We updated a controller to use different interfaces.
- We removed a redundant `EmailFlagManager` class.

### 1.7.1

##### Improvements
- We updated our module in line with newer PHPStan and coding standards.
- We removed two redundant controller files.

### 1.7.0

##### Improvements
- Our connector now supports all internal (staging) API endpoints, to facilitate QA.

### 1.6.1

##### Improvements
- We updated some class references in line with the automated setup feature in the Email module.
- PHP 7.2 is now a minimum requirement for running this module.

### 1.6.0

##### What's new
- This module has been renamed `dotdigital/dotdigital-magento2-extension-chat`.

##### Improvements
- We've added a new plugin to provide additional configuration values to our integration insight data cron.
- `setup_version` has been removed from module.xml; in the Dashboard, we now use composer.json to provide the current active module version.
- Menus and ACL resources are now translatable. [External contribution](https://github.com/dotmailer/dotmailer-magento2-extension-chat/pull/3)

### 1.4.0

###### Improvements
- We’ve replaced comapi.com URLs with their updated dotdigital.com equivalents.
- The `launchTimeout` delay in the widget embed code has been removed (there is already a delay without this).

###### Bug fixes
- The Chat API space id is now re-saved whenever the chat settings change, regardless of whether it was set before; this resolves a possible issue when configuring Chat across multiple websites. 
- We fixed a possible bug to do with checking for an API space id in browser local storage.

### 1.0.3

###### Improvements
- We've added a Content Security Policy whitelist for specific domains used by this module. [External contribution](https://github.com/dotmailer/dotmailer-magento2-extension-chat/pull/1)

### 1.0.2

###### Bug fixes
- Access tokens for the Chat API are now refreshed when they expire.
- We resolved some access control issues relating to non-admin user accounts.

### 1.0.1

###### What's new
- We've changed the _Chat_ menu item to _Dotdigital Chat_ in the Marketing menu.

### 1.0.0

###### What’s new
- Dotdigital Chat is now available for Magento merchants. Existing clients can enable chat via **Dotdigital > Chat Settings** to start using this new channel.  
