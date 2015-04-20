# Instagram

This module display your photo from your Instagram account. You need to know your Access token from your Instagram account.


## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is Instagram.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/instagram:~1.0
```

## Usage

In the configuration panel of this module, you can record your Access token, the username and the number of photo to display

## How to retrieve my Access Token

1. You have to connect to https://instagram.com/developer/ to create your application token.
2. Make sure you have un checked the "Disable implicit OAuth" option
3. Connect to https://instagram.com/oauth/authorize/?client_id=CLIENT-ID&redirect_uri=REDIRECT-URI&response_type=token and paste the token from the new URL : http://your-redirect-uri#access_token=ACESSTOKEN

