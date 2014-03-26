<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:output method="html" encoding="utf-8" indent="yes" />

	<xsl:template match="/variables">
		<xsl:text disable-output-escaping='yes'>&lt;!DOCTYPE html></xsl:text>
		<html>
			<head>
				<title>Phalcon PHP Framework</title>
			</head>
			<body>

				<h2>Users</h2>
				<table>
					<xsl:for-each select="allusers/user">
						<tr>
							<td>
								<xsl:value-of select="php:function('ucfirst',string(uid))" />
							</td>
						</tr>
					</xsl:for-each>
				</table>
				
			</body>
		</html>
	</xsl:template>

</xsl:stylesheet>