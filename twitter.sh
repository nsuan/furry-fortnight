#!/bin/bash -e

php  twitter.php  > twitter-tmp.txt

sort twitter-tmp.txt | uniq > twitter.txt

