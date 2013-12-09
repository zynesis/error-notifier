# ErrorNotifier plugin
ErrorNotifier is a CakePHP plugin that will notify when an error occurs at your site. There are two functions for this plugin, one is to report a handling error such as fatal errors and errors. The second function of this plugin is to allow you to call the plugin method to fire an email at will. This function will be useful if you want to notify yourself with fail save function or etc.

Set up
--------
1. Load the plugin in bootstrap.php

	```bash
	CakePlugin::load('ErrorNotifier');
	```

2. If you need error notification, please set the below line or you can skip this step

	Bootstrap.php

		App::uses('NotifierComponent', 'ErrorNotifier.Controller/Component');
	

	Core.php

		Configure::write('Error.handler', 'NotifierComponent::handleError');
	

3. Open up Plugin/ErrorNotifier/Config/bootstrap.php and modify the email values with yours


Voila, you're set
