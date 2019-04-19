import { ModalConfirmation } from "./components/modal-confirmation";

document.querySelectorAll('.btn-outline-danger').forEach(
    (element, key) => {
        element.addEventListener('click', (new ModalConfirmation()).buildModalWithEvent);
    }
);