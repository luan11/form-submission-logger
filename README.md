![Form SubmissionLogger Logo](https://raw.githubusercontent.com/luan11/form-submission-logger/master/example/images/form-submission-logger-logo.png)

# Form SubmissionLogger - A way to save form submissions and view then

## :triangular_flag_on_post: Usage example

### Before all, in file `config.php` (from `src` folder) choose your database type

#### Available database types

| Type | Need credentials | Recommended |
| ---- | ---------------- | ----------- |
| SQLite3 `default` | :x: | :+1: |
| MySQL | :heavy_check_mark: | :+1: |
| JSON | :x: | :-1: |

### To save the submission data from form

**File:** `save-log.php`

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

**File:** `see-logs.php`

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
