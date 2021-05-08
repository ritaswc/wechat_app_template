#!/bin/sh

STAGED_FILES=$(git diff --cached --name-only --diff-filter=ACM | grep ".jsx\{0,1\}$")

if [[ "$STAGED_FILES" = "" ]]; then
  exit 0
fi

PASS=true

echo "\nValidating Javascript:\n"

# Check for eslint
ESLINT="$(git rev-parse --show-toplevel)/node_modules/.bin/eslint"

if [[ ! -x "$ESLINT" ]]; then
  echo "\t\033[41mPlease install ESlint\033[0m (npm i --save --save-exact --dev eslint)"
  exit 1
fi

for FILE in $STAGED_FILES
do
  "$ESLINT" "$FILE"

  if [[ "$?" == 0 ]]; then
    echo "\t\033[32mESLint Passed: $FILE\033[0m"
  else
    echo "\t\033[41mESLint Failed: $FILE\033[0m"
    PASS=false
  fi
done

echo "\nJavascript validation completed!\n"

if ! $PASS; then
  echo "\033[41mCOMMIT FAILED:\033[0m Your commit contains files that should pass ESLint but do not. Please fix the ESLint errors and try again.\n"
  exit 1
else
  echo "\033[42mCOMMIT SUCCEEDED\033[0m\n"
fi

exit $?
