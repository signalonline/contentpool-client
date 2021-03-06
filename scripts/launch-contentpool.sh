#!/bin/bash

PHAPP_VERSION=0.6.2

set -e
set -x
cd `dirname $0`/../..

if ! command -v phapp > /dev/null; then
  echo Installing phapp...
  curl -L https://github.com/drunomics/phapp-cli/releases/download/$PHAPP_VERSION/phapp.phar > phapp
  chmod +x phapp
  sudo mv phapp /usr/local/bin/phapp
else
  echo Phapp version `phapp --version` found.
fi

[ ! -d ../contentpool ] || (echo "Old install profile is still existing, please remove ../contentpool." && exit 1)

git clone https://github.com/drunomics/contentpool.git contentpool

./contentpool/scripts/create-project.sh

# Make sure http port of both compose setups do not collide.
echo "WEB_HTTP_PORT=81" > ./contentpool-project/.docker.overrides.env

./contentpool/scripts/run-server.sh
./contentpool/scripts/init-project.sh

echo "Contentpool server project installed."
echo "SUCCESS."
