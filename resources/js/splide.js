/**
 * Splide.js - Libreria per la gestione di caroselli
 *
 * Questo file importa e configura Splide, una libreria JavaScript leggera e flessibile
 * per la creazione e gestione di caroselli/slider responsive. Splide è ottimizzato
 * per le prestazioni e offre un'esperienza fluida su dispositivi desktop e mobili.
 *
 * Caratteristiche principali:
 * - Completamente responsive
 * - Supporto per touch/swipe su dispositivi mobili
 * - Navigazione tramite frecce, paginazione e trascinamento
 * - Autoplay e pausa al passaggio del mouse
 * - Supporto per animazioni e transizioni personalizzabili
 * - Gestione avanzata di layout e breakpoint
 *
 * Utilizzo base:
 * const splide = new Splide('#il-tuo-elemento', {
 *   // opzioni di configurazione
 *   perPage: 3,
 *   gap: '1rem',
 *   autoplay: true
 * });
 * splide.mount();
 *
 * @see https://splidejs.com/ - Documentazione ufficiale
 */
import Splide from '@splidejs/splide';
import '@splidejs/splide/dist/css/themes/splide-default.min.css';

window.Splide = Splide;
