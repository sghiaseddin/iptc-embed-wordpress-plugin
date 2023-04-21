# iptc-embed-wordpress-plugin
**IPTC Embed Wordpress Plugin**

This is a plugin for wordpress that add metadata on uploaded jpeg images. It is based on "Image metadata in Google Images" to add "**licensable**" badge on images. The Google-supported properties from schema.org/ImageObject are *creator*, *creditText*, *copyrightNotice*, and *license*. All these properties could be added as metadata to jpeg media. The plugin use IPTC and XMP standards to add metadata.


### How to use
The plugin create a settings page in wordpress settings. User can save following details to use later in embed action:
* Photographer Name / Creator
* Organization or Agancy / Credit Line
* Copyright Notice
* Web Statement of Rights / Copyright Info URL

For embed action, the plugin add a button to each row in media library table view. Clicking the green button, the function check the already embeded details and add/edit them if needed.

![Screenshot of IPCT Settings page](/screenshot/settings.jpeg)
*IPCT Settings page*

![Screenshot of Media Library page](/screenshot/iptc-embed-action.jpeg)
*Media Library page, table view*


### Related link
* [IPTC photo metadata](https://developers.google.com/search/docs/appearance/structured-data/image-license-metadata#iptc-photo-metadata)
* [Quick guide to IPTC Photo Metadata on Google Images](https://iptc.org/standards/photo-metadata/quick-guide-to-iptc-photo-metadata-and-google-images/)

To check media metadata, use iptc.org tool:
* [Get IPTC Photo Metadata](https://getpmd.iptc.org/getiptcpmd.html)


### Contribution
The following codes has been used as vendor with minor changes and improvment in this plugin:
* [Image metadata library](https://github.com/dchesterton/image)
* [IPTC EASY 1.0 - IPTC data manipulator for JPEG images](https://www.php.net/manual/en/function.iptcembed.php#85887)


### Changelog
* v1.0.0 initial release
