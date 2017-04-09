#!bin/bash

vendor/bin/phinx migrate
vendor/bin/phinx seed:run