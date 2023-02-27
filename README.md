The «**[Backend Login with Google Account](https://mage2.pro/c/extensions/google-backend-login)**» module for allows you to be authenticated in your Magento 2 backend using your Google Account.  
The module is **free** and **open source**.

**Demo video**: https://www.youtube.com/watch?v=DrjAdd5UwEI

![](https://mage2.pro/uploads/default/original/1X/00fd10a57da2c072fbf5f037824f6ece35476368.png)

## How to install
[Hire me in Upwork](https://upwork.com/fl/mage2pro), and I will: 
- install and configure the module properly on your website
- answer your questions
- solve compatiblity problems with third-party checkout, shipping, marketing modules
- implement new features you need 

### 2. Self-installation
```
bin/magento maintenance:enable
rm -f composer.lock
composer clear-cache
composer require mage2pro/google-backend-login:*
bin/magento setup:upgrade
bin/magento cache:enable
rm -rf var/di var/generation generated/code
bin/magento setup:di:compile
rm -rf pub/static/*
bin/magento setup:static-content:deploy -f en_US <additional locales>
bin/magento maintenance:disable
```

## How to update
```
bin/magento maintenance:enable
composer remove mage2pro/google-backend-login
rm -f composer.lock
composer clear-cache
composer require mage2pro/google-backend-login:*
bin/magento setup:upgrade
bin/magento cache:enable
rm -rf var/di var/generation generated/code
bin/magento setup:di:compile
rm -rf pub/static/*
bin/magento setup:static-content:deploy -f en_US <additional locales>
bin/magento maintenance:disable
```
