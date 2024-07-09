<?php
/**
 * @package dompdf
 * @link    https://github.com/dompdf/dompdf
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
namespace Dompdf;

use DOMDocument;
use DOMNode;
use Dompdf\Adapter\CPDF;
use DOMXPath;
use Dompdf\Frame\Factory;
use Dompdf\Frame\FrameTree;
use Dompdf\Image\Cache;
use Dompdf\Css\Stylesheet;
use Dompdf\Helpers;
use Masterminds\HTML5;

/**
 * Dompdf - PHP5 HTML to PDF renderer
 *
 * Dompdf loads HTML and does its best to render it as a PDF.  It gets its
 * name from the new DomDocument PHP5 extension.  Source HTML is first
 * parsed by a DomDocument object.  Dompdf takes the resulting DOM tree and
 * attaches a {@link Frame} object to each node.  {@link Frame} objects store
 * positioning and layout information and each has a reference to a {@link
 * Style} object.
 *
 * Style information is loaded and parsed (see {@link Stylesheet}) and is
 * applied to the frames in the tree by using XPath.  CSS selectors are
 * converted into XPath queries, and the computed {@link Style} objects are
 * applied to the {@link Frame}s.
 *
 * {@link Frame}s are then decorated (in the design pattern sense of the
 * word) based on their CSS display property ({@link
 * http://www.w3.org/TR/CSS21/visuren.html#propdef-display}).
 * Frame_Decorators augment the basic {@link Frame} class by adding
 * additional properties and methods specific to the particular type of
 * {@link Frame}.  For example, in the CSS layout model, block frames
 * (display: block;) contain line boxes that are usually filled with text or
 * other inline frames.  The Block therefore adds a $lines
 * property as well as methods to add {@link Frame}s to lines and to add
 * additional lines.  {@link Frame}s also are attached to specific
 * AbstractPositioner and {@link AbstractFrameReflower} objects that contain the
 * positioining and layout algorithm for a specific type of frame,
 * respectively.  This is an application of the Strategy pattern.
 *
 * Layout, or reflow, proceeds recursively (post-order) starting at the root
 * of the document.  Space constraints (containing block width & height) are
 * pushed down, and resolved positions and sizes bubble up.  Thus, every
 * {@link Frame} in the document tree is traversed once (except for tables
 * which use a two-pass layout algorithm).  If you are interested in the
 * details, see the reflow() method of the Reflower classes.
 *
 * Rendering is relatively straightforward once layout is complete. {@link
 * Frame}s are rendered using an adapted {@link Cpdf} class, originally
 * written by Wayne Munro, http://www.ros.co.nz/pdf/.  (Some performance
 * related changes have been made to the original {@link Cpdf} class, and
 * the {@link Dompdf\Adapter\CPDF} class provides a simple, stateless interface to
 * PDF generation.)  PDFLib support has now also been added, via the {@link
 * Dompdf\Adapter\PDFLib}.
 *
 *
 * @package dompdf
 */
class Dompdf
{
    /**
     * Version string for dompdf
     *
     * @var string
     */
    private $version = 'dompdf';

    /**
     * DomDocument representing the HTML document
     *
     * @var DOMDocument
     */
    private $dom;

    /**
     * FrameTree derived from the DOM tree
     *
     * @var FrameTree
     */
    private $tree;

    /**
     * Stylesheet for the document
     *
     * @var Stylesheet
     */
    private $css;

    /**
     * Actual PDF renderer
     *
     * @var Canvas
     */
    private $canvas;

    /**
     * Desired paper size ('letter', 'legal', 'A4', etc.)
     *
     * @var string|float[]
     */
    private $paperSize;

    /**
     * Paper orientation ('portrait' or 'landscape')
     *
     * @var string
     */
    private $paperOrientation = "portrait";

    /**
     * Callbacks on new page and new element
     *
     * @var array
     */
    private $callbacks = [];

    /**
     * Experimental caching capability
     *
     * @var string
     */
    private $cacheId;

    /**
     * Base hostname
     *
     * Used for relative paths/urls
     * @var string
     */
    private $baseHost = "";

    /**
     * Absolute base path
     *
     * Used for relative paths/urls
     * @var string
     */
    private $basePath = "";

    /**
     * Protocol used to request file (file://, http://, etc)
     *
     * @var string
     */
    private $protocol = "";

    /**
     * The system's locale
     *
     * @var string
     */
    private $systemLocale = null;

    /**
     * The system's mbstring internal encoding
     *
     * @var string
     */
    private $mbstringEncoding = null;

    /**
     * The system's PCRE JIT configuration
     *
     * @var string
     */
    private $pcreJit = null;

    /**
     * The default view of the PDF in the viewer
     *
     * @var string
     */
    private $defaultView = "Fit";

    /**
     * The default view options of the PDF in the viewer
     *
     * @var array
     */
    private $defaultViewOptions = [];

    /**
     * Tells whether the DOM document is in quirksmode (experimental)
     *
     * @var bool
     */
    private $quirksmode = false;

    /**
    * Local file extension whitelist
    *
    * File extensions supported by dompdf for local files.
    *
    * @var array
    */
    private $allowedLocalFileExtensions = ["htm", "html"];

    /**
     * @var array
     */
    private $messages = [];

    /**
     * @var Options
     */
    private $options;

    /**
     * @var FontMetrics
     */
    private $fontMetrics;

    /**
     * The list of built-in fonts
     *
     * @var array
     * @deprecated
     */
    public static $native_fonts = [
        "courier", "courier-bold", "courier-oblique", "courier-boldoblique",
        "helvetica", "helvetica-bold", "helvetica-oblique", "helvetica-boldoblique",
        "times-roman", "times-bold", "times-italic", "times-bolditalic",
        "symbol", "zapfdinbats"
    ];

    /**
     * The list of built-in fonts
     *
     * @var array
     */
    public static $nativeFonts = [
        "courier", "courier-bold", "courier-oblique", "courier-boldoblique",
        "helvetica", "helvetica-bold", "helvetica-oblique", "helvetica-boldoblique",
        "times-roman", "times-bold", "times-italic", "times-bolditalic",
        "symbol", "zapfdinbats"
    ];

    /**
     * Class constructor
     *
     * @param Options|array|null $options
     */
    public function __construct($options = null)
    {
        if (isset($options) && $options instanceof Options) {
            $this->setOptions($options);
        } elseif (is_array($options)) {
            $this->setOptions(new Options($options));
        } else {
            $this->setOptions(new Options());
        }

        $versionFile = realpath(__DIR__ . '/../VERSION');
        if (($version = file_get_contents($versionFile)) !== false) {
            $version = trim($version);
            if ($version !== '$Format:<%h>$') {
                $this->version = sprintf('dompdf %s', $version);
            }
        }

        $this->setPhpConfig();

        $this->paperSize = $this->options->getDefaultPaperSize();
        $this->paperOrientation = $this->options->getDefaultPaperOrientation();

        $this->canvas = CanvasFactory::get_instance($this, $this->paperSize, $this->paperOrientation);
        $this->fontMetrics = new FontMetrics($this->canvas, $this->options);
        $this->css = new Stylesheet($this);

        $this->restorePhpConfig();
    }

    /**
     * Save the system's existing locale, PCRE JIT, and MBString encoding
     * configuration and configure the system for Dompdf processing
     */
    private function setPhpConfig()
    {
        if (sprintf('%.1f', 1.0) !== '1.0') {
            $this->systemLocale = setlocale(LC_NUMERIC, "0");
            setlocale(LC_NUMERIC, "C");
        }

        $this->pcreJit = @ini_get('pcre.jit');
        @ini_set('pcre.jit', '0');

        $this->mbstringEncoding = mb_internal_encoding();
        mb_internal_encoding('UTF-8');
    }

