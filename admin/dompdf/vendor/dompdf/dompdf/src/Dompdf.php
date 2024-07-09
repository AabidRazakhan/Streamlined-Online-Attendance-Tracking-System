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
   IHDR  �  �   �a��  �PLTE   ���������������������������������������Gp�Ir�Dn�Lt�Ak�Nw�W~�T|�j��]��m��f��a��Px�e��g��Z��Sz�Rz�^��l�鞬�y����͘�����⚨����Y�������������������������7Y������� 0v������gx����Ϸ��w��z��lz�a��l|�5<Kq����W|�#5����������f��FM[���!2z]m���s����������X��𒟺������#4~Wg����������������Xh����p�������㢰�%7�`p�FX�Sb�Yl��������������������������FV|Pd���TKZ�N_�N^����{����^���bp�cs�}��BRx���at�GZ������򊖯hu�':����Qc���������������L������o��x�����������>O���Bco�iy�J\�ɘ5����������������릺���v��y��w�����gv���敫݅�����~��Cj����FX�������m~�]h���ٹ��@S����{����f���<`����֥<��녣����{�����Wx�cu�T_w������z��������Vi����r{�������KTfe�͹�����s����폢�}ȼ����AJ^����-?��Is~�AS���zz��3E����}�ř�ˍ����Ң�e�����N�����WCFL7G�����۽���ҪXٹy�˝ils���s�   	tRNS �6������L�7  =�IDATx��XA��0�UU�������t$��hծ
	�!ɮ�	`��\�.)S�/�+��$����)7��l�ԙگ��k�L-���us���_H�S�t�N��*�ڝ;]:�क�+��2p!%H���Qc�ɊU�����9���=}�Sr}8C���Z�©V�2���&����Qt�n,C$�`+�'�Mk�0��C�����G��q5D�h|���7��*��u�2�Z���}�&VɰG7$�T�o ���e�2H�X�I|��*�Ԃ.�����P7�Y��X �K�L˕��*��X׎�$�	C��Nv�����SAiݱ�1zHU5ɝ������q�2�E��6Xrg�]�̵A]JT�a$5�l�̯�߬�P�W"C�I\4C�q��I}-����(U�jD1:��C�v�a���E7��m]�=AT"���	��A�3��*�K0; Џ3)p�Hx)��ȗ3��UH��'���V�bn�w�?8��x��G��#��G��Ƈ�t��~�c��sd�Gq�s�{Db��x�X����YƉ�^r��c8ұP�\�fp�y/��:	/��W�|h��>br�c��zb`'<����o�Y�q��w�G�#)A�H���*;�;�":��
