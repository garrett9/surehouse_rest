# SureHouse REST API
 - [About](#about)
 - [Installation](#installation)
 - [Configuration](#configuration)

# About
This application is the REST API that handles all requests for information in the SureHouse database. It only returns JSON responses, uses JSON web tokens as authentication, and was built on a LAMP stack.

# Installation
Installation of the application is very simple through git. Simply fun the following command.

```
git clone https://github.com/garrett9/surehouse_rest
```

As a side note, make sure that whatever user that runs your web server has permissions to execute and read all files, and also has permissions to write to the **storage** directory in the application.

# Configuration
Upon installing the application, there will be an **.env.example** example file in the root of the application. In this file, you will find the required configurations that need to be set in order to properly run the application. Once you finish your configurations, change this file to **.env** instead of **.env.example** in order for it to be used in the application.
