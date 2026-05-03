import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    prepare(event) {
        const frame = event.currentTarget.dataset.frame;
        if (!frame) {
            return;
        }

        this.element.dataset.turboFrame = frame;
    }
}
