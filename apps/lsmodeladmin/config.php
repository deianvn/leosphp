<?php

#####################################
# Configuration #####################
#####################################

# Declares the active state of the application
# Values: true, false
# Default: false
# Required: no

$this->setEnabled(true);

# List of dependant modules used by the application
# values: array of strings representing the names of the modules
# Required: no

$this->addModule('lsutils');
$this->addModule('lsmysql');
$this->addModule('lsmodel', array('DBHost' => '127.0.0.1', 'DBUserName' => 'zapazi', 'DBPassword' => 'zapazi'));

# Define the default application action
# values: string value representing the action name
# Required: false

$this->setDefaultAction('Models_View');

# Define the default action parameters
# values: string value representing each action parameter
# Required: false

# $this->addDefaultActionParam('Hello, World!');

# Enable the localization support
# values: true, false
# Default: false
# Required: no

$this->setLocalizationEnabled(true);

# Define the default application locale
# values: any locale abbriviation or user defined string value
# Required: false

$this->setDefaultLocale('en');

# Cache resources
# Values: true, false
# Default: false
# Required: false

$this->setCacheResourcesEnabled(false);
