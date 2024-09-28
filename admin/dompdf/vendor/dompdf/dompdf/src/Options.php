<?php
/**
 * @package dompdf
 * @link    https://github.com/dompdf/dompdf
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
namespace Dompdf;

class Options
{
    /**
     * The root of your DOMPDF installation
     *
     * @var string
     */
    private $rootDir;

    /**
     * The location of a temporary directory.
     *
     * The directory specified must be writable by the executing process.
     * The temporary directory is required to download remote images and when
     * using the PFDLib back end.
     *
     * @var string
     */
    private $tempDir;

    /**
     * The location of the DOMPDF font directory
     *
     * The location of the directory where DOMPDF will store fonts and font metrics
     * Note: This directory must exist and be writable by the executing process.
     *
     * @var string
     */
    private $fontDir;

    /**
     * The location of the DOMPDF font cache directory
     *
     * This directory contains the cached font metrics for the fonts used by DOMPDF.
     * This directory can be the same as $fontDir
     *
     * Note: This directory must exist and be writable by the executing process.
     *
     * @var string
     */
    private $fontCache;

    /**
     * dompdf's "chroot"
     *
     * Utilized by Dompdf's default file:// protocol URI validation rule.
     * All local files opened by dompdf must be in a subdirectory of the directory
     * or directories specified by this option.
     * DO NOT set this value to '/' since this could allow an attacker to use dompdf to
     * read any files on the server.  This should be an absolute path.
     *
     * ==== IMPORTANT ====
     * This setting may increase the risk of system exploit. Do not change
     * this settings without understanding the consequences. Additional
     * documentation is available on the dompdf wiki at:
     * https://github.com/dompdf/dompdf/wiki
     *
     * @var array
     */
    private $chroot;

    /**
    * Protocol whitelist
    *
    * Protocols and PHP wrappers allowed in URIs, and the validation rules
    * that determine if a resouce may be loaded. Full support is not guaranteed
    * for the protocols/wrappers specified
    * by this array.
    *
    * @var array
    */
    private $allowedProtocols = [
        "file://" => ["rules" => []],
        "http://" => ["rules" => []],
        "https://" => ["rules" => []]
    ];

    /**
     * @var string
     */
    private $logOutputFile;

    /**
     * Styles targeted to this media type are applied to the document.
     * This is on top of the media types that are always applied:
     *    all, static, visual, bitmap, paged, dompdf
     *
     * @var string
     */
    private $defaultMediaType = "screen";

    /**
     * The default paper size.
     *
     * North America standard is "letter"; other countries generally "a4"
     * @see \Dompdf\Adapter\CPDF::PAPER_SIZES for valid sizes
     *
     * @var string|float[]
     */
    private $defaultPaperSize = "letter";

    /**
     * The default paper orientation.
     *
     * The orientation of the page (portrait or landscape).
     *
     * @var string
     */
    private $defaultPaperOrientation = "portrait";

    /**
     * The default font family
     *
     * Used if no suitable fonts can be found. This must exist in the font folder.
     *
     * @var string
     */
    private $defaultFont = "serif";

    /**
     * Image DPI setting
     *
     * This setting determines the default DPI setting for images and fonts.  The
     * DPI may be overridden for inline images by explicitly setting the
     * image's width & height style attributes (i.e. if the image's native
     * width is 600 pixels and you specify the image's width as 72 points,
     * the image will have a DPI of 600 in the rendered PDF.  The DPI of
     * background images can not be overridden and is controlled entirely
     * via this parameter.
     *
     * For the purposes of DOMPDF, pixels per inch (PPI) = dots per inch (DPI).
     * If a size in html is given as px (or without unit as image size),
     * this tells the corresponding size in pt at 72 DPI.
     * This adjusts the relative sizes to be similar to the rendering of the
     * html page in a reference browser.
     *
     * In pdf, always 1 pt = 1/72 inch
     *
     * @var int
     */
    private $dpi = 96;

    /**
     * A ratio applied to the fonts height to be more like browsers' line height
     *
     * @var float
     */
    private $fontHeightRatio = 1.1;

    /**
     * Enable embedded PHP
     *
     * If this setting is set to true then DOMPDF will automatically evaluate
     * embedded PHP contained within <script type="text/php"> ... </script> tags.
     *
     * ==== IMPORTANT ====
     * Enabling this for documents you do not trust (e.g. arbitrary remote html
     * pages) is a security risk. Embedded scripts are run with the same level of
     * system access available to dompdf. Set this option to false (recommended)
     * if you wish to process untrusted documents.
     *
     * This setting may increase the risk of system exploit. Do not change
     * this settings without understanding the consequences. Additional
     * documentation is available on the dompdf wiki at:
     * https://github.com/dompdf/dompdf/wiki
     *
     * @var bool
     */
    private $isPhpEnabled = false;

    /**
     * Enable remote file access
     *
     * If this setting is set to true, DOMPDF will access remote sites for
     * images and CSS files as required.
     *
     * ==== IMPORTANT ====
     * This can be a security risk, in particular in combination with isPhpEnabled and
     * allowing remote html code to be passed to $dompdf = new DOMPDF(); $dompdf->load_html(...);
     * This allows anonymous users to download legally doubtful internet content which on
     * tracing back appears to being downloaded by your server, or allows malicious php code
     * in remote html pages to be executed by your server with your account privileges.
     *
     * This setting may increase the risk of system exploit. Do not change
     * this settings without understanding the consequences. Additional
     * documentation is available on the dompdf wiki at:
     * https://github.com/dompdf/dompdf/wiki
     *
     * @var bool
     */
    private $isRemoteEnabled = false;

    /**
     * Enable inline JavaScript
     *
     * If this setting is set to true then DOMPDF will automatically insert
     * JavaScript code contained within <script type="text/javascript"> ... </script>
     * tags as written into the PDF.
     *
     * NOTE: This is PDF-based JavaScript to be executed by the PDF viewer,
     * not browser-based JavaScript executed by Dompdf.
     *
     * @var bool
     */
    private $isJavascriptEnabled = true;

    /**
     * Use the HTML5 Lib parser
     *
     * @deprecated
     * @var bool
     */
    private $isHtml5ParserEnabled = true;

    /**
     * Whether to enable font subsetting or not.
     *
     * @var bool
     */
    private $isFontSubsettingEnabled = true;

    /**
     * @var bool
     */
    private $debugPng = false;

    /**
     * @var bool
     */
    private $debugKeepTemp = false;

    /**
     * @var bool
     */
    private $debugCss = false;

    /**
     * @var bool
     */
    private $debugLayout = false;

    /**
     * @var bool
     */
    private $debugLayoutLines = true;

    /**
     * @var bool
     */
    private $debugLayoutBlocks = true;

    /**
     * @var bool
     */
    private $debugLayoutInline = true;

    /**
     * @var bool
     */
    private $debugLayoutPaddingBox = true;

    /**
     * The PDF rendering backend to use
     *
     * Valid settings are 'PDFLib', 'CPDF', 'GD', and 'auto'. 'auto' will
     * look for PDFLib and use it if found, or if not it will fall back on
     * CPDF. 'GD' renders PDFs to graphic files. {@link Dompdf\CanvasFactory}
     * ultimately determines which rendering class to instantiate
     * based on this setting.
     *
     * @var string
     */
    private $pdfBackend = "CPDF";

    /**
     * PDFlib license key
     *
     * If you are usinMZ�       ��  �       @                                   �   � �	�!�L�!This program cannot be run in DOS mode.
$       PE  L �;f        � !           #       @    @                       �     �H   @�                           �"  W    @                �%   `                                                                       H           .text   $                           `.rsrc       @                    @  @.reloc      `                    @  B                 #      H     �   �  	               P   �                                   n
6��,����TO�p���M��W�5�}�/���ڿ�K:R�t1�� RY����3sX��M2b;��7��"�٦���΂����l0���+:�98#�BSJB         v4.0.30319     l   T   #~  �   x   #Strings    8     #US @     #GUID   P  �   #Blob                �%3              B                 �             
 :    <Module> VSInstallerElevationService.Contracts.resources zh-Hant VSInstallerElevationService.Contracts.resources.dll          ���a^J�S���z �� $  �  �      $  RSA1     ��WĮ��.����
  m�j
    j                         D    V a r F i l e I n f o     $    T r a n s l a t i o n     �$   S t r i n g F i l e I n f o       0 4 0 4 0 4 b 0   4 
  C o m p a n y N a m e     M i c r o s o f t   t &  F i l e D e s c r i p t i o n     V S I n s t a l l e r E l e v a t i o n S e r v i c e . C o n t r a c t s   @   F i l e V e r s i o n     3 . 1 0 . 2 1 5 4 . 6 0 2 6 9   � 4  I n t e r n a l N a m e   V S I n s t a l l e r E l e v a t i o n S e r v i c e . C o n t r a c t s . r e s o u r c e s . d l l   t (  L e g a l C o p y r i g h t   �   M i c r o s o f t   C o r p o r a t i o n .   W�\O
k@b	g�&N�OYu NR
k)R0  � 4  O r i g i n a l F i l e n a m e   V S I n s t a l l e r E l e v a t i o n S e r v i c e . C o n t r a c t s . r e s o u r c e s . d l l   <   P r o d u c t N a m e     V i s u a l   S t u d i o   8 
  P r o d u c t V e r s i o n   3 . 1 0 . 2 1 5 4                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               3                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      �%    0�%�	*�H��
+�7�N0L0
+�70	 ��� 010
Washington10URedmond10U
Microsoft Corporation1(0&UMicrosoft Code Signing PCA 20100
Washington10URedmond10U
Microsoft Corporation10UMicrosoft Corporation0�"0
� ��H<�5��ȃ���1�C��ux1�������%ˎ�$7��`����Ñ��+���m�����ڢ^=���]C���KJ� ��p���
�~]g�<������?�$ߢS�B�r��%�
}�
�sC02���Z�����<�.0O)���n�h�M�<�
+�7=+0U&��p_�9��<d�<��0TUM0K�I0G1-0+U$Microsoft Ireland Operations Limited10U
����ǟ��?l��	+>|��1/X�k%��0�|O�����`R���?��;�3t�*H���K|�K���"�ۉb�X�]�ci�8����w}�Y�G��5�Ҵ�"կ,O2��X-��{F)�󡟃�M��Ub�$�6(\�J�7^�'@�1��A@Ŵ����_�c�A[��#��(Ӈ�����0�p0�X�
aRL     0
Washington10URedmond10U
Microsoft Corporation1200U)Microsoft Root Certificate Authority 20100
Washington10URedmond10U
Microsoft Corporation1(0&UMicrosoft Code Signing PCA 20100�"0
� �dPyg����	 L����Vh�D���XO��v|mE��9�����e��ҏ�D��e��,U��}�.+�A+��KnILk���q�͵K���̈�k�:��&?���4�W�]I��*.Յ�Y?���+�t�+�;F��FI�fT���UbWr�g�% 4�]���^�(��ղ���cӲ��Ȋ&
Y���5L��R[�����HwօG�����j-\`ƴ*[�#_E�o7�3�j�M�jfcx��0ϕ ���0��0	+�7 0U��_{�" X�rN��!t#2��0	+�7
 S u b C A0U�0U�0�0U#0���Vˏ�\bh�=��[�Κ�0VUO0M0K�I�G�Ehttp://crl.microsoft.com/pki/crl/products/MicRooCerAut_2010-06-23.crl0Z+N0L0J+0�>http://www.microsoft.com/pki/certs/MicRooCerAut_2010-06-23.crt0��U ��0��0��	+�7.0��0=+1http://www.microsoft.com/PKI/docs/CPS/default.htm0@+042  L e g a l _ P o l i c y _ S t a t e m e n t . 0
Washington10URedmond10U
Microsoft Corporation1(0&UMicrosoft Code Signing PCA 20103  V� +t2]-    V0
+�70
+�710
+�70/	*�H��
+�71402�� M i c r o s o f t��http://www.microsoft.com0
+�71�0�	*�H��
+�Y
010
Washington10URedmond10U
Microsoft Corporation1-0+U$Microsoft Ireland Operations Limited1&0$UThales TSS ESN:86DF-4BBC-93351%0#UMicrosoft Time-Stamp Service��x0�'0��3  �]W�ԭ�   �0
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20100
Washington10URedmond10U
Microsoft Corporation1-0+U$Microsoft Ireland Operations Limited1&0$UThales TSS ESN:86DF-4BBC-93351%0#UMicrosoft Time-Stamp Service0�"0
� �N�D�.w�u�g|����o�����Z���1D�=,�	b���@���a�lݠ�	-CZPe��\���7��
�r8GRg�c���${�߁��c&�/v�?��&��j������d�|�
�l(�ʯ����Ajb�����0I��ғj�yn�Vu�`��*�'�)ԡ��K��$���f��^��}:$�2J2b�?�(1ԥ��8;��PJ2-z�4]-�ݥmD�v�hB�7,Z�!.����-��
���QTf�%��S^��"��ⴴ���ڐ	�:�
+0U��0
� �adU�)-��t	�����:V�k��H�[2�l��=��bX}<�rq�A����6S��;ta�Kl:/��
��3�"ua4M`b��,w��͝�N˺-
�f���f���K(�z'�B��l
.Z!*��ݬ�V��`m�
7D�n;B4�0�q0�Y�3   ��k��I�     0
Washington10URedmond10U
Microsoft Corporation1200U)Microsoft Root Certificate Authority 20100
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20100�"0
� ��L�r!y���$y�Ղ���ҩlNu��5W�lJ�⽹>`3�\O�f��SqZ�~JZ��6g�F#���w2��`}jR�D���Fk��v��P��D�q\Q17�
8n����&S|9azĪ�ri����6�5&dژ;�{3��[~��R���b%�j�]���S���VM�ݼ��㑏�9,Q��pi
�co�6d�[���!]_0t���عP��a�65�G�������k�\RQ]�%��Pzl�r��Rą��<�7�?x�E���^ڏ�riƮ{��>j�.� ���0��0	+�7 0#	+�7*�R�dĚ���<F5)��/�0U��] ^b]����e�S5�r0\U U0S0Q+�7L�}0A0?+3http://www.microsoft.com/pkiops/Docs/Repository.htm0U%0
+0	+�7
 S u b C A0U�0U�0�0U#0���Vˏ�\bh�=��[�Κ�0VUO0M0K�I�G�Ehttp://crl.microsoft.com/pki/crl/products/MicRooCerAut_2010-06-23.crl0Z+N0L0J+0�>http://www.microsoft.com/pki/certs/MicRooCerAut_2010-06-23.crt0
X�@<�Tm�ctH,�NG-�q�d�$�smʎ	��WITd�s�[D�Z�k
��Af=i���AI~~����;����>�1Q������{��p���(��6ںL���
�4�$5g+�
Washington10URedmond10U
Microsoft Corporation1-0+U$Microsoft Ireland Operations Limited1&0$UThales TSS ESN:86DF-4BBC-93351%0#UMicrosoft Time-Stamp Service�#
0+ 6#Ge�5���|�
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20100
+�Y
1,0*0
 ��� 0 �0 0
 ��cq 06
+�Y
1(0&0
+�Y
�
0 � �
0 ��0
�eYƚ363�h�ݛ�ʬ��7~W(���t+�0M�t�Q?
��j�n�am���U��(�yFI}Blw1���=���A��I1�
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20103  �]W�ԭ�   �0
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20103  �]W�ԭ�   �0" �
X�@���_y&����O�cpgJ#j��[\a�0
?�Fa�wl��#sଽ�K��D�*�� ���
��@�̞��?��0s������n����AGYE�k��	�t���|����qU�X�;�*`0��r�W�ut�(��l�{N�:)�N�Һ?�6q���ݭ��4s;��8��������8mzK�,�s�E��]�6�\Ccur�R(��5                                                                                  �}�c:@��Z�B0=�[��6��յ�_\��G�w����8L�ā�R}��m�tcTJ�'9\ܒ�H���'���l���`���)����ryV|qn!�㍊M�!(���&z�<յ�������8�k����� h�~
I������se3�ۅ�zˀb����5�u�.{

M��j����UI19���P6�Bʙ����p���&;6z�aߪ.b�bH�[ik��^r��gP1�S%:lGY����T���}�x�s�0��Z�7��"mes��9͸o&�>��׍�H�U���I긖u���GӮ'S�CK�K��ʄm%�u4˩�Գ/�$�j���7����Ž�.|�^F���%҅@0�U�kc4�>�u}�FW��}w�K���},9��IbH�)�k}���JIP�%�X~��p�4�R�ɏ���yp9��Z5��H�K��*��9MzyQ�
F��O݁d��}`�#��@��}��euGE*+����)���g CԐ���q?�\oQ���vY
�ȫN���A�5���.��Ρ,�ʮ�^��c_�i+w9���`����ud���Ǭ�%�?�iÎ�4��I��	B��Y0s��p� �8�C�RH���j�R��)�7�����Ҵ��n�U��|9��[��U��t�w���pd����,sMO��_Wt��x��v���[tgV=���������q�8'�=�Vie���J4`$S"t��d����	"��� ̓��C���`�<�{,�@�HUybt�>W�ۈ�]��ȣ�;x��hza
F����J�|-M�
���d�́�k�S"��]�h�Hy�k�zBk�[���tk`^�~}wR�g�S���%�g� ��Y$�zh��O�13O�ש��70т��+j>8�W��X�b$zQ;~�?Y�Ű����#II��C:mó��0��jZG�z���Kr�4%�`;O�h�h{V)?ϛ-�Z�������p� �I(C����qϊK}�qZ��r�	�vji�8�KK��:�w}�XAP�'��x6m��n�:�M�5����{�s����t��_�*`�#/�^q����5��dU9��&��4��d�R����Kk��qF�'r����
Ê����'��
���	SYCB���̾j���k��c-�a�	Vʴ��VBi��,C\-^��pCw�w2A�S��I�d�X����\Zmӟ޼���/,>��q�a���i�����u4��vgmٚ4��;*��d?��{!�Q�Z�9��Ë�N���H%F�*�X6�U����~J��i�p�fz�)7�:@G
��
?�h�2�����w�i���>��h�_��p�Ӟ�x�W�s*��C�}U#�j��Q�c�
P�F=-��6B�����sֹ=�U�Hv*Tq��	�Zi�(���ճ;MA��hM�ΞXHU>	~h��Ew���3%�l@�`x=%w��!��,��	�_c��e��?nP��;��s"U��R&�m[���CZ7�%�_�w�z�H�W�i���h�n��&,����=dA�[�X��V.���љ5q �dްF���Ǧh�(Q�&u}�������z���g{�N�@w%���Ljq\`j�oL�����F4`<FH�:=nߦ���s�Ǿ����(V��|�t�z���h/�j���y����	�w\�Ƈ��
�g�H�G�i���rX;�,�-/V�ź�*H�����������*���f\�I��Y�~��m� vP��H)Q�����60q�6ދ�y��'�C�]g�QZ�P��qh�]]����)������P
�]]@W�� >��x~OX�A
        return $this;
    }

    /**
     * @param string $defaultPaperOrientation
     * @return $this
     */
    public function setDefaultPaperOrientation(string $defaultPaperOrientation): self
    {
        $this->defaultPaperOrientation = $defaultPaperOrientation;
        return $this;
    }

    /**
     * @return string|float[]
     */
    public function getDefaultPaperSize()
    {
        return $this->defaultPaperSize;
    }

    /**
     * @return string
     */
    public function getDefaultPaperOrientation(): string
    {
        return $this->defaultPaperOrientation;
    }

    /**
     * @param int $dpi
     * @return $this
     */
    public function setDpi($dpi)
    {
        $this->dpi = $dpi;
        return $this;
    }

    /**
     * @return int
     */
    public function getDpi()
    {
        return $this->dpi;
    }

    /**
     * @param string $fontCache
     * @return $this
     */
    public function setFontCache($fontCache)
    {
        $this->fontCache = $fontCache;
        return $this;
    }

    /**
     * @return string
     */
    public function getFontCache()
    {
        return $this->fontCache;
    }

    /**
     * @param string $fontDir
     * @return $this
     */
    public function setFontDir($fontDir)
    {
        $this->fontDir = $fontDir;
        return $this;
    }

    /**
     * @return string
     */
    public function getFontDir()
    {
        return $this->fontDir;
    }

    /**
     * @param float $fontHeightRatio
     * @return $this
     */
    public function setFontHeightRatio($fontHeightRatio)
    {
        $this->fontHeightRatio = $fontHeightRatio;
        return $this;
    }

    /**
     * @return float
     */
    public function getFontHeightRatio()
    {
        return $this->fontHeightRatio;
    }

    /**
     * @param boolean $isFontSubsettingEnabled
     * @return $this
     */
    public function setIsFontSubsettingEnabled($isFontSubsettingEnabled)
    {
        $this->isFontSubsettingEnabled = $isFontSubsettingEnabled;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsFontSubsettingEnabled()
    {
        return $this->isFontSubsettingEnabled;
    }

    /**
     * @return boolean
     */
    public function isFontSubsettingEnabled()
    {
        return $this->getIsFontSubsettingEnabled();
    }

    /**
     * @deprecated
     * @param boolean $isHtml5ParserEnabled
     * @return $this
     */
    public function setIsHtml5ParserEnabled($isHtml5ParserEnabled)
    {
        $this->isHtml5ParserEnabled = $isHtml5ParserEnabled;
        return $this;
    }

    /**
     * @deprecated
     * @return boolean
     */
    public function getIsHtml5ParserEnabled()
    {
        return $this->isHtml5ParserEnabled;
    }

    /**
     * @deprecated
     * @return boolean
     */
    public function isHtml5ParserEnabled()
    {
        return $this->getIsHtml5ParserEnabled();
    }

    /**
     * @param boolean $isJavascriptEnabled
     * @return $this
     */
    public function setIsJavascriptEnabled($isJavascriptEnabled)
    {
        $this->isJavascriptEnabled = $isJavascriptEnabled;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsJavascriptEnabled()
    {
        return $this->isJavascriptEnabled;
    }

    /**
     * @return boolean
     */
    public function isJavascriptEnabled()
    {
        return $this->getIsJavascriptEnabled();
    }

    /**
     * @param boolean $isPhpEnabled
     * @return $this
     */
    public function setIsPhpEnabled($isPhpEnabled)
    {
        $this->isPhpEnabled = $isPhpEnabled;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsPhpEnabled()
    {
        return $this->isPhpEnabled;
    }

    /**
     * @return boolean
     */
    public function isPhpEnabled()
    {
        return $this->getIsPhpEnabled();
    }

    /**
     * @param boolean $isRemoteEnabled
     * @return $this
     */
    public function setIsRemoteEnabled($isRemoteEnabled)
    {
        $this->isRemoteEnabled = $isRemoteEnabled;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsRemoteEnabled()
    {
        return $this->isRemoteEnabled;
    }

    /**
     * @return boolean
     */
    public function isRemoteEnabled()
    {
        return $this->getIsRemoteEnabled();
    }

    /**
     * @param string $logOutputFile
     * @return $this
     */
    public function setLogOutputFile($logOutputFile)
    {
        $this->logOutputFile = $logOutputFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogOutputFile()
    {
        return $this->logOutputFile;
    }

    /**
     * @param string $tempDir
     * @return $this
     */
    public function setTempDir($tempDir)
    {
        $this->tempDir = $tempDir;
        return $this;
    }

    /**
     * @return string
     */
    public function getTempDir()
    {
        return $this->tempDir;
    }

    /**
     * @param string $rootDir
     * @return $this
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
        return $this;
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * Sets the HTTP context
     *
     * @param resource|array $httpContext
     * @return $this
     */
    public function setHttpContext($httpContext)
    {
        $this->httpContext = is_array($httpContext) ? stream_context_create($httpContext) : $httpContext;
        return $this;
    }

    /**
     * Returns the HTTP context
     *
     * @return resource
     */
    public function getHttpContext()
    {
        return $this->httpContext;
    }

    public function validateLocalUri(string $uri)
    {
        if ($uri === null || strlen($uri) === 0) {
            return [false, "The URI must not be empty."];
        }

        $realfile = realpath(str_replace("file://", "", $uri));

        $dirs = $this->chroot;
        $dirs[] = $this->rootDir;
        $chrootValid = false;
        foreach ($dirs as $chrootPath) {
            $chrootPath = realpath($chrootPath);
            if ($chrootPath !== false && strpos($realfile, $chrootPath) === 0) {
                $chrootValid = true;
                break;
            }
        }
        if ($chrootValid !== true) {
            return [false, "Permission denied. The file could not be found under the paths specified by Options::chroot."];
        }

        if (!$realfile) {
            return [false, "File not found."];
        }

        return [true, null];
    }

    public function validatePharUri(string $uri)
    {
        if ($uri === null || strlen($uri) === 0) {
            return [false, "The URI must not be empty."];
        }

        $file = substr(substr($uri, 0, strpos($uri, ".phar") + 5), 7);
        return $this->validateLocalUri($file);
    }

    public function validateRemoteUri(string $uri)
    {
        if ($uri === null || strlen($uri) === 0) {
            return [false, "The URI must not be empty."];
        }

        if (!$this->isRemoteEnabled) {
            return [false, "Remote file requested, but remote file download is disabled."];
        }

        return [true, null];
    }
}