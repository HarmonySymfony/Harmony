#!/bin/bash

set -e

host="$1"
port="$2"
shift 2
cmd="$@"

# Function to check if MySQL is ready
mysql_ready() {
    mysqladmin -h "$host" -P "$port" -uroot -proot ping > /dev/null 2>&1
}

until mysql_ready; do
    >&2 echo "MySQL is unavailable - sleeping"
    sleep 1
done

>&2 echo "MySQL is up - executing command"
exec $cmd
