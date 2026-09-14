import './bootstrap';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);
window.Chart = Chart;

window.history.scrollRestoration = 'manual';

window.resetPageScroll = () => {
    const mainEl = document.querySelector('main');

    if (mainEl) {
        mainEl.scrollTop = 0;
        mainEl.scrollLeft = 0;
    }

    window.scrollTo(0, 0);
    document.documentElement.scrollTop = 0;
    document.body.scrollTop = 0;
};

document.addEventListener('DOMContentLoaded', window.resetPageScroll);
document.addEventListener('livewire:navigated', window.resetPageScroll);
window.addEventListener('pageshow', window.resetPageScroll);
window.addEventListener('popstate', window.resetPageScroll);
