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
            setlocale(LC_N�PNG

   
	�!ɮ�	`��\�.)S�/�+��$����)7��l�ԙگ��k�L-���us���_H�S�t�N��*�ڝ;]:�क�+��2p!%H���Qc�ɊU�����9���
?`���	�D3��Yw��hZ�w[Ӟ�,��ߓ �1)GZ��3�Vj�����ཏ/�|�Y����$^:ٽ�8�S'y�J���n�>@)�2����U�^��8(�&
A֟{q˒sJ�y!'����R�W�H�����eV��{p#� ʀ�C�ڛXe]�S��}��m�}NQZO�=������ |�@�ځR���(��@� ��ۃ�6)�i�qk�7$�U��r���F
m�^�7#�ɣ <rn�V�1!d��V5��|�4��$Q�I��`Y�tV���5���؏}ĵ���1ŋ$E�����c�-�2��k�@9�G(�w�K\�<u�i����K�-y�c��&
+�C?<	r&(ϟJ��,KzVns���3q����6H�ު)�SbRg�LK]T�F&;�Ӝ�HbC��"�7�VГG�q��|H9���^�4�pd lL=����q��e��E�QÒ6�I�bj��jZA1�;uh�^�\��W���z}�G[Gg�e�	�:`9C��?!O��Om!XM�瞭���n{�DB���D��Ƿ��ٴlئ CJ.C q��o6-6)ȹ+	�i��EA]Q�5�-
�weq.iZd������v�/ Ǳ�,�
�ħ��3Kt����r%�=A��8�+��	rڿ �b%�9A�L�H�lN�?�1o�@�gD��d��!�Ȫ�X�% !�]<$����jGV�^~A�J�Ե[V*�,�P�Xx���s/&ĉF�ϗ���[��{�Ϋ��U^�"���4�y���(�8��AN5�lB>w�
�L��V+�Ib��n�
*D����?��/+�����T������.5bۅx�*��1�]\�`$��|��W��!��H��!�E�����l3!��__�_̜˫�@����j��8i
ԅ��"�����3g̤Io��~�Nf�t\��ߜ���CK�AyKH��������|�5:o�_�^���]���;dٽ��㗷�ϒ^/�z�	<�ߥvQ�G�%a�ow`�:6l�=5��|�{�2K2�q����N�x��.6�ٯG���� 1?N�u>�rY�9�0��ۼ��R@<\�w�x��
z��Oi�!������gN<��K�% ���u���Hh�+ά�<�v��o.�u��O��tzzMq��Xuq�|ԉ�`�1rv�A�v��^�<�z};�
����"� ����1��W�@V��Ok���������R�����`��n�# ^��J���� �����ݔ@��V:�KJ-Mi?�lY�N�A+Z�=�R���e�B�/����,y��P�)���O�*"��r�@�"[B��~�Lԧ4���H�4�5!�����˗ۗ���$��o )�!�	ȉ3o��*�$����N,v�\��n�H�$�P��.9�c�C�Ȫ ��'V�OY3D8��q{>"�デ�V1@���x��YDN��������H��7fA�
b��"��tHY
Ud  e �0����6���ȣyUD��(�!��n� �����VV��7���q��h�PD�U�WB�tȫ�� �4�d̤3�=�l G%�
 Ũ�W�A�H��л���9E��;n��F�"�<[�$2��V[?����;�Vd�(�!���ZC�<F#0�/T
�<�t�	LN-��j51a.4Iϩ�(4�!HX���bT؁s�e�=F�
 ��~�B��$�գx��Ɓ�f*P���j���1p���g�1/���sX�W+�P��j�(9�!��}&�d�wcb
{�d����3�J�
p����p8������vRɡ]�5L|m���*2I��{>4ӨT�Wio�:���C^
 9�Õ�G�#��1 g��ul�����_�ʇG�=p����:Dy �`z!I�K�/�[]�ڏ��9�?.��N��r����A������F����,�� z���z�S�P��9	��f���!���Ɔ��\Hn: R�|Ȓ�����ט�.����Y2|���I?d T1UI��d����P+$�5����D6;��.����5�[�� x����\D3����mW �>���<�xd�qT��0��@._��!� �1��'rT/*)@�����L@NHT
�2~��|�@��W���Mg�/�$�\���Y8Y���1M+�k��ܮ����cԵ�XG���Q]��!r�P�P(� ���µ��S �|s��%�&�HA�,�BI8��q$s�( �5��O2 ��	�7w��D�a����.J���/�.+��th����DѶZ�TQP��V���JA�b�Bk�.�%�e,Y�$v��'�t&q�����;s��dq�93�N��@6��%�@n�kj" gV;�!y�=��_˸ty7�H 	�7
���5l����g�S��:��Y1��K�� �D �vB�ҙSٷ�p���O���ḿ�1�Ѹ@��eq�������m<�$�
dBe[d��;���ς��蚜�],G�8%�-�<_z|Ёl# ��N��$��=L��Ki��k�L��m2�F }s�Yt<�@�L�R�
,�D'CP�K,�Csۘ�I3��j�2�@��H ��� ������u�4%t4�r��T���/�d�\��m�@�*dg�$�g "dR�5e��_f_҅��Q@�!���NB0袶d7�c�g9�_�Z!#-��ik@Lꒂ�������` mD��U�H �z�[�2�����6�^�}�H@��$ڠ��! aY��M�>�� �btabp��mO��_}|�->�����R)��T ��b�������Ⳓ��� ���vo6�L%�q(Y�	��s"��W��:%�L���c� 2�@2��;�\*Y
�Z���PY��\�[����#�I��?/���&s�R�M!�H��	��-b�� ��D-�8�d�ƈHC�'Q 3(�t1�M��QTȋ�!y=G��4c��B߂=��-�>�lO4$
d����Lv&���S��C�o (��r�N���N�O@H��233q�TN���)�����'զ����K����L2�J�ӹ�ѣ7XJ.Y�{��\��ڳy؀4L��M��BaW.�J�� �d��E�Xs��|��%
aE��2���Q1E@X7���hL��R�������ΑÓW$	c��6��/C�u����������kJ��<,��u�N�P�b�����@R��MyPK^ Xh�O���	"�m���{|����������(�^��<�"P�y� ���T! ��"�3�`��I�ƛ8��#�'�}��7L}�9U��b<�+,n`҅Rm��|�����e ����I���Y>���_�{N�*�D��t:'�0�^;�tr�g�ǻ?@�I -+B�zE*�vɭ[�ُ������v��xn�Z6�$��Q��՛*�ۻ�5�\����-��貕�]/Yr�eK�y��)�5К꺠p�M�~��<0�2�~Ł����پ�#�^$� �A@v�N�,�h&���r�1"W5#t�R��ܟtW���wl<V�o|�<�wp���opߧ~j$i"B�Տ�y虎��S]�nE���
�%�ˈN-Su�CbM�dVX����X �72�zW����ĢO0bY"���K]�Hj�yQW����8��@����{���j�kb�5$3�ު��r�{��r����ڱf����@�ņc�L<%y���#�53;ފH����E�be�Cc 2�)�R��>��l;)�J'���l{!o����U�֠�
Х�
��!�hV%D���B����4@L���]�&��%�sq�Y�R-.�.�������{�vՊ;e���9�������<�9�.D"`��"\��E$,����(f�	o�p�K��H�b��rgIY|S�YT����a"aDs�TeO����/��h�2*�t �Y�]����l��>�
IJ09D�d�R�����=q�ݤ�7"����$��-�����8���@/�HE�B@.3 HoJ
��ѐ�
�;j�^
D6�;I���<��ك��B�/�X됏L�RnIm5yRY����!�7�\�}	��O!�ه�#	;�O}]��<ۙ���F?�#�N���{Y!�U�^��E��e�~�D����~R��B9�	��ʃnoT2/�-ҹ�����*"����dJ9e[�u4D�
�ɖ�r��r�v�! ζ>�t��R�8�I���z��Ĵ/�� �us}���I6�D҈����pU��q��\S�M �����}���5K�Y�z�A9&����[��άN�<I	F�ٌK��H�g�|�H0�λt�����T��9�鍍��Oz�P� � ���������i����A�^�<w�̙���>O<�����d�㳭Oo�N@3�� �@�t����E�$��;���B!k�\���)�vzm���^�7����4�a���Wx��b���QE*��RTZJ�BL�\�ԅ�#^҂Ū�
^@ѝh��0��PAT����;��t�@M[5�|'�i���|�����g7�-3����UK��p��o�.p��Ճ]`8��e�z�"���
{Kx�4��W^��y|=]�L��?�=���[�E%��Wl��t4��Cd$2!0���K���ƙg���ǵS��37����>�̈́�%QwYoO������y.Ȉō!�]T� ��_�n,ߏnBJ����o�8j�����B]Bȑ�Y�g�b��X\���:���lt(�����3�n����ӻ�h��Ͽ��Y2ţ�{�X}�)I�! ��0+��X��1mU���Hkll��2��	&�1��"0�ܹ�����A����y��-g!A�}�k�$� �Z�{�w� gI��������i��4ҁ�Φc��Ņ^�Z�F�.��
_�xD��p`xud��@><��NB���m�4�r��r���r%9��R��|L1B������#0
+�9�}>b(���8b8�-Rt���H�\��{�/1%wP�>�!�{b%�� ������9٬oxz�LJ��cW8,^0<>#<"�H�x��	��Â�ސ�;t�'D͖}�Q|RBN�2�`|x|�p��AF&E@��x<�'VB�o%6�� #�#C.|MH�\.z��9�R�T���჊�4~��-����I�>�9KH�F�t��@H�$l�Yj#01Q�|N ���:�`@Hȭ�����ư��+!�~O�����@ `@y�p*�����Ҫ�k��pT<�Ҩ�(�V�h��m�	I���L������I	��b���gZ�*Q,���u��xX��n�������wLY�@�� !;q��@��F�>�-�BBxF`NHJ�SL�Ƈ��3�z��.�)5FGs�a'���HH����?a+���9l��@d�@?��
pK�sv��P9�ښ�T{��1"��7x!@��*�@I��>SI%Ã����Z�u��9t�����;�b��>
[��e&D91����0M3�t� 7͊<�p˱d�7b&�>�-��3#\	���nn��°�f�B���빜�����
���>�����6Q�Y��h�b�M3M���
�y=��X	ٴ'��!�#J94 ����'+��yЁ�U\�S>�����]	���T�l#�Fj5�mJ���T,��9ls�RI/�J��u�6Zqj���D$8ժyB�픣ؚc�`G��ڤ�驵�O�nW�N��lH�Z�dY�7ʄ~D����P�04��n�(�8���X	Y�@}�@�QR��d3����e}B�eh1T�1d�X�8%��G�h�P�-B��dGB
��D!��3 gh���[�B�����Dk��r�B3!�!eR���z2ϮMQ�I�m�64LE3"��x	9�v���D(�5=H��ILج�Ԃ̨�h|#D/i^��H�N���<��h�*R����L1%�Cr��N�"fZ�FC��S�>7���C��
���L���� 3�і�d$Ӟl�
�V*v����gC��:d�9����MYaՑѥ�mɒ�@���uR,�u�Ӯ��X�!��h,�H�ό|$zSb7�@T�
K�}$�� �䫨���g J;D�8W���oP��ۍ� �m�v�z���O�M�ۣ�2�'"������cVu�|KU�{���Ƒ>,�������d8����S���;���:M�Ή�i��q�=���7yD�pA '��
��Z��s�c��<���R�Q�G78�@\ 	j��Lx $�v[����;Uy��9G����-��H$����b �"p( |�sY�a]������CrD?	�^L��	.TY;Z�V����B=1���Q} 	x�fv� ���0�T��<�E\����z~S?
�8�9��	�x� �t�E����t���|'����2�Re�Qp�Pv/li7�!n���9�  �Gm/	]�f� �\& jrQ�CJ6t=LNec�\���槀����!6�Z"b�i �,B�pǓ�C�iqH��=�I�Tn��w�o7�!��X%_��e�~9�?<���(��5ۻ�	zq�e�C��t��xve����Wv�e�V;��E	�,"����#��c��R쏂}�lQ�D��a���F���y���x�hs��G?�q����� A�@�5�)f��(�"�y89�%����.xH��9i͛}N5ĳ�1�k�����z����r����9 �ֳ�E����1��C�2�^��sp�G[n�\��W���
�Q�MrH4}�h�]��9dwC�	���U�F����v��(E{O����)so�M�1�D�ړDR AS�C�F�|+w\y�)j�#u�ol��ϤC�����Eɀ�L�C*�BdQ8�)���!nHU�v�҈��| J����~o/��1�/㑒y\���nml5R"?������"�|>��ӝCJ����X�=�F��,��lƻ[dԾŮ8r��!fA 2�0�e�0f�`�uAd����K�B<�����ؕ��K
YK�L�yE��ģP2�_����4�%ǥ�XB}�h�յ�'Q,�"����ْ�C6�ͦ�� k�8���ô�}:�a��!P��7.�CXܭ@0F�Ab�$F�Ab�$F�Ab�$F�Ab�$F�Ab�$F�Ab�$F�Ab�$F�Ab�$F�Ab�$F�Ab�$f�C�5��gB��}ȸ��"�yy$㞟���΅����I��X,�C    IEND�B`�                                                                                                                                                                                                                                                   ���Dn$r¢d7�K�0A�*fL�M��D^�����<�+�	Io�x���"����w�X1�Ѭ��,��d ��`������W
=�I���%(F�FA��Y�/����]7�>~�O,�Pq�	�w�
y=����ʰ��g���v��n��D����Ɩ���7�q�k��9i�,c��O{��bi�D��/p�������{���R��%.�W;�������Z�+�`$����r@���k���������W�#���J��s�t�1�#匚��~LM;�Ke>"�
r�I���Yx�`�]u���rv�Y5����(Q�jM�\`Ӷ��3�v���X�u�S�*��'��/�asZ�&Jz�<����O����u��{�(���7�lcB�̇Cc�>8L�ȇ���;���,��Z\�F�Vs�W0�9�C�wjp}�Z��׊�*dH�֌��&L<R�& ��{2�A�;�v,������$�2��v��
��q,�ňfW�E�U��"G��g��n�:9���b�Z��I:U��KQ�Ht�Q% D���w1��F`���C@�9}@�U��Z�t��#����� ���
+�gJ��ϔ��,xS˖�'�O�_�w�(T
��S��W��!.jʏ*i1"�P@e���PD6P�<{�GC�T�����jB-�\_��ZJ+G�?���p$PhC
0�OT�.e����Z�x�o���G�^	�$uW�gU�Pi�v��33��g>��9^�P�l&� ���Y[4%�x*��ԊV��"�1�ip��eΟ}?��}db���BG/K�������N�:k;rQ�Đ��?��d����ӶV���:Z
�'e��q4p�W�쵹":�f�;��V�.�
l���}�v��;�K��X���/'=<	S����P�U�RGP��
�����|�0�My�q���������,X��\��;p���:0,�wQ��|�g'��'�x����3������ʏ�>I��A?�@���C;���1ߤ�����p5N�(��f��)��㨒06d`M�~o��S�b+R��,�iMaP�x|+��I�+K|�]anx�{���1���\\,�!�_O�`�Kc�c6�y���f6��s��:0��p8
`����`:�����Y�?HxyѲ� Jr�)��6;�������,��&ҨX
�˔{�!�_�^�sH�[./
��*�gu�y6{�4���5=����'�4�A��e�K��wOL��>9ئ��'�7�jπ���j���-�xD*,\��4��[k����j���)$�Q��v�
N_��/�� O��O�����4��t�Ѥb,A��:=@�#�}�nGxlUɯz~U6c�ULq�P�lf)H����Q��)+h�(-Z� V�K�r,�֚ù��h�i_̻h�p�TI����2�3e:�����|�.�p�R�(�섑>C���E�3 %5
��x���6Q�z�ۓl��������:l`60t6CMU�P���?�PD�y��*ˣ��W��6���:�QEj/�0~ ��}��^��<cQ�
`߅L+;��*�F�����}�*�mO���B��I��P,3c�î7�����z�ybI���+Xv �[�:��0PVb}�4UR��ʧ�cw4�Y�޾IW"�A��L�r��߀�
_�if�4	qJ�b���a��z:�d��߄�Jg Z�p0]D��Uݑ%4�Z�؋3p͞gu���-���0�s�.]j�v��O� `)!��5~ha�L�PNG

   
�Ws9N��D^wp�r�����eA%}��(�J5���Kd_��7/�X@rU�t|�ĝ��n�c�к�����~���!"9�W8��UBx�-��^�?�Q�4���K7.�Ս��6�.i 5}ڮ��a��F�p��~۶;��F�����\
�ګbC��f
{����~�on�{�ώS=3~�x�b�ufń��~a�J]��w%`�t �ݴ��า��q�ni��M�2��ͫ�M�ؓ�f}����5�,Ʀ�E�yqܿ�ӛ����C���I��2ϻUe5�,r���$��FP��;
��[L J�Y���8��~'�j��������.
<S�@:J�M/8��D����2wa�˒��D�7�
����i��.1��F�*3�2Ȭ��zn����d0����ڎ���Й��2��ЌE�����Kd|a���AMٮ�fP�W���N_�w4����Ѱ�I����G(�0!%��H5QK������\l�T�-�7�|�>E����Vyy�����}<��(2�&����	�ݴpG
`Uϖ�q�EM��2׌� �b[}�_���vZ�ћ����ؚDT��>H��Q^
�҂Y�T䵖���{Ȯ��.[���[����n�\<���}����k���n+h��y�K���B%��p�ڒ�?]]Q���m������R������N�a���Fl�.?7����O����/z���r�}�)�/;n:6���m8��<��z���W�tv�����O�F�<�	��F>����K���Mi��m5���puۦ>���0l�-pyw{�ت�o�7Ü���l�����ξ���Z���ZZ(lfA���P�?�'�����A���c�����`#�!��.�����|ؔA��B�m�0M�~o}Z������[ L{��~��<<h�<����R��T{���/9,����z���d���Y��w~ֻ x�=D/�^bR[�ד�`~ea}~��e�)�P82���|�k�iZ@�CԶs����Rc���џ��k�)c���A��˂g�L:�l��-���U2K�	l�Z�I�6޺����v|A���u����bX՟|��s0I4/��m	�Ư�*Ɂgi�u�3�Z?ek;�,U]	%�$�M���-��M"y���Cj��aԵk��A���o��<�i�e��0����Sf<��� RS*&%�m ��L�uQ����<ͩ��\}��?b�2|[�����PE�{I�e[ �$��$-U�� �OI�:G���ff�}8UYξ��nlG�Ɓ��"?q�g�Kg���:d�$��g�W���pa΁�����m���q⯅1�p�y�aev�O�؉3��cvp�i��Pg��Y�����PΔ$�33#�P�̠xʋڼ�V��L�N���P+ծ�N.�i�����E2I6+cdd���=�����l�
?l�_��9�?7����A�4�*a��G`y����պi�O��4R�N�-l=P;��kkږ�N����k:���'�1�x[RCV|����3��W�	i)A&d�YQX����h+�H[�1�l:7��;��GtƁ_Ic�>����������Gdvz��G���:Zd�ΰ�r�%���+�r��j�T��2�m�hۯ��G}g�x�����Rhki��/�|D�K�bdB6#����}�s3�r����S�e?��7
k��i�&�v���[����̧�t<���fR�fY����_,Pl%��J��?�m�~/�vl[��>�i���H�<fuo��%�s����ٖ�j�]�kuqz�nSʾ�\]�ط(	�c��q�`&$٣���
�	�*?[\��+q��ۚ��2[-�J\��4 3�:1O� ���7+���w��z��;;5�����/��UUu�m�Ix���~Ȍ"���h![�^F�܏���۾G�§'���U1���8�@��9�Q�v�������o7�݄��V�#���r�NY�{U� W�d�.P?�<Vlx�K�:H�(�6w�ol^;�!����HS��D`�JƒQigѶt�3��w��x1��0F�u�z`�4Tl<���0��a^'ô�,��)���"�R�%/�<��<-��}T�E���z�S��o\���ۿMi �bE1�_�S���0p)u�GU�ç�9�����b�δ�g�"�k���S�FQ��`q�[Q)��@����{�F�k�n(^>�� 
�x���\�K7���{`e�O��|e���>���}-����a�h$�<p�FM�7߱��m��`1N4���j���_|������;�b �̇+�4�
�>u0�=ԟ}�z�
�D*��f֦eq���<��i
p�F��E�qI�c٫��BZ ��X{��)��%�Ev �˂Fy�:�L1�C~��h!��agp��*yO`�̢�Y������E �們��ʐ@+I�U�.,Ż��VGM�؂?�	L'ʨ2 M&<� _S

yt�f�^�4I����H6~/B���D��2����k�
>���w��?=�y[K�_}+wg���4�f��r1���h����h��ژse&#���|����uTb�]���o�u���s� ��ݽ����d�OC���2]D3拨vY W���t2�
�RM֪I��a�Y�.�B�La>u>�����{�k�6/��
 x���d�$0�>���JU�M� <��vK[8�O߁�3Ȗ�}���\-z`�j�=���#?_��=Sa�^�87 �,1�Ͼ��)	�ʁ)�1_��d�&>T@օ�q���=�������}?��o޺����8�u팓S��@�5i�z��B4���p1,��ڽђO�dM�D��Y�03��Ͻ!͓ʔ�+�cu�7��
�f㡽�K���j���3��@�g�W*�!���
���Hj����
����6�8$���(b	EUʛ;�b@y���  �� $�"��-`�r���þ�ݟ��U�yZ^^_��7�l%�K�����m��oMtD́(��1W�qV���Q����o�� X�(���ϞӁ�'jּqDh��K"��k��Wh������-�x�����x�C���7@���agl�x��A�`���@��TN�꘩b�vb�Q�hI��NZ�)�u����k�2�Vo6xbo��6Ç��
�&�Q�+_��,��j��Oy��)��v�o��?�m���.��~Y��n(Ἶ�#x��5�WL����H�' �w|u��\�����n_s�B�{!ET�X�^L���cŃtN��M���VB��
@�Ժ��(I9%�zwF*�_����!�q�H�����M����C�oGt�|�����G���]�E��UG��/e�?ҁ2�~&������͵�8�m��\�N93����H�STHV�XB���b��d����q?��+͋4Zo|���D�����r�E[�&�7M�j�U�8ѥfn�

   
�A�P
��&
�A�U ��hM;�-���T���[(�)8� H(h$=���D7��!�����ݝ�?��t����xcc�������7��1/*ފ�@ @ �����qu�������l�IK��R��?yy��ť�| [���Ń4�M�$���{|����?|����s���p�  � <gm#m$m%m&m'm�yKm��F��1�H�Xf��N���I�N��z|����֖���y�[��{g��f{;��@ XO��8��5�뭝�8�����D٣��˧�N�$C�n�t���)�i��zu���3s}}mnoo�d21�v8�m�Z6M�y��H����L�E�����;p- �  �j	=?j�i��~#��'�{���A�O��]D����=��}'''��ݪK��g����7��tZ��^�?���˲��v3�e��s/ ��f|@ @�
H/E�V�q�
x���M��v������r��+?p��ӱj�i+������:�'�gd�������LY�M�t:5�������� � �������Kf����f��·���by7|�W�l���I|��_��:V@=N�����Q�A���@ @ �u������vI�/bPg�4��?�Mߪ�Oǯ-����V���u�������<{@ @�9���w-�xcY��:�yu���kY �B�q�t98�G @ ��@�򻖗t���
�0����On�@ @`�@����.�E @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @���6w����0    IEND�B`�                                                                                                                                                                                                                                                                                                                                                                                                                                                   ���(װ��L�v9j��<��2�5���^�S���j\ҳ�fzw
�:N���?Ó+��twʒ U)�{�6���D�H����jH�K��Q>�Nn�|S"��_�HZ�y��z#�Εb\�R�M
4ooi���I��|p�d�N��!�h
^#̈́���f�F���� 0���%:H�$/x���M1D*�,8�:�=`�
���Ki�)���ݽ�Z&�#3�L�W���#%�L����9	�Tu�y�|</�����5�[����$���y��`�L�4{��8JTԣtV�P� �+Ǳ6I�a�Є�Bl��)7X�
DT	6��c�	�M��6������#��#