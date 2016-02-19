# PHP Console Script to Upload to S3

## Requirements
Install AWS SDK for PHP with composer
```bash
$ php composer.phar install
```

__Optional__

You can remove raverent/kint from `composer.json`. I use it because it gives me a beautiful way to debug


## Configuration
Configure your AWS Access Key and Secret in `config.php`


## How to Run
From the terminal, run upload.php with the following parameters
* `-f`: The path of the file to be uploaded
* `-v`: validity of S3 URL in months. This value needs to be an integer greater than 0
* `-s`: subfolder (or the resource path)
* `-m`: (optional) Upload mode (file = 0 or stream = 1). Default is 1

Example: 

Upload file
```bash
$ php upload.php -f /path/to/file.pdf -v 12 -s the/sub/folder -m 0
```

Upload stream
```bash
$ php upload.php -f https://hoiio-dl.s3.amazonaws.com/recordings/WNCPnqfrobFdmZY2DcOO/rec-24da20bc-c746-49c3-9c05-ae10b0e639d2.mp3?a=123 -s test -v 12 -m 1
```