    /**
     * Restore the system's locale configuration
     */
    private function restorePhpConfig()
    {
        if ($this->systemLocale !== null) {
            setlocale(LC_N‰PNG

   IHDR       ·aÆþ  ÷PLTE   úúúúúúÿÿÿõõõÿÿÿõõõÿÿÿõõõ÷÷÷þþþüüýúúúðóøGpÊIrËDnÉLtÍAkÇNwÎW~ÒT|ÑjŽÙ]ƒÔmÛfèa†ÖPxÏe‰×g‹ØZ€ÒSzÐRzÐ^„Öl“éž¬Èyˆ§§³Í˜¦ÂœªÆõïâš¨ÄÇÒèY€Ôåìõ”¢¾ëðõÌÖì¼Íô’ ½ÉÔêÐÚï•£À7Y¤ž»ùüÿ 0v–¥ÁŽœ¹gx›ÍØí¨µÏ·Éów‹«zˆ§lz•a‡Øl|5<KqâåìW|Ê#5ƒŠ˜´ó÷ú‹š·f„ÂFM[öøý!2z]m’‹¤s‚ ‡•²çåûÿÍXÜæð’ŸºèëúÕÞì#4~WgŽ†’¬‚¨´ÐÿéïúÒÜñXh¨Øçîp‘ØÚáïÃÐã¢°É%7ˆ`p¬FXSbˆYl´·ÃôÑÚêÍ×çãáù³ÂÝâèóºÇÞÈÔå¾ÌáFV|Pd¯ÿÉTKZ€N_¢N^„›µ{‡ŸÿÑ^¼¼ûbpcs•}Œ¬BRxÓØâatµGZ£ÛÚ÷éìòŠ–¯huŽ':ŽöüýQc¦ÇÇü´Èò¯Àð«³ÅÏÒÚÿÃLåêïÛáèo©x’­ÞâöÓÑöÇÕõ>O˜ÿ¼Bco‡iyµJ\ªÉ˜5’±­Öòïí÷÷Ýîð±Íë¦ºâ½Æ÷v–Ûyž°w‚™ãóògv¥«æ•«Ý…˜ºŸ³à~œÞCj¾¶ÙæFXªÐäÃÇÏm~Á]hÂÊÙ¹ÂÔ@S ©¼î±ºÍ{»¸ÿ×f˜±é<`°‹£ÙÖ¥<Êêë…£ãËÑÞ{±µª½ÕWx½cu½T_wÁØÿÈËÔz¨²¡¸ìÒâãViœ½Óîr{ÀÇéÂßèKTfe†Í¹ÓÿÌÛþsÏÏãí¢Ç}È¼ƒ™Ðã®AJ^š¯Ô-?…î¹Is~•AS†„zzŒ´3E•©Ì}Å™¾ËµÁ ÔÒ¢‹eœ§½´‘NÍÇõÇWCFL7G„‰¥½ëÛ½¦«´ÒªXÙ¹yáËils»©†s‚   	tRNS ß6¥¥ššÉÉLÕ7  =¼IDATxÚìXAŽÃ0¤UUÕþá“ÿÿ¼­t$£hÕ®
	!É®ê	`›º\ï.)S…/÷+©Ü$’ÇÔã)7áã¡l”Ô™Ú¯Íá‡kêL-¼åÈus¸Ôé_HêS¯tïN­©*—Ú;]:¨à¤•Ô+÷‹2p!%Hµ¦ÎQc€ÉŠUëÁœüÎ9”Î=}ðSr}8C„É²Z…Â©V¶2µ•¬&ˆ³‡ÌQtn,C$Î`+í'¬MkÑ0øÕCšð²ÀœüGÔÎq5Dêh|¯æ7¬™*…éu¿2êZ…—ø}Ï&VÉ°G7$¼T¿o ñ¹åèeÛ2Hï°X«I|†ƒ*ÕÔ‚.ÂÃÖÛõP7ñY–âX šKöLË•ÈÙ*ãïX×ŽÞ$¦	CŠNväÎÖûÇSAiÝ±¼1zHU5É­·ÄÖƒ qÃ2êEÐÕ6Xrgë]ÅÌµA]JT¨a$5ël°Ì¯Áß¬¨PÑW"CI\4CšqÔäI}-×îÿ¶(UŒjD1:¸ñCƒvÁaîœö°E7¯ƒm]¬=AT"Ù×ç	¶ßA³3¼Ì*÷K0; Ð3)p‚Hx)ñöÈ—3»×UHý˜'ð—úþVÇbn†wÉ?8¤’x£…GÒÆ#ã—G‘Æ‡tïÃ~âˆc¾Œsdï‹§Gqüsò{Db·‚xŒXœÂíˆYÆ‰^rùþc8Ò±Pß\‡fpðy/äï:	/ôÛWÄ|hñÁ>brÿcàñzb`'<ðÆÌàoúYïq²ÀwøG¾#)A§Hšà*;õ;¨":¤ð
?`ƒûî	ûD3«ÙYwÂîhZØw[Óž–,¼ÿß“ ’1)GZÑð3ä¾Vjõäæßñà½/ç|üYÚû‚ì$^:Ù½Ê8¿S'yÏJáÊùnç>@)–2™À²ìUÇ^ÑË8(Ã&
AÖŸ{qË’sJ­y!'”ÆáËRÓW H¿¶ÊÒöeVÉã{p#« Ê€CþÚ›Xe]ÜSðÌ}º½mÓ}NQZOÞ=À²§Ïå |É@ùÚRïÍÓ(ª£@• ¤íÛƒ€6)âiÇqk¿7$˜U¬‡rÿ½âFL@üëTÓõý\QìòÅ ÎH
m•^ñ7#„É£ <rn«VÐ1!dšV5ôÆ|Æ4þ¿$Q–I„À`Y²tVôÕÈ5ºôãØ}Äµ—Æã1Å‹$E‚º°¢Œcå-‹2ÛÆkÛ@9œG(ðwüK\œ<u¶i ’è¥ùKí-yÔcì™&$#B…(>D™Ý­U<@7ýc½3€YK7,Õ²@P5—º‰K:õãâ,0eŸé«,K;Ã[å|AbŸ™d½ä,ŠOñ ·Ê’’àÖ¿Ûm‰¨¡bK}làW¤˜óØ`Z:KØ7Ž¥[–šv|ÈÝ«VÎ.Ñ‰%™ÅR(6ãèL°]&ˆ^>è‰ÞµÌ
+ùC?<	r&(ÏŸJÇÒ,KzVns…êºÛ3q»íÐÆ6HôÞª)»SbRgÃLK]TàF&;°Óœè„HbCÏÆ"£7šVÐ“G÷q£Ü|H9åìñ^Ï4Þpd lL=†¬Šèq…¤e™þEÊQÃ’6ÏI¤bj‹ÀjZA1õ;uh“^š\÷ºWöµ¬z}ÑG[Ggƒe÷	“:`9C†•?!O²ŽOm!XMËçž­–£Ín{øDB»Øæ·D»ŽÇ·ŒƒÙ´lØ¦ CJ.C qîœo6-6)È¹+	³i™°EA]Q”5­-
Òweq.iZdêöÝ±ÛÇvß/ Ç±Ê,‘
òÄ§ýò3KtŠßúr%²=Aûò8—+‘í	rÚ¿ ‡b%²9AŠL«H‰lN?ì˜1oÓ@€gDùédÇÖ!©ÈªäX¢% !¹]<$™€ÌÀjGVÉ^~A¶JÔµ[V*¡,™P§Xxïîâs/&Ä‰F¾Ï—ôùì[îë{ÏÎ«ûáU^ï"¥›÷4ðyö™»(Ý8¿þAN5«lB>wµÐm¼{x7ŸšU6!#mC¼y›OÍ*™{ZV–Î¨×ù´õ’	9Ò6FC	Y3-#¦fÂX*•òy)™jrƒg±4Èó‚ë•ìË›ž.ÿ’®-ZŸÏÏ'årdr4±¡Z<%6X îáˆ{¥kJÈ
ŒLÑÃV+ÔIbÓÅn‹ON&$"Cr©YåÒ0®7˜L§“÷Ü\I²|óž{Ãétz5½‚‘µQBVæØ¤ÞðŠF±tÇÑ-%ä/óÙ¤èû“ÉÎdbM€}úp7›åŒžãôâ€HBÖýõ¤TB.LJx²;ÜÝ…pREàŒè&ç0œq(=§ÙD#<BryÌ*•xÈ"&!­¡à¤eN“â˜œ°9#”Î`½3»•BH.5«lBÖ—“˜/ „ÄB¨0·Ü:‹=ÄD•¬Ìœ³Í=Æ£^o •,&$Q¤‘ÎHûMQ²pÊÌ4Eu}@‡!fMe0)	üˆA{9ÀŽ’*ßaÏ9ä8žAöØKæýOƒàAQBÖ Kn+Ü§„-—,Â XVí–mƒ¢„¬I8®ç…€ç¹äOBl0à3?h N,š&JÈz›ý¸hÿQ‡:Äu7 ÔQ	3¢„¬Él¸Uó»Q]\D1Žj>.CÊ\Þ•	b[µ¿{4Ïø’„¨YÅÙ C®XX®®n~ ?A@HÀ«Vb½ÊÌâ1‚¤6n]§Ýú×ÑÍägÅÑø“°ë•!™1äŒHñ‹W,ÿ·Búï"b}n²ugõãÿ"þo";,AÜt!çmj$ù¦XŒ²õ!Q£æEàú «í#ÆxGbÏl"ËgÈÂ£LbdŸ±êA¿ã%#œ÷ÛnúºmÛ§Mýøì¼TéTScy.ÿõÕ®–F&˜ u·Bæù"u|Ð¢BòÍÕäé(¹	2b^Ä2ZÏ}ÈdòaÕhÅêôOÇâŸÂ7rÝÁ¢…UA+ExÊºX°‰KÍoh}š.Œ¥m`~X´¥wú§2¨äæ Ów‡)J†<8«“®–N†öïèu·ß>xBæ¹yßiƒì#–mÙ…è![
*DøÈÒ? /+•ÊåöËTàÒöö·ú.5bÛ…xÊ*¾á1ü]\¥`$û‡|¶·W©ì!ðHç•Ë!æˆEØÿ¼‡l3!µ__‰_ÌœË«Ó@Æ×âój›”8i¾ X‚‚ª(¢uáBï* ¢ ®Ü(ˆ(¨›v¡ît[‚˜ØºS®P
Ô…ˆ‹"®Áÿ¿3gÌ¤IoµÑ~“NfÎt\œŸßœÔÛëCK©AyKHÏ¤ŽòõˆÅÉ|Ã5:oÚ_¯^¥‡ß]´ù¿;dÙ½òïã—·çÏ’^/©zÂ	<Žß¥vQÁG’%a‰ow`‘:6lÙ=5„|¿{½2K2šq¤ô“Nüxñú.6ÈÙ¯Gþð¢ÞÂ 1?N¿u>´rY…9ä0€œÛ¼ýáR@<\©wÕxšûD±¢;Dc@&‹+rvÿûdƒÐÏÓsÙ/âQ‹-’Èòâ Ðél‡xÔFª×÷iî7b’NI Áó*®ôXXóÄaY4²*áÀÖsêoœ=z•ìš‡óYùÌ
z±œOi¿!€£ÝÁ¢ØgN<”¿K‡% ¦—èu»ƒæHhñ+Î¬†<³výÿo.ò‘uõáO·ŒtzzMq¿­Xuq‡|Ô‰×`Ò1rvþAˆv»Ý^È<Êz};É¤°Oêì2¡x™bzûñÄ¸ûÇ4 VbN5ýŽb‰I—= Nè«9¯ŸÎ¬ÿÿí÷û/ÏAw äÅ9Þ~ÛŠÉ!ÅÇv@ZìâšÃã‡¤<ÇÅ@ðœ%-’Ha™ÄÀ¥ÇZ©ØÔöMòƒ³”¬]¹´=rHÕtMTè‚Aˆ“CÔ?  {$‘ÙqÈ-dM'O5=Ñ#]5¦µßŒ&®tãà§ßâˆ’)„Ñël[pˆ—÷ã_}QD~È„ö·9K@¢lJj®ãé˜Ok¿Ýt²õy<  @÷kÈ‰ë ÄÅrL"àw
¼„ªŽ"Ò ‡üÿß1¼¿W›@V®äOk¿í¤¥Âú¨“ŸR²®ä²ìÞ`à›nÕ# ^‡òJ•«úï ™è¼þ¬Ý”@æˆV:ÉKJ-Mi?¬lYÙN¸A+Z‘=ìR©äâeõBÈ/•ŒîÀ,yËP×)‘ªÇOü*"ÿÿrÐ@ò¨"[B›Ò~áLÔ§4† áH‡4–5!°°ÃÐ•Ë—Û—»‚×$Æo )Ò!›	È‰3oŒ™*È$™Ÿ·çN,vÈ\™“núHµ$ÂP”ì.9¤c°CŽÈª õœ'VOY3D8“õq{>"–ãƒ‡V1@…ÒñxéÈYDNç²¬¸§¬›çH×Î7fAé
bŽ©"õßtHY
Ud  e ñ0ñÂÎÀ6±ˆëÈ£yUDòñ(Ð!³Änš Êçß²ºVVªú7³×õq³h°PDä™UÏWBŠtÈ«™¢ 4ŒdÌ¤3ç=®l G% RC+—¼ÐÇ°êw{˜›½Î ÌKd>?…:äÒ¹sÏ{<@ši7ŒuŒ¡ÁèqH¹Æ" µZ¹ 4ó	æhµ#›¨ˆìÉ¤¸§,vHˆ­ï:FÒÓô{§°_hiwÄ]ó­¥@ÄïàÑ¸Ê@ÖoPªÙa¯¶aCŸ7Ðírˆ/qU‡Eò=céWI_ d4iv\ãm'ô÷ûm+û¨Ò±ÅzL)¬ÓÊ!« d=¿¬pàCƒNØƒÂËayÃz4¬y$‹H£žË …:@ÎÈ…EÎŠ_Æ¸ñû…#“ïÊf*¹ ÀS×ŒäÀ")%Cæ	6ìv;¬ËÝ*‚,‘Ü@þ…C> È—%q(‘\4IC¬2ªõIÒ@ÖEY/[‚ä…¡']+jë×«Õ× BgÖò?,,Ô!+ŸÏÁäªæÈ(:2Íhøtžzže ë ê+8N+¯7ÉO"É@ £H‡0­Îü 0ˆJ»tBtdé¦–qs>NÒ8Æ@æÖªJ_s0@!.®ê ’Ã …>eí@¶DÿÈ*÷+ñ™!šÈ¸:–Ã’Ñ.AÇkÜÃ"l rˆa©¨†t}õÂœ¬ê 2ÈŠb²‚l¹»$‘Ï£J§Ò¾…À‹ò?|×B¤T¢wíÛ×Ê‚]	Z˜,ô}YÊIš:ÖâHöÑxE" kæ¤ÖÉ—ÍE=:HÝ#."ù>÷I]9d÷Ý3Âf‰¸’÷ô{Òûýw;µÚ=DdpŸŠ,ôT @Ú=$ÚÚû”ÚÃ&Á`¹Zü6Â—¾“Fh­ó „äÆQÕÏ=6Ñ!@Ql¹ý|+´ýáûxrÓ &YjoagLï|ŠÛ‰Þ ï|×5úû´ZCÇÔ¨±xÞ€4€$dõhæ­Pé& ªãÁwùò‰ÉYCþP·_Ó$Ë%é¹VÖÚ°ÒŠ£}Aï"[ô·´b<›IHZ«~Œ\ARcÛ³ç˜"«IstÍÕ|>²ªj>Çë¯P96	HNýqQ×@ò$W+?œ@LA,ÈS*°…Ñ— Þû-ea hºÒ)­wC¿´[˜§‡ó”Qh#ó†òluB5c€Ç,“!i¡ˆœ:zlÅòL:u9d¹
 Å¨ÒWøA¤H²ŠÐ»äåºÃ9E£â;n¶ÌF†"‡<[•$2·¾V[?—ŒÞ;ÇVdó(Þ!÷ŠÂZCŒ<F#0/T
Š<Õt×	LN-æ²j51a.4IÏ©»(4Ó!HXá¹÷bTØsŽe¿=Fä¤÷Žkº§°CòÈt¿eÁ·€®2®^œþ±óµON²â_8ä°r§( \ÓÛý è·‰C¿7H0¼È^±~Õt¸‡´øÍ\HªO3xÀ!ÈšU9´æÙ•ƒ'æ	Èò¿/ hè y9 Öñ`§%_9e:¼˜‘%KG`¹.È°ÚýŠë–\­ñcóiÖ‰E@ž<[»F!Yƒ&5f¾öÙ•'TÕ—Á"yýQ€C4ŸÔ?hQÇçP]ä4z&§T¤(8T0¢X¡Jˆ‚g‡6ƒ¤Ä]ü°`EÅ?Ø1K¶(R§(¤C…’AŠÐ®2¹88¸úý½wÏ_ï%¯¹žÞ%~ï¸{÷»ü–ßÇïûÝ;šx/‰h~bµ¦Zó(¤ÐÔåäu-íµËJ·^ó²0cÁ×[Ù]2I@VA¤¯|\]}†¥á$€¿Ä‡CÈTC±±…—­çS·ö„)¤¨ÍË†Ÿ9Ô=Ý‚R¼YA Ò`øbÐVVê‚GìÒ!“SnùÒêêÇþ±	Ää˜²à™á²òÉTsÛˆAÏ÷¯Óaù|…ZˆjøvrÑëé¢Ö‡Õ»Z—0 †aùcì‚i@ÆÆVhl¬”+ú§,¤Ø˜18Äî·íæÕ¶—Æs”¨¾¯§¿¡›Xn,Ëžn	¡1/Â ÔX9Ê#ƒÀ0 Ú(Œª…C.Ÿ9å±ÃoòÔ—=½u(¥žz—ay†äÍ”·\ÊX$#_[€6ÑÈ‹gf T«˜zÈÂÉS â4¼$]Þšƒ†Þ#®íAÄ[3&-[ötk‡Ä+”€ÒV^‡~>…U¡[®ÏrÉuŠÏ! ¹vòæ'[¡iMÛ‹+âbÊö^œ s §ËŽÑRZ´ö–|…b%òARs¡r¹Þùv;(Ç†§¬î1ôçH¹4gG!ùÚê«\Bà€¦N±ÖšŠpeÉž>-+­µ5¼ˆTÏÁå|÷qC )3`þˆÄ!ÏïG	Äÿîf,[®9âX–ìééÑEp`‰ž\~"’™ÞÓ	H×JíG‰ˆŽ¹šþÊ‘•å$jéõôÎ,²ŒTˆFÂ@:³ÛA[	øx¡Cê¥u;ÉÆÁÕ—]Q¹ÒšÎŒZÜÓ“êÅ	]30Ó–ê!õÎøF@ƒÔ©§3myWÁÿÞ!^§ãÝÔÚ!N¯­¼¦µ’{º•Æß>•¥Ã µg"ÔÛóÙ<irªV+ŠÇ¬Îíí þØ˜á·½l˜ršÄ©tšŽÉUÍ¤ì0”IP!,9,DŠ@21>^ÙMããã:ñ` š=bì!_Î\„:HFl;•ô]gø¬}JˆóY»ç;V_ÔÆòlÔ(3W§óÍ Üª×‰ÍX¹‘‘àWQ8„p±°{›v½Sâ/ÑUHŽ3hùS¾ÌIªC†b›ŠÏ9úxëƒH­(šÉ®*—]·X#¹ž­ƒqÈ)ò­´î+°vV_à8Kå„Éw¨€&(Ú=´î|vI@„G€d7¹PqŠxx@ö	}C.J 7ŒßÅÈ°T!{Ý‘ŸäJé'çû&$kîÖ<y¯•'å„äÉ®o)‘ú}F—-p~rô`t²n~Ï‘&%Ì\&Õj  ’Ï¿•(çÄ'ñƒª•'pˆ¡†1°qB`Ïù0H¤º1ÿdssóÖÏ[êˆƒ<rv4G¡ù|"uÈ'ñ{! ’"üûïrlOùà½ÖçK¥MR[ä‘w¸Õ.æ~4¿,$ €´CÄ/„TK ³ ‰Aø†íú½™™g³vj7è(†è¸ÅÂ¯æ;8C
 êÄ~áBµä$ã–Õ£x¦¢Æó…f*P£ÂÇjå­¡Ì1p‡³gÄ1/¤ ùsX’W+ãPƒ•j“(9Ü!ûÎ}&‡d«wcb
{Œd¸§Åç3‘J£
p±ùå«úp8äÜç³ÐÓÆvRÉ¡]5L|mˆÏí*2I€Ø{>4Ó¨T±Wio¼:‡²›C^
 9ˆÃ•ãGø#Ž·1 gùŽul‡ô‚÷º§_‡Ê‡G¤=p¼úùå:Dy ª`z!IãKíŠ/å[]ÿÚŠ«9€?.ŽéN¶ùrø’øÄA­¸ªšæ¸âF¢³Šã,¿Û z“zÜS×Pèü9	ÄÍf›ï‡Ð!ï©Æ†±ˆ\Hn: Rð|È’ÅñŽ¤²×˜‹.Ž¦¬«Y2|Ù÷¾I?d T1UI±±d¬ö–P+$5ï…Ï‘êD6;¤¹.€„¥•5É[¯ñ x¾¥—Î\D3”ðùÛmW ¹>„¹Þ<xdæqTóÙ0™Ï@._Ð!á ·1ïÄ'rT/*)@¡¡ðù¤L@NHT
ä2~Çè|¬@ÀÃW¤òÅMgè/ó$\Ø•ÂY8Y€Êñ1M+æ‚kã¿ÌÜ®ûÃäžcÔµÿXGâÐèQ]æñ£!r¿P¸P(— ÄÂÆÂµÜþS ×|s™ß%ó&†HA,ÅBI8ääq$sÔ( é5êO2 Ìÿ	ä7wçÚD…a·ÅÔÆ.J £ˆ/Ô.+º©thâ•‚î¬DÑ¶ZŸTQPÁ…V»ÒÝJA‘b›Bk—.%še,Y„$váî£'ít&qêèÔÿÞ;sî¹÷dq¾93¤Në–Ê@6Þù%ï@n¯kj" gV;ˆ!y‘=˜õ_Ë¸ty7ÙH 	È7¤<ÿ£2Óõ oñ—.ÈfüÎªÒ`)VÃjKÌ¨+4ut„ƒ­iÛâ!ç²Ë=ó¸ÖÍ%Î 9$Õ¾‡}KûdCP45}â
±Êî5lÀ´Ø›gÀS¬©:“°Y1™ýK ÙD ãvBÄÒ™SÙ·êp”óO–¯ëmÌã1ÐÑ¸@øêeq‚Ý×ØöÏ²m<´$ù
dBe[dÑâ;º­´Ï‚¡Žèšœ©],G8%Ô-‰<_z|Ðl# ÷¯NÔé$“è=Lø¨KiŸò‹”kÎL‘ãm2£F }sñYt<à@”LäR•
,œD'CP€K, CsÛ˜™I3¯ûjé2@èŒÒH ÇË ¤©´›«u³4%t4˜rÅÄTõÒ/×dü\‘m£@æ*dg$g "dRÒ5e¬­_f_Ò…¶¸Q@Ú!ßäÈNB0è¢¶d7©cºg9ò¨_ëZ!#-¤ ik@Lê’‚©‹„ìÿˆþ` mDäÙUËH ˜zŽ[Ö2äÁ·¬ˆ6Ø^õ}äH@Ô$Ú –‘! aY’…Må>ææ Ûbtabp†´mO Ý_}|Ä->¬€¼ÍÅR)ŸßT £äˆb‰á“ÖÈ²ìÉâ³’ƒ©ñ ò¿Ãvo6žL%“q(Yª	¨s"«óWŒ:%«L®‰ýcñ 2ù@2…ô;©\*Y
ZäÄÈPYËå\û[ƒ•âÁ#âIœä?/€ÜË&sï´RÅM!òH‹Õ	´ðŸ-bÁ œD-²8žd¹ÆˆHC“'Q 3(­t1úMù¤QTÈ‹‘!y=GõåŒ4c”¿Bß‚=¼§-ž>¡lO4$MâÊdŒ*äää×ùï9™:Ñ.¯eÊŸåT È.HêF?€ÜkM~:¿(.t‡—ÎX.ñP 
dâÎÈÏLv&™»½SÍÃCÄo (”·r¯N¼ôóNÇO@HÄ233q­TNà€Ä)íážåÇ'Õ¦µõËìKºÐãúL2•J§Ó¹ÜÑ£7XJ.YÈ{¨€\‡«Ú³yØ€4LžM‚„BaW.Jùê¿ údÿúE€Xs–±|¬%
aEÃü2ý¡ãQ1E@X7¨•éhLâÅR•…â‡ø¡Î‘Ã“W$	c±·6¶Ô/Cõu¥°ñùý‡®³ÛkJñô<,ÀÐu’N¢Pªbâ–—Ä@Rµ¡MyPK^ Xh«O’‰Ñ	"±m¯ñÜ{|ÝÔôùî¶ãïÄå(‘^ÆÀ<Ø"P¸y« âÓT! ÂÙ"‹3Î`ô™IˆÆ›8€ã#‹'}þ­7L}è9U¤šb<Ý+,n`Ò…Rm¨’|òäôèÛe ©¬ˆáIóòÍY>³¼Æ_¦{Nµ*¢D’©t:'ˆ0˜^;˜tr¶g¥Ç»?@ÖI -+B½zE*îvÉ­[’Ù£§øèÔùvÆÑxnìZ6ž$‹æQ††Õ›*äÛ»Î5†\åó€„-”è²•ç]/YröeK¶yñá¥)â5Ðšêº pÜMœ~ÕÜ<0ð2’~ÅÁ°ð©Ù¾µ#á^$þ ùA@vˆN´,Ýh&š¦“rÐ1"W5#tRÇÓÜŸtWŽ·Þwl<V¶o|Þ<°wp°¯¯opß§~j$i"BƒÕÛyè™Ž°ÖS]ÝnEâÛ
È%‘ËˆN-SuÉCbMîdVX¹²àX ù72¦zWÖÑçÄ¢O0bY"ÒßßK]ÓHjÅyQWÛ»»ž8‰@îÿûô‚{§Öàj‘kb„5$3Þª¬rð{Œÿrþ˜¨Ú±fª›à@ŠÅ†c±L<%y ‘À#®53;ÞŠHèÀ±¯E²beÈCc 2é‹)¬R¦ƒ>«½l;)¼J'ŠÏÎl{!oñŸ§ÛˆûUâÖ ªÒ0X /³ BRLÀ#›²Ð§æ¦D-‚ÞØÝq×Kf=WÈ6úÈ²»v³©çªcðË=‘îâ1Þ˜ê–½Ý4Øwð`_'aF' tÌ&Ÿö—)5“Ž2‰n ¹m´Qi=Û³?äA+¼`ãÈEU!º
Ð¥¤¯ðó,i³Ñ‰%Œò«ÜÍ³lk^â?¿>%è£ŠGggçÇáX'1X2xˆ°ÒqðÒš½Í¯­â¶ÕÞ±ÿïUÈ¸ òìâ‘>Ýìb/»ø¨-ÍK®É³'Ø¶Ïkü/êÍ.¦­2Œã×ã[½÷™™8½ ÔÎ¨pdÉ:73ÇÑÈš0
š!‘hV%DÂÄÍBÝ»Ä4@L“Ž]¬&„°%¦sq£YºR-.æ.¸óÆÿóž÷í{övÕŠ;eûŸõ9ïùÚÂóãÿ<ï9§.D"`áõ"\¤àE$,ÒùÊ(f°	oÿpÉKµŒHûbÿ£rgIY|S‘YT®ÚËýa"aDsÂTeOªÔö®/­Ûh¨2*Öt –Yð®]úýÒ÷lùù>³(Àæ ž·|{‘b·»8o‘@ä¯·ê„G{g§IÐ˜B„8GM^nÊîš=¹·€iÖæqx½Þåe`@”T äžAƒ"*³MHT4^·|/VPÝÛ{b¤(SÖ8äýq	þ‡¯åH(»?$’QiÌmÌ[ë¯"‡×åp´,/{‰Ã2­icí€@É½¿SºÑxC¬l­Ï²’†ÓýEùqÈìûãÐ =6Hã@”^b`RÀ©KvHuaQ±*= â–[äxæG"tïþ;Ä‚™G4FäXª8Ý™;K2{„IþT
IJ09D¬dÝR®‘ËÎ¹=qŠÝ¤÷7"ƒÀà€$•Š-˜€üþóš8€äç@/·HEžB@.3 HoJÃ"ÁˆM3©d§=QL¥d·ñp¹%èW{—Ö–™¼²ÑPl9äéY, íugÞŒXãq„K&\ØJ9A¦_îRÌR²Sz.Ö~° ~qwOàã`7º˜@‚6°¥AÉl44ˆòÍÍÈÝ'‡ž+IšÆ(iç 2³m¢a¶‰Ô6R¦loûúÛ«GGöØ€#ìî„º(WšMàþ%|'ˆW¬ûs£ñ¶`"ÀØ´x|sFÍÂÌW‘%™»L@®Ç!Ý»¤‡ídA¾s<1ÚQÑßìd0ÝÝ B!^˜ˆ-ƒc²†gð\—ÖÔF#†-HáQ¼®ÊƒC DöÈ6_R„,R²Y&ÇbÉ1û¹_/'½îž/’A¨{°«bTB¿LBS^l’c" Âuÿk—kÙåP	Ì2 §ÜÃEª,x–µ‹	*¹|ÂÜ)©-S×$™nó¾í__·ÚTµøNr+‰zÕ5"X!8Â“¤¶M 1ã¸ð½­z°æ¡¶/ª}øÔ!ðbïêÃùp€@/ £@ð‘R7JäA}Ædþ2~á³'ôÑ ¶}´³¢`ñðÙ­`kßX_k+p0O„f&'÷ß@ºD mÖh7C.…‰yÅs¤—¿‘¯Í‡CŠ¯¯®ÌâÃkß ésL6â¬x~T©	ÌýXî×ß^ÂƒÞÞždªµµ¯¯„±ÐL[	»º  pI ÐÅVÓÀÆÁ'Êƒ‘@ù°ñ<«)á@ˆH-eaR‡ò4Ee;£ÒèR;›4L>d¢­­%+Ö…cÄ„À¸ÈòÔ$)ì`€ G|{FŒÇYVÌ²*¢f6“RVIçì$g¢«Mô¦p¤²55ß7F,±	-´1Å8 À@E3„Š] ¢¶o"dÌˆJ<ü,€#t#’‡H Jfå@="è¨ž ”Gƒñ5ÅÚÝAÍOÀD°Ûb<†6IE6šM=>:´	<¢ŽÅ1wöØæê	ˆ"+ÒÿyO¢æúß<õª4*¿×ßÔ~¿~~ÞïGÎ‘÷XÓBTØFAÀ°g ËŒ.„à&š/“ÉäÇã½ÛÛwåÁ!HëönÕž(eâ{¤Šš¸¦%8?…¾ØP-3Á†¾.DPäÈp¶»0k†Epƒyh¶ªÝ­|!È‡× p e|ÞÏCöµ<ó>±k'ôl´ã|HýÕÈ€a<XìŽ1My±#•H$Rˆ)?5!>Ž°dÊK-‡×5(˜< xv’‡ ˆ¼ý¹Dd9¥Àó‹À7ä"bDÄ/ßE;¥ÍEšSK1‡°0È€´MNÅ«íN­ººÚé¤0 ÙõÔ<ë2®)¤EL˜)lµ¹›2¾êkÅ}H­ ",™z!îÔs­õ¬²!·1¦%ç«Õ=Ñ[ÇªŠö6-&" R­;u=E8èÓ•••»[´_ Ñœöj„óÆ–„ŠZØEx„C $Ä»u<;ÉƒCj› }(þYï†%¸,ÇÕ,+IGT1äp=Î-¹Çêè_­«+Á	¤þÄÝ»wW dÖ :²­§Œ¢•
Ž•Ñ¦ ƒ}À)¨0@ö”w¢yqoóðMÖó©ÈÕ]Åê4Ë‡¼ÔHÿ«¦«k’êõx.×—D—jÊkf¢%Ï…Ož×¥Go/NYôm@ìš^m§"•ØÍÔÀ€nw@À6`¡1 NïÂäi³uÀðS³÷'ü¾E<€?zb¸Èr‡ŒüI@¾Ç(J\®É&åºOÎ¦^±Ùzv×Äb5•^Œ=#¬+¬8j”¬Þñ¤ît2 Õš#´†Öã: ‘3È#`$ÁÈ;´2ÚF@ÄL€B«?a¾6Bó„v""e…C¬BUEH&EÙS³_A¬|Ü"¯--½f³U®FÅõ r£±€«ö8ÚµSsBv¤ ˆ
°;j¬^`HULœ@IÛ‡Y¯xè‘«`LD:›víCd=áÔ²¯Žå¥Y’%F–}Ù¯.¼Ç&Õ³gêy~@îÜ8_°Ï{YâtL¥àuJ³Ã	ˆh šoëë£ÔdÂcó\ Õ[YòÁ¦NÓ{CîCŸ_3ýN‹Ýœl1Fk6‹eÉêÿU]ø›IÓÍá:äÆ§è!K{ Yß­‹zÅõ§6 Û:;h€¡¡•}›~-‘"{Ð'…©³=8ÝËm‡ÿUå®·Ô!½¯ì' g¯‰Ò/’N±-|¥.’>Y*Dt †! 3ÔÔ›XÍÚDÎE[O×+;urš:êÖŸg´ãÜ9¢àÑÅñæþAäÔiùZÄŠ;õÞz’L2I®”¾À³¤£,JVBd5Zj ¹óíÂÄÄñ"ã?†¶ì°…
D6ð;I—Æ<À´Ùƒ·¸B/XëLáRnIm5yRY´üˆí!7Ÿ\Â}	õO!ÞÙ‡›#	;’O}]·ë<Û™ŽÙF?‚#N§ø×{Y!ÌUÇ^—¯E¬˜eõ~¹D®œ½Æ~R…€B9”	€ÑÊƒnoT2/¤-Ò¹·½óäê*"‰ôˆëdJ9e[´u4DÄU1”+œÍLÔÝ<ŒG1'66jÜõõç_¯µÔ!‹_‚"U&£*ÙÈå	bO¾Tkž¶™‘4mll<õjGÇè3‘ƒëA»ÑÖu‚È6Ð`æE[˜›ë˜¬WÆ!æBÅ¢¿«“Ýq6w÷‹ÜYÒC @™È?².SŽäGuaÜ‰Héh4ÔÞù‘ƒíåëAÝDØAdlœF[§-íá:f\¢³»B¨¾¡c©³¢ñf®Ï|d;Z|_aéUI‰Ãb½³z~ª¦R6öò¦ÆG©j?¹ºIïzº]ËzEœõÜ¨°¶® Ù
ÌÉ–¾r¯©rÒv! Î¶>ûté™ØRÉ8’I·ˆ¾zëÜÄ´/žÐ ´us}ÒÍõI6œDÒˆ’ˆ ƒpU¿qãå‘\S»M ŸÃ×ß}êôÛ5K»Yáz©A9&‰€ÉÍ[“çÎ¬Nû<I	F¶ÙŒKÓÁHÀgƒ|H0¡Î»tš—¢–ˆTö²9ôé¥¡OïŒ¾zªPÈ ³ Â¼ýôéðš¥òiÛø‡¡Aú^ž<wîÌ™™»ë>O<¸•Ð©­dÜã³­Oo¬N@3Óë¶ á’@ôt½‘ø‘Eã$õô…;£îþB!k€\Þÿõ)Ëvzm’ºï¿^ÿ7ùæÒâ4†a·šæWxÃ§b…‚—QE*ˆ—RTZJâBLÚ\ÁÔ…¨#^Ò‚Åªè
^@Ñhƒà0–ºPATÜèÞ÷;Éñt¼@M[5ú|'Éi±ä™÷|§™ƒ‹g7—-3•ÈÔÎUK™€pâæ‹oß.pñâÕƒ]`8àøeèzù"ñµÑÀ
{Kxã±4ƒáW^½¿y|=]âLˆ¸?¿=âúÓ[ÅE%ÒëWlžÛt4•ÇCd$2!0ð€±K¾üþÆ™gïèÓÇµSÍí37§™Žõ>ÿÍ„Ô%QwYoOâü¼¦¡«y.ÈˆÅ!‚]TÉ —Ž_¯n,ßnBJâõíëoç8j°¹×ÀÖB]BÈ‘‘YñgóŽŠbç­X\¹‘±:„üÔlt(¹ùùíò3Ïn¿ùôéÓ»ÛhúËÏ¿¿ãY2Å£Å{ÒX}á)IÐ! ¿å0+ö¬X¼Ì1mUÍó ÀHkll¬…2ÕÈ	&„1¼‹"ÂŒ0îÜ¹üþóù·AŸùü¾yçÖ-g!Añ}´kº$„ ŸZó{¹wá gIÀ°–Œ¬‰Èi×ñ4ÒƒÎ¦c¤ŠÅ…^²ZûFÉ.¦ä
_´xDÈœp`xudƒë@><·ë–NBâÂámˆ4¦rà¡ÙrŒœªr%9ËðœŠRÎÂ|L1BµƒÁšºð#0
+Å9Ý}>b(„è›8b8–-RtÏðÉH´\é€ï{‡/1%wPß>!©{b%äÂ „€éºç¤ÐÝ9Ù¬oxz¶LJ„”cW8,^0<>#<"ÌH´x€¸	ÙúÃ‚ÞÑ;t•'DÍ–}ÅQ|RBN2²`|x|ÁpèóAF&E@ž·x<º'VB¶o%6ˆÁ #Ò#C.|MH¶\.z¤„9áRêTêÇÔáƒŠ¸4~éÒ-ª€ÉÉI£>Æ9KH¿F…tÀ¥@HÏ$lÏYj#01Qñ´|N …€ª:¤`@HÈ­ñí¶¡´Æ°·Š+!·~O¿„€Œî@ `@y˜p*ž‰àÒÒªÕkõ¯pT<íÒ¨æ(ÅV«hÜÃm™	ILÀÎÎê‹±öI	àà¤b˜ºšgZü*Q,ëõ¥uÆäxX ÝnŒª–ã¹÷îßwLYŠ@Œ„ !;qÿó@ðÇF¤>-ðBBxF`NHJÅSLÝÆ‡ã3Šz±Ý.â)5FGsÐa'¤èÄHHâðŠ?a+„ô‰é9l³@d„@?™ðœ
pKÓsv¾ÀP9˜ÚšëT{ºÔ1"Ü7x!@Ö¯*Œ@I’>SI%Ãƒ†ã†ëZÀuà9tñ‰—½;Ãb„ó>
[à…e&D91‘ª«Å0M3™tÃ 7ÍŠ<ÏpË±d©7b&‚>¡-°á3#\	‚‹†nnÂÃÂ°Üf…B‚¹¦ë¹œéôš‘¸
™ÊÎ>¡¡¤6QÃY§h–b™M3M§â²ú
¡y=öôX	Ù´'°‡!ý#J94 ¤êº®'+¯–yÐæU\šS>ºáåþ“]	ùŽ½Týl#•Fj5è±mJŒŠâT,Ø9lsÝRI/•J¹Ýu´6Zqj€ŒD$8ÕªyBµí”£Øšcä`GºçÚ¤Åé©µÇOÈnWšNÈÕlH¾Z«dY¦7Ê„~DµíÈÐP¡04¤ºnŽ(å8½µöX	YÈ@}Ã@„QRÜ„d3™Œœ‘e}B—eh1T™1dºX¹8%”éG¤hÄPø-B†ÜdGB
™õD!™’3 gh™€’[ÂBÚàÍðÖDkíñrˆB3!²!eR«Êëz2Ï®MQƒIÎm«64LE3"¶öx	9ôvã„D(ä³5=H§óILØ¬Ô‚Ì¨Òh|#D/i^ÓÌH¿N¬„¬<ôöhÉ*R®•™…L1%§Cr®NÓ"fZFC³ÚS•>7£´öCÈî
êéLˆ´Ó 3ÈÑ–Êd$Óžl¨ÍsÜ)JšW¯~žíh²ô‹üBÖÜù![Voøžst:°mäÜ?%õ‚H„äIÈúj1“þŠ½TM§®Ï”&Kj£d8N§ïÃU°¨Y1tù—ºû_(dhdSw\û:[½é‡ŒI= ’EOgB|ei*eCJA3Mea%¤1Ùmä,×Ö…’vóéUÆç™ÍŠgéÞ	’ø«Æ½«9â~ó	žCvåç)ÿ>dãÝ„!-êél­²‹)…Rb+—jðˆdT&Ä4rRFƒ’°ƒ@HÈ9Ë*ŽkwÙáÿ¾„œÞB7½Äë·œ–"#’÷©§§ä”ŽsÁ´Ó­YÛíQZ³4Ø—II›ò”9{õìÙóów`ñ:ÕÕ·„üñLðA‡<²zÀ	£{Ä+Ä#ŒYŠÌ7éû´.¤°Ô'-¦~4rMÛ¬’ëâk…J¼O‰Ðãüâ^w=þNýï!ñx{çMFaˆê;ÄÓß‹ÜþXD’Åûtøð—HƒnÊ<"™Üdi´Ñ°ø§%²é4?<aBž’®äÆ2]ê!ìôÇ'Òš«úÊÇ5R„ŸµªÌ’Iil¦ZbÍj<"!m4‘¡$ˆY·h‡c]0m†„8ýÑ	†”x¸zIÏ¬ê˜oz9ÿR%#YŸ÷t`'Uv]ßnË|Í}ôˆ„xšD%FæáGïVw>(0ƒÆŸÌøÂÞùó6„a|×Ÿ¢j‘¹ˆ?*2 1)K¥Hž@Ñ9›­8öÆ„ÈÀ+J² L‘š	1Ð	ñ	ÒØyÞó¹—âŒsi¯(Ï]lÇ‰£È?=ïûÞÙi_=U§uŽÑÕ¶z¢Þ+¹÷P^¿\¡Y–S«¿xA@^Ü;Îéë<ÛÚogYd$€´ýÐµæ‘tQûvž¾Vâ»H‡¨v®ýN£N=èTé•Óí{K÷Ç/eNÆ;ãá~Ì~›œ$$Äã~ö
V*vÈéÜÎgC¿ª:d9äæÍÇMYaÕ‘Ñ¥šmÉ’È@¾û‘uR,ìu§Ó®¸€XÅ!Ùâh,HåÏŒ|$zSb7‰@T£
KÁ}$“ˆ ’ä«¨¦ã…§g J;DÑ8W“ ëoP¥äÛú ÜmÞv£z»·OˆMöÛ£—2‰'"«Ÿ÷òùÏcVu‡|KU«{¸…´Æ‘>,‹ú±àýÉd8ÁïôS¶ ™;ïêé¿:M­Î‰‰i¹áqî=©×ê7yD—pA '—¼‡Ã8´sQÏÊ;Ä[˜˜C~Íóø›ˆrú©RT(«©ÌÒå3RÈ²’HÅ£ $ Ö=N·5\BÃRt¥—.<Òtw$¾V‡œ$qn¡ª¢ô;Ä@Z@NjdæäB#	d -‡äQüîXÄ!}	$ˆlˆ1ÖÂj‡‘h)™Œ04'~KYb‡X”YÙÂ$‡°Ø¯åÕ	Ñ(ƒT‚€]ÉÇ)ô^]-ZeI <  ŒÇCè•®	ŽI’ˆK@t:¤ Ýº\ñ0;Lî0"i|•j"L¤½)>Gg·aÜ8Ä²Ü 	$ŒÓÒ' _•† "¢€h©²rÙU®ÏÐjeŒCpmÆëÅ
ˆZäës´c‡¤<®³ÊRQ–G78‡@\ 	j‘ÙLx $v[ò áºÖ’;UyËó‡±9GµŸ€Ä-º‰H$‘Ðïõb ¡"p( |™sYÅa]ÿ°ã¸åÈ÷CrD?	ã^Lö 	.TY;Z‚V¦ÊÒÄB=1¸ÊÂQ} 	xàfv× Ä´½0áT·Ç<äE\©û‘î‘z~S?
µ8Õ9„Å	€x¾ ˜t‡Eœ¨åÅt»—|'‡¸‚‡2ÐReáQp½Pv/li7Ë!nˆ¬Î9±  éGm/	]Šf± ‡\& jrQ‹CJ6t=LNecÜ\êÞÎæ§€ûƒÀ!6‹Z"b‰i É,B×pÇ“ÃCŸiqHÞ¹=ÚI©Tnâ‹w¶o7§!³‹X%_³˜eÝ~9Þ?<úñã(¶—5Û»Ô	zq™e’C¬Öt·ÑxveýÑúÇWvÓeýV; ˆE	Å,"£Éû£#âñcºšRì‚}•lQÙD‡ÐaƒîÝF£±ÙyûºÑxýhsãÓG?éq¢„ëè‡ A’@‚5­)f‚¾(“"êy89„%×äÖú.xHýì9iÍ›}N5Ä³–1ùk¢¶À„z±ýÐÍrˆß­9 ïÖ³ûE£îÁÞ1’ÃC¢2å–^‡üspùG[nè\–åWž»è·Ÿ°x¶··'x|–@º‘µÄ¹¬¬çÖå*.ÕŠW…v´,3byôou¶R *bÉ˜ ¤HÒ×å<Š””˜­Êï¿ sY¨{ïoÓ¿ù¾F<„®¨;Ü[ÝXD9BÜšùL“CÊ}ã’p
ÜQþMrH4}Ôh¼]¿“9dwC¥	æ”ÁU¯F‡ŒÿŠvþÅ(E{O‚¦ŽÔ)soM¤1±DÌÚ“DR ASCÊF£|+w\y—)j#u‹ol«Ï¤C®ªˆ˜õEÉ€øLC*ÖBdQ8¦)½ÌÉ!nHUÖvÒˆ¥Ä| J ÊÒ~o/ú‚1ë/ã‘’y\£ªšnml5R"?»ž£¡ðý"|>×ÌÓCJ†§òåXá=ÿF“ª,—£lÆ»[dÔ¾Å®8ræä!fA 2û0£eì0fÛîšµ`•uAd•‡ˆKƒB<¹ÓØÝØØ•¹ÚK¢,2’ r0)FT*:äâ 9­ÙŽRv§ÓÎvj­^àœTËŸÎ~fDºÓ„;ó²×ª}…9¹©7‘^÷X>w~WÌ½.y(1·Â7X)BŠ¸—Šgé£è­ÈYÑ/„¬´
YKLêyEÎàÄ£P2©_®¤§4%Ç¥ˆXB} hÂÕµò'Q,î"…“€úÙ’¡C6äºÍ¦øó kÄ8­€¦Ã´ò«}:¶aø–!Pöß7.²CXÜ­@0FAb‰$FAb‰$FAb‰$FAb‰$FAb‰$FAb‰$FAb‰$FAb‰$FAb‰$FAb‰$f®CÈ5û²gBÖÌ}È¸çõ"žyy$ãžŸµ¯Ïá¿®½æõ¦I¯X,ÕC    IEND®B`‚                                                                                                                                                                                                                                                   Ð÷ÈDn$rÂ¢d7»K®0A§*fL’Mš™D^Œ„²ù<­+ú	IoÐxž­Î"¬ø€ÑwàX1¦Ñ¬ùü,þâd ¯¾`…üôý……Wí“œœ_2Þ&†ª[„†`rkû¹†]b¸Â1±eôTcˆùµ7Ÿ+îzøsÎdô^]¦tð|¦½ò"Çë¬ìÐ7	\Ö¨`@ißéÓuäªØ-îÁõ±Îø5âz’Y'(Õ0‰“*å"y6”vÅæØ=_Á*Dµƒpú/È¤›
=ìIž¹Ñ%(F‘FA¯÷Yñ/Œøé¦]7µ>~°O,šPq	ÄwÔÖÎÆ¼(³'0S["hÖfBµK/ö¬;jF¨Ét	Ðå	oïÖ£KëQ‹£‰Îá¢Ô»»S»;x²!›,í24V•’ÄÁ§ø¯
y=ô¨”€Ê°¤ñgµ”ýv¾ƒn¾´D­¹¡öÆ–˜½”7ÈqãkñÔ9i•,c¡«O{ìÛbiïD¿Ç/p‡áÁŽæÖ{Àâì®RÌë•%.ÕW;‹ü‹ú±À¢ZØ+È`$ï–†õï½í¶r@Šðøk¼“ƒ¾Ï¦ëŠáW#ñÒñJÄÒs’tþ1¨#åŒšïà~LM;úKe>"ÞåGÅm­€–Ï…‘ÁÔú Ï©Ë±¸·¾ÒXõ"‘#!JmÛfêt7“¯RÖ¬J$3gv+òƒ3ÄÁ¿¥6ePÈ,ÌÏ‰ý'‹«Wa¯4AC­­ÛÐã‹S{ÎÎ€¬ßh(²?TŠ8zõ÷£„p²ZÙ•|$Q.yÊó@êhÒ†. …ßáZÍrtO–Ë¾žº4™èveÂÞ> UÅþí#öÒ#ê§X²îEMà¨=s¡ëÃ|Ud>7ñCæ°¾A…²Kê öÝä£ÛÄFSHðPè/_«Dð~>Ôù"l_¯£„„º­ŸòÏŒ¬e'#§FMcjÿe¹à]Nyºâ;«¸…åÇ]zÀñ4¦%c+›%¦Ø–(•¬{ŸÓú­×¼AxkÏ¡NXpéôþ0†ê<•Ò8Dçœi .äOG~ËØIÁ’E]‚3®£à5ã])ª¿@Žùº…³q×WúZþr¿e´äÊzž<v}‰kT•ôd.«0||0°×oÏ-ÎU1ètFü®gðÈ·Å©÷D'o¢«0k­è‹nm‘ç¬·&p—§ðT…~’8‡¢ X'”€±C6§>‡žØ%ÆušÛÛ°áJœ›–•oŽßXøJ¿ä?[d/²Ÿ}Ê-Aøô„ÆþçönŸããàq0&nâ(	?Ÿ–ÿ~ê(KÆiÖ®½"¼VñŒºþ.ó's‚r7÷¥	nîÚù™Žj7÷ÐÕÞ–ˆö.²é3ç}k‡YW‚à£lu_p0V%ÐlAy.Î˜*Â08q­Ž·êcéÎ/ªè¦ÿÅQµ×ˆõlfÌ8¨êI_ô“¦L{ãÜ’“GmqÒx63´Á;½	äVNÃsDþuô%Ïýï¤dÁßo¹SÈ,õ­9>=£QÞÒN¥•E?iØÐõ¼ÄPÂîó@{`\—Ï:3[×ç—ÿëÑƒ`ê1ƒ|Ñ¬®@ÓËQ´ëUS^ å¡j3z á:Ã£ˆ ÓŒ„7uŸ¸µqµÓú‹ÐkÎGeš³ñ¿Ÿæ­•pLhÓè\È
rÙI’þYxï`ò]uù©Ärv×Y5é‚èßÉ(QªjMž\`Ó¶¨ž3Žvâ‹‹XèuïSƒ*×Î'¬¢/ÆasZ¼&Jzä<çŽçøºOûø²ˆuÑê{ç(‹Š¾7°lcBÈÌ‡CcÒ>8L È‡Œ¤†;Íë‚Ç,ÿíZ\ÅFÐVsñW0å9ýÂŸCüwjp}ÛZïö×Šú*dH½ÖŒÄÑ&L<Rþ& ”¤{2³AÉ;Æv,¸ÉÝÃæœ$2²óvÉë
Ùq,¨ÅˆfWœE·U«˜"G·gø¸n«:9Íþ«bÇZ€‰I:U§áKQäHt—Q% D×•w1¨âF`»ƒ²C@Í9}@ƒU¦ÒZótœ…#žª¹ÿ‰ ©è}’ãöASÕ¸Ý˜¨‹4[K^=þá°C=ŸÙ©™+áõû»'ýù»òýöãŒA¢K|-Zjõ"Èt ©?ÊhµõôÇ–¸PFD°ë”è
+¤gJ¹³Ï”¿Ä,xSË–Ù'ªOæ®_ŸwÝ(T
­µSîÍW™«!.jÊ*i1"ÜP@e€ÁÓPD6Pú<{ŠGCáœT™Ò“¼ÊjB-Ë\_›ZJ+G†?†­ìp$PhC
0ËOTâ.eˆ¼¹Zí†x oîöûGÇ^	¿$uW«gUÐPi¿vÛÒ33º·g>žö9^óP¨l&Š ÏåèY[4%ûx*¹ÝÔŠVº¦"­1ÔipûÜeÎŸ}?çÂ}dbõ®ÐBG/K¯²”’…áöNÕ:k;rQìÄ½Þ?ç¢d®¥òéÓ¶V½ÎÇ:Z
¸'e‰Šq4pïWôìµ¹":£få;ýëV©.½%Ñ??M±¿ŸfSÛ:ò¤
lÌÞ}©v¢‰;øKÓÕXÛãÀ/'=<	SËÙìÇPŒUÓRGPÿÅ
ÞÛúÈÆ|Ä0ìMy›qŠüõÛÿîšÄúê,XÇó\ñ²Ø;pƒ£…:0,ÛwQ¨|Äg'êÏ'ŸxŽÒÿè¥3¨µþè‰ÄÊÞ>I‘–A?ß@ú¾ÏC;“ö¸1ß¤ŸªÒÀ´p5NÅ(¨ïf´)‰Õã¨’06d`Mù~o¿ÆS—b+Rð„,ËiMaPÜx|+ÄIî+K|ñ]anx…{á¸¾1þ¦ã\\,Ò!¡_OÞ`ëKcƒc6yàßf6È¤s·Ê:0è±ëƒp8
`œ÷öÆ`:ÅúæÚ†YŸ?HxyÑ²Õ JrÃ)Üø6;ß…ÒÒññØ,¢Ñ&Ò¨X
‹Ë”{ƒ!ì_^£sH—[./ŒtÛþ…æb³êÜ«•# ±ì1Xo†Ýðü^`ÉúŠñp­„ßàÕ¡;ê.NlW[ÿ ”ž0ß¹Œg©ËÀ[Íµ´Ñ&Mn=¦<a
¤Ã*‹gu¨y6{Â4Ì¿á5=›ôÞè'î4ÏAà•eƒKâwOLâî>9Ø¦¿†'ð7„jÏ€ê¨ÔŠj±õ”-ÒxD*,\ƒÓ4»«[kœ×éÁj ’þ)$üQü”v«xçd&ÿàš³ƒÜbZ˜?ì¼qÄ³ý³?6JgzŠ„>`}¡‡ÿ«XJEsKÛžDŸí\g¦ú7-ÏñÕS(ß8\ÔG©UÓK¬ãÎÔåkP±Ê>êq§=…ÇLà—›CýâÓåôqº‰_f1Ú³¥à¦¾;
N_ôÞ/£¢ Oå˜ïO‚¿þŸÙ4¼Ät€Ñ¤b,A»«:=@Ç#á }­nGxlUÉ¯z~U6cÕULq®PØlf)H™ÎÄÕQÓÂ)+h£(-Z¿ VãKÜr,ÍÖšÃ¹¥‚h¤i_Ì»húpŽTI‘¾ºË2¯3e:§¦©‡¯|ß.ªp²R‡(Äì„‘>CÝïÈEé3 %5âƒ1«¿!¶ëËì¬&’jû{‹‹¥.’›óÛNìs".¦Ì¦…’ã<ø¯ÏÙ­ÞÑÃG(P¯ÖfÝ:þÆœjõc“Â©ÛNñû¾¶aÒuPßˆ@®„©B	:u`ûY…XiGë[¶Ýì§`=e ï†FÛ©tÊ¸§¥ H^‰ ôÎ¿$ã¿zE ¾`›"åWôÛxÇÚ$ÞLz [.à]})
åìxùýá6QÏz²Û“l‡¨¹›¶õéÃ:l`60t6CMU’P½§Ê?¾PDêy É*Ë£µ¹W‹ª6÷ò:ƒQEj/‡0~ ÿÖ}ˆö^ŒÕ<cQ§
`ß…L+;‚ö*úFøˆ¨ºÆ}ö*™mOëåûBº°I‚»P,3cÁÃ®7‡ÚøÎàzò¯ybI´ä³»î·±+Xv –[ž:ñÙ0PVb}Ý4URõÒÊ§§cw4ØY«Þ¾IW"AîäLÒr¦ò™ß€Ÿ»Ôÿ{Š|Ýæ¥Y9û"´?×vÊMÑyús ùïÜŒÜQÝ§:ŸþßÌ~îíð1ã!Ð]BOApGövQÔyxæ¼lÄ"_ À%šå	ÉO"¤s#JgÑˆ¥ßøL€È¶BlY³é?ë(Õo`I˜Œ{ø™óNu|™ÌJO&a´#?sZÿºN[áxÏ—¯X¶àÊ`ú\°ã üØÙy³ÖÜkuì?ªís‡nÝ]´«iÒ8Ú`yÈ·]¥õÍq!#(˜Ä¸§OQx"¼õ ‘2ûRhVŸîXnN“³TÇZ.B×ôÓ§…/´Ð$¢GÁŸîKå-rë²Ã|i8èKïÊ:°['QÜcèÝ@p9€^,\‹YH”£+*ÑÝâÆ
_®if¾4	qJ¹b¢¶a·áz:‚dàüß„åJg ZÎp0]D›ÕUÝ‘%4½ZþØ‹3pÍžguŽœè-è£Õ0­sü.]j”v£ÝO˜ `)!·ª5~haòŠ—L‰PNG

   IHDR   P   P   Žò­   sRGB ®Îé   DeXIfMM *    ‡i                            P        P    1àLO  yIDATxíœ]ŒdÇUÇïwwO÷|íÎîfwl¯íX‰ã„lG8(ÀK‚ñ Š’HDAyAÊ/Að°Vá„Â!28	NŒ‚A1!N"G`;Ž	ÆY{½ëõ®×³3=Ý÷›ß¿êÖôížÏµÇS³·OuÝú8çÏ9uªnõzÞQ:Bà#Ž8Bà#Ž8Bà#Ž8Bà#Žxóðß¼¡¦GªëÃÍç—Ny^¹ê•õªç\þªWÕ«~à­zu½&ƒ[=8,Óç jÏ¿àyõ¯†ÖÕ/Ô÷ðÂÜÍ¯¾äû/§Gxs¾½! ÖOÿÝüz§»UŽ¿ê×€â”õ¼3\oá
Ws9Nš¼D^wpšrß¯¿èÕeA%}õÁ(¡J5¾ç—ÐKd_¤è7/ÔX@rUÒt|áÄ¿´nâ‡cûÐº¼þôßü~ôË!"9‘W8˜ä°UBx‚-°…^Ò?éQÇ4®ªÜK7. ÕëÃ6˜.i 5}Ú®šþa¢ÌF°pçÇ~Û¶;œÏF¬ÃéìÊ\³ÍG½ÅÐâ×ÜiÔ]ô¢ÎüTû"ÝðŠñe>÷¦ªíú¥æ!ä£µ²JæÎ®¼ýã˜þá¤àpº±½EúpQaUÆÌdj³—jªÌ%—wõü0ñÂ¸‹«¨XmÑ(™C#“¦÷š4Û^Å®¬¯p\aPd7M…€WŸøâ¯•Ùø]pï•U¶ÅœÆ´•AÇ½%Ú
àÚ«bC•—f
{õ¡ïæ~“on˜{ÊÏŽS=3~—xÝbðufÅ„ëï~aîJ]ÅÑw%`”t ÄÝ´²à¸²éûqï˜niÙÎMË2óòÍ«ÍMµØ“þf}ªó‰éæ5¯,Æ¦ºEãyqÜ¿÷Ó›êáõ¤CÑÀ«IòÕ2Ï»Ue5§,r©‰½$ òFP«Ê;1fÊý0îyAQŽÙ6*fò¢.2;wuL÷2w;DE¹ò¢ºŒ¶Šâ[àf•gÝ«Iç«¯8×ö5c¾®+ÿùÈ³ñÐÆ´J˜sÈ‹
’£[L JÏY°ŒÐ8õ¥~'æjó‘À¦é‹ûŽª.š~²æžÊôP!¦/Ñ<}P¼;9lÅÿ|M jP7à=(@œ­,K@Ú9®uà‰Ú<ÚÇŒ[Iª,Qsy-J^c ©«6¬æáÐ^c+9jòfœ’	žè×ŒÛÐ<?hØ6[2¹²ƒÐ°ÞKßÿó/TE¶l6 4ŽŸ¼qÚbtæSFSîdŽò[Ì ÚPp›
<Sª@:JúM/8Û÷D¤ãÅ¬2waÊË’¡éDå7â5˜pÎ¿úÌÙ·_Ýx².« åÃ`b°Ó_ò’Î@ÞÎ $:›BåMšÜ;ªçØry×vö¾çéºyX¶Ýìö{N™n^oõÝôñÃ ê.%ï<þ¶‹ÏxÞýæÇ¸s‡­Òk`<µÏ×ÆUEHøšÉÃý¿Å7ãsm;?~z~¯ªeZÔà2ZØÐv~·ûªÒ‡´ÄP{%ÓÍ—..Ÿ”~ž%Cv½zÈ6:gžuÜ´Å{|¶FÞ£ÖÌ­Kß;ó›Y6¾]ÌÀÚÎ ?×6Cë‡j3ih‘eµ¾®’ßQ]ò£Ž*oûqÔÖSû0ê5 `âðjê‰6y¹ÓásòÀ¿Y:º]2Íˆy ¯BzòDÎù—¿åx:Z¿@Ìg×j3Våâ°¥Ü`YûÛSÀ,ªxo¦)·—LµÞã¶b¼"m¹ãWMãÚ[¿v	 ÙŒ¢®Ýsy(›y§7¿ú–Ÿ:Oy¿jÊªüné4õþÒ]>ò¿¯‹<6¦À•(o5I)-ª½¢H!Í$ç./sC°7í¤y“{¨Ž©'jóV#kcæV#M¾ÑN«•¶<à)´1”a§›þ+Å¥ù:êxÞ¢eK6ÉèyÖ”w­]¾/€ çdÒ…³×>’†÷)äpfiÌ Su&áh‘Û%ê*iæ´ææÀP?-“jü¡ü¢.ë¨zòÀ¶§ŽîéÒîÆà‹OâAü:žO³¼gãá}Î^ùˆkÜ–Ý•ÍÒ-pfoèû¤žÈ×Å?îWŠ*gj…AÃ¨©c˜6ÑF(/Ú_8Á,ÛU©zóâ¤G6rM·Êí}¾Î$×ë{æ¶é§ÅŠaË«É[§zÃµË¶©xn5˜í_<£É·£ïÃ¯ ¶÷›'Aùä‰Ì0±¯uF­_X
¿˜éÀi—¨.1ä¨òFÛ*3–2È¬ŒÙzn¢½ÓÃdÂ0®€ºŽÚŽ¤ÉêÐ™¸Í2ßýÐŒE‰ù³ñ¨½ÞKd|aÉÿâAMÙ®‡fPÕWÚÒÎN_¾w4Úø¨§Ñ°–I¤ªÏG(í0!%¼ÑH5QK«ú¦Ôî\lÙT¡-³7š|û>EË÷´¥VyyžÚ¬ªä}<Þü(2þ&ÏûŽš	‹Ý´pGœ€wÎLi1þ*³zÌZ·Ewð#N3EÅ¼Âè Šm;ù8µ7¾Îú8å)0W[{m=êšúvBP=;I4õ&Ú2•·¯ fL„/õÝŸúÒŸ(—ÑÈ†¶ù&ó%s['˜ÒIÚU·ªœ8á__âüh¸ñ–ˆÙ3Œ˜E£_!S!VË“i’SZkÚ
`UÏ–ŠqëEMâ†Ñ2×Œš ×b[}ë_övZ¥Ñ›¼¨ºÔØšDTÏ¿>HÃìQ^
øÒ‚YêTäµ–ï÷Î{È®ê¦Ñ.[¬·ï[´Ïíón¿\<ãÅ¾}þ¿†ëk·»·n+h÷äyËK‹„øB%¡…p†Ú’½?]]Q¥™ömÀìíæ©žRØõ®½ºÖNåŽaåÊËFlÿ.?7¿ðìêOŸý‰Ó/z¹÷ìrå}ì)Þ/;n:6½›m8¥ª<§zž¿ðœWßtvù¾þ÷ûOŽFë<–	¦—F>“ŸæÑKóÒë†Mi©îm5µ÷þpuÛ¦>šîô0l‘-pyw{ÄØªáo7Ãœ¹ëÊl·½Þàå›Î¾ç¾™ŸZôü»ZZ(lfAÜÑ©PÎ?ã'ÉÀû‹A§ò²åcïø™ÞÜ`#€!™®.Ÿ—¸Žš|Ø”A®ñˆBñmœ0MÎ~o}Zøö²‚[ L{§~µã˜<<hì­<÷Ùÿ÷RÆÒT{¹¼­/9,ÿ’Ç÷z½þ†d”¬’Y²ëw~Ö» xÎ=D/Š^bR[ó×“¹`~ea}~å–ÛeŽ)ÞP82€Šº|–kûiZ@‚CÔ¶sÔôŽRcûŸ€ÑŸ¨Ækò¢)cª­ãAùÙË‚gÁL:½lþÄ-’Œ’U2Kö	lçZùIé6ÞºåÌ÷¢v|AèñÉu°´¸úbXÕŸ|åÊs0I4/Æ–m	›Æ¯ˆ*Égiêu¢3ûZ?ek;ž,U]	%ª$M™€Ø-àìM"yÞìå›CjÐîaÔµkâòA•ËÇoùä<²i„e‰¨0ôü§ûSf<ËÆî RS*&%ãm ¢¤L»uQ‡óÇÎ<Í©ƒÏ\}ùù?bÆ2|[‘ŽÒ›œPEé{I£e[ Í$ðä$-UŽå™ àOI½:G¯ïðffÖ}8UYÎ¾ònlGÕÆçò"?qógúKgžÎó:d‹$äÝgàWÈìõpaÎ«Åö´ó£müŸªqâ¯…1±päyøaevçOçØ‰3ŸÑcvpˆiÔPgŽ¢Y–²Š‹PÎ”$33#™P§Ì xÊ‹Ú¼úV™êLÚNú£˜P+Õ®ÌN.¡iãÀ•”’E2I6+cdd–ì²=üà”òôlÓ
?lü_ßÊ9ö?7ƒªäÀAÀ4‰*aþ¦G`yõÚÕ…Õºi¥OÉç4R…Nó-l=P;˜»kkÚ–îN»µòêk:µ¿«'ž1Ÿx[RCV|‰ŠÉå•3ôçW©	i)A&dÃYQX™å‡“h+ŒH[â1Òl:7ÕÀ;Ï÷GtÆ_Ic>«âÎÍßôÐüÒÉGdvz²ÒG•—†:ZdìÎ°Ìr£%ž­+Õröj…T…æ2¦mêhÛ¯£êG}géx«ÿöØÆRhkiàÍ/Ÿ|D¼KÉbdB6#£‘™‘}s3ßrºÂäÛS½e?ºØ7ühÒ0(‰Ô´Å±‘]\ºõËK+OX0& r«ÜRé·^¡¸¤ÛJM53i˜<òh1ý5ÔöÃºg»SK“ÕUèõ¦«qgÇVE­LD–N>!žm%”Y¨OL¤INfa ,\ù,H3{gæû(ˆ"·‰K¥ŠAùPvpüÖæK/H@Ë¬@æ¢2CÉgh¡Þ‡Hs¶]Ê›?¢?Q“Wß*ˆºèÏQaí†»ñø>;¶êàqpü–èˆïF²L’Q²šÛø80€½ªÀückû,f•ˆKøÎ{%“‚zqåŽ¿êö®À$€„,Gm^š€Ü“°Ž¶óµmdòOeîÏå¡%}n¶ËØÝÁÂµxló,Œ,Í]2JÖ`gªLM"»5ªt8Ò¼-¢â
kñ½ìiè&ÌvÈüÊ[ÿº¾üÌ§òt<§úÈfR›fYÎÙ¶à_,Pl%êó§Jßó?àmœ~/±vl[úº>—iÍÙÅH§<fuoøå%ïs¿ëûÃÙ–»jà]£kuqzÈnSÊ¾ç\]÷Ø·(	ïc¦Ûq½`&$Ù£ò˜ûØ
ö	 *?[\¾ã+qÒÍÛšèò2[-›J\¨Õ4 3š:1O‹ Ñçá‚7+û¿w¯¿z³÷;;5ØÀû·©/ÀÕUUu²m°IxìúÔ~ÈŒ"Ïëçh![à^FãÜ‚áâ±Û¾GŠÂ§'çäU1®´Ñ8ˆ@–ö9°Qóv’ý†Ê¢Ûâo7ÜÝ„×ßV‹#€ëÅr­NY {UÆ WËd¯.P?€<VlxÄKý:Hë(¾6wì–ol^;ÿ!îæˆÁHSìÅD`ò¤JÆ’QigÑ¶tú3çÞwþÌx1¥û0Fê†uŒz`›4Tl<ÎëÎ0«’a^'Ã´Ç,¤Æ)ïÒó"ÈR¬%/£<¯ü<-ýœ}TÏEùïýzçS½®o\ÐôèÛ¿Mi ÚbE1û_¨S¶Á©0p)u™GU—Ã§ì9–•—åb¹Î´ìgˆ"ÍkÀ«á«SžFQïò`qõ[Q)ú´@‹ô€ýò{ÒFÝkèn(^>ÕÙ LHTBÜ\~…‡hµYUœeUgœV‰®4-£"«‚2C!¸o®²=ísä˜BE,Xjpú¹ú‡Û¡²%[5v×À¦BzüJmö9Ü4W•uFçs 5.âÍCã¨sÀ#õñŽ<­Õbt Ê÷ºŠÂdþùîÂ©îhã¥»ªé•Qˆ0LXRí—¤y/ñÎû¹'/ ÌŽ2€Kí~³¢™†O—QVÔñ8¼¬ŽÙ’”–%@W9¶“S/å•I3{C‰OQ]´8 Ðê/¿^~W;‰ï¼Õ{û~š¸;€/¿\ß5­þQrs]ŒÊjPåwÖáárÒ8ÄæÆdY-#]‚'Þ\"otê)	U¯
£xñÙî\ÙK7¯¾ƒ{`eÍO É|eèáž>úÐÍ}-Çì°àaÆh$ã<pÙFMÆ7ß±ž½mþÔ`1N4âúõjøÃÿ_|ìññùŒ×;Òb ÔÌ‡+©4ã
ñ>u0â=ÔŸ}¹zŒþÇþô³ñ§÷bl ïgÌ/ù~0ë½\wy­P.êˆÕB^:±2qÔŽ‘7GŠŒÜ…±LˆšÓ¯.3ÕíÔAÜ=öÔ¥›§k·Ô0]“@°Ö
ÂD*ð×fÖ¦eqð™„ø<´‰iÍËã^/ïûVçWNvùÄ$[çßwoþö³Ý•¿ý§áã—¯ù€óþ“’3ç©Hñ­šh:ey,ì÷L»ÛŽü ´pf|bP£^5ØÄÆ^™DeADS0hVD˜oÈc‚z¶A¸ªš³Çõ‘7MÞ‡’{+ßãÁE¶´|¡N¨LÈ8jPj±. ui¿CËAs)ÏÅ.Ú“ä˜mz÷ûVçfÁkuã:ûå_è¿‡v>fÄæq¤¼K’jþD>‘½Úí€npÖI-d2‘/,ç7ª<ñKÀä}[”G~”óŒx’uŠ@@[6à	0¢€ô¦´38õXw¯ºI…ý80guÂÓÐZ•iÄP³nmIÄœQH¢&dfeÃ|g2Û'»î·­ÖÓÙÓ'Ãã?{oçíQ–`¤‡¡ÀV¬Ñ>øÙ) ·aC·Û œ…·´°(NÕéšWzA)|a^3FÊ‚*BË1“Ø˜AÐBzˆqB6²E,`ýaÒ;óh$¼[á.àÙøOæKâûNÉÌ´rúhœÉ³+êˆ·	!H’Ë$½õöy#9Pz÷ÑY3¢–Q
p¢F«»E¥qIcÙ«ÃœBZ âïX{¾’)çì”%€Ev ÑË‚FyÙ:†L1¢C~³Öh!ó†Áagpó¿à*yO`×Ì¢šY ÔçàÙÐE êå€‘±ÈÊ@+IÇUœ.,Å»øôVGMöØ‚?Ï	L'Ê¨2 M&<Ÿ _S
¦5p{/{j ª£…JøÂ¶)×EÆ„˜su¨~V”qÊ˜˜qÁ«0i¡P8œŠÕÂºÚ 74Q WÃxîÌ7xÊ¹@Ó%vÜ–y+žajÆäjM \¦U”+Æ#¶S`l¸ÕbÿŒ™™óX^…š<XYáüÌt&ôŒ&î×ËŽ¨F-l™òåÕ&1¡üaÞË9@Ì79iI`Ï<ÂË·Ê€è—ÞÈ\ÒÀÀùÀF•÷Ø±Öƒî©Gø®¥Š™@
ytòfë^Œ4I¦‹ÖéH6~/Bó“ŒŒÕDœ2«¢âÕkî¾kµ;½úª·ŽÆišQ<cÆ¬®ÂÒW„…¢rçî=Ø;{VØÄÑÂ‹¥ÿm0¡ô¼,,+Þ&VÌÊ!ÛÂh!@NfäF^ímÀ8@ÖÃ0H^ñ“ÿ†ÂUî=-€ê	NñÎf)à…µYM°ª`eh€—ûÛøeZøÕÏä¯L5ÚãËcOU/0Gø¥fãëd®hÁ?‘&î]˜[{¨û˜Å‹Å jfæ…šˆ9bšIÅ†5øDM&ôÆ…FJ+k§™€&/ùÑÒ£Ü'¢$ÀålŠ4²¤yZMhU1.Ât\Dé8sf_ü åu\þûããW.¼Tíû›àó—ê«_ûfñ¡#lÅZàC3‰0&omñ%&Ö2¡L›mùi.·Ý¶{˜×ª¢ç•]o”çÄb1oõb€S/ÊŠYsF	²Ì„býb ˆh!>Qšhfé¨ÿ|.ü ê¡Áÿ¬†eÖh›“G‘ÕZßª<ÄYÎVQõÀ?Œž{þR¥ß4ì˜~|©~åO¾R>Alo|û™!³oóRÉ‚‡û0>0@wì¤U¸oWW ’J>ñ•»<-õžZ\.ô3^ÎáÍqT	aFåÇ:6&mÒôI»ÈDTâ“õ“@Aæ=--ÀPë;öfÂþr"Ín\ÜÃoïÜÐ†¦ FØ‚ŸqÄÀúŽïD3™tH' ¯ûÙ?>ýþ{’•{ÞœX9fwU®¼R¿ýdyñáoyhžÕìEcµO,©àohuŠ¼¦´)vú2ý˜wª1SfATá¹­Ó[?Z¼9è¾¼t½p˜ÅQ·ÎãÜë°çwØ1ê l9šïÏÁ,ûj¢õ É{üH®bëÈŸcqÐe-Õ©³µ{Ã ûÉ^ž,C7GUõƒÿNÇÿú­Ñ«šu	;À ¹Ì?Àd—…gGo,Ôµ#c\òÓ4Ð1ÛR«d¹ÆhD.f_íqíäHù¿
>ñ‹Áíw¿Ý?=èy[KÂ_}+wgÒþÏ4ØfÎÿr1¢´³h˜ò¢œÄh„÷Ú˜se&#¢…Ö|“¯ëuTbÛ]÷’¥o–uôƒöÐs½ ¸çÝ½¹÷ß×dÚOCóð‰2]D3æ‹¨vY W›‚Ät2Ô
ÕRMÖªI‚ña‹Y¶.±BÑLa>u>ñáàŽŸ{k¼6/íü¨Æ{èm"K=³ZQ ]¬TJmfæ²½ÔcµÂ¬ŒË[‡ùu„\ç1â±l3êòï¼#é
 x›×Údò$0Ð>óþÌJUåMƒ <ÀôvK[8•Oßœ3È–Þ}§¿ó\-z`Øj³=«ÕÊ#?_ßá=SaÎ^Ÿ87 ¥,1úÏ¾—Ï)	¿Ê)Ô1_ºàd¯&>T@Ö…µq¨™µ=¦Íæ·ïæÀû}?üÜoÞº‰ƒ–8‘uíŒ“S®à@ô5i zžÒB4«™óp1,¥‰Ú½Ñ’O«dMÑD´ÐY©03×ÕÏ½!Í“Ê”‰+®cu×7ÇÌÿOÒú&<ï^3€êk
Äfã¡½äKÙöÒj¥½ä3ëæ’@ÛgÉW*´!¤©ËTqC¦, ÑDy}tñò×ñ†—h;ÈùºŠ oüä³ÞgwêdÛ¬²S¥ýÊ¶é§™™u¶ðäËÁÜÜ0è]?Æ£2ùã8ž#vÒœMR²ÀëÎ'ÁÏs(´Ö¹£çÏÎvÉn6¶äNØ€ƒù äÜ™öNÙÀÐ»m£)Ï$&ð”áà2-/{u7×JI+&-?ïbj¬DºYãO`?	w¿( ªûý@Lòµh³Œ¢dPÄ¼«ˆ£ºŒñ†IÀï6>Êé%¯Š™Xj•ÐDÓÌ’Ñ-·‚Â"êgçðµ¥V°C®5¹–•Zi…ôFƒ'¹ÀQšÈ†6o™9Hæ•€f…«ä2È
†ÏHjúÕóáA3È;AŽÆœ–cWMï@ØS5~Í{3Á;t ÷‘Õ
¿îçà6š8$¤Ž‹(b	EUÊ›;Êb@y“‚þ  ‡ç¦ $æ‹"ŒÄ-`˜rÆ® Ã¾¤ÝŸìãU¥yZ^^_ôÊ7Êl%§K‡ª®ÓmæÌoMtDÌ(Ÿ¸1W…qVóãÍQ”æœØä•ožØ Xã¤(‡óÀÏžÓ­'jÖ¼qDh—K"¸²k÷±Wh‡ÜÌøø¼-ðx—Óþ‡x£Cüßá¦7@±¸ˆaglŒxå™äA”`ÇãÈ@‡ýTN‡ê˜©bÚvbÍQéhIÉþNZ•)àu¸²¸Òk†2íVo6xbošÉ6Ã‡ßDÍÎ«+Ú„ëƒàz'å¿ÅâtüˆõZL´x~Nñ¦%2§›8)WeoÄW)Ël^té¡6yÍlû&ižƒgŠIWx˜tˆMˆ£_ h"Lz¾€ÔqÚp#t c¶\›S¼é„˜9-r•ƒ¿ÃNyáß«ø•¹yíªc(o†Ï›Åæÿ UH'"Çy¥    IEND®B`‚                                                                                                                                                                                                                                                 Z‡W¨P±5ucÚ 1  a:yŒòøé}¶oœõŸÐ¤Ä5>á·öåÊq'§/‹õtZNþ+XîžÄÛuŠßz9(1Ú•qÂçœÍ1JŠz­JÐ7ÍXI‰pžØÀs¦4õ¦9Ð’öiQækì´ô“O¦"zùÂ~…ó¶
ï&ˆQƒ+_×—,¨ŸjÃîOyýÞ)©Ûv¸o•­?Êm¦–À.¸è~Yà§ån(á¼¾ì#xî›ð5›WLäæýšH§' Ów|uýƒ\Éþ“ëèn_s×Bï¢{!ETò…Xí^Lê¬úcÅƒtNMøŠÌVB£è€
@ëÔº¬—(I9%ÂzwF*•_ûµô„€ð!€qäHäÁ¿ØÊM·àÐçC¡oGtÐ|øº‹ÏóG¾þ]ÝEÉÏUG¿ /e¤?Ò2Ñ~&€Š´Ûá°à¢ÍµÁ8ìm©Ô\”N93€ðÛÇH•STHV¥XBŽbŒ–d­Éùïq?Žê+Í‹4Zo|–Þ†Dðñýðïr­E[â&­7M›jªU‘8Ñ¥fnÿÃÂ¾w­_Š³(…3Ö+	'JÀÿ àÕ ubÒO&;ŒÛ¥mfö2ã;€nIs"O«µ‚d¿øâœ´ÙýRo>™8ºC‰PNG

   IHDR  ~      )*Q   sRGB ®Îé  IDATxíÛMjÛ@€aÍÈ‰œÄ6n!ËzÈ*‡èº
¹AïP
Í&
½A‘U ·›hM;–-ÍôûT«€[(’)8¯ H(h$=«—ÙD7ï½Ñ!ŽŽŽìÝÝ‘?Ûétâûûûxcc£ñþýé«ÃÃ7Ÿ1/*ÞŠË@ @ µžúquõéõÅÅé—Ùl–IKåÒRùîî®“?yyéôÅ¥§| [åâòµÅƒ4›MŸ$‰—½{|ÜÉÏÏ?|ÕÇsßÊ×pŒ  € <gm#m$m%m&m'm¨yKm«§Fƒ„1´HõXfý´N£Ùì»IÓN¦õz|üîíÖÖ–•Šµyž[çšÆ{gœóf{;ŒÀ@ XOñ8Š¬5Þë­ø8Ž¬ŽÊDÙ£“ØË§ÓNž$C×n¿tó–ò¡­ê)–i«–zuŒ³³3s}}mnooíd21ò°v8ê±mµZ6MÓyøíHðåòçL»E‚•Ÿ£Ê;p- €  °j	=?jüiøÅ~#¯á'³{îááAãO¢¯]DŸû½½=§³}'''‹åÝªK½•güôÊñ7ŸŽtZ«½^Ï?¼žË²®év3“e‘És/ «¶f|@ @à¿
H/E­VÅqä#}Ô•
x™óòMŸëv»®ßïëçr‹è+?pÕèÓ±j™i+‡ŸÎúéÀ:ó'ágdÆÏèìŸ ‘©LYúMÍt:5²äûäÞÌú© € ¬£€Îö•ßKfúüææfñ»ùÎ‡à“¿by7|×WçlŸÞÿI|•è_ÿ:V@=NÓÞâ¾ûûQ¤A¨çÙ@ @ uÐÀ»¹ùývIÒ/bPgø4øô?úMßª¢OÇ¯-¸Êá§ëVžýûu¦øáÇâžƒá<{@ @à9„ÈÓw-ÿxcYð:–yu¬ÚÃkY êBêqØt98³G @ žƒ@¼ò»–—tËçë
¾0æÊÂëOnÌ@ @`¹@ÝÁ·ü.œE @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ Ÿ6w–÷‘Ò0    IEND®B`‚                                                                                                                                                                                                                                                                                                                                                                                                                                                    Äó–(×°ÔÀL›v9jªÙ<áÃ2‚5‚Äþ^ªS£¢ôj\Ò³Ûfzw='fM=èè’öØ|ú=¬Ù±(å2Šâ¬óö:Î¦]ø€Ò)]¨_â²`r=ôð«ÂwOAè/)³Ke¯5Æõ‘Û=²·uú¢‚‚Bi‡ãÌ‚´"¦ùeKr³=†‚¼—…ˆ°þíïÒðíÜ!W;V¨÷…¦~ÕÖ˜f4`éUáæcµâ^÷Ëý-(¨^dUS­"ó©rÔw,•™a[LN„à/?[‘eè2±Õž©-nÔ=»oy·2^RJ7±=Æ½Ï|–ÊbGGÏƒî¢–Á·€Hmªt­WÝÞõ‰HyUTjFÅ‘ß»À>¢"d‹´ÀÊ(„!à3MHBÊñf†ñ«ø+Uôsž_gfëAìZIíÓ k´¿ÝE¦®ÿÔL"­(ò½ÈÍu>š|.x%x&RÓ2}>=ÞX”×>QÙ`ù©iæ“Ü‰¹Né4œjÆÐ(´«‹~ÄVE?ÕCµÙÆÆk»LnÄâ"C£‹g_7·R¿¾Cc÷»9¶áV%Ø5ÅÔ}kÌìèZLÇÐÁß,ÝÂsF‚ÌIpfWâÅCu‚M†ãþø„ÍÇCõN°{¶=²zëtk…Å€}:‡³…	”œqö–±w5på(ARZWž&äˆKÅâáíÊÅñt€c'ë_ÓšoX2­Nð2h‘F€"Ö+a£s¡Žv®ƒfë†cå€yŒïT É²…R¸«ûMº×‚·ÂÂYIe	auN¨sýi§?%3—:ß´ÄG3G–ßÀ½r.“dkŽ5$]}¦3(à
›:Nû¸“?Ã“+ÜÛtwÊ’ U)¯{¸6ðãD°H£··jHK¡ŒQ>Nnò|S"ˆæ_äHZÛyËéz#ñŽÎ•b\¶R‚Mõ&¾œòÅ˜v€Ô«}P ìÿéXnòwôýdöÏTÚR¦Ñ
4ooiÂãñIÀÒ|p®dÍN„ç!­hõ[UK6kèbD4½‹¢…ÔæÔ7ØÛM,¼lÏwcÒÑn¾™=¹ž;×j¡^OïÃÈ,Õ¼Œÿni1µLä¹À±÷½
^#Í„‘äÝfàFÑËÙõ 0™úô%:H·$/x·¯ŠM1D*ë,8¸:’=`—›"Õ‡Kž®§ÊÅ”7KNEÉýÍñ@M.†ì)²hV÷<w³+yU j­Ð£Æœ˜,õÛ*EàY!8x÷8Õ·£e¡ÏV¤2"ž	7|êŒù«M‘©HtIšÑ§üIÏwùqíã ˜FÂÞë¹+¾[îHlxúÏÞU¸Ð@!+¥± E3©3À†äÓHø Ëý‘6¨ïˆÊkÔÍôZ¤KŸ’3W1á™|Éµ@Ê,1²‰­ÑÚn•„¹a”¦¢æbèW_x/nNkæWÏA˜s±Šp·xÆ¯·Øe²FÔúºG›da>ådL‹Iþ¹ôfl•7r˜§ª,[Üÿ5^^ª»+„ÄOp³Ë†IŽ/’Õöö°Npz;W_`-Y£žZ~[îÚ†Øj³²“2±Èk¦ãî1Íáêt×?2Õw]S`¬x‡ñú¯„œCj,D²!‘JšÑ—ºyiÙ0Økú'ø²<"óÙÄÊ^Tûô›¥RoQE˜ÕæÆcM%6nžõÑ“0Ñ·g—¬ºWøêëá‘zXÇk§uQb=DÙ”»òžô—òÔ±îîûÀ¢GÞiuÝ•žöG9Ð\ó"P¥ž´b-}N­”Y¥bdÖ
¾½±KiÑ)ô±ÎÝ½Z&ª#3®L¿W¦Ÿ¨#%ŠLˆ¶¶÷9	‘Tuýyð|</É—©ÿ5´[ôð€Ú$å™Ìèy¤‹`£LÄ4{Ž»8JTÔ£tV¦P« á¢+Ç±6IÛa¾Ð„àBlì×)7XªÀŽ€çYWn·Õ®…²·*ˆ­¡¹òÅXäfð. fÌþr÷ äÁtÏsº(áÑµä.5hóãpÑd¥,ãa ß¼áÓ~ùpüÇófåÇêÚc˜ÊÛkq¢µ¥þ=&RüÎ€§ôærF5í0ÝgQ; ©PdÚ¤4 ùÙu³BnyÔ©sœìœ?¾>œ'iž”«Ÿ<ò¿5A	ièñöÁÔ™¼Èì+-€í‘	¤	ÏÀÇDÝ¾D<ÂKO÷ »ºäRÒ„®v,D3ÐW÷sö¢<‘™°øBÚPó$tBéEûi™ËºÖx.Ç†‡`^°0	j§nÐrø º|á/VÂ©í|È ï}¸“ùM»‹j£²ŠÃvÐ¥n÷;:¯
DT	6Ÿ×c†	¿MÇÞ6„ÆøÑß#Š#UEð} b) €(—ÎV€¹`ìÜ¨]7¾Ä:y›¶ qO<§—ãg w‚§2uº3¿ªš9wPØYêäo²pWë&éé‡=5t§nùˆÁžŠL'Þg–²§‘ÍœgÈ8Ç¢Ónp,bÐ