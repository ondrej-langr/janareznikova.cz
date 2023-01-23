#!/bin/bash
COMMAND="wp --path=${WORDPRESS_PATH}"
echo "Installing plugins and themes"

#Append below for the plugin installation

if [ $(${COMMAND} plugin is-installed hello) ]; then
  echo "Removing Useless Plugin hello"
  ${COMMAND} plugin delete hello
fi

if [ $(${COMMAND} plugin is-installed navz-photo-gallery) ]; then
  echo "Update navz-photo-gallery plugin"
  ${COMMAND} plugin update navz-photo-gallery --activate
else
  echo "Install navz-photo-gallery plugin"
  ${COMMAND} plugin install navz-photo-gallery --activate
fi

if [ $(${COMMAND} plugin is-installed advanced-custom-fields) ]; then
  echo "Update advanced-custom-fields plugin"
  ${COMMAND} plugin update advanced-custom-fields --activate
else
  echo "Install advanced-custom-fields plugin"
  ${COMMAND} plugin install advanced-custom-fields --activate
fi

if [ $(${COMMAND} plugin is-installed cookie-notice) ]; then
  echo "Update cookie notice plugin in order to sho information regarding cookies"
  ${COMMAND} plugin update cookie-notice --activate
else
  echo "Install cookie notice plugin in order to sho information regarding cookies"
  ${COMMAND} plugin install cookie-notice --activate
fi
