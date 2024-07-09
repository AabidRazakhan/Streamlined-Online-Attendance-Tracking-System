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
        '<âƒ’' => '&nvlt',
        '=' => '&equals;',
        '=âƒ¥' => '&bne',
        '>' => '&gt;',
        '>âƒ’' => '&nvgt',
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
        'Â ' => '&nbsp;',
        'Â¡' => '&iexcl;',
        'Â¢' => '&cent;',
        'Â£' => '&pound;',
        'Â¤' => '&curren;',
        'Â¥' => '&yen;',
        'Â¦' => '&brvbar;',
        'Â§' => '&sect;',
        'Â¨' => '&DoubleDot;',
        'Â©' => '&copy;',
        'Âª' => '&ordf;',
        'Â«' => '&laquo;',
        'Â¬' => '&not;',
        'Â­' => '&shy;',
        'Â®' => '&reg;',
        'Â¯' => '&macr;',
        'Â°' => '&deg;',
        'Â±' => '&plusmn;',
        'Â²' => '&sup2;',
        'Â³' => '&sup3;',
        'Â´' => '&DiacriticalAcute;',
        'Âµ' => '&micro;',
        'Â¶' => '&para;',
        'Â·' => '&CenterDot;',
        'Â¸' => '&Cedilla;',
        'Â¹' => '&sup1;',
        'Âº' => '&ordm;',
        'Â»' => '&raquo;',
        'Â¼' => '&frac14;',
        'Â½' => '&half;',
        'Â¾' => '&frac34;',
        'Â¿' => '&iquest;',
        'Ã€' => '&Agrave;',
        'Ã' => '&Aacute;',
        'Ã‚' => '&Acirc;',
        'Ãƒ' => '&Atilde;',
        'Ã„' => '&Auml;',
        'Ã…' => '&Aring;',
        'Ã†' => '&AElig;',
        'Ã‡' => '&Ccedil;',
        'Ãˆ' => '&Egrave;',
        'Ã‰' => '&Eacute;',
        'ÃŠ' => '&Ecirc;',
        'Ã‹' => '&Euml;',
        'ÃŒ' => '&Igrave;',
        'Ã' => '&Iacute;',
        'Ã' => '&Icirc;',
        'Ã' => '&Iuml;',
        'Ã' => '&ETH;',
        'Ã‘' => '&Ntilde;',
        'Ã’' => '&Ograve;',
        'Ã“' => '&Oacute;',
        'Ã”' => '&Ocirc;',
        'Ã•' => '&Otilde;',
        'Ã–' => '&Ouml;',
        'Ã—' => '&times;',
        'Ã˜' => '&Oslash;',
        'Ã™' => '&Ugrave;',
        'Ãš' => '&Uacute;',
        'Ã›' => '&Ucirc;',
        'Ãœ' => '&Uuml;',
        'Ã' => '&Yacute;',
        'Ã' => '&THORN;',
        'ÃŸ' => '&szlig;',
        'Ã ' => '&agrave;',
        'Ã¡' => '&aacute;',
        'Ã¢' => '&acirc;',
        'Ã£' => '&atilde;',
        'Ã¤' => '&auml;',
        'Ã¥' => '&aring;',
        'Ã¦' => '&aelig;',
        'Ã§' => '&ccedil;',
        'Ã¨' => '&egrave;',
        'Ã©' => '&eacute;',
        'Ãª' => '&ecirc;',
        'Ã«' => '&euml;',
        'Ã¬' => '&igrave;',
        'Ã­' => '&iacute;',
        'Ã®' => '&icirc;',
        'Ã¯' => '&iuml;',
        'Ã°' => '&eth;',
        'Ã±' => '&ntilde;',
        'Ã²' => '&ograve;',
        'Ã³' => '&oacute;',
        'Ã´' => '&ocirc;',
        'Ãµ' => '&otilde;',
        'Ã¶' => '&ouml;',
        'Ã·' => '&divide;',
        'Ã¸' => '&oslash;',
        'Ã¹' => '&ugrave;',
        'Ãº' => '&uacute;',
        'Ã»' => '&ucirc;',
        'Ã¼' => '&uuml;',
        'Ã½' => '&yacute;',
        'Ã¾' => '&thorn;',
        'Ã¿' => '&yuml;',
        'Ä€' => '&Amacr;',
        'Ä' => '&amacr;',
        'Ä‚' => '&Abreve;',
        'Äƒ' => '&abreve;',
        'Ä„' => '&Aogon;',
        'Ä…' => '&aogon;',
        'Ä†' => '&Cacute;',
        'Ä‡' => '&cacute;',
        'Äˆ' => '&Ccirc;',
        'Ä‰' => '&ccirc;',
        'ÄŠ' => '&Cdot;',
        'Ä‹' => '&cdot;',
        'ÄŒ' => '&Ccaron;',
        'Ä' => '&ccaron;',
        'Ä' => '&Dcaron;',
        'Ä' => '&dcaron;',
        'Ä' => '&Dstrok;',
        'Ä‘' => '&dstrok;',
        'Ä’' => '&Emacr;',
        'Ä“' => '&emacr;',
        'Ä–' => '&Edot;',
        'Ä—' => '&edot;',
        'Ä˜' => '&Eogon;',
        'Ä™' => '&eogon;',
        'Äš' => '&Ecaron;',
        'Ä›' => '&ecaron;',
        'Äœ' => '&Gcirc;',
        'Ä' => '&gcirc;',
        'Ä' => '&Gbreve;',
        'ÄŸ' => '&gbreve;',
        'Ä ' => '&Gdot;',
        'Ä¡' => '&gdot;',
        'Ä¢' => '&Gcedil;',
        'Ä¤' => '&Hcirc;',
        'Ä¥' => '&hcirc;',
        'Ä¦' => '&Hstrok;',
        'Ä§' => '&hstrok;',
        'Ä¨' => '&Itilde;',
        'Ä©' => '&itilde;',
        'Äª' => '&Imacr;',
        'Ä«' => '&imacr;',
        'Ä®' => '&Iogon;',
        'Ä¯' => '&iogon;',
        'Ä°' => '&Idot;',
        'Ä±' => '&inodot;',
        'Ä²' => '&IJlig;',
        'Ä³' => '&ijlig;',
        'Ä´' => '&Jcirc;',
        'Äµ' => '&jcirc;',
        'Ä¶' => '&Kcedil;',
        'Ä·' => '&kcedil;',
        'Ä¸' => '&kgreen;',
        'Ä¹' => '&Lacute;',
        'Äº' => '&lacute;',
        'Ä»' => '&Lcedil;',
        'Ä¼' => '&lcedil;',
        'Ä½' => '&Lcaron;',
        'Ä¾' => '&lcaron;',
        'Ä¿' => '&Lmidot;',
        'Å€' => '&lmidot;',
        'Å' => '&Lstrok;',
        'Å‚' => '&lstrok;',
        'Åƒ' => '&Nacute;',
        'Å„' => '&nacute;',
        'Å…' => '&Ncedil;',
        'Å†' => '&ncedil;',
        'Å‡' => '&Ncaron;',
        'Åˆ' => '&ncaron;',
        'Å‰' => '&napos;',
        'ÅŠ' => '&ENG;',
        'Å‹' => '&eng;',
        'ÅŒ' => '&Omacr;',
        'Å' => '&omacr;',
        'Å' => '&Odblac;',
        'Å‘' => '&odblac;',
        'Å’' => '&OElig;',
        'Å“' => '&oelig;',
        'Å”' => '&Racute;',
        'Å•' => '&racute;',
        'Å–' => '&Rcedil;',
        'Å—' => '&rcedil;',
        'Å˜' => '&Rcaron;',
        'Å™' => '&rcaron;',
        'Åš' => '&Sacute;',
        'Å›' => '&sacute;',
        'Åœ' => '&Scirc;',
        'Å' => '&scirc;',
        'Å' => '&Scedil;',
        'ÅŸ' => '&scedil;',
        'Å ' => '&Scaron;',
        'Å¡' => '&scaron;',
        'Å¢' => '&Tcedil;',
        'Å£' => '&tcedil;',
        'Å¤' => '&Tcaron;',
        'Å¥' => '&tcaron;',
        'Å¦' => '&Tstrok;',
        'Å§' => '&tstrok;',
        'Å¨' => '&Utilde;',
        'Å©' => '&utilde;',
        'Åª' => '&Umacr;',
        'Å«' => '&umacr;',
        'Å¬' => '&Ubreve;',
        'Å­' => '&ubreve;',
        'Å®' => '&Uring;',
        'Å¯' => '&uring;',
        'Å°' => '&Udblac;',
        'Å±' => '&udblac;',
        'Å²' => '&Uogon;',
        'Å³' => '&uogon;',
        'Å´' => '&Wcirc;',
        'Åµ' => '&wcirc;',
        'Å¶' => '&Ycirc;',
        'Å·' => '&ycirc;',
        'Å¸' => '&Yuml;',
        'Å¹' => '&Zacute;',
        'Åº' => '&zacute;',
        'Å»' => '&Zdot;',
        'Å¼' => '&zdot;',
        'Å½' => '&Zcaron;',
        'Å¾' => '&zcaron;',
        'Æ’' => '&fnof;',
        'Æµ' => '&imped;',
        'Çµ' => '&gacute;',
        'È·' => '&jmath;',
        'Ë†' => '&circ;',
        'Ë‡' => '&Hacek;',
        'Ë˜' => '&Breve;',
        'Ë™' => '&dot;',
        'Ëš' => '&ring;',
        'Ë›' => '&ogon;',
        'Ëœ' => '&DiacriticalTilde;',
        'Ë' => '&DiacriticalDoubleAcute;',
        'Ì‘' => '&DownBreve;',
        'Î‘' => '&Alpha;',
        'Î’' => '&Beta;',
        'Î“' => '&Gamma;',
        'Î”' => '&Delta;',
        'Î•' => '&Epsilon;',
        'Î–' => '&Zeta;',
        'Î—' => '&Eta;',
        'Î˜' => '&Theta;',
        'Î™' => '&Iota;',
        'Îš' => '&Kappa;',
        'Î›' => '&Lambda;',
        'Îœ' => '&Mu;',
        'Î' => '&Nu;',
        'Î' => '&Xi;',
        MZ       ÿÿ  ¸       @                                   €   º ´	Í!¸LÍ!This program cannot be run in DOS mode.
