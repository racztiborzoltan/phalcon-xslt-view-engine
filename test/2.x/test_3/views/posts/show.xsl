<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl"
	xsl:extension-element-prefixes="php" exclude-result-prefixes="php">
	
	<xsl:output method="html" encoding="utf-8" indent="yes" />

	<xsl:template name="show" match="/variables">
		<h3>This is show view!</h3>

		<p>
			I have received the parameter
			<xsl:value-of select="/variables/postId" />
		</p>
	</xsl:template>

</xsl:stylesheet>