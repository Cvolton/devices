# Devices
This project can be used to create websites that broadcast useless information about your devices to the internet.

The following websites are powered by this project:
* https://hpgaming.online / https://hpgaming.eu
* https://wes2.online

## Setup
In order to use this project you need
* PHP (versions prior to 5.4 guaranteed NOT to work, versions prior to 7.3 untested; tested on 7.3.19)
* MySQL / MariaDB (tested on MariaDB 10.3.27)

Steps:
1. Import database.sql into a database
2. Edit database info in config/config.php, set a custom api key and set paths to your files
3. Create entries for your devices manually (note: OS type 0 = Linux, type 1 = Windows)
4. Upload relevant photos of your devices to the web server
5. Create CRON jobs or Windows tasks using Task Scheduler using our example scripts or adapt them for your own usage
6. Your device info should be viewable through view_device.php using the appropriate id get parameter.
7. Optional: Set up a reverse proxy or apache2 rewriting rules pointing your desired URL to the appropriate device

## Example scripts
### Arch Linux Bash
```bash
#!/bin/bash
curl -d "api_key=your_api_key_here&version_tag=`uname -a`&packages_count=`pacman -Q | wc -l`&id=1" -X POST http://127.0.0.1/devices/update_device.php
```

### Windows Powershell
```powershell
$buildtag = Get-ItemProperty -Path "Registry::HKEY_LOCAL_MACHINE\SOFTWARE\Microsoft\Windows NT\CurrentVersion" -Name BuildLabEx
$postParams = @{api_key='your_api_key_here';version_tag=$buildtag.BuildLabEx;packages_count='-1';id='1'}
Invoke-WebRequest -Uri http://127.0.0.1/devices/update_device.php -Method POST -Body $postParams
```

## update_device.php API
update_device.php accepts POST requests with these parameters

| Parameter      | Value           |
| -------------- |:-------------:|
| api_key        | Must match $apiKey in config/config.php |
| version_tag    | Displayed as "Build tag" or "Kernel version" depending on OS type |
| packages_count | Displayed as "Packages installed" with OS type 0 |
| id             | The ID of the operating system in the database |