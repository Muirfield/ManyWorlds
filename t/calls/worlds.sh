#!/bin/sh
#
phars="modular modcmd subcmd"

pre() {
  rm -rf $MPDIR/worlds/t*
}

post() {
  rm -rf $MPDIR/worlds/t*
}

case "$1" in 
  pre)
    pre "$@"
    ;;
  post)
    post "$@"
    ;;
esac
  