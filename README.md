Version
=======
Cloud 8.x-1.x
- This branch is still under development.  Any volunteer effort is greatly appreciated.
- Currently, aws_cloud is the only cloud implementation that is working.
- Future support includes OpenStack, Eucalyptus, Cloudn, Docker via Kubernetes

BASIC INFO
==========
Cloud (a.k.a Clanavi) is a set of modules that lets users administer public and 
private clouds from within Drupal. Clanavi provide users the ability to manage public 
clouds such as AWS EC2 clouds as well as private clouds like OpenStack, Eucalyptus and XCP.


INSTALLATION: FOR USAGE WITH AMAZON EC2
=======================================
- Download cloud module
- Enable the aws_cloud module.  This will also enable the required modules.

BASIC USAGE: AMAZON EC2
=======================
  Basic setup
  -----------
  1. Create a new Cloud Config based on your needs.  Go to Structure > Cloud config list and "+ Add Cloud config"
  2. Enter all required configuration parameters.  *You must add each aws region as a separate Cloud Config entity*
  3. Run cron to update your specific cloud region.
  4. Use the links under "Cloud Service Providers > [CLOUD CONFIG]" to manager your AWS EC2 entities.
  5. Import Images using "Cloud Service Providers > [CLOUD CONFIG]" Images tab.
    - Click on "+ Import AWS Cloud Image"
    - Search for images by ami name.  For example, to import Anaconda images based on Ubuntu, type in "anaconda*ubuntu*".
      Use the AWS Console on aws.amazon.com to search for images to import
  6. Import or Add a Keypair.  The keypair is used to log into any system you launch.  Use the links under
    "Cloud Service Providers > [CLOUD CONFIG]" Key Pair tab
      - Use the "+ Import AWS Cloud Key Pair" button to import an existing key pair.  You will be uploading your public key.
      - Use "+ Add AWS Cloud Key Pair" to have AWS generate a new private key.  You will be prompted to download the key
        after it is created.
  7. Setup security groups, network interfaces as needed.

  Launching instance
  ------------------
  1. Create a server template under "Design > Cloud Server Template > [CLOUD CONFIG]"
  2. Once template is created, click the "Launch" tab to launch it.

PERMISSIONS
===========
Configure permissions per your requirements

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
    +-cloud_alerts (Not working in 8.x-1.x)
    +-cloud_pricing ((Not working in 8.x-1.x)
    +-cloud_scripting (Not working in 8.x-1.x)
    +-cloud_server_templates
    +-modules
     - cloud_service_providers
      - aws_cloud

