<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl"
	xsl:extension-element-prefixes="php" exclude-result-prefixes="php">
	
	<xsl:import href="../posts/show.xsl" />
	
	<xsl:output method="html" encoding="utf-8" indent="yes" />

	<xsl:template name="posts" match="/variables">
		<h2>This is the "posts" controller layout!</h2>

		<xsl:call-template name="show"></xsl:call-template>
	</xsl:template>

</xsl:stylesheet>