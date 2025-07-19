<?php

namespace App\Helpers;

use Heriw\LaravelSimpleHtmlDomParser\HtmlDomParser;

class CleanHtml
{
    /**
     * Pulisce il testo HTML rimuovendo attributi e tag indesiderati.
     */
    public static function clean(string $testo): string
    {
        // @var object $html
        $html = HtmlDomParser::str_get_html($testo);

        // Rimuovere gli attributi non necessari da tutti i tag eccetto 'img' e 'a'
        // @phpstan-ignore-next-line
        foreach ($html->find('*') as $element) {
            if (! in_array($element->tag, ['img', 'a'])) {
                foreach ($element->getAllAttributes() as $attr => $val) {
                    $element->$attr = null;
                }
            }
        }

        // Per 'img', mantenere solo 'src', 'alt', 'width', 'height'
        // @phpstan-ignore-next-line
        foreach ($html->find('img') as $img) {
            foreach ($img->getAllAttributes() as $attr => $val) {
                if (! in_array($attr, ['src', 'alt', 'width', 'height'])) {
                    $img->$attr = null;
                }
            }
        }

        // Per 'a', mantenere solo 'href'
        // @phpstan-ignore-next-line
        foreach ($html->find('a') as $a) {
            foreach ($a->getAllAttributes() as $attr => $val) {
                if ($attr != 'href') {
                    $a->$attr = null;
                }
            }
        }

        // Rimuovere i tag 'span' mantenendo il loro contenuto
        // @phpstan-ignore-next-line
        foreach ($html->find('span') as $span) {
            $span->outertext = $span->innertext;
        }

        // Rimuovere i commenti
        // @phpstan-ignore-next-line
        foreach ($html->find('comment') as $comment) {
            $comment->outertext = '';
        }

        // Pulire l'HTML e restituirlo
        // @phpstan-ignore-next-line
        $htmlPulito = $html->save();
        $htmlPulito = preg_replace(['/&nbsp;/', "/\s+/", "/\u{A0}/"], ' ', $htmlPulito);

        return trim($htmlPulito);
    }
}
