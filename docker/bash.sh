#!/bin/bash

if [ "$1" != "" ]; then
    name="$1"
else
    name="api"
fi

container=$(sudo docker ps | grep "LDS-API-$name" | awk -F' ' '{print $1}')

if [ "$container" == "" ]; then
    echo ""
    echo "Container LDS-API-$name is not available yet"
    echo ""

    exit 1
fi

sudo docker exec -it "$container" bash
