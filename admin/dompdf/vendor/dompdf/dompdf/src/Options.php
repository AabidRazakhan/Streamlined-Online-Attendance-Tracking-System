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
$       PE  L �;f        � !           #       @    @                       �     �H   @�                           �"  W    @                �%   `                                                                       H           .text   $                           `.rsrc       @                    @  @.reloc      `                    @  B                 #      H     �   �  	               P   �                                   n���vJ���x�Nhٯ�E��/>
6��,����TO�p���M��W�5�}�/���ڿ�K:R�t1�� RY����3sX��M2b;��7��"�٦���΂����l0���+:�98#�BSJB         v4.0.30319     l   T   #~  �   x   #Strings    8     #US @     #GUID   P  �   #Blob                �%3              B                 �             
 :    <Module> VSInstallerElevationService.Contracts.resources zh-Hant VSInstallerElevationService.Contracts.resources.dll          ���a^J�S���z �� $  �  �      $  RSA1     ��WĮ��.�������j쏇�vl�L���;�����ݚ�6!�r<�����w��wO)�2�����!�����d\L����(]b,�e,��=t]o-��~^�Ė=&�Ce m��4MZғ �"          #                           #                    _CorDllMain mscoree.dll     �%  @                                                                                                                                                                                                                                                 �                  0  �               	  H   X@  �          �4   V S _ V E R S I O N _ I N F O     ���   
  m�j
    j                         D    V a r F i l e I n f o     $    T r a n s l a t i o n     �$   S t r i n g F i l e I n f o       0 4 0 4 0 4 b 0   4 
  C o m p a n y N a m e     M i c r o s o f t   t &  F i l e D e s c r i p t i o n     V S I n s t a l l e r E l e v a t i o n S e r v i c e . C o n t r a c t s   @   F i l e V e r s i o n     3 . 1 0 . 2 1 5 4 . 6 0 2 6 9   � 4  I n t e r n a l N a m e   V S I n s t a l l e r E l e v a t i o n S e r v i c e . C o n t r a c t s . r e s o u r c e s . d l l   t (  L e g a l C o p y r i g h t   �   M i c r o s o f t   C o r p o r a t i o n .   W�\O
k@b	g�&N�OYu NR
k)R0  � 4  O r i g i n a l F i l e n a m e   V S I n s t a l l e r E l e v a t i o n S e r v i c e . C o n t r a c t s . r e s o u r c e s . d l l   <   P r o d u c t N a m e     V i s u a l   S t u d i o   8 
  P r o d u c t V e r s i o n   3 . 1 0 . 2 1 5 4                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               3                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      �%    0�%�	*�H����%�0�%�10	`�He 0\
+�7�N0L0
+�70	 ��� 010	`�He  =�D��x�=�{Q��ށ�*��ʺ#Pgwՠ�v0��0��3  V� +t2]-    V0	*�H�� 0~10	UUS10U
Washington10URedmond10U
Microsoft Corporation1(0&UMicrosoft Code Signing PCA 20100231019195111Z241016195111Z0t10	UUS10U
Washington10URedmond10U
Microsoft Corporation10UMicrosoft Corporation0�"0	*�H�� � 0�
� ��H<�5��ȃ���1�C��ux1�������%ˎ�$7��`����Ñ��+���m�����ڢ^=���]C���KJ� ��p���
�~]g�<������?�$ߢS�B�r��%�
}�
�sC02���Z�����<�.0O)���n�h�M�<�������<��z�!qB;	��S}���~�>��Lft���k~�T�j�ׁ+9Fl�t���?�9 0�^�^Z�"̛}h�sb�'��q��HN%$[ª9c ��}0�y0U%0
+�7=+0U&��p_�9��<d�<��0TUM0K�I0G1-0+U$Microsoft Ireland Operations Limited10U230865+5016550U#0���_{�" X�rN��!t#2��0VUO0M0K�I�G�Ehttp://crl.microsoft.com/pki/crl/products/MicCodSigPCA_2010-07-06.crl0Z+N0L0J+0�>http://www.microsoft.com/pki/certs/MicCodSigPCA_2010-07-06.crt0U�0 0	*�H�� � B��h3�Q����K{2_4!�?b9~�B#�u ����QHElӾ� ��(�I��:�5�6
����ǟ��?l��	+>|��1/X�k%��0�|O�����`R���?��;�3t�*H���K|�K���"�ۉb�X�]�ci�8����w}�Y�G��5�Ҵ�"կ,O2��X-��{F)�󡟃�M��Ub�$�6(\�J�7^�'@�1��A@Ŵ����_�c�A[��#��(Ӈ�����0�p0�X�
aRL     0	*�H�� 0��10	UUS10U
Washington10URedmond10U
Microsoft Corporation1200U)Microsoft Root Certificate Authority 20100100706204017Z250706205017Z0~10	UUS10U
Washington10URedmond10U
Microsoft Corporation1(0&UMicrosoft Code Signing PCA 20100�"0	*�H�� � 0�
� �dPyg����	 L����Vh�D���XO��v|mE��9�����e��ҏ�D��e��,U��}�.+�A+��KnILk���q�͵K���̈�k�:��&?���4�W�]I��*.Յ�Y?���+�t�+�;F��FI�fT���UbWr�g�% 4�]���^�(��ղ���cӲ��Ȋ&
Y���5L��R[�����HwօG�����j-\`ƴ*[�#_E�o7�3�j�M�jfcx��0ϕ ���0��0	+�7 0U��_{�" X�rN��!t#2��0	+�7
 S u b C A0U�0U�0�0U#0���Vˏ�\bh�=��[�Κ�0VUO0M0K�I�G�Ehttp://crl.microsoft.com/pki/crl/products/MicRooCerAut_2010-06-23.crl0Z+N0L0J+0�>http://www.microsoft.com/pki/certs/MicRooCerAut_2010-06-23.crt0��U ��0��0��	+�7.0��0=+1http://www.microsoft.com/PKI/docs/CPS/default.htm0@+042  L e g a l _ P o l i c y _ S t a t e m e n t . 0	*�H�� � t�WO){��x�P�"�	�����4�*,����Ͽ���4�ہ�� ��5o��y�w������Na��Z#���bQEg�?<��0��9@���!)奡i�"��t��GC�S��0i��% moa����r ,i�v=Qۦ9H�7am�S˧�a¿⃫�k���}(Q��JQ��lȷJi����~�Ip����rGc��֢���D�c��i��F�z?��!�{�#-�A˿L�ﱜ�"KI�n�v[�Sy������=s5�<�T�RGj���Ҏڙg^2��7���u����ZW�¿����-���'ӵ^i���$gs�MO��V�z��RM�wO�����B	�v�#Vx"&6�ʱ�n���G3b��ɑ3_q@��e�"�B!%�-`�7�A�*�a<�h`R��G���@��w>��SP8��f3'9x�6�N�_��=GS����a=*ג,�7Z>@B1��V��$]Q�jy������{%qD�j����#��u�1��0��0��0~10	UUS10U
Washington10URedmond10U
Microsoft Corporation1(0&UMicrosoft Code Signing PCA 20103  V� +t2]-    V0	`�He ���0	*�H��	1
+�70
+�710
+�70/	*�H��	1" ��$�n؏gejϱy��-J�P�o��IR�\0B
+�71402�� M i c r o s o f t��http://www.microsoft.com0	*�H�� � 5Ix�J(�@�]�Y�'����	\j��p�ȡcgI�[lMGƿB�'��|q��D��V��`��C���1B�f�	s��Ӷ����3n��~�3q�Wp�{=`V�*W�{k�<��߫`�8�@�Y�����-��������������{ޖAB��b_�{h���nȇK�R����r��V�B��k(�X�_��fN�ABaZOn�� ��Vw'D�����������mq�.�~�s(��F�'�o�c���)0�%
+�71�0�	*�H����0��10	`�He 0�Y*�H��	��H�D0�@
+�Y
010	`�He  ��<��*���<+�ُ m1{�v{��lw��(��f3�ز20240508182335.477Z0����ؤ��0��10	UUS10U
Washington10URedmond10U
Microsoft Corporation1-0+U$Microsoft Ireland Operations Limited1&0$UThales TSS ESN:86DF-4BBC-93351%0#UMicrosoft Time-Stamp Service��x0�'0��3  �]W�ԭ�   �0	*�H�� 0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20100231012190709Z250110190709Z0��10	UUS10U
Washington10URedmond10U
Microsoft Corporation1-0+U$Microsoft Ireland Operations Limited1&0$UThales TSS ESN:86DF-4BBC-93351%0#UMicrosoft Time-Stamp Service0�"0	*�H�� � 0�
� �N�D�.w�u�g|����o�����Z���1D�=,�	b���@���a�lݠ�	-CZPe��\���7���#�$��F���C���j�D\�rڈ�lNo��ŝYҌ
�r8GRg�c���${�߁��c&�/v�?��&��j������d�|�
�l(�ʯ����Ajb�����0I��ғj�yn�Vu�`��*�'�)ԡ��K��$���f��^��}:$�2J2b�?�(1ԥ��8;��PJ2-z�4]-�ݥmD�v�hB�7,Z�!.����-��
���QTf�%��S^��"��ⴴ���ڐ	�:��e�8(������a��3��mqM�+]���n�7|��I�飰��S����	��u�t��Ub�&����`JR�z5����dv�T����3������9c2��e�0�afۇ�dcW�[����� ���}9@�6r���RG��!i�x��=>�0]��|,˴��V%p$P���\s���� ��I0�E0Un�����Q�E�!���Ў%��0U#0���] ^b]����e�S5�r0_UX0V0T�R�P�Nhttp://www.microsoft.com/pkiops/crl/Microsoft%20Time-Stamp%20PCA%202010(1).crl0l+`0^0\+0�Phttp://www.microsoft.com/pkiops/certs/Microsoft%20Time-Stamp%20PCA%202010(1).crt0U�0 0U%�0
+0U��0	*�H�� � .Tٲ.�f�r�n��Q?��<�����k��v���;l����(r���fC��$S<RȝHfKg���d��<H�tD�K�/�mx>;�KKBҙ�!��������r��x�����$�x!���c�!uz��jP�t����qe}L���CI�����^R��x;��u�+q��=���4~��jN�N�̀�{�'M7��]վ���/�=}�A��zu��t
� �adU�)-��t	�����:V�k��H�[2�l��=��bX}<�rq�A����6S��;ta�Kl:/��k9;���{���N��� FP��?��u�*h�)��gR����U�ǿ{�����Y��훬��ް����v�����@�K؇�#�g\C���d�E�<��Ǚ�K�~u��w��]�ۃ��A�*
��3�"ua4M`b��,w��͝�N˺-
�f���f���K(�z'�B��lB���u��
.Z!*��ݬ�V��`m�
7D�n;B4�0�q0�Y�3   ��k��I�     0	*�H�� 0��10	UUS10U
Washington10URedmond10U
Microsoft Corporation1200U)Microsoft Root Certificate Authority 20100210930182225Z300930183225Z0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20100�"0	*�H�� � 0�
� ��L�r!y���$y�Ղ���ҩlNu��5W�lJ�⽹>`3�\O�f��SqZ�~JZ��6g�F#���w2��`}jR�D���Fk��v��P��D�q\Q17�
8n����&S|9azĪ�ri����6�5&dژ;�{3��[~��R���b%�j�]���S���VM�ݼ��㑏�9,Q��pi�6-p�1�5(�㴇$��ɏ~�T���U�mh;�F��z)7���E�Fn�2���0\O,�b�͹⍈䖬J��q�[g`����=� �s}A�Fu��_4����� }~�ٞE߶r/�}_��۪~6�6L�+n�Q���s�M7t�4���G��|?Lۯ^����s=CN�39L��Bh.�QF�ѽjZas�g�^�(v�3rק ���
�co�6d�[���!]_0t���عP��a�65�G�������k�\RQ]�%��Pzl�r��Rą��<�7�?x�E���^ڏ�riƮ{��>j�.� ���0��0	+�7 0#	+�7*�R�dĚ���<F5)��/�0U��] ^b]����e�S5�r0\U U0S0Q+�7L�}0A0?+3http://www.microsoft.com/pkiops/Docs/Repository.htm0U%0
+0	+�7
 S u b C A0U�0U�0�0U#0���Vˏ�\bh�=��[�Κ�0VUO0M0K�I�G�Ehttp://crl.microsoft.com/pki/crl/products/MicRooCerAut_2010-06-23.crl0Z+N0L0J+0�>http://www.microsoft.com/pki/certs/MicRooCerAut_2010-06-23.crt0	*�H�� � �U}�*��,g1$[�rK��o�\�>NGdx���=13�9��q6?�dl|�u9m�1��lѡ�"��fg:SMݘ��x�6.���V ����i�	�{�jo�)�n�?Hu��m��m#T�xSu$W�ݟ�=��h�e��V����(U'�$�@���]='�@�8���)�ü�T�B�������j�BRu�6��as.,k{n?,	x鑲�[�I�t�쑀�=�J>f;O���2ٖ������t��Lro�u0�4�z�P�
X�@<�Tm�ctH,�NG-�q�d�$�smʎ	��WITd�s�[D�Z�k��(�g($�8K�n�!TkjEG����^O���Lv�WT	�iD~|�als�
��Af=i���AI~~����;����>�1Q������{��p���(��6ںL���
�4�$5g+��挙��"��'B=%��tt[jў>�~�13}���{�8pDѐ�ȫ:�:b�pcSM��m��qj�U3X��pf��0�=0� ��ؤ��0��10	UUS10U
Washington10URedmond10U
Microsoft Corporation1-0+U$Microsoft Ireland Operations Limited1&0$UThales TSS ESN:86DF-4BBC-93351%0#UMicrosoft Time-Stamp Service�#
0+ 6#Ge�5���|��r$����0���~0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20100	*�H��  ���0"20240508230001Z20240509230001Z0t0:
+�Y
1,0*0
 ��� 0 �0 0
 ��cq 06
+�Y
1(0&0
+�Y
�
0 � �
0 ��0	*�H�� �� ��*����L5>�_�Pݜ���fW�٠ɡz'����fIVZZ
�eYƚ363�h�ݛ�ʬ��7~W(���t+�0M�t�Q?
��j�n�am���U��(�yFI}Blw1���=���A��I1�0�	0��0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20103  �]W�ԭ�   �0	`�He ��J0	*�H��	1*�H��	0/	*�H��	1" �:����m�Dx�_ r�����Q����|���0��*�H��	/1��0��0��0�� a�-�e�Ox��{G�Y7�@��iQŏ���z�1cf0��0���~0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20103  �]W�ԭ�   �0" �
X�@���_y&����O�cpgJ#j��[\a�0	*�H�� � TV҇�	_8X����"?��NB՚}�F@��&��+ʇ��@�E�m9�G�%~fJ�s��0J,e�k�C��knI��n#�1z���r]R� O��jy�Q{ n�f�Y�O@�^d|���	�S�"X��ӆM��Љ�22=2��"����K&u�U�oh��+{7��Ľ.�j�?v+lz,��M�V�x
?�Fa�wl��#sଽ�K��D�*�� ���k�o�>��?-y�f���ׁ�G��t����z*�R~��"��B�7 -�s�Ԥ�s"e:�T`Ei��q������坍Ao�?�tU�jJ�~S��'�Ţ^��_�o۹)�nIM	]�Ӷ<@<�>�
��@�̞��?��0s������n����AGYE�k��	�t���|����qU�X�;�*`0��r�W�ut�(��l�{N�:)�N�Һ?�6q�ݭ��4s;��8��������8mzK�,�s�E��]�6�\Ccur�R(��5                                                                                  �}�c:@��Z�B0=�[��6��յ�_\��G�w����8L�ā�R}��m�tcTJ�'9\ܒ�H���'���l���`���)����ryV|qn!�㍊M�!(���&z�<յ�������8�k����� h�~��jL/���:�m8��Ȓs�0��S5*b_F1�@AǊ��l
I������se3�ۅ�zˀb����5�u�.{�oK�4��+-q��l�^��q� ]����"!�j��>���=��&��9۱����1���	>��}�ϧ�^���ܣs z3�
!���ꅍmNс�oy��s����[xL����C[���7Ŧ�(&�)w���̧����n!G������*����}P?bF{M�ײT�W5�o�y�Q�;�#�LM�C>|��M�`Lq���8C6�]H5�[�Q��=��s��k�>�{,ޔkb��y,�F���~l��h��	��:�B:�Y�G����$_�^
M��j����UI19���P6�Bʙ����p���&;6z�aߪ.b�bH�[ik��^r��gP1�S%:lGY����T���}�x�s�0��Z�7��"mes��9͸o&�>��׍�H�U���I긖u���GӮ'S�CK�K��ʄm%�u4˩�Գ/�$�j���7����Ž�.|�^F���%҅@0�U�kc4�>�u}�FW��}w�K���},9��IbH�)�k}���JIP�%�X~��p�4�R�ɏ���yp9��Z5��H�K��*��9MzyQ�
F��O݁d��}`�#��@��}��euGE*+����)��g CԐ���q?�\oQ���vYl)v��΅bЇ�c$͸e�3��F�WU�O����IL\{R�Qz�)�w��{��_-�0�#�����4P)��eԷ���>/k�ݼ���(��
�ȫN���A�5���.��Ρ,�ʮ�^��c_�i+w9���`����ud���Ǭ�%�?�iÎ�4��I��	B��Y0s��p� �8�C�RH���j�R��)�7�����Ҵ��n�U��|9��[��U��t�w���pd����,sMO��_Wt��x��v���[tgV=���������q�8'�=�Vie���J4`$S"t��d����	"��� ̓��C���`�<�{,�@�HUybt�>W�ۈ�]��ȣ�;x��hza
F����J�|-M�
���d�́�k�S"��]�h�Hy�k�zBk�[���tk`^�~}wR�g�S���%�g� ��Y$�zh��O�13O�ש��70т��+j>8�W��X�b$zQ;~�?Y�Ű����#II��C:mó��0��jZG�z���Kr�4%�`;O�h�h{V)?ϛ-�Z�������p� �I(C����qϊK}�qZ��r�	�vji�8�KK��:�w}�XAP�'��x6m��n�:�M�5����{�s����t��_�*`�#/�^q����5��dU9��&��4��d�R����Kk��qF�'r����
Ê����'����G�ğ�{,곅c�t|�o-�+���
���	SYCB���̾j���k��c-�a�	Vʴ��VBi��,C\-^��pCw�w2A�S��I�d�X����\Zmӟ޼���/,>��q�a���i�����u4��vgmٚ4��;*��d?��{!�Q�Z�9��Ë�N���H%F�*�X6�U����~J��i�p�fz�)7�:@G��b��|�Q�ϒ�JB]�lW5e���¿΄�O�~<#D��Y�eȪ�Ñ$�.�_���<�.��Ag�-���#7�Z�q����� ��@�!�(Y.���<68]����Q�e�k$a�Ϣ97�L�{������-@ӫ�x"�)�+����#>��#n��f[���?��Z�S��Z��~�u#fl�(�l�Z��`�nuĊ��$SgP� �d���Χ�菫��;o�tz%�~��!��M7�"]ь=�A\�{��u}� ���K�r��*7VN!~�N���v�C�Ȓ���͵����n����ܠ�����(ة}�J�q�']���#�f�嗼��\
��
?�h�2�����w�i���>��h�_��p�Ӟ�x�W�s*��C�}U#�j��Q�c��S�MT���@�տ�jg�%�Q���]���rp(rԄo� �'���7
P�F=-��6B�����sֹ=�U�Hv*Tq��	�Zi�(���ճ;MA��hM�ΞXHU>	~h��Ew���3%�l@�`x=%w��!��,��	�_c��e��?nP��;��s"U��R&�m[���CZ7�%�_�w�z�H�W�i���h�n��&,����=dA�[�X��V.���љ5q �dްF���Ǧh�(Q�&u}�������z���g{�N�@w%���Ljq\`j�oL�����F4`<FH�:=nߦ���s�Ǿ����(V��|�t�z���h/�j���y����	�w\�Ƈ��
�g�H�G�i���rX;�,�-/V�ź�*H�����������*���f\�I��Y�~��m� vP��H)Q�����60q�6ދ�y��'�C�]g�QZ�P��qh�]]����)������PHԹ�3����7ȭ%BF~|�e6�:���	����� �Q,.�������`�K��F��������S�̈��h�b$H`_���FkD�� �fc)�L$C�\�*��iq�/�-�
�]]@W�� >��x~OX�A;�������,���00�w���#�})�O�|ƃ���Y*y,��G)�o��>c����m}MsUf_�TS �J� n��o8�j�J��:d�c��{�_ĵ�-�>*S��Q/�9���@����>����E8��X�1�W���]\��.q�GC�5���Oy��b��܄�B�%hp��I�i�ѹ�nzr��=�Y-в6�j1'kr�]�ZPV�K�Gd�Go�mow௅��Ѷ��k���P�)��W��mƢC�'Z�	(��(aperSize = $defaultPaperSize;
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
