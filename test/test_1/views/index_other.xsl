<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
	<xsl:output method="html" encoding="utf-8" indent="yes" />

	<xsl:template match="/variables">
		<xsl:text disable-output-escaping='yes'>&lt;!DOCTYPE html></xsl:text>
		<html>
			<head>
				<title>Phalcon PHP Framework</title>
				<style type="text/css">
					table{
						border: 1px solid black;
						border-collapse: collapse;
					}
					table td{
						border: 1px solid black;
						padding: 10px;
					}
				</style>
			</head>
			<body>

				<h2>Users</h2>
				<table>
					<tr>
						<xsl:for-each select="allusers/user">
							<td>
								<xsl:value-of select="php:function('ucfirst',string(uid))" />
							</td>
						</xsl:for-each>
					</tr>
				</table>
				
			</body>
		</html>
	</xsl:template>

</xsl:stylesheet>