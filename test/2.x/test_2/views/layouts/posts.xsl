<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl"
	xsl:extension-element-prefixes="php" exclude-result-prefixes="php">
	<xsl:output method="html" encoding="utf-8" indent="yes" />

	<xsl:template match="/variables">
		<h2>This is the "posts" controller layout!</h2>
		<xsl:value-of select="/variables/_getContent"
			disable-output-escaping="yes" />
	</xsl:template>

</xsl:stylesheet>