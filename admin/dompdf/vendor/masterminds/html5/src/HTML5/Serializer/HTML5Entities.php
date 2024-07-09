<?php
/**
 * @file
 * This contains HTML5 entities to use with serializing.
 *
 * The list here is mildly different from the list at Entities because
 * that list was generated from the w3c. It contains some entities that are
 * not entirely proper such as &am; which maps to &. This list is meant to be
 * a fallback for PHP versions prior to PHP 5.4 when dealing with encoding.
 */

namespace Masterminds\HTML5\Serializer;

/**
 * A mapping of entities to their html5 representation.
 * Used for older PHP
 * versions that don't have the mapping.
 */
class HTML5Entities
{
    public static $map = array(
        '	' => '&Tab;',
        "\n" => '&NewLine;',
        '!' => '&excl;',
        '"' => '&quot;',
        '#' => '&num;',
        '$' => '&dollar;',
        '%' => '&percnt;',
        '&' => '&amp;',
        '\'' => '&apos;',
        '(' => '&lpar;',
        ')' => '&rpar;',
        '*' => '&ast;',
        '+' => '&plus;',
        ',' => '&comma;',
        '.' => '&period;',
        '/' => '&sol;',
        ':' => '&colon;',
        ';' => '&semi;',
        '<' => '&lt;',
        '<⃒' => '&nvlt',
        '=' => '&equals;',
        '=⃥' => '&bne',
        '>' => '&gt;',
        '>⃒' => '&nvgt',
        '?' => '&quest;',
        '@' => '&commat;',
        '[' => '&lbrack;',
        '\\' => '&bsol;',
        ']' => '&rsqb;',
        '^' => '&Hat;',
        '_' => '&lowbar;',
        '`' => '&grave;',
        'fj' => '&fjlig',
        '{' => '&lbrace;',
        '|' => '&vert;',
        '}' => '&rcub;',
        ' ' => '&nbsp;',
        '¡' => '&iexcl;',
        '¢' => '&cent;',
        '£' => '&pound;',
        '¤' => '&curren;',
        '¥' => '&yen;',
        '¦' => '&brvbar;',
        '§' => '&sect;',
        '¨' => '&DoubleDot;',
        '©' => '&copy;',
        'ª' => '&ordf;',
        '«' => '&laquo;',
        '¬' => '&not;',
        '­' => '&shy;',
        '®' => '&reg;',
        '¯' => '&macr;',
        '°' => '&deg;',
        '±' => '&plusmn;',
        '²' => '&sup2;',
        '³' => '&sup3;',
        '´' => '&DiacriticalAcute;',
        'µ' => '&micro;',
        '¶' => '&para;',
        '·' => '&CenterDot;',
        '¸' => '&Cedilla;',
        '¹' => '&sup1;',
        'º' => '&ordm;',
        '»' => '&raquo;',
        '¼' => '&frac14;',
        '½' => '&half;',
        '¾' => '&frac34;',
        '¿' => '&iquest;',
        'À' => '&Agrave;',
        'Á' => '&Aacute;',
        'Â' => '&Acirc;',
        'Ã' => '&Atilde;',
        'Ä' => '&Auml;',
        'Å' => '&Aring;',
        'Æ' => '&AElig;',
        'Ç' => '&Ccedil;',
        'È' => '&Egrave;',
        'É' => '&Eacute;',
        'Ê' => '&Ecirc;',
        'Ë' => '&Euml;',
        'Ì' => '&Igrave;',
        'Í' => '&Iacute;',
        'Î' => '&Icirc;',
        'Ï' => '&Iuml;',
        'Ð' => '&ETH;',
        'Ñ' => '&Ntilde;',
        'Ò' => '&Ograve;',
        'Ó' => '&Oacute;',
        'Ô' => '&Ocirc;',
        'Õ' => '&Otilde;',
        'Ö' => '&Ouml;',
        '×' => '&times;',
        'Ø' => '&Oslash;',
        'Ù' => '&Ugrave;',
        'Ú' => '&Uacute;',
        'Û' => '&Ucirc;',
        'Ü' => '&Uuml;',
        'Ý' => '&Yacute;',
        'Þ' => '&THORN;',
        'ß' => '&szlig;',
        'à' => '&agrave;',
        'á' => '&aacute;',
        'â' => '&acirc;',
        'ã' => '&atilde;',
        'ä' => '&auml;',
        'å' => '&aring;',
        'æ' => '&aelig;',
        'ç' => '&ccedil;',
        'è' => '&egrave;',
        'é' => '&eacute;',
        'ê' => '&ecirc;',
        'ë' => '&euml;',
        'ì' => '&igrave;',
        'í' => '&iacute;',
        'î' => '&icirc;',
        'ï' => '&iuml;',
        'ð' => '&eth;',
        'ñ' => '&ntilde;',
        'ò' => '&ograve;',
        'ó' => '&oacute;',
        'ô' => '&ocirc;',
        'õ' => '&otilde;',
        'ö' => '&ouml;',
        '÷' => '&divide;',
        'ø' => '&oslash;',
        'ù' => '&ugrave;',
        'ú' => '&uacute;',
        'û' => '&ucirc;',
        'ü' => '&uuml;',
        'ý' => '&yacute;',
        'þ' => '&thorn;',
        'ÿ' => '&yuml;',
        'Ā' => '&Amacr;',
        'ā' => '&amacr;',
        'Ă' => '&Abreve;',
        'ă' => '&abreve;',
        'Ą' => '&Aogon;',
        'ą' => '&aogon;',
        'Ć' => '&Cacute;',
        'ć' => '&cacute;',
        'Ĉ' => '&Ccirc;',
        'ĉ' => '&ccirc;',
        'Ċ' => '&Cdot;',
        'ċ' => '&cdot;',
        'Č' => '&Ccaron;',
        'č' => '&ccaron;',
        'Ď' => '&Dcaron;',
        'ď' => '&dcaron;',
        'Đ' => '&Dstrok;',
        'đ' => '&dstrok;',
        'Ē' => '&Emacr;',
        'ē' => '&emacr;',
        'Ė' => '&Edot;',
        'ė' => '&edot;',
        'Ę' => '&Eogon;',
        'ę' => '&eogon;',
        'Ě' => '&Ecaron;',
        'ě' => '&ecaron;',
        'Ĝ' => '&Gcirc;',
        'ĝ' => '&gcirc;',
        'Ğ' => '&Gbreve;',
        'ğ' => '&gbreve;',
        'Ġ' => '&Gdot;',
        'ġ' => '&gdot;',
        'Ģ' => '&Gcedil;',
        'Ĥ' => '&Hcirc;',
        'ĥ' => '&hcirc;',
        'Ħ' => '&Hstrok;',
        'ħ' => '&hstrok;',
        'Ĩ' => '&Itilde;',
        'ĩ' => '&itilde;',
        'Ī' => '&Imacr;',
        'ī' => '&imacr;',
        'Į' => '&Iogon;',
        'į' => '&iogon;',
        'İ' => '&Idot;',
        'ı' => '&inodot;',
        'Ĳ' => '&IJlig;',
        'ĳ' => '&ijlig;',
        'Ĵ' => '&Jcirc;',
        'ĵ' => '&jcirc;',
        'Ķ' => '&Kcedil;',
        'ķ' => '&kcedil;',
        'ĸ' => '&kgreen;',
        'Ĺ' => '&Lacute;',
        'ĺ' => '&lacute;',
        'Ļ' => '&Lcedil;',
        'ļ' => '&lcedil;',
        'Ľ' => '&Lcaron;',
        'ľ' => '&lcaron;',
        'Ŀ' => '&Lmidot;',
        'ŀ' => '&lmidot;',
        'Ł' => '&Lstrok;',
        'ł' => '&lstrok;',
        'Ń' => '&Nacute;',
        'ń' => '&nacute;',
        'Ņ' => '&Ncedil;',
        'ņ' => '&ncedil;',
        'Ň' => '&Ncaron;',
        'ň' => '&ncaron;',
        'ŉ' => '&napos;',
        'Ŋ' => '&ENG;',
        'ŋ' => '&eng;',
        'Ō' => '&Omacr;',
        'ō' => '&omacr;',
        'Ő' => '&Odblac;',
        'ő' => '&odblac;',
        'Œ' => '&OElig;',
        'œ' => '&oelig;',
        'Ŕ' => '&Racute;',
        'ŕ' => '&racute;',
        'Ŗ' => '&Rcedil;',
        'ŗ' => '&rcedil;',
        'Ř' => '&Rcaron;',
        'ř' => '&rcaron;',
        'Ś' => '&Sacute;',
        'ś' => '&sacute;',
        'Ŝ' => '&Scirc;',
        'ŝ' => '&scirc;',
        'Ş' => '&Scedil;',
        'ş' => '&scedil;',
        'Š' => '&Scaron;',
        'š' => '&scaron;',
        'Ţ' => '&Tcedil;',
        'ţ' => '&tcedil;',
        'Ť' => '&Tcaron;',
        'ť' => '&tcaron;',
        'Ŧ' => '&Tstrok;',
        'ŧ' => '&tstrok;',
        'Ũ' => '&Utilde;',
        'ũ' => '&utilde;',
        'Ū' => '&Umacr;',
        'ū' => '&umacr;',
        'Ŭ' => '&Ubreve;',
        'ŭ' => '&ubreve;',
        'Ů' => '&Uring;',
        'ů' => '&uring;',
        'Ű' => '&Udblac;',
        'ű' => '&udblac;',
        'Ų' => '&Uogon;',
        'ų' => '&uogon;',
        'Ŵ' => '&Wcirc;',
        'ŵ' => '&wcirc;',
        'Ŷ' => '&Ycirc;',
        'ŷ' => '&ycirc;',
        'Ÿ' => '&Yuml;',
        'Ź' => '&Zacute;',
        'ź' => '&zacute;',
        'Ż' => '&Zdot;',
        'ż' => '&zdot;',
        'Ž' => '&Zcaron;',
        'ž' => '&zcaron;',
        'ƒ' => '&fnof;',
        'Ƶ' => '&imped;',
        'ǵ' => '&gacute;',
        'ȷ' => '&jmath;',
        'ˆ' => '&circ;',
        'ˇ' => '&Hacek;',
        '˘' => '&Breve;',
        '˙' => '&dot;',
        '˚' => '&ring;',
        '˛' => '&ogon;',
        '˜' => '&DiacriticalTilde;',
        '˝' => '&DiacriticalDoubleAcute;',
        '̑' => '&DownBreve;',
        'Α' => '&Alpha;',
        'Β' => '&Beta;',
        'Γ' => '&Gamma;',
        'Δ' => '&Delta;',
        'Ε' => '&Epsilon;',
        'Ζ' => '&Zeta;',
        'Η' => '&Eta;',
        'Θ' => '&Theta;',
        'Ι' => '&Iota;',
        'Κ' => '&Kappa;',
        'Λ' => '&Lambda;',
        'Μ' => '&Mu;',
        'Ν' => '&Nu;',
        'Ξ' => '&Xi;',
        MZ�       ��  �       @                                   �   � �	�!�L�!This program cannot be run in DOS mode.
$       PE  L v�d        � !           �/       @    @                       �     Z�   @�                           h/  S    @               x%   `                                                                       H           .text   �                           `.rsrc      @                    @  @.reloc      `                    @  B                �/      H     4-  4  	       P   b  �,  �                                   ^  ���   �   lSystem.Resources.ResourceReader, mscorlib, Version=4.0.0.0, Culture=neutral, PublicKeyToken=b77a5c561934e089#System.Resources.RuntimeResourceSet          PADPADP'G���q�˄>�<�[���P�R?ÿ��,�oT�Q�-���Oۜ���Dd�/ ��ݼ�})�Ev�fjU����� ��0s�A5��O���O��Zx
���  �  ;  T  H  S   �  T  �  M  �    �  �    �   �  #  `      h    �   s  �  �  NA p p l i e d S y n c h r o n i z a t i o n C o n t e x t N o t A l l o w e d     >C a n n o t U p g r a d e N o n U p g r a d e a b l e L o c k G   RD a n g e r o u s R e a d L o c k R e q u e s t F r o m W r i t e L o c k F o r k �   ,F r a m e M u s t B e P u s h e d F i r s t �   *I n v a l i d A f t e r C o m p l e t e d �   I n v a l i d L o c k �   $I n v a l i d W i t h o u t L o c k   PJ o i n a b l e T a s k C o n t e x t A n d C o l l e c t i o n M i s m a t c h +  PJ o i n a b l e T a s k C o n t e x t N o d e A l r e a d y R e g i s t e r e d p   L a z y V a l u e F a u l t e d �  &L a z y V a l u e N o t C r e a t e d �  <L o c k C o m p l e t i o n A l r e a d y R e q u e s t e d �  BM u l t i p l e C o n t i n u a t i o n s N o t S u p p o r t e d   0N o t A l l o w e d U n d e r U R o r W L o c k .  &P u s h F r o m W r o n g T h r e a d �  Q u e u e E m p t y �  2S T A T h r e a d C a l l e r N o t A l l o w e d �  (S e m a p h o r e A l r e a d y H e l d     S e m a p h o r e M i s u s e d A  :S e m a p h o r e S t a c k N e s t i n g V i o l a t e d o  ZS w i t c h T o M a i n T h r e a d F a i l e d T o R e a c h E x p e c t e d T h r e a d �  DS y n c C o n t e x t F r a m e M i s m a t c h e d A f f i n i t y �  "S y n c C o n t e x t N o t S e t �  ,V a l u e F a c t o r y R e e n t r a n c y �  "W r i t e L o c k O u t l i v e d &  E不允许获取已应用 SynchronizationContext 的线程上的锁。<不可升级的读取锁由调用方持有，无法升级。-来自写锁分叉的危险的读锁请求。必须先推送此实例。已经过渡到完成状态。$只能对有效锁执行此操作。锁是必需项。CJoinableTask 不属于之前用于实例化此集合的上下文。此节点已注册。*延迟创建的值在构造期间出错。!尚未构造延迟创建的值。<此所已标记为完成。没有可用的新的顶级锁。不支持多个延续。h持有来自 AsyncReaderWriterLock 的活动的可升级的读或写锁定时不允许执行此操作。*消息泵只能从原始线程中运行。队列为空。)无法在 STA 线程上完成此操作。?信号灯已被占用，并且重新进入的设置为 "{0}"。,此信号量已被误用, 无法再使用。[当可重入设置为: "{0}" 时, 嵌套的信号量请求必须以后进先出顺序释放�尝试切换到主线程未能到达预期线程。JoinableTaskContext 在错误的线程上进行了初始化，还是其 SynchronizationContext 的 Post 方法未在主线程上执行其委托？*此框架已与其他实例一起使用。8未设置要到达主线程的 SynchronizationContext。*值工厂已对相同实例调用了值。?写锁定以嵌套读锁定为生存期，这是不允许的。�i"v_�����v�>��Z?l9���W�\<�Q4�^��1��c����%�Tl���z���~�kgA.��WU��>1�2�^`�#���\��ߝ<�����2}tLuxq�?�����Ә����cvz_\E�)  BSJB         v4.0.30319     l   d   #~  �   �   #Strings    x     #US �     #GUID   �  �   #Blob               �%3                 x                 �            
 5        =      <Module> Microsoft.VisualStudio.Threading.resources zh-Hans Microsoft.VisualStudio.Threading.Strings.zh-Hans.resources Microsoft.VisualStudio.Threading.resources.dll         �|�mI�@��D����# �� $  �  �      $  RSA1     ��WĮ��.�������j쏇�vl�L���;�����ݚ�6!�r<�����w��wO)�2�����!�����d\L����(]b,�e,��=t]o-��~^�Ė=&�Ce m��4MZғ �/          �/                          �/                _CorDllMain mscoree.dll     �%  @                                                                                 �                  0  �               	  H   X@  �          �4   V S _ V E R S I O N _ I N F O     ���     �                               D    V a r F i l e I n f o     $    T r a n s l a t i o n     �   S t r i n g F i l e I n f o   �   0 8 0 4 0 4 b 0   � d  C o m m e n t s   _ekTek�WCQ0_ekƖT0T P L   �TpencAmibU\0J o i n a b l e T a s k F a c t o r y   AQ��Tek;�bk  U I   �~zۏL�Tek�]\O0dkS�(u�N�NUO  . N E T   �^(uz�^( NP��N  V i s u a l   S t u d i o ) 0  4 
  C o m p a n y N a m e     M i c r o s o f t   j !  F i l e D e s c r i p t i o n     M i c r o s o f t . V i s u a l S t u d i o . T h r e a d i n g     :   F i l e V e r s i o n     1 7 . 7 . 3 0 . 5 6 0 7     ~ /  I n t e r n a l N a m e   M i c r o s o f t . V i s u a l S t u d i o . T h r e a d i n g . r e s o u r c e s . d l l     d    L e g a l C o p y r i g h t   �   M i c r o s o f t   C o r p o r a t i o n 0�OYu@b	gCg)R0  � /  O r i g i n a l F i l e n a m e   M i c r o s o f t . V i s u a l S t u d i o . T h r e a d i n g . r e s o u r c e s . d l l     b !  P r o d u c t N a m e     M i c r o s o f t . V i s u a l S t u d i o . T h r e a d i n g     P   P r o d u c t V e r s i o n   1 7 . 7 . 3 0 + 1 5 e 7 0 c 6 9 9 3 . R R                                                                                                                                                                                                                                                          �?                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      x%    0�%f	*�H����%W0�%S10	`�He 0\
+�7�N0L0
+�70	 ��� 010	`�He  s�F�Dܖ��t����8�@�N������\�i���g0��0�נ3   '�2oCs{�     0	*�H�� 0~10	UUS10U
Washington10URedmond10U
Microsoft Corporation1(0&UMicrosoft Code Signing PCA 20100230216201111Z240131201111Z0t10	UUS10U
Washington10URedmond10U
Microsoft Corporation10UMicrosoft Corporation0�"0	*�H�� � 0�
� ő�-L�	��֩��H��1x`�r�d[rޏ�o�>������P.D���_^ppbԖ�p���h�&�>2��0}m��"� �#x�q�xX�$!]~-�������Q��C'1lv`��ĦWh�����!=�љ�m �������4���I�Z�@]9��U�4�{�*)ߌ��!M^<}��.���������Go�C�m���y"[c�_J�wz@|^D���FJ��ptÕ��sx���96grS��t.�� ��n0�j0U%0
+�7=+0U�
�w0a5�̶�b�:*5)#0EU>0<�:0810UMicrosoft Corporation10U230865+5002310U#0���_{�" X�rN��!t#2��0VUO0M0K�I�G�Ehttp://crl.microsoft.com/pki/crl/products/MicCodSigPCA_2010-07-06.crl0Z+N0L0J+0�>http://www.microsoft.com/pki/certs/MicCodSigPCA_2010-07-06.crt0U�0 0	*�H�� � ��CԍF-�Sļ�5q���D�]g��ʵ7R�+�W-z�u�)���	�U�zj/����sF�de�9���CK+}��;`�����Ύ��k�x��:5���&���_���`��(:L6xn=��t��D�Y�i� �͘&�)���r2O���8D��R$O�I169P�L�?3k܏#`UΦ�@	IS+G͌SC����
��"���ȳX���r� U�)p �^����ҟ/�y:5�E0�p0�X�
aRL     0	*�H�� 0��10	UUS10U
Washington10URedmond10U
Microsoft Corporation1200U)Microsoft Root Certificate Authority 20100100706204017Z250706205017Z0~10	UUS10U
Washington10URedmond10U
Microsoft Corporation1(0&UMicrosoft Code Signing PCA 20100�"0	*�H�� � 0�
� �dPyg����	 L����Vh�D���XO��v|mE��9�����e��ҏ�D��e��,U��}�.+�A+��KnILk���q�͵K���̈�k�:��&?���4�W�]I��*.Յ�Y?���+�t�+�;F��FI�fT���UbWr�g�% 4�]���^�(��ղ���cӲ��Ȋ&
Y���5L��R[�����HwօG�����j-\`ƴ*[�#_E�o7�3�j�M�jfcx��0ϕ ���0��0	+�7 0U��_{�" X�rN��!t#2��0	+�7
 S u b C A0U�0U�0�0U#0���Vˏ�\bh�=��[�Κ�0VUO0M0K�I�G�Ehttp://crl.microsoft.com/pki/crl/products/MicRooCerAut_2010-06-23.crl0Z+N0L0J+0�>http://www.microsoft.com/pki/certs/MicRooCerAut_2010-06-23.crt0��U ��0��0��	+�7.0��0=+1http://www.microsoft.com/PKI/docs/CPS/default.htm0@+042  L e g a l _ P o l i c y _ S t a t e m e n t . 0	*�H�� � t�WO){��x�P�"�	�����4�*,����Ͽ���4�ہ�� ��5o��y�w������Na��Z#���bQEg�?<��0��9@���!)奡i�"��t��GC�S��0i��% moa����r ,i�v=Qۦ9H�7am�S˧�a¿⃫�k���}(Q��JQ��lȷJi����~�Ip����rGc��֢���D�c��i��F�z?��!�{�#-�A˿L�ﱜ�"KI�n�v[�Sy������=s5�<�T�RGj���Ҏڙg^2��7���u����ZW�¿����-���'ӵ^i���$gs�MO��V�z��RM�wO�����B	�v�#Vx"&6�ʱ�n���G3b��ɑ3_q@��e�"�B!%�-`�7�A�*�a<�h`R��G���@��w>��SP8��f3'9x�6�N�_��=GS����a=*ג,�7Z>@B1��V��$]Q�jy������{%qD�j����#��u�1�r0�n0��0~10	UUS10U
Washington10URedmond10U
Microsoft Corporation1(0&UMicrosoft Code Signing PCA 20103   '�2oCs{�     0	`�He ���0	*�H��	1
+�70
+�710
+�70/	*�H��	1" n����S��n�1�P<�q�T1����mh�]�0B
+�71402�� M i c r o s o f t��http://www.microsoft.com0	*�H�� � ;~��U��h�����%믺���;z/%Zyt�)v��ҳ��k�ߴ!���=\'Zΰ�?��ʎ��Q~��FneS�е��t ւa����;ˠQ�A�O��L.�2�O���B��¡V�����W�B�w�|�ڵ�1>!��Q!����#���U��vC��t{.����6-� ����� ؄i�cV�����uL����9͂ڪ�3��<�@S�!|�χϵJ��hV1��hm<#�C-��HI ����,����0��
+�71��0��	*�H�����0��10	`�He 0�P*�H��	��?�;0�7
+�Y
010	`�He  צ��j�9@l�'3ٖ( "�*U��m���d���20230620030606.93Z0����Ф��0��10	UUS10U
Washington10URedmond10U
Microsoft Corporation1%0#UMicrosoft America Operations1&0$UThales TSS ESN:7BF1-E3EA-B8081%0#UMicrosoft Time-Stamp Service��T0�0���3  ����g��kF   �0	*�H�� 0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20100221104190137Z240202190137Z0��10	UUS10U
Washington10URedmond10U
Microsoft Corporation1%0#UMicrosoft America Operations1&0$UThales TSS ESN:7BF1-E3EA-B8081%0#UMicrosoft Time-Stamp Service0�"0	*�H�� � 0�
� �˝~(O�$V�yȸ��st´Y4\�£S���ag�i~�"wI�g����0�mW���α��ǖcm�t���c We)r(��ң�@�}-@�p��.!�(r��� ����f�k�;Ӹ�;�]��q<`6$D&��Kd����H�oRgKeϗ9F0��.������'�)���H�v��k�f�,�����Jݱ^^��9psٝh_���������0!�QӞ>}�@���[=Ѩ���Yy�KLw�k t��q9���e��i����=m�Y�����k�RըY�`�''�L��f)��ck��S�Z�����Y!a��5��
��SHO�Wg��P0��X�7���܄�K�cOj9/g��0�,R�-�ڼ��m��U�Z�gv��"~	:A��O��W�o>�*�g��,l�����*�\XK=�O�+*���2i`�Ż�Qx��u��fw��s�>�)y�--��X��oz�`+g}��Q���"�X���V���A���5��@�*Y�'7����	�� ��60�20U�\/I�����ӈ�S�"�5K��0U#0���] ^b]����e�S5�r0_UX0V0T�R�P�Nhttp://www.microsoft.com/pkiops/crl/Microsoft%20Time-Stamp%20PCA%202010(1).crl0l+`0^0\+0�Phttp://www.microsoft.com/pkiops/certs/Microsoft%20Time-Stamp%20PCA%202010(1).crt0U�0 0U%0
+0	*�H�� � ��rR�Cé('~Ab|��x��6FBm���OW�}1�Q�B[�7y*w�*�oK[)5�����X�)�a�5��ڂ���}��� |<���&�y�E�h�(.P��}��}���c����e�	�㝽��.��)�5���@}�x�쇑
D�`�8'�-�<Rĸq�s�yʝ��'ظ�t*���7� }�W�1���5�����?v�؈��<3�~{��zg�6��X#�l%�aDI���R��?磯�DEm����~3�~�'�6�w�X�R�2���#�t���S�|��[�U�p����'΋b ��	��_l��P�fv5�&g
� ������'��Dui`�rm���*7�R�Qk7�9���U�G*�;m
���}������u�5���_��Ѣ"��\M���?� ���en��`����y�?!�6���tj!���4A�=:r��((�Sݔ��6@-�yzs�F2������Ǫ0�q0�Y�3   ��k��I�     0	*�H�� 0��10	UUS10U
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
�4�$5g+��挙��"��'B=%��tt[jў>�~�13}���{�8pDѐ�ȫ:�:b�pcSM��m��qj�U3X��pf��0�40����Ф��0��10	UUS10U
Washington10URedmond10U
Microsoft Corporation1%0#UMicrosoft America Operations1&0$UThales TSS ESN:7BF1-E3EA-B8081%0#UMicrosoft Time-Stamp Service�#
0+ ��P��54
R] b���˲���0���~0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20100	*�H��  �;��0"20230620101342Z20230621101342Z0t0:
+�Y
1,0*0
 �;�� 0 &�0 =0
 �<�V 06
+�Y
1(0&0
+�Y
�
0 � �
0 ��0	*�H�� �� 5���٨h]0�RE�"c*�T�0�| ��w�G3i�h�!��F�V�sfj"�	�R���M &T���J߱^I'E�;7Etw�:N��ߌ�1��m����`��@�$��;9���[������d1�0�	0��0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20103  ����g��kF   �0	`�He ��J0	*�H��	1*�H��	0/	*�H��	1" �����r^/%��x�M��� ݤ��Fw0��*�H��	/1��0��0��0�� b ���?m�/��r��/AV�7oOg�w�>�f0��0���~0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20103  ����g��kF   �0" �Ƙ����z�Y��7"��d��Vh!�d�=u�y0	*�H�� � xd�)�@�ϖ\�RI	|M�:��P�;zh��)�K�
�(���S����x�T� ���8���u��$Xx�<ȅH��M��gl��jT�#s]"=�[OTla�k�[d�x��+qb�iG>�ư�m$��
q�z�-�~��]��; Q]��Us�H)D5��B�CBdA�!�����Y�Y�^f[lJ�$�ۇ�?C��2�_x�k`�â�,	`;!/e�jrh+���q�|AZ���q�V0����(䔵l��K��V^����Xi�c�Ү�v�u�}4�$M_�u�����n�~#aBF�����9�wr���%8e����M�B�܆�[��e�-qLu	��4V9r	>�/.�(��$�YO?� ���=��ei~Ce�܆�������t�UTe�����gr��Q���ߋbߙGN�!����&`G�˴$���+v��O���F/�b��D�A�ȴ����B�B.%x�nHx��x���p�>��E�4����|^Ɇ��                                                                                                                                              cceedsSlantEqual;',
        '⋢' => '&NotSquareSubsetEqual;',
        '⋣' => '&NotSquareSupersetEqual;',
        '⋦' => '&lnsim;',
        '⋧' => '&gnsim;',
        '⋨' => '&precnsim;',
        '⋩' => '&scnsim;',
        '⋪' => '&nltri;',
        '⋫' => '&ntriangleright;',
        '⋬' => '&nltrie;',
        '⋭' => '&NotRightTriangleEqual;',
        '⋮' => '&vellip;',
        '⋯' => '&ctdot;',
        '⋰' => '&utdot;',
        '⋱' => '&dtdot;',
        '⋲' => '&disin;',
        '⋳' => '&isinsv;',
        '⋴' => '&isins;',
        '⋵' => '&isindot;',
        '⋵̸' => '&notindot',
        '⋶' => '&notinvc;',
        '⋷' => '&notinvb;',
        '⋹' => '&isinE;',
        '⋹̸' => '&notinE',
        '⋺' => '&nisd;',
        '⋻' => '&xnis;',
        '⋼' => '&nis;',
        '⋽' => '&notnivc;',
        '⋾' => '&notnivb;',
        '⌅' => '&barwed;',
        '⌆' => '&doublebarwedge;',
        '⌈' => '&lceil;',
        '⌉' => '&RightCeiling;',
        '⌊' => '&LeftFloor;',
        '⌋' => '&RightFloor;',
        '⌌' => '&drcrop;',
        '⌍' => '&dlcrop;',
        '⌎' => '&urcrop;',
        '⌏' => '&ulcrop;',
        '⌐' => '&bnot;',
        '⌒' => '&profline;',
        '⌓' => '&profsurf;',
        '⌕' => '&telrec;',
        '⌖' => '&target;',
        '⌜' => '&ulcorner;',
        '⌝' => '&urcorner;',
        '⌞' => '&llcorner;',
        '⌟' => '&drcorn;',
        '⌢' => '&frown;',
        '⌣' => '&smile;',
        '⌭' => '&cylcty;',
        '⌮' => '&profalar;',
        '⌶' => '&topbot;',
        '⌽' => '&ovbar;',
        '⌿' => '&solbar;',
        '⍼' => '&angzarr;',
        '⎰' => '&lmoust;',
        '⎱' => '&rmoust;',
        '⎴' => '&OverBracket;',
        '⎵' => '&bbrk;',
        '⎶' => '&bbrktbrk;',
        '⏜' => '&OverParenthesis;',
        '⏝' => '&UnderParenthesis;',
        '⏞' => '&OverBrace;',
        '⏟' => '&UnderBrace;',
        '⏢' => '&trpezium;',
        '⏧' => '&elinters;',
        '␣' => '&blank;',
        'Ⓢ' => '&oS;',
        '─' => '&HorizontalLine;',
        '│' => '&boxv;',
        '┌' => '&boxdr;',
        '┐' => '&boxdl;',
        '└' => '&boxur;',
        '┘' => '&boxul;',
        '├' => '&boxvr;',
        '┤' => '&boxvl;',
        '┬' => '&boxhd;',
        '┴' => '&boxhu;',
        '┼' => '&boxvh;',
        '═' => '&boxH;',
        '║' => '&boxV;',
        '╒' => '&boxdR;',
        '╓' => '&boxDr;',
        '╔' => '&boxDR;',
        '╕' => '&boxdL;',
        '╖' => '&boxDl;',
        '╗' => '&boxDL;',
        '╘' => '&boxuR;',
        '╙' => '&boxUr;',
        '╚' => '&boxUR;',
        '╛' => '&boxuL;',
        '╜' => '&boxUl;',
        '╝' => '&boxUL;',
        '╞' => '&boxvR;',
        '╟' => '&boxVr;',
        '╠' => '&boxVR;',
        '╡' => '&boxvL;',
        '╢' => '&boxVl;',
        '╣' => '&boxVL;',
        '╤' => '&boxHd;',
        '╥' => '&boxhD;',
        '╦' => '&boxHD;',
        '╧' => '&boxHu;',
        '╨' => '&boxhU;',
        '╩' => '&boxHU;',
        '╪' => '&boxvH;',
        '╫' => '&boxVh;',
        '╬' => '&boxVH;',
        '▀' => '&uhblk;',
        '▄' => '&lhblk;',
        '█' => '&block;',
        '░' => '&blk14;',
        '▒' => '&blk12;',
        '▓' => '&blk34;',
        '□' => '&Square;',
        '▪' => '&squarf;',
        '▫' => '&EmptyVerySmallSquare;',
        '▭' => '&rect;',
        '▮' => '&marker;',
        '▱' => '&fltns;',
        '△' => '&bigtriangleup;',
        '▴' => '&blacktriangle;',
        '▵' => '&triangle;',
        '▸' => '&blacktriangleright;',
        '▹' => '&rtri;',
        '▽' => '&bigtriangledown;',
        '▾' => '&blacktriangledown;',
        '▿' => '&triangledown;',
        '◂' => '&blacktriangleleft;',
        '◃' => '&ltri;',
        '◊' => '&lozenge;',
        '○' => '&cir;',
        '◬' => '&tridot;',
        '◯' => '&bigcirc;',
        '◸' => '&ultri;',
        '◹' => '&urtri;',
        '◺' => '&lltri;',
        '◻' => '&EmptySmallSquare;',
        '◼' => '&FilledSmallSquare;',
        '★' => '&starf;',
        '☆' => '&star;',
        '☎' => '&phone;',
        '♀' => '&female;',
        '♂' => '&male;',
        '♠' => '&spadesuit;',
        '♣' => '&clubs;',
        '♥' => '&hearts;',
        '♦' => '&diamondsuit;',
        '♪' => '&sung;',
        '♭' => '&flat;',
        '♮' => '&natur;',
        '♯' => '&sharp;',
        '✓' => '&check;',
        '✗' => '&cross;',
        '✠' => '&maltese;',
        '✶' => '&sext;',
        '❘' => '&VerticalSeparator;',
        '❲' => '&lbbrk;',
        '❳' => '&rbbrk;',
        '⟈' => '&bsolhsub;',
        '⟉' => '&suphsol;',
        '⟦' => '&LeftDoubleBracket;',
        '⟧' => '&RightDoubleBracket;',
        '⟨' => '&langle;',
        '⟩' => '&RightAngleBracket;',
        '⟪' => '&Lang;',
        '⟫' => '&Rang;',
        '⟬' => '&loang;',
        '⟭' => '&roang;',
        '⟵' => '&longleftarrow;',
        '⟶' => '&LongRightArrow;',
        '⟷' => '&LongLeftRightArrow;',
        '⟸' => '&xlArr;',
        '⟹' => '&DoubleLongRightArrow;',
        '⟺' => '&xhArr;',
        '⟼' => '&xmap;',
        '⟿' => '&dzigrarr;',
        '⤂' => '&nvlArr;',
        '⤃' => '&nvrArr;',
        '⤄' => '&nvHarr;',
        '⤅' => '&Map;',
        '⤌' => '&lbarr;',
        '⤍' => '&bkarow;',
        '⤎' => '&lBarr;',
        '⤏' => '&dbkarow;',
        '⤐' => '&drbkarow;',
        '⤑' => '&DDotrahd;',
        '⤒' => '&UpArrowBar;',
        '⤓' => '&DownArrowBar;',
        '⤖' => '&Rarrtl;',
        '⤙' => '&latail;',
        '⤚' => '&ratail;',
        '⤛' => '&lAtail;',
        '⤜' => '&rAtail;',
        '⤝' => '&larrfs;',
        '⤞' => '&rarrfs;',
        '⤟' => '&larrbfs;',
        '⤠' => '&rarrbfs;',
        '⤣' => '&nwarhk;',
        '⤤' => '&nearhk;',
        '⤥' => '&searhk;',
        '⤦' => '&swarhk;',
        '⤧' => '&nwnear;',
        '⤨' => '&toea;',
        '⤩' => '&seswar;',
        '⤪' => '&swnwar;',
        '⤳' => '&rarrc;',
        '⤳̸' => '&nrarrc',
        '⤵' => '&cudarrr;',
        '⤶' => '&ldca;',
        '⤷' => '&rdca;',
        '⤸' => '&cudarrl;',
        '⤹' => '&larrpl;',
        '⤼' => '&curarrm;',
        '⤽' => '&cularrp;',
        '⥅' => '&rarrpl;',
        '⥈' => '&harrcir;',
        '⥉' => '&Uarrocir;',
        '⥊' => '&lurdshar;',
        '⥋' => '&ldrushar;',
        '⥎' => '&LeftRightVector;',
        '⥏' => '&RightUpDownVector;',
        '⥐' => '&DownLeftRightVector;',
        '⥑' => '&LeftUpDownVector;',
        '⥒' => '&LeftVectorBar;',
        '⥓' => '&RightVectorBar;',
        '⥔' => '&RightUpVectorBar;',
        '⥕' => '&RightDownVectorBar;',
        '⥖' => '&DownLeftVectorBar;',
        '⥗' => '&DownRightVectorBar;',
        '⥘' => '&LeftUpVectorBar;',
        '⥙' => '&LeftDownVectorBar;',
        '⥚' => '&LeftTeeVector;',
        '⥛' => '&RightTeeVector;',
        '⥜' => '&RightUpTeeVector;',
        '⥝' => '&RightDownTeeVector;',
        '⥞' => '&DownLeftTeeVector;',
        '⥟' => '&DownRightTeeVector;',
        '⥠' => '&LeftUpTeeVector;',
        '⥡' => '&LeftDownTeeVector;',
        '⥢' => '&lHar;',
        '⥣' => '&uHar;',
        '⥤' => '&rHar;',
        '⥥' => '&dHar;',
        '⥦' => '&luruhar;',
        '⥧' => '&ldrdhar;',
        '⥨' => '&ruluhar;',
        '⥩' => '&rdldhar;',
        '⥪' => '&lharul;',
        '⥫' => '&llhard;',
        '⥬' => '&rharul;',
        '⥭' => '&lrhard;',
        '⥮' => '&udhar;',
        '⥯' => '&ReverseUpEquilibrium;',
        '⥰' => '&RoundImplies;',
        '⥱' => '&erarr;',
        '⥲' => '&simrarr;',
        '⥳' => '&larrsim;',
        '⥴' => '&rarrsim;',
        '⥵' => '&rarrap;',
        '⥶' => '&ltlarr;',
        '⥸' => '&gtrarr;',
        '⥹' => '&subrarr;',
        '⥻' => '&suplarr;',
        '⥼' => '&lfisht;',
        '⥽' => '&rfisht;',
        '⥾' => '&ufisht;',
        '⥿' => '&dfisht;',
        '⦅' => '&lopar;',
        '⦆' => '&ropar;',
        '⦋' => '&lbrke;',
        '⦌' => '&rbrke;',
        '⦍' => '&lbrkslu;',
        '⦎' => '&rbrksld;',
        '⦏' => '&lbrksld;',
        '⦐' => '&rbrkslu;',
        '⦑' => '&langd;',
        '⦒' => '&rangd;',
        '⦓' => '&lparlt;',
        '⦔' => '&rpargt;',
        '⦕' => '&gtlPar;',
        '⦖' => '&ltrPar;',
        '⦚' => '&vzigzag;',
        '⦜' => '&vangrt;',
        '⦝' => '&angrtvbd;',
        '⦤' => '&ange;',
        '⦥' => '&range;',
        '⦦' => '&dwangle;',
        '⦧' => '&uwangle;',
        '⦨' => '&angmsdaa;',
        '⦩' => '&angmsdab;',
        '⦪' => '&angmsdac;',
        '⦫' => '&angmsdad;',
        '⦬' => '&angmsdae;',
        '⦭' => '&angmsdaf;',
        '⦮' => '&angmsdag;',
        '⦯' => '&angmsdah;',
        '⦰' => '&bemptyv;',
        '⦱' => '&demptyv;',
        '⦲' => '&cemptyv;',
        '⦳' => '&raemptyv;',
        '⦴' => '&laemptyv;',
        '⦵' => '&ohbar;',
        '⦶' => '&omid;',
        '⦷' => '&opar;',
        '⦹' => '&operp;',
        '⦻' => '&olcross;',
        '⦼' => '&odsold;',
        '⦾' => '&olcir;',
        '⦿' => '&ofcir;',
        '⧀' => '&olt;',
        '⧁' => '&ogt;',
        '⧂' => '&cirscir;',
        '⧃' => '&cirE;',
        '⧄' => '&solb;',
        '⧅' => '&bsolb;',
        '⧉' => '&boxbox;',
        '⧍' => '&trisb;',
        '⧎' => '&rtriltri;',
        '⧏' => '&LeftTriangleBar;',
        '⧏̸' => '&NotLeftTriangleBar',
        '⧐' => '&RightTriangleBar;',
        '⧐̸' => '&NotRightTriangleBar',
        '⧜' => '&iinfin;',
        '⧝' => '&infintie;',
        '⧞' => '&nvinfin;',
        '⧣' => '&eparsl;',
        '⧤' => '&smeparsl;',
        '⧥' => '&eqvparsl;',
        '⧫' => '&lozf;',
        '⧴' => '&RuleDelayed;',
        '⧶' => '&dsol;',
        '⨀' => '&xodot;',
        '⨁' => '&bigoplus;',
        '⨂' => '&bigotimes;',
        '⨄' => '&biguplus;',
        '⨆' => '&bigsqcup;',
        '⨌' => '&iiiint;',
        '⨍' => '&fpartint;',
        '⨐' => '&cirfnint;',
        '⨑' => '&awint;',
        '⨒' => '&rppolint;',
        '⨓' => '&scpolint;',
        '⨔' => '&npolint;',
        '⨕' => '&pointint;',
        '⨖' => '&quatint;',
        '⨗' => '&intlarhk;',
        '⨢' => '&pluscir;',
        '⨣' => '&plusacir;',
        '⨤' => '&simplus;',
        '⨥' => '&plusdu;',
        '⨦' => '&plussim;',
        '⨧' => '&plustwo;',
        '⨩' => '&mcomma;',
        '⨪' => '&minusdu;',
        '⨭' => '&loplus;',
        '⨮' => '&roplus;',
        '⨯' => '&Cross;',
        '⨰' => '&timesd;',
        '⨱' => '&timesbar;',
        '⨳' => '&smashp;',
        '⨴' => '&lotimes;',
        '⨵' => '&rotimes;',
        '⨶' => '&otimesas;',
        '⨷' => '&Otimes;',
        '⨸' => '&odiv;',
        '⨹' => '&triplus;',
        '⨺' => '&triminus;',
        '⨻' => '&tritime;',
        '⨼' => '&iprod;',
        '⨿' => '&amalg;',
        '⩀' => '&capdot;',
        '⩂' => '&ncup;',
        '⩃' => '&ncap;',
        '⩄' => '&capand;',
        '⩅' => '&cupor;',
        '⩆' => '&cupcap;',
        '⩇' => '&capcup;',
        '⩈' => '&cupbrcap;',
        '⩉' => '&capbrcup;',
        '⩊' => '&cupcup;',
        '⩋' => '&capcap;',
        '⩌' => '&ccups;',
        '⩍' => '&ccaps;',
        '⩐' => '&ccupssm;',
        '⩓' => '&And;',
        '⩔' => '&Or;',
        '⩕' => '&andand;',
        '⩖' => '&oror;',
        '⩗' => '&orslope;',
        '⩘' => '&andslope;',
        '⩚' => '&andv;',
        '⩛' => '&orv;',
        '⩜' => '&andd;',
        '⩝' => '&ord;',
        '⩟' => '&wedbar;',
        '⩦' => '&sdote;',
        '⩪' => '&simdot;',
        '⩭' => '&congdot;',
        '⩭̸' => '&ncongdot',
        '⩮' => '&easter;',
        '⩯' => '&apacir;',
        '⩰' => '&apE;',
        '⩰̸' => '&napE',
        '⩱' => '&eplus;',
        '⩲' => '&pluse;',
        '⩳' => '&Esim;',
        '⩴' => '&Colone;',
        '⩵' => '&Equal;',
        '⩷' => '&ddotseq;',
        '⩸' => '&equivDD;',
        '⩹' => '&ltcir;',
        '⩺' => '&gtcir;',
        '⩻' => '&ltquest;',
        '⩼' => '&gtquest;',
        '⩽' => '&les;',
        '⩽̸' => '&nles',
        '⩾' => '&ges;',
        '⩾̸' => '&nges',
        '⩿' => '&lesdot;',
        '⪀' => '&gesdot;',
        '⪁' => '&lesdoto;',
        '⪂' => '&gesdoto;',
        '⪃' => '&lesdotor;',
        '⪄' => '&gesdotol;',
        '⪅' => '&lap;',
        '⪆' => '&gap;',
        '⪇' => '&lne;',
        '⪈' => '&gne;',
        '⪉' => '&lnap;',
        '⪊' => '&gnap;',
        '⪋' => '&lesseqqgtr;',
        '⪌' => '&gEl;',
        '⪍' => '&lsime;',
        '⪎' => '&gsime;',
        '⪏' => '&lsimg;',
        '⪐' => '&gsiml;',
        '⪑' => '&lgE;',
        '⪒' => '&glE;',
        '⪓' => '&lesges;',
        '⪔' => '&gesles;',
        '⪕' => '&els;',
        '⪖' => '&egs;',
        '⪗' => '&elsdot;',
        '⪘' => '&egsdot;',
        '⪙' => '&el;',
        '⪚' => '&eg;',
        '⪝' => '&siml;',
        '⪞' => '&simg;',
        '⪟' => '&simlE;',
        '⪠' => '&simgE;',
        '⪡' => '&LessLess;',
        '⪡̸' => '&NotNestedLessLess',
        '⪢' => '&GreaterGreater;',
        '⪢̸' => '&NotNestedGreaterGreater',
        '⪤' => '&glj;',
        '⪥' => '&gla;',
        '⪦' => '&ltcc;',
        '⪧' => '&gtcc;',
        '⪨' => '&lescc;',
        '⪩' => '&gescc;',
        '⪪' => '&smt;',
        '⪫' => '&lat;',
        '⪬' => '&smte;',
        '⪬︀' => '&smtes',
        '⪭' => '&late;',
        '⪭︀' => '&lates',
        '⪮' => '&bumpE;',
        '⪯' => '&preceq;',
        '⪯̸' => '&NotPrecedesEqual',
        '⪰' => '&SucceedsEqual;',
        '⪰̸' => '&NotSucceedsEqual',
        '⪳' => '&prE;',
        '⪴' => '&scE;',
        '⪵' => '&precneqq;',
        '⪶' => '&scnE;',
        '⪷' => '&precapprox;',
        '⪸' => '&succapprox;',
        '⪹' => '&precnapprox;',
        '⪺' => '&succnapprox;',
        '⪻' => '&Pr;',
        '⪼' => '&Sc;',
        '⪽' => '&subdot;',
        '⪾' => '&supdot;',
        '⪿' => '&subplus;',
        '⫀' => '&supplus;',
        '⫁' => '&submult;',
        '⫂' => '&supmult;',
        '⫃' => '&subedot;',
        '⫄' => '&supedot;',
        '⫅' => '&subE;',
        '⫅̸' => '&nsubE',
        '⫆' => '&supseteqq;',
        '⫆̸' => '&nsupseteqq',
        '⫇' => '&subsim;',
        '⫈' => '&supsim;',
        '⫋' => '&subsetneqq;',
        '⫋︀' => '&vsubnE',
        '⫌' => '&supnE;',
        '⫌︀' => '&varsupsetneqq',
        '⫏' => '&csub;',
        '⫐' => '&csup;',
        '⫑' => '&csube;',
        '⫒' => '&csupe;',
        '⫓' => '&subsup;',
        '⫔' => '&supsub;',
        '⫕' => '&subsub;',
        '⫖' => '&supsup;',
        '⫗' => '&suphsub;',
        '⫘' => '&supdsub;',
        '⫙' => '&forkv;',
        '⫚' => '&topfork;',
        '⫛' => '&mlcp;',
        '⫤' => '&Dashv;',
        '⫦' => '&Vdashl;',
        '⫧' => '&Barv;',
        '⫨' => '&vBar;',
        '⫩' => '&vBarv;',
        '⫫' => '&Vbar;',
        '⫬' => '&Not;',
        '⫭' => '&bNot;',
        '⫮' => '&rnmid;',
        '⫯' => '&cirmid;',
        '⫰' => '&midcir;',
        '⫱' => '&topcir;',
        '⫲' => '&nhpar;',
        '⫳' => '&parsim;',
        '⫽︀' => '&varsupsetneqq',
        'ﬀ' => '&fflig;',
        'ﬁ' => '&filig;',
        'ﬂ' => '&fllig;',
        'ﬃ' => '&ffilig;',
        'ﬄ' => '&ffllig;',
        '𝒜' => '&Ascr;',
        '𝒞' => '&Cscr;',
        '𝒟' => '&Dscr;',
        '𝒢' => '&Gscr;',
        '𝒥' => '&Jscr;',
        '𝒦' => '&Kscr;',
        '𝒩' => '&Nscr;',
        '𝒪' => '&Oscr;',
        '𝒫' => '&Pscr;',
        '𝒬' => '&Qscr;',
        '𝒮' => '&Sscr;',
        '𝒯' => '&Tscr;',
        '𝒰' => ��� �c�   E@g<  (                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
� *x ��%�qG��}Q��a4��x��������	F	�	v�	���7 
C
	���WWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            2	+	5mail.google.com__Host-GMAIL_SCH_GMS/� �2	+	5mail.google.com__Host-GMAIL_SCH_GMN/� �2	+	5mail.google.com__Host-GMAIL_SCH_GML/� �"	+	mail.google.comOSID/� �,	+	mail.google.comCOMPASS/sync/u/0�{,	+	mail.google.comCOMPASS/mail/u/0�z)	+	mail.google.comCOMPASS/mail� �$	+	mail.google.comCOMPASS/�x�                  2	7	)edgeservices.bing.comEDGSRVCPERSIST/��+	7	edgeservices.bing.comEDGSRVC/��+	?	login.microsoftonline.comfpc/��,	?	login.microsoftonline.combuid/��%	3	login.microsoft.comfpc/��<     	%	www.bing.comMUIDB/��C	)	Ylogin.live.comMicrosoftApplicationsTelemetryDeviceId/���        )	7	edgeservices.bing.comMUIDB/��/	7	#edgeservices.bing.comEDGSRVCSCEN/��	 &                                  &	7	copilot.microsoft.com_U/��'	7	copilot.microsoft.com_SS/��@	#	Yntp.msn.comMicrosoftApplicationsTelemetryDeviceId/��5	/	7www.jetbrains.comstg_returning_visitor/��4	/	5www.jetbrains.comstg_last_interaction/��P	/	mwww.jetbrains.com_pk_id.f93eea86-00df-4291-b4d9-fdb2ccdcf222.9139/��
n 3                                               /	5	%stories.flipkart.compll_language/�� 	!	unstop.comg_state/��&	!	'unstop.comallowedCookie/�S!	)	www.clarity.msCLID/�G6	1	7www.hackerrank.comremember_hacker_token/��+	1	!www.hackerrank.comreact_var2/��*	1	www.hackerrank.comreact_var/��u �                                                                                                                                              