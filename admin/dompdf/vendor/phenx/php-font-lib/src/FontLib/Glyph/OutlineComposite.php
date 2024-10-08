<?php
/**
 * @package php-font-lib
 * @link    https://github.com/PhenX/php-font-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @version $Id: Font_Table_glyf.php 46 2012-04-02 20:22:38Z fabien.menager $
 */

namespace FontLib\Glyph;

/**
 * Composite glyph outline
 *
 * @package php-font-lib
 */
class OutlineComposite extends Outline {
  const ARG_1_AND_2_ARE_WORDS    = 0x0001;
  const ARGS_ARE_XY_VALUES       = 0x0002;
  const ROUND_XY_TO_GRID         = 0x0004;
  const WE_HAVE_A_SCALE          = 0x0008;
  const MORE_COMPONENTS          = 0x0020;
  const WE_HAVE_AN_X_AND_Y_SCALE = 0x0040;
  const WE_HAVE_A_TWO_BY_TWO     = 0x0080;
  const WE_HAVE_INSTRUCTIONS     = 0x0100;
  const USE_MY_METRICS           = 0x0200;
  const OVERLAP_COMPOUND         = 0x0400;

  /**
   * @var OutlineComponent[]
   */
  public $components = array();

  function getGlyphIDs() {
    if (empty($this->components)) {
      $this->parseData();
    }

    $glyphIDs = array();
    foreach ($this->components as $_component) {
      $glyphIDs[] = $_component->glyphIndex;

      $_glyph   = $this->table->data[$_component->glyphIndex];

      if ($_glyph !== $this) {
        $glyphIDs = array_merge($glyphIDs, $_glyph->getGlyphIDs());
      }
    }

    return $glyphIDs;
  }

  /*function parse() {
    //$this->parseData();
  }*/

  function parseData() {
    parent::parseData();

    $font = $this->getFont();

    do {
      $flags      = $font->readUInt16();
      $glyphIndex = $font->readUInt16();

      $a = 1.0;
      $b = 0.0;
      $c = 0.0;
      $d = 1.0;
      $e = 0.0;
      $f = 0.0;

      $point_compound  = null;
      $point_component = null;

      $instructions = null;

      if ($flags & self::ARG_1_AND_2_ARE_WORDS) {
        if ($flags & self::ARGS_ARE_XY_VALUES) {
          $e = $font->readInt16();
          $f = $font->readInt16();
        }
        else {
          $point_compound  = $font->readUInt16();
          $point_component = $font->readUInt16();
        }
      }
      else {
        if ($flags & self::ARGS_ARE_XY_VALUES) {
          $e = $font->readInt8();
          $f = $font->readInt8();
        }
        else {
          $point_compound  = $font->readUInt8();
          $point_component = $font->readUInt8();
        }
      }

      if ($flags & self::WE_HAVE_A_SCALE) {
        $a = $d = $font->readInt16();
      }
      elseif ($flags & self::WE_HAVE_AN_X_AND_Y_SCALE) {
        $a = $font->readInt16();
        $d = $font->readInt16();
      }
      elseif ($flags & self::WE_HAVE_A_TWO_BY_TWO) {
        $a = $font->readInt16();
        $b = $font->readInt16();
        $c = $font->readInt16();
        $d = $font->readInt16();
      }

      //if ($flags & self::WE_HAVE_INSTRUCTIONS) {
      //
      //}

      $component                  = new OutlineComponent();
      $component->flags           = $flags;
      $component->glyphIndex      = $glyphIndex;
      $component->a               = $a;
      $component->b               = $b;
      $component->c               = $c;
      $component->d               = $d;
      $component->e               = $e;
      $component->f               = $f;
      $component->point_compound  = $point_compound;
      $component->point_component = $point_component;
      $component->instructions    = $instructions;

      $this->components[] = $component;
    } while ($flags & self::MORE_COMPONENTS);
  }

  function encode() {
    $font = $this->getFont();

    $gids = $font->getSubset();

    $size = $font->writeInt16(-1);
    $size += $font->writeFWord($this->xMin);
    $size += $font->writeFWord($this->yMin);
    $size += $font->writeFWord($this->xMax);
    $size += $font->writeFWord($this->yMax);

    foreach ($this->components as $_i => $_component) {
      $flags = 0;
      if ($_component->point_component === null && $_component->point_compound === null) {
        $flags |= self::ARGS_ARE_XY_VALUES;

        if (abs($_component->e) > 0x7F || abs($_component->f) > 0x7F) {
          $flagp a r t n e r S c a n T i m e   =   1 7 2 0 4 6 6 1 6 2  
 c l o u d F i l e s A c t i v e L o g   =   C : \ W i n d o w s \ s y s t e m 3 2 \ L o g F i l e s \ C l o u d F i l e s \ C l d F l t 0 . e t l | 1 7 2 0 4 6 2 5 3 5 | 4 0 9 6 | v 7 G 0 t c J N z f 7 p O D / L i s t / b j / 8 O / g =  
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          