#!/bin/bash

function in_array {
    for i in "$1"; do
        if [ $i == $2  ]; then
            echo "1"
        fi
    done
}

connection=`php connection.php`

binDir=`cd ../../../bin; pwd`
propel="$binDir/propel"

appDir=`cd ../../../../app; pwd`
modelDir="$appDir/model"
schemaDir="$appDir/schema"

if [ $# == 0 ]; then
    $propel
    exit
fi

if [ $1 == "help" -o $1 == "test:prepare" ]; then
    $propel $@
    exit
fi

params=''

if [ "$(in_array $@ 'convert-conf')" == "1" -o "$(in_array $@ 'config:convert-xml')" == "1" ]
then
    params="--input-dir=$appDir/schema --output-dir=$appDir/schema"

elif [ "$(in_array $@ 'build')" == "1" -o "$(in_array $@ 'model:build')" == "1" ]
then
    params="--input-dir=$appDir/schema --output-dir=$appDir/model --disable-namespace-auto-package"

elif [ "$(in_array $@ 'sql')" == "1" -o "$(in_array $@ 'sql:build')" == "1" ]
then
    params="--input-dir=$appDir/schema --output-dir=$appDir/schema/generated-sql"

elif [ "$(in_array $@ 'insert')" == "1" -o "$(in_array $@ 'sql:build')" == "1" ]
then
    params="--input-dir=$appDir/schema --connection='$connection'"

elif [ "$(in_array $@ 'reverse')" == "1" -o "$(in_array $@ 'database:reverse')" == "1" ]
then
    params="--input-dir=$appDir/schema --output-dir=$appDir/schema/generated-reversed-database --connection='$connection'"

elif [ "$(in_array $@ 'diff')" == "1" -o "$(in_array $@ 'migration:diff')" == "1" ]
then
    params="--input-dir=$appDir/schema --output-dir=$appDir/schema/generated-migrations --connection='$connection'"

elif [ "$(in_array $@ 'down')" == "1" -o "$(in_array $@ 'migration:down')" == "1" \
     -o "$(in_array $@ 'migrate')" == "1" -o "$(in_array $@ 'migration:migrate')" == "1" \
     -o "$(in_array $@ 'status')" == "1" -o "$(in_array $@ 'migration:status')" == "1" \
     -o "$(in_array $@ 'up')" == "1" -o "$(in_array $@ 'migration:up')" == "1" ]
then
    params="--input-dir=$appDir/schema --output-dir=$appDir/schema/generated-migrations --connection='default=mysql:host=localhost;dbname=myplace2;user=root;password=123'"
fi

echo "$propel $params $@"
