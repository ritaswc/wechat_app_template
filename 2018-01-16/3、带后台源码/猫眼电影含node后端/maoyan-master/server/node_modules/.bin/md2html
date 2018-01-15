#!/bin/sh
basedir=$(dirname "$(echo "$0" | sed -e 's,\\,/,g')")

case `uname` in
    *CYGWIN*) basedir=`cygpath -w "$basedir"`;;
esac

if [ -x "$basedir/node" ]; then
  "$basedir/node"  "$basedir/../markdown/bin/md2html.js" "$@"
  ret=$?
else 
  node  "$basedir/../markdown/bin/md2html.js" "$@"
  ret=$?
fi
exit $ret
