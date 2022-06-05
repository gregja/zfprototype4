<?php

/*
 * Zend_Pdf Extension: Gorilla_Pdf_Page & Gorilla_Pdf_Table
 * By Joseph Montanez
 *
 * At work there is a growing need to move into pdf creation,
 * since we generate all of our invoices via HTML. Some clients
 * need precision on form creation. PDF offers that but
 * Zend_Pdf lib is very limited. So I implemented text-wrapper
 * and table class/functions to help guide me.
 * Its far from done, but a good start to blog about it.
 *
 * http://www.gorilla3d.com/v8/zend-pdf-text-wrapping-and-tables
 * http://labs.gorilla3d.com/Gorilla_Pdf.txt
 *
 * Petite modification réalisée par G. Jarrige (GJ) le 28/07/2010 :
 * - ajout de la propriété publique $headborder pour pouvoir paramétrer
 *   l'épaisseur du cadre de la première ligne d'un tableau, indépendamment
 *   des autres lignes
 */

class Gorilla_Pdf_Table {

    public $x;
    public $y;
    public $width;
    public $border = 0.5;
    public $headborder = 0.75;
    public $page;
    protected $_pages = array();
    protected $_rows = array();

    function __construct($page, $x, $y) {
        $this->page = $page;
        $this->x = $x;
        $this->y = $y;
    }

    public function addRow(Gorilla_Pdf_Table_Row $row) {
        $this->_rows[] = $row;
    }

    public function render() {

        $i = 1;
        $y = $this->page->getHeight() - $this->y;
        foreach ($this->_rows as $row) {
            if ($i == 1) {
                $this->page->setLineWidth($this->headborder);
            } else {
                $this->page->setLineWidth($this->border);
            }
            if ($y - $row->testRender($this->page, $this->x, $y) < 0) {
                $font = $this->page->getFont();
                $font_size = $this->page->getFontSize();
                //$linewidth = $this->page->getLineWidth();
                $this->page = new Gorilla_Pdf_Page($this->page);
                $this->page->setFont($font, $font_size);
                $this->page->setLineWidth($this->border);
                $this->_pages[] = $this->page;
                $y = $this->page->getHeight();
            }
            $row->render($this->page, $this->x, $y);
            $y -= $row->getHeight();
            $i++;
        }
        return $this->_pages;
    }

}

class Gorilla_Pdf_Table_Row {

    protected $_width;
    protected $_height;
    protected $_cols = array();

    public function addColumn(Gorilla_Pdf_Table_Column $col) {
        $this->_cols[] = $col;
    }

    public function render($page, $x, $y) {
        $tmp_x = $x;
        $max_height = 0;
        foreach ($this->_cols as $col) {
            $col->render($page, $x, $y);
            $height = $col->getHeight();
            if ($height > $max_height) {
                $max_height = $height;
            }
            $x += $col->getWidth();
        }
        $this->_height = $max_height;
        $this->renderBorder($page, $tmp_x, $y);
    }

    public function testRender($page, $x, $y) {
        $tmp_x = $x;
        $max_height = 0;
        foreach ($this->_cols as $col) {
            $col->testRender($page, $x, $y);
            $height = $col->getHeight();
            if ($height > $max_height) {
                $max_height = $height;
            }
            $x += $col->getWidth();
        }
        $this->_height = $max_height;
        return $this->_height;
    }

    public function renderBorder($page, $x, $y) {
        foreach ($this->_cols as $col) {
            $col->renderBorder($page, $x, $y, $this->_height);
            $x += $col->getWidth();
        }
    }

    public function getHeight() {
        return $this->_height;
    }

}

class Gorilla_Pdf_Table_Column {

    protected $_height;
    protected $_width;
    protected $_text;
    protected $_padding = 3;
    protected $_align = 'left';

    public function setText($text) {
        $this->_text = $text;
        return $this;
    }

    public function getWidth() {
        return $this->_width;
    }

    public function setWidth($width) {
        $this->_width = $width;
        return $this;
    }

    public function getHeight() {
        return $this->_height;
    }

    public function setAlignment($align) {
        $this->_align = $align;
    }

    public function render($page, $x, $y) {
        $font_size = $page->getFontSize();
        $size = $page->drawVariableText($this->_text, $x + $this->_padding, $page->getHeight() - $y + $font_size, $this->_width - $this->_padding, $this->_align);
        $this->_height = $size['height'] + $this->_padding;
        $this->_width = $this->_width + $this->_padding;
    }

