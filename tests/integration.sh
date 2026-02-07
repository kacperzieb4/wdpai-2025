#!/bin/bash

echo "TEST 1: Homepage (200)"
curl -i http://localhost:8080/

echo
echo "TEST 2: Login page (200)"
curl -i http://localhost:8080/login

echo
echo "TEST 3: Wrong route (404)"
curl -i http://localhost:8080/nie-istnieje

echo
echo "TEST 4: Forbidden (403)"
curl -i http://localhost:8080/manage-users
