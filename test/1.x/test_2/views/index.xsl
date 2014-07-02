<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl" xsl:extension-element-prefixes="php" exclude-result-prefixes="php">
	<xsl:output method="html" encoding="utf-8" indent="yes" />

	<xsl:template match="/variables">
		<xsl:text disable-output-escaping='yes'>&lt;!DOCTYPE html></xsl:text>
		<html>
		    <head>
		        <title>Example</title>
		    </head>
		    <body>
		
		        <h1>This is main layout!</h1>
		
				<xsl:value-of select="/variables/_getContent" disable-output-escaping="yes" />
		    </body>
		</html>
	</xsl:template>

</xsl:stylesheet>