    public function testRender($page, $x, $y) {
        $font_size = $page->getFontSize();
        $size = $page->getVariableText($this->_text, $x + $this->_padding, $page->getHeight() - $y + $font_size, $this->_width - $this->_padding);
        $this->_height = $size['height'] + $this->_padding;
        $this->_width = $this->_width + $this->_padding;
    }

    public function renderBorder($page, $x, $y, $height) {
        $font_size = $page->getFontSize();
        $page->drawRectangle($x, $y, $x + $this->_width, $y - $height, $fillType = Zend_Pdf_Page::SHAPE_DRAW_STROKE);
    }

}

class Gorilla_Pdf_Page extends Zend_Pdf_Page {

    public function drawInfoBox($header, $lines, $x, $y, $width, $height) {
        $font_size = $this->getFontSize();
        //-- Draw the box
        $this->drawRectangle($x, $this->getHeight() - $y, $x + $width, $this->getHeight() - $y - $height, $fillType = Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        //-- Draw the header bottom
        $this->drawLine($x, $this->getHeight() - $y - ($font_size * 2), $x + $width, $this->getHeight() - $y - ($font_size * 2));
        //-- Draw the header text
        $this->drawText($header, $x + ($font_size / 2), $this->getHeight() - $y - $font_size - ($font_size / 4));
        $this->drawMultilineText($lines, $x + 3, $y + ($font_size * 3));
    }

    public function drawMultilineText($lines, $x, $y) {
        $y = $this->getHeight() - $y;
        $font_size = $this->getFontSize();
        foreach ($lines as $i => $line) {
            $this->drawText($line, $x + 2, $y - ($font_size * 1.2 * $i));
        }
    }

    public function getVariableText($str, $x, $y, $max_width) {
        $y = $this->getHeight() - $y;
        $font = $this->getFont();
        $font_size = $this->getFontSize();
        //-- Find out each word's width
        $words = explode(' ', $str);
        $words_lens = array();
        $em = $font->getUnitsPerEm();
        $space_size = array_sum($font->widthsForGlyphs(array(ord(' ')))) / $em * $font_size;
        foreach ($words as $i => $word) {
            $word .= ' ';
            $glyphs = array();
            foreach (range(0, strlen($word) - 1) as $i) {
                $value = isset($str[$i]) ? ord($str[$i]) : ' ';
                $glyphs[] = $value;
            }
            $words_lens[] = array_sum($font->widthsForGlyphs($glyphs)) / $em * $font_size;
        }
        //-- Push words onto lines to be drawn.
        $y_inc = $y;
        $x_inc = 0;
        $lines = array();
        $line = array();
        $i = 0;
        $max_length = count($words);
        while ($i < $max_length) {
            if (($x_inc + $words_lens[$i]) < $max_width) {
                $x_inc += $words_lens[$i] + $space_size;
                $line[] = $words[$i];
            } else {
                $lines[] = array($line, $x, $y_inc);
                $y_inc -= $font_size;
                $x_inc = 0;
                $line = array();
                $line[] = isset($words[$i + 1]) ? $words[$i + 1] : '';
                $i++;
            }
            $i++;
        }
        unset($words_lens);
        $lines[] = array($line, $x, $y_inc);
        //var_dump($lines); echo "<br/>";
        return array('width' => $max_width, 'height' => ($font_size * count($lines)), 'lines' => $lines);
    }

    public function drawVariableText($str, $x, $y, $max_width, $align='left') {
        $text = $this->getVariableText($str, $x, $y, $max_width);
        foreach ($text['lines'] as $line) {
            list($str, $x, $y) = $line;
            $x_pos = $x;
            if ($align == 'right') {
                $len = $this->calculateTextWidth(implode(' ', $str));
                $x_pos += $max_width - $len;
            } else if ($align == 'center') {
                $len = $this->calculateTextWidth(implode(' ', $str));
                $x_pos += ( $max_width - $len) / 2;
            }
            $this->drawText(implode(' ', $str), $x_pos, $y);
        }
        return array('width' => $max_width, 'height' => $text['height']);
    }

    public function calculateTextWidth($str) {
        $font = $this->getFont();
        $font_size = $this->getFontSize();
        //-- Find out each word's width
        $em = $font->getUnitsPerEm();
        $glyphs = array();
        if (strlen($str)>0) {
            foreach (range(0, strlen($str) - 1) as $i) {
                $glyphs[] = ord($str[$i]);
            }
        }
        return array_sum($font->widthsForGlyphs($glyphs)) / $em * $font_size;
    }

}
