#!/bin/bash

everything() {( set -e # Fail if error
	cd /home/rboxlo/github

	# The following two commands are the same as git pull except they overwrite locally changed files.
	git fetch --all
	
	git reset --hard origin/master
	
	cd /home/rboxlo/github/Rboxlo

	docker-compose build .

	# Disable color codes which do not look good in the logs.
	export CLICOLOR=0
	
	docker-compose up -d --force-recreate --name rboxlo
)}

everything	
exit_status=$?

if [ ${exit_status} -ne 0 ]; then
	echo "We have an error."
	exit "${exit_status}"
fi
