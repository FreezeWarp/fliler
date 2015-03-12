## Executables ##
Note: Fliler software components are not tested for use on IIS, though most of the recommended packages have Windows version. Packages are all loaded through their Linux executable name - for instance, to load "abiword.exe" you will need to create a link on "abiword" or all functionality will fail. Since this is all recommended, and certainly not needed for the core functionality, Fliler will still work without.

1. AbiWord and AbiText (makes possible the editing of .doc, .docx, .odt, and .rtf files)

2. Batik is also recommended for support of SVG file viewing. However, this comes at quite a cost since Batik has a very large install size. Still, this will make SVG viewing, even on Internet Explorer 8 and under, possible.

## PHP Libraries ##
#### Required ####
1. MySQLi (UAC, file searching, file backups)

2. PCRE

3. MCrypt (Cookie password storage)

#### Recommended ####
1. Imagick (for PDF image viewing and others)

2. GD (only if Imagick is not installed; for PNG, GIF, and JPEG conversion).

3. EXIF (image properties)

4. Fileinfo (file properties and mime types)

5. Zip Compression (directory downloads)