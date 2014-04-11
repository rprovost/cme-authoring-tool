This authoring tool has been designed to automate the process of
creating a CME bundle.

THIS AUTHORING TOOL IS PROVIDED "AS IS" AND WITHOUT ANY WARRANTY,
EXPRESS, IMPLIED OR OTHERWISE, REGARDING ITS CONDITION OR PERFORMANCE,
OR FITNESS FOR A PARTICULAR PURPOSE.

@author Ryan Provost (ryan.provost@sonymusic.com)
@author Chris Young-Zawada (chris.young-zawada@wmg.com)

VERSION HISTORY

0.9.4
==========
Moved directly from 0.9.2 to 0.9.4 as a result of requirement combining and shifting

* Added check to verify all uploads have completed before building
* Added check to verify a bundle has been titled
* Added check to verify a bundle has been given an artist
* Added check to verify a bundle has been given a GRid
* Resume feature will now recall previously uploaded files
* Updated bundle folder structure to reflect CME spec "best practices"
* Updated CMEAdmin parameters to reflect spec updates
* Updated CMEAdmin namespace URI
* Updated JS to fix all console errors
* Updated "album" to "collection" to reflect CME spec requirements
* Removed unnecessary "creations" output from manifest
* Visual polish to form fields

released: 2010-09-20


0.9.2
==========
* Fixed double-quote breaking XML attribute in manifest
* Fixed issue with ampersands in XML attributes
* Fixed issue with ungrouped asset when starting from scratch
* Fixed asset to artist reference
* Fixed naming nomenclature in code
* Resume feature now recalls "encoding" information
* Added storing of "protected" status for assets
* Added "doap" namespace on output manifest
* Added support for lyrics upload
* Added CMEAdmin parameter support
* Cleaned and organized Javascript
* Added visual polish
  - graphical "delete" button on assets
  - auto-ellipsis on headers
  - fixed weird shift when assets became scrollable or unscrollable
  - moved asset scrollbar inline with header/footer

released: 2010-07-02


0.9.1
==========
* Added versioning output in manifest
* Converted "import" feature to "plug-in" based system

released: 2010-06-11


0.9.0
==========
* Complete code re-write
* Database structure naming made more appropriate

released: 2010-05-21
