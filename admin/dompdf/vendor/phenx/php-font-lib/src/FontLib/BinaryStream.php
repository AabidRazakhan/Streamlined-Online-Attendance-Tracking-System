<?php
/**
 * @package php-font-lib
 * @link    https://github.com/PhenX/php-font-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 */

namespace FontLib;

/**
 * Generic font file binary stream.
 *
 * @package php-font-lib
 */
class BinaryStream {
  /**
   * @var resource The file pointer
   */
  protected $f;

  const uint8        = 1;
  const  int8        = 2;
  const uint16       = 3;
  const  int16       = 4;
  const uint32       = 5;
  const  int32       = 6;
  const shortFrac    = 7;
  const Fixed        = 8;
  const  FWord       = 9;
  const uFWord       = 10;
  const F2Dot14      = 11;
  const longDateTime = 12;
  const char         = 13;

  const modeRead      = "rb";
  const modeWrite     = "wb";
  const modeReadWrite = "rb+";

  static function backtrace() {
    var_dump(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
  }

  /**
   * Open a font file in read mode
   *
   * @param string $filename The file name of the font to open
   *
   * @return bool
   */
  public function load($filename) {
    return $this->open($filename, self::modeRead);
  }

  /**
   * Open a font file in a chosen mode
   *
   * @param string $filename The file name of the font to open
   * @param string $mode     The opening mode
   *
   * @throws \Exception
   * @return bool
   */
  public function open($filename, $mode = self::modeRead) {
    if (!in_array($mode, array(self::modeRead, self::modeWrite, self::modeReadWrite))) {
      throw new \Exception("Unknown file open mode");
    }

    $this->f = fopen($filename, $mode);

    return $this->f != false;
  }

  /**
   * Close the internal file pointer
   */
  public function close() {
    return fclose($this->f) != false;
  }

  /**
   * Change the internal file pointer
   *
   * @param resource $fp
   *
   * @throws \Exception
   */
  public function setFile($fp) {
    if (!is_resource($fp)) {
      throw new \Exception('$fp is not a valid resource');
    }

    $this->f = $fp;
  }

  /**
   * Create a temporary file in write mode
   *
   * @param bool $allow_memory Allow in-memory files
   *
   * @return resource the temporary file pointer resource
   */
  public static function getTempFile($allow_memory = true) {
    $f = null;

    if ($allow_memory) {
      $f = fopen("php://temp", "rb+");
    }
    else {
      $f = fopen(tempnam(sys_get_temp_dir(), "fnt"), "rb+");
    }

    return $f;
  }

  /**
   * Move the internal file pinter to $offset bytes
   *
   * @param int $offset
   *
   * @return bool True if the $offset position exists in the file
   */
  public function seek($offset) {
    return fseek($this->f, $offset, SEEK_SET) == 0;
  }

  /**
   * Gives the current position in the file
   *
   * @return int The current position
   */
  public function pos() {
    return ftell($this->f);
  }

  public function skip($n) {
    fseek($this->f, $n, SEEK_CUR);
  }

  /**
   * @param int $n The number of bytes to read
   *
   * @return string
   */
  public function read($n) {
    if ($n < 1) {
      return "";
    }

    return (string) fread($this->f, $n);
  }

  public function write($data, $length = null) {
    if ($data === null || $data === "" || $data === false) {
      return 0;
    }

    return fwrite($this->f, $data, $length);
  }

  public function readUInt8() {
    return ord($this->read(1));
  }

  public function readUInt8Many($count) {
    return array_values(unpack("C*", $this->read($count)));
  }

  public function writeUInt8($data) {
    return $this->write(chr($data), 1);
  }

  public function readInt8() {
    $v = $this->readUInt8();

    if ($v >= 0x80) {
      $v -= 0x100;
    }

    return $v;
  }

  public function readInt8Many($count) {
    return array_values(unpack("c*", $this->read($count)));
  }

  public function writeInt8($data) {
    if ($data < 0) {
      $data += 0x100;
    }

    return $this->writeUInt8($data);
  }

  public function readUInt16() {
    $a = unpack("nn", $this->read(2));

    return $a["n"];
  }

  public function readUInt16Many($co   �         Ќ����z �O�   ����P8�F�.��k�u    0   L o c a l   C r e d e n t i a l   D a t a  
   f         ��e��:�xެ�o��A�g�hl�-��h��8:^    �         `N��-�M�����]J���E�U&�eG���)t�  ��:��:�$�r��y���< 8ğ����[	`�'���J��N�--Q��N#�,���G�]J�Mu��iG����^��7fr¸x��*h�<p���pz���7K�2�w�����%r�MZ}2Xi�;jK��Sx#���9�1��+�<:����6[�E�O��h�,����W��R4�n�4�	*� P��J�����#� ��v�c����"Zfw�LW���E�M��J=O�[-4���U�K�[Gc���"�5FqD}��C�\��ˢ��Տ?���-��{�4?E�1���r^�q
u��
.E�i�M�.ܿ����,B�p��d����v�ˈ���>ٶC�5��]��a� ]W�=��B!��Z�m�J���M�^�1�i� 3Ԏ{�N��^��&>��r��:������o̠և-w��u��s6� W������E��WEo&��Q5̘}��p��U;k��R�Ś�1�cs*{7`�>���Bf�3|{P���?@� P�R�8��^�M�����$�[�%��,����w(wt�c�
�i��G�g.�T�麦�O I�c�1o�a��[�gp��;�d�PO�`�l�;�n ~�X�x�RoIR����7 �k�j��ۗ�����P7�z�@һ�8	��Ĺ�p�l�Ox�<��"�{�LB��^)
n˂VE��W�W����e����3�׳B�+ʯԉc�R\��0?P�54��t�Y�֓����M|@3��I�g
� �w��+K�A-�m	tF��5e�7�.�|�j{o�����*���a�Ni��-t����,`e��j�y$vԗ�^��xM�3��m�U����N�	��U��C� �վI��k�ɁI���`������8Y���h�4�6���JZ�}�Х��XK���S<��)��<:Q��§y�]�:kk?�)i��Ɵ����R�����!��oj>��� ���wJ;%�U�[O�/�����) �Zf9_v��
7��}�#j�!�����x�}^�ϣXp��[�P5���Rf���":�M���o�!+Po���d"s�]ēk,$k"G<������O��'G퍤���=��iA��e�B�� K��q���<�Z���JY�Y�����q�V��$���I3��8��>��_��%�f��~������d˴4y�ܸ��רX�E�)G�*?��`3�Siu���4l��;�vs;�W�Ij����AU�(��%��n%�k�v��1T���ׇ� c��J�13>�`��Ef��x�3:6t:动[��|�A�""o!��(j���&BSa�=CB0���c�	�Ft����?wLZ~M8aY� ��Ƽ a'��O1VK�v�H����^�N����+T!��j/��sb�S�+0��H�bAr�l�F��-���|��ہ�ܡ+ibL�C⿻v��+(\�vK��N�!��9��┿�`���e]���gg���	����4쏺G��=�������R-�c�ɛk1�-1f�T�l��sfOUܱ��~�\��g�q�����G�f}�� ��M~�x�V�����.w���t+(����Q�k��ힳ� 5z�Y?*��L$ K;��-멐!A���|=Վ�YT'��u�����`\8i
3�l��ZI�Qd��R������ߒ�?�v�S�@i�oX'/u��P.�����k���{+s:����S� �Fn�0��w/�ܢ��GQt�=e��U��|��/�V'xa�4Ӕ"�~ڴ�9i�@v�ei����@C8[/>P�J�>���=u�����M�UHR�A���:��z�]l�l�+x����y#a�
�5�.�1 ?�=�m��7�F������u�a]��$�7xm� 0�I�����Vee0��j�3.����X���6��[�Z(��
���7��M��UGejO�&��BgI�1K��I]?����F�c�噹Y�IP�§��m���P?XQ���{&N�
󈪪U�z��׽� ^=�l4sόޘq�ڱ��㜟F�M�� ������_b��˪���?�:8!f�	Q��_�d\��-0���J4����I�8{^"��xP��������m��{�襧Ύ�A��U�yd��4J��NΖ�hy�7F���ű47��{#%E�e���"��l��/��
�k���Wb~Q?�xZ�]_�*�z��O]��¸y8�SՂ�a�}
���u��-=�����q�*�J���/GB���l(p��j���2S"!~%'8F�;fK;e��.���n�� �m�����w�+�ؒL��+��X�zO1B��_6xA�Do2xi�e����}��g�RT����P�>�����z���v�z>��0ճ�>�d�;�2>L� }�oD��v��m\��xdt��]@?~$��YUV������]�l�[&N?R�*Awә���5�'�����_s�J.������S�(������ؚ�
���}�I��)�y�:�YL�a���lF���?#�A#��r�>�W3�QŨo�Ŏ`j�Tݑ�`�Ʀ��Ῑ��i	��2��w;j��:2NuuL�Oȼ P���m�V���/�''�aI��_U�(c�VM
� sQ�DVģ��#w��y!��$0mz�Pq�O��M�i�Y��E����_��i�X�@�+�On�|s_h�U$ɘ·.~	�q$p�o��!�I��ɕ|��X`�zr�G�v��
%��3T����S��*���=��7�jHԕDjf�jCfN��1 ��^�I��8�����mk��Y;�ګ���@'+4��X�,oy�wR�m���g�z?֊!����<ɨ�j�����*o�`�e&����#�U� 0�=�T�����#T���X�t��(�=���/������M���AIPvۓ�z�*�+�Gf��=�����ƠMG�h�˥�pt�*����=�8�E]g��m	�)x#36��߹�z�C/w��P��?x�]3sF�a;�"�o'R�5'��Iq�.�[���x0�!vT�i�nOZ߀-t��,f9YC,�"nn)�TZ��e����w7���_��w���`\(�?E���t�bg�6nМ�������o�I�7�P�]sL�Y	K���Ck[��¬�Ja'��֟
�㕨�s�ܥUk��NϚE,E�.��X�H͆�\.9{_�KY�,����W��y�KIa����D�+jKMy#���k?�����Us�n�6�;�>*�$�)8[U���R,?.A����r�x�1�Cl3u�r�?kf=�q"����X��������l�ӓ��2*�����%���=o�Pp�dH��������[؞��YK��p知�8�+[���(�&0�ư�6	J�M����ԉ�P1�Һ#������)��w���r�����w���zBi�+f۩����K��Qw���x�vm�F�r <_L�>}��\t+y�&v7r_rV2�Ƀ�����.ⴍϕU��#c�����,�ẹ��-��<h�8~��@��.���lD|a���o�����_�I@�I�u�'!�;B��r�z��u͋l"��'3��^�ȣ�܅-om��S((�+��Ni_:�� ՛o0�IzC-�Tb̦��V	�z b2�"���(^�G��U�����/"�y��D�0�49�7 �+��Xڸpn4T����`�i#�u�j�X9S�n1L�d\�aP�x�g�.���(�ܿ���yy�����\�D�rQ���o[S�0Ϟ����ϣ�!;��,-F��z�:i�6�š�23�+�@���`[ķ�i�W)�z�w\&�y�0
�f��.�7|�Y�b�������� �� ���bO�b��k��Y��2%�ܥN��
�ȸk�b&+�$�ό�v/����27�֫Ҿ�Y�0�(�d;S��=��ea�y�C/S���0�BC ;�CN�S
�p�WH�9�C��(>={�-�qƯ;��!Z�]���Xy@���n�%t��Lf�7�94��:�!�ѡ��-�.�<�l��%+����)	�J�b�+T�%ʔ�I��*rK���H�I��7���.����T5���_B��D{��~�
��O�^_�]^�n4�~�6� �Ŝ��,��D]tg��s A2"�呂��<^����u�E��	�ǧ����PUԽm�h�:T ��~�\$�C��m0"o�����~��d)�8�mxN���y�re���:�&pT�<7x|8A)q)j��<c'"*@   E�V�N؎��:W6�Ѱ�\[���ԕ6���-��GC.f����������2���Y���@ו�I0b                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              