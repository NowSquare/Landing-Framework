<?php
namespace Platform\Controllers\Core;

class Parser extends \App\Http\Controllers\Controller {
  
  /*
  |--------------------------------------------------------------------------
  | Parser Controller
  |--------------------------------------------------------------------------
  |
  | Parser for all kind of strings
  |--------------------------------------------------------------------------
  */

  /**
   * \Platform\Controllers\Core\Parser::beautifyHtml($html);
   * Returns beutified html
   */
  public static function beautifyHtml($html, $ignoreA = false) {
    $indenter = new \Gajus\Dindent\Indenter(['indentation_character' => '  ']);
    $indenter->setElementType('style', \Gajus\Dindent\Indenter::ELEMENT_TYPE_BLOCK);
    $indenter->setElementType('label', \Gajus\Dindent\Indenter::ELEMENT_TYPE_INLINE);
    if (! $ignoreA) $indenter->setElementType('a', \Gajus\Dindent\Indenter::ELEMENT_TYPE_BLOCK);
    $indenter->setElementType('i', \Gajus\Dindent\Indenter::ELEMENT_TYPE_BLOCK);
    $indenter->setElementType('h1', \Gajus\Dindent\Indenter::ELEMENT_TYPE_INLINE);
    $indenter->setElementType('h2', \Gajus\Dindent\Indenter::ELEMENT_TYPE_INLINE);
    $indenter->setElementType('h3', \Gajus\Dindent\Indenter::ELEMENT_TYPE_INLINE);
    $indenter->setElementType('h4', \Gajus\Dindent\Indenter::ELEMENT_TYPE_INLINE);
    $indenter->setElementType('h5', \Gajus\Dindent\Indenter::ELEMENT_TYPE_INLINE);
    $indenter->setElementType('h6', \Gajus\Dindent\Indenter::ELEMENT_TYPE_INLINE);

    return $indenter->indent($html);
  }
}