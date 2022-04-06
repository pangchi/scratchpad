# scratchpad
This enables users to copy text from mobile devices to computers in same home network.
Computer acts as a host. Requires http server with PHP. For QR scan, computer must have access to google APIs.
Password is not encrypted, use at your own risk.

Contains Bootstrap min and svg icons

Unable to see update? Check access right. In Linux, "chmod 777 scratch.json"
Also apply same 777 rights to /files directory.

Files larger than 2MB may not be uploaded, change upload_max_filesize = 8M in php.ini to >2MB. Recommend 8MB 

Uses fetch api. Not IE 11 compatible.
