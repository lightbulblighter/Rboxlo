#!/bin/bash

# Make sure it exists
mkdir -p /var/www/assets

# Fix permissions
chown -R www-data:www-data /var/www

if [ ! -e /var/www/renders/users/0.png ]; then
	echo "/var/www/renders/users/0.png not found, creating..."
	mkdir -p /var/www/renders/users
	cp -r /var/www/bak/renders/users/0.png /var/www/renders/users/0.png
fi

if [ ! -e /var/www/constant/constanturation.php ]; then
	echo "/var/www/constant/constanturation.php not found, creating..."
	mkdir -p /var/www/constant
	cp -r /var/www/bak/constant/constanturation.sample.php /var/www/constant/constanturation.php
	echo "Please edit this file with your constanturation."
	exit 1
fi

# Start the first process
nginx
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start nginx: $status"
  exit $status
fi

# Start the second process
php-fpm
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start php-fpm: $status"
  exit $status
fi

# Naive check runs checks once a minute to see if either of the processes exited.
# This illustrates part of the heavy lifting you need to do if you want to run
# more than one service in a container. The container exits with an error
# if it detects that either of the processes has exited.
# Otherwise it loops forever, waking up every 60 seconds

while sleep 60; do
  ps aux | grep nginx | grep -q -v grep
  NGINX_STATUS=$?
  ps aux | grep php-fpm | grep -q -v grep
  PHP_STATUS=$?
  # If the greps above find anything, they exit with 0 status
  # If they are not both 0, then something is wrong
  if [ $NGINX_STATUS -ne 0 -o $PHP_STATUS -ne 0 ]; then
    echo "One of the processes has already exited."
    exit 1
  fi
done

