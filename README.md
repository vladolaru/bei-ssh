# SSH - Secret Santa's Helper

This is a proof-of-concept app part of Pixelgrade's backend internship programme.

It is not intended to cover all the situations and security requirements of a real life web app, but plans to go a decent way in that direction.

It's main focus is to showcase a way (far from perfect) to approach a web app following the MVC design pattern.

## Nginx Config

Here is an example of how the nginx config should look like (this is just the location part):

```
location ~ ^/bei-ssh/?(.*) {
	try_files $uri $uri/ /bei-ssh/index.php?app-query=$1&$args;

	# Pass all .php files onto a php-fpm/php-fcgi server.
	location ~ [^/]\.php(/|$) {
		fastcgi_split_path_info ^(.+?\.php)(/.*)$;
		if (!-f $document_root$fastcgi_script_name) {
			return 404;
		}
		# This is a robust solution for path info security issue and works with "cgi.fix_pathinfo = 1" in /etc/php.ini (default)

		include ./fastcgi_params;
		fastcgi_index index.php;
		fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
	#	fastcgi_intercept_errors on;
		fastcgi_pass php;
	}
}
```

In this configuration, we assume the app is in a subdirectory of the domain root directory, called `bei-ssh`

## Apache .htaccess Config

The app already comes with a .htaccess but here is it just in case:

```
RewriteEngine On

RewriteBase /

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule ^(.*)$ index.php?app-query=$1 [L,QSA]
```

Put this in the `.htaccess` file in your app's root directory (where `index.php` resides).