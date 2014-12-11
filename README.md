#LATCH INSTALLATION GUIDE FOR OWNCLOUD


##PREREQUISITES 
 * ownCloud versions 7.0.0, 7.0.1, 7.0.2 and 7.0.3.

 * Curl extensions active in PHP (uncomment **"extension=php_curl.dll"** or"** extension=curl.so"** in Windows or Linux php.ini respectively. 

 * To get the **"Application ID"** and **"Secret"**, (fundamental values for integrating Latch in any application), it’s necessary to register a developer account in [Latch's website](https://latch.elevenpaths.com"https://latch.elevenpaths.com"). On the upper right side, click on **"Developer area"**.

 
##DOWNLOADING THE OWNCLOUD PLUGIN
 * When the account is activated, the user will be able to create applications with Latch and access to developer documentation, including existing SDKs and plugins. The user has to access again to [Developer area](https://latch.elevenpaths.com/www/developerArea"https://latch.elevenpaths.com/www/developerArea"), and browse his applications from **"My applications"** section in the side menu.

* When creating an application, two fundamental fields are shown: **"Application ID"** and **"Secret"**, keep these for later use. There are some additional parameters to be chosen, as the application icon (that will be shown in Latch) and whether the application will support OTP  (One Time Password) or not.

* From the side menu in developers area, the user can access the **"Documentation & SDKs"** section. Inside it, there is a **"SDKs and Plugins"** menu. Links to different SDKs in different programming languages and plugins developed so far, are shown.


##INSTALLING THE PLUGIN IN OWNCLOUD

* Once the administrator has downloaded the plugin, it has to be added in its administration panel in ownCloud. Unzip the downloaded plugin inside **“latch_plugin”** folder, and place the whole content inside **“apps”** folder, inside ownCloud folder set.
 

* Now, Latch plugin has to be enabled in ownCloud. To do so, go to the **"Apps"** section and tap the **"+ Apps"** button. On the list of apps, go to the Latch plugin referenced as **“Latch Authentication Plugin”** and then activate it by tapping the **“Enable”** button. 

 
* After enabling the plugin, enter the **"Application ID"** and the **"Secret"** previously generated. To do so, go to the **“Admin”** menu and, after entering the corresponding data in the **“Latch Configuration”** section, save the changes with the **“Save”** button.



##UNINSTALLING THE PLUGIN IN OWNCLOUD
* Follow these same steps in reverse to uninstall the plugin. First disable the plugin from the **“Disable Plugin”** link in the Latch setup window. 
 

* After accepting plugin disabling, uninstall it. To do so, go to the **"Apps"** section and tap the **"+ Apps"** button. On the list of apps, go to the Latch plugin referenced as “Latch Authentication Plugin” and then uninstall it by tapping the “Uninstall” link. After reloading the page, the reference to the Latch plugin will have disappeared.



##USE OF LATCH MODULE FOR THE USERS
**Latch does not affect in any case or in any way the usual operations with an account. It just allows or denies actions over it, acting as an independent extra layer of security that, once removed or without effect, will have no effect over the accounts, that will remain with its original state.**

###Pairing a user in ownCloud
The user needs the Latch application installed on the phone, and follow these steps:

* **Step 1:** Logged in your own ownCloud account and go to **"Personal"**.

* **Step 2:** From the Latch app on the phone, the user has to generate the token, pressing on **“Add a new service"** at the bottom of the application, and pressing **"Generate new code"** will take the user to a new screen where the pairing code will be displayed.

* **Step 3:** The user has to type the characters generated on the phone into the text box displayed on the web page. Click on **"Pair Account"** button.

* **Step 4:** Now the user may lock and unlock the account, preventing any unauthorized access.

###Unpairing a user in ownCloud
* The user should access their ownCloud account and under the **“Personal”** section tap the **“Unpair Account”** button. He will receive a notification indicating that the service has been unpaired.



##RESOURCES
- You can access Latch´s use and installation manuals, together with a list of all available plugins here: [https://latch.elevenpaths.com/www/developers/resources](https://latch.elevenpaths.com/www/developers/resources)

- Further information on de Latch´s API can be found here: [https://latch.elevenpaths.com/www/developers/doc_api](https://latch.elevenpaths.com/www/developers/doc_api)

- For more information about how to use Latch and testing more free features, please refer to the user guide in Spanish and English:
	1. [English version](https://latch.elevenpaths.com/www/public/documents/howToUseLatchNevele_EN.pdf)
	1. [Spanish version](https://latch.elevenpaths.com/www/public/documents/howToUseLatchNevele_ES.pdf)

- You can watch videos about installing and using Latch on [YouTube](https://www.youtube.com/user/ElevenPath) and [Vimeo](http://vimeo.com/elevenpaths).

- You can access all of ElevenPaths' documentation (including slides and all of Latch's manuals) in 
[Slideshare](http://www.slideshare.net/elevenpaths).
