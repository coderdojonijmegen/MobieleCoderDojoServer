#!/usr/bin/env bash


make html &&
rm -rf docs/ &&
mv build/html/ docs/ &&
touch docs/.nojekyll
