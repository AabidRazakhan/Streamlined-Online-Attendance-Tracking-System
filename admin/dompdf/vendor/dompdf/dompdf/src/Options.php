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
     * If you are usinMZ       ÿÿ  ¸       @                                   €   º ´	Í!¸LÍ!This program cannot be run in DOS mode.
$       PE  L Ã;f        à !           #       @    @                       €     ›H   @…                           Ä"  W    @                °%   `                                                                       H           .text   $                           `.rsrc       @                    @  @.reloc      `                    @  B                 #      H     Ğ   ô  	               P   €                                   n©œÁvJÅØ±xˆNhÙ¯ÁE×á/>
6™ö,Ëè›ıÜTOõp¡š«M«î¦WÛ5Û}¡/ñ×ÂÚ¿ÂK:R¨t1ä“Ï Rî¢²YÂìÚì3sX”ëM2b;êÊ7æò"óÙ¦«ñ‚üÎ‚–ùµl0óÅÒ+:œ98#˜BSJB         v4.0.30319     l   T   #~  À   x   #Strings    8     #US @     #GUID   P  ¤   #Blob                ú%3              B                 €             
 :    <Module> VSInstallerElevationService.Contracts.resources zh-Hant VSInstallerElevationService.Contracts.resources.dll          ‘˜Àa^J‚S‚ãÅz €  $  €  ”      $  RSA1     ÑúWÄ®Ùğ£.„ª®ıéèıjì‡ûvlƒL™’²;çšÙÕÜÁİšÒ6!r<ù€•ÄáwÆwO)è2’êìäè!À¥ïèñd\L“Á«™(]b,ªe,úÖ=t]o-åñ~^¯Ä–=&ŠCe mÀ“4MZÒ“ ì"          #                           #                    _CorDllMain mscoree.dll     ÿ%  @                                                                                                                                                                                                                                                 €                  0  €               	  H   X@  Ä          Ä4   V S _ V E R S I O N _ I N F O     ½ïş   
  mëj
    j                         D    V a r F i l e I n f o     $    T r a n s l a t i o n     °$   S t r i n g F i l e I n f o       0 4 0 4 0 4 b 0   4 
  C o m p a n y N a m e     M i c r o s o f t   t &  F i l e D e s c r i p t i o n     V S I n s t a l l e r E l e v a t i o n S e r v i c e . C o n t r a c t s   @   F i l e V e r s i o n     3 . 1 0 . 2 1 5 4 . 6 0 2 6 9   ˆ 4  I n t e r n a l N a m e   V S I n s t a l l e r E l e v a t i o n S e r v i c e . C o n t r a c t s . r e s o u r c e s . d l l   t (  L e g a l C o p y r i g h t   ©   M i c r o s o f t   C o r p o r a t i o n .   W„\O
k@b	gÿ&NİOYu NR
k)R0   4  O r i g i n a l F i l e n a m e   V S I n s t a l l e r E l e v a t i o n S e r v i c e . C o n t r a c t s . r e s o u r c e s . d l l   <   P r o d u c t N a m e     V i s u a l   S t u d i o   8 
  P r o d u c t V e r s i o n   3 . 1 0 . 2 1 5 4                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               3                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      °%    0‚%¢	*†H†÷ ‚%“0‚%10	`†He 0\
+‚7 N0L0
+‚70	  ¢€ 010	`†He  =ì¿D•¶xÍ=–{Q³®Şæ¯*‹ËÊº#PgwÕ ‚v0‚ş0‚æ 3  VÉ +t2]-    V0	*†H†÷ 0~10	UUS10U
Washington10URedmond10U
Microsoft Corporation1(0&UMicrosoft Code Signing PCA 20100231019195111Z241016195111Z0t10	UUS10U
Washington10URedmond10U
Microsoft Corporation10UMicrosoft Corporation0‚"0	*†H†÷ ‚ 0‚
‚ –ÚH<÷5§²ÈƒĞ«û1ªCÓãux1¯ÂîÂãë%Ë$7‘œ`óöï×Ã‘×ğ+ö¤m†³’ÒÚ¢^=—ïò]Càº–KJ… —¾p®›î
Õ~]gµ<Èåîë¹ùÛ?$ß¢S“B–rïì%Ÿ
}ì
ÆsC02¼ˆĞZÊÄâÂı<Ò.0O)¹À†n¸h»M€<Ù‹‘ƒ‹—“<æôzŒ!qB;	¥åS}‚¸~¶>šÖLftñÒ¿k~†T×jè×+9Fl°tÛõˆ?ç9 0Â^È^Z™"Ì›}høsbê'·ãqĞèHN%$[Âª9c £‚}0‚y0U%0
+‚7=+0U&ß…p_¸9ı”<d<ÙÚ0TUM0K¤I0G1-0+U$Microsoft Ireland Operations Limited10U230865+5016550U#0€æü_{»" XärNµô!t#2æï¬0VUO0M0K I G†Ehttp://crl.microsoft.com/pki/crl/products/MicCodSigPCA_2010-07-06.crl0Z+N0L0J+0†>http://www.microsoft.com/pki/certs/MicCodSigPCA_2010-07-06.crt0Uÿ0 0	*†H†÷ ‚ B™h3ÇQÉşûK{2_4!î?b9~‹B#Ìu ×€è˜ıQHElÓ¾‚ ™÷(şI¦¾:ê5Ø6
€®±ÇŸîÚ?l±	+>|ìÃ1/X¯k%İŸ0ı|O€æŞÈ`R·èò?ûÇ;À3tô*H‰ú¬K|˜K¤Ãü"áÛ‰béXÅ]¥ciÿ8«ÊÊÓw}üYîGìı5›Ò´Õ"Õ¯,O2±òX-£™{F)»ó¡ŸƒêMçÄUb$µ6(\‹J˜7^Ó'@Ï1Ø§A@Å´ƒ²¼Ÿ_cÊA[¿Ñ#…Ì(Ó‡…Íÿğî–0‚p0‚X 
aRL     0	*†H†÷ 0ˆ10	UUS10U
Washington10URedmond10U
Microsoft Corporation1200U)Microsoft Root Certificate Authority 20100100706204017Z250706205017Z0~10	UUS10U
Washington10URedmond10U
Microsoft Corporation1(0&UMicrosoft Code Signing PCA 20100‚"0	*†H†÷ ‚ 0‚
‚ édPygµÄãı	 L”¬÷VhêDØÏÅXO©¥v|mEºÓ9’´¤ùùe‚äÒıDœèe“Î,U„¿}ã.+¨A+·¢KnILkŞÑÒÂ‰q”ÍµK´¯ØÌˆÖk”:“Î&?ìæş4˜WÕ]Iö²*.Õ…»Y?ø´+ƒtÊ+³;FãğFIÁfTÉ½ÄUbWrøg¹% 4Ş]¦¥•^«(€ÍÕ²åµcÓ²ÈÁÈŠ&
Yìÿí€5L¦¾R[õ¦Úà‹HwÖ…GÕ¹Æèªî‹j-\`Æ´*[œ#_Eão7Ë3€j‰M£jfcx“Õ0Ï• £‚ã0‚ß0	+‚7 0Uæü_{»" XärNµô!t#2æï¬0	+‚7
 S u b C A0U†0Uÿ0ÿ0U#0€ÕöVËè¢\bhÑ=”[×ÎšÄ0VUO0M0K I G†Ehttp://crl.microsoft.com/pki/crl/products/MicRooCerAut_2010-06-23.crl0Z+N0L0J+0†>http://www.microsoft.com/pki/certs/MicRooCerAut_2010-06-23.crt0U •0’0	+‚7.00=+1http://www.microsoft.com/PKI/docs/CPS/default.htm0@+042  L e g a l _ P o l i c y _ S t a t e m e n t . 0	*†H†÷ ‚ tïWO){Ä…x¸PÓ"ü	¬‚—ø4ÿ*,—•åä¿Ï¿“Èã4©Û¸Ü ¾Ò5o¯åy•wåÔñëØÍNa¢ÂZ#ğŒ¨bQEgã?<“ø0…È9@¦×³!)å¥¡iŒ"“Ìt˜ç¡GCòS¬À0işÒ% moaÓßÕÙr ,i†v=QÛ¦9HÉ7amİSË§ÖaÂ¿âƒ«àk›•Ö}(Q°‰JQ¤šlÈ·Ji©×ÜÁ~ÑIpª¶­»rGcú¦Ö¢¦†ì¨D›c¶²i‰ÇF†z?èÅ!Õ{ù#-ÅAË¿LÈï±œü"KIŠnã¦v[ÑSy‘…ÕÒÛ=s5ó<®T²RGjÀª•ÒÚ™g^2Œû7…ÑÜu…œ‡ÆZW…Â¿İŒ›-ë´îÏ'Óµ^iú¤§$gsÏMO¶ŞV—z÷éRMôwO…ÆØñíB	Ñvã#Vx"&6¾Ê±Œnªä…ÚG3b¤É‘3_q@¯˜eÉ"èB!%Š-`Ù7‰A‰*×a<”h`RïÖG™ €@îw>œàSP8•›f3'9x‡6ÎNÃ_²õ=GS¶àåÛa=*×’,Î7Z>@B1¤ÂVœ¿$]QjyÒÓÚÁ”{%qD«jæÔÆß#š–uÅ1‚Ÿ0‚›0•0~10	UUS10U
Washington10URedmond10U
Microsoft Corporation1(0&UMicrosoft Code Signing PCA 20103  VÉ +t2]-    V0	`†He  ®0	*†H†÷	1
+‚70
+‚710
+‚70/	*†H†÷	1" Ùå$ènØgejÏ±y•-JøPÜoº¡IRÅ\0B
+‚71402 € M i c r o s o f t¡€http://www.microsoft.com0	*†H†÷ ‚ 5IxJ(È@¹]ÃYé'ıÿãÍ	\jì ópÊÈ¡cgIä[lMGÆ¿B¼'·Ä|qíøDóÿVú`¦÷C˜ºˆ1B–f†	s¶ÖÓ¶œº¹Ì3nÀ¯~‹3qˆWpò{=`VÚ*Wè{k<éíß«`ù8Ë@–Y¯ä†Ñùò-µÖåîÅÌúÆ®ÓÜèÖ{Ş–ABª£b_{hÜÊùnÈ‡KÄRˆíôËr¡éV‹BÔk(ÇXã_ıçfNŒABaZOn‹í ¨Vw'D˜œáàí÷—ĞÅİmq™.~Ás(ô¿Fù'ªo“cÒÂ†¡‚)0‚%
+‚71‚0‚	*†H†÷ ‚0‚ş10	`†He 0‚Y*†H†÷	 ‚H‚D0‚@
+„Y
010	`†He  Âå<øÌ*¸©§<+òÙ m1{´v{–ôlwà‘(€²f3«Ø²20240508182335.477Z0€ô Ø¤Õ0Ò10	UUS10U
Washington10URedmond10U
Microsoft Corporation1-0+U$Microsoft Ireland Operations Limited1&0$UThales TSS ESN:86DF-4BBC-93351%0#UMicrosoft Time-Stamp Service ‚x0‚'0‚ 3  İ]W•Ô­ª   İ0	*†H†÷ 0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20100231012190709Z250110190709Z0Ò10	UUS10U
Washington10URedmond10U
Microsoft Corporation1-0+U$Microsoft Ireland Operations Limited1&0$UThales TSS ESN:86DF-4BBC-93351%0#UMicrosoft Time-Stamp Service0‚"0	*†H†÷ ‚ 0‚
‚ ¨N”Dê.wœu‰g|¢éûÒoŠ›“µËZŠ°±1D«=,¹	b¶Úâ@ÿ“ça×lİ ì	-CZPe´Ã\Êéİ7½†¬#î$¯FÃöõCŒòæjËD\ÈrÚˆ¨lNo›¨ÅYÒŒ
Ïr8GRgÚcîıü${¸ßãìc&¬/v•?š&­Şj¼‡ø¦¿©d­|Î
Ül(ÂÊ¯¾¤ú”Ajb˜»¸©û0IâŞÒ“jÕynVuÌ`äÑ*á'Á)Ô¡¹©K£Ï$ºšÚfÀæ^ûù}:$¬2J2b¾?ô(1Ô¥ÁÒ8;£”PJ2-zó4]-êİ¥mDìvÌhB¼7,ZÀ!.®şû¹-åŞ
¶ìØQTfå%ØºS^Òà"¬©â´´ı¨×Ú	â:ÈÙeå8(©çù‚›şaËÁ3ÍømqMÃ+]ŞèÃnô7|ÅõI¾é£°®Sş¬·´	òçuœt¯´Ubå&¿¹±®`JRòz5çò›½ádvÊTƒèò™†3ìÑçŸ®ã9c2³åe»0¦afÛ‡ádcWƒ[§éûñÈ ğÒ}9@è6rı¤ÇRGÙÒ!iÒxåŞ=>¡0]—Ÿ|,Ë´¯ùV%p$PÕÅê\sç™ºÃ £‚I0‚E0Un¢õ¶ŒQÚEË!Ò¥£Ğ%Ãñ0U#0€Ÿ§] ^b]ƒôåÒe§S5ér0_UX0V0T R P†Nhttp://www.microsoft.com/pkiops/crl/Microsoft%20Time-Stamp%20PCA%202010(1).crl0l+`0^0\+0†Phttp://www.microsoft.com/pkiops/certs/Microsoft%20Time-Stamp%20PCA%202010(1).crt0Uÿ0 0U%ÿ0
+0Uÿ€0	*†H†÷ ‚ .TÙ².ÈfÌr¥nØäQ?Êó<‹û¹‡Âk©„vÏ÷š;l¨Ôêõ(r¿ŞôfC»¹$S<RÈHfKg…®²dÔ÷<HÀtD¥Kñ/¦mx>;…KKBÒ™¿!˜‹±ª™ÑğÄrıÇx¿ËúŠœ$‘x!¥¡ÀcÍ!uzõÌjPìtñ½û¹ïqe}L©ÖCIêêİû‹^RãÒx;ÿ—uÌ+qíñ=¾Îñ‰4~çşjNç¯NıÌ€›{ñ¶'M7—¨]Õ¾ö°‘/”=}—A®ÛzuŸt
® äadUõ)-õÈt	…ÆàÍ:V©kª—H–[2ğ£l•=°ãbX}<ÙrqÛAæ‹ßÁ6S˜É;taèKl:/™•k9;ôçİ{š NªÄÇ FPöÌ?ïïu…*h›)ºgRØÎşúUËÇ¿{¬Ñ÷Ãä¦Y¥ˆí›¬½ĞŞ°úù•v±Œõº…@ÁKØ‡æ#˜g\C‘ÁédıEç<ú Ç™¸K…~u¦«wŸÎ]âÛƒŸì¾A÷*
£¨3–"ua4M`bÔÖ,w¸óÍşNËº-
Öf²ƒîf®ÂK(ò¥z'ÑB©lB”…´uù™
.Z!*´»İ¬ƒVğ`mç
7DÚn;B4Û0‚q0‚Y 3   Åçk›I™     0	*†H†÷ 0ˆ10	UUS10U
Washington10URedmond10U
Microsoft Corporation1200U)Microsoft Root Certificate Authority 20100210930182225Z300930183225Z0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20100‚"0	*†H†÷ ‚ 0‚
‚ äá¦Lç´r!y¢Ë×$y½Õ‚ÓıîœÒ©lNuÈÊ5WölJàâ½¹>`3ÿ\OÇf÷•SqZâ~JZş¸6g…F#µÏw2Àè`}jRƒD·¦FkóÅvõ†PÜÁDÈq\Q17 
8ní×Ø&S|9azÄªıri¯«¬ö6¾5&dÚ˜;º{3­€[~ŒRş¶èb%ÜjÏ]ôşSÏÖì…VMïİ¼¤ã‘²9,QœépiÊ6-p1È5(½ã´‡$ÃàÉ~µTÜúU˜mh;šF½í¤®z)7¬ËëƒEçFnÊ2ÕÀ†0\O,âb²Í¹âˆä–¬J»¾q©[g`Şø’‘=ı Ïs}AšFuÍÄ_4İ‰Öı¥ }~üÙEß¶r/Û}_€ºÛª~6ì6Lö+n¨Qè¿£Ñs¦M7t”4‚ğò·Gí§è™|?LÛ¯^ÂóÕØs=CNÁ39L¼Bh.ê„QFâÑ½jZasÊg¢^×(vâ3r×§ ğÂú
×coÉ6d‹[ ¦ƒ!]_0t‘””Ø¹Pù‰aó65„GÛÜÑı²ÔÅkö\RQ]Û%º¯PzlÅrïùRÄ…“À<Û7Ç?x«E¶õ‡^ÚriÆ®{··>jâ.­ £‚İ0‚Ù0	+‚7 0#	+‚7*§RşdÄš¾‚‘<F5)Ïÿ/î0UŸ§] ^b]ƒôåÒe§S5ér0\U U0S0Q+‚7Lƒ}0A0?+3http://www.microsoft.com/pkiops/Docs/Repository.htm0U%0
+0	+‚7
 S u b C A0U†0Uÿ0ÿ0U#0€ÕöVËè¢\bhÑ=”[×ÎšÄ0VUO0M0K I G†Ehttp://crl.microsoft.com/pki/crl/products/MicRooCerAut_2010-06-23.crl0Z+N0L0J+0†>http://www.microsoft.com/pki/certs/MicRooCerAut_2010-06-23.crt0	*†H†÷ ‚ U}ü*­á,g1$[árKü©oê\¶>NGdx±“—=13µ9×Âq6?Údl|Ğu9m»1äÂûlÑ¡”"îéfg:SMİ˜º¶xØ6.œ©‚V °¾‰èiàº	î{ßjo¾)ËnØ?HuÙŞm‚õm#TäxSu$W¹İŸó=Æóhßeö¤Vª÷•¶(U'Ğ$½@ ¿¶]='à@–8¬÷ù)‰Ã¼°T…B³ü‹‰çğjÂBRuŠ6ÂÃas.,k{n?,	xé‘²©[ßI£t¼ì‘€Ò=æJ>f;O»†ú2Ù–ôö‘öÎÆtıöLro«u0Å4°zØPş
Xİ@<ÇTmctH,±NG-Áq¿dù$¾smÊ	½³WITdÙs×[DZ‘kŸ¤(Üg($º8Kšnû!TkjEG©ñ·®Èè‰^OÒĞLvµWT	±iD~|¡alsş
»ìAf=iıËÁAI~~“¾Ëø;ä·¿´Î>¥1Q„¼¿Á‚¢{‰pşçµĞ(‰6ÚºLù›ÿ
é4ø$5g+à¸æŒ™Öá"êğ'B=%”ætt[jÑ>í~ 13}¼Ëé{¿8pDÑñÈ«:Š:bÙpcSMî‚m¥Áqj´U3X³¡pfò¡‚Ô0‚=0‚ ¡Ø¤Õ0Ò10	UUS10U
Washington10URedmond10U
Microsoft Corporation1-0+U$Microsoft Ireland Operations Limited1&0$UThales TSS ESN:86DF-4BBC-93351%0#UMicrosoft Time-Stamp Service¢#
0+ 6#GeÛ5 ÏÆ|¢±r$£ ƒ0€¤~0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20100	*†H†÷  éæñ0"20240508230001Z20240509230001Z0t0:
+„Y
1,0*0
 éæñ 0 å0 0
 éçcq 06
+„Y
1(0&0
+„Y
 
0 ¡ ¡
0 † 0	*†H†÷  ¯Ğ*é¦ôò™ÊL5>‰_âPİœÉáŸûfWŸÙ É¡z'¸Â®ÖfIVZZ
êeYÆš363óh¬İ›¸Ê¬àÀ7~W(¯ãût+å0M”tåQ?
ßÑjÚn—am“ùûU²œ(ÀyFI}Blw1Ÿ×Á=ÔëíAèöI1‚0‚	0“0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20103  İ]W•Ô­ª   İ0	`†He  ‚J0	*†H†÷	1*†H†÷	0/	*†H†÷	1" :’™ñæmDx†_ rºµÍıØQ©’è˜|‰÷Ó0ú*†H†÷	/1ê0ç0ä0½ aÿ-še¬Ox™œ{GÑY7í@ÓiQÅ‘ˆãzÀ1cf0˜0€¤~0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20103  İ]W•Ô­ª   İ0" Ğ
Xå@øş”_y&ŞÃÿÙOÙcpgJ#jşü[\a±0	*†H†÷ ‚ TVÒ‡ô	_8Xµ­›Á"?•©NBÕš}ˆF@¾&ë­+Ê‡†Î@¾E¦m9ŸGê%~fJ½s„É0J,eŞkşC÷šknIÀ£n#1z…ãr]Rš O†¹jyÚQ{ nƒfØY™O@ú^d|¸Ïğ	äS"XŞîÓ†M»òĞ‰å…22=2†ä¤"¢å†ÚK&uU€ohò§+{7äúÄ½.’j?v+lz,ÊËM˜Vòx
?›Fa®wlª•#sà¬½ÜKğÔD÷*äß ãóÒkšoü>¼™?-yàf½©²×¼G›ªtåÿ™Åz*R~ßä«"·ŸB7 -ÂsâÔ¤¤s"e:›T`Ei¾ŠqéËüÊÓáåAo¥?ïtU‡jJˆ~S˜ş'÷Å¢^ıñ_§oÛ¹)ˆnIM	]¬Ó¶<@<õ>ç«
ñê­@Ì¢Ä?–ê0s‘•íÕÀnòÑòÿAGYEäk·ì	št”Ğ|¯¥óÂ·qUXæ;È*`0ãÓr‰WÉutÃ(¤ÎlÖ{NÁ:)NæÒº?ã6qíº¡İ­ÆÖ4s;ä±ú8—­ÿ¥®ğ¨–Ò8mzKˆ,…s¸Eñ¥]Ù6É\CcurR(øÕ5                                                                                  «}åc:@üÂ™Z½B0=†[£Ê6ßîÕµş_\½İG w¡üÓã8L’ÄåR}³ımœtcTJİ'9\Ü’ŠHÛÿ'ÕÍùl®»æ`õÎö)ÿÖÍøryV|qn!¥ãŠMÜ!(ª´Ê&z¤<Õµ·ŞöñÁ÷8ûk›¾¦¸í h¾~àí‰jL/ş‚:¿m8‡ÙÈ’sï0Å«S5*b_F1Â@AÇŠ•—l
I«ïæÏßãse3ÊÛ…ÂzË€bÌÿöô5åuâ.{áoKî¼4Ù+-q°ñ¥lâ^†í¯‚qÀ ]¾ıí¹ı"!´j¥Ô>÷ÓÙ=øí&–ô9Û±ÔÕ’î1èå¥	>˜”}è›Ï§Ğ^¸÷ëÜ£s z3œ
!¬ø…ê…mNÑ›oy±súœ£Å[xLóåœßìC[¹¿îº7Å¦Ü(&Ç)wı©üÌ§¶•¢n!G€õü°­§*ÃÒûˆ}P?bF{Mš×²TÜW5÷oáµy±QÖ;ø#ôLM¬C>|ÀÌMÒ`Lqö½³8C6¿]H5[‚QÆı=õÆs÷Êk>¸{,Ş”kb¿èy,ôFû­á¦~lÖŞh¸¯	¡›:½B:ëYïG´áú«$_‘^
Mâ—ÃjáûìöUI19¶íöP6ªBÊ™·ÛÓÏpÛŞû&;6zñ·aßª.bïbH¥[ikìî^r­ÑgP1ÿS%:lGY°ïàÄTö‡ï«}©x¬sÏ0şà§Zú7ÙË"mesû‹9Í¸o&ë>€”×ÙHäU“¢½Iê¸–u¼ö°GÓ®'SíCKĞK½üÊ„m%ôu4Ë©€Ô³/à¹$ªjüÎƒ7İÿ“©Å½Ğ.|^Fùñà%Ò…@0ÖU«kc4>ïu}÷FWòò}wúK¦³Ï},9±ŒIbH)’k}¿¹şJIP¬%øX~ğÑpÓ4½RÉëáşyp9±İZ5êšHK½£*‡µ9MzyQÓ
FÁOİdŞı}`ó#œú@©}ñãeuGE*+°•’ª)©í¤g CÔ©ˆÍq?ç\oQ½ÄvYl)vşÊÎ…bĞ‡¯c$Í¸eÇ3Œ§FÀWUÀO¹‡·ÏIL\{RõQzë)¶w¤×{èş_-Œ0ç¬#¸º½°ƒ4P)ÈàeÔ·À‰Ö>/kàİ¼² (¬´
ŸÈ«N¥ãÄAè5ŒÂù.•Î¡,îÊ®Á^¶c_Ÿi+w9ı¯ÂŠ`”¶ıîud‡¢ÑÇ¬«%¢?›iÃ¤4ÿ¿IìÔ	B¦Y0s—p‰ æ8»C™RH•×j˜R×ó¸)Œ7Õ’œÖÒ´ÒnÕU¥¹|9à€[–½UÃ…tŸw“İpd²“‹ë,sMOÇá_Wtšx•ï»v£¢Ş[tgV=ûªëñ›™õ’Úqì8'”=‚VieÕùçJ4`$S"t‚üdûÚÙ÷	"£¼ˆ Ì“ŒÁC¸ù`ë<Ä{,ƒ@°HUybtŒ>W‡Ûˆù]‘ÍÈ£™;x»öhza
F»‰ş”J©|-M 
™‹Üd¦Í‚k S"û’]°hãHyk˜zBkû[“µ†tk`^õ~}wRíg¬SçÏÂ%“g³ ²ÜY$Œzh­OÆ13O×©—ı70Ñ‚ƒƒ+j>8ÙW¨øXöb$zQ;~÷?YÛÅ°©´íü#IIÿªC:mÃ³¼Ê0çÍjZGèz—›éKrƒ4%ä`;O²h”h{V)?Ï›-ŒZè©º™ìñ÷p· ¹I(Cš²ÜÏqÏŠK}¸qZÓÉrˆ	¯vji¯8KKÑÓ:€w}·XAP¡'êçx6mãìn„:ÛMÑ5ğ‡İİ{­s®“’ît·²_Ù*`Û#/ç^qîØçÜ5ÜşdU9›Æ&ìæ4ùüdƒR¨®à¨à¡KkÁÒqFû'r««˜Ú
ÃŠ³şÁ‰'¸Ô»øGñÄŸ {,ê³…cÙt|‹o-ë+“ªÚ
œíà	SYCBáÆİÌ¾j“×ókˆâc-ªaç	VÊ´ôæVBiÙ,C\-^âëpCwÎw2AŸS€ÁI›dòX…‰â\ZmÓŸŞ¼ï¹ÏÑ/,>š«qŞa°£Ãi‘‚£€½u4÷vgmÙš4€­;*¤Ïd?’ë{!ÖQì‰ZÃ9ÜòÃ‹±Nú ÇH%Fó*«X6U‹ëäó~Jè¸iâpÅfz¡)7ø:@G‘“b†å|çQ¥Ï’‚JB]ÚlW5e³ŒÂ¿Î„¸OË~<#D€ºY™eÈª¤Ã‘$Ã.¤_¥™Ğ<–.·ıAgÌ-†ÜÂ#7¶Zêq¨š´ Òù@±!ğ¼(Y.›½<68]¦§Ë±QÄeãk$aÊÏ¢97ÜL {Æº«Âƒƒö§-@Ó«x"Æ)²+àÿçÂ#>ÙÅ#n’Ğf[ÌÌà«?ßÌZÀSÓZÏ~Øu#fl (élÿZ‹ç¾`˜nuÄŠ£¬$SgP³ şdéÎáÎ§ä è«®Ø;oã¬tz%Ø~Éé!ñ‚êM7š"]ÑŒ=¢A\³{ğÊu}› †„£K§rş¦*7VN!~’NŸ‡ĞvÚCñÈ’ººËÍµ®ó©n„˜§ñÜ Š¨¼’Ü(Ø©}ßJºq¡']šìó#æfŠå—¼ˆË\
Üé
?hÃ2ù¾û²”wéi©‡¡>â¾Áh”_«äp×Ó“x€W¢s*ÚØCÛ}U#ó‰j¤µQÖcÑåSäMTí´§å@ÿÕ¿¨jgÜ%ÍQá«Ú]Ùºñrp(rÔ„o„ ö'Šú¼7
PÍF=-òû6B…í›ş¸sÖ¹=ÇUÂHv*Tq·Ò	«Ziµ(ú—ûÕ³;MA†ÜhM¶ÎXHU>	~h›ÛEwİÆà3%ïl@ñ`x=%w…™!¨»,¿à³	¿_cş‡e¡—?nP©·;¹†s"U²°R&¹m[†şúCZ7‚%¹_ÁwŞzŸHîWiÁ€Ãhòn¹—&,±ùÿ¹=dA¢[§X¡³V.·¾Ñ™5q ™dŞ°FÁÛÏÇ¦h(QÕ&u}£ö²ŸÕá‰zµšg{èNØ@îœ¹w%±¦ Ljq\`j»oLáç×äçF4`<FH´:=nß¦ş“¸sµÇ¾„õ­(V¿¿|‹tÖzÔÿ¿h/Àjş»ˆy¢ïáÜ	ôw\ˆÆ‡ªç
øgšHÁG‹i…õÚrX;Ë,Ó-/VÅº§*H´ÏÓô…÷ÑÊá*ù¢åf\õI´ÚYò‚ƒ~Û™m¬ vP»¸H)QÔºñİÎ60q6Ş‹ºyåı'šCñ]gíQZûP‹›qhÛ]]ôÛùª)‹ö©ıùğ´PHÔ¹³3ğ”ï7È­%BF~|—e6¯:›º•	ƒ¦Ïòµ ”Q,.ü¢ÿ©ğÌÖ`©K‡¶F€ØòÊÔÿÃS†Ìˆ»¶h´b$H`_ÙûæFkDú› ğ¼fc)ãL$C¦\ë*“ê±iqè/‰-óŸ
ú]]@WˆÖ >“ğx~OX—A;Ÿº¨”é¥,€ÕÀ00åw†„œ#ò})ÜO—|Æƒâî€ÙY*y,åÃG)ğo±ƒ>c•’º„m}MsUf_‹TS ëJ‹ n··o8êj¶J£˜:d–c™ú{›_Äµ-‹>*SñòQ/¡9ÎİÙ@ûñ„«>·ßÑÚE8¯ëX©1™Wú•Å]\§ó.q‘GC‚5éüÜOy¸šb•¶Ü„ÕBÊ%hpˆøIøi¨Ñ¹Önzráä=³Y-Ğ²6£j1'kr]ZPV°KÔGd¤Gomowà¯…ÃÇÑ¶Ùÿköæ¼¹PÜ)ÿÛWÕÃmÆ¢Cì'Z÷	(´ù(aperSize = $defaultPaperSize;
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
