#!/bin/bash

TIMESTAMP=$(date +"%F")
BACKUP_DIR="/var/backups"

# create backup
mysqldump -u root -proot sleepadvisor | gzip > "$BACKUP_DIR/sleepadvisor_$TIMESTAMP.gz"

# delete older than 30 days
find "$BACKUP_DIR" -name 'js_*' -mtime +30 -type f -delete

