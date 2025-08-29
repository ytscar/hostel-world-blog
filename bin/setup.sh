#!/bin/bash

# Ensure siteurl and home are correct
wp-env run cli wp option update siteurl "http://hostel-world-blog.localhost:8274"
wp-env run cli wp option update home "http://hostel-world-blog.localhost:8274"

# Replace old domain references with local's across the whole DB
wp-env run cli wp search-replace 'https://www.hostelworld.com/blog' 'http://hostel-world-blog.localhost:8274' --all-tables --skip-columns=guid
