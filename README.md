![Form SubmissionLogger Logo](https://raw.githubusercontent.com/luan11/form-submission-logger/master/example/images/form-submission-logger-logo.png)

# Form SubmissionLogger - A way to save form submissions and view then

## :rocket: Usage example

First download the project and use files from `/src` folder.

### To save the submission data from form

`form.php`

```php
<?php

use SubmissionLogger\SubmissionLogger;

require 'SubmissionLoggerAutoload.php';

$data = [
	'foo' => $_POST['bar'],
	'bar' => $_POST['foo']
];

if(SubmissionLogger::store($data) === false) {
	echo 'Log not registered';
} else {
	echo 'Log registered successfully';
}
```

### To see logs

`logs.php`

```php
<?php

use SubmissionLogger\SubmissionLogger;

require 'SubmissionLoggerAutoload.php';

$sl = new SubmissionLogger;

$sl->start();
```
## License

Copyright &copy; [Luan Novais](https://github.com/luan11).

This project is [MIT](https://github.com/luan11/form-submission-logger/blob/master/LICENSE) licensed.
