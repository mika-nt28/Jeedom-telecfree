#!/bin/bash
echo 1 > /tmp/compilation_telecfree_in_progress
sudo apt-get install -y --force-yes netcat
echo 100 > /tmp/compilation_telecfree_in_progress
rm /tmp/compilation_telecfree_in_progress
