Version
=======
Cloud 8.x-1.x
* This is under development as a very initial stage.

BASIC INFO
==========

Cloud (a.k.a Clanavi) is a set of modules that lets users administer public and 
private clouds from within Drupal. Clanavi provide users the ability to manage public 
clouds such as AWS EC2 clouds as well as private clouds like OpenStack, Eucalyptus and XCP.

FEATURES
========
- TODO

INSTALLATION: FOR USAGE WITH AMAZON EC2
=======================================
- TODO

BASIC USAGE: AMAZON EC2
=======================
- TODO

PERMISSONS
==========
- TODO

SYSTEM REQUIREMENTS
===================
- PHP    5.4 or Higher
- MySQL  5.5 or Higher
- Drupal 8.x
- 512MB Memory: If you use AWS Cloud module, the running host of this
  system requires more than 512MB memory to download a list of images
 (because it's huge amount of data for listing).

DIRECTORY STRUCTURE
===================

cloud
  +-modules (depends on Cloud module) (Cloud is a core module for Cloud package)
    +-cloud_alerts
    +-cloud_pricing
    +-cloud_scripting
    +-cloud_server_templates
    +-modules
     - cloud_service_providers
      - aws_cloud

hook_api() FOR SUBSIDIARY MODULES
======================================
- TODO

