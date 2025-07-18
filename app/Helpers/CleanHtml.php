<?php

namespace App\Helpers;

use Heriw\LaravelSimpleHtmlDomParser\HtmlDomParser;

class CleanHtml
{
    /**
     * Pulisce il testo HTML rimuovendo attributi e tag indesiderati.
     *
     * @param string $testo
     * @return string
     *
     * @psalm-suppress MixedMethodCall
     * @phpstan-ignore-next-line
     */
    public static function clean($testo)
    {
        /** @var \simplehtmldom\simple_html_dom $html */
        $html = HtmlDomParser::str_get_html($testo);

        // Rimuovere gli attributi non necessari da tutti i tag eccetto 'img' e 'a'
        foreach ($html->find('*') as $element) {
            if (! in_array($element->tag, ['img', 'a'])) {
                foreach ($element->getAllAttributes() as $attr => $val) {
                    $element->$attr = null;
                }
            }
        }

        // Per 'img', mantenere solo 'src', 'alt', 'width', 'height'
        foreach ($html->find('img') as $img) {
            foreach ($img->getAllAttributes() as $attr => $val) {
                if (! in_array($attr, ['src', 'alt', 'width', 'height'])) {
                    $img->$attr = null;
                }
            }
        }

        // Per 'a', mantenere solo 'href'
        foreach ($html->find('a') as $a) {
            foreach ($a->getAllAttributes() as $attr => $val) {
                if ($attr != 'href') {
                    $a->$attr = null;
                }
            }
        }

        // Rimuovere i tag 'span' mantenendo il loro contenuto
        foreach ($html->find('span') as $span) {
            $span->outertext = $span->innertext;
        }

        // Rimuovere i commenti
        foreach ($html->find('comment') as $comment) {
            $comment->outertext = '';
        }

        // Pulire l'HTML e restituirlo
        $htmlPulito = $html->save();
        $htmlPulito = preg_replace(['/&nbsp;/', "/\s+/", "/\u{A0}/"], ' ', $htmlPulito);

        return trim($htmlPulito);
    }
}
