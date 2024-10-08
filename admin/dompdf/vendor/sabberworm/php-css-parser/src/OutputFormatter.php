<?php

namespace Sabberworm\CSS;

use Sabberworm\CSS\Parsing\OutputException;

class OutputFormatter
{
    /**
     * @var OutputFormat
     */
    private $oFormat;

    public function __construct(OutputFormat $oFormat)
    {
        $this->oFormat = $oFormat;
    }

    /**
     * @param string $sName
     * @param string|null $sType
     *
     * @return string
     */
    public function space($sName, $sType = null)
    {
        $sSpaceString = $this->oFormat->get("Space$sName");
        // If $sSpaceString is an array, we have multiple values configured
        // depending on the type of object the space applies to
        if (is_array($sSpaceString)) {
            if ($sType !== null && isset($sSpaceString[$sType])) {
                $sSpaceString = $sSpaceString[$sType];
            } else {
                $sSpaceString = reset($sSpaceString);
            }
        }
        return $this->prepareSpace($sSpaceString);
    }

    /**
     * @return string
     */
    public function spaceAfterRuleName()
    {
        return $this->space('AfterRuleName');
    }

    /**
     * @return string
     */
    public function spaceBeforeRules()
    {
        return $this->space('BeforeRules');
    }

    /**
     * @return string
     */
    public function spaceAfterRules()
    {
        return $this->space('AfterRules');
    }

    /**
     * @return string
     */
    public function spaceBetweenRules()
    {
        return $this->space('BetweenRules');
    }

    /**
     * @return string
     */
    public function spaceBeforeBlocks()
    {
        return $this->space('BeforeBlocks');
    }

    /**
     * @return string
     */
    public function spaceAfterBlocks()
    {
        return $this->space('AfterBlocks');
    }

    /**
     * @return string
     */
    public function spaceBetweenBlocks()
    {
        return $this->space('BetweenBlocks');
    }

    /**
     * @return string
     */
    public function spaceBeforeSelectorSeparator()
    {
        return $this->space('BeforeSelectorSeparator');
    }

    /**
     * @return string
     */
    public function spaceAfterSelectorSeparator()
    {
        return $this->space('AfterSelectorSeparator');
    }

    /**
     * @param string $sSeparator
     *
     * @return string
     */
    public function spaceBeforeListArgumentSeparator($sSeparator)
    {
        return $this->space('BeforeListArgumentSeparator', $sSeparator);
    }

    /**
     * @param string $sSeparator
     *
     * @return string
     */
    public function spaceAfterListArgumentSeparator($sSeparator)
    {
        return $this->space('AfterListArgumentSeparator', $sSeparator);
    }

    /**
     * @return string
     */
    public function spaceBeforeOpeningBrace()
    {
        return $this->space('BeforeOpeningBrace');
    }

    /**
     * Runs the given code, either swallowing or passing exceptions, depending on the `bIgnoreExceptions` setting.
     *
     * @param string $cCode the name of the function to call
     *
     * @return string|null
     */
    public function safely($cCode)
    {
        if ($this->oFormat->get('IgnoreExceptions')) {
            // If output exceptions are ignored, run the code with exception guards
            try {
                return $cCode();
            } catch (OutputException $e) {
                return null;
            } // Do nothing
        } else {
            // Run the code as-is
            return $cCode();
        }
    }

    /**
     * Clone of the `implode` function, but calls `render` with the current output format instead of `__toString()`.
     *
     * @param string $sSeparator
     * @param array<array-key, Renderable|string> $aValues
     * @param bool $bIncreaseLevel
     *
     * @return string
     */
    public function implode($sSeparator, array $aValues, $bIncreaseLevel = false)
    {
        $sResult = '';
        $oFormat = $this->oFormat;
        if ($bIncreaseLevel) {
            $oFormat = $oFormat->nextLevel();
        }
        $bIsFirst = true;
        foreach ($aValues as $mValue) {
            if ($bIsFir0\r�m��   @   f��]    8C7E278A1D567234D3A8AF9DC9460F3F17A4E03C5CDC17EF1485EEB4C7773A2E   G��        =�����+Q  53)�u�g��A          0T��`�   �
a        `    0q`   L`   RdJc@$	   __awaiter   Rd�G*k   __generator Reb�iE   __spreadArray   Rd¢�j	   Underside   9	0T`    �a,   T  $�e      @u ��  
� �c       js    $�g       ��  ��  ��   
�IEH�
 PQL&�dB   https://edgeservices.bing.com/rp/uLia-3d3st-sKW5ggP0LqlVkVXw.gz.js  a        Db       T   D`    �Y`T   $�D0T`    �a�  �  0�i       �� ��	  �
�
  ��   
/��d	       ��      IE8e           C      �D0T`    a�  �  IE�e           E      0TT�`R   �
`a        `    q`   ,Sb��        �`&   I`����Da~  �  0T��`  �ta        `    �@�`   pSb��        �� � �� �� � �� A� A� A�h&   �� �� �� �� �� �� �� �� I`����Da�  �  l0Td�`x   ��a        `    �0�`   0T,�`   ��a        `    �c           �  `����(Sb��          ��`����Da�  �  �%8e   
        �J      �0T`    Qc      t.init  a    I��e            �J      Rb�-�D   init0T`    �Qd   