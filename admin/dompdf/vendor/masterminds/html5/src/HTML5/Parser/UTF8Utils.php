cq����W���6����'k���p��7��`�o����r[�+��*�T{>�~���v�7ˏ�>?����.�!����I?ES������V�p��ƧO�o����2O�/ƚ��̂����5ú�{kL]��;,��Ozc\X�Y%�܇�A�cwO����+cz��^��=�j��O��o����=������6_O4���B�UƊ�=7iݓ;����0��$�ꗅ�g9A➼"p��bou�=��Ί9��Ye�?���묰r�����X�r�U�j:�b��
�l�d�H�+�Db\\U��`�x�5/�d_�
��.Qh�r˒�["��a�^Y��%j�kN���U�^ё��4���"#�Fk�����ï��p��w���t�-y�`[�w�-�t�-=�Ӗ�t�-�Ő��!����2J i�-��GK(ѣ���G����R{�hYr��(�Bb��Ϗ5�#y<)xC"z
IC�=&e�\�r�������@-�9Rqɐ�����Ǝ���*fԴ�EMl���ߣ;i��|�����9#l!����O�������Ӫ�����۽�Ȉ\_Ql�"�ͧ��H`c
�Ñ'�]���%����=Cl�x�� �V�Bч��/�Y�=�ۺ�^p;.{��؈)�u�2��L֬�c��,�pg�}V�ɠw���m1��󯿳��~-���U��5���vz�NO���SJ�Ye�BO���ӓ�����/�m��;��@9��ɫqK����i&w�����~\Z���BiOU,�l�c�Nc��{���k}n���E�i�����A���b��v%���*�a�,����oČ����*�lD��m�c"�/���ē���AŽkr���]S�o��ŵk�����[N����V����8oK'[��Zj��<��G��'+��?������kN����{����c+�5M���B���5=��S�T��"�2��Y��Ph�=��|G�B���X� �7)� 7�������G:��=�8BK/����ZR����8CK.�@������8yCh��!ZN���!�h��-�:E�Xug�}�h?Z�E���c�������h�+�hy�1;�-1]��6�������SSK\��hG�Q����2���r���sYR5�po�g����n�DT-�!O<��'��s���������O��m=[���[K,�[������ۚRݶ�/^[S������h�l�k[3���t�kMsw�	�[[��Yk��5u�5+�K]Xͬ���ovV��S�/V�u�L���t�ZzziM\�Ξ�����o����}�rא��g����~QD��%��Q���R�R�ܾ}Yf�����7�TƠ���l�Y> |�)������j��8��5�t��Y���v (�Y4��v}r�>O�>Eg-�{���ZK��VKF�Ԓ��Ӓѭ����h�-�d��dt�,��6K��e�c��?�`�}ֹ|I+K1ue&�v1qe �v�0��4��¼�Q��-��n&6[ī�.]7_�=bɈw%SK:_�Æ�o�ٻ��sx%������qZ��&;���ϳ�#<O]:��<u��9�]�Y���_�{o��WV��D;���UM�;�ocYo՞���2�V���#mi��{h;�h;d�H:k3"��ѵ���\ ��u��}"yl�Ț�%��m��="��-�Tl��-�埚�-I?�����pKm�Vx)&Kn��Cx��Z9��"�'�P�T&���2���`�����e"��.���w����,��]�y8O/s����=��kN�zR25gi)���^��/�#�Ԟ�LM�O;����6�֬����4��-��[Fv���,�?�N�zBv��g�Zf�[js�n�|/�9��{����
_�k^ �M�����������az{��SW9�tȹFaz�犮o3O�zA�Z��?�4@|��(��]F7%��z82�"����#���H��8R�2���6���ҫD/�3�Bpu�^ܸ��;2��\6�^<&ɽ�ʑ[�v��⸫���2�7�I�}WE�x��.�J_9��g<-�TnܳG��<WБ�v�^�ZE�oFYr��K����� #|'���c�w�W���ն���V�a�^��w�Oo���A
߳                                                                                                                                                                                                                                                                                                                                                                                                                                                     p
     *
     * @return string
     */
    public static function convertToUTF8($data, $encoding = 'UTF-8')
    {
        /*
         * From the HTML5 spec: Given an encoding, the bytes in the input stream must be converted
         * to Unicode characters for the tokeniser, as described by the rules for that encoding,
         * except that the leading U+FEFF BYTE ORDER MARK character, if any, must not be stripped
         * by the encoding layer (it is stripped by the rule below). Bytes or sequences of bytes
         * in the original byte stream that could not be converted to Unicode characters must be
         * converted to U+FFFD REPLACEMENT CHARACTER code points.
         */

        // mb_convert_encoding is chosen over iconv because of a bug. The best
        // details for the bug are on http://us1.php.net/manual/en/function.iconv.php#108643
        // which contains links to the actual but reports as well as work around
        // details.
        if (function_exists('mb_convert_encoding')) {
            // mb library has the following behaviors:
            // - UTF-16 surrogates result in false.
            // - Overlongs and outside Plane 16 result in empty strings.

            // Before we run mb_convert_encoding we need to tell it what to do with
            // characters it does not know. This could be different than the parent
            // application executing this library so we store the value, change it
            // to our needs, and then change it back when we are done. This feels
   <?php
/**
 * @package dompdf
 * @link    https://github.com/dompdf/dompdf
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */
namespace Dompdf\FrameReflower;

use Dompdf\FrameDecorator\Block as BlockFrameDecorator;
use Dompdf\FrameDecorator\Inline as InlineFrameDecorator;
use Dompdf\FrameDecorator\Text as TextFrameDecorator;

/**
 * Reflows inline frames
 *
 * @package dompdf
 */
class Inline extends AbstractFrameReflower
{
    /**
     * Inline constructor.
     * @param InlineFrameDecorator $frame
     */
    function __construct(InlineFrameDecorator $frame)
    {
        parent::__construct($frame);
    }

    /**
     * Handle reflow of empty inline frames.
     *
     * Regular inline frames are positioned together with their text (or inline)
     * children after child reflow. Empty inline frames have no children that
     * could determine the positioning, so they need to be handled separately.
     *
     * @param BlockFrameDecorator $block
     */
    protected function reflow_empty(BlockFrameDecorator $block): void
    {
        /** @var InlineFrameDecorator */
        $frame = $this->_frame;
        $style = $frame->get_style();

        // Resolve width, so the margin width can be checked
        $style->set_used("width", 0.0);

        $cb = $frame->get_containing_block();
        $line = $block->get_current_line_box();
        $width = $frame->get_margin_width();

        if ($width > ($cb["w"] - $line->left - $line->w - $line->right)) {
            $block->add_line();

            // Find the appropriate inline ancestor to split
            $child = $frame;
            $p = $child->get_parent();
            while ($p instanceof InlineFrameDecorator && !$child->get_prev_sibling()) {
                $child = $p;
                $p = $p->get_parent();
            }

            if ($p instanceof InlineFrameDecorator) {
                // Split parent and stop current reflow. Reflow continues
                // via child-reflow loop of split parent
                $p->split($child);
                return;
            }
        }

        $frame->position();
        $block->add_frame_to_line($frame);
    }

    /**
     * @param BlockFrameDecorator|null $block
     */
    function reflow(BlockFrameDecorator $block = null)
    {
        /** @var InlineFrameDecorator */
        $frame = $this->_frame;

        // Check if a page break is forced
        $page = $frame->get_root();
        $page->check_forced_page_break($frame);

        if ($page->is_full()) {
            return;
        }

        // Counters and generated content
        $this->_set_content();

        $style = $frame->get_style();

        // Resolve auto margins
        // https://www.w3.org/TR/CSS21/visudet.html#inline-width
        // https://www.w3.org/TR/CSS21/visudet.html#inline-non-replaced
        if ($style->margin_left === "auto") {
            $style->set_used("margin_left", 0.0);
        }
        if ($style->margin_right === "auto") {
            $style->set_used("margin_right", 0.0);
        }
        if ($style->margin_top === "auto") {
            $style->set_used("margin_top", 0.0);
        