<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
	version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns="http://www.w3.org/1999/xhtml">

	<!-- <xsl:template match="/">
		<xsl:call-template name="Webform">
			<xsl:with-param name="form" select="webform"/>
		</xsl:call-template>
	</xsl:template>-->
	
	<xsl:variable name="lcase" select="'abcdefghijklmnopqrstuvwxyz'" />
	<xsl:variable name="ucase" select="'ABCDEFGHIJKLMNOPQRSTUVWXYZ'" />
	
	<xsl:template name="Webform">
		<xsl:param name="form"/>
		
		<form action="{$form/@target}" method="{$form/@method}" id="{$form/@id}" class="webform {$form/@classes}">
			<xsl:if test="$form/@id">
				<xsl:attribute name="id"><xsl:value-of select="$form/@id"/></xsl:attribute>
			</xsl:if>
			<xsl:if test="$form/errors">
				<div class="webform-errors">
					<xsl:value-of select="$form/errors/@occur"/>:
					<ul>
						<xsl:for-each select="$form/errors/*">
							<li><xsl:value-of select="."/></li>
						</xsl:for-each>
					</ul>
				</div>
			</xsl:if>
			<xsl:for-each select="$form/*">
				<xsl:choose>
					<xsl:when test="local-name(.) = 'area'">
						<xsl:call-template name="WebformArea">
							<xsl:with-param name="area" select="."/>
							<xsl:with-param name="form" select="$form"/>
						</xsl:call-template>
					</xsl:when>
					<xsl:when test="local-name(.) = 'control'">
						<xsl:call-template name="WebformControl">
							<xsl:with-param name="control" select="."/>
							<xsl:with-param name="form" select="$form"/>
						</xsl:call-template>
					</xsl:when>
				</xsl:choose>
			</xsl:for-each>
			
			<xsl:if test="count($form/test[@type='MatchTest']) &gt; 0">
				<script>
					var webform = {
						testMatches : function (message, controls) {
							var ok = true;
							if (controls.length) {
								var val = $(controls[0]).val();
								
								controls.forEach(function (id) {
									control = $(id);
									if (val !== control.val()) {
										ok = false;
										control.setCustomValidity(message);
									}
								});
							}
							return ok;
						}
					};
					var form = $("#<xsl:value-of select="$form/@id"/>");
					<xsl:for-each select="$form/test[@type='MatchTest']">
						<xsl:text>form.submit(function (e){return webform.testMatches("</xsl:text>
						<xsl:value-of select="@message"></xsl:value-of>
						<xsl:text>",[</xsl:text>
						<xsl:for-each select="control">
							<xsl:text>"#</xsl:text>
							<xsl:value-of select="@id"/>
							<xsl:text>"</xsl:text>
							<xsl:if test="position() != last()"><xsl:text>,</xsl:text></xsl:if>
						</xsl:for-each>
						<xsl:text>]);});</xsl:text>
					</xsl:for-each>
					//form.submit(function (e) {return false;});
				</script>
			</xsl:if>
		</form>
	</xsl:template>
	
	<xsl:template name="WebformArea">
		<xsl:param name="area"/>
		<xsl:param name="form"/>
		
		<fieldset class="webform-area {$area/@classes}" id="{$area/@id}">
			<xsl:if test="$area/@width != ''">
				<xsl:attribute name="style">width: <xsl:value-of select="$area/@width"/>%;</xsl:attribute>
			</xsl:if>
			<xsl:if test="$area/@label != ''">
				<legend id="{$area/@id}-label" class="webform-area-label"><xsl:value-of select="$area/@label"/></legend>
			</xsl:if>
			<xsl:if test="$area/@description != ''">
				<div class="webform-description"><xsl:value-of select="$area/@description"/></div>
			</xsl:if>
			
			<xsl:for-each select="$area/*">
				<xsl:choose>
					<xsl:when test="local-name(.) = 'area'">
						<xsl:call-template name="WebformArea">
							<xsl:with-param name="area" select="."/>
							<xsl:with-param name="form" select="$form"/>
						</xsl:call-template>
					</xsl:when>
					<xsl:when test="local-name(.) = 'control'">
						<xsl:call-template name="WebformControl">
							<xsl:with-param name="control" select="."/>
							<xsl:with-param name="form" select="$form"/>
						</xsl:call-template>
					</xsl:when>
				</xsl:choose>
			</xsl:for-each>
		</fieldset>

	</xsl:template>

	<xsl:template name="WebformControl">
		<xsl:param name="control"/>
		<xsl:param name="form"/>
		
		<xsl:variable name="descLabel" select="$form/@description-position = 'desc-label'"/>
		<xsl:variable name="descBetween" select="$form/@description-position = 'desc-between'"/>
		<xsl:variable name="descEnd" select="$form/@description-position = 'desc-end'"/>

		<xsl:choose>
			<xsl:when test="$control/@type = 'Hidden'">
				<input type="hidden" value="{$control/@value}" name="{$control/@name}" id="{$control/@id}" class="{$control/@classes}"/>
			</xsl:when>
			
			<xsl:when test="$control/@type = 'CheckBox' or $control/@type = 'Radio'">
				<div class="webform-control-box {$control/@classes}" id="{$control/@id}-control">
					<xsl:if test="$control/@orientation = 'left'">
						<xsl:if test="$control/@label != ''">
							<label class="webform-label" id="{$control/@id}-label" for="{$control/@id}">
								<xsl:value-of select="$control/@label" disable-output-escaping="yes"/>
								
								<xsl:if test="$control/@description != '' and $descLabel">
									<span class="webform-description"><xsl:value-of select="$control/@description" disable-output-escaping="yes"/></span>
								</xsl:if>
							</label>
						</xsl:if>
						<xsl:if test="$control/@description != '' and ($descBetween or $descEnd)">
							<span class="webform-description"><xsl:value-of select="$control/@description" disable-output-escaping="yes"/></span>
						</xsl:if>
					</xsl:if>
					
					<input type="{translate($control/@type, $ucase, $lcase)}" value="{$control/@value}" name="{$control/@name}" id="{$control/@id}" class="webform-control {$control/@classes}">
						<xsl:if test="$control/@checked = 'yes'">
							<xsl:attribute name="checked"/>
						</xsl:if>
						<xsl:if test="$control/@disabled = 'yes'">
							<xsl:attribute name="disabled"/>
						</xsl:if>
					</input>
					
					<xsl:if test="count($control/error) &gt; 0">
						<ul class="webform-errors">
							<xsl:for-each select="$control/error">
								<li><xsl:value-of select="."/></li>
							</xsl:for-each>
						</ul>
					</xsl:if>
					
					<xsl:if test="$control/@orientation = 'right'">
						<xsl:if test="$control/@label != ''">
							<label class="webform-label" id="{$control/@id}-label" for="{$control/@id}">
								<xsl:value-of select="$control/@label" disable-output-escaping="yes"/>
								
								<xsl:if test="$control/@description != '' and $descLabel">
									<span class="webform-description"><xsl:value-of select="$control/@description" disable-output-escaping="yes"/></span>
								</xsl:if>
							</label>
						</xsl:if>
						<xsl:if test="$control/@description != '' and ($descBetween or $descEnd)">
							<span class="webform-description"><xsl:value-of select="$control/@description" disable-output-escaping="yes"/></span>
						</xsl:if>
					</xsl:if>
				</div>
			</xsl:when>

			<xsl:when test="$control/@type = 'Submit' or $control/@type = 'Reset'">
				<input type="{translate($control/@type, $ucase, $lcase)}" value="{$control/@label}" name="{$control/@name}" id="{$control/@id}" class="{$control/@classes} webform-control">
					<xsl:if test="$control/@disabled = 'yes'">
						<xsl:attribute name="disabled">disabled</xsl:attribute>
					</xsl:if>
				</input>
			</xsl:when>
			
			<xsl:otherwise>
				<div class="webform-control-box {$control/@classes}" id="{$control/@id}-control">
					<xsl:if test="$control/@label != ''">
						<label class="webform-label" id="{$control/@id}-label" for="{$control/@id}">
							<xsl:value-of select="$control/@label" disable-output-escaping="yes"/>
							
							<xsl:if test="$control/@description != '' and $descLabel">
								<span class="webform-description"><xsl:value-of select="$control/@description" disable-output-escaping="yes"/></span>
							</xsl:if>
						</label>
					</xsl:if>
					<xsl:if test="$control/@description != '' and $descBetween">
						<span class="webform-description"><xsl:value-of select="$control/@description" disable-output-escaping="yes"/></span>
					</xsl:if>

					<xsl:call-template name="WebformControlContent">
						<xsl:with-param name="control" select="$control"/>
						<xsl:with-param name="form" select="$form"/>
					</xsl:call-template>
					
					<xsl:if test="count($control/error) &gt; 0">
						<ul class="webform-errors">
							<xsl:for-each select="$control/error">
								<li><xsl:value-of select="."/></li>
							</xsl:for-each>
						</ul>
					</xsl:if>
					
					<xsl:if test="$control/@description != '' and $descEnd">
						<span class="webform-description"><xsl:value-of select="$control/@description" disable-output-escaping="yes"/></span>
					</xsl:if>
				</div>
			</xsl:otherwise>
		</xsl:choose>

	</xsl:template>
	
	<xsl:template name="WebformControlContent">
		<xsl:param name="control"/>
		<xsl:param name="form"/>
		
		<xsl:choose>
			<xsl:when test="$control/@type = 'ComboBox'">
				<select name="{$control/@name}" id="{$control/@id}" class="webform-control {$control/@classes}">
					<xsl:if test="$control/@disabled = 'yes'">
						<xsl:attribute name="disabled">disabled</xsl:attribute>
					</xsl:if>
					<xsl:if test="$control/@required = 'yes'">
						<xsl:attribute name="required"/>
					</xsl:if>
					<xsl:if test="$control/@readonly = 'yes'">
						<xsl:attribute name="readonly"/>
					</xsl:if>
					<xsl:if test="$control/@dirname != ''">
						<xsl:attribute name="dirname"><xsl:value-of select="$control/@dirname"/></xsl:attribute>
					</xsl:if>
					<xsl:if test="$control/@title">
						<xsl:attribute name="title"><xsl:value-of select="$control/@title"/></xsl:attribute>
					</xsl:if>
					<xsl:for-each select="$control/option">
						<option value="{@value}" class="{@classes}" id="{@id}">
							<xsl:if test="@checked = 'yes'">						
								<xsl:attribute name="selected">selected</xsl:attribute>
							</xsl:if>
							<xsl:value-of select="@label"/>
						</option>
					</xsl:for-each>
				</select>
			</xsl:when>
			
			<xsl:when test="$control/@type = 'Group'">
				<ul class="webform-control-content">
					<xsl:for-each select="$control/control">
						<li>
							<xsl:call-template name="WebformControl">
								<xsl:with-param name="control" select="."/>
								<xsl:with-param name="form" select="$form"/>
							</xsl:call-template>
						</li>
					</xsl:for-each>
				</ul>
			</xsl:when>
			
			<xsl:when test="$control/@type = 'MultiLine'">
				<textarea name="{$control/@name}" id="{$control/@id}" class="webform-control {$control/@classes}">
					<xsl:if test="$control/@disabled = 'yes'">
						<xsl:attribute name="disabled"/>
					</xsl:if>
					<xsl:if test="$control/@required = 'yes'">
						<xsl:attribute name="required"/>
					</xsl:if>
					<xsl:if test="$control/@readonly = 'yes'">
						<xsl:attribute name="readonly"/>
					</xsl:if>
					<xsl:if test="$control/@dirname != ''">
						<xsl:attribute name="dirname"><xsl:value-of select="$control/@dirname"/></xsl:attribute>
					</xsl:if>
					<xsl:if test="$control/@title">
						<xsl:attribute name="title"><xsl:value-of select="$control/@title"/></xsl:attribute>
					</xsl:if>
					<xsl:if test="$control/@maxlength">
						<xsl:attribute name="maxlength"><xsl:value-of select="$control/@maxlength"/></xsl:attribute>
					</xsl:if>
					<xsl:value-of select="$control/@value"/>
					<xsl:comment></xsl:comment>
				</textarea>
			</xsl:when>
			
			<xsl:otherwise>
				<xsl:variable name="type">
					<xsl:choose>
						<xsl:when test="$control/@type = 'SingleLine'">text</xsl:when>
						<xsl:otherwise>
							<xsl:value-of select="translate($control/@type, $ucase, $lcase)"/>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:variable>

				<input type="{$type}" value="{$control/@value}" name="{$control/@name}" id="{$control/@id}" class="webform-control {$control/@classes}">
					<xsl:if test="$control/@disabled = 'yes'">
						<xsl:attribute name="disabled"/>
					</xsl:if>
					<xsl:if test="$control/@required = 'yes'">
						<xsl:attribute name="required"/>
					</xsl:if>
					<xsl:if test="$control/@readonly = 'yes'">
						<xsl:attribute name="readonly"/>
					</xsl:if>
					<xsl:if test="$control/@multiple = 'yes'">
						<xsl:attribute name="multiple"/>
					</xsl:if>
					<xsl:if test="$control/@dirname != ''">
						<xsl:attribute name="dirname"><xsl:value-of select="$control/@dirname"/></xsl:attribute>
					</xsl:if>
					<xsl:if test="$control/@title != ''">
						<xsl:attribute name="title"><xsl:value-of select="$control/@title"/></xsl:attribute>
					</xsl:if>
					<xsl:if test="$control/@autocomplete != ''">
						<xsl:attribute name="autocomplete"><xsl:value-of select="$control/@autocomplete"/></xsl:attribute>
					</xsl:if>
					<xsl:if test="$control/@maxlength != ''">
						<xsl:attribute name="maxlength"><xsl:value-of select="$control/@maxlength"/></xsl:attribute>
					</xsl:if>
					<xsl:if test="$control/@max != ''">
						<xsl:attribute name="max"><xsl:value-of select="$control/@max"/></xsl:attribute>
					</xsl:if>
					<xsl:if test="$control/@min != ''">
						<xsl:attribute name="min"><xsl:value-of select="$control/@min"/></xsl:attribute>
					</xsl:if>
					<xsl:if test="$control/@step != ''">
						<xsl:attribute name="step"><xsl:value-of select="$control/@step"/></xsl:attribute>
					</xsl:if>
					<xsl:if test="$control/@pattern != ''">
						<xsl:attribute name="pattern"><xsl:value-of select="$control/@pattern"/></xsl:attribute>
					</xsl:if>
					<xsl:if test="$control/@placeholder != ''">
						<xsl:attribute name="placeholder"><xsl:value-of select="$control/@placeholder"/></xsl:attribute>
					</xsl:if>
					<xsl:if test="$control/suggestions">
						<xsl:attribute name="list"><xsl:value-of select="$control/@id"/>-suggestions</xsl:attribute>
					</xsl:if>
				</input>
				
				<xsl:if test="$control/suggestions">
					<datalist id="{$control/@id}-suggestions">
						<xsl:copy-of select="$control/suggestions/*" disable-output-escaping="yes"/>
					</datalist>
				</xsl:if>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
</xsl:stylesheet>
