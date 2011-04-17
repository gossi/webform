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
	
	<xsl:template name="Webform">
		<xsl:param name="form"/>
		
		<form action="{$form/@target}" method="{$form/@method}">
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
		</form>
	</xsl:template>
	
	<xsl:template name="WebformArea">
		<xsl:param name="area"/>
		<xsl:param name="form"/>
		
		<fieldset class="webform-area {$area/@classes}" id="{$area/@id}">
			<xsl:if test="$area/@label != ''">
				<legend id="{$area/@id}-label" class="webform-area-label"><xsl:value-of select="$area/@label"/></legend>
			</xsl:if>
			<xsl:if test="$area/@description != ''">
				<div class="webform-description"><xsl:value-of select="$area/@description"/></div>
			</xsl:if>
			
			<xsl:for-each select="$area/control">
				<xsl:call-template name="WebformControl">
					<xsl:with-param name="control" select="."/>
					<xsl:with-param name="form" select="$form"/>
				</xsl:call-template>
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
			<xsl:when test="$control/@type = 'SingleLine'">
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

					<input type="text" value="{$control/@value}" name="{$control/@name}" id="{$control/@id}" class="webform-control {$control/@classes}">
						<xsl:if test="$control/@disabled = 'yes'">
							<xsl:attribute name="disabled">disabled</xsl:attribute>
						</xsl:if>
						<xsl:if test="$control/@readonly = 'yes'">
							<xsl:attribute name="readonly">readonly</xsl:attribute>
						</xsl:if>
						<xsl:if test="$control/validator[@type = 'Length'] and $control/validator[@max]">
							<xsl:attribute name="maxlength"><xsl:value-of select="$control/validator/@max"/></xsl:attribute>
						</xsl:if>
					</input>
					
					<xsl:if test="$control/@description != '' and $descEnd">
						<span class="webform-description"><xsl:value-of select="$control/@description" disable-output-escaping="yes"/></span>
					</xsl:if>
				</div>
			</xsl:when>

			<xsl:when test="$control/@type = 'Password'">
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

					<input type="password" value="{$control/@value}" name="{$control/@name}" id="{$control/@id}" class="webform-control {$control/@classes}">
						<xsl:if test="$control/@disabled = 'yes'">
							<xsl:attribute name="disabled">disabled</xsl:attribute>
						</xsl:if>
						<xsl:if test="$control/validator[@type = 'Length'] and $control/validator[@max]">
							<xsl:attribute name="maxlength"><xsl:value-of select="$control/validator[@max]"/></xsl:attribute>
						</xsl:if>
					</input>
					
					<xsl:if test="$control/@description != '' and $descEnd">
						<span class="webform-description"><xsl:value-of select="$control/@description" disable-output-escaping="yes"/></span>
					</xsl:if>
				</div>
			</xsl:when>
			
			<xsl:when test="$control/@type = 'Hidden'">
				<input type="hidden" value="{$control/@value}" name="{$control/@name}" id="{$control/@id}" class="{$control/@classes}"/>
			</xsl:when>
			
			<xsl:when test="$control/@type = 'Radio'">
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
					
					<input type="radio" value="{$control/@value}" name="{$control/@name}" id="{$control/@id}" class="webform-control {$control/@classes}">
						<xsl:if test="$control/@checked = 'yes'">
							<xsl:attribute name="checked">checked</xsl:attribute>
						</xsl:if>
						<xsl:if test="$control/@disabled = 'yes'">
							<xsl:attribute name="disabled">disabled</xsl:attribute>
						</xsl:if>
					</input>
					
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
			
			<xsl:when test="$control/@type = 'CheckBox'">
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
					
					<input type="checkbox" value="{$control/@value}" name="{$control/@name}" id="{$control/@id}" class="webform-control {$control/@classes}">
						<xsl:if test="$control/@checked = 'yes'">
							<xsl:attribute name="checked">checked</xsl:attribute>
						</xsl:if>
						<xsl:if test="$control/@disabled = 'yes'">
							<xsl:attribute name="disabled">disabled</xsl:attribute>
						</xsl:if>
					</input>
					
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

			<xsl:when test="$control/@type = 'MultiLine'">
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

					<textarea value="{$control/@value}" name="{$control/@name}" id="{$control/@id}" class="webform-control {$control/@classes}">
						<xsl:if test="$control/@disabled = 'yes'">
							<xsl:attribute name="disabled">disabled</xsl:attribute>
						</xsl:if>
						<xsl:value-of select="$control/@default"/>
						<xsl:comment></xsl:comment>
					</textarea>
					
					<xsl:if test="$control/@description != '' and $descEnd">
						<span class="webform-description"><xsl:value-of select="$control/@description" disable-output-escaping="yes"/></span>
					</xsl:if>
				</div>
			</xsl:when>

			<xsl:when test="$control/@type = 'Group'">
				<div class="webform-control-box webform-group-{$control/@direction} {$control/@classes}" id="{$control/@id}-control">
					<xsl:if test="$control/@label != ''">
						<label class="webform-label" id="{$control/@id}-label">
							<xsl:value-of select="$control/@label" disable-output-escaping="yes"/>
							
							<xsl:if test="$control/@description != '' and $descLabel">
								<span class="webform-description"><xsl:value-of select="$control/@description" disable-output-escaping="yes"/></span>
							</xsl:if>
						</label>
					</xsl:if>
					
					<xsl:if test="$control/@description != '' and $descBetween">
						<span class="webform-description"><xsl:value-of select="$control/@description" disable-output-escaping="yes"/></span>
					</xsl:if>
					
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
					
					<xsl:if test="$control/@description != '' and $descEnd">
						<span class="webform-description"><xsl:value-of select="$control/@description" disable-output-escaping="yes"/></span>
					</xsl:if>
				</div>
			</xsl:when>
			
			<xsl:when test="$control/@type = 'ComboBox'">
				<div class="webform-control-box {$control/@classes}" id="{$control/@id}-control">
					<xsl:if test="$control/@direction">
						<xsl:attribute name="class">webform-group-<xsl:value-of select="$control/@direction"/></xsl:attribute>
					</xsl:if>
					
					<xsl:if test="$control/@label != ''">
						<label class="webform-label" id="{$control/@id}-label">
							<xsl:value-of select="$control/@label" disable-output-escaping="yes"/>
						</label>
					</xsl:if>
					
					<xsl:if test="$control/@description != '' and $descBetween">
						<span class="webform-description"><xsl:value-of select="$control/@description" disable-output-escaping="yes"/></span>
					</xsl:if>
					<select name="{$control/@name}" id="{$control/@id}" class="webform-control {$control/@classes}">
						<xsl:if test="$control/@disabled = 'yes'">
							<xsl:attribute name="disabled">disabled</xsl:attribute>
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
					
					<xsl:if test="$control/@description != '' and $descEnd">
						<span class="webform-description"><xsl:value-of select="$control/@description" disable-output-escaping="yes"/></span>
					</xsl:if>
				</div>
			</xsl:when>

			<xsl:when test="$control/@type = 'Submit'">
				<input type="submit" value="{$control/@label}" name="{$control/@name}" id="{$control/@id}" class="webform-control {$control/@classes}">
					<xsl:if test="$control/@disabled = 'yes'">
						<xsl:attribute name="disabled">disabled</xsl:attribute>
					</xsl:if>
					<xsl:if test="$control/validator[@type = 'Length'] and $control/validator[@max]">
						<xsl:attribute name="maxlength"><xsl:value-of select="$control/validator[@max]"/></xsl:attribute>
					</xsl:if>
				</input>
			</xsl:when>

			<xsl:when test="$control/@type = 'Reset'">
				<input type="reset" value="{$control/@label}" name="{$control/@name}" id="{$control/@id}" class="webform-control {$control/@classes}">
					<xsl:if test="$control/@disabled = 'yes'">
						<xsl:attribute name="disabled">disabled</xsl:attribute>
					</xsl:if>
					<xsl:if test="$control/validator[@type = 'Length'] and $control/validator[@max]">
						<xsl:attribute name="maxlength"><xsl:value-of select="$control/validator[@max]"/></xsl:attribute>
					</xsl:if>
				</input>
			</xsl:when>
		</xsl:choose>

	</xsl:template>
</xsl:stylesheet>
