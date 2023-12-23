#!/bin/bash

success=0
error=1

OUT_COLOR_RED='\033[0;31m'
OUT_COLOR_GREEN='\033[0;32m'
OUT_COLOR_BLUE='\033[0;34m'
OUT_NO_COLOR='\033[0m'

# вывод цветного сообщения
function output() {
  case $2 in
    success)
      echo -e "$OUT_COLOR_GREEN$1$OUT_NO_COLOR"
      ;;
    error)
      echo -e "$OUT_COLOR_RED$1$OUT_NO_COLOR"
      ;;
    * | info)
      echo -e "$OUT_COLOR_BLUE$1$OUT_NO_COLOR"
    ;;
  esac
}

function yesno() {
  default=''
  if [[ ! (-z $2)  ]]; then
   default=" [$2]"
  fi
  question="$1 (y/n)${default}:"
  while true; do
    read -p "${question}" answer
    if [[ ${answer} = "" ]]; then
        answer=$2
    fi
    case ${answer} in
      Y | y | yes ) return ${success};;
      N | n | no ) return ${error};;
      * ) echo "Please answer yes or no.";;
    esac
  done
}

# запуск docker-compose
function makeDocker() {

  if ! (docker info); then
    output "docker is not running. Can not continue" error
    return ${error}
  fi
  if ! (hash docker-compose 2>/dev/null); then
    output "docker-compose is not running. Can not continue." error
    return ${error}
  fi
  if ! [[ -f 'docker-compose.yml' ]]; then
    output "docker-compose.yml not found. Can not continue." error
    return ${error}
  fi
  if ! (docker volume inspect le_shop_pg_data >/dev/null); then
    docker volume create --name=le_shop_pg_data
  fi

  if ! (docker network inspect lisa_splitter_network >/dev/null); then
        docker network create --gateway 130.10.2.1 --subnet 130.10.2.0/24 phonywar_network
  fi

  if ! docker-compose up -d; then
    output "docker-compose could not" error
    return ${error}
  fi

  output "Docker containers have been set up successfully." success
  return ${success}
}

function copyEnv() {
  if ! [[ -f '.env' ]]; then
      cp .example.env .env
  fi
}

function chmodStorage() {
  chmod -R 777 storage
}

function initialiseApi() {
  output "generating api laravel encryption key" info
    docker exec phonywar_php bash -c "php artisan key:generate"
  output "key generation successful" success

  output "installing laravel packages" info
    docker exec phonywar_php bash -c "composer install"
  output "installation of laravel packages successful" success
}

function checkHosts() {
    HOSTS='127.0.0.1 phonywar.com'
    if grep "${HOSTS}" /etc/hosts | grep -v '^#'; then
      echo "${HOSTS} уже присутствуют в /etc/hosts"
    else
      sudo /bin/bash -c "echo -e '\n${HOSTS}' >> /etc/hosts";
      output "${HOSTS} have been added successfully to /etc/hosts." success
    fi
    output "The site is available at \n " info
    output "phonywar.com:20080 " info
}

function fullInstall() {
    copyEnv
    chmodStorage
    if ! makeDocker; then
        return
    fi
    initialiseApi
    checkHosts
}

fullInstall