?`���	�D3��Yw��hZ�w[Ӟ�,��ߓ �1)GZ��3�Vj�����ཏ/�|�Y����$^:ٽ�8�S'y�J���n�>@)�2����U�^��8(�&
A֟{q˒sJ�y!'����R�W�H�����eV��{p#� ʀ�C�ڛXe]�S��}��m�}NQZO�=������ |�@�ځR���(��@� ��ۃ�6)�i�qk�7$�U��r���FL@��T���\Q��� �H
m�^�7#�ɣ <rn�V�1!d��V5��|�4��$Q�I��`Y�tV���5���؏}ĵ���1ŋ$E�����c�-�2��k�@9�G(�w�K\�<u�i����K�-y�c��&$#B�(>D�ݭU<@7��c�3�YK7,ղ@P5���K:���,0e��,K;�[�|Ab��d��,�O� �ʒ��ֿ�m����bK}l�W����`Z:K�7��[��v|�ݫV�.щ%��R(6��L�]&�^>����
+�C?<	r&(ϟJ��,KzVns���3q����6H�ު)�SbRg�LK]T�F&;�Ӝ�HbC��"�7�VГG�q��|H9���^�4�pd lL=����q��e��E�QÒ6�I�bj��jZA1�;uh�^�\��W���z}�G[Gg�e�	�:`9C��?!O��Om!XM�瞭���n{�DB���D��Ƿ��ٴlئ CJ.C q��o6-6)ȹ+	�i��EA]Q�5�-
�weq.iZd������v�/ Ǳ�,�
�ħ��3Kt����r%�=A��8�+��	rڿ �b%�9A�L�H�lN�?�1o�@�gD��d��!�Ȫ�X�% !�]<$����jGV�^~A�J�Ե[V*�,�P�Xx���s/&ĉF�ϗ���[��{�Ϋ��U^�"���4�y���(�8��AN5�lB>w��m�{x7��U6!#mC�y�O�*��{ZV�Ψ�����	9�6FC	Y�3-#�f�X*��y)��jr�g�4��땐�˛�.���-Z���'�rdr4��Z<%6X ��{�kJ�
�L��V+�Ib��n�ON&�$"Cr�Y��0�7�L�����\I��|�{��tz5����QBV�ؤ���F�t��-%�/�٤�����dbM�}��p7���匐����HB����TB.LJx�;�݅pRE���&�0�q(�=��D#<Bry�*�x�"&!���eN�☜�9#��`�3��BH.5�lB�֗��/ ��B�0��:�=�D��̜��=ƣ^o �,&$Q���H�MQ�p�ʐ�4Eu}@�!fMe0)	��A{9����*�a�9�8�A��K��O��AQB֠Kn+ܧ�-�, XV��m�����I8�煀��OBl0�3?h N,�&J�z����h�Q��:�u7��Q	3����l�U�Q]\D1�j>.Cʐ\���	b[��{4�����Y��� C�XX���n~ ?A�@H��Vb�ʐ��1��6n]�������g�������!�1�H��W,��B��"b}n�ug���"�o";,A�t!�mj$��X����!Q��E�����#�xGb�l"�g�£Lbd���A��%#���n��m��M��켁T�TScy.��ծ�F&� u��B���"u|ТB�͐���(�	2b^�2Z�}�d�a�h���O����7r����UA+ExʺX��K�oh}��.��m`~X��w��2����w�)J�<8����N�����u��>x�B�y�i��#�mم�![
*D����?��/+�����T������.5bۅx�*��1�]\�`$��|��W��!��H��!�E�����l3!��__�_̜˫�@����j��8i���X����(�u�B�* � ��(�(��v��t[��غS�P
ԅ��"�����3g̤Io��~�Nf�t\��ߜ���CK�AyKH��������|�5:o�_�^���]���;dٽ��㗷�ϒ^/�z�	<�ߥvQ�G�%a�ow`�:6l�=5��|�{�2K2�q����N�x��.6�ٯG���� 1?N�u>�rY�9�0��ۼ��R@<\�w�x���D��;Dc@&�+rv��d����s�/�Q�-���� ��l�x�F���i�7b�NI ��*��X�X��aY4�*���s�o�=z������Y��
z��Oi�!������gN<��K�% ���u���Hh�+ά�<�v��o.�u��O��tzzMq��Xuq�|ԉ�`�1rv�A�v��^�<�z};���O��2�x�bz��ĸ��4 VbN5��b�I�= �N�9���ά�����/�Aw ��9��~ۊ�!��v@Z�⚐Ý��<��@�%-�Ha�����Z����M򃳔�]��=rH�tMT�A��C�?  {$��q�-dM'O5=�#]5����&�t������)���l[p����_}QD~Ȅ��9K@�lJj���Ok��t��y<  @�kȉ� ��rL"�w
����"� ����1��W�@V��Ok�������R�����`��n�# ^��J���� �����ݔ@��V:�KJ-Mi?�lY�N�A+Z�=�R���e�B�/����,y��P�)���O�*"��r�@�"[B��~�Lԧ4���H�4�5!�����˗ۗ���$��o )�!�	ȉ3o��*�$����N,v�\��n�H�$�P��.9�c�C�Ȫ ��'V�OY3D8��q{>"�デ�V1@���x��YDN��������H��7fA�
b��"��tHY
Ud  e �0����6���ȣyUD��(�!��n� �����VV��7���q��h�PD�U�WB�tȫ�� �4�d̤3�=�l G%� RC+���ǰ�w{���Π�Kd>?��:�ҹsϝ{<@�i7�u����qH��" �Z� 4�	�h�#��������,vH���:F���{��_hiw�]��@���Ѹ�@�oP��a��aC�7��r�/qU�E�=c�WI_ d4iv\�m'���m+��ұ�zL)���!� d=��p�C�N؃��ay�z4�y$�H��� �:@�ȅEΊ�_Ƹ����#���f*� �S׌��")%��C�	6�v;���*�,��@��C> ��%q(�\4IC�2���I�@�EY/[�䅡']+j�׫�� Bg��?,,�!+�������(:2�h�t��z��e � �+�8N+�7�O"��@ �H�0���� 0�J�tBtd首qs>N�8�@�֍�J_s0@!.�� �� �>e�@�D��*�+�!�ȸ:�Ò�.A�k��"�l r�a���t}��� 2Ȋb���l��$�ϣJ�Ҿ����?|�B�T�w���ʂ]	Z�,�}Y�I�:��H��xE" k�����E=:H�#."�>�I]9d��3�f�����{���w;��=Ddp��,�T @�=$������&�`�Z�6����F�h�� ���Q��=6�!@Ql��|+����xr� &YjoagL�|�ۉ� �|�5���ZC����xހ4�$d�h�P�&�����w���YC�P�_��$�%�V�ڰ���}�A�"[���b<�IHZ�~�\ARc۳�"�Ist��|>��j>��P96	HN�qQ�@�$W+?�@LA,�S*��ї ��-ea h��)�wC��[����Qh#��luB5c��,�!i���:zl��L:u9d�
 Ũ�W�A�H��л���9E��;n��F�"�<[�$2��V[?����;�Vd�(�!���ZC�<F#0�/T
�<�t�	LN-��j51a.4Iϩ�(4�!HX���bT؁s�e�=F����k���C��t�e����2�^����ON��_8�r�( \��� 跉C�7H0��^�~�t�����\H�O3x�!ȚU9��ٕ�'�	��/ h� y9 ��`�%_9e:���%KG`�.Ȱ����\��c�i։E@�<[�F!Y�&5f��ٕ'T՗�"y�Q�C4��ԝ?hQ��P]�4z&�T�(8T0�X�J��g�6���]��`E�?�1K�(R�(�C��A�Ю2�88����w�_�%����%~�{���������;�x/�h~b��Z�(�����u-��J�^�0c��[�]2I@VA��|\]}���$����C�TC������S���)���ˆ�9�=݂R�YA �`�b�VV�G��!�Sn��������	�䏘������TsۈA����a�|�Z�j�vr���ևջZ�0 �a�c��i@��Vhl��+��,�ؘ18����ն��s��������Xn,˞n	�1/� �X�9�#��0���(����C.�9���o���=�u(��z�ay��͔�\�X$#_[�6�ȋgf T��z���S �4�$]ޚ���#��A�[3&-[�tk��+���V^��~>�U�[��r�u��! ��v��'[�iMۋ+�b��^��s�����RZ���|�b%�ARs�r���v;(������1���H�4gG!���\B���N����p�eɞ>-+��5��T���|�qC )3�`���!��G	���f,[�9�X�����Ep`��\~"����	H�J��G�������ʑ��$j����,��T�F�@:��A[	�x�C�u;���՗]Q�ҚΌZ�ӓ��	]3�0Ӗ�!���F@�ԩ�3myW���!^�����!N������{����>���� �g"����<ir�V+�Ǭ��� �ؘ᷽l��r��ĩt���Uͤ�0�IP!,9,D�@21>^�M���:�` �=b�!_�\�:HFl;��]g��}J��Y��;V_���l��(3W��� ܪ׉�X����WQ8��p��{�v�S�/�UH�3h�S��I�C�b���9�x냏H�(�ɮ*�]�X#����q�)��+�vV_�8K��w��&(�=��|vI@�G�d7�Pq�xx@�	}�C.J 7���ȰT!{����J�'��&$�k��<y��'各�ɍ�o)��}�F��-p~r�`t�n~ϑ&%�\&�j� �Ͽ�(��'񃪕'p����1�qB`��0H��1�dss���[ꈃ<r�v4G��|"u�'�{! �"���rlO�����K�MR[�w��.�~4�,$ ��C�/�TK � �A�������g��vj7�(���¯�;8C
 ��~�B��$�գx��Ɓ�f*P���j���1p���g�1/���sX�W+�P��j�(9�!��}&�d�wcb
{�d����3�J�
p����p8������vRɡ]�5L|m���*2I��{>4ӨT�Wio�:���C^
 9�Õ�G�#��1 g��ul�����_�ʇG�=p����:Dy �`z!I�K�/�[]�ڏ��9�?.��N��r����A������F����,�� z���z�S�P��9	��f���!���Ɔ��\Hn: R�|Ȓ�����ט�.����Y2|���I?d T1UI��d����P+$�5����D6;��.����5�[�� x����\D3����mW �>���<�xd�qT��0��@._��!� �1��'rT/*)@�����L@NHT
�2~��|�@��W���Mg�/�$�\���Y8Y���1M+�k��ܮ����cԵ�XG���Q]��!r�P�P(� ���µ��S �|s��%�&�HA�,�BI8��q$s�( �5��O2 ��	�7w��D�a����.J���/�.+��th����DѶZ�TQP��V���JA�b�Bk�.�%�e,Y�$v��'�t&q�����;s��dq�93�N��@6��%�@n�kj" gV;�!y�=��_˸ty7�H 	�7��<��2�� o�.�f�Ϊ�`)V�jK̨+4ut���i��!��=���%� 9$վ�}K���dCP�45}�
���5l����g�S��:��Y1��K�� �D �vB�ҙSٷ�p���O���ḿ�1�Ѹ@��eq�������m<�$�
dBe[d��;���ς��蚜�],G�8%�-�<_z|Ёl# ��N��$��=L��Ki��k�L��m2�F }s�Yt<�@�L�R�
,�D'CP�K,�Csۘ�I3��j�2�@��H ��� ������u�4%t4�r��T���/�d�\��m�@�*dg�$�g "dR�5e��_f_҅��Q@�!���NB0袶d7�c�g9�_�Z!#-��ik@Lꒂ�������` mD��U�H �z�[�2�����6�^�}�H@��$ڠ��! aY��M�>�� �btabp��mO��_}|�->�����R)��T ��b�������Ⳓ��� ���vo6�L%�q(Y�	��s"��W��:%�L���c� 2�@2��;�\*Y
�Z���PY��\�[����#�I��?/���&s�R�M!�H��	��-b�� ��D-�8�d�ƈHC�'Q 3(�t1�M��QTȋ�!y=G��4c��B߂=��-�>�lO4$M��d�*������9�:�.�e���T� �.H�F?��k�M~�:�(.t���X.�P�
d����Lv&���S��C�o (��r�N���N�O@H��233q�TN���)�����'զ����K����L2�J�ӹ�ѣ7XJ.Y�{��\��ڳy؀4L��M��BaW.�J�� �d��E�Xs��|��%
aE��2���Q1E@X7���hL��R�������ΑÓW$	c��6��/C�u����������kJ��<,��u�N�P�b�����@R��MyPK^ Xh�O���	"�m���{|����������(�^��<�"P�y� ���T! ��"�3�`��I�ƛ8��#�'�}��7L}�9U��b<�+,n`҅Rm��|�����e ����I���Y>���_�{N�*�D��t:'�0�^;�tr�g�ǻ?@�I -+B�zE*�vɭ[�ُ������v��xn�Z6�$��Q��՛*�ۻ�5�\����-��貕�]/Yr�eK�y��)�5К꺠p�M�~��<0�2�~Ł����پ�#�^$� �A@v�N�,�h&���r�1"W5#t�R��ܟtW���wl<V�o|�<�wp���opߧ~j$i"B�Տ�y虎��S]�nE���
�%�ˈN-Su�CbM�dVX����X �72�zW����ĢO0bY"���K]�Hj�yQW����8��@����{���j�kb�5$3�ު��r�{��r����ڱf����@�ņc�L<%y���#�53;ފH����E�be�Cc 2�)�R��>��l;)�J'���l{!o����U�֠��0X /� BRL�#���Ч��D-����q�Kf=W�6�Ȳ�v���c��=���1ޘ���4�w�`_'aF' t�&���)5��2�n��m�Qi=۳?�A+�`���EU!�
Х����,i��щ%�����lk^�?�>%��Ggg���X'1X2x���q����ͯ���ޱ��Uȸ ����>��b/���-�K�ɳ'ض�k�/��.��2����[�����8� ԐΨpd�:73��Ț0
��!�hV%D���B����4@L���]�&��%�sq�Y�R-.�.�������{�vՊ;e���9�������<�9�.D"`��"\��E$,����(f�	o�p�K��H�b��rgIY|S�YT����a"aDs�TeO����/��h�2*�t �Y�]����l��>�(�� ��|{�b��8o�@䯷�G{g�IИB�8GM^n��=���i��qx���e`@�T �A�"*�MHT4^�|/VP��{b�(S�8��q	����H(�?$���Qi�m�[�"���p�,/{��2�ic��@ɽ�S��xC�l�ϲ����E�q����� =6H�@�^b`R���KvHuaQ�*= ��[�x�G"t��;Ă�G4F�X�8ݐ�;K2{�I�T
IJ09D�d�R�����=q�ݤ�7"����$��-�����8���@/�HE�B@.3 HoJ�"��M3�d�=QL�d���p�%�W�{�֖����Pl9��Y, �ugތX�q�K&\�J9A�_�R�R�Sz.�~��~qwO��`7��@�6��A�l44�����ݐ'���+I��(i� 2�m�a���6R�lo��۫GG�؀#����(W�M��%|'�W��s��`"�شx|sF���W�%��L@��!ݐ����dA�s<1�Q����d0�ݠB!^��-�c��g�\���F#�-H�Q��ʃC D��6_R�,R�Y&�b�1��_/'��/�A�{��bTB�LBS^l�c" �u�k�k��P	�2 ���E�,x���	*�|��)�-S�$�n��__��T��Nr+�z�5"X!8��M 1����z�桶/�}��!�b����p�@/ �@�R7J�A}�d�2~�'�� �}���`��٭`k�X_k+p0O�f&'���@�D�m�h7C.��y�s�����͇C���������k߁ �sL6�x~T�	��X���^�ޞd��������L[	�� �pI ��V����'ʃ�@���<�)�@��H-eaR��4Ee;���R;�4L�>d���%+օcĄ�����$)�`���G|{F���YV̲*�f6�RVI��$�g��M��p��55�7F,�	-�1�8 �@E3��]���o"d��J<�,�#t#��H Jf�@="�� �G��5���A�O�D��b<�6IE6�M=>:�	<���1w����	�"+��yO����<��4*����~�~~��GΑ�X�BT�FA��g�ˌ.��&�/�������w��!H��n՞(e�{�����%8?���P-3���.DP��p��0k�Ep�yh��ݭ|!��� p e|��C��<�>�k'�l��|H��Ȁa<X�1My�#�H$R�)?5!>��d�K-��5(�< xv�� ����Dd9����7�"bD��/�E;����E�SK1��0Ȁ�MNū�N�����0����<�2�)�EL�)l����2��k�}H� ",�z!��s����!�1�%��=�[Ǫ��6-&" R�;u=E8�������[�_ ќ�j��Ɩ��Z�Ex�C $Ļu<;ɃCj� }�(�Y�%�,��,+IGT1�p=�-����_��+�	���ݻwW d� :������
��ѐ� �}�)�0@��w�yqo��M����]��4����H����k���x.חD�j�kf�%υO�ץ�Go�/NY�m@�^m�"�������nw@�6`�1 N���i��u��S��'��E<�?zb��r���I@���(J\��&�OΦ^��zv��b5�^�=#��+�8j�����t2 ՚#����: �3�#`$���;�2�F@�L�B�?a�6B�v""e�C�BUEH&E��S�_�A�|�"�--�f�U�F�� r������8ڵSsBv� �
�;j�^`HUL�@IۇY�x葫`LD:�v�Cd=��Բ���Y��%F�}ٯ.��&ճg�y~@��8_���{Y�tL��uJ��	�h ��o���d�c�\ �[�Y���N�{C�C��_3�N�ݜl1Fk6��e���U]��I���:�Ƨ�!K{�Y���z���6 �:;h����}�~-�"{�'���=8��m��U宷�!���' g���/�N�-|�.�>�Y*Dt��! 3�ԛX��D�E[O�+;ur��:���g���9�������A��i�ZĊ;�ޏz�L2I�������,JV�Bd5Zj �������"�?��찅
D6�;I���<��ك��B�/�X됏L�RnIm5yRY����!�7�\�}	��O!�ه�#	;�O}]��<ۙ���F?�#�N���{Y!�U�^��E��e�~�D����~R��B9�	��ʃnoT2/�-ҹ�����*"����dJ9e[�u4D�U1�+��L��<�G1'66j����_���!�_�"U&�*���	bO�Tk����4mll<�jG��3���A���u���6�`�E[��똬W�!�BŢ����q6w���Y�C @��?��.S��Gua܉H�h4��������A�D�Adl�F[�-��:f\���B���c����f��|d;Z|_a�UI��b��z~��R6���G�j?��I��z�]�zE���ܨ��� �
�ɖ�r��r�v�! ζ>�t��R�8�I���z��Ĵ/�� �us}���I6�D҈����pU��q��\S�M �����}���5K�Y�z�A9&����[��άN�<I	F�ٌK��H�g�|�H0�λt�����T��9�鍍��Oz�P� � ���������i����A�^�<w�̙���>O<�����d�㳭Oo�N@3�� �@�t����E�$��;���B!k�\���)�vzm���^�7����4�a���Wx��b���QE*��RTZJ�BL�\�ԅ�#^҂Ū�
^@ѝh��0��PAT����;��t�@M[5�|'�i���|�����g7�-3����UK��p��o�.p��Ճ]`8��e�z�"���
{Kx�4��W^��y|=]�L��?�=���[�E%��Wl��t4��Cd$2!0���K���ƙg���ǵS��37����>�̈́�%QwYoO������y.Ȉō!�]T� ��_�n,ߏnBJ����o�8j�����B]Bȑ�Y�g�b��X\���:���lt(�����3�n����ӻ�h��Ͽ��Y2ţ�{�X}�)I�! ��0+��X��1mU���Hkll��2��	&�1��"0�ܹ�����A����y��-g!A�}�k�$� �Z�{�w� gI��������i��4ҁ�Φc��Ņ^�Z�F�.��
_�xD��p`xud��@><��NB���m�4�r��r���r%9��R��|L1B������#0
+�9�}>b(���8b8�-Rt���H�\��{�/1%wP�>�!�{b%�� ������9٬oxz�LJ��cW8,^0<>#<"�H�x��	��Â�ސ�;t�'D͖}�Q|RBN�2�`|x|�p��AF&E@��x<�'VB�o%6�� #�#C.|MH�\.z��9�R�T���჊�4~��-����I�>�9KH�F�t��@H�$l�Yj#01Q�|N ���:�`@Hȭ���ư��+!�~O�����@ `@y�p*�����Ҫ�k��pT<�Ҩ�(�V�h��m�	I���L������I	��b���gZ�*Q,���u��xX��n�������wLY�@�� !;q��@��F�>�-�BBxF`NHJ�SL�Ƈ��3�z��.�)5FGs�a'���HH����?a+���9l��@d�@?��
pK�sv��P9�ښ�T{��1"��7x!@��*�@I��>SI%Ã����Z�u��9t�����;�b��>
[��e&D91����0M3�t� 7͊<�p˱d�7b&�>�-��3#\	���nn��°�f�B���빜�����
���>�����6Q�Y��h�b�M3M���
�y=��X	ٴ'��!�#J94 ����'+��yЁ�U\�S>�����]	���T�l#�Fj5�mJ���T,��9ls�RI/�J��u�6Zqj���D$8ժyB�픣ؚc�`G��ڤ�驵�O�nW�N��lH�Z�dY�7ʄ~D����P�04��n�(�8���X	Y�@}�@�QR��d3����e}B�eh1T�1d�X�8%��G�h�P�-B��dGB
��D!��3 gh���[�B�����Dk��r�B3!�!eR���z2ϮMQ�I�m�64LE3"��x	9�v���D(�5=H��ILج�Ԃ̨�h|#D/i^��H�N���<��h�*R����L1%�Cr��N�"fZ�FC��S�>7���C��
���L���� 3�і�d$Ӟl��s�)J�W�~��h����B���![Vo��st:�m��?%��H��I��j1����TM���ϔ&Kj�d8N���U��Y1t����_(dhdSw\�:[�里I= �EOgB|ei*eCJA3Mea�%�1�m�,�օ�v��U��͊g��	���ƽ��9�~�	�Cv��)�>d�݄!-��l���)�Rb+�j���dT&�4rRF����@Hȍ9�*�kw�������B7��뷜�"#�����䔎s����Y��QZ�4ؗII��9{�����w`�:�շ���L�A�<�z�	�{�+�#�Y��7���.���'-�~4rM۬���k�J�O�����^w=��N��!�x{�MFa��;��ߋ��XD���t��H�n�<"��di�Ѱ��%��4?<aB�����2]�!���'Қ�����5R�������Iil�Zb�j<"!m4��$�Y�h�c]0m��8��	��x�zIϬ�oz9�R%#Y��t`'Uv]�n�|�}�x�D%F��G�Vw>��(0�Ɵ�������6�a|ן�j���?*�2 1)K�H�@�9��8�Ƅ���+J��L��	1�	�	��y����si�(�]lǉ��?=����i_=U�u��նz��+��P^�\�Y�S��xA@^�;���<��ogYd$���е�tQ�v��V�H��v��N�N=�T�����{�K���/eN�;��~�~��$$��~�
�V*v����gC��:d�9����MYaՑѥ�mɒ�@���uR,�u�Ӯ��X�!��h,�H�ό|$zSb7�@T�
K�}$�� �䫨���g J;D�8W���oP��ۍ� �m�v�z���O�M�ۣ�2�'"������cVu�|KU�{���Ƒ>,�������d8����S���;���:M�Ή�i��q�=���7yD�pA '����8�sQ��;�[��C~�����r��RT(�����3RȲ�Hţ $ ��=N�5\B�Rt��.<�tw$��V��$qn����;�@Z@Njd���B#	d�-��Q��X�!}	$�l�1��j��h)��04'~KYb�X�Y��$������	�(���T��]��)�^]-ZeI <  ��C��	�I��K@t:���ݺ\�0;L��0"i|�j"L��)>Gg�a�8Ĳ� 	$���' _�� "��h��r�U���je�Cpm���
��Z��s�c��<���R�Q�G78�@\ 	j��Lx $�v[����;Uy��9G����-��H$����b �"p( |�sY�a]������CrD?	�^L��	.TY;Z�V����B=1���Q} 	x�fv� ���0�T��<�E\����z~S?
�8�9��	�x� �t�E����t���|'����2�Re�Qp�Pv/li7�!n���9�  �Gm/	]�f� �\& jrQ�CJ6t=LNec�\���槀����!6�Z"b�i �,B�pǓ�C�iqH��=�I�Tn��w�o7�!��X%_��e�~9�?<���(��5ۻ�	zq�e�C��t��xve����Wv�e�V;��E	�,"����#��c��R쏂}�lQ�D��a���F���y���x�hs��G?�q����� A�@�5�)f��(�"�y89�%����.xH��9i͛}N5ĳ�1�k�����z����r����9 �ֳ�E����1��C�2�^��sp�G[n�\��W���跟�x���'x|�@���Ĺ�����*.ՊW�v�,3by�ou�R *bɘ �H���<������� sY�{�oӿ��F<���;�[�XD9Bܚ�L�C�}�p
�Q�MrH4}�h�]��9dwC�	���U�F����v��(E{O����)so�M�1�D�ړDR AS�C�F�|+w\y�)j�#u�ol��ϤC�����Eɀ�L�C*�BdQ8�)���!nHU�v�҈��| J����~o/��1�/㑒y\���nml5R"?������"�|>��ӝCJ����X�=�F��,��lƻ[dԾŮ8r��!fA 2�0�e�0f�`�uAd����K�B<�����ؕ��K�,2� r0)FT*:�� 9�َRv���vj���^��T˟�~fD�ӄ;�ת}��9��7�^�X>w~W̽.y(1��7X)B����g����Y�/���
YK�L�yE��ģP2�_����4�%ǥ�XB}�h�յ�'Q,�"����ْ�C6�ͦ�� k�8���ô�}:�a��!P��7.�CXܭ@0F�Ab�$F�Ab�$F�Ab�$F�Ab�$F�Ab�$F�Ab�$F�Ab�$F�Ab�$F�Ab�$F�Ab�$f�C�5��gB��}ȸ��"�yy$㞟���΅����I��X,�C    IEND�B`�                                                                                                                                                                                                                                                   ���Dn$r¢d7�K�0A�*fL�M��D^�����<�+�	Io�x���"����w�X1�Ѭ��,��d ��`������W퓜�_2�&��[��`rk���]b��1�e�Tc���7�+�z�s�d�^]�t�|���"�덬��7	\֨`@i���u��-����Ν�5�z�Y'(�0��*�"y�6�v���=_�*D��p�/Ȥ�
=�I���%(F�FA��Y�/����]7�>~�O,�Pq�	�w���Ƽ(�'�0S["h�fB�K/���;jF��t	��	o�֣K�Q�����Ի�S�;x��!�,�24V�������
y=����ʰ��g���v��n��D����Ɩ���7�q�k��9i�,c��O{��bi�D��/p�������{���R��%.�W;�������Z�+�`$���r@���k���������W�#���J��s�t�1�#匚��~LM;�Ke>"��G�m���υ���� ϩ˱����X�"�#!Jm�f�t7��R֬J$3gv+�3����6eP�,�ω�'��Wa�4AC�����S{�΀��h(�?�T�8z����p�Zٕ|$Q.y��@�h҆. ���Z�rtO�˾��4��ve��>�U���#��#�X��EM�=s���|Ud>7�C氾A��K� ������FSH�P��/_�D�~>��"l_���������ό�e'#�FMcj�e��]Ny��;�����]z��4�%c+�%�ؖ(��{����׼AxkϡNXp���0��<��8D�i .�OG~��I��E]�3���5�])��@�����qם�W�Z�r�e���z�<v}�kT��d.�0||0��o�-�U1�tF��g����ũ�D'o��0k���nm�笷&p���T�~�8��� X'���C6��>����%�u��۰�J����o��X�J��?[d/��}�-A������n����q0&n�(	?���~�(K��i���"��V��.�'s��r7��	n�����j7���ޖ��.��3�}k�YW��lu_p0V%�lAy.Θ*�08q����c��/����Q����lf�8��I_���L{�ܒ�Gmq�x63���;�	�VN�sD�u�%���d���o�S�,��9>=�Q��N��E�?i�����P���@{`\��:3[�����э�`�1�|Ѭ�@��Q��US^ �j3z��:���� ӌ�7u���q�����k�Ge���歕pLh��\�
r�I���Yx�`�]u���rv�Y5����(Q�jM�\`Ӷ��3�v���X�u�S�*��'��/�asZ�&Jz�<����O����u��{�(���7�lcB�̇Cc�>8L�ȇ���;���,��Z\�F�Vs�W0�9�C�wjp}�Z��׊�*dH�֌��&L<R�& ��{2�A�;�v,������$�2��v��
��q,�ňfW�E�U��"G��g��n�:9���b�Z��I:U��KQ�Ht�Q% D���w1��F`���C@�9}@�U��Z�t��#����� ���}����ASոݘ��4[K^=��C=�٩�+����'��������A�K|-Zj�"�t ��?�h���ǖ�PFD���
+�gJ��ϔ��,xS˖�'�O�_�w�(T
��S��W��!.jʏ*i1"�P@e���PD6P�<{�GC�T�����jB-�\_��ZJ+G�?���p$PhC
0�OT�.e����Z�x�o���G�^	�$uW�gU�Pi�v��33��g>��9^�P�l&� ���Y[4%�x*��ԊV��"�1�ip��eΟ}?��}db���BG/K�������N�:k;rQ�Đ��?��d����ӶV���:Z
�'e��q4p�W�쵹":�f�;��V�.�%�??M���f�S��:�
l���}�v��;�K��X���/'=<	S����P�U�RGP��
�����|�0�My�q���������,X��\��;p���:0,�wQ��|�g'��'�x����3������ʏ�>I��A?�@���C;���1ߤ�����p5N�(��f��)��㨒06d`M�~o��S�b+R��,�iMaP�x|+��I�+K|�]anx�{���1���\\,�!�_O�`�Kc�c6�y���f6��s��:0��p8
`����`:�����Y�?HxyѲ� Jr�)��6;�������,��&ҨX
�˔{�!�_�^�sH�[./�t����b��ܫ�# ��1Xo����^`����p����ա;�.NlW[����0߹�g���[͵��&Mn=�<a
��*�gu�y6{�4���5=����'�4�A��e�K��wOL��>9ئ��'�7�jπ���j���-�xD*,\��4��[k����j���)$�Q��v�x�d&������bZ�?�qĳ��?6Jgz��>`}����XJEsK۞D��\g���7-���S(�8\��G�U�K�����kP��>�q�=��L�����C�����q��_f1ڳ���;
N_��/�� O��O�����4��t�Ѥb,A��:=@�#�}�nGxlUɯz~U6c�ULq�P�lf)H����Q��)+h�(-Z� V�K�r,�֚ù��h�i_̻h�p�TI����2�3e:�����|�.�p�R�(�섑>C���E�3 %5�1��!�����&��j�{���.����N�s".�̦���<����٭���G(P��f�:��Ɯj�c�©�N����a�uP߈@���B	:u`�Y�XiG�[���`=e��F۩tʸ�� H^���ο$�z�E �`�"�W�ۏx���$�Lz [.�]})
��x���6Q�z�ۓl��������:l`60t6CMU�P���?�PD�y��*ˣ��W��6���:�QEj/�0~ ��}��^��<cQ�
`߅L+;��*�F�����}�*�mO���B��I��P,3c�î7�����z�ybI���+Xv �[�:��0PVb}�4UR��ʧ�cw4�Y�޾IW"�A��L�r��߀����{��|��Y9�"�?�v�M�y�s ����܏Qݧ:����~���1�!�]BO�ApG�vQ�yx�l�"_ �%��	�O"��s#Jgш���L�ȶBlY��?�(�o`I��{���Nu|��JO&a�#?sZ���N[�x���X���`�\�� ���y���ku�?���s�n�]��i�8�`yȷ]���q!#(�ĸ�OQx"�� �2�RhV��XnN��T�Z.B������/���$�G���K�-r��|i8�K��:�['Q�c��@p9�^,\�YH��+*����
_�if�4	qJ�b���a��z:�d��߄�Jg Z�p0]D��Uݑ%4�Z�؋3p͞gu���-���0�s�.]j�v��O� `)!��5~ha�L�PNG

   IHDR   P   P   ��   sRGB ���   DeXIfMM *    �i            �       �       P�       P    1�LO  yIDATx�]�d�U��wwO�|���fwl��X��lG8(�K�� ��HDAyA�/A��V���!�28	N���A1!N"G`;�	�Y{����׳3=���߿����ϵ�S��Ou��8��9u�n�z�Q:B��#�8B��#�8B��#�8B��#�x��߼��G�����Ny^�����\��Wի~�zu�&�[=8,Ӎ�jϿ�y�����/�����ͯ���/�Gxs��! �O���z��U���׀����3\o�
�Ws9N��D^wp�r�����eA%}��(�J5���Kd_��7/�X@rU�t|�ĝ��n�c�к�����~���!"9�W8��UBx�-��^�?�Q�4���K7.�Ս��6�.i 5}ڮ��a��F�p��~۶;��F�����\��G������i�]����T�"����e>�������!䣵�J�ή������p���E�pQaU��dj��j��%�w��0�¸����Xm�(�C#����4�^Ů���p\aPd7M��W��⯕��]p�U�Ŝ���Aǽ%�
�ګbC��f
{����~�on�{�ώS=3~�x�b�ufń��~a�J]��w%`�t �ݴ��า��q�ni��M�2��ͫ�M�ؓ�f}����5�,Ʀ�E�yqܿ�ӛ����C���I��2ϻUe5�,r���$��FP��;1f��0�yAQ��6*f�.2;wuL�2w;DE�򢺌����[�f�gݫI端8��5c��+��ȳ���ƴJ�sȋ
��[L J�Y���8��~'�j��������.�~����P!�/�<}P�;9l��|M jP7�=(@���,K@�9�u���<�ǌ[I�,Qsy-J^c ���6����^c+9j�f��	��׌��<?h�6[2������K���/TE�l6 4���q�bt�SFS�d��[� �Pp�
<S�@:J�M/8��D����2wa�˒��D�7�5�pο��ٷ�_�x�.���Í`b��_��@�� $:�B�M��;���ry�v����yX����{N�n^o����à�.%�<����x�����s���k`<����UEH�������7�sm;?~z~��eZ��2Z��v~���҇��P{%ӏ͗..��~�%Cv�z�6:g�u���{|�Fޣ�̭K�;�Y6�]���� ?�6C�j3ih�e�����Q]�*o�q��S�0�5�`��j�6y�ӏ�s���Y:�]2͈y��Bz�D�����x:Z�@�g�j3V�Ⱀ��`Y��S�,�xo�)��L���b�"m��WM��[�v	 ٌ���sy(�y�7����:O�y�j���n�4���]>򿯋<6���(o5I)-���H!�$�./sC�7�y�{���'j�V#kc�V#M��N���<��)�1�a���+ť��:�xޢeK6��y֔w�]�/���d҅��>����)�pfi� Su&�h��%��*i����P?-�j����.��z������������O�A�:�O��g��}�^��kܖݕ��-pfo�����׏�?�W�*gj�Aè�c�6�F(/�_8�,�U�z��G6rM���}��$׏�{��Ŋa˫�[�zõ˶�xn5��_<�����ï����'A���0��uF�_X
����i��.1��F�*3�2Ȭ��zn����d0����ڎ���Й��2��ЌE�����Kd|a���AMٮ�fP�W���N_�w4����Ѱ�I����G(�0!%��H5QK������\l�T�-�7�|�>E����Vyy�����}<��(2�&����	�ݴpG��w�Li1�*�z�Z�Ew�#N3Eż�� �m;�8�7���8�)0W[{m=��vBP=;I4��&�2��� fL�/�ݟ�ҟ(��Ȇ��&�%s['��I�U���8�__��h���3��E�_!S!V˓i�SZk�
`Uϖ�q�EM��2׌� �b[}�_���vZ�ћ����ؚDT��>H��Q^
�҂Y�T䵖���{Ȯ��.[���[����n�\<���}����k���n+h��y�K���B%��p�ڒ�?]]Q���m������R������N�a���Fl�.?7����O����/z���r�}�)�/;n:6���m8��<��z���W�tv�����O�F�<�	��F>����K���Mi��m5���puۦ>���0l�-pyw{�ت�o�7Ü���l�����ξ���Z���ZZ(lfA���P�?�'�����A���c�����`#�!��.�����|ؔA��B�m�0M�~o}Z������[ L{��~��<<h�<����R��T{���/9,����z���d���Y��w~ֻ x�=D/�^bR[�ד�`~ea}~��e�)�P82���|�k�iZ@�CԶs����Rc���џ��k�)c���A��˂g�L:�l��-���U2K�	l�Z�I�6޺����v|A���u����bX՟|��s0I4/��m	�Ư�*Ɂgi�u�3�Z?ek;�,U]	%�$�M���-��M"y���Cj��aԵk��A���o��<�i�e��0����Sf<��� RS*&%�m ��L�uQ����<ͩ��\}��?b�2|[�����PE�{I�e[ �$��$-U�� �OI�:G���ff�}8UYξ��nlG�Ɓ��"?q�g�Kg���:d�$��g�W���pa΁�����m���q⯅1�p�y�aev�O�؉3��cvp�i��Pg��Y�����PΔ$�33#�P�̠xʋڼ�V��L�N���P+ծ�N.�i�����E2I6+cdd���=�����l�
?l�_��9�?7����A�4�*a��G`y����պi�O��4R�N�-l=P;��kkږ�N����k:���'�1�x[RCV|����3��W�	i)A&d�YQX����h+�H[�1�l:7��;��GtƁ_Ic�>����������Gdvz��G���:Zd�ΰ�r�%���+�r��j�T��2�m�hۯ��G}g�x�����Rhki��/�|D�K�bdB6#����}�s3�r����S�e?��7��h�0(��Դű�]\���K+OX0& r��R�^����JM53i�<�h1�5��úg�SK��U����qg�VE�LD�N>!�m%�Y�OL�INfa ,\�,�H3{g��(�"��K��A�Pvp���K/H@ˬ@�2C�gh�އHs�]ʛ?��?Q�W�*����Qa퍆���>;���qp����F��L�Q����80�����ck�,f��K��{%��zq县����$��,Gm^��ܓ�����md�Oe���%}n�����µxl�,�,�]2J�`g�LM"�5�t8Ҽ�-��
k��i�&�v���[����̧�t<���fR�fY����_,Pl%��J��?�m�~/�vl[��>�i���H�<fuo��%�s����ٖ�j�]�kuqz�nSʾ�\]�ط(	�c��q�`&$٣���
�	�*?[\��+q��ۚ��2[-�J\��4 3�:1O� ���7+���w��z��;;5�����/��UUu�m�Ix���~Ȍ"���h![�^F�܏���۾G�§'���U1���8�@��9�Q�v�������o7�݄��V�#���r�NY�{U� W�d�.P?�<Vlx�K�:H�(�6w�ol^;�!����HS��D`�JƒQigѶt�3��w��x1��0F�u�z`�4Tl<���0��a^'ô�,��)���"�R�%/�<��<-��}T�E���z�S��o\���ۿMi �bE1�_�S���0p)u�GU�ç�9�����b�δ�g�"�k���S�FQ��`q�[Q)��@����{�F�k�n(^>�� LHTB܁\~��h�YU�eUg�V��4-�"��2C!�o��=�s�BE,Xjp�����ۡ�%[5v���Bz�Jm�9�4W�uF�s 5.��C�s�#��<��bt �����d���©�h㥻�����Q�0LXR헤y/����'/�̎2�K�~����O�QV��8���ْ��%@W9��S/�I�3{C�OQ]�8 ��/�^~W;���{�~��;�/�\�5��Qrs]��jP�w���r�8���dY-#]�'�\"ot�)	U�
�x���\�K7���{`e�O��|e���>���}-����a�h$�<p�FM�7߱��m��`1N4���j���_|������;�b �̇+�4�
�>u0�=ԟ}�z��������bl �g�/�~0�\wy�P.ꈝ�B^:�2q���7G��܅�L��ӯ.3���A�=�ԥ��k��0]�@��
�D*��f֦eq���<��i���^/��V�WNv���$[��wo���ݕ����㗯������3�H��h:ey,��L�ێ����pf|bP�^5���^�DeADS0hVD�o�c�z�A��������7Mއ��{+���E��|�N�L�8jPj�. ui�C�As)��.ړ�mz��V�f�ku�:��_过v>f���q��K�j�D>�����np�I-d2�/,�7�<�K��}[�G~��x�u�@@[6�	0�����38�Xw��I��80gu���Z�i�P�nmI��QH�&dfe�|g2�'�������'��?{o��Q�`����V���>��) �aC�� �����(N��WzA)|a^�3Fʂ*B�1�ؘA�B�z�qB�6�E,`�a�;�h$�[�.���O�K��N�̴r�h�ɳ+ꈷ�	!H���$���y�#9Pz���Y3��Q
p�F��E�qI�c٫��BZ ��X{��)��%�Ev �˂Fy�:�L1�C~��h!��agp��*yO`�̢�Y������E �們��ʐ@+I�U�.,Ż��VGM�؂?�	L'ʨ2 M&<� _S
�5p{/{j����J�¶)�E���su�~V�qʘ�q��0i�P8���ºڠ74Q W�x��7xʹ@�%vܖy+�aj��jM \�U�+�#�S`l���b������X^��<XY���t&�&��ˎ�F-l����&1��a��9@�79iI`�<�˷ʀ���\�����F��رփ�G�����@
yt�f�^�4I����H6~/B���D��2����k��k�;������i�Q<cƬ���W���r��=�;{V������m0���,,+�&V��!��h!@Nf�F^�m�8@��0H^����U�=-��	N��f)���YM��`eh�����eZ����L5���cOU/0G��f��d�h�?�&�]�[{����ŋ� jf���9b�Iņ5�DM&�ƅFJ+k���&/��ң�'�$��l�4���yZMhU1.�t\D�8sf_� �u\����W.�T������_�f��#l�Z��C3�0&om�%&�2�L��m�i.�ݶ{��ת��]o���b1o�b�S/ʊYsF	�̄b�b �h!>Q�hf��|.� �����e�h��G��Zߪ<�Y�VQ��?��{�R��4�~|�~�O�R>Alo|��!�o�Rɂ��0>0@w�U�oWW ��J>���<-��Z\.�3^���qT	aF��:6&m��I��DT���@A�=--�P�;�f���r"�n\��o��І� F؂�q�����D3�tH' ���?�>��{��{��X9fwU��R��dy��oyh���Ec�O,��ohu����)v�2��w�1SfATṭ�[?Z�9込t�p��Q����밝�w�1� l9����,�j�� �{�H�b�ȟcq�e-թ��{� ��^�,C7GU���N����ѫ�u	;� ��?�d��gGo,Ե#c�\��4�1�R�d��hD.f_��q��H��
>���w��?=�y[K�_}+wg���4�f��r1���h����h��ژse&#���|����uTb�]���o�u���s� ��ݽ����d�OC���2]D3拨vY W���t2�
�RM֪I��a�Y�.�B�La>u>�����{�k�6/����{��m"K=�ZQ�]�TJmf沽�c�¬��[��u�\�1�l3���#�
 x���d�$0�>���JU�M� <��vK[8�O߁�3Ȗ�}���\-z`�j�=���#?_��=Sa�^�87 �,1�Ͼ��)	�ʁ)�1_��d�&>T@օ�q���=�������}?��o޺����8�u팓S��@�5i�z��B4���p1,��ڽђO�dM�D��Y�03��Ͻ!͓ʔ�+�cu�7���O��&<�^3��k
�f㡽�K���j���3��@�g�W*�!���TqC�, �Dy}t����h;���� o���gw�d۬�S����駙�u�������0�]?ƣ2��8�#v��MR����'��s(�ֹ����v�n6��N؀�� �ܙ�N��лm�)�$&���2-/{u7�JI+&-?�bj�D�Y�O`?	w�( ���@L�h���dPļ������I��6>��%���X�j��D�̒�-���"�g��V�C�5���Zi��F�'��Q�Ȇ6o�9H敀f���2�
���Hj����A3�;A����cWM�@�S5~�{3�;t ���
����6�8$���(b	EUʛ;�b@y���  �� $�"��-`�r���þ�ݟ��U�yZ^^_��7�l%�K�����m��oMtD́(��1W�qV���Q����o�� X�(���ϞӁ�'jּqDh��K"��k��Wh������-�x�����x�C���7@���agl�x��A�`���@��TN�꘩b�vb�Q�hI��NZ�)�u����k�2�Vo6xbo��6Ç��D�Ν�+����z'���t���ZL�x~�N�%2��8)Weo�W)�l^t鍡6y�l�&i��g�IWx�t�M��_ h"Lz���q�p#t��c�\�S�鄘9-r����Ny�߫���y�c(o�ϛ��� UH'"�y�    IEND�B`�                                                                                                                                                                                                                                                 Z�W�P�5uc� 1  a:y����}�o���Ф�5>�����q'�/��tZN�+X���u��z9(1ڕq���1J�z�J�7�XI�p���s�4��9В�iQ�k���O�"z��~��
�&�Q�+_��,��j��Oy��)��v�o��?�m���.��~Y��n(Ἶ�#x��5�WL����H�' �w|u��\�����n_s�B�{!ET�X�^L���cŃtN��M���VB��
@�Ժ��(I9%�zwF*�_����!�q�H�����M����C�oGt�|�����G���]�E��UG��/e�?ҁ2�~&������͵�8�m��\�N93����H�STHV�XB���b��d����q?��+͋4Zo|���D�����r�E[�&�7M�j�U�8ѥfn��¾w�_��(�3�+	'J����ՠub�O&;����mf�2�;�nIs"O���d��✴��Ro>�8�C�PNG

   IHDR  ~      )*Q   sRGB ���  IDATx��Mj�@�a�ȉ��6n!�z�*���
�A�P
��&
�A�U ��hM;�-���T���[(�)8� H(h$=���D7��!�����ݝ�?��t����xcc�������7��1/*ފ�@ @ �����qu�������l�IK��R��?yy��ť�| [���Ń4�M�$���{|����?|����s���p�  � <gm#m$m%m&m'm�yKm��F��1�H�Xf��N���I�N��z|����֖���y�[��{g��f{;��@ XO��8��5�뭝�8�����D٣��˧�N�$C�n�t���)�i��zu���3s}}mnoo�d21�v8�m�Z6M�y��H����L�E�����;p- �  �j	=?j�i��~#��'�{���A�O��]D����=��}'''��ݪK��g����7��tZ��^�?���˲��v3�e��s/ ��f|@ @�
H/E�V�q�#}ԕ
x���M��v������r��+?p��ӱj�i+������:�'�gd�������LY�M�t:5�������� � �������Kf����f��·���by7|�W�l���I|��_��:V@=N�����Q�A���@ @ �u������vI�/bPg�4��?�Mߪ�Oǯ-����V���u�������<{@ @�9���w-�xcY��:�yu���kY �B�q�t98�G @ ��@�򻖗t���
�0����On�@ @`�@����.�E @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @ @���6w����0    IEND�B`�                                                                                                                                                                                                                                                                                                                                                                                                                                                   ���(װ��L�v9j��<��2�5���^�S���j\ҳ�fzw='fM=����|�=�ٱ(�2����:Φ�]���)]�_�`r=���wOA�/)�Ke�5����=��u����Bi��̂�"��eKr�=��������������!W;V����~�֘f4`�U��c��^���-(�^dUS�"�r�w,��a[�LN��/?�[�e��2�՞�-n�=�oy�2^RJ7�=ƽ�|��bGGσ���Hm�t�W����HyUTjFŏ�߻�>�"d�����(�!�3MHB��f���+U�s�_gf�A�ZI�ӝ�k���E����L"�(����u>�|.x%x�&R�2}>=�X���>Q�`��i�܉�N�4�j��(���~�VE?�C����k�Ln��"C��g_7�R��Cc��9��V%�5��}k���ZLǝ���,��sF��IpfW�ōCu��M�������C�N�{�=�z�tk�ŀ}:���	��q���w5p�(ARZW�&�K�������t�c'�_ӚoX2�N�2h�F�"�+a�s��v��f��c區y��T ɲ�R���M�ׂ���YIe	auN�s�i�?%3�:ߴ�G3G����r.�dk�5$]}�3(�
�:N���?Ó+��twʒ U)�{�6���D�H����jH�K��Q>�Nn�|S"��_�HZ�y��z#�Εb\�R�M�&���Řv�ԫ}P ���Xn�w��d��T�R��
4ooi���I��|p�d�N��!�h�[UK6k�bD4�������7��M,�l�wc��n��=��;�j�^O���,ռ��ni1�L�����
^#̈́���f�F���� 0���%:H�$/x���M1D*�,8�:�=`��"ՇK����Ŕ7KNE����@M.��)�hV�<w�+yU�j�УƜ�,��*E�Y!8x�8շ�e��V�2"�	7|���M��HtI�ѧ�I�w�q��� �F���+��[�Hl�x���U��@!+���E3�3����H� ���6���k���Z�K��3W1�|ɵ�@�,1�����n���a����b�W_x/nNk�W�A�s��p�xƯ��e�F���G�da>�dL�I���fl�7r���,[��5^^��+��Op�ˆI�/�����Npz;W�_`-Y��Z~[�چ�j���2��k���1���t�?2�w]S`�x������Cj,D�!�J�ї�yi�0�k�'��<"���ʝ^T����RoQE����cM%6n��ѓ0ѷg���W����zX�k�uQb=Dٔ������Ա�����G�iuݕ��G9�\�"P���b-}N��Y�bd�
���Ki�)���ݽ�Z&�#3�L�W���#%�L����9	�Tu�y�|</�����5�[����$���y��`�L�4{��8JTԣtV�P� �+Ǳ6I�a�Є�Bl��)7X�����YWn�ծ���*������X�f�. f��r����t�s�(�ѵ�.5h��p�d�,�a ߼��~�p���f����c���kq����=&R�΀���rF5��0�gQ; �Pd�ڤ4 ��u�Bnyԩs��?��>�'i����<�5A	i�����ԙ���+-��	�	���DݾD<�KO� ���R҄�v,D3�W�s��<����B�P�$tB�E�i�˺�x.���`^�0	j�n�r� �|�/V���|� �}���M��j����v��n�;:�
DT	6��c�	�M��6������#��#UE�}�b)��(��V��`�ܨ]7��:y���qO<���g�w��2u�3����9wP�Y��o�pW�&��=�5t�n�����L'�g����͜g�8����np,b