$       PE  L v‘d        à !           ¾/       @    @                       €     Z×   @…                           h/  S    @               x%   `                                                                       H           .text   Ä                           `.rsrc      @                    @  @.reloc      `                    @  B                 /      H     4-  4  	       P   b  ²,  €                                   ^  ÎÊï¾   ‘   lSystem.Resources.ResourceReader, mscorlib, Version=4.0.0.0, Culture=neutral, PublicKeyToken=b77a5c561934e089#System.Resources.RuntimeResourceSet          PADPADP'GˆÆq–Ë„>—<Û[¸ ˜P¼R?Ã¿¤˜,ÃoTĞQ®-ÔÿûOÛœœÜæDdå/ ìüİ¼ğ})ûEv˜fjU¸çÕèæ §·0së˜A5ÉÊO¯€ÑO—Zx
ı¼³  ‘  ;  T  H  S   Ì  T  æ  M  Ã    ğ  ‹    í   Œ  #  `      h    –   s  ü  ÷  NA p p l i e d S y n c h r o n i z a t i o n C o n t e x t N o t A l l o w e d     >C a n n o t U p g r a d e N o n U p g r a d e a b l e L o c k G   RD a n g e r o u s R e a d L o c k R e q u e s t F r o m W r i t e L o c k F o r k …   ,F r a m e M u s t B e P u s h e d F i r s t ´   *I n v a l i d A f t e r C o m p l e t e d Ñ   I n v a l i d L o c k ñ   $I n v a l i d W i t h o u t L o c k   PJ o i n a b l e T a s k C o n t e x t A n d C o l l e c t i o n M i s m a t c h +  PJ o i n a b l e T a s k C o n t e x t N o d e A l r e a d y R e g i s t e r e d p   L a z y V a l u e F a u l t e d ‡  &L a z y V a l u e N o t C r e a t e d ³  <L o c k C o m p l e t i o n A l r e a d y R e q u e s t e d Ö  BM u l t i p l e C o n t i n u a t i o n s N o t S u p p o r t e d   0N o t A l l o w e d U n d e r U R o r W L o c k .  &P u s h F r o m W r o n g T h r e a d ˜  Q u e u e E m p t y Ä  2S T A T h r e a d C a l l e r N o t A l l o w e d Õ  (S e m a p h o r e A l r e a d y H e l d     S e m a p h o r e M i s u s e d A  :S e m a p h o r e S t a c k N e s t i n g V i o l a t e d o  ZS w i t c h T o M a i n T h r e a d F a i l e d T o R e a c h E x p e c t e d T h r e a d Ì  DS y n c C o n t e x t F r a m e M i s m a t c h e d A f f i n i t y ”  "S y n c C o n t e x t N o t S e t À  ,V a l u e F a c t o r y R e e n t r a n c y ú  "W r i t e L o c k O u t l i v e d &  Eä¸å…è®¸è·å–å·²åº”ç”¨ SynchronizationContext çš„çº¿ç¨‹ä¸Šçš„é”ã€‚<ä¸å¯å‡çº§çš„è¯»å–é”ç”±è°ƒç”¨æ–¹æŒæœ‰ï¼Œæ— æ³•å‡çº§ã€‚-æ¥è‡ªå†™é”åˆ†å‰çš„å±é™©çš„è¯»é”è¯·æ±‚ã€‚å¿…é¡»å…ˆæ¨é€æ­¤å®ä¾‹ã€‚å·²ç»è¿‡æ¸¡åˆ°å®ŒæˆçŠ¶æ€ã€‚$åªèƒ½å¯¹æœ‰æ•ˆé”æ‰§è¡Œæ­¤æ“ä½œã€‚é”æ˜¯å¿…éœ€é¡¹ã€‚CJoinableTask ä¸å±äºä¹‹å‰ç”¨äºå®ä¾‹åŒ–æ­¤é›†åˆçš„ä¸Šä¸‹æ–‡ã€‚æ­¤èŠ‚ç‚¹å·²æ³¨å†Œã€‚*å»¶è¿Ÿåˆ›å»ºçš„å€¼åœ¨æ„é€ æœŸé—´å‡ºé”™ã€‚!å°šæœªæ„é€ å»¶è¿Ÿåˆ›å»ºçš„å€¼ã€‚<æ­¤æ‰€å·²æ ‡è®°ä¸ºå®Œæˆã€‚æ²¡æœ‰å¯ç”¨çš„æ–°çš„é¡¶çº§é”ã€‚ä¸æ”¯æŒå¤šä¸ªå»¶ç»­ã€‚hæŒæœ‰æ¥è‡ª AsyncReaderWriterLock çš„æ´»åŠ¨çš„å¯å‡çº§çš„è¯»æˆ–å†™é”å®šæ—¶ä¸å…è®¸æ‰§è¡Œæ­¤æ“ä½œã€‚*æ¶ˆæ¯æ³µåªèƒ½ä»åŸå§‹çº¿ç¨‹ä¸­è¿è¡Œã€‚é˜Ÿåˆ—ä¸ºç©ºã€‚)æ— æ³•åœ¨ STA çº¿ç¨‹ä¸Šå®Œæˆæ­¤æ“ä½œã€‚?ä¿¡å·ç¯å·²è¢«å ç”¨ï¼Œå¹¶ä¸”é‡æ–°è¿›å…¥çš„è®¾ç½®ä¸º "{0}"ã€‚,æ­¤ä¿¡å·é‡å·²è¢«è¯¯ç”¨, æ— æ³•å†ä½¿ç”¨ã€‚[å½“å¯é‡å…¥è®¾ç½®ä¸º: "{0}" æ—¶, åµŒå¥—çš„ä¿¡å·é‡è¯·æ±‚å¿…é¡»ä»¥åè¿›å…ˆå‡ºé¡ºåºé‡Šæ”¾Åå°è¯•åˆ‡æ¢åˆ°ä¸»çº¿ç¨‹æœªèƒ½åˆ°è¾¾é¢„æœŸçº¿ç¨‹ã€‚JoinableTaskContext åœ¨é”™è¯¯çš„çº¿ç¨‹ä¸Šè¿›è¡Œäº†åˆå§‹åŒ–ï¼Œè¿˜æ˜¯å…¶ SynchronizationContext çš„ Post æ–¹æ³•æœªåœ¨ä¸»çº¿ç¨‹ä¸Šæ‰§è¡Œå…¶å§”æ‰˜ï¼Ÿ*æ­¤æ¡†æ¶å·²ä¸å…¶ä»–å®ä¾‹ä¸€èµ·ä½¿ç”¨ã€‚8æœªè®¾ç½®è¦åˆ°è¾¾ä¸»çº¿ç¨‹çš„ SynchronizationContextã€‚*å€¼å·¥å‚å·²å¯¹ç›¸åŒå®ä¾‹è°ƒç”¨äº†å€¼ã€‚?å†™é”å®šä»¥åµŒå¥—è¯»é”å®šä¸ºç”Ÿå­˜æœŸï¼Œè¿™æ˜¯ä¸å…è®¸çš„ã€‚Îi"v_şª³îïvÚ>©˜Z?l9ô¨«WÎ\<õQ4ä^÷¢1ßîªcŸ¬ÃÑ%®Tl’ËÕz­ù²~økgA.üáWUÔî>1²2À^`·#ªöğ\²Šß<ØÇ¢áı2}tLuxq°?¹‹ÁŒÓ˜°âœ÷cvz_\EÄ)  BSJB         v4.0.30319     l   d   #~  Ğ   ¨   #Strings    x     #US €     #GUID     ¤   #Blob               ú%3                 x                 €            
 5        =      <Module> Microsoft.VisualStudio.Threading.resources zh-Hans Microsoft.VisualStudio.Threading.Strings.zh-Hans.resources Microsoft.VisualStudio.Threading.resources.dll         ä|æ½mI‡@‘òD³şïñ# €  $  €  ”      $  RSA1     ÑúWÄ®Ùğ£.„ª®ıéèıjì‡ûvlƒL™’²;çšÙÕÜÁİšÒ6!r<ù€•ÄáwÆwO)è2’êìäè!À¥ïèñd\L“Á«™(]b,ªe,úÖ=t]o-åñ~^¯Ä–=&ŠCe mÀ“4MZÒ“ /          ®/                           /                _CorDllMain mscoree.dll     ÿ%  @                                                                                 €                  0  €               	  H   X@  ¸          ¸4   V S _ V E R S I O N _ I N F O     ½ïş     ç                               D    V a r F i l e I n f o     $    T r a n s l a t i o n     °   S t r i n g F i l e I n f o   ô   0 8 0 4 0 4 b 0   à d  C o m m e n t s   _ekTekúWCQ0_ekÆ–T0T P L   ŒTpencAmibU\0J o i n a b l e T a s k F a c t o r y   AQ¸‹Tek;–bk  U I   ¿~zÛLˆTekå]\O0dkS(uNûNUO  . N E T   ”^(uz^( NP–N  V i s u a l   S t u d i o ) 0  4 
  C o m p a n y N a m e     M i c r o s o f t   j !  F i l e D e s c r i p t i o n     M i c r o s o f t . V i s u a l S t u d i o . T h r e a d i n g     :   F i l e V e r s i o n     1 7 . 7 . 3 0 . 5 6 0 7     ~ /  I n t e r n a l N a m e   M i c r o s o f t . V i s u a l S t u d i o . T h r e a d i n g . r e s o u r c e s . d l l     d    L e g a l C o p y r i g h t   ©   M i c r o s o f t   C o r p o r a t i o n 0İOYu@b	gCg)R0  † /  O r i g i n a l F i l e n a m e   M i c r o s o f t . V i s u a l S t u d i o . T h r e a d i n g . r e s o u r c e s . d l l     b !  P r o d u c t N a m e     M i c r o s o f t . V i s u a l S t u d i o . T h r e a d i n g     P   P r o d u c t V e r s i o n   1 7 . 7 . 3 0 + 1 5 e 7 0 c 6 9 9 3 . R R                                                                                                                                                                                                                                                          À?                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      x%    0‚%f	*†H†÷ ‚%W0‚%S10	`†He 0\
+‚7 N0L0
+‚70	  ¢€ 010	`†He  sğFÓDÜ–ğõtä™ÉÅ8À@ğN²­íÇ÷¡\şiû ‚g0‚ï0‚× 3   'Ö2oCs{‡     0	*†H†÷ 0~10	UUS10U
Washington10URedmond10U
Microsoft Corporation1(0&UMicrosoft Code Signing PCA 20100230216201111Z240131201111Z0t10	UUS10U
Washington10URedmond10U
Microsoft Corporation10UMicrosoft Corporation0‚"0	*†H†÷ ‚ 0‚
‚ Å‘¹-Lğ	ÂèÖ©àôH½ª1x`çrd[rŞïoß>¢¿Ûê÷¬P.DˆĞø_^ppbÔ–Öp‹“ÉhÈ&>2Ûå0}mïÃ"ã É#xÃqîxXóš$!]~-áñÿ…»µôQ¯¶C'1lv`ƒÏÄ¦WhµŒüú×!=ÍÑ™ƒm –Çù™»üÉ4ÒíûIÎZ¾@]9²¸UÕ4¸{â*)ßŒÎ!M^<}®³.Úç÷É¢ğÊGo‡Câm‚®y"[c½_J”wz@|^DŒĞFJ€…ptÃ•Ö–sxáÖ¡96grS›êt.‘¡ £‚n0‚j0U%0
+‚7=+0U
Ùw0a5¡Ì¶«bÃ:*5)#0EU>0<¤:0810UMicrosoft Corporation10U230865+5002310U#0€æü_{»" XärNµô!t#2æï¬0VUO0M0K I G†Ehttp://crl.microsoft.com/pki/crl/products/MicCodSigPCA_2010-07-06.crl0Z+N0L0J+0†>http://www.microsoft.com/pki/certs/MicCodSigPCA_2010-07-06.crt0Uÿ0 0	*†H†÷ ‚ áÒCÔF-ÎSÄ¼Ê5qèá¥ùDú]gŠÛÊµ7R¾+ˆW-zŠuñ)ÒÆë	ÊUİzj/ƒ—™–sFíˆde÷9êàCK+}…×;`¡ºØÆÑÎ„á‰kÁx¹™:5¶¹¯&®Ùè_àö®`óô(:L6xn=Úòt¦€D„Yği€ ğÍ˜&º)î¨Ãr2Oé‚´8D³†R$OƒI169PùLı?3kÜ#`UÎ¦ÿ@	IS+GÍŒSCõÓËş
ö‹"ÔæãÈ³Xµãìr¾ U·)p ¿^ÜóäÜÒŸ/œy:5ñ‡E0‚p0‚X 
aRL     0	*†H†÷ 0ˆ10	UUS10U
Washington10URedmond10U
Microsoft Corporation1200U)Microsoft Root Certificate Authority 20100100706204017Z250706205017Z0~10	UUS10U
Washington10URedmond10U
Microsoft Corporation1(0&UMicrosoft Code Signing PCA 20100‚"0	*†H†÷ ‚ 0‚
‚ édPygµÄãı	 L”¬÷VhêDØÏÅXO©¥v|mEºÓ9’´¤ùùe‚äÒıDœèe“Î,U„¿}ã.+¨A+·¢KnILkŞÑÒÂ‰q”ÍµK´¯ØÌˆÖk”:“Î&?ìæş4˜WÕ]Iö²*.Õ…»Y?ø´+ƒtÊ+³;FãğFIÁfTÉ½ÄUbWrøg¹% 4Ş]¦¥•^«(€ÍÕ²åµcÓ²ÈÁÈŠ&
Yìÿí€5L¦¾R[õ¦Úà‹HwÖ…GÕ¹Æèªî‹j-\`Æ´*[œ#_Eão7Ë3€j‰M£jfcx“Õ0Ï• £‚ã0‚ß0	+‚7 0Uæü_{»" XärNµô!t#2æï¬0	+‚7
 S u b C A0U†0Uÿ0ÿ0U#0€ÕöVËè¢\bhÑ=”[×ÎšÄ0VUO0M0K I G†Ehttp://crl.microsoft.com/pki/crl/products/MicRooCerAut_2010-06-23.crl0Z+N0L0J+0†>http://www.microsoft.com/pki/certs/MicRooCerAut_2010-06-23.crt0U •0’0	+‚7.00=+1http://www.microsoft.com/PKI/docs/CPS/default.htm0@+042  L e g a l _ P o l i c y _ S t a t e m e n t . 0	*†H†÷ ‚ tïWO){Ä…x¸PÓ"ü	¬‚—ø4ÿ*,—•åä¿Ï¿“Èã4©Û¸Ü ¾Ò5o¯åy•wåÔñëØÍNa¢ÂZ#ğŒ¨bQEgã?<“ø0…È9@¦×³!)å¥¡iŒ"“Ìt˜ç¡GCòS¬À0işÒ% moaÓßÕÙr ,i†v=QÛ¦9HÉ7amİSË§ÖaÂ¿âƒ«àk›•Ö}(Q°‰JQ¤šlÈ·Ji©×ÜÁ~ÑIpª¶­»rGcú¦Ö¢¦†ì¨D›c¶²i‰ÇF†z?èÅ!Õ{ù#-ÅAË¿LÈï±œü"KIŠnã¦v[ÑSy‘…ÕÒÛ=s5ó<®T²RGjÀª•ÒÚ™g^2Œû7…ÑÜu…œ‡ÆZW…Â¿İŒ›-ë´îÏ'Óµ^iú¤§$gsÏMO¶ŞV—z÷éRMôwO…ÆØñíB	Ñvã#Vx"&6¾Ê±Œnªä…ÚG3b¤É‘3_q@¯˜eÉ"èB!%Š-`Ù7‰A‰*×a<”h`RïÖG™ €@îw>œàSP8•›f3'9x‡6ÎNÃ_²õ=GS¶àåÛa=*×’,Î7Z>@B1¤ÂVœ¿$]QjyÒÓÚÁ”{%qD«jæÔÆß#š–uÅ1‚r0‚n0•0~10	UUS10U
Washington10URedmond10U
Microsoft Corporation1(0&UMicrosoft Code Signing PCA 20103   'Ö2oCs{‡     0	`†He  ®0	*†H†÷	1
+‚70
+‚710
+‚70/	*†H†÷	1" nü€ÑïS´í’n©1©P<Ìq»T1øšú¤mh¿]Ş0B
+‚71402 € M i c r o s o f t¡€http://www.microsoft.com0	*†H†÷ ‚ ;~¯­UøùhóïŸÌÌ%ë¯º¢¤;z/%Zyt«)vˆï£Ò³ºükšß´!ì÷’=\'ZÎ°ë?¶¦Ê³™Q~­¬FneSÆĞµ´ˆt Ö‚a’¶ÜÏ;Ë QïA÷OÃ…L.£2¡OÏòĞB±Â¡VªÖÉï™İW›Bï„wÎ|ôÚµÕ1>!‘ŞQ!‚”ºÓ#£¹×UŠÅvCÍÑt{.µ®§è6-‹ –… ™à Ø„iÏcVØ¤ˆÂÉuL³ª÷Æ9Í‚Úªš3¼º<ê@SÔ!|ÁÏ‡ÏµJ…áhV1ãÚhm<#õC-âÚHI ¯†Ç÷,¶¡‚ü0‚ø
+‚71‚è0‚ä	*†H†÷ ‚Õ0‚Ñ10	`†He 0‚P*†H†÷	 ‚?‚;0‚7
+„Y
010	`†He  ×¦ê€j¹9@lÊ'3Ù–( "ò*U¸ßm ‘ğd‹â§20230620030606.93Z0€ô Ğ¤Í0Ê10	UUS10U
Washington10URedmond10U
Microsoft Corporation1%0#UMicrosoft America Operations1&0$UThales TSS ESN:7BF1-E3EA-B8081%0#UMicrosoft Time-Stamp Service ‚T0‚0‚ô 3  Èù°îgíêkF   È0	*†H†÷ 0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20100221104190137Z240202190137Z0Ê10	UUS10U
Washington10URedmond10U
Microsoft Corporation1%0#UMicrosoft America Operations1&0$UThales TSS ESN:7BF1-E3EA-B8081%0#UMicrosoft Time-Stamp Service0‚"0	*†H†÷ ‚ 0‚
‚ ¹Ë~(Oƒ$VÂyÈ¸ªstÂ´Y4\è¦Â£SÖÒéagƒi~²"wIg²–³¡0mWù™Î±úŒÇ–cmœtãÊc We)r(‡·Ò£@â}-@‰p†Ï.!‚(röÇ Á¤™Äf„kı;Ó¸²;š]•Íq<`6$D&ı¶KdÎù™òH€oRgKeÏ—9F0Ïÿ.—²ÎÓÍ‡'ğ)ô´˜Húvëkğf¢,ˆ«¿¬Jİ±^^ÿŠ9psÙh_—ÓÚ’ºÚÀó0!ÍQÓ>}ü@áäà[=Ñ¨ÁÎŞYyŒKLwùk t¦q9²¸e¡¨iå‘¢ç=måY¿ßÕêØkRÕ¨Y€`Ğ''´L¼Ùf)ôØckšSËZÙÃîÀå¥Y!aŠ¹5§»
«ÖSHO´Wgğ––‚P0‹–XÏ7¼³èÜ„ÿKşcOj9/g°˜0À,RÖ-ÂÚ¼üòm¾İUéZágvüÀ"~	:AÅìO¨W¿o>®*‰g™£,lüáÓö¯*Ò\XK=ÏO‘+*„´û2i`ĞÅ»öQx““u£şfw¶ésë>)yƒ--êşXóàozÆ`+g}ë˜ÊQÖÀŞ"ÿXúÈÇVÿ‹¦A®˜‰5éœÅ@¦*Yí'7æ»øºÙ	ãõ £‚60‚20Uµ\/IºÒÀ°Óˆ’Sâ"»5Käõ0U#0€Ÿ§] ^b]ƒôåÒe§S5ér0_UX0V0T R P†Nhttp://www.microsoft.com/pkiops/crl/Microsoft%20Time-Stamp%20PCA%202010(1).crl0l+`0^0\+0†Phttp://www.microsoft.com/pkiops/certs/Microsoft%20Time-Stamp%20PCA%202010(1).crt0Uÿ0 0U%0
+0	*†H†÷ ‚ ÃÖrRÈCÃ©('~Ab|†íx–û6FBmùÈ¢OWı}1¯Q¾B[¹7y*w»*¢oK[)5ôı‡ğÒX›)½aÔ5ÂøÚ‚ªÚî¹}‚µ¢ |<º¼±&yÅEñhÊ(.PåÜ}èµ}¤œ£cšøØÄeì	şã½•Ï.¦Ä)Ü5ş§é¤@}—xÆì‡‘
Dî`¦8'º-™<RÄ¸q®sØyÊáÔ'Ø¸¥Â–t*…Ô•7È }ĞWå1À å5¼¿İÃæ?v„ØˆÖá<3Ö~{ï£ñ˜zgç6³èX#ïl%˜aDI†œ®Róí¥?ç£¯„DEm¶à’µ~3ò~´'†6ıw¾XâR‹2Ó¾#Át«¿Sà|ªİ[¤Up´ÿ¡á'Î‹b –	‚Æ_lóÑPêfv5«&g
é ïîµû¦Èø'õ·Dui`¤rm¥‹²*7ûR‡Qk7¯9ş•ÓUä¬G*²;m
¿¬º}¥ˆŒŞu˜5¨üŞ_‘€Ñ¢"±Î\M¡—ı?ú Ïèöen›Î`½¸¼ÒyÚ?!Å6åÁìtj!–Š£4A£=:rşä((¡Sİ”ª§6@-ÓyzsûÂ†F2¨Åêì¼àùÇª0‚q0‚Y 3   Åçk›I™     0	*†H†÷ 0ˆ10	UUS10U
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
é4ø$5g+à¸æŒ™Öá"êğ'B=%”ætt[jÑ>í~ 13}¼Ëé{¿8pDÑñÈ«:Š:bÙpcSMî‚m¥Áqj´U3X³¡pfò¡‚Ë0‚40ø¡Ğ¤Í0Ê10	UUS10U
Washington10URedmond10U
Microsoft Corporation1%0#UMicrosoft America Operations1&0$UThales TSS ESN:7BF1-E3EA-B8081%0#UMicrosoft Time-Stamp Service¢#
0+ ßÎP»Æ54
R] b¹ÀîË² ƒ0€¤~0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20100	*†H†÷  è;‰Ö0"20230620101342Z20230621101342Z0t0:
+„Y
1,0*0
 è;‰Ö 0 &®0 =0
 è<ÛV 06
+„Y
1(0&0
+„Y
 
0 ¡ ¡
0 † 0	*†H†÷  5®ğõÙ¨h]0ËRE"c*™T´0¶| İİwÑG3i hİ!…ÀFÚVôsfj"¹	´RÂ³áM &T®í¸µå¡Jß±^I'E×;7Etwš:NãİßŒ1¸İm‡¤ô×`œî·@ï†¡÷$éÎ;9²ê[Á½ş¬¬¿d1‚0‚	0“0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20103  Èù°îgíêkF   È0	`†He  ‚J0	*†H†÷	1*†H†÷	0/	*†H†÷	1" ıöÕ÷·r^/%²ûxôM‡‰î İ¤¶›Fw0ú*†H†÷	/1ê0ç0ä0½ b ˜ÏÍ?m£/Ç£r¤¯/AVá7oOgÄwì>„f0˜0€¤~0|10	UUS10U
Washington10URedmond10U
Microsoft Corporation1&0$UMicrosoft Time-Stamp PCA 20103  Èù°îgíêkF   È0" ©Æ˜Àã‹Îz‡Y¼—7"˜‰dÀàVh!ÓdÌ=u€y0	*†H†÷ ‚ xd·)à@Ï–\¾RI	|M§:°æPì;zhøç)¯K±
İ(ÖØÉSÌ­•¹xTÏ ‡Äæ8‚¸ÂuÜâ$Xx³<È…Hö¾M¦¦gl¸ŞjTÃ#s]"=á[OTlaûkª[d£xéë+qb–iG>Æ°¦m$¦ÿ
qÇzÄ-£~ÿê]×ü; Q]œÈUs˜H)D5ğÏBÿCBdAê!¡ë˜À˜³YğY¶^f[lJÒ$“Û‡³?CøÉ2ù_xİk`Ã¢¢,	`;!/e¬jrh+…¼©q¸|AZƒÖôq®V0ğææ(ä”µlñÓK³ÃV^ûÓÂ’‘¶Xi¡cÒ®ëvğu…}4ú$M_ŸuŠ¯òìÄnÌ~#aBFÿıßñ×9ÿwrùÆŒ%8eÑğƒMåB˜Ü†‘[…eé-qLu	—Ö4V9r	>Ù/.è(‘$ÄYO?Ú ¾ Ç=€‹ei~CeàÜ†Œ‚½ì‘Â“•Ét‚UTeéãÒò’gr»½Q—½ß‹bß™GN¨!¬ê€û°&`GÁË´$œßô+vÅÍO¹åÏF/“bëD‹AßÈ´µ˜²üBöB.%xånHx¨æx Ö÷p¯>ìÂEÕ4×ñ£íÇ|^É†¤”                                                                                                                                              cceedsSlantEqual;',
        'â‹¢' => '&NotSquareSubsetEqual;',
        'â‹£' => '&NotSquareSupersetEqual;',
        'â‹¦' => '&lnsim;',
        'â‹§' => '&gnsim;',
        'â‹¨' => '&precnsim;',
        'â‹©' => '&scnsim;',
        'â‹ª' => '&nltri;',
        'â‹«' => '&ntriangleright;',
        'â‹¬' => '&nltrie;',
        'â‹­' => '&NotRightTriangleEqual;',
        'â‹®' => '&vellip;',
        'â‹¯' => '&ctdot;',
        'â‹°' => '&utdot;',
        'â‹±' => '&dtdot;',
        'â‹²' => '&disin;',
        'â‹³' => '&isinsv;',
        'â‹´' => '&isins;',
        'â‹µ' => '&isindot;',
        'â‹µÌ¸' => '&notindot',
        'â‹¶' => '&notinvc;',
        'â‹·' => '&notinvb;',
        'â‹¹' => '&isinE;',
        'â‹¹Ì¸' => '&notinE',
        'â‹º' => '&nisd;',
        'â‹»' => '&xnis;',
        'â‹¼' => '&nis;',
        'â‹½' => '&notnivc;',
        'â‹¾' => '&notnivb;',
        'âŒ…' => '&barwed;',
        'âŒ†' => '&doublebarwedge;',
        'âŒˆ' => '&lceil;',
        'âŒ‰' => '&RightCeiling;',
        'âŒŠ' => '&LeftFloor;',
        'âŒ‹' => '&RightFloor;',
        'âŒŒ' => '&drcrop;',
        'âŒ' => '&dlcrop;',
        'âŒ' => '&urcrop;',
        'âŒ' => '&ulcrop;',
        'âŒ' => '&bnot;',
        'âŒ’' => '&profline;',
        'âŒ“' => '&profsurf;',
        'âŒ•' => '&telrec;',
        'âŒ–' => '&target;',
        'âŒœ' => '&ulcorner;',
        'âŒ' => '&urcorner;',
        'âŒ' => '&llcorner;',
        'âŒŸ' => '&drcorn;',
        'âŒ¢' => '&frown;',
        'âŒ£' => '&smile;',
        'âŒ­' => '&cylcty;',
        'âŒ®' => '&profalar;',
        'âŒ¶' => '&topbot;',
        'âŒ½' => '&ovbar;',
        'âŒ¿' => '&solbar;',
        'â¼' => '&angzarr;',
        'â°' => '&lmoust;',
        'â±' => '&rmoust;',
        'â´' => '&OverBracket;',
        'âµ' => '&bbrk;',
        'â¶' => '&bbrktbrk;',
        'âœ' => '&OverParenthesis;',
        'â' => '&UnderParenthesis;',
        'â' => '&OverBrace;',
        'âŸ' => '&UnderBrace;',
        'â¢' => '&trpezium;',
        'â§' => '&elinters;',
        'â£' => '&blank;',
        'â“ˆ' => '&oS;',
        'â”€' => '&HorizontalLine;',
        'â”‚' => '&boxv;',
        'â”Œ' => '&boxdr;',
        'â”' => '&boxdl;',
        'â””' => '&boxur;',
        'â”˜' => '&boxul;',
        'â”œ' => '&boxvr;',
        'â”¤' => '&boxvl;',
        'â”¬' => '&boxhd;',
        'â”´' => '&boxhu;',
        'â”¼' => '&boxvh;',
        'â•' => '&boxH;',
        'â•‘' => '&boxV;',
        'â•’' => '&boxdR;',
        'â•“' => '&boxDr;',
        'â•”' => '&boxDR;',
        'â••' => '&boxdL;',
        'â•–' => '&boxDl;',
        'â•—' => '&boxDL;',
        'â•˜' => '&boxuR;',
        'â•™' => '&boxUr;',
        'â•š' => '&boxUR;',
        'â•›' => '&boxuL;',
        'â•œ' => '&boxUl;',
        'â•' => '&boxUL;',
        'â•' => '&boxvR;',
        'â•Ÿ' => '&boxVr;',
        'â• ' => '&boxVR;',
        'â•¡' => '&boxvL;',
        'â•¢' => '&boxVl;',
        'â•£' => '&boxVL;',
        'â•¤' => '&boxHd;',
        'â•¥' => '&boxhD;',
        'â•¦' => '&boxHD;',
        'â•§' => '&boxHu;',
        'â•¨' => '&boxhU;',
        'â•©' => '&boxHU;',
        'â•ª' => '&boxvH;',
        'â•«' => '&boxVh;',
        'â•¬' => '&boxVH;',
        'â–€' => '&uhblk;',
        'â–„' => '&lhblk;',
        'â–ˆ' => '&block;',
        'â–‘' => '&blk14;',
        'â–’' => '&blk12;',
        'â–“' => '&blk34;',
        'â–¡' => '&Square;',
        'â–ª' => '&squarf;',
        'â–«' => '&EmptyVerySmallSquare;',
        'â–­' => '&rect;',
        'â–®' => '&marker;',
        'â–±' => '&fltns;',
        'â–³' => '&bigtriangleup;',
        'â–´' => '&blacktriangle;',
        'â–µ' => '&triangle;',
        'â–¸' => '&blacktriangleright;',
        'â–¹' => '&rtri;',
        'â–½' => '&bigtriangledown;',
        'â–¾' => '&blacktriangledown;',
        'â–¿' => '&triangledown;',
        'â—‚' => '&blacktriangleleft;',
        'â—ƒ' => '&ltri;',
        'â—Š' => '&lozenge;',
        'â—‹' => '&cir;',
        'â—¬' => '&tridot;',
        'â—¯' => '&bigcirc;',
        'â—¸' => '&ultri;',
        'â—¹' => '&urtri;',
        'â—º' => '&lltri;',
        'â—»' => '&EmptySmallSquare;',
        'â—¼' => '&FilledSmallSquare;',
        'â˜…' => '&starf;',
        'â˜†' => '&star;',
        'â˜' => '&phone;',
        'â™€' => '&female;',
        'â™‚' => '&male;',
        'â™ ' => '&spadesuit;',
        'â™£' => '&clubs;',
        'â™¥' => '&hearts;',
        'â™¦' => '&diamondsuit;',
        'â™ª' => '&sung;',
        'â™­' => '&flat;',
        'â™®' => '&natur;',
        'â™¯' => '&sharp;',
        'âœ“' => '&check;',
        'âœ—' => '&cross;',
        'âœ ' => '&maltese;',
        'âœ¶' => '&sext;',
        'â˜' => '&VerticalSeparator;',
        'â²' => '&lbbrk;',
        'â³' => '&rbbrk;',
        'âŸˆ' => '&bsolhsub;',
        'âŸ‰' => '&suphsol;',
        'âŸ¦' => '&LeftDoubleBracket;',
        'âŸ§' => '&RightDoubleBracket;',
        'âŸ¨' => '&langle;',
        'âŸ©' => '&RightAngleBracket;',
        'âŸª' => '&Lang;',
        'âŸ«' => '&Rang;',
        'âŸ¬' => '&loang;',
        'âŸ­' => '&roang;',
        'âŸµ' => '&longleftarrow;',
        'âŸ¶' => '&LongRightArrow;',
        'âŸ·' => '&LongLeftRightArrow;',
        'âŸ¸' => '&xlArr;',
        'âŸ¹' => '&DoubleLongRightArrow;',
        'âŸº' => '&xhArr;',
        'âŸ¼' => '&xmap;',
        'âŸ¿' => '&dzigrarr;',
        'â¤‚' => '&nvlArr;',
        'â¤ƒ' => '&nvrArr;',
        'â¤„' => '&nvHarr;',
        'â¤…' => '&Map;',
        'â¤Œ' => '&lbarr;',
        'â¤' => '&bkarow;',
        'â¤' => '&lBarr;',
        'â¤' => '&dbkarow;',
        'â¤' => '&drbkarow;',
        'â¤‘' => '&DDotrahd;',
        'â¤’' => '&UpArrowBar;',
        'â¤“' => '&DownArrowBar;',
        'â¤–' => '&Rarrtl;',
        'â¤™' => '&latail;',
        'â¤š' => '&ratail;',
        'â¤›' => '&lAtail;',
        'â¤œ' => '&rAtail;',
        'â¤' => '&larrfs;',
        'â¤' => '&rarrfs;',
        'â¤Ÿ' => '&larrbfs;',
        'â¤ ' => '&rarrbfs;',
        'â¤£' => '&nwarhk;',
        'â¤¤' => '&nearhk;',
        'â¤¥' => '&searhk;',
        'â¤¦' => '&swarhk;',
        'â¤§' => '&nwnear;',
        'â¤¨' => '&toea;',
        'â¤©' => '&seswar;',
        'â¤ª' => '&swnwar;',
        'â¤³' => '&rarrc;',
        'â¤³Ì¸' => '&nrarrc',
        'â¤µ' => '&cudarrr;',
        'â¤¶' => '&ldca;',
        'â¤·' => '&rdca;',
        'â¤¸' => '&cudarrl;',
        'â¤¹' => '&larrpl;',
        'â¤¼' => '&curarrm;',
        'â¤½' => '&cularrp;',
        'â¥…' => '&rarrpl;',
        'â¥ˆ' => '&harrcir;',
        'â¥‰' => '&Uarrocir;',
        'â¥Š' => '&lurdshar;',
        'â¥‹' => '&ldrushar;',
        'â¥' => '&LeftRightVector;',
        'â¥' => '&RightUpDownVector;',
        'â¥' => '&DownLeftRightVector;',
        'â¥‘' => '&LeftUpDownVector;',
        'â¥’' => '&LeftVectorBar;',
        'â¥“' => '&RightVectorBar;',
        'â¥”' => '&RightUpVectorBar;',
        'â¥•' => '&RightDownVectorBar;',
        'â¥–' => '&DownLeftVectorBar;',
        'â¥—' => '&DownRightVectorBar;',
        'â¥˜' => '&LeftUpVectorBar;',
        'â¥™' => '&LeftDownVectorBar;',
        'â¥š' => '&LeftTeeVector;',
        'â¥›' => '&RightTeeVector;',
        'â¥œ' => '&RightUpTeeVector;',
        'â¥' => '&RightDownTeeVector;',
        'â¥' => '&DownLeftTeeVector;',
        'â¥Ÿ' => '&DownRightTeeVector;',
        'â¥ ' => '&LeftUpTeeVector;',
        'â¥¡' => '&LeftDownTeeVector;',
        'â¥¢' => '&lHar;',
        'â¥£' => '&uHar;',
        'â¥¤' => '&rHar;',
        'â¥¥' => '&dHar;',
        'â¥¦' => '&luruhar;',
        'â¥§' => '&ldrdhar;',
        'â¥¨' => '&ruluhar;',
        'â¥©' => '&rdldhar;',
        'â¥ª' => '&lharul;',
        'â¥«' => '&llhard;',
        'â¥¬' => '&rharul;',
        'â¥­' => '&lrhard;',
        'â¥®' => '&udhar;',
        'â¥¯' => '&ReverseUpEquilibrium;',
        'â¥°' => '&RoundImplies;',
        'â¥±' => '&erarr;',
        'â¥²' => '&simrarr;',
        'â¥³' => '&larrsim;',
        'â¥´' => '&rarrsim;',
        'â¥µ' => '&rarrap;',
        'â¥¶' => '&ltlarr;',
        'â¥¸' => '&gtrarr;',
        'â¥¹' => '&subrarr;',
        'â¥»' => '&suplarr;',
        'â¥¼' => '&lfisht;',
        'â¥½' => '&rfisht;',
        'â¥¾' => '&ufisht;',
        'â¥¿' => '&dfisht;',
        'â¦…' => '&lopar;',
        'â¦†' => '&ropar;',
        'â¦‹' => '&lbrke;',
        'â¦Œ' => '&rbrke;',
        'â¦' => '&lbrkslu;',
        'â¦' => '&rbrksld;',
        'â¦' => '&lbrksld;',
        'â¦' => '&rbrkslu;',
        'â¦‘' => '&langd;',
        'â¦’' => '&rangd;',
        'â¦“' => '&lparlt;',
        'â¦”' => '&rpargt;',
        'â¦•' => '&gtlPar;',
        'â¦–' => '&ltrPar;',
        'â¦š' => '&vzigzag;',
        'â¦œ' => '&vangrt;',
        'â¦' => '&angrtvbd;',
        'â¦¤' => '&ange;',
        'â¦¥' => '&range;',
        'â¦¦' => '&dwangle;',
        'â¦§' => '&uwangle;',
        'â¦¨' => '&angmsdaa;',
        'â¦©' => '&angmsdab;',
        'â¦ª' => '&angmsdac;',
        'â¦«' => '&angmsdad;',
        'â¦¬' => '&angmsdae;',
        'â¦­' => '&angmsdaf;',
        'â¦®' => '&angmsdag;',
        'â¦¯' => '&angmsdah;',
        'â¦°' => '&bemptyv;',
        'â¦±' => '&demptyv;',
        'â¦²' => '&cemptyv;',
        'â¦³' => '&raemptyv;',
        'â¦´' => '&laemptyv;',
        'â¦µ' => '&ohbar;',
        'â¦¶' => '&omid;',
        'â¦·' => '&opar;',
        'â¦¹' => '&operp;',
        'â¦»' => '&olcross;',
        'â¦¼' => '&odsold;',
        'â¦¾' => '&olcir;',
        'â¦¿' => '&ofcir;',
        'â§€' => '&olt;',
        'â§' => '&ogt;',
        'â§‚' => '&cirscir;',
        'â§ƒ' => '&cirE;',
        'â§„' => '&solb;',
        'â§…' => '&bsolb;',
        'â§‰' => '&boxbox;',
        'â§' => '&trisb;',
        'â§' => '&rtriltri;',
        'â§' => '&LeftTriangleBar;',
        'â§Ì¸' => '&NotLeftTriangleBar',
        'â§' => '&RightTriangleBar;',
        'â§Ì¸' => '&NotRightTriangleBar',
        'â§œ' => '&iinfin;',
        'â§' => '&infintie;',
        'â§' => '&nvinfin;',
        'â§£' => '&eparsl;',
        'â§¤' => '&smeparsl;',
        'â§¥' => '&eqvparsl;',
        'â§«' => '&lozf;',
        'â§´' => '&RuleDelayed;',
        'â§¶' => '&dsol;',
        'â¨€' => '&xodot;',
        'â¨' => '&bigoplus;',
        'â¨‚' => '&bigotimes;',
        'â¨„' => '&biguplus;',
        'â¨†' => '&bigsqcup;',
        'â¨Œ' => '&iiiint;',
        'â¨' => '&fpartint;',
        'â¨' => '&cirfnint;',
        'â¨‘' => '&awint;',
        'â¨’' => '&rppolint;',
        'â¨“' => '&scpolint;',
        'â¨”' => '&npolint;',
        'â¨•' => '&pointint;',
        'â¨–' => '&quatint;',
        'â¨—' => '&intlarhk;',
        'â¨¢' => '&pluscir;',
        'â¨£' => '&plusacir;',
        'â¨¤' => '&simplus;',
        'â¨¥' => '&plusdu;',
        'â¨¦' => '&plussim;',
        'â¨§' => '&plustwo;',
        'â¨©' => '&mcomma;',
        'â¨ª' => '&minusdu;',
        'â¨­' => '&loplus;',
        'â¨®' => '&roplus;',
        'â¨¯' => '&Cross;',
        'â¨°' => '&timesd;',
        'â¨±' => '&timesbar;',
        'â¨³' => '&smashp;',
        'â¨´' => '&lotimes;',
        'â¨µ' => '&rotimes;',
        'â¨¶' => '&otimesas;',
        'â¨·' => '&Otimes;',
        'â¨¸' => '&odiv;',
        'â¨¹' => '&triplus;',
        'â¨º' => '&triminus;',
        'â¨»' => '&tritime;',
        'â¨¼' => '&iprod;',
        'â¨¿' => '&amalg;',
        'â©€' => '&capdot;',
        'â©‚' => '&ncup;',
        'â©ƒ' => '&ncap;',
        'â©„' => '&capand;',
        'â©…' => '&cupor;',
        'â©†' => '&cupcap;',
        'â©‡' => '&capcup;',
        'â©ˆ' => '&cupbrcap;',
        'â©‰' => '&capbrcup;',
        'â©Š' => '&cupcup;',
        'â©‹' => '&capcap;',
        'â©Œ' => '&ccups;',
        'â©' => '&ccaps;',
        'â©' => '&ccupssm;',
        'â©“' => '&And;',
        'â©”' => '&Or;',
        'â©•' => '&andand;',
        'â©–' => '&oror;',
        'â©—' => '&orslope;',
        'â©˜' => '&andslope;',
        'â©š' => '&andv;',
        'â©›' => '&orv;',
        'â©œ' => '&andd;',
        'â©' => '&ord;',
        'â©Ÿ' => '&wedbar;',
        'â©¦' => '&sdote;',
        'â©ª' => '&simdot;',
        'â©­' => '&congdot;',
        'â©­Ì¸' => '&ncongdot',
        'â©®' => '&easter;',
        'â©¯' => '&apacir;',
        'â©°' => '&apE;',
        'â©°Ì¸' => '&napE',
        'â©±' => '&eplus;',
        'â©²' => '&pluse;',
        'â©³' => '&Esim;',
        'â©´' => '&Colone;',
        'â©µ' => '&Equal;',
        'â©·' => '&ddotseq;',
        'â©¸' => '&equivDD;',
        'â©¹' => '&ltcir;',
        'â©º' => '&gtcir;',
        'â©»' => '&ltquest;',
        'â©¼' => '&gtquest;',
        'â©½' => '&les;',
        'â©½Ì¸' => '&nles',
        'â©¾' => '&ges;',
        'â©¾Ì¸' => '&nges',
        'â©¿' => '&lesdot;',
        'âª€' => '&gesdot;',
        'âª' => '&lesdoto;',
        'âª‚' => '&gesdoto;',
        'âªƒ' => '&lesdotor;',
        'âª„' => '&gesdotol;',
        'âª…' => '&lap;',
        'âª†' => '&gap;',
        'âª‡' => '&lne;',
        'âªˆ' => '&gne;',
        'âª‰' => '&lnap;',
        'âªŠ' => '&gnap;',
        'âª‹' => '&lesseqqgtr;',
        'âªŒ' => '&gEl;',
        'âª' => '&lsime;',
        'âª' => '&gsime;',
        'âª' => '&lsimg;',
        'âª' => '&gsiml;',
        'âª‘' => '&lgE;',
        'âª’' => '&glE;',
        'âª“' => '&lesges;',
        'âª”' => '&gesles;',
        'âª•' => '&els;',
        'âª–' => '&egs;',
        'âª—' => '&elsdot;',
        'âª˜' => '&egsdot;',
        'âª™' => '&el;',
        'âªš' => '&eg;',
        'âª' => '&siml;',
        'âª' => '&simg;',
        'âªŸ' => '&simlE;',
        'âª ' => '&simgE;',
        'âª¡' => '&LessLess;',
        'âª¡Ì¸' => '&NotNestedLessLess',
        'âª¢' => '&GreaterGreater;',
        'âª¢Ì¸' => '&NotNestedGreaterGreater',
        'âª¤' => '&glj;',
        'âª¥' => '&gla;',
        'âª¦' => '&ltcc;',
        'âª§' => '&gtcc;',
        'âª¨' => '&lescc;',
        'âª©' => '&gescc;',
        'âªª' => '&smt;',
        'âª«' => '&lat;',
        'âª¬' => '&smte;',
        'âª¬ï¸€' => '&smtes',
        'âª­' => '&late;',
        'âª­ï¸€' => '&lates',
        'âª®' => '&bumpE;',
        'âª¯' => '&preceq;',
        'âª¯Ì¸' => '&NotPrecedesEqual',
        'âª°' => '&SucceedsEqual;',
        'âª°Ì¸' => '&NotSucceedsEqual',
        'âª³' => '&prE;',
        'âª´' => '&scE;',
        'âªµ' => '&precneqq;',
        'âª¶' => '&scnE;',
        'âª·' => '&precapprox;',
        'âª¸' => '&succapprox;',
        'âª¹' => '&precnapprox;',
        'âªº' => '&succnapprox;',
        'âª»' => '&Pr;',
        'âª¼' => '&Sc;',
        'âª½' => '&subdot;',
        'âª¾' => '&supdot;',
        'âª¿' => '&subplus;',
        'â«€' => '&supplus;',
        'â«' => '&submult;',
        'â«‚' => '&supmult;',
        'â«ƒ' => '&subedot;',
        'â«„' => '&supedot;',
        'â«…' => '&subE;',
        'â«…Ì¸' => '&nsubE',
        'â«†' => '&supseteqq;',
        'â«†Ì¸' => '&nsupseteqq',
        'â«‡' => '&subsim;',
        'â«ˆ' => '&supsim;',
        'â«‹' => '&subsetneqq;',
        'â«‹ï¸€' => '&vsubnE',
        'â«Œ' => '&supnE;',
        'â«Œï¸€' => '&varsupsetneqq',
        'â«' => '&csub;',
        'â«' => '&csup;',
        'â«‘' => '&csube;',
        'â«’' => '&csupe;',
        'â«“' => '&subsup;',
        'â«”' => '&supsub;',
        'â«•' => '&subsub;',
        'â«–' => '&supsup;',
        'â«—' => '&suphsub;',
        'â«˜' => '&supdsub;',
        'â«™' => '&forkv;',
        'â«š' => '&topfork;',
        'â«›' => '&mlcp;',
        'â«¤' => '&Dashv;',
        'â«¦' => '&Vdashl;',
        'â«§' => '&Barv;',
        'â«¨' => '&vBar;',
        'â«©' => '&vBarv;',
        'â««' => '&Vbar;',
        'â«¬' => '&Not;',
        'â«­' => '&bNot;',
        'â«®' => '&rnmid;',
        'â«¯' => '&cirmid;',
        'â«°' => '&midcir;',
        'â«±' => '&topcir;',
        'â«²' => '&nhpar;',
        'â«³' => '&parsim;',
        'â«½ï¸€' => '&varsupsetneqq',
        'ï¬€' => '&fflig;',
        'ï¬' => '&filig;',
        'ï¬‚' => '&fllig;',
        'ï¬ƒ' => '&ffilig;',
        'ï¬„' => '&ffllig;',
        'ğ’œ' => '&Ascr;',
        'ğ’' => '&Cscr;',
        'ğ’Ÿ' => '&Dscr;',
        'ğ’¢' => '&Gscr;',
        'ğ’¥' => '&Jscr;',
        'ğ’¦' => '&Kscr;',
        'ğ’©' => '&Nscr;',
        'ğ’ª' => '&Oscr;',
        'ğ’«' => '&Pscr;',
        'ğ’¬' => '&Qscr;',
        'ğ’®' => '&Sscr;',
        'ğ’¯' => '&Tscr;',
        'ğ’°' => ÙÕù ¡c×   E@g<  (                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             
İ *x îÇ%òqGøª}Q¸a4Ş«xÔµ–Ã¢ÿÔ¥	F	—	v×	¾ß«7 
C
	àÂWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWWW                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            2	+	5mail.google.com__Host-GMAIL_SCH_GMS/» “2	+	5mail.google.com__Host-GMAIL_SCH_GMN/» ’2	+	5mail.google.com__Host-GMAIL_SCH_GML/» ‘"	+	mail.google.comOSID/» ‚,	+	mail.google.comCOMPASS/sync/u/0»{,	+	mail.google.comCOMPASS/mail/u/0»z)	+	mail.google.comCOMPASS/mail» ¡$	+	mail.google.comCOMPASS/»xĞ                  2	7	)edgeservices.bing.comEDGSRVCPERSIST/»Ì+	7	edgeservices.bing.comEDGSRVC/»Ë+	?	login.microsoftonline.comfpc/»Ä,	?	login.microsoftonline.combuid/»Ÿ%	3	login.microsoft.comfpc/»Ã<     	%	www.bing.comMUIDB/»ØC	)	Ylogin.live.comMicrosoftApplicationsTelemetryDeviceId/»µ¡        )	7	edgeservices.bing.comMUIDB/»Î/	7	#edgeservices.bing.comEDGSRVCSCEN/»Í	 &                                  &	7	copilot.microsoft.com_U/»à'	7	copilot.microsoft.com_SS/»Ş@	#	Yntp.msn.comMicrosoftApplicationsTelemetryDeviceId/»Ï5	/	7www.jetbrains.comstg_returning_visitor/»ì4	/	5www.jetbrains.comstg_last_interaction/»ëP	/	mwww.jetbrains.com_pk_id.f93eea86-00df-4291-b4d9-fdb2ccdcf222.9139/»â
n 3                                               /	5	%stories.flipkart.compll_language/»  	!	unstop.comg_state/»¯&	!	'unstop.comallowedCookie/»S!	)	www.clarity.msCLID/»G6	1	7www.hackerrank.comremember_hacker_token/»Â+	1	!www.hackerrank.comreact_var2/»Á*	1	www.hackerrank.comreact_var/»Àu ’                                                                                                                                